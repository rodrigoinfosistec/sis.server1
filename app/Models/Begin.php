<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Begin extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'begins';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'initialize',

        'created_at',
        'updated_at',
    ];

    /**
     * Inicialização do software na view login(/)
     * 
     * @return bool true
     */
    public static function initialize() : bool {
        if(Begin::doesntExist()):
            /**
             * Registra inicialização.
             */
            Begin::create([
                'initialize' => true
            ]);

            /**
             * Insere todas as Páginas.
             */
            Page::insertPages();

            /**
             * Insere o Grupo de Usuário "DEVELOPMENT".
             */
            Usergroup::insertDevelopment();

            /**
             * Relaciona o Grupo de Usuário "DEVELOPMENT" com todas as páginas.
             */
            Usergrouppage::relatesDevelopmentPages();

            /**
             * Insere o Grupo de Usuário ADMINISTRADOR.
             */
            Usergroup::insertAdministrator();

            /**
             * Relaciona o Grupo de Usuário ADMINISTRADOR com todas as Páginas a ele permitidas.
             */
            Usergrouppage::relatesAdministratorPages();

            /**
             * Insere o Grupo de Usuário 'FUNCIONARIO'.
             */
            Usergroup::insertEmployee();

            /**
             * Relaciona o Grupo de Usuário 'FUNCIONARIO' com a Página 'Detalhes Funcionário'.
             */
            Usergrouppage::relatesEmployeePage();

            /**
             * Insere o usuário "MASTER".
             */
            User::insertMaster();

            /**
             * Insere a Negociação Padrão com Fornecedores.
             */
            Providerbusinessdefault::businessDefault();
        endif;

        return true;
    }

}
