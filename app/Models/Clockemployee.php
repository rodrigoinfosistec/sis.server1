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
            'employee_name'          => $employee->name,
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
        //...

        return true;
    }

}