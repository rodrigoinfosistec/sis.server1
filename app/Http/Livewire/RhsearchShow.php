<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Rhsearch;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class RhsearchShow extends Component
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

    public $rhsearch_id;
    public $name;
    public $link;
    public $icon;
    public $color;
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

            'name'  => ['required', 'between:2,255', 'unique:rhsearches,name,'.$this->rhsearch_id.''],
            'link'  => ['required', 'between:1,255', 'unique:rhsearches,link,'.$this->rhsearch_id.''],
            'icon'  => ['required', 'between:2,50', 'unique:rhsearches,icon,'.$this->rhsearch_id.''],
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

        $this->rhsearch_id = '';
        $this->name        = '';
        $this->link        = '';
        $this->icon        = '';
        $this->color       = '';
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
            'existsItem'   => Rhsearch::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Rhsearch::where([
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
                'name' => ['required', 'between:2,255', 'unique:rhsearches'],
                'link' => ['required', 'between:1,255', 'unique:rhsearches'],
            ]);

            // Estende $validatedData
            $validatedData['icon']  = $this->icon;
            $validatedData['color'] = $this->color;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Rhsearch::validateAdd($data);

            // Cadastra.
            if ($valid) Rhsearch::add($data);

            // Executa dependências.
            if ($valid) Rhsearch::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $rhsearch_id)
    {
        // RH Pesquisa.
        $rhsearch = Rhsearch::find($rhsearch_id);

        // Inicializa propriedades dinâmicas.
        $this->rhsearch_id  = $rhsearch->id;
        $this->name         = $rhsearch->name;
        $this->link         = $rhsearch->link;
        $this->icon         = $rhsearch->icon;
        $this->color        = $rhsearch->color;
        $this->status       = $rhsearch->status;
        $this->created      = $rhsearch->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $rhsearch_id)
    {
        // RH Pesquisa.
        $rhsearch = Rhsearch::find($rhsearch_id);

        // Inicializa propriedades dinâmicas.
        $this->rhsearch_id  = $rhsearch->id;
        $this->name         = $rhsearch->name;
        $this->link         = $rhsearch->link;
        $this->icon         = $rhsearch->icon;
        $this->color        = $rhsearch->color;
        $this->status       = $rhsearch->status;
        $this->created      = $rhsearch->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name'  => ['required', 'between:2,255', 'unique:rhsearches,name,'.$this->rhsearch_id.''],
                'link'  => ['required', 'between:1,255', 'unique:rhsearches,link,'.$this->rhsearch_id.''],
            ]);

            // Estende $validatedData
            $validatedData['rhsearch_id'] = $this->rhsearch_id;
            $validatedData['icon']        = $this->icon;
            $validatedData['color']       = $this->color;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Rhsearch::validateEdit($data);

            // Atualiza.
            if ($valid) Rhsearch::edit($data);

            // Executa dependências.
            if ($valid) Rhsearch::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $rhsearch_id)
    {
        // RH Pesquisa.
        $rhsearch = Rhsearch::find($rhsearch_id);

        // Inicializa propriedades dinâmicas.
        $this->rhsearch_id  = $rhsearch->id;
        $this->name         = $rhsearch->name;
        $this->link         = $rhsearch->link;
        $this->icon         = $rhsearch->icon;
        $this->color        = $rhsearch->color;
        $this->status       = $rhsearch->status;
        $this->created      = $rhsearch->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['rhsearch_id'] = $this->rhsearch_id;
            $validatedData['name']        = $this->name;
            $validatedData['link']        = $this->link;
            $validatedData['icon']        = $this->icon;
            $validatedData['color']       = $this->color;
            $validatedData['status']      = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Rhsearch::validateErase($data);

            // Executa dependências.
            if ($valid) Rhsearch::dependencyErase($data);

            // Exclui.
            if ($valid) Rhsearch::erase($data);

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
            $valid = Rhsearch::validateGenerate($data);

            // Gera relatório.
            if ($valid) Rhsearch::generate($data);

            // Executa dependências.
            if ($valid) Rhsearch::dependencyGenerate($data);

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
            $valid = Rhsearch::validateMail($data);

            // Envia e-mail.
            if ($valid) Rhsearch::mail($data);

            // Executa dependências.
            if ($valid) Rhsearch::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
