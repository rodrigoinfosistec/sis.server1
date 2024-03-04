<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Brand;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class BrandShow extends Component
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

    public $brand_id;
    public $name;
    public $status;
    public $created;

    public $array_brand = [];

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

            'name' => ['required', 'between:3,255', 'unique:brands,name,'.$this->brand_id.''],
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

        $this->brand_id = '';
        $this->name         = '';
        $this->status       = '';
        $this->created      = '';

        $this->array_brand = [];
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
            'existsItem'   => Brand::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Brand::where([
                                [$this->filter, 'like', '%'. $this->search . '%']
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
                'name' => ['required', 'between:3,255', 'unique:brands'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Brand::validateAdd($data);

            // Cadastra.
            if ($valid) Brand::add($data);

            // Executa dependências.
            if ($valid) Brand::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $brand_id)
    {
        // Marca.
        $brand = Brand::find($brand_id);

        // Inicializa propriedades dinâmicas.
        $this->brand_id = $brand->id;
        $this->name     = $brand->name;
        $this->status   = $brand->status;
        $this->created  = $brand->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $brand_id)
    {
        // Marca.
        $brand = Brand::find($brand_id);

        // Inicializa propriedades dinâmicas.
        $this->brand_id = $brand->id;
        $this->name     = $brand->name;
        $this->status   = $brand->status;
        $this->created  = $brand->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name' => ['required', 'between:3,255', 'unique:brands,name,'.$this->brand_id.''],
            ]);

            // Estende $validatedData
            $validatedData['brand_id'] = $this->brand_id;
            $this->status ? $validatedData['status'] = true : $validatedData['status'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Brand::validateEdit($data);

            // Atualiza.
            if ($valid) Brand::edit($data);

            // Executa dependências.
            if ($valid) Brand::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $brand_id)
    {
        // Marca.
        $brand = Brand::find($brand_id);

        // Inicializa propriedades dinâmicas.
        $this->brand_id = $brand->id;
        $this->name     = $brand->name;
        $this->status   = $brand->status;
        $this->created  = $brand->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['brand_id'] = $this->brand_id;
            $validatedData['name']     = $this->name;
            $validatedData['status']   = $this->status;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Brand::validateErase($data);

            // Executa dependências.
            if ($valid) Brand::dependencyErase($data);

            // Exclui.
            if ($valid) Brand::erase($data);

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
            $valid = Brand::validateGenerate($data);

            // Gera relatório.
            if ($valid) Brand::generate($data);

            // Executa dependências.
            if ($valid) Brand::dependencyGenerate($data);

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
            $valid = Brand::validateMail($data);

            // Envia e-mail.
            if ($valid) Brand::mail($data);

            // Executa dependências.
            if ($valid) Brand::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
