<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employeepay extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeepays';

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

        // Verifica se a data do pagamento já consta em outro pagamento.
        if(Employeepay::where(['date' => $data['validatedData']['date'], 'employee_id' => $data['validatedData']['employee_id']])->exists()):
            $message = 'O dia ' . General::decodeDate($data['validatedData']['date']) . ' já consta em outro pagamento para o  funcionário.';
        endif;

        // Verifica se o tempo não estão zerados.
        if((int)$data['validatedData']['time_old'] == 0 && (int)$data['validatedData']['minut'] == 0):
            $message = 'Tempo não pode está zerado.';
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
        Employeepay::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => Employee::find($data['validatedData']['employee_id'])->name,
            'date'          => $data['validatedData']['date'],
            'time'          => $data['validatedData']['time'],
        ]);

        // After.
        $after = Employeepay::where(['date' => $data['validatedData']['date'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Auditoria.
        Audit::employeepayAdd($data, $after);

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
        // Define as Horas a serem descontadas.
        $m      = explode(':', $data['validatedData']['time']);
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
            'start'       => $data['validatedData']['date'],
            'end'         => $data['validatedData']['date'],
            'time'        => $minuts,
            'description' => 'Pagamento de Horas (R$)',
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
            $minuts = ($m[0] * 60) + $m[1];

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
                'description' => 'Pagamento de Horas (R$) Excluído',
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
        Employeepay::find($data['validatedData']['employeepay_id'])->delete();

        // Auditoria.
        Audit::employeepayErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' do funcionário ' .  $data['validatedData']['employee_name'] . ' excluído com sucesso.';
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
        if($list = Employeepay::where([
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
        Report::employeepayGenerate($data);

        // Auditoria.
        Audit::employeepayGenerate($data);

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
        Email::employeepayMail($data);

        // Auditoria.
        Audit::employeepayMail($data);

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
