<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Product;
use App\Models\Productgroup;
use App\Models\Productdeposit;
use App\Models\Productmeasure;
use App\Models\Provider;
use App\Models\Company;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductShow extends Component
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

    public $product_id;
    public $name;
    public $code;
    public $reference;
    public $ean;
    public $cost;
    public $margin;
    public $value;
    public $quantity;
    public $signal;
    public $amount;
    public $provider_id;
    public $productgroup_id;
    public $productmeasure_id;
    public $status;
    public $created;

    public $productgroup_name;
    public $productgroup_code;
    public $productgroup_origin;
    public $productmeasure_name;
    public $productmeasure_quantity;

    public $csv;
    public $provider;
    public $append;

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

            'csv' => ['file', 'required'],
            'provider_id' => ['required'],
            'append' => ['nullable'],
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

        $this->product_id = '';
        $this->name = '';
        $this->code = '';
        $this->reference = '';
        $this->ean = '';
        $this->cost = '';
        $this->margin = '';
        $this->value = '';
        $this->quantity = '';
        $this->signal = '';
        $this->amount = '';
        $this->provider_id = '';
        $this->productgroup_id = '';
        $this->productmeasure_id = '';
        $this->status = '';
        $this->created = '';

        $this->productgroup_name = '';
        $this->productgroup_code = '';
        $this->productgroup_origin = '';

        $this->productmeasure_name = '';
        $this->productmeasure_quantity = '';

        $this->csv = '';
        $this->provider = '';
        $this->append = '';
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
            'existsItem'   => Product::where('company_id', auth()->user()->company_id)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Product::where([
                            ['company_id', auth()->user()->company_id],
                            [$this->filter, 'like', '%'. $this->search . '%'],
                        ])->orderBy('name', 'ASC')->paginate(100),
        ]);
    }

    /**
     * addCsv()
     *  registerCsv()
     */
    public function addCsv()
    {
        //...
    }
        public function registerCsv()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'csv' => ['file', 'required'],
                'provider_id' => ['required'],
                'append' => ['nullable'],
            ]);

            // Estende $validatedData.
            $validatedData['provider'] = Provider::find($validatedData['provider_id']);
            $validatedData['file_name'] = $validatedData['provider_id'] . '_' . Str::random(10) . '.csv';

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Product::validateAdd($data);

            // Valida.
            if ($valid) $data['validatedData']['csvArray'] = $valid['CsvArray'];

            // Cadastra.
            if ($valid) Product::add($data);

            // Executa dependências.
            if ($valid) Product::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/product');
        }

    /** 
     * detail()
     */
    public function detail(int $product_id)
    {
        // Funcionário.
        $product = Product::find($product_id);

        // Quantidade.
        if(Productdeposit::where(['product_id' => $product_id, 'deposit_id' => Company::find(auth()->user()->company_id)->depositdefault_id])->exists()):
            $quantity = Productdeposit::where(['product_id' => $product_id, 'deposit_id' => Company::find(auth()->user()->company_id)->depositdefault_id])->first()->quantity;
        else:
            $quantity = 0.00;
        endif;

        // Inicializa propriedades dinâmicas.
        $this->product_id = $product_id;
        $this->name = $product->name;
        $this->code = $product->code;
        $this->reference = $product->reference;
        $this->ean = $product->ean;
        $this->cost = $product->cost;
        $this->margin = $product->margin;
        $this->quantity = General::decodeFloat2($product->quantity - $quantity);
        $this->company_id = $product->company_id;
        $this->productgroup_id = $product->productgroup_id;
        $this->productmeasure_id = $product->productmeasure_id;
        $this->status = $product->status;
        $this->created = $product->created_at->format('d/m/Y H:i:s');
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
            $valid = Product::validateGenerate($data);

            // Gera relatório.
            if ($valid) Product::generate($data);

            // Executa dependências.
            if ($valid) Product::dependencyGenerate($data);

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
            $valid = Product::validateMail($data);

            // Envia e-mail.
            if ($valid) Product::mail($data);

            // Executa dependências.
            if ($valid) Product::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
