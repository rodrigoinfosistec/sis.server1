<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presencein extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'presenceins';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'company_name',
        'company_id',

        'user_id',
        'user_name',

        'date',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function company(){return $this->belongsTo(Company::class);}
    public function user(){return $this->belongsTo(User::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se Presença Entrada já existe para a Empresa.
        if(Presencein::where(['company_id' => Auth()->user()->company_id, 'date' => $data['validatedData']['date']])->exists()):
            $message = 'Presença Entrada em ' . General::decodeDate($data['validatedData']['date']);
        endif;

        // Verifica se não existe funcionário controlado para esta Empresa.
        if(Employee::where(['company_id' => Auth()->user()->company_id, 'limit_controll' => true, 'status' => true])->doesntExist()):
            $message = 'Nenhum Funcionário controlado para esta Empresa';
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
        $presencein_id = Presencein::create([
            'company_name' => $data['validatedData']['company_name'],
            'company_id'   => $data['validatedData']['company_id'],
            'user_id'      => $data['validatedData']['user_id'],
            'user_name'    => $data['validatedData']['user_name'],
            'date'         => $data['validatedData']['date'],
        ])->id;

        // After.
        $after = Presencein::find($presencein_id);

        // Auditoria.
        Audit::presenceinAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrada com sucesso.';
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
        $presencein = Presencein::where(['company_id' => Auth()->user()->company_id, 'date' => $data['validatedData']['date']])->first();

        // Percorre todos os funcionarios controlados.
        foreach(Employee::where(['company_id' => Auth()->user()->company_id, 'limit_controll' => true, 'status' => true])->get() as $key => $employee):
            // Vincula Funcionario na Presença Entrada.
            $presenceinemployee_id = Presenceinemployee::create([
                'presencein_id' => $presencein->id,
                'employee_id' => $employee->id,
                'employee_name' => $employee->name,
                'user_id' => Auth()->user()->id,
                'user_name' => Auth()->user()->name,
            ])->id;

            $after = Presenceinemployee::find($presenceinemployee_id);

            // Auditoria.
            Audit::presenceinemployeeAdd($data, $after);
        endforeach;

        return true;
    }
}
