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

        // Funcionário.
        $employee = Employee::find($data['validatedData']['employee_id']);

        // Verifica se este Registro já foi efetuado.
        if(Clockregistry::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date'], 'time' => $data['validatedData']['time']])->exists()):
            $message = 'Registro já efetuado em ' . date_format(date_create($data['validatedData']['date']), 'd/m/Y') . ' ' . $data['validatedData']['time'] . '.';
        endif;

        // Verifica se o tipo de registro do Funcionário é 'ALTERNATIVO'.
        if($employee->clock_type == 'REGISTRY'):
            $message = 'Sem permissão para utilizar esta função.';
        endif;

        // Verifica se data é domingo.
        if(date_format(date_create($data['validatedData']['date']),'l') == 'Sunday'):
            $message = 'Domingo: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se data é Feriado.
        if($employee->clock_type == 'REGISTRY'):
            $message = 'Feriado: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário está de Férias na data.
        if($employee->clock_type == 'REGISTRY'):
            $message = 'Fárias: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário está de Licença na data.
        if($employee->clock_type == 'REGISTRY'):
            $message = 'Licença médica: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário está de Atestado na data.
        if($employee->clock_type == 'REGISTRY'):
            $message = 'Atestado médico: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário Faltou na data.
        if($employee->clock_type == 'REGISTRY'):
            $message = 'Falta computada: Dia não autorizado para registrar ponto, será reportado à Gerência.';
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
