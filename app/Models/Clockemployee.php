<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clockemployee extends Model
{
   /**
     * Nome da tabela.
     */
    protected $table = 'clockemployees';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id',

        'employee_id',
        'employee_name',

        'journey_start_week',
        'journey_end_week',
        'journey_start_saturday',
        'journey_end_saturday',

        'allowance_total',
        'delay_total',
        'extra_total',
        'balance_total',

        'note',

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
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
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
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Funcionário.
        $employee = Employee::find($data['validatedData']['employee_id']);

        // Cadastra.
        Clockemployee::create([
            'clock_id'               => $data['validatedData']['clock_id'],
            'employee_id'            => $employee->id,
            'employee_name'          => Employee::find($employee->id)->name,
            'journey_start_week'     => $employee->journey_start_week,
            'journey_end_week'       => $employee->journey_end_week,
            'journey_start_saturday' => $employee->journey_start_saturday,
            'journey_end_saturday'   => $employee->journey_end_saturday,
        ]);

        // After.
        $after = Clockemployee::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $employee->id])->first();

        // Auditoria.
        Audit::clockemployeeAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->employee_name . ' cadastrado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAdd(array $data) : bool {
        // Funcionário do ponto.
        $clockemployee = Clockemployee::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Cadastra Clockday do Funcionário.
        $date = $clockemployee->clock->start;
        while($date <= $clockemployee->clock->end):
            // Define a Jornada.
            if(date_format(date_create($date), 'l') != 'Sunday'):
                if(date_format(date_create($date), 'l') != 'Saturday'):
                    $journey_start = $clockemployee->employee->journey_start_week;
                    $journey_end   = $clockemployee->employee->journey_end_week;
                    $journey_break = '01:00';
                else:
                    $journey_start = $clockemployee->employee->journey_start_saturday;
                    $journey_end   = $clockemployee->employee->journey_end_saturday;
                    $journey_break = null;
                endif;
            else:
                $journey_start = null;
                $journey_end   = null;
                $journey_break = null;
            endif;

            // Hidrata eventos.
            $events = Clockevent::where(['clock_id' => $clockemployee->clock->id, 'employee_id' => $clockemployee->employee->id, 'date' => $date])->get();

            if($events->count() > 0):
                // Input.
                $input = $events[0]->time;

                // Break Start.
                if(!empty($events[1])):
                    $break_start = $events[1]->time;
                else:
                    $break_start = null;
                endif;

                // Break End.
                if(!empty($events[2])):
                    $break_end = $events[2]->time;
                else:
                    $break_end = null;
                endif;

                // Output.
                if(!empty($events[3])):
                    $output = $events[3]->time;
                else:
                    $output = null;
                endif;
            else:
                $input         = null;
                $break_start   = null;
                $break_end     = null;
                $output        = null;
            endif;

            // Cadastra Clockday.
            Clockday::create([
                'clock_id'      => $clockemployee->clock->id,
                'employee_id'   => $clockemployee->employee->id,
                'date'          => $date,
                'input'         => $input,
                'break_start'   => $break_start,
                'break_end'     => $break_end,
                'output'        => $output,
                'journey_start' => $journey_start,
                'journey_end'   => $journey_end,
                'journey_break' => $journey_break,
            ]);

            $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));  
        endwhile;

        return true;
    }

    /**
     * Valida atualização de observação.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditNote(array $data) : bool {
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
     * Atualiza observação.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editNote(array $data) : bool {
        // Before.
        $before = Clockemployee::find($data['validatedData']['clockemployee_id']);

        // Atualiza.
        Clockemployee::find($data['validatedData']['clockemployee_id'])->update([
            'note' => Str::upper($data['validatedData']['note']),
        ]);

        // After.
        $after = Clockemployee::find($data['validatedData']['clockemployee_id']);

        // Auditoria.
        Audit::clockemployeeEditNote($data, $before, $after);

        // Mensagem.
        $message = 'Observação do Funcionário ' .  $after->employee_name . ' atualizada com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de atualização de observação.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEditNote(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Valida exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateErase(array $data) : bool {
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
     * Executa dependências de exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyErase(array $data) : bool {
        // Exclui os registros diários de funcionários vinculados ao ponto.
        Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id']])->delete();

        return true;
    }

    /**
     * Exclui.
     * @var array $data
     * 
     * @return bool true
     */
    public static function erase(array $data) : bool {
        // Exclui.
        Clockemployee::find($data['validatedData']['clockemployee_id'])->delete();

        // Auditoria.
        Audit::clockemployeeErase($data);

        // Mensagem.
        $message = 'Funcionário ' .  $data['validatedData']['employee_name'] . ' excluído deste ponto com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Gera relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function generatePdf(array $data) : bool {
        // Estende $data.
        $data['path']       = public_path('/storage/pdf/clockemployee/');
        $data['file_name']  = 'clockemployee_' . auth()->user()->id . '_' . $data['clock_id'] . '_' . $data['employee_id'] . '_' . Str::random(10) . '.pdf';

        // Gera PDF.
        Report::clockemployeeGenerate($data);

        // Auditoria
        Audit::clockemployeeGenerate($data);

        return true;
    }

    /**
     * Valida envio de e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateMail(array $data) : bool {
        $message = null;

        // Verifica conexão com a internet.
        if(checkdnsrr('google.com') < 1):
            $message = 'Sem conexão com a internet..';
        endif;

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return true;
    }

    /**
     * Envia e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function mail(array $data) : bool {
        // Envia e-mail.
        Email::clockemployeeMail($data);

        // Auditoria.
        Audit::clockemployeeMail($data);

        // Mensagem.
        $message = 'E-mail para ' . $data['validatedData']['mail'] . ' enviado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de envio de e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyMail(array $data) : bool {
        //...

        return true;
    }
}