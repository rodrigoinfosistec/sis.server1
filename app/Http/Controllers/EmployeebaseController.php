<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Page;

class EmployeebaseController extends Controller
{
    public $pageName;

    public function index(){
        /**
         * Nome da página - Configurável e único.
         * @var string $this->pageName.
         */
        $this->pageName = 'employeebase';

        /**
         * Expulsa Usuário sem autorização de acesso à Página.
         */
        if (!Page::userAuthorized($this->pageName)) return redirect()->to('/home');

        /**
         * View.
         */
        return view('employee', [
            'config' => [
                'name'  => $this->pageName,
                'title' => Page::getTitleByName($this->pageName), 
                'icon'  => Page::getIconByName($this->pageName), 
            ],
        ]);
    }
}
