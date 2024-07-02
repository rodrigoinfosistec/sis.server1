<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Product;
use App\Models\Productgroup;
use App\Models\Productmeasure;
use App\Models\Provider;

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
    public $signal;
    public $amount;
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
        $this->signal = '';
        $this->amount = '';
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
            'existsItem'   => Product::where('status', true)->exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Product::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('name', 'ASC')->paginate(100),
        ]);
    }

    /** 
     * detail()
     */
    public function detail(int $product_id)
    {
        // Produto.
        $product = Product::find($product_id);

        // Nota Fiscal.
        $invoice = Invoice::find($product->invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->product_id = $product->id;

        $this->product_id = $product->id;
        $this->name = $product->name;
        $this->code = $product->code;
        $this->reference = $product->reference;
        $this->ean = $product->ean;
        $this->cost = $product->cost;
        $this->margin = $product->margin;
        $this->value = $product->value;
        $this->signal = $product->signal;
        $this->amount = $product->amount;
        $this->status = $product->status;
        $this->created = $product->created_at->format('d/m/Y H:i:s');

        $this->productgroup_name = $product->productgroup->name;
        $this->productgroup_code = $product->productgroup->code;
        $this->productgroup_origin = $product->productgroup->origin;
        $this->productmeasure_name = $product->productmeasure->name;
        $this->productmeasure_quantity = $product->productmeasure->quantity;
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
