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
        $allowance ? $interval = Clock::intervalMinuts($allowance->start, $allowance->end) : $interval = '0:0';

        // Atualiza.
        Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'date' => $data['date']])->update([
            'input'         => $data['input'],
            'break_start'   => $data['break_start'],
            'break_end'     => $data['break_end'],
            'output'        => $data['output'],
            'journey_start' => $data['journey_start'],
            'journey_end'   => $data['journey_end'],
            'journey_break' => $data['journey_break'],
            'allowance'     => $interval,
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
                    $minuts_journey = (($j[0] * 60) + $j[1]);

                    // Define Intervalo.
                    $b = explode(':', $data['journey_end']);
                    $minuts_interval = (($b[0] * 60) + $b[1]);

                    // Define Períodos.
                    $time_morning   = Clock::intervalMinuts($data['input'], $data['break_start']);
                    $time_interval  = Clock::intervalMinuts($data['break_start'], $data['break_end']);
                    $time_afternoon = Clock::intervalMinuts($data['break_end'], $data['output']);

                    // Minutos trabalhados.
                    $m = explode(':', $time_morning);
                    $t = explode(':', $time_afternoon);
                    $i = explode(':', $time_interval);
                    $minuts_work = ((((($m[0] * 60) + $m[1]) + (($t[0] * 60) + $t[1])) + 60) - (($i[0] * 60) + $i[1]));

                    dd($minuts_work);
                else:
                    $authorized = false;
                endif;
            else:
                $authorized = false;
            endif;
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
