<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usergrouppage extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'usergrouppages';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'usergroup_id',
        'page_id',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function usergroup(){return $this->belongsTo(Usergroup::class);}
    public function Page(){return $this->belongsTo(Page::class);}

    /**
     * Relaciona o Grupo de Usuário "DEVELOPMENT" com todas as páginas.
     * 
     * @return bool true
     */
    public static function relatesDevelopmentPages() : bool {
        // Percorre todas as Páginas.
        foreach(Page::get() as $key => $page):
            // Verifica se o Grupo de Usuário "DEVELOPMENT" não está cadastrado.
            if(Usergroup::where('name', 'DEVELOPMENT')->doesntExist()):
                // Cadastra o Grupo de Usuário "DEVELOPMENT".
                Usergroup::insertDevelopment();
            endif;

            // Verifica se a Página já não está vinculada ao Grupo de Usuário "DEVELOPMENT".
            if(Usergrouppage::where(['usergroup_id' => Usergroup::where('name', 'DEVELOPMENT')->first()->id, 'page_id' => $page->id])->doesntExist()):
                // Cadastra Página para o Grupo de Usuário "DEVELOPMENT".
                Usergrouppage::create([
                    'usergroup_id' => Usergroup::where('name', 'DEVELOPMENT')->first()->id, 
                    'page_id'      => $page->id
                ]);
            endif;
        endforeach;

        return true;
    }

    /**
     * Relaciona o Grupo de Usuário "ADMINISTRADOR" com todas as páginas.
     * 
     * @return bool true
     */
    public static function relatesAdministratorPages() : bool {
        // Percorre todas as Páginas.
        foreach(Page::get() as $key => $page):
            // Verifica se o Grupo de Usuário "ADMINISTRADOR" não está cadastrado.
            if(Usergroup::where('name', 'ADMINISTRADOR')->doesntExist()):
                // Cadastra o Grupo de Usuário "ADMINISTRADOR".
                Usergroup::insertAdministrator();
            endif;

            // Verifica se a Página já não está vinculada ao Grupo de Usuário "ADMINISTRADOR".
            if(Usergrouppage::where(['usergroup_id' => Usergroup::where('name', 'ADMINISTRADOR')->first()->id, 'page_id' => $page->id])->doesntExist()):
                // Cadastra Página para o Grupo de Usuário "ADMINISTRADOR".
                Usergrouppage::create([
                    'usergroup_id' => Usergroup::where('name', 'ADMINISTRADOR')->first()->id, 
                    'page_id'      => $page->id
                ]);
            endif;
        endforeach;

        return true;
    }

    /**
     * Relaciona o Grupo de Usuário "FUNCIONARIO" com a página "Detaçhes Funcionário".
     * 
     * @return bool true
     */
    public static function relatesEmployeePage() : bool {
        // Verifica se o Grupo de Usuário "FUNCIONARIO" não está cadastrado.
        if(Usergroup::where('name', 'FUNCIONARIO')->doesntExist()):
            // Cadastra o Grupo de Usuário "FUNCIONARIO".
            Usergroup::insertEmployee();
        endif;

        // Página "Detalhes Funcionário".
        $page = Page::where('name', 'employeebase')->first();

        // Verifica se a Página já não está vinculada ao Grupo de Usuário "FUNCIONARIO".
        if(Usergrouppage::where(['usergroup_id' => Usergroup::where('name', 'FUNCIONARIO')->first()->id, 'page_id' => $page->id])->doesntExist()):
            // Cadastra Página para o Grupo de Usuário "FUNCIONARIO".
            Usergrouppage::create([
                'usergroup_id' => Usergroup::where('name', 'FUNCIONARIO')->first()->id, 
                'page_id'      => $page->id
            ]);
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
        $usergrouppage_id = Usergrouppage::create([
            'usergroup_id' => $data['usergroup_id'],
            'page_id'      => $data['page_id'],
        ])->id;

        // Estende $data
        $data['usergrouppage_id'] = $usergrouppage_id;

        // After
        $after = Usergrouppage::find($usergrouppage_id);

        // Auditoria.
        Audit::usergrouppageAdd($data, $after);

        return true;
    }

    /**
     * Exclui.
     * @var array $data
     * 
     * @return bool true
     */
    public static function erase(array $data) : bool {
        // Before
        $before = Usergrouppage::find($data['usergrouppage_id']);

        // Exclui.
        Usergrouppage::find($data['usergrouppage_id'])->delete();

        // Auditoria.
        Audit::usergrouppageErase($data, $before);

        return true;
    }
}
