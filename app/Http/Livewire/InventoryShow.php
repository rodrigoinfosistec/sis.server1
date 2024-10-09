<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;

use App\Models\Inventory;
use App\Models\Inventoryproduce;
use App\Models\Produce;
use App\Models\Producebrand;
use App\Models\Deposit;
use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class InventoryShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'producebrand_name';

    public $report_id;
    public $mail;
    public $comment;

    public $inventory_id;
    public $producebrand_id;
    public $producebrand_name;
    public $deposit_id;
    public $deposit_name;
    public $company_id;
    public $user_id;
    public $user_name;
    public $observation;
    public $finished;
    public $created;
    public $updated;

    public $array_produce_id = [];
    public $array_produce_name = [];
    public $array_produce_reference = [];
    public $array_produce_ean = [];
    public $array_produce_quantity = [];

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

            'producebrand_id' => ['required'],
            'deposit_id' => ['required'],
            'observation' => ['nullable', 'between:2,255'],
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

        $this->inventory_id = '';
        $this->producebrand_id = '';
        $this->producebrand_name = '';
        $this->deposit_id = '';
        $this->deposit_name = '';
        $this->user_id = '';
        $this->user_name = '';
        $this->observation = '';
        $this->finished = '';
        $this->created = '';
        $this->updated = '';

        $this->array_produce_id = [];
        $this->array_produce_name = [];
        $this->array_produce_code = [];
        $this->array_produce_reference = [];
        $this->array_produce_ean = [];
        $this->array_produce_quantity = [];

        $this->array_produce_producemeasure_id = [];
        $this->array_produce_producemeasure_name = [];
        $this->array_produce_producemeasure_quantity = [];

        $this->array_produce_score = [];
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
            'existsItem'   => Inventory::where('company_id', auth()->user()->company_id)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_2' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_2' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Inventory::where([
                            ['company_id', auth()->user()->company_id],
                            [$this->filter, 'like', '%'. $this->search . '%'],
                        ])->orderBy('finished', 'ASC')->orderBy('id', 'DESC')->paginate(100),
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
                'producebrand_id' => ['required'],
                'deposit_id' => ['required'],
                'observation' => ['nullable', 'between:2,255'],
            ]);

            // Estende $validatedData.
            if(!isset($validatedData['observation'])):
                $validatedData['observation'] = '';
            endif;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Inventory::validateAdd($data);

            // Cadastra.
            if ($valid) $inventory_id = Inventory::add($data);

            // Estende $data['validatedData'].
            if ($valid) $data['validatedData']['inventory_id'] = $inventory_id;

            // Executa dependências.
            if ($valid) Inventory::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $inventory_id)
    {
        // Balanço.
        $inventory = Inventory::find($inventory_id);

        // Inicializa propriedades dinâmicas.
        $this->inventory_id = $inventory_id;
        $this->producebrand_id = $inventory->producebrand_id;
        $this->producebrand_name = $inventory->producebrand_name;
        $this->deposit_id = $inventory->deposit_id;
        $this->deposit_name = $inventory->deposit_name;
        $this->user_id = $inventory->user_id;
        $this->user_name = $inventory->user_name;
        $this->observation = $inventory->observation;
        $this->finished = $inventory->finished;
        $this->created = $inventory->created_at->format('d/m/Y H:i:s');
        $this->updated = $inventory->updated_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $inventory_id)
    {
        // Balanço.
        $inventory = Inventory::find($inventory_id);

        // Inicializa propriedades dinâmicas.
        $this->inventory_id = $inventory->id;
        $this->producebrand_id = $inventory->producebrand_id;
        $this->producebrand_name = (string)$inventory->producebrand_name;
        $this->deposit_id = $inventory->deposit_id;
        $this->deposit_name = (string)$inventory->deposit_name;
        $this->company_id = $inventory->company_id;
        $this->user_id = $inventory->user_id;
        $this->user_name = (string)$inventory->user_name;
        $this->observation = (string)$inventory->observation;
        $this->finished = $inventory->finished;
        $this->created = $inventory->created_at->format('d/m/Y H:i:s');

        // Percorre os Produtos do Balanço.
        foreach(Inventoryproduce::where('inventory_id', $inventory_id)->get() as $key => $inventoryproduce):
            // Inicializa variáveis, dinamicamente.
            $this->array_produce_score[$inventoryproduce->produce->id] = '';
        endforeach;
    }
        public function modernize()
        {
            // Estende $validatedData.
            $validatedData['inventory_id'] = $this->inventory_id;
            $validatedData['deposit_id'] = $this->deposit_id;

            // Percorre os Produtos do Balanço.
            foreach(Inventoryproduce::where('inventory_id', $this->inventory_id)->get() as $key => $inventoryproduce):
                // Monta array do Produto do balanço.
                $validatedData['inventoryproduce_id'] = $inventoryproduce->id;
                $validatedData['score'] = $this->array_produce_score[$inventoryproduce->produce->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = Inventory::validateEdit($data);

                // Atualiza.
                if ($valid) Inventory::edit($data);

                // Executa dependências.
                if ($valid) Inventory::dependencyEdit($data);
            endforeach;

            // Consolida balanço.
            Inventory::find($this->inventory_id)->update([
                'finished' => true,
            ]);

            // Gera o Relatório em PDF.
            Report::inventoryGenerate($data);

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
            $valid = Inventory::validateGenerate($data);

            // Gera relatório.
            if ($valid) Inventory::generate($data);

            // Executa dependências.
            if ($valid) Inventory::dependencyGenerate($data);

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
            $valid = Inventory::validateMail($data);

            // Envia e-mail.
            if ($valid) Inventory::mail($data);

            // Executa dependências.
            if ($valid) Inventory::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
