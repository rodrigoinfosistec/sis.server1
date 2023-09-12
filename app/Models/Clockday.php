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
                    // Dia.
                    $time_default = '4:0';
                    $time_day = Clock::intervalMinuts($data['input'], $data['output']);
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
                if($data['break_start'] >= $data['input']):
                    // Manhã
                    $time_morning = Clock::intervalMinuts($data['input'], $data['break_start']);
                else:
                    $authorized = false;
                endif;

                // Evita pausa final menor que pausa inicial.
                if($data['break_end'] >= $data['break_start']):
                    // Intervalo
                    $time_interval = Clock::intervalMinuts($data['break_start'], $data['break_end']);
                else:
                    $authorized = false;
                endif;

                // Evita saída menor que pausa final.
                if($data['output'] >= $data['break_end']):
                    // Tarde.
                    $time_afternoon = Clock::intervalMinuts($data['break_end'], $data['output']);
                else:
                    $authorized = false;
                endif;
            else:
                $authorized = false;
            endif;
        endif;

        // Sábado.
        if($authorized && !empty($time_day)):
            // Tempo do dia.
            $time_day = $time_day;

        // Não Sábado.
        elseif($authorized):
            

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
