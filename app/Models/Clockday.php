<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clockday extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockdays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id',
        'employee_id',

        'date',

        'input',
        'break_start',
        'break_end',
        'output',

        'journey_start',
        'journey_end',
        'journey_break',

        'allowance',
        'delay',
        'extra',
        'balance',

        'authorized',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function clock(){return $this->belongsTo(Clock::class);}
    public function employee(){return $this->belongsTo(Employee::class);}

    /**
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEdit(array $data) : bool {
        $message = null;

        // ...

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return true;
    }

    /**
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Inicializa Allowance.
        $allowance_real = 0;
        $minuts_al      = 0;

        // Allowance.
        $allowance = Employeeallowance::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first();
        if($allowance):
            // Converte o abono em minutos.
            $minuts_as = General::timeToMinuts($allowance->start);
            $minuts_ae = General::timeToMinuts($allowance->end);

            // Abono Minutos.
            $allowance_real = $minuts_ae - $minuts_as;

            // Justificado.
            if($allowance->merged):
                $minuts_as -= 60;
                $minuts_ae += 60;
            endif;

            $minuts_al = $minuts_ae - $minuts_as;
        endif;

        // Atualiza.
        Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->update([
            'input'         => $data['input'],
            'break_start'   => $data['break_start'],
            'break_end'     => $data['break_end'],
            'output'        => $data['output'],
            'journey_start' => $data['journey_start'],
            'journey_end'   => $data['journey_end'],
            'journey_break' => $data['journey_break'],
        ]);

        // Inicializa $authorized.
        $authorized = true;

        // Inicializa variáveis.
        $minuts_delay   = 0;
        $minuts_extra   = 0;

        // Sábado.
        if(date_format(date_create($data['date']), 'l') == 'Saturday'):
            // Evita horários vazios.
            if($data['input'] && $data['output']):
                // Evita saída menor que entrada.
                if($data['output'] >= $data['input']):
                    // Converte jornada para minutos.
                    $minuts_js = General::timeToMinuts($data['journey_start']);
                    $minuts_je = General::timeToMinuts($data['journey_end']);

                    // Converte Registros em minutos.
                    $minuts_r_in = General::timeToMinuts($data['input']);
                    $minuts_r_ou = General::timeToMinuts($data['output']);

                    // verifica se existe abono.
                    if($minuts_al > 0):
                        // Verifica se período do abono está fora do expediente.
                        if(($minuts_as < $minuts_js) && ($minuts_ae >= $minuts_js)):
                            $minuts_as = $minuts_js;
                        elseif(($minuts_ae > $minuts_je) && ($minuts_as <= $minuts_je)):
                            $minuts_ae = $minuts_je;
                        else:
                            $minuts_as = 0;
                            $minuts_ae = 0;
                        endif;

                        // Abono válido para fins de cálculo.
                        $minuts_al = $minuts_ae - $minuts_as;
                    endif;

                    // Analisa Entrada.
                    if(($minuts_r_in - 5) > $minuts_js):
                        // Incrementa atraso, caso exista.
                        $minuts_delay += (($minuts_r_in - 5) - $minuts_js);

                        // Decrementa atraso, no caso abono.
                        if($minuts_al > 0):
                            if($minuts_al >= $minuts_delay):
                                $minuts_al -= $minuts_delay;
                                $minuts_delay = 0;
                            else:
                                $minuts_delay -= $minuts_al;
                                $minuts_al = 0;
                            endif;
                        endif;
                    elseif(($minuts_r_in + 5) < $minuts_js):
                        // Incrementa extra.
                        $minuts_extra += ($minuts_js - ($minuts_r_in + 5));
                    endif;

                    // Analisa Saída.
                    if($minuts_je > ($minuts_r_ou + 5)):
                        // Incrementa atraso, caso exista.
                        $minuts_delay += ($minuts_je - ($minuts_r_ou + 5));

                        // Decrementa atraso, no caso abono.
                        if($minuts_al > 0):
                            if($minuts_al >= $minuts_delay):
                                $minuts_al -= $minuts_delay;
                                $minuts_delay = 0;
                            else:
                                $minuts_delay -= $minuts_al;
                                $minuts_al = 0;
                            endif;
                        endif;
                    elseif($minuts_je < ($minuts_r_ou - 5)):
                        // Incrementa extra.
                        $minuts_extra += (($minuts_r_ou - 5) - $minuts_je);
                    endif;

                    // Saldo.
                    $minuts_ba = $minuts_extra - $minuts_delay;

                    // Sinal.
                    if($minuts_ba > 0):     
                        $signal = '+';
                    elseif($minuts_ba < 0): 
                        $signal = '-';
                    else:                  
                         $signal = ''; 
                    endif;  
                else:
                    $authorized = false;
                endif;
            else:
                $authorized = false;
            endif;

        // Não Sábado.
        else:
            // Evita horários vazios.
            if($data['input'] && $data['break_start'] && $data['break_end'] && $data['output']):
                // Evita pausa inicial menor que entrada.
                if($data['break_start'] >= $data['input'] && $data['break_end'] >= $data['break_start'] && $data['output'] >= $data['break_end']):
                    // Converte jornada para minutos.
                    $minuts_js = General::timeToMinuts($data['journey_start']);
                    $minuts_je = General::timeToMinuts($data['journey_end']);
                    $minuts_jb = General::timeToMinuts($data['journey_break']);

                    // Converte Registros em minutos.
                    $minuts_r_in = General::timeToMinuts($data['input']);
                    $minuts_r_bs = General::timeToMinuts($data['break_start']);
                    $minuts_r_be = General::timeToMinuts($data['break_end']);
                    $minuts_r_br = $minuts_r_be - $minuts_r_bs;
                    $minuts_r_ou = General::timeToMinuts($data['output']);

                    // verifica se existe abono.
                    if($minuts_al > 0):
                        // Verifica se período do abono está fora do expediente.
                        if(($minuts_as < $minuts_js) && ($minuts_ae >= $minuts_js)):
                            $minuts_as = $minuts_js;
                        elseif(($minuts_ae > $minuts_je) && ($minuts_as <= $minuts_je)):
                            $minuts_ae = $minuts_je;
                        else:
                            $minuts_as = 0;
                            $minuts_ae = 0;
                        endif;
                    endif;

                    // Analisa Entrada.
                    if(($minuts_r_in - 5) > $minuts_js):
                        // Incrementa atraso, caso exista.
                        $minuts_delay += (($minuts_r_in - 5) - $minuts_js);

                        // Decrementa atraso, no caso abono.
                        if($minuts_al > 0):
                            if($minuts_al >= $minuts_delay):
                                $minuts_al -= $minuts_delay;
                                $minuts_delay = 0;
                            else:
                                $minuts_delay -= $minuts_al;
                                $minuts_al = 0;
                            endif;
                        endif;
                    elseif(($minuts_r_in + 5) < $minuts_js):
                        // Incrementa extra.
                        $minuts_extra += ($minuts_js - ($minuts_r_in + 5));
                    endif;

                    // Analisa Intervalo.
                    if($minuts_r_br > $minuts_jb):
                        // Incrementa atraso.
                        $minuts_delay += ($minuts_r_br - $minuts_jb);

                        // Decrementa atraso, no caso abono.
                        if($minuts_al > 0):
                            if($minuts_al >= $minuts_delay):
                                $minuts_al -= $minuts_delay;
                                $minuts_delay = 0;
                            else:
                                $minuts_delay -= $minuts_al;
                                $minuts_al = 0;
                            endif;
                        endif;
                    elseif($minuts_r_br < $minuts_jb):
                        // Incrementa extra.
                        $minuts_extra += ($minuts_jb - $minuts_r_br);
                    endif;

                    // Analisa Saída.
                    if($minuts_je > ($minuts_r_ou + 5)):
                        // Incrementa atraso, caso exista.
                        $minuts_delay += ($minuts_je - ($minuts_r_ou + 5));

                        // Decrementa atraso, no caso abono.
                        if($minuts_al > 0):
                            if($minuts_al >= $minuts_delay):
                                $minuts_al    -= $minuts_delay;
                                $minuts_delay = 0;
                            else:
                                $minuts_delay -= $minuts_al;
                                $minuts_al    = 0;
                            endif;
                        endif;
                    elseif($minuts_je < ($minuts_r_ou - 5)):
                        // Incrementa extra.
                        $minuts_extra += (($minuts_r_ou - 5) - $minuts_je);
                    endif;

                    // Saldo.
                    $minuts_ba = $minuts_extra - $minuts_delay;

                    //dd($minuts_ba);

                    // Sinal.
                    if($minuts_ba > 0):     
                        $signal = '+';
                    elseif($minuts_ba < 0): 
                        $signal = '-';
                    else:                  
                         $signal = ''; 
                    endif;
                else:
                    $authorized = false;
                endif;
            else:
                $authorized = false;
            endif;
        endif;

        if($authorized):
            // Time Abono.
            $hour  = $allowance_real / 60;
            $hour  = (int)$hour;
            $minut = $allowance_real % 60;
            $time_al = str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);

            // Time Atraso.
            $hour  = $minuts_delay / 60;
            $hour  = (int)$hour;
            $minut = $minuts_delay % 60;
            $time_delay = str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);

            // Time Extra.
            $hour  = $minuts_extra / 60;
            $hour  = (int)$hour;
            $minut = $minuts_extra % 60;
            $time_extra = str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);

            // Time Saldo.
            $hour  = $minuts_ba / 60;
            $hour  = (int)$hour;
            $minut = $minuts_ba % 60;
            $time_ba = $signal . str_pad(abs($hour), 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad(abs($minut), 2 ,'0' , STR_PAD_LEFT);

            // Atualiza.
            Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->update([
                'allowance'  => $time_al,
                'delay'      => $time_delay,
                'extra'      => $time_extra,
                'balance'    => $time_ba,
            ]);
        endif;

        if(date_format(date_create($data['date']), 'l') == 'Sunday') $authorized = true;
        if(Holiday::where(['date' => $data['date']])->first()) $authorized = true;
        if(Employeeeasy::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;
        if(Employeevacationday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;
        if(Employeeattestday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;
        if(Employeelicenseday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;
        if(Employeeabsenceday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;

        // Atualiza.
        Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->update([
            'authorized' => $authorized,
        ]);

        // After.
        $after = Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first();

        // Mensagem.
        $message = 'Horas do funcionário ' . $after->employee->name . ' atualizadas com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEdit(array $data) : bool {
        //...

        return true;
    }

}
