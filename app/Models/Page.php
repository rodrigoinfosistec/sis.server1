<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'pages';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',
        'title',
        'icon',
        'test',

        'created_at',
        'updated_at',
    ];

    /**
     * Busca o Título pelo nome.
     * @var string $name
     * 
     * @return string $title
     */
    public static function getTitleByName(string $name) : string {
        (string)$title = Page::where('name',  $name)->first()->title;

        return $title;
    }

    /**
     * Busca o Ícone pelo nome.
     * @var string $name
     * 
     * @return string $icon
     */
    public static function getIconByName(string $name) : string {
        (string)$icon = Page::where('name',  $name)->first()->icon;

        return $icon;
    }

    /**
     * Cadastra as páginas.
     */
    public static function insertPages(){
        // Percorre as páginas configuradas.
        foreach(Config::getPages() as $key => $page):
            // Verifica se a página não está cadastrada.
            if(Page::where('name', $page['name'])->doesntExist()):
                // Cadastra as páginas.
                Page::create([
                    'name'  => $page['name'],
                    'title' => $page['title'],
                    'icon'  => $page['icon'],
                    'test'  => $page['test'],
                ]);
            endif;
        endforeach;

        return true;
    }

    /**
     * Valida autoriação de usuário para acessar página.
     * @var string $pageName
     * 
     * @return bool status
     */
    public static function userAuthorized(string $pageName) : bool {
        // Grupo de Usuário.
        $usergroup = Usergroup::where(['id' => Auth()->user()->usergroup_id, 'status' => true])->first();

        // Página.
        $page = Page::where('name', $pageName)->first();

        // Verifica se o usuário tem autorização para acessar a página.
        if(($page->name == 'home' || !$page->test) || ( ($page->test) && (Usergrouppage::where(['usergroup_id' => $usergroup->id, 'page_id' => $page->id])->exists()) ) ):
            // Autoriza acesso à página.
            return true;
        else:
            // Mensagem alerta.
            session()->flash('message', 'Acesso à página ' . $page->title . ' não Permitido.');
            session()->flash('color', 'warning');

            // Exspulsa Usuário da página.
            return false;
        endif;
    }
}
