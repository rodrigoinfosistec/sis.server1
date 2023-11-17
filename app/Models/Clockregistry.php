<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

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

        // Verifica se este Registro já foi efetuado.
        if(Clockregistry::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date'], 'time' => $data['validatedData']['time']])->exists()):
            $message = 'Registro já efetuado em ' . date_format(date_create($data['validatedData']['date']), 'd/m/Y') . ' ' . $data['validatedData']['time'] . '.';
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
        // Cadastra.
        Clockregistry::create([
            'employee_id' => $data['validatedData']['employee_id'],
            'date'        => $data['validatedData']['date'],
            'time'        => $data['validatedData']['time'],
        ]);

        // After.
        $after = Clockregistry::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date'], 'time' => $data['validatedData']['time']])->first();

        // Auditoria.
        Audit::clockregistryAdd($data, $after);

        // Mensagem.
        $message = 'Registro de ponto em ' . date_format(date_create($data['validatedData']['date']), 'd/m/Y') . ' ' . $data['validatedData']['time'] . ' cadastrado com sucesso.';
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
