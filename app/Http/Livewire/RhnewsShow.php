<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Rhnews;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class RhnewsShow extends Component
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

    public $rhnews_id;
    public $name;
    public $description;
    public $salute;
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

            'name'        => ['required', 'between:2,255', 'unique:rhnews,name,'.$this->rhnews_id.''],
            'description' => ['required', 'between:2,255', 'unique:rhnews,description,'.$this->rhnews_id.''],
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

        $this->rhnews_id   = '';
        $this->name        = '';
        $this->description = '';
        $this->salute      = '';
        $this->status      = '';
        $this->created     = '';
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
            'existsItem'   => Rhnews::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Rhnews::where([
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
                'name'        => ['required', 'between:2,255', 'unique:rhnews'],
                'description' => ['required', 'between:2,255', 'unique:rhnews'],
            ]);

            // Estende $validatedData
            $validatedData['salute'] = $this->salute;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Rhnews::validateAdd($data);

            // Cadastra.
            if ($valid) Rhnews::add($data);

            // Executa dependências.
            if ($valid) Rhnews::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $rhnews_id)
    {
        // RH Informa.
        $rhnews = Rhnews::find($rhnews_id);

        // Inicializa propriedades dinâmicas.
        $this->rhnews_id   = $rhnews->id;
        $this->name        = $rhnews->name;
        $this->description = $rhnews->description;
        $this->salute      = $rhnews->salute;
        $this->status      = $rhnews->status;
        $this->created     = $rhnews->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $rhnews_id)
    {
        // RH Informa.
        $rhnews = Rhnews::find($rhnews_id);

        // Inicializa propriedades dinâmicas.
        $this->rhnews_id   = $rhnews->id;
        $this->name        = $rhnews->name;
        $this->description = $rhnews->description;
        $this->salute      = $rhnews->salute;
        $this->status      = $rhnews->status;
        $this->created     = $rhnews->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name'  => ['required', 'between:2,255', 'unique:rhnews,name,'.$this->rhnews_id.''],
                'description'  => ['required', 'between:2,255', 'unique:rhnews,description,'.$this->rhnews_id.''],
            ]);

            // Estende $validatedData
            $validatedData['rhnews_id'] = $this->rhnews_id;
            $validatedData['salute']    = $this->salute;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Rhnews::validateEdit($data);

            // Atualiza.
            if ($valid) Rhnews::edit($data);

            // Executa dependências.
            if ($valid) Rhnews::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $rhnews_id)
    {
        // RH Informa.
        $rhnews = Rhnews::find($rhnews_id);

        // Inicializa propriedades dinâmicas.
        $this->rhnews_id   = $rhnews->id;
        $this->name        = $rhnews->name;
        $this->description = $rhnews->description;
        $this->salute      = $rhnews->salute;
        $this->status      = $rhnews->status;
        $this->created     = $rhnews->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['rhnews_id']   = $this->rhnews_id;
            $validatedData['name']        = $this->name;
            $validatedData['description'] = $this->description;
            $validatedData['salute']      = $this->salute;
            $validatedData['status']      = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Rhnews::validateErase($data);

            // Executa dependências.
            if ($valid) Rhnews::dependencyErase($data);

            // Exclui.
            if ($valid) Rhnews::erase($data);

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
            $valid = Rhnews::validateGenerate($data);

            // Gera relatório.
            if ($valid) Rhnews::generate($data);

            // Executa dependências.
            if ($valid) Rhnews::dependencyGenerate($data);

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
            $valid = Rhnews::validateMail($data);

            // Envia e-mail.
            if ($valid) Rhnews::mail($data);

            // Executa dependências.
            if ($valid) Rhnews::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
