<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employeeallowance extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeeallowances';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',
        'employee_name',

        'date',

        'start',
        'end',

        'merged',

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

        // Verifica se final da jornada da semana é maior que o início.
        if($data['validatedData']['start'] >= $data['validatedData']['end']):
            $message = 'Horário final do abono deve ser maior que o horário inicial do abono.';
        endif;

        // Verifica se a data do abono já consta em outro abono.
        if(Employeeallowance::where(['date' => $data['validatedData']['date'], 'employee_id' => $data['validatedData']['employee_id']])->exists()):
            $message = 'O dia ' . General::decodeDate($data['validatedData']['date']) . ' já consta em outro abono do funcionário.';
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
        Employeeallowance::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => Employee::find($data['validatedData']['employee_id'])->name,
            'date'          => $data['validatedData']['date'],
            'start'         => $data['validatedData']['start'],
            'end'           => $data['validatedData']['end'],
            'merged'        => $data['validatedData']['merged'],
        ]);

        // After.
        $after = Employeeallowance::where(['date' => $data['validatedData']['date'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Auditoria.
        Audit::employeeallowanceAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' do funcionário ' . $after->employee_name . ' cadastrado com sucesso.';
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
        // ...

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
        Employeeallowance::find($data['validatedData']['employeeallowance_id'])->delete();

        // Auditoria.
        Audit::employeeallowanceErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' do funcionário ' .  $data['validatedData']['employee_name'] . ' excluída com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Valida geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateGenerate(array $data) : bool {
        $message = null;

        // verifica se existe algum item retornado na pesquisa.
        if($list = Employeeallowance::where([
                [$data['filter'], 'like', '%'. $data['search'] . '%'],
            ])->doesntExist()):

            $message = 'Nenhum ítem selecionado.';
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
     * Gera relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function generate(array $data) : bool {
        // Estende $data.
        $data['path']      = public_path('/storage/pdf/' . $data['config']['name'] . '/');
        $data['file_name'] = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.pdf';

        // Gera PDF.
        Report::employeeallowanceGenerate($data);

        // Auditoria.
        Audit::employeeallowanceGenerate($data);

        // Mensagem.
        $message = 'Relatório PDF gerado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyGenerate(array $data) : bool {
        //...

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
            $message = 'Sem conexão com a internet.';
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
        Email::employeeallowanceMail($data);

        // Auditoria.
        Audit::employeeallowanceMail($data);

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
