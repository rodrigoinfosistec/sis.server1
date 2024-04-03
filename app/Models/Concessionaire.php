<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Concessionaire extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'concessionaires';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'status',

        'created_at',
        'updated_at',
    ];

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
        // Cadastra.
        Concessionaire::create([
            'name' => Str::upper($data['validatedData']['name']),
        ]);

        // After.
        $after = Concessionaire::where('name', Str::upper($data['validatedData']['name']))->first();

        // Auditoria.
        Audit::concessionaireAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->name . ' cadastrada com sucesso.';
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
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEdit(array $data) : bool {
        $message = null;

        // Concessionária.
        $concessionaire = Concessionaire::find($data['validatedData']['concessionaire_id']);

        // Verifica se Usuário não está Inativo.
        if(!$data['validatedData']['status']):
            // Verifica se algum Usuário já utiliza o Grupo de Produto.
            if(User::where(['concessionaire_id' => $data['validatedData']['concessionaire_id']])->exists()):
                $message = $data['config']['title'] . ' ' . $concessionaire->name .' utilizado em usuário ' . User::where(['concessionaire_id' => $data['validatedData']['concessionaire_id']])->first()->name;
            endif;
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
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Before.
        $before = Concessionaire::find($data['validatedData']['concessionaire_id']);

        // Atualiza.
        Concessionaire::find($data['validatedData']['concessionaire_id'])->update([
            'name'   => Str::upper($data['validatedData']['name']),
            'status' => $data['validatedData']['status'],
        ]);

        // After.
        $after = Concessionaire::find($data['validatedData']['concessionaire_id']);

        // Auditoria.
        Audit::concessionaireEdit($data, $before, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $after->name . ' atualizada com sucesso.';
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
     * Valida exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateErase(array $data) : bool {
        $message = null;

        // Usuário.
        if(User::where(['concessionaire_id' => $data['validatedData']['concessionaire_id']])->exists()):
            $message = $data['config']['title'] . ' ' . Concessionaire::find($data['validatedData']['concessionaire_id'])->name . ' utilizado em usuário ' . User::where(['concessionaire_id' => $data['validatedData']['concessionaire_id']])->first()->name . '.';
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
        Concessionaire::find($data['validatedData']['concessionaire_id'])->delete();

        // Auditoria.
        Audit::concessionaireErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['name'] . ' excluída com sucesso.';
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
        if($list = Concessionaire::where([
            [$data['filter'], 'like', '%'. $data['search'] . '%'],
            ['name', '!=', 'DEVELOPMENT'],
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

        // Gera o PDF.
        Report::concessionaireGenerate($data);

        // Auditoria.
        Audit::concessionaireGenerate($data);

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
        Email::concessionaireMail($data);

        // Auditoria.
        Audit::concessionaireMail($data);

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
