<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Usergroup;
use App\Models\Usergrouppage;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class UsergroupShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'name';

    public $report_id;
    public $mail;
    public $comment;

    public $usergroup_id;
    public $name;
    public $status;
    public $created;

    public $array_usergroup = [];

    public $array_usergrouppage = [];

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

            'name' => ['required', 'between:2,255', 'unique:usergroups,name,'.$this->usergroup_id.''],
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

        $this->usergroup_id = '';
        $this->name         = '';
        $this->status       = '';
        $this->created      = '';

        $this->array_usergroup = [];

        $this->array_usergrouppage = [];
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
            'existsItem'   => Usergroup::where('name', '!=', 'DEVELOPMENT')->exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Usergroup::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['name', '!=', 'DEVELOPMENT'],
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
                'name' => ['required', 'between:2,255', 'unique:usergroups'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Usergroup::validateAdd($data);

            // Cadastra.
            if ($valid) Usergroup::add($data);

            // Executa dependências.
            if ($valid) Usergroup::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $usergroup_id)
    {
        // Grupo de Usuário.
        $usergroup = Usergroup::find($usergroup_id);

        // Inicializa propriedades dinâmicas.
        $this->usergroup_id = $usergroup->id;
        $this->name         = $usergroup->name;
        $this->status       = $usergroup->status;
        $this->created      = $usergroup->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $usergroup_id)
    {
        // Grupo de Usuário.
        $usergroup = Usergroup::find($usergroup_id);

        // Inicializa propriedades dinâmicas.
        $this->usergroup_id = $usergroup->id;
        $this->name         = $usergroup->name;
        $this->status       = $usergroup->status;
        $this->created      = $usergroup->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name' => ['required', 'between:2,255', 'unique:usergroups,name,'.$this->usergroup_id.''],
            ]);

            // Estende $validatedData
            $validatedData['usergroup_id'] = $this->usergroup_id;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Usergroup::validateEdit($data);

            // Atualiza.
            if ($valid) Usergroup::edit($data);

            // Executa dependências.
            if ($valid) Usergroup::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editPermission()
     *  modernizePermission()
     */
    public function editPermission(int $usergroup_id)
    {
        // Grupo de Usuário.
        $usergroup = Usergroup::find($usergroup_id);

        // Inicializa propriedades dinâmicas.
        $this->usergroup_id = $usergroup->id;
        $this->name         = $usergroup->name;
        $this->status       = $usergroup->status;
        $this->created      = $usergroup->created_at->format('d/m/Y H:i:s');

        // Percorre as Páginas.
        foreach(Page::whereNotIn("name", ['home', 'logo'])->orderBy("title", "ASC")->get() as $key => $page):
            // Relaciona Grupo de Usuário à Página.
            Usergrouppage::where(['usergroup_id' => $usergroup->id, 'page_id' => $page->id])->exists() ? $this->array_usergrouppage[$page->id] = true : $this->array_usergrouppage[$page->id] = false;
        endforeach;
    }
        public function modernizePermission()
        {
            // Define $data
            $data['config']              = $this->config;
            $data['array_usergrouppage'] = $this->array_usergrouppage;
            $data['usergroup_id']        = $this->usergroup_id;
            $data['name']                = Usergroup::find($this->usergroup_id)->name;

            // Valida atualização de permissão.
            $valid = Usergroup::validateEditPermission($data);

            // Atualiza permissão.
            if ($valid) Usergroup::editPermission($data);

            // Executa dependências de permissão.
            if ($valid) Usergroup::dependencyEditPermission($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $usergroup_id)
    {
        // Grupo de Usuário.
        $usergroup = Usergroup::find($usergroup_id);

        // Inicializa propriedades dinâmicas.
        $this->usergroup_id = $usergroup->id;
        $this->name         = $usergroup->name;
        $this->status       = $usergroup->status;
        $this->created      = $usergroup->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['usergroup_id'] = $this->usergroup_id;
            $validatedData['name']         = $this->name;
            $validatedData['status']       = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Usergroup::validateErase($data);

            // Executa dependências.
            if ($valid) Usergroup::dependencyErase($data);

            // Exclui.
            if ($valid) Usergroup::erase($data);

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
            $valid = Usergroup::validateGenerate($data);

            // Gera relatório.
            if ($valid) Usergroup::generate($data);

            // Executa dependências.
            if ($valid) Usergroup::dependencyGenerate($data);

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
            $valid = Usergroup::validateMail($data);

            // Envia e-mail.
            if ($valid) Usergroup::mail($data);

            // Executa dependências.
            if ($valid) Usergroup::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
