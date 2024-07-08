<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;

use App\Models\Output;
use App\Models\Outputproduct;
use App\Models\Product;
use App\Models\Company;
use App\Models\Deposit;
use App\Models\Deposituser;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class OutputShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'deposit_name';

    public $report_id;
    public $mail;
    public $comment;

    public $output_id;
    public $deposit_id;
    public $deposit_name;
    public $user_id;
    public $user_name;
    public $observation;
    public $finished;
    public $created;
    public $updated;

    public $product_id;
    public $product_name;
    public $quantity;

    public $outputproduct_id;

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

            'deposit_id' => ['required'],
            'observation' => ['required'],

            'product_id' => ['required'],
            'quantity' => ['required', 'numeric'],
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

        $this->output_id = '';
        $this->deposit_id = '';
        $this->deposit_name = '';
        $this->user_id = '';
        $this->user_name = '';
        $this->observation = '';
        $this->finished = '';
        $this->created = '';
        $this->updated = '';

        $this->product_id = '';
        $this->product_name = '';
        $this->quantity = '';

        $this->outputproduct_id = '';

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
        // Inicializa variável.
        $array = [];

        // Monta o array.
        foreach(Deposituser::where('user_id', auth()->user()->id)->get() as $key => $deposituser):
            $array[] =  $deposituser->deposit_id;
        endforeach;

        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Output::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Output::where([
                ['company_id', auth()->user()->company_id],
                [$this->filter, 'like', '%'. $this->search . '%'],
            ])->whereIn('deposit_id', $array)->orderBy('finished', 'ASC')->orderBy('id', 'DESC')->paginate(100),
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
                'deposit_id' => ['required'],
                'observation' => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Output::validateAdd($data);

            // Cadastra.
            if ($valid) $output_id = Output::add($data);

            // Estende $data['validatedData'].
            if ($valid) $data['validatedData']['output_id'] = $output_id;

            // Executa dependências.
            if ($valid) Output::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addProduct()
     *  registerProduct()
     */
    public function addProduct(int $output_id)
    {
        // Empresa.
        $output = Output::find($output_id);

        // Inicializa propriedades dinâmicas.
        $this->output_id = $output_id;
        $this->deposit_id = $output->deposit_id;
        $this->deposit_name = $output->deposit_name;
        $this->user_id = $output->user_id;
        $this->user_name = $output->user_name;
        $this->observation = $output->observation;
        $this->finished = $output->finished;
        $this->created = $output->created_at->format('d/m/Y H:i:s');
        $this->updated = $output->updated_at->format('d/m/Y H:i:s');
    }
        public function registerProduct()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'product_id' => ['required'],
                'quantity' => ['required', 'numeric', 'min:0.1'],
            ]);

            // Estende $validatedData.
            $validatedData['output_id'] = $this->output_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Outputproduct::validateAdd($data);

            // Cadastra.
            if ($valid) Outputproduct::add($data);

            // Executa dependências.
            if ($valid) Outputproduct::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $output_id)
    {
        // Empresa.
        $output = Output::find($output_id);

        // Inicializa propriedades dinâmicas.
        $this->output_id = $output_id;
        $this->deposit_id = $output->deposit_id;
        $this->deposit_name = $output->deposit_name;
        $this->user_id = $output->user_id;
        $this->user_name = $output->user_name;
        $this->observation = $output->observation;
        $this->finished = $output->finished;
        $this->created = $output->created_at->format('d/m/Y H:i:s');
        $this->updated = $output->updated_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $output_id)
    {
        // Balanço.
        $output = Output::find($output_id);

        // Inicializa propriedades dinâmicas.
        $this->output_id = $output->id;
        $this->deposit_id = $output->deposit_id;
        $this->deposit_name = (string)$output->deposit_name;
        $this->company_id = $output->company_id;
        $this->user_id = $output->user_id;
        $this->user_name = (string)$output->user_name;
        $this->observation = (string)$output->observation;
        $this->finished = $output->finished;
        $this->created = $output->created_at->format('d/m/Y H:i:s');

        // Percorre os Produtos da Saída.
        foreach(Outputproduct::where('output_id', $output_id)->get() as $key => $outputproduct):
            // Inicializa variáveis, dinamicamente.
            $this->array_product_score[$outputproduct->product->id] = '';
        endforeach;
    }
        public function modernize()
        {
            // Estende $validatedData.
            $validatedData['output_id'] = $this->output_id;
            $validatedData['deposit_id'] = $this->deposit_id;

            // Percorre os Produtos da Saída.
            foreach(Outputproduct::where('output_id', $this->output_id)->get() as $key => $outputproduct):
                // Monta array do Produto do balanço.
                $validatedData['outputproduct_id'] = $outputproduct->id;
                $validatedData['score'] = $this->array_product_score[$outputproduct->product->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = Output::validateEdit($data);

                // Atualiza.
                if ($valid) Output::edit($data);

                // Executa dependências.
                if ($valid) Output::dependencyEdit($data);
            endforeach;

            // Consolida balanço.
            Output::find($this->output_id)->update([
                'finished' => true,
            ]);

            // Gera o Relatório em PDF.
            Report::outputGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseProduct()
     *  excludeProduct()
     */
    public function eraseProduct(int $outputproduct_id)
    {
        // Produto da Saída.
        $outputproduct = Outputproduct::find($outputproduct_id);

        // Inicializa propriedades dinâmicas.
        $this->outputproduct_id = $outputproduct_id;
        $this->product_name = $outputproduct->product_name;
        $this->deposit_name = $outputproduct->output->deposit->name;
        $this->created = $outputproduct->created_at->format('d/m/Y H:i:s');
    }
        public function excludeProduct()
        {
            // Define $validatedData
            $validatedData['outputproduct_id'] = $this->outputproduct_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Outputproduct::validateErase($data);

            // Executa dependências.
            if ($valid) Outputproduct::dependencyErase($data);

            // Exclui.
            if ($valid) Outputproduct::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

        /**
     * erase()
     *  exclude()
     */
    public function erase(int $output_id)
    {
        // Produto da Saída.
        $output = Output::find($output_id);

        // Inicializa propriedades dinâmicas.
        $this->output_id = $output_id;
        $this->deposit_id = $output->deposit_id;
        $this->deposit_name = $output->deposit_name;
        $this->company_id = $output->company_id;
        $this->observation = $output->observation;
        $this->created = $output->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['output_id'] = $this->output_id;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['observation'] = $this->observation;
            $validatedData['created'] = $this->created;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Output::validateErase($data);

            // Executa dependências.
            if ($valid) Output::dependencyErase($data);

            // Exclui.
            if ($valid) Output::erase($data);

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
            $valid = Output::validateGenerate($data);

            // Gera relatório.
            if ($valid) Output::generate($data);

            // Executa dependências.
            if ($valid) Output::dependencyGenerate($data);

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
            $valid = Output::validateMail($data);

            // Envia e-mail.
            if ($valid) Output::mail($data);

            // Executa dependências.
            if ($valid) Output::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
