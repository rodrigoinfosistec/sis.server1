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
        // Allowance.
        $allowance = Employeeallowance::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first();
        if($allowance):
            $time_allowance_simple = Clock::intervalMinuts($allowance->start, $allowance->end);
            $a = explode(':', $time_allowance_simple);
            $minuts_allowance = (($a[0] * 60) + $a[1]);

            // Justificado acresce 2h.
            if($allowance->merged) $minuts_allowance=+ 240;
    
            $a_hour  = $minuts_allowance / 60;
            $a_hour  = (int)$a_hour;
            $a_minut = $minuts_allowance % 60;
            $time_allowance = str_pad($a_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($a_minut, 2 ,'0' , STR_PAD_LEFT);
        else:
            $minuts_allowance = 0;
            $time_allowance  = '00:00';
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

        // Sábado.
        if(date_format(date_create($data['date']), 'l') == 'Saturday'):
            // Evita horários vazios.
            if($data['input'] && $data['output']):
                // Evita saída menor que entrada.
                if($data['output'] >= $data['input']):
                    // Define Jornada.
                    $time_journey   = Clock::intervalMinuts($data['journey_start'], $data['journey_end']);
                    $j = explode(':', $time_journey);
                    $minuts_journey = (($j[0] * 60) + $j[1]);

                    // Minutos trabalhados.
                    $time_day = Clock::intervalMinuts($data['input'], $data['output']);
                    $t = explode(':', $time_day);
                    $minuts_work = (($t[0] * 60) + $t[1]);

                    // Tempo trabalhado.
                    $hour  = $minuts_work / 60;
                    $hour  = (int)$hour;
                    $minut = $minuts_work % 60;
                    $time_work = str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);;

                    // Define os Horários.
                    $minuts_delay   = 0;
                    $minuts_extra   = 0;
                    $minuts_balance = 0;
                    $time_delay     = '00:00';
                    $time_extra     = '00:00';
                    $time_balance   = '00:00';
                    if($minuts_journey > ($minuts_work + 10)):
                        $minuts_work += 10;

                        // Atraso.
                        $minuts_delay = $minuts_journey - $minuts_work;
                        if($minuts_allowance >= $minuts_delay):
                            $minuts_delay = 0;
                        else:
                            $minuts_delay = $minuts_delay - $minuts_allowance;
                        endif;               

                        $d_hour  = $minuts_delay / 60;
                        $d_hour  = (int)$d_hour;
                        $d_minut = $minuts_delay % 60;
                        $time_delay = str_pad($d_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($d_minut, 2 ,'0' , STR_PAD_LEFT);
                    elseif($minuts_work > ($minuts_journey + 10)):
                        $minuts_journey += 10;

                        // Extras.
                        $minuts_extra = $minuts_work - $minuts_journey;
                        $e_hour  = $minuts_extra / 60;
                        $e_hour  = (int)$e_hour;
                        $e_minut = $minuts_extra % 60;
                        $time_extra = str_pad($e_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($e_minut, 2 ,'0' , STR_PAD_LEFT);
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
                    // Define Jornada.
                    $time_journey   = Clock::intervalMinuts($data['journey_start'], $data['journey_end']);
                    $j = explode(':', $time_journey);

                    // Define Intervalo.
                    $b = explode(':', $data['journey_break']);
                    $minuts_interval = (($b[0] * 60) + $b[1]);

                    // Minutos Jornada.
                    $minuts_journey = (($j[0] * 60) + $j[1]) - $minuts_interval;

                    // Define Períodos.
                    $time_morning   = Clock::intervalMinuts($data['input'], $data['break_start']);
                    $time_interval  = Clock::intervalMinuts($data['break_start'], $data['break_end']);
                    $time_afternoon = Clock::intervalMinuts($data['break_end'], $data['output']);

                    // Minutos trabalhados.
                    $m = explode(':', $time_morning);
                    $t = explode(':', $time_afternoon);
                    $i = explode(':', $time_interval);
                    $minuts_work = ((((($m[0] * 60) + $m[1]) + (($t[0] * 60) + $t[1])) + $minuts_interval) - (($i[0] * 60) + $i[1]));

                    // Tempo trabalhado.
                    $hour  = $minuts_work / 60;
                    $hour  = (int)$hour;
                    $minut = $minuts_work % 60;
                    $time_work = str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);

                    // Define os Horários.
                    $minuts_delay   = 0;
                    $minuts_extra   = 0;
                    $minuts_balance = 0;
                    $time_delay     = '00:00';
                    $time_extra     = '00:00';
                    $time_balance   = '00:00';
                    if($minuts_journey > ($minuts_work + 10)):
                        $minuts_work += 10;

                        // Atraso.
                        $minuts_delay = $minuts_journey - $minuts_work;
                        if($minuts_allowance >= $minuts_delay):
                            $minuts_delay = 0;
                        else:
                            $minuts_delay = $minuts_delay - $minuts_allowance;
                        endif;               

                        $d_hour  = $minuts_delay / 60;
                        $d_hour  = (int)$d_hour;
                        $d_minut = $minuts_delay % 60;
                        $time_delay = str_pad($d_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($d_minut, 2 ,'0' , STR_PAD_LEFT);
                    elseif($minuts_work > ($minuts_journey + 10)):
                        $minuts_journey += 10;

                        // Extras.
                        $minuts_extra = $minuts_work - $minuts_journey;
                        $e_hour  = $minuts_extra / 60;
                        $e_hour  = (int)$e_hour;
                        $e_minut = $minuts_extra % 60;
                        $time_extra = str_pad($e_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($e_minut, 2 ,'0' , STR_PAD_LEFT);
                    endif;
                else:
                    $authorized = false;
                endif;
            else:
                $authorized = false;
            endif;
        endif;

        if($authorized):
            // Balance.
            if($minuts_extra > $minuts_delay):
                // Minuts.
                $minuts_balance = $minuts_extra - $minuts_delay;

                // Time.
                $ba_hour  = $minuts_balance / 60;
                $ba_hour  = (int)$ba_hour;
                $ba_minut = $minuts_balance % 60;
                $time_balance = '+' . str_pad($ba_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($ba_minut, 2 ,'0' , STR_PAD_LEFT);
            elseif($minuts_delay > $minuts_extra):
                // Minuts.
                $minuts_balance = $minuts_delay - $minuts_extra;

                // Time.
                $ba_hour  = $minuts_balance / 60;
                $ba_hour  = (int)$ba_hour;
                $ba_minut = $minuts_balance % 60;
                $time_balance = '-' . str_pad($ba_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($ba_minut, 2 ,'0' , STR_PAD_LEFT);
            endif;

            // Atualiza.
            Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->update([
                'allowance'  => $time_allowance,
                'delay'      => $time_delay,
                'extra'      => $time_extra,
                'balance'    => $time_balance,
            ]);
        endif;

        if(date_format(date_create($data['date']), 'l') == 'Sunday') $authorized = true;
        if(Holiday::where(['date' => $data['date']])->first()) $authorized = true;
        if(Employeeeasy::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;
        if(Employeevacationday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;
        if(Employeeattestday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;
        if(Employeeabsenceday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->first()) $authorized = true;

        // Atualiza.
        Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->update([
            'authorized' => $authorized,
        ]);

        if(Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'authorized' => false])->doesntExist()):
            // Estende $data.
            $data['clock_id']         = $data['validatedData']['clock_id'];
            $data['employee_id']      = $data['validatedData']['employee_id'];
            $data['clockemployee_id'] = $data['validatedData']['clockemployee_id'];

            // Gera o PDF.
            Clockemployee::generatePdf($data);
        endif;

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
