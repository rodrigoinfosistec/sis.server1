<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\General;
use App\Models\Report;

use App\Models\Invoice;

use App\Models\Provider;
use App\Models\Providerbusiness;
use App\Models\Company;
use App\Models\Invoiceefisco;
use App\Models\Invoiceitem;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class InvoiceShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'number';

    public $report_id;
    public $mail;
    public $comment;

    public $invoice_id;
    public $provider_id;
    public $provider_name;
    public $company_id;
    public $company_name;
    public $key;
    public $number;
    public $range;
    public $total;
    public $issue;
    public $created;

    public $xml;
    public $csv;

    public $provider_cnpj;

    public $business_id;
    public $business_multiplier_type;
    public $business_multiplier_quantity;
    public $business_multiplier_value;
    public $business_multiplier_ipi;
    public $business_multiplier_ipi_aliquot;
    public $business_margin;
    public $business_shipping;
    public $business_discount;
    public $business_addition;

    public $efisco_id;
    public $efisco_invoice_id;
    public $efisco_productgroup_id;
    public $efisco_value;
    public $efisco_icms;

    public $array_item_equipment         = [];
    public $array_item_productgroup_id   = [];
    public $array_item_invoicecsv_id     = [];
    public $array_item_signal            = [];
    public $array_item_amount            = [];
    public $array_item_identifier        = [];
    public $array_item_code              = [];
    public $array_item_ean               = [];
    public $array_item_name              = [];
    public $array_item_ncm               = [];
    public $array_item_cfop              = [];
    public $array_item_cest              = [];
    public $array_item_measure           = [];
    public $array_item_quantity          = [];
    public $array_item_quantity_final    = [];
    public $array_item_value             = [];
    public $array_item_value_final       = [];
    public $array_item_ipi               = [];
    public $array_item_ipi_final         = [];
    public $array_item_ipi_aliquot       = [];
    public $array_item_ipi_aliquot_final = [];
    public $array_item_margin            = [];
    public $array_item_shipping          = [];
    public $array_item_discount          = [];
    public $array_item_addition          = [];
    public $array_item_index             = [];

    public $array_item_hold   = [];
    public $array_item_price  = [];
    public $array_item_card   = [];
    public $array_item_retail = [];

    public $hold_all;

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

            'xml' => ['file', 'required'],
            'csv' => ['file', 'required'],

            'business_multiplier_type'        => ['required'],
            'business_multiplier_quantity'    => ['required'],
            'business_multiplier_value'       => ['required'],
            'business_multiplier_ipi'         => ['required'],
            'business_multiplier_ipi_aliquot' => ['required'],
            'business_margin'                 => ['required'],
            'business_shipping'               => ['required'],
            'business_discount'               => ['required'],
            'business_addition'               => ['required'],

            'efisco_invoice_id'      => ['required'],
            'efisco_productgroup_id' => ['required'],
            'efisco_value'           => ['required'],
            'efisco_icms'            => ['required'],
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

        $this->invoice_id    = '';
        $this->provider_id   = '';
        $this->provider_name = '';
        $this->company_id    = '';
        $this->company_name  = '';
        $this->key           = '';
        $this->number        = '';
        $this->range         = '';
        $this->total         = '';
        $this->issue         = '';
        $this->created       = '';

        $this->xml = '';
        $this->csv = '';

        $this->provider_cnpj = '';

        $this->business_id                     = '';
        $this->business_multiplier_type        = '';
        $this->business_multiplier_quantity    = '';
        $this->business_multiplier_value       = '';
        $this->business_multiplier_ipi         = '';
        $this->business_multiplier_ipi_aliquot = '';
        $this->business_margin                 = '';
        $this->business_shipping               = '';
        $this->business_discount               = '';
        $this->business_addition               = '';

        $this->efisco_id              = '';
        $this->efisco_invoice_id      = '';
        $this->efisco_productgroup_id = '';
        $this->efisco_value           = '';
        $this->efisco_icms            = '';

        $this->array_item_equipment         = [];
        $this->array_item_productgroup_id   = [];
        $this->array_item_csv_id            = [];
        $this->array_item_signal            = [];
        $this->array_item_amount            = [];
        $this->array_item_identifier        = [];
        $this->array_item_code              = [];
        $this->array_item_ean               = [];
        $this->array_item_name              = [];
        $this->array_item_ncm               = [];
        $this->array_item_cfop              = [];
        $this->array_item_cest              = [];
        $this->array_item_measure           = [];
        $this->array_item_quantity          = [];
        $this->array_item_quantity_final    = [];
        $this->array_item_value             = [];
        $this->array_item_value_final       = [];
        $this->array_item_ipi               = [];
        $this->array_item_ipi_final         = [];
        $this->array_item_ipi_aliquot       = [];
        $this->array_item_ipi_aliquot_final = [];
        $this->array_item_margin            = [];
        $this->array_item_shipping          = [];
        $this->array_item_discount          = [];
        $this->array_item_addition          = [];
        $this->array_item_index             = [];

        $this->array_item_hold   = [];
        $this->array_item_price  = [];
        $this->array_item_card   = [];
        $this->array_item_retail = [];

        $this->hold_all = '';
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
            'existsItem'   => Invoice::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Invoice::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                            ])->orderBy('id', 'DESC')->paginate(12),
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
                'xml' => ['required', 'file'],
                'csv' => ['required', 'file'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Invoice::validateAdd($data);

            // Valida.
            if($valid):
                // Inicializa objeto xml.
                $xmlObject = $valid['xmlObject'];

                // Inicializa array csv.
                $CsvArray  = $valid['CsvArray'];

                // Provider.
                $provider = Provider::where('cnpj', Provider::encodeCnpj((string)$xmlObject->NFe->infNFe->emit->CNPJ))->first();

                // Company.
                $company = Company::where('cnpj', Company::encodeCnpj((string)$xmlObject->NFe->infNFe->dest->CNPJ))->first();

                // Estende $data['validatedData'].
                $data['validatedData']['provider_id']   = $provider->id;
                $data['validatedData']['provider_name'] = $provider->name;
                $data['validatedData']['company_id']    = $company->id;
                $data['validatedData']['company_name']  = $company->name;
                $data['validatedData']['key']           = Invoice::encodeKey((string)$xmlObject->protNFe->infProt->chNFe);
                $data['validatedData']['number']        = Invoice::encodeNumber((string)$xmlObject->NFe->infNFe->ide->nNF);
                $data['validatedData']['range']         = Invoice::encodeRange((string)$xmlObject->NFe->infNFe->ide->serie);
                $data['validatedData']['total']         = $xmlObject->NFe->infNFe->total->ICMSTot->vNF;
                $data['validatedData']['issue']         = Invoice::encodeIssue((string)$xmlObject->NFe->infNFe->ide->dhEmi);
                $data['validatedData']['xmlObject']     = $xmlObject;
                $data['validatedData']['CsvArray']      = $CsvArray;
            endif;

            // Cadastra.
            if ($valid) Invoice::add($data);

            // Executa dependências.
            if ($valid) Invoice::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/invoice');
        }

    /**
     * editBusiness()
     *  modernizeBusiness()
     */
    public function editBusiness(int $invoice_id)
    {
        // Fornecedor.
        $provider = Provider::find(Invoice::find($invoice_id)->provider_id);

        // Negociação com o Fornecedor.
        $business = Providerbusiness::where('provider_id', $provider->id)->first();

        // Inicializa propriedades dinâmicas.
        $this->invoice_id                      = $invoice_id;

        $this->provider_id                     = $provider->id;
        $this->provider_cnpj                   = $provider->cnpj;
        $this->provider_name                   = $provider->name;

        $this->business_id                     = $business->id;
        $this->business_multiplier_type        = $business->multiplier_type;
        $this->business_multiplier             = ($business->multiplier_type == 'quantity') ? (string)General::decodeFloat2($business->multiplier_quantity) : (string)General::decodeFloat2($business->multiplier_value);
        $this->business_multiplier_quantity    = General::decodeFloat2($business->multiplier_quantity);
        $this->business_multiplier_value       = General::decodeFloat2($business->multiplier_value);
        $this->business_multiplier_ipi         = General::decodeFloat2($business->multiplier_ipi);
        $this->business_multiplier_ipi_aliquot = General::decodeFloat2($business->multiplier_ipi_aliquot);

        $this->business_margin                 = General::decodeFloat2($business->margin);
        $this->business_shipping               = General::decodeFloat2($business->shipping);
        $this->business_discount               = General::decodeFloat2($business->discount);
        $this->business_addition               = General::decodeFloat2($business->addition);
    }
        public function modernizeBusiness()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'business_multiplier_type'        => ['required'],
                'business_multiplier'             => ['required'],
                'business_multiplier_ipi'         => ['required'],
                'business_multiplier_ipi_aliquot' => ['required'],
                'business_margin'                 => ['required'],
                'business_shipping'               => ['required'],
                'business_discount'               => ['required'],
                'business_addition'               => ['required'],
            ]);

            // Estende $validatedData
            $validatedData['provider_id']         = $this->provider_id;
            $validatedData['providerbusiness_id'] = $this->business_id;
            $validatedData['invoice_id']          = $this->invoice_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Providerbusiness::validateEdit($data);

            // Atualiza.
            if ($valid) Providerbusiness::edit($data);

            // Executa dependências.
            if ($valid) Providerbusiness::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addEfisco()
     *  registerEfisco()
     */
    public function addEfisco(int $invoice_id)
    {
        // Nota Fiscal.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->number        = $invoice->number;
        $this->provider_name = $invoice->provider_name;
    }
        public function registerEfisco()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'efisco_productgroup_id' => ['required'],
                'efisco_value'           => ['required'],
                'efisco_icms'            => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['invoice_id'] = $this->invoice_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Invoiceefisco::validateAdd($data);

            // Cadastra.
            if ($valid) Invoiceefisco::add($data);

            // Executa dependências.
            if ($valid) Invoiceefisco::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseEfisco()
     *  excludeEfisco()
     */
    public function eraseEfisco(int $invoice_id)
    {
        // Nota Fiscal.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->number        = $invoice->number;
        $this->provider_name = $invoice->provider_name;
    }
        public function excludeEfisco(int $invoiceefisco_id)
        {
            // eFisco.
            $invoiceefisco = Invoiceefisco::find($invoiceefisco_id);

            // Define $validatedData
            $validatedData['invoiceefisco_id']           = $invoiceefisco->id;
            $validatedData['invoice_id']                 = $invoiceefisco->invoice_id;
            $validatedData['invoice_number']             = $invoiceefisco->invoice->number;
            $validatedData['provider_name']              = $invoiceefisco->invoice->provider->name;
            $validatedData['efisco_productgroup_id']     = $invoiceefisco->productgroup_id;
            $validatedData['efisco_productgroup_code']   = $invoiceefisco->productgroup->code;
            $validatedData['efisco_productgroup_origin'] = $invoiceefisco->productgroup->origin;
            $validatedData['efisco_icms']                = $invoiceefisco->icms;
            $validatedData['efisco_value']               = $invoiceefisco->value;
            $validatedData['efisco_value_invoice']       = $invoiceefisco->value_invoice;
            $validatedData['efisco_value_final']         = $invoiceefisco->value_final;
            $validatedData['efisco_ipi_invoice']         = $invoiceefisco->ipi_invoice;
            $validatedData['efisco_ipi_final']           = $invoiceefisco->ipi_final;
            $validatedData['efisco_index']               = $invoiceefisco->iindex;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Invoiceefisco::validateErase($data);

            // Executa dependências.
            if ($valid) Invoiceefisco::dependencyErase($data);

            // Exclui.
            if ($valid) Invoiceefisco::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editItem()
     *  modernizeItem()
     */
    public function editItem(int $invoice_id)
    {
        // Invoice.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->number        = $invoice->number;
        $this->provider_name = $invoice->provider->name;

        // Percorre os itens da Nota Fiscal.
        foreach(Invoiceitem::where('invoice_id', $invoice_id)->orderBy('identifier', 'ASC')->get() as $key => $invoiceitem):
            // Inicializa variáveis, dinamicamente.
            $this->array_item_equipment[$invoiceitem->id]         = $invoiceitem->equipment;
            $this->array_item_productgroup_id[$invoiceitem->id]   = $invoiceitem->productgroup_id;
            $this->array_item_invoicecsv_id[$invoiceitem->id]     = $invoiceitem->invoicecsv_id;
            $this->array_item_signal[$invoiceitem->id]            = $invoiceitem->signal;
            $this->array_item_amount[$invoiceitem->id]            = General::decodeFloat3($invoiceitem->amount);
            $this->array_item_identifier[$invoiceitem->id]        = $invoiceitem->identifier;
            $this->array_item_code[$invoiceitem->id]              = $invoiceitem->code;
            $this->array_item_ean[$invoiceitem->id]               = $invoiceitem->ean;
            $this->array_item_name[$invoiceitem->id]              = $invoiceitem->name;
            $this->array_item_ncm[$invoiceitem->id]               = $invoiceitem->ncm;
            $this->array_item_cfop[$invoiceitem->id]              = $invoiceitem->cfop;
            $this->array_item_cest[$invoiceitem->id]              = $invoiceitem->cest;
            $this->array_item_measure[$invoiceitem->id]           = $invoiceitem->measure;
            $this->array_item_quantity_final[$invoiceitem->id]    = General::decodeFloat3($invoiceitem->quantity_final);
            $this->array_item_value_final[$invoiceitem->id]       = General::decodeFloat3($invoiceitem->value_final);
            $this->array_item_ipi_final[$invoiceitem->id]         = General::decodeFloat3($invoiceitem->ipi_final);
            $this->array_item_ipi_aliquot_final[$invoiceitem->id] = General::decodeFloat3($invoiceitem->ipi_aliquot_final);
            $this->array_item_margin[$invoiceitem->id]            = General::decodeFloat2($invoiceitem->margin);
            $this->array_item_shipping[$invoiceitem->id]          = General::decodeFloat2($invoiceitem->shipping);
        endforeach;
    }
        public function modernizeItem()
        {
            // Estende $validatedData.
            $validatedData['invoice_id'] = $this->invoice_id;

            // Percorre os itens da Nota Fiscal.
            foreach(Invoiceitem::where('invoice_id', $this->invoice_id)->get() as $key => $invoiceitem):
                // Monta array Item da Nota Fiscal.
                $validatedData['invoiceitem_id']    = $invoiceitem->id;
                $validatedData['equipment']         = $this->array_item_equipment[$invoiceitem->id];
                $validatedData['productgroup_id']   = $this->array_item_productgroup_id[$invoiceitem->id];
                $validatedData['invoicecsv_id']     = $this->array_item_invoicecsv_id[$invoiceitem->id];
                $validatedData['quantity_final']    = $this->array_item_quantity_final[$invoiceitem->id];
                $validatedData['value_final']       = $this->array_item_value_final[$invoiceitem->id];
                $validatedData['ipi_final']         = $this->array_item_ipi_final[$invoiceitem->id];
                $validatedData['ipi_aliquot_final'] = $this->array_item_ipi_aliquot_final[$invoiceitem->id];
                $validatedData['margin']            = $this->array_item_margin[$invoiceitem->id];
                $validatedData['shipping']          = $this->array_item_shipping[$invoiceitem->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = Invoiceitem::validateEdit($data);

                // Atualiza.
                if ($valid) Invoiceitem::edit($data);

                // Executa dependências.
                if ($valid) Invoiceitem::dependencyEdit($data);
            endforeach;

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editItemAmount()
     *  modernizeItemAmount()
     */
    public function editItemAmount(int $invoice_id)
    {
        // Invoice.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->number        = $invoice->number;
        $this->provider_name = $invoice->provider->name;

        // Percorre os itens da Nota Fiscal.
        foreach(Invoiceitem::where('invoice_id', $invoice_id)->orderBy('identifier', 'ASC')->get() as $key => $invoiceitem):
            // Inicializa variáveis, dinamicamente.
            $this->array_item_equipment[$invoiceitem->id]         = $invoiceitem->equipment;
            $this->array_item_productgroup_id[$invoiceitem->id]   = $invoiceitem->productgroup_id;
            $this->array_item_invoicecsv_id[$invoiceitem->id]     = $invoiceitem->invoicecsv_id;
            $this->array_item_signal[$invoiceitem->id]            = $invoiceitem->signal;
            $this->array_item_amount[$invoiceitem->id]            = General::decodeFloat3($invoiceitem->amount);
            $this->array_item_identifier[$invoiceitem->id]        = $invoiceitem->identifier;
            $this->array_item_code[$invoiceitem->id]              = $invoiceitem->code;
            $this->array_item_ean[$invoiceitem->id]               = $invoiceitem->ean;
            $this->array_item_name[$invoiceitem->id]              = $invoiceitem->name;
            $this->array_item_ncm[$invoiceitem->id]               = $invoiceitem->ncm;
            $this->array_item_cfop[$invoiceitem->id]              = $invoiceitem->cfop;
            $this->array_item_cest[$invoiceitem->id]              = $invoiceitem->cest;
            $this->array_item_measure[$invoiceitem->id]           = $invoiceitem->measure;
            $this->array_item_quantity_final[$invoiceitem->id]    = General::decodeFloat3($invoiceitem->quantity_final);
            $this->array_item_value_final[$invoiceitem->id]       = General::decodeFloat3($invoiceitem->value_final);
            $this->array_item_ipi_final[$invoiceitem->id]         = General::decodeFloat3($invoiceitem->ipi_final);
            $this->array_item_ipi_aliquot_final[$invoiceitem->id] = General::decodeFloat3($invoiceitem->ipi_aliquot_final);
            $this->array_item_margin[$invoiceitem->id]            = General::decodeFloat2($invoiceitem->margin);
            $this->array_item_shipping[$invoiceitem->id]          = General::decodeFloat2($invoiceitem->shipping);
            $this->array_item_index[$invoiceitem->id]             = General::decodeFloat2($invoiceitem->index);
        endforeach;
    }
        public function modernizeItemAmount()
        {
            // Estende $validatedData.
            $validatedData['invoice_id'] = $this->invoice_id;

            // Percorre os itens da Nota Fiscal.
            foreach(Invoiceitem::where('invoice_id', $this->invoice_id)->get() as $key => $invoiceitem):
                // Monta array Item da Nota Fiscal.
                $validatedData['invoiceitem_id'] = $invoiceitem->id;
                $validatedData['signal']         = $this->array_item_signal[$invoiceitem->id];
                $validatedData['amount']         = $this->array_item_amount[$invoiceitem->id];
                $validatedData['index']          = $this->array_item_index[$invoiceitem->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = Invoiceitem::validateEditAmount($data);

                // Atualiza.
                if ($valid) Invoiceitem::editAmount($data);

                // Executa dependências.
                if ($valid) Invoiceitem::dependencyEditAmount($data);
            endforeach;

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editItemPrice()
     *  modernizeItemPrice()
     */
    public function editItemPrice(int $invoice_id)
    {
        // Invoice.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->number        = $invoice->number;
        $this->provider_name = $invoice->provider->name;
        $this->hold_all      = false;

        // Percorre os itens da Nota Fiscal.
        foreach(Invoiceitem::where('invoice_id', $invoice_id)->orderBy('identifier', 'ASC')->get() as $key => $invoiceitem):
            // Inicializa variáveis, dinamicamente.
            $this->array_item_equipment[$invoiceitem->id] = $invoiceitem->equipment;
            $this->array_item_price[$invoiceitem->id]     = General::decodeFloat2($invoiceitem->price);
            $this->array_item_card[$invoiceitem->id]      = General::decodeFloat2($invoiceitem->card);
            $this->array_item_retail[$invoiceitem->id]    = General::decodeFloat2($invoiceitem->retail);
            $this->array_item_hold[$invoiceitem->id]      = false;
        endforeach;
    }
        public function modernizeItemPrice()
        {
            // Estende $validatedData.
            $validatedData['invoice_id'] = $this->invoice_id;
            $validatedData['hold_all']   = $this->hold_all;

            // Percorre os itens da Nota Fiscal.
            foreach(Invoiceitem::where('invoice_id', $this->invoice_id)->get() as $key => $invoiceitem):
                // Monta array Item da Nota Fiscal.
                $validatedData['invoiceitem_id'] = $invoiceitem->id;
                $validatedData['price']          = $this->array_item_price[$invoiceitem->id];
                $validatedData['card']           = $this->array_item_card[$invoiceitem->id];
                $validatedData['retail']         = $this->array_item_retail[$invoiceitem->id];
                $validatedData['hold']           = $this->array_item_hold[$invoiceitem->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = Invoiceitem::validateEditPrice($data);

                // Atualiza.
                if ($valid) Invoiceitem::editPrice($data);

                // Executa dependências.
                if ($valid) Invoiceitem::dependencyEditPrice($data);
            endforeach;

            $data_file['config']     = $data['config'];
            $data_file['invoice_id'] = $this->invoice_id;
            $data_file['random']     = Str::random(10);

            // Gera o PDF.
            Invoice::generatePricePdf($data_file);

            // Gera o CSV e ZIP.
            Invoice::generatePriceCsv($data_file);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $invoice_id)
    {
        // Nota Fiscal.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->provider_id   = $invoice->provider_id;
        $this->provider_name = $invoice->provider_name;
        $this->company_id    = $invoice->company_id;
        $this->company_name  = $invoice->company_name;
        $this->key           = $invoice->key;
        $this->number        = $invoice->number;
        $this->range         = $invoice->range;
        $this->total         = $invoice->total;
        $this->issue         = date_format(date_create($invoice->issue), 'd/m/Y H:i:s');
        $this->created       = $invoice->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['invoice_id']    = $this->invoice_id;
            $validatedData['provider_id']   = $this->provider_id;
            $validatedData['provider_name'] = $this->provider_name;
            $validatedData['company_id']    = $this->company_id;
            $validatedData['company_name']  = $this->company_name;
            $validatedData['key']           = $this->key;
            $validatedData['number']        = $this->number;
            $validatedData['range']         = $this->range;
            $validatedData['total']         = $this->total;
            $validatedData['issue']         = $this->issue;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Invoice::validateErase($data);

            // Executa dependências.
            if ($valid) Invoice::dependencyErase($data);

            // Exclui.
            if ($valid) Invoice::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detailAlert()
     */
    public function detailAlert(int $invoice_id)
    {
        // Nota Fiscal.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->provider_id   = $invoice->provider_id;
        $this->provider_name = $invoice->provider_name;
        $this->company_id    = $invoice->company_id;
        $this->company_name  = $invoice->company_name;
        $this->key           = $invoice->key;
        $this->number        = $invoice->number;
        $this->range         = $invoice->range;
        $this->total         = $invoice->total;
        $this->issue         = date_format(date_create($invoice->issue), 'd/m/Y H:i:s');
        $this->created       = $invoice->created_at->format('d/m/Y H:i:s');
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
            $valid = Invoice::validateGenerate($data);

            // Gera relatório.
            if ($valid) Invoice::generate($data);

            // Executa dependências.
            if ($valid) Invoice::dependencyGenerate($data);

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
            $valid = Invoice::validateMail($data);

            // Envia e-mail.
            if ($valid) Invoice::mail($data);

            // Executa dependências.
            if ($valid) Invoice::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * mailPrice()
     *  sendPrice()
     */
    public function mailPrice(int $invoice_id)
    {
        // Nota Fiscal.
        $invoice = Invoice::find($invoice_id);

        // Inicializa propriedades dinâmicas.
        $this->invoice_id    = $invoice->id;
        $this->number        = $invoice->number;
        $this->provider_name = $invoice->provider_name;
    }
        public function sendPrice()
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
            $valid = Invoice::validateMailPrice($data);

            // Envia e-mail.
            if ($valid) Invoice::mailPrice($data);

            // Executa dependências.
            if ($valid) Invoice::dependencyMailPrice($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
