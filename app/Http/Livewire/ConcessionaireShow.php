<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Concessionaire;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConcessionaireShow extends Component
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

    public $concessionaire_id;
    public $name;
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

            'name' => ['required', 'between:3,255', 'unique:concessionaires,name,'.$this->concessionaire_id.''],
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

        $this->concessionaire_id = '';
        $this->name              = '';
        $this->status            = '';
        $this->created           = '';

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
            'existsItem'   => Concessionaire::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Concessionaire::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
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
                'name' => ['required', 'between:3,255', 'unique:concessionaires'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Concessionaire::validateAdd($data);

            // Cadastra.
            if ($valid) Concessionaire::add($data);

            // Executa dependências.
            if ($valid) Concessionaire::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $concessionaire_id)
    {
        // Grupo de Usuário.
        $concessionaire = Concessionaire::find($concessionaire_id);

        // Inicializa propriedades dinâmicas.
        $this->concessionaire_id = $concessionaire->id;
        $this->name              = $concessionaire->name;
        $this->status            = $concessionaire->status;
        $this->created           = $concessionaire->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $concessionaire_id)
    {
        // Grupo de Usuário.
        $concessionaire = Concessionaire::find($concessionaire_id);

        // Inicializa propriedades dinâmicas.
        $this->concessionaire_id = $concessionaire->id;
        $this->name              = $concessionaire->name;
        $this->status            = $concessionaire->status;
        $this->created           = $concessionaire->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name' => ['required', 'between:3,255', 'unique:concessionaires,name,'.$this->concessionaire_id.''],
            ]);

            // Estende $validatedData
            $validatedData['concessionaire_id'] = $this->concessionaire_id;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Concessionaire::validateEdit($data);

            // Atualiza.
            if ($valid) Concessionaire::edit($data);

            // Executa dependências.
            if ($valid) Concessionaire::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $concessionaire_id)
    {
        // Grupo de Usuário.
        $concessionaire = Concessionaire::find($concessionaire_id);

        // Inicializa propriedades dinâmicas.
        $this->concessionaire_id = $concessionaire->id;
        $this->name              = $concessionaire->name;
        $this->status            = $concessionaire->status;
        $this->created           = $concessionaire->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['concessionaire_id'] = $this->concessionaire_id;
            $validatedData['name']         = $this->name;
            $validatedData['status']       = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Concessionaire::validateErase($data);

            // Executa dependências.
            if ($valid) Concessionaire::dependencyErase($data);

            // Exclui.
            if ($valid) Concessionaire::erase($data);

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
            $valid = Concessionaire::validateGenerate($data);

            // Gera relatório.
            if ($valid) Concessionaire::generate($data);

            // Executa dependências.
            if ($valid) Concessionaire::dependencyGenerate($data);

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
            $valid = Concessionaire::validateMail($data);

            // Envia e-mail.
            if ($valid) Concessionaire::mail($data);

            // Executa dependências.
            if ($valid) Concessionaire::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
