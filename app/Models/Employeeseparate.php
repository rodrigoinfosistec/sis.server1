<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employeeseparate extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeeseparates';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',
        'employee_name',

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

        // Verifica se a data da Hora Avulsa já consta.
        if(Employeeseparate::where(['date' => $data['validatedData']['date'], 'employee_id' => $data['validatedData']['employee_id']])->exists()):
            $message = 'O dia ' . General::decodeDate($data['validatedData']['date']) . ' já consta em outra Hora Avulsa do  funcionário.';
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
        Employeeseparate::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => Employee::find($data['validatedData']['employee_id'])->name,
            'date'          => $data['validatedData']['date'],
            'time'          => $data['validatedData']['time'],
        ]);

        // After.
        $after = Employeeseparate::where(['date' => $data['validatedData']['date'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Auditoria.
        Audit::employeeseparateAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' do funcionário ' . $after->employee_name . ' cadastrada com sucesso.';
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
        // Define as Horas a serem descontadas.
        $m      = explode(':', $data['validatedData']['time']);
        $minuts = (($m[0] * 60) + $m[1]);

        // Atualiza Banco de Horas.
        $employee = Employee::find($data['validatedData']['employee_id']);

        Employee::find($data['validatedData']['employee_id'])->update([
            'datatime' => ($employee->datatime + ($minuts)),
        ]);

        // Registra Movimento do Banco de Horas.
        Clockbase::create([
            'user_id'     => Auth()->user()->id,
            'employee_id' => $data['validatedData']['employee_id'],
            'start'       => $data['validatedData']['date'],
            'end'         => $data['validatedData']['date'],
            'time'        => $minuts,
            'description' => 'Horas Avulsas',
        ]);

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
        // Subtrai Banco de Horas.
        if($data['validatedData']['time']):
            // Define as Horas a serem descontadas.
            $m = explode(':', $data['validatedData']['time']);
            $minuts = 0 - (($m[0] * 60) + $m[1]);

            // Atualiza Banco de Horas.
            $employee = Employee::find($data['validatedData']['employee_id']);

            Employee::find($data['validatedData']['employee_id'])->update([
                'datatime' => ($employee->datatime + ($minuts)),
            ]);

            // Registra Movimento do Banco de Horas.
            Clockbase::create([
                'user_id'     => Auth()->user()->id,
                'employee_id' => $data['validatedData']['employee_id'],
                'start'       => $data['validatedData']['date_encode'],
                'end'         => $data['validatedData']['date_encode'],
                'time'        => $minuts,
                'description' => 'Horas Avulsas Excluída',
            ]);
        endif;

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
        Employeeseparate::find($data['validatedData']['employeeseparate_id'])->delete();

        // Auditoria.
        Audit::employeeseparateErase($data);

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
        if($list = Employeeseparate::where([
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
        Report::employeeseparateGenerate($data);

        // Auditoria.
        Audit::employeeseparateGenerate($data);

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
        Email::employeeseparateMail($data);

        // Auditoria.
        Audit::employeeseparateMail($data);

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
