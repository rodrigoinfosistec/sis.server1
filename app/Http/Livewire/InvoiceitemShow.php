<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Invoiceitem;
use App\Models\Invoice;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class InvoiceitemShow extends Component
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

    public $product_id;
    public $signal;
    public $amount;
    public $code;
    public $ean;
    public $name;
    public $cost;
    public $ncm;
    public $cfop;
    public $cest;
    public $created;

    public $invoice_provider_name;
    public $invoice_company_name;
    public $invoice_number;
    public $invoice_issue;

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
        $this->signal     = '';
        $this->amount     = '';
        $this->code       = '';
        $this->ean        = '';
        $this->name       = '';
        $this->cost       = '';
        $this->ncm        = '';
        $this->cfop       = '';
        $this->cest       = '';
        $this->created    = '';

        $this->invoice_provider_name = '';
        $this->invoice_company_name  = '';
        $this->invoice_number        = '';
        $this->invoice_issue         = '';
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
            'existsItem'   => Invoiceitem::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Invoiceitem::where([
                                ['cost', '!=', NULL],
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('id', 'DESC')->limit(200)->paginate(20),
        ]);
    }

    /** 
     * detail()
     */
    public function detail(int $product_id)
    {
        // Grupo de Produto.
        $product = Invoiceitem::find($product_id);

        // Nota Fiscal.
        $invoice = Invoice::find($product->invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->product_id = $product->id;
        $this->signal     = $product->signal;
        $this->amount     = $product->amount;
        $this->code       = $product->code;
        $this->ean        = $product->ean;
        $this->name       = $product->name;
        $this->cost       = $product->cost;
        $this->ncm        = $product->ncm;
        $this->cfop       = $product->cfop;
        $this->cest       = $product->cest;
        $this->created    = $product->created_at->format('d/m/Y H:i:s');

        $this->invoice_provider_name = $invoice->provider_name;
        $this->invoice_company_name  = $invoice->company_name;
        $this->invoice_number        = $invoice->number;
        $this->invoice_issue         = date_format(date_create($invoice->issue), 'd/m/Y H:i:s');
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
            $valid = Invoiceitem::validateGenerate($data);

            // Gera relatório.
            if ($valid) Invoiceitem::generate($data);

            // Executa dependências.
            if ($valid) Invoiceitem::dependencyGenerate($data);

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
            $valid = Invoiceitem::validateMail($data);

            // Envia e-mail.
            if ($valid) Invoiceitem::mail($data);

            // Executa dependências.
            if ($valid) Invoiceitem::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
