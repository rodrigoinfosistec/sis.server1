<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clockregistry extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockregistries';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',

        'date',
        'time',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employee(){return $this->belongsTo(Employee::class);}

    
    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se existe algum Funcionário com o código.
        if(Employee::where('code', $data['validatedData']['code'])->doesntExist()):
            $message = 'Código inválido';
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
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Inicializa vaeiáveis.
        $employee_id = Employee::where('code', $data['validatedData']['code'])->first()->id;
        $date = date('Y-m-d');
        $time = date('H:i');

        // Cadastra.
        Clockregistry::create([
            'employee_id' => $employee_id,
            'date'        => $date,
            'time'        => $time,
        ]);

        // After.
        $after = Clockregistry::where(['employee_id' => $employee_id, 'date' => $date, 'time' => $time])->first();

        // Auditoria.
        Audit::clockregistryAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' do Funcionário ' . $after->employee->name . ' cadastrado com sucesso.';
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
