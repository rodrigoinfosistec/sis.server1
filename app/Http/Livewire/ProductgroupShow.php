<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\User;
use App\Models\Productgroup;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductgroupShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'code';

    public $report_id;
    public $mail;
    public $comment;

    public $productgroup_id;
    public $code;
    public $origin;
    public $name;
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

            'code'   => ['required', 'between:1,10'],
            'origin' => ['required', 'between:3,255'],
            'name'   => ['required', 'between:3,255'],
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

        $this->productgroup_id = '';
        $this->code            = '';
        $this->origin          = '';
        $this->name            = '';
        $this->created         = '';
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
            'existsItem'   => Productgroup::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Productgroup::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('code', 'ASC')->paginate(12),
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
                'code'   => ['required', 'between:1,10'],
                'origin' => ['required', 'between:3,255'],
                'name'   => ['required', 'between:3,255'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Productgroup::validateAdd($data);

            // Cadastra.
            if ($valid) Productgroup::add($data);

            // Executa dependências.
            if ($valid) Productgroup::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $productgroup_id)
    {
        // Grupo de Produto.
        $productgroup = Productgroup::find($productgroup_id);

        // Inicializa propriedades dinâmicas.
        $this->productgroup_id = $productgroup->id;
        $this->code            = $productgroup->code;
        $this->origin          = $productgroup->origin;
        $this->name            = $productgroup->name;
        $this->created         = $productgroup->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $productgroup_id)
    {
        // Grupo de Produto.
        $productgroup = Productgroup::find($productgroup_id);

        // Inicializa propriedades dinâmicas.
        $this->productgroup_id = $productgroup->id;
        $this->code            = $productgroup->code;
        $this->origin          = $productgroup->origin;
        $this->name            = $productgroup->name;
        $this->created         = $productgroup->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'code'   => ['required', 'between:1,10'],
                'origin' => ['required', 'between:3,255'],
                'name'   => ['required', 'between:3,255'],
            ]);

            // Estende $validatedData
            $validatedData['productgroup_id'] = $this->productgroup_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Productgroup::validateEdit($data);

            // Atualiza.
            if ($valid) Productgroup::edit($data);

            // Executa dependências.
            if ($valid) Productgroup::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $productgroup_id)
    {
        // Grupo de Produto.
        $productgroup = Productgroup::find($productgroup_id);

        // Inicializa propriedades dinâmicas.
        $this->productgroup_id = $productgroup->id;
        $this->code            = $productgroup->code;
        $this->origin          = $productgroup->origin;
        $this->name            = $productgroup->name;
        $this->created         = $productgroup->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['productgroup_id'] = $this->productgroup_id;
            $validatedData['code']           = $this->code;
            $validatedData['origin']         = $this->origin;
            $validatedData['name']           = $this->name;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Productgroup::validateErase($data);

            // Executa dependências.
            if ($valid) Productgroup::dependencyErase($data);

            // Exclui.
            if ($valid) Productgroup::erase($data);

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
            $valid = Productgroup::validateGenerate($data);

            // Gera relatório.
            if ($valid) Productgroup::generate($data);

            // Executa dependências.
            if ($valid) Productgroup::dependencyGenerate($data);

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
            $valid = Productgroup::validateMail($data);

            // Envia e-mail.
            if ($valid) Productgroup::mail($data);

            // Executa dependências.
            if ($valid) Productgroup::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
