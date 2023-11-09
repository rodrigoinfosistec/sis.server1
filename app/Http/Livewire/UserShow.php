<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners       = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'name';

    public $report_id;
    public $mail;
    public $comment;

    public $user_id;
    public $company_id;
    public $company_name;
    public $usergroup_id;
    public $usergroup_name;
    public $employee_id;
    public $name;
    public $email;
    public $password;
    public $confirm;
    public $password_old;
    public $confirm_old;
    public $status;
    public $created;

    /**
     * Construtor.
     */
    public function mount($config){
        $this->config = $config;
    }

    /**
     * Valida campos gerais.
     */
    protected function rules()
    {
        return [
            'report_id' => ['required'],
            'mail'      => ['required', 'email', 'between:2,255'],
            'comment'   => ['nullable', 'between:2,255'],

            'company_id'   => ['required'],
            'usergroup_id' => ['required'],
            'employee_id'  => ['required'],
            'name'         => ['required', 'between:3,255'],
            'email'        => ['required', 'email', 'between:3,255', 'unique:users,email,'.$this->user_id.''],
            'password'     => ['required', 'between:3,255'],

            'confirm'      => ['required', 'between:3,255'],
            'password_old' => ['required', 'between:3,255'],
        ];
    }

    /**
     * Valida atualização.
     */
    public function updated($fields){
        $this->validateOnly($fields);
    }

    /**
     * Fecha Modals.
     */
    public function closeModal(){
        $this->resetInput();
        $this->resetValidation();
    }

    /**
     * Reseta atributos.
     */
    public function resetInput()
    {
        $this->report_id = '';
        $this->mail      = '';
        $this->comment   = '';

        $this->user_id        = '';
        $this->company_id     = '';
        $this->company_name   = '';
        $this->usergroup_id   = '';
        $this->usergroup_name = '';
        $this->employee_id    = '';
        $this->name           = '';
        $this->email          = '';
        $this->password       = '';
        $this->confirm        = '';
        $this->password_old   = '';
        $this->confirm_old    = '';
        $this->status         = '';
        $this->created        = '';
    }

    /**
     * Atualiza conteúdo sem atualizar página.
     */
    public function refresh()
    {
        $this->emit('refreshChildren');
    }

    /**
     * Renderiza página.
     */
    public function render(){
        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => User::where('name', '!=', 'MASTER')->exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => User::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['name', '!=', 'MASTER'],
                            ])->orderBy('name', 'ASC')->paginate(12),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        //...
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'company_id'   => ['required'],
                'usergroup_id' => ['required'],
                'name'         => ['required', 'between:3,255'],
                'email'        => ['required', 'email', 'between:3,255', 'unique:users'],
                'password'     => ['required', 'between:3,255'],
                'confirm'      => ['required', 'between:3,255'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = User::validateAdd($data);

            // Cadastra.
            if ($valid) User::add($data);

            // Executa dependências.
            if ($valid) User::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $user_id)
    {
        // Usuário.
        $user = User::find($user_id);

        // Inicializa propriedades dinâmicas.
        $this->user_id        = $user->id;
        $this->company_id     = $user->company_id;
        $this->company_name   = $user->company_name;
        $this->usergroup_id   = $user->usergroup_id;
        $this->usergroup_name = $user->usergroup_name;
        $this->name           = $user->name;
        $this->email          = $user->email;
        $this->status         = $user->status;
        $this->created        = $user->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $user_id)
    {
        // Usuário.
        $user = User::find($user_id);

        // Inicializa propriedades dinâmicas.
        $this->user_id        = $user->id;
        $this->company_id     = $user->company_id;
        $this->company_name   = $user->company_name;
        $this->usergroup_id   = $user->usergroup_id;
        $this->usergroup_name = $user->usergroup_name;
        $this->employee_id    = $user->employee_id;
        $this->name           = $user->name;
        $this->email          = $user->email;
        $this->status         = $user->status;
        $this->created        = $user->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'company_id'   => ['required'],
                'usergroup_id' => ['required'],
                'employee_id'  => ['nullable'],
                'name'         => ['required', 'between:3,255'],
                'email'        => ['required', 'email', 'between:3,255', 'unique:users,email,'.$this->user_id.''],
            ]);

            // Estende $validatedData
            $validatedData['user_id'] = $this->user_id;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = User::validateEdit($data);

            // Atualiza.
            if ($valid) User::edit($data);

            // Executa dependências.
            if ($valid) User::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editPassword()
     *  modernizePassword()
     */
    public function editPassword(int $user_id)
    {
        // Usuário.
        $user = User::find($user_id);

        // Inicializa propriedades dinâmicas.
        $this->user_id        = $user->id;
        $this->company_id     = $user->company_id;
        $this->company_name   = $user->company_name;
        $this->usergroup_id   = $user->usergroup_id;
        $this->usergroup_name = $user->usergroup_name;
        $this->name           = $user->name;
        $this->email          = $user->email;
        $this->status         = $user->status;
        $this->created        = $user->created_at->format('d/m/Y H:i:s');
    }
        public function modernizePassword()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'password_old' => ['required', 'between:3,255'],
                'password'     => ['required', 'between:3,255'],
                'confirm'      => ['required', 'between:3,255'],
            ]);

            // Estende $validatedData
            $validatedData['user_id']     = $this->user_id;
            $validatedData['name']        = User::find($this->user_id)->name;
            $validatedData['confirm_old'] = User::find($this->user_id)->password;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = User::validateEditPassword($data);

            // Atualiza.
            if ($valid) User::editPassword($data);

            // Executa dependências.
            if ($valid) User::dependencyEditPassword($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $user_id)
    {
        // Usuário.
        $user = User::find($user_id);

        // Inicializa propriedades dinâmicas.
        $this->user_id        = $user->id;
        $this->company_id     = $user->company_id;
        $this->company_name   = $user->company_name;
        $this->usergroup_id   = $user->usergroup_id;
        $this->usergroup_name = $user->usergroup_name;
        $this->name           = $user->name;
        $this->email          = $user->email;
        $this->status         = $user->status;
        $this->created        = $user->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['user_id']        = $this->user_id;
            $validatedData['company_id']     = $this->company_id;
            $validatedData['company_name']   = $this->company_name;
            $validatedData['usergroup_id']   = $this->usergroup_id;
            $validatedData['usergroup_name'] = $this->usergroup_name;
            $validatedData['name']           = $this->name;
            $validatedData['email']          = $this->email;
            $validatedData['status']         = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = User::validateErase($data);

            // Executa dependências.
            if ($valid) User::dependencyErase($data);

            // Exclui.
            if ($valid) User::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * generate()
     *  sire()
     */
    public function generate()
    {
        //...
    }
        public function sire()
        {
            // Define $data.
            $data['config'] = $this->config;
            $data['filter'] = $this->filter;
            $data['search'] = $this->search;

            // Valida geração de relatório.
            $valid = User::validateGenerate($data);

            // Gera relatório.
            if ($valid) User::generate($data);

            // Executa dependências.
            if ($valid) User::dependencyGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * mail()
     *  send()
     */
    public function mail()
    {
        //...
    }
        public function send()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'report_id' => ['required'],
                'mail'      => ['required', 'email', 'between:2,255'],
                'comment'   => ['nullable', 'between:2,255'],
            ]);

            // Define $data
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida envio do e-mail.
            $valid = User::validateMail($data);

            // Envia e-mail.
            if ($valid) User::mail($data);

            // Executa dependências.
            if ($valid) User::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
