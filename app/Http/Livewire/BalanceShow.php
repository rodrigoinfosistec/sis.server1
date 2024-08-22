<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;

use App\Models\Balance;
use App\Models\Balanceproduct;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Deposit;
use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class BalanceShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'provider_name';

    public $report_id;
    public $mail;
    public $comment;

    public $balance_id;
    public $provider_id;
    public $provider_name;
    public $deposit_id;
    public $deposit_name;
    public $user_id;
    public $user_name;
    public $observation;
    public $finished;
    public $created;
    public $updated;

    public $array_product_id = [];
    public $array_product_name = [];
    public $array_product_code = [];
    public $array_product_reference = [];
    public $array_product_ean = [];
    public $array_product_quantity = [];

    public $array_product_productmeasure_id = [];
    public $array_product_productmeasure_name = [];
    public $array_product_productmeasure_quantity = [];

    public $array_product_score = [];

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

            'provider_id' => ['required'],
            'deposit_id' => ['required'],
            'observation' => ['required'],
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

        $this->balance_id = '';
        $this->provider_id = '';
        $this->provider_name = '';
        $this->deposit_id = '';
        $this->deposit_name = '';
        $this->user_id = '';
        $this->user_name = '';
        $this->observation = '';
        $this->finished = '';
        $this->created = '';
        $this->updated = '';

        $this->array_product_id = [];
        $this->array_product_name = [];
        $this->array_product_code = [];
        $this->array_product_reference = [];
        $this->array_product_ean = [];
        $this->array_product_quantity = [];

        $this->array_product_productmeasure_id = [];
        $this->array_product_productmeasure_name = [];
        $this->array_product_productmeasure_quantity = [];

        $this->array_product_score = [];
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
            'existsItem'   => Balance::where('company_id', auth()->user()->company_id)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Balance::where([
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
                'provider_id' => ['required'],
                'deposit_id' => ['required'],
                'observation' => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Balance::validateAdd($data);

            // Cadastra.
            if ($valid) $balance_id = Balance::add($data);

            // Estende $data['validatedData'].
            if ($valid) $data['validatedData']['balance_id'] = $balance_id;

            // Executa dependências.
            if ($valid) Balance::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $balance_id)
    {
        // Balanço.
        $balance = Balance::find($balance_id);

        // Inicializa propriedades dinâmicas.
        $this->balance_id = $balance_id;
        $this->provider_id = $balance->provider_id;
        $this->provider_name = $balance->provider_name;
        $this->deposit_id = $balance->deposit_id;
        $this->deposit_name = $balance->deposit_name;
        $this->user_id = $balance->user_id;
        $this->user_name = $balance->user_name;
        $this->observation = $balance->observation;
        $this->finished = $balance->finished;
        $this->created = $balance->created_at->format('d/m/Y H:i:s');
        $this->updated = $balance->updated_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $balance_id)
    {
        // Balanço.
        $balance = Balance::find($balance_id);

        // Inicializa propriedades dinâmicas.
        $this->balance_id = $balance->id;
        $this->provider_id = $balance->provider_id;
        $this->provider_name = (string)$balance->provider_name;
        $this->deposit_id = $balance->deposit_id;
        $this->deposit_name = (string)$balance->deposit_name;
        $this->company_id = $balance->company_id;
        $this->user_id = $balance->user_id;
        $this->user_name = (string)$balance->user_name;
        $this->observation = (string)$balance->observation;
        $this->finished = $balance->finished;
        $this->created = $balance->created_at->format('d/m/Y H:i:s');

        // Percorre os Produtos do Balanço.
        foreach(Balanceproduct::where('balance_id', $balance_id)->get() as $key => $balanceproduct):
            // Inicializa variáveis, dinamicamente.
            $this->array_product_score[$balanceproduct->product->id] = '';
        endforeach;
    }
        public function modernize()
        {
            // Estende $validatedData.
            $validatedData['balance_id'] = $this->balance_id;
            $validatedData['deposit_id'] = $this->deposit_id;

            // Percorre os Produtos do Balanço.
            foreach(Balanceproduct::where('balance_id', $this->balance_id)->get() as $key => $balanceproduct):
                // Monta array do Produto do balanço.
                $validatedData['balanceproduct_id'] = $balanceproduct->id;
                $validatedData['score'] = $this->array_product_score[$balanceproduct->product->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = Balance::validateEdit($data);

                // Atualiza.
                if ($valid) Balance::edit($data);

                // Executa dependências.
                if ($valid) Balance::dependencyEdit($data);
            endforeach;

            // Consolida balanço.
            Balance::find($this->balance_id)->update([
                'finished' => true,
            ]);

            // Gera o Relatório em PDF.
            Report::balanceGenerate($data);

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
            $valid = Balance::validateGenerate($data);

            // Gera relatório.
            if ($valid) Balance::generate($data);

            // Executa dependências.
            if ($valid) Balance::dependencyGenerate($data);

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
            $valid = Balance::validateMail($data);

            // Envia e-mail.
            if ($valid) Balance::mail($data);

            // Executa dependências.
            if ($valid) Balance::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
