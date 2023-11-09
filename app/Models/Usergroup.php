<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Usergroup extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'usergroups';

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
     * Força o cadastro do Grupo de Usuário "DEVELOPMENT".
     * 
     * @return bool true
     */
    public static function insertDevelopment() : bool {
        // Verifica se o Grupo de Usuário "DEVELOPMENT" não está cadastrado.
        if(Usergroup::where('name', 'DEVELOPMENT')->doesntExist()):
            // Cadastra o Grupo de Usuário "DEVELOPMENT".
            Usergroup::create([
                'name' => 'DEVELOPMENT',
            ]);
        endif;

        return true;
    }

    /**
     * Força o cadastro do Grupo de Usuário "ADMINISTRADOR".
     * 
     * @return bool true
     */
    public static function insertAdministrator() : bool {
        // Verifica se o Grupo de Usuário "ADMINISTRADOR" não está cadastrado.
        if(Usergroup::where('name', 'ADMINISTRADOR')->doesntExist()):
            // Cadastra o Grupo de Usuário "ADMINISTRADOR".
            Usergroup::create([
                'name' => 'ADMINISTRADOR',
            ]);
        endif;

        return true;
    }

    /**
     * Força o cadastro do Grupo de Usuário "FUNCIONARIO".
     * 
     * @return bool true
     */
    public static function insertEmployee() : bool {
        // Verifica se o Grupo de Usuário "FUNCIONARIO" não está cadastrado.
        if(Usergroup::where('name', 'FUNCIONARIO')->doesntExist()):
            // Cadastra o Grupo de Usuário "FUNCIONARIO".
            Usergroup::create([
                'name' => 'FUNCIONARIO',
            ]);
        endif;

        return true;
    }

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
        Usergroup::create([
            'name' => Str::upper($data['validatedData']['name']),
        ]);

        // After.
        $after = Usergroup::where('name', Str::upper($data['validatedData']['name']))->first();

        // Auditoria.
        Audit::usergroupAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->name . ' cadastrado com sucesso.';
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

        // Grupo de Usuário.
        $usergroup = Usergroup::find($data['validatedData']['usergroup_id']);

        // Verifica se Usuário não está Inativo.
        if(!$data['validatedData']['status']):
            // Verifica se algum Usuário já utiliza o Grupo de Produto.
            if(User::where(['usergroup_id' => $data['validatedData']['usergroup_id']])->exists()):
                $message = $data['config']['title'] . ' ' . $usergroup->name .' utilizado em usuário ' . User::where(['usergroup_id' => $data['validatedData']['usergroup_id']])->first()->name;
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
        $before = Usergroup::find($data['validatedData']['usergroup_id']);

        // Atualiza.
        Usergroup::find($data['validatedData']['usergroup_id'])->update([
            'name'   => Str::upper($data['validatedData']['name']),
            'status' => $data['validatedData']['status'],
        ]);

        // After.
        $after = Usergroup::find($data['validatedData']['usergroup_id']);

        // Auditoria.
        Audit::usergroupEdit($data, $before, $after);

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
        // Usuário.
        User::where(['usergroup_id' => $data['validatedData']['usergroup_id']])->update([
            'usergroup_name' => Str::upper($data['validatedData']['name']),
        ]);

        return true;
    }

    /**
     * Valida atualização de permissão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditPermission(array $data) : bool {
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
     * Atualiza permissão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editPermission(array $data) : bool {
        // Percorre páginas atualizáveis.
        foreach(Page::whereNotIn('name', ['home', 'logo'])->orderBy('title', 'ASC')->get() as $key => $page):
            $usergrouppage = Usergrouppage::where(['usergroup_id' => $data['usergroup_id'], 'page_id' => $page->id])->get();

            // Estende $data
            $data['page_id']   = $page->id;
            $data['page_name'] = $page->name;

            // Verifica se foi selecionada a Permissão.
            if($data['array_usergrouppage'][$page->id]):
                // Verifica se não existe a Permissão.
                if(Usergrouppage::where(['usergroup_id' => $data['usergroup_id'], 'page_id' => $page->id])->doesntExist()):
                    // Cadastra a Permissão.
                    Usergrouppage::add($data);
                endif;
            else:
                // Verifica se existe a Permissão.
                if(Usergrouppage::where(['usergroup_id' => $data['usergroup_id'], 'page_id' => $page->id])->exists()):
                    // Estende $data
                    $data['usergrouppage_id'] = Usergrouppage::where(['usergroup_id' => $data['usergroup_id'], 'page_id' => $page->id])->first()->id;

                    // Exclui a permissão.
                    Usergrouppage::erase($data);
                endif;
            endif;
        endforeach;

        // Mensagem.
        $message = 'Permissões do ' . $data['config']['title'] . ' ' .  Usergroup::find($data['usergroup_id'])->name . ' atualizadas com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de atualização de permissão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEditPermission(array $data) : bool {
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
        if(User::where(['usergroup_id' => $data['validatedData']['usergroup_id']])->exists()):
            $message = $data['config']['title'] . ' ' . Usergroup::find($data['validatedData']['usergroup_id'])->name . ' utilizado em usuário ' . User::where(['usergroup_id' => $data['validatedData']['usergroup_id']])->first()->name . '.';
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
        // Página vinculada à Grupo de Produto.
        Usergrouppage::where('usergroup_id', $data['validatedData']['usergroup_id'])->delete();

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
        Usergroup::find($data['validatedData']['usergroup_id'])->delete();

        // Auditoria.
        Audit::usergroupErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['name'] . ' excluído com sucesso.';
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
        if($list = Usergroup::where([
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
        Report::usergroupGenerate($data);

        // Auditoria.
        Audit::usergroupGenerate($data);

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
        Email::usergroupMail($data);

        // Auditoria.
        Audit::usergroupMail($data);

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
