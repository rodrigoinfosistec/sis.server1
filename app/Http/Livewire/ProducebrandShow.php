<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Producebrand;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProducebrandShow extends Component
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

    public $producebrand_id;
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

            'name' => ['required', 'between:2,255', 'unique:producebrands,name,'.$this->producebrand_id.''],
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

        $this->producebrand_id = '';
        $this->name    = '';
        $this->status  = '';
        $this->created = '';
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
            'existsItem'   => Producebrand::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Producebrand::where([
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
                'name' => ['required', 'between:2,255', 'unique:producebrands'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Producebrand::validateAdd($data);

            // Cadastra.
            if ($valid) Producebrand::add($data);

            // Executa dependências.
            if ($valid) Producebrand::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $producebrand_id)
    {
        // Banco.
        $producebrand = Producebrand::find($producebrand_id);

        // Inicializa propriedades dinâmicas.
        $this->producebrand_id = $producebrand->id;
        $this->name    = $producebrand->name;
        $this->status  = $producebrand->status;
        $this->created = $producebrand->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $producebrand_id)
    {
        // Banco.
        $producebrand = Producebrand::find($producebrand_id);

        // Inicializa propriedades dinâmicas.
        $this->producebrand_id = $producebrand->id;
        $this->name    = $producebrand->name;
        $this->status  = $producebrand->status;
        $this->created = $producebrand->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name' => ['required', 'between:2,255', 'unique:producebrands,name,'.$this->producebrand_id.''],
            ]);

            // Estende $validatedData
            $validatedData['producebrand_id'] = $this->producebrand_id;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Producebrand::validateEdit($data);

            // Atualiza.
            if ($valid) Producebrand::edit($data);

            // Executa dependências.
            if ($valid) Producebrand::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $producebrand_id)
    {
        // Banco.
        $producebrand = Producebrand::find($producebrand_id);

        // Inicializa propriedades dinâmicas.
        $this->producebrand_id = $producebrand->id;
        $this->name    = $producebrand->name;
        $this->status  = $producebrand->status;
        $this->created = $producebrand->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['producebrand_id'] = $this->producebrand_id;
            $validatedData['name']    = $this->name;
            $validatedData['status']  = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Producebrand::validateErase($data);

            // Executa dependências.
            if ($valid) Producebrand::dependencyErase($data);

            // Exclui.
            if ($valid) Producebrand::erase($data);

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
            $valid = Producebrand::validateGenerate($data);

            // Gera relatório.
            if ($valid) Producebrand::generate($data);

            // Executa dependências.
            if ($valid) Producebrand::dependencyGenerate($data);

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
            $valid = Producebrand::validateMail($data);

            // Envia e-mail.
            if ($valid) Producebrand::mail($data);

            // Executa dependências.
            if ($valid) Producebrand::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
