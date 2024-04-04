<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Document;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentShow extends Component
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

    public $document_id;
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

            'name' => ['required', 'between:2,255', 'unique:documents,name,'.$this->document_id.''],
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

        $this->document_id = '';
        $this->name        = '';
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
            'existsItem'   => Document::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Document::where([
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
                'name' => ['required', 'between:2,255', 'unique:documents'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Document::validateAdd($data);

            // Cadastra.
            if ($valid) Document::add($data);

            // Executa dependências.
            if ($valid) Document::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $document_id)
    {
        // Documento.
        $document = Document::find($document_id);

        // Inicializa propriedades dinâmicas.
        $this->document_id = $document->id;
        $this->name        = $document->name;
        $this->status      = $document->status;
        $this->created     = $document->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $document_id)
    {
        // Documento.
        $document = Document::find($document_id);

        // Inicializa propriedades dinâmicas.
        $this->document_id = $document->id;
        $this->name        = $document->name;
        $this->status      = $document->status;
        $this->created     = $document->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name' => ['required', 'between:2,255', 'unique:documents,name,'.$this->document_id.''],
            ]);

            // Estende $validatedData
            $validatedData['document_id'] = $this->document_id;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Document::validateEdit($data);

            // Atualiza.
            if ($valid) Document::edit($data);

            // Executa dependências.
            if ($valid) Document::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $document_id)
    {
        // Documento.
        $document = Document::find($document_id);

        // Inicializa propriedades dinâmicas.
        $this->document_id = $document->id;
        $this->name        = $document->name;
        $this->status      = $document->status;
        $this->created     = $document->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['document_id'] = $this->document_id;
            $validatedData['name']        = $this->name;
            $validatedData['status']      = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Document::validateErase($data);

            // Executa dependências.
            if ($valid) Document::dependencyErase($data);

            // Exclui.
            if ($valid) Document::erase($data);

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
            $valid = Document::validateGenerate($data);

            // Gera relatório.
            if ($valid) Document::generate($data);

            // Executa dependências.
            if ($valid) Document::dependencyGenerate($data);

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
            $valid = Document::validateMail($data);

            // Envia e-mail.
            if ($valid) Document::mail($data);

            // Executa dependências.
            if ($valid) Document::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
