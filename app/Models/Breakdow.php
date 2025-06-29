<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Breakdow extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'breakdows';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'producebrand_name',
        'producebrand_id',
        'deposit_id',
        'deposit_name',
        'producemeasure_id',
        'producemeasure_name',
        'company_id',
        'company_name',

        'list_path',
        'status',
        'value',
        'description',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function producebrand(){return $this->belongsTo(Producebrand::class);}
    public function deposit(){return $this->belongsTo(Deposit::class);}
    public function producemeasure(){return $this->belongsTo(Producemeasure::class);}
    public function company(){return $this->belongsTo(Company::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        //

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
        $after_id = Breakdow::create([
            'producebrand_name'   => Producebrand::find($data['validatedData']['producebrand_id'])->name,
            'producebrand_id'     => $data['validatedData']['producebrand_id'],
            'deposit_id'          => $data['validatedData']['deposit_id'],
            'deposit_name'        => Deposit::find($data['validatedData']['deposit_id'])->name,
            'producemeasure_id'   => $data['validatedData']['producemeasure_id'],
            'producemeasure_name' => Producemeasure::find($data['validatedData']['producemeasure_id'])->name,
            'company_id'          => $data['validatedData']['company_id'],
            'company_name'        => Company::find($data['validatedData']['company_id'])->name,
            'value'               => General::encodeFloat2($data['validatedData']['value']),
            'description'         => $data['validatedData']['description'],
        ])->id;

        // After.
        $after = Breakdow::find($after_id);

        // Auditoria.
        Audit::breakdowAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->producebrand_name . ' cadastrada com sucesso.';
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

    /**
     * Valida cadastro TXT.
     * @var array $data
     * 
     * @return <array, bool>
     */
    public static function validateAddTxt(array $data){
        $message = null;

        // Salva arquivo, caso seja um txt.
        $txtArray = Report::txtBreakdow($data);

        // Verifica se é um arquivo txt.
        if(empty($txtArray)):
            $message = 'Arquivo deve ser um txt de colaborador (registro de ponto).';
        endif;

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return $txtArray;
    }

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
        // Before.
        $before = Breakdow::find($data['validatedData']['breakdow_id']);

        // Tratar pdf.

        $list_path = 'listtestedjhfshfsd.pdf';

        // Atualiza.
        Breakdow::find($data['validatedData']['breakdow_id'])->update([
            'list_path'=> $list_path,
            'status' => 'EMBALADO',
        ]);

        // After.
        $after = Breakdow::find($data['validatedData']['breakdow_id']);

        // Auditoria.
        Audit::breakdowEdit($data, $before, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $after->name . ' atualizado com sucesso.';
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
        // ...

        return true;
    }

    /**
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditDoc(array $data) : bool {
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
    public static function editDoc(array $data) : bool {
        // Before.
        $before = Breakdow::find($data['validatedData']['breakdow_id']);

        // Atualiza.
        Breakdow::find($data['validatedData']['breakdow_id'])->update([
            'cpf'  => $data['validatedData']['cpf'],
            'rg'   => $data['validatedData']['rg'],
            'cnh'  => $data['validatedData']['cnh'],
            'ctps' => $data['validatedData']['ctps'],
        ]);

        // After.
        $after = Breakdow::find($data['validatedData']['breakdow_id']);

        // Mensagem.
        $message = 'Documentos do ' . $data['config']['title'] . ' ' .  $after->name . ' atualizado com sucesso.';
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
    public static function dependencyEditDoc(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Valida atualização do Limite.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditLimit(array $data) : bool {
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
    public static function editLimit(array $data) : bool {
        // Before.
        $before = Breakdow::find($data['validatedData']['breakdow_id']);

        // Atualiza.
        Breakdow::find($data['validatedData']['breakdow_id'])->update([
            'limit_start_week'     => General::timeToMinuts($data['validatedData']['limit_start_week']),
            'limit_end_week'       => General::timeToMinuts($data['validatedData']['limit_end_week']),
            'limit_start_saturday' => General::timeToMinuts($data['validatedData']['limit_start_saturday']),
            'limit_end_saturday'   => General::timeToMinuts($data['validatedData']['limit_end_saturday']),
        ]);

        // After.
        $after = Breakdow::find($data['validatedData']['breakdow_id']);

        // Auditoria.
        Audit::breakdowEditLimit($data, $before, $after);

        // Mensagem.
        $message = 'Limites de Ponto do ' . $data['config']['title'] . ' ' .  $after->name . ' atualizados com sucesso.';
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
    public static function dependencyEditLimit(array $data) : bool {
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

        $breakdow = Breakdow::find($data['validatedData']['breakdow_id']);

        // 

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
        Breakdow::find($data['validatedData']['breakdow_id'])->delete();

        // Auditoria.
        Audit::breakdowErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['producebrand_name'] . ' excluída com sucesso.';
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
        if($list = Breakdow::where([
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
        Report::breakdowGenerate($data);

        // Auditoria.
        Audit::breakdowGenerate($data);

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
        Email::breakdowMail($data);

        // Auditoria.
        Audit::breakdowMail($data);

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
