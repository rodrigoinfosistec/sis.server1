<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\General;
use App\Models\Report;
use App\Models\Invoice;

use App\Models\Deposit;
use App\Models\Deposituser;
use App\Models\Depositinput;
use App\Models\Depositinputproduct;
use App\Models\Depositinputitem;
use App\Models\Product;

use App\Models\Provider;
use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class DepositinputShow extends Component
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

    public $depositinput_id;
    public $deposit_name;
    public $deposit_id;
    public $provider_id;
    public $provider_name;
    public $company_id;
    public $company_name;
    public $user_id;
    public $user_name;
    public $key;
    public $number;
    public $range;
    public $total;
    public $issue;
    public $observation;
    public $type;
    public $funded;
    public $created;

    public $xml;

    public $product_id;
    public $quantity;

    public $array_product_id = [];
    public $array_product_signal = [];
    public $array_product_amount = [];
    public $array_product_quantity = [];
    public $array_product_quantity_final = [];

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
            'deposit_id' => ['required'],
            'observation' => ['required'],

            'product_id' => ['required'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
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
        $this->mail = '';
        $this->comment = '';

        $this->depositinput_id = '';
        $this->deposit_name = '';
        $this->deposit_id = '';
        $this->provider_id = '';
        $this->provider_name = '';
        $this->company_id = '';
        $this->company_name = '';
        $this->user_id = '';
        $this->user_name = '';
        $this->key = '';
        $this->number = '';
        $this->range = '';
        $this->total = '';
        $this->issue = '';
        $this->observation = '';
        $this->type = '';
        $this->funded = '';
        $this->created = '';

        $this->xml = '';

        $this->product_id = '';
        $this->quantity = '';

        $this->array_product_id = [];
        $this->array_product_signal = [];
        $this->array_product_amount = [];
        $this->array_product_quantity = [];
        $this->array_product_quantity_final = [];
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
            'existsItem'   => Depositinput::where('company_id', auth()->user()->company_id)->whereIn('deposit_id', $array)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Depositinput::where([
                [$this->filter, 'like', '%'. $this->search . '%'],
                ['company_id', Auth()->user()->company_id],
            ])->whereIn('deposit_id', $array)->orderBy('id', 'DESC')->paginate(100),
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
                'provider_id' => ['required'],
                'observation' => ['required'],
            ]);

            // Depósito.
            $deposit = Deposit::find($validatedData['deposit_id']);

            // Fornecedor.
            $provider = Provider::find($validatedData['provider_id']);

            // Empresa.
            $company = Company::find(auth()->user()->company_id);

            // Estende $validatedData.
            $validatedData['deposit_name'] = $deposit->name;
            $validatedData['provider_name'] = $provider->name;
            $validatedData['company_id'] = $company->id;
            $validatedData['company_name'] = $company->name;
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['user_name'] = auth()->user()->name;
            $validatedData['number'] = Invoice::encodeNumber((string)( (string)$validatedData['deposit_id'] . (string)$validatedData['provider_id'] ) . str_pad(rand(0,9999), 4, "0", STR_PAD_LEFT) );

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Depositinput::validateAdd($data);

            // Cadastra.
            if ($valid) Depositinput::add($data);

            // Executa dependências.
            if ($valid) Depositinput::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addXml()
     *  registerXml()
     */
    public function addXml()
    {
        //...
    }
        public function registerXml()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'xml' => ['required', 'file', 'mimetypes:application/x-empty,text/plain,text/x-asm,application/octet-stream,inode/x-empty'],
                'deposit_id' => ['required'],
                'observation' => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Depositinput::validateAddXml($data);

            // Valida.
            if($valid):
                // Inicializa objeto xml.
                $xmlObject = $valid['xmlObject'];

                // Depósito.
                $deposit = Deposit::find($data['validatedData']['deposit_id']);

                // Fornecedor.
                $provider = Provider::where('cnpj', Provider::encodeCnpj((string)$xmlObject->NFe->infNFe->emit->CNPJ))->first();

                // Empresa.
                $company = Company::find(auth()->user()->company_id);

                // Estende $data['validatedData'].
                $data['validatedData']['deposit_name'] = $deposit->name;
                $data['validatedData']['provider_id'] = $provider->id;
                $data['validatedData']['provider_name'] = $provider->name;
                $data['validatedData']['company_id'] = $company->id;
                $data['validatedData']['company_name'] = $company->name;
                $data['validatedData']['user_id'] = auth()->user()->id;
                $data['validatedData']['user_name'] = auth()->user()->name;
                $data['validatedData']['key'] = Invoice::encodeKey((string)$xmlObject->protNFe->infProt->chNFe);
                $data['validatedData']['number'] = Invoice::encodeNumber((string)$xmlObject->NFe->infNFe->ide->nNF);
                $data['validatedData']['range'] = Invoice::encodeRange((string)$xmlObject->NFe->infNFe->ide->serie);
                $data['validatedData']['total'] = $xmlObject->NFe->infNFe->total->ICMSTot->vNF;
                $data['validatedData']['issue'] = Invoice::encodeIssue((string)$xmlObject->NFe->infNFe->ide->dhEmi);
                $data['validatedData']['type'] = 'xml';
                $data['validatedData']['xmlObject'] = $xmlObject;
            endif;

            // Cadastra.
            if ($valid) Depositinput::addXml($data);

            // Executa dependências.
            if ($valid) Depositinput::dependencyAddXml($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addProduct()
     *  registerProduct()
     */
    public function addProduct(int $depositinput_id)
    {
        // Entrada Depósito.
        $depositinput = Depositinput::find($depositinput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositinput_id = $depositinput_id;
        $this->deposit_name = $depositinput->deposit_name;
        $this->deposit_id = $depositinput->deposit_id;
        $this->provider_id = $depositinput->provider_id;
        $this->provider_name = $depositinput->provider_name;
        $this->company_id = $depositinput->company_id;
        $this->company_name = $depositinput->company_name;
        $this->user_id = $depositinput->user_id;
        $this->user_name = $depositinput->user_name;
        $this->key = $depositinput->key;
        $this->number = $depositinput->number;
        $this->range = $depositinput->range;
        $this->total = $depositinput->total;
        $this->issue = date_format(date_create($depositinput->issue), 'd/m/Y H:i:s');
        $this->observation = $depositinput->observation;
        $this->type = $depositinput->type;
        $this->created = $depositinput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositinput->updated_at->format('d/m/Y H:i:s');
    }
        public function registerProduct()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'product_id' => ['required'],
                'quantity' => ['required', 'numeric', 'min:0.1'],
            ]);

            // Estende $validatedData.
            $validatedData['depositinput_id'] = $this->depositinput_id;
            $validatedData['deposit_id'] = $this->deposit_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Depositinputproduct::validateAdd($data);

            // Cadastra.
            if ($valid) Depositinputproduct::add($data);

            // Executa dependências.
            if ($valid) Depositinputproduct::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editItemRelates()
     *  modernizeItemRelates()
     */
    public function editItemRelates(int $depositinput_id)
    {
        // Entrada Depósito.
        $depositinput = Depositinput::find($depositinput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositinput_id = $depositinput_id;
        $this->deposit_name = $depositinput->deposit_name;
        $this->deposit_id = $depositinput->deposit_id;
        $this->provider_id = $depositinput->provider_id;
        $this->provider_name = $depositinput->provider_name;
        $this->company_id = $depositinput->company_id;
        $this->company_name = $depositinput->company_name;
        $this->user_id = $depositinput->user_id;
        $this->user_name = $depositinput->user_name;
        $this->key = $depositinput->key;
        $this->number = $depositinput->number;
        $this->range = $depositinput->range;
        $this->total = $depositinput->total;
        $this->issue = date_format(date_create($depositinput->issue), 'd/m/Y H:i:s');
        $this->observation = $depositinput->observation;
        $this->type = $depositinput->type;
        $this->created = $depositinput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositinput->updated_at->format('d/m/Y H:i:s');

        // Seta quantidades.
        foreach(Depositinputitem::where('depositinput_id', $depositinput_id)->get() as $key => $depositinputitem):
            $this->array_product_quantity[$depositinputitem->id] = number_format($depositinputitem->quantity);
        endforeach;
    }
        public function modernizeItemRelates()
        {
            // Define $validatedData
            $validatedData['depositinput_id'] = $this->depositinput_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['provider_id'] = $this->provider_id;
            $validatedData['provider_name'] = $this->provider_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['company_name'] = $this->company_name;
            $validatedData['user_id'] = $this->user_id;
            $validatedData['user_name'] = $this->user_name;
            $validatedData['key'] = $this->key;
            $validatedData['number'] = $this->number;
            $validatedData['range'] = $this->range;
            $validatedData['total'] = $this->total;
            $validatedData['issue'] = $this->issue;
            $validatedData['observation'] = $this->observation;
            $validatedData['type'] = $this->type;
            $validatedData['created'] = $this->created;

            // Percorre os produtos da Nota Fiscal.
            foreach($this->array_product_id as $key => $product_id):
                // Item da Entrada.
                $depositinputitem = Depositinputitem::find($key);

                // Produto.
                $product = Product::find($product_id);

                // Monta array Item da Nota Fiscal.
                $validatedData['depositinputitem_id'] = $key;
                $validatedData['identifier'] = $depositinputitem->identifier;
                $validatedData['quantity'] = $this->array_product_quantity[$depositinputitem->id];
                $validatedData['provider_code'] = $depositinputitem->provideritem->code;
                $validatedData['provideritem_id'] = $depositinputitem->provideritem->id;

                $validatedData['product_id'] = $product_id;
                $validatedData['product_name'] = $product->name;
                $validatedData['product_code'] = $product->code;

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida exclusão.
                $valid = Depositinputitem::validateEditItemRelates($data);

                // Executa dependências.
                if ($valid) Depositinputitem::dependencyEditItemRelates($data);

                // Exclui.
                if ($valid) Depositinputitem::editItemRelates($data);
            endforeach;

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editItemAmount()
     *  modernizeItemAmount()
     */
    public function editItemAmount(int $depositinput_id)
    {
        // Entrada Depósito.
        $depositinput = Depositinput::find($depositinput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositinput_id = $depositinput_id;
        $this->deposit_name = $depositinput->deposit_name;
        $this->deposit_id = $depositinput->deposit_id;
        $this->provider_id = $depositinput->provider_id;
        $this->provider_name = $depositinput->provider_name;
        $this->company_id = $depositinput->company_id;
        $this->company_name = $depositinput->company_name;
        $this->user_id = $depositinput->user_id;
        $this->user_name = $depositinput->user_name;
        $this->key = $depositinput->key;
        $this->number = $depositinput->number;
        $this->range = $depositinput->range;
        $this->total = $depositinput->total;
        $this->issue = date_format(date_create($depositinput->issue), 'd/m/Y H:i:s');
        $this->observation = $depositinput->observation;
        $this->type = $depositinput->type;
        $this->created = $depositinput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositinput->updated_at->format('d/m/Y H:i:s');

        // Percorre todos os Produtos da Entrada.
        foreach(Depositinputproduct::where('depositinput_id', $depositinput_id)->get() as $key => $depositinputproduct):
            $depositinputitem = Depositinputitem::where(['depositinput_id' => $depositinput_id, 'identifier' => $depositinputproduct->identifier])->first();
            $this->array_product_signal[$depositinputproduct->id] = $depositinputitem->provideritem->signal;
            $this->array_product_amount[$depositinputproduct->id] = General::decodeFloat3($depositinputitem->provideritem->amount);
        endforeach;
    }
        public function modernizeItemAmount()
        {
            // Define $validatedData.
            $validatedData['depositinput_id'] = $this->depositinput_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['provider_id'] = $this->provider_id;
            $validatedData['provider_name'] = $this->provider_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['company_name'] = $this->company_name;
            $validatedData['user_id'] = $this->user_id;
            $validatedData['user_name'] = $this->user_name;
            $validatedData['key'] = $this->key;
            $validatedData['number'] = $this->number;
            $validatedData['range'] = $this->range;
            $validatedData['total'] = $this->total;
            $validatedData['issue'] = $this->issue;
            $validatedData['observation'] = $this->observation;
            $validatedData['type'] = $this->type;
            $validatedData['created'] = $this->created;

            // Percorre os itens da Nota Fiscal.
            foreach(Depositinputproduct::where('depositinput_id', $this->depositinput_id)->get() as $key => $depositinputproduct):
                // Item da Entrada.
                $depositinputitem = Depositinputitem::where(['depositinput_id' => $this->depositinput_id, 'identifier' => $depositinputproduct->identifier])->first();

                // Monta array Item da Nota Fiscal.
                $validatedData['depositinputproduct_id'] = $depositinputproduct->id;
                $validatedData['provideritem_id'] = $depositinputitem->provideritem_id;
                $validatedData['product_id'] = $depositinputproduct->product_id;
                $validatedData['signal'] = $this->array_product_signal[$depositinputproduct->id];
                $validatedData['amount'] = General::encodeFloat3($this->array_product_amount[$depositinputproduct->id]);
                if($this->array_product_signal[$depositinputproduct->id] == 'divide'):
                    $validatedData['quantity_final'] = $depositinputproduct->quantity / General::encodeFloat3($this->array_product_amount[$depositinputproduct->id]);
                else:
                    $validatedData['quantity_final'] = $depositinputproduct->quantity * General::encodeFloat3($this->array_product_amount[$depositinputproduct->id]);
                endif;

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida exclusão.
                $valid = Depositinputproduct::validateEditItemAmount($data);

                // Executa dependências.
                if ($valid) Depositinputproduct::dependencyEditItemAmount($data);

                // Exclui.
                if ($valid) Depositinputproduct::editItemAmount($data);
            endforeach;

            // Consolida a Entrada.
            Depositinput::find($this->depositinput_id)->update([
                'funded' => true,
            ]);

            // Gera o Relatório em PDF.
            if ($valid) Report::depositinputGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editAmount()
     *  modernizeProductAmount()
     */
    public function editProductAmount(int $depositinput_id)
    {
        // Entrada Depósito.
        $depositinput = Depositinput::find($depositinput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositinput_id = $depositinput_id;
        $this->deposit_name = $depositinput->deposit_name;
        $this->deposit_id = $depositinput->deposit_id;
        $this->provider_id = $depositinput->provider_id;
        $this->provider_name = $depositinput->provider_name;
        $this->company_id = $depositinput->company_id;
        $this->company_name = $depositinput->company_name;
        $this->user_id = $depositinput->user_id;
        $this->user_name = $depositinput->user_name;
        $this->key = $depositinput->key;
        $this->number = $depositinput->number;
        $this->range = $depositinput->range;
        $this->total = $depositinput->total;
        $this->issue = date_format(date_create($depositinput->issue), 'd/m/Y H:i:s');
        $this->observation = $depositinput->observation;
        $this->type = $depositinput->type;
        $this->created = $depositinput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositinput->updated_at->format('d/m/Y H:i:s');
    }
        public function modernizeProductAmount()
        {
            // Define $validatedData.
            $validatedData['depositinput_id'] = $this->depositinput_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['provider_id'] = $this->provider_id;
            $validatedData['provider_name'] = $this->provider_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['company_name'] = $this->company_name;
            $validatedData['user_id'] = $this->user_id;
            $validatedData['user_name'] = $this->user_name;
            $validatedData['key'] = $this->key;
            $validatedData['number'] = $this->number;
            $validatedData['range'] = $this->range;
            $validatedData['total'] = $this->total;
            $validatedData['issue'] = $this->issue;
            $validatedData['observation'] = $this->observation;
            $validatedData['type'] = $this->type;
            $validatedData['created'] = $this->created;

            // Percorre os Produtos da Nota Fiscal.
            foreach(Depositinputproduct::where('depositinput_id', $this->depositinput_id)->get() as $key => $depositinputproduct):
                // Monta array Item da Nota Fiscal.
                $validatedData['depositinputproduct_id'] = $depositinputproduct->id;
                $validatedData['product_id'] = $depositinputproduct->product_id;
                $validatedData['quantity_final'] = $depositinputproduct->quantity_final;

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida exclusão.
                $valid = Depositinputproduct::validateEditProductAmount($data);

                // Executa dependências.
                if ($valid) Depositinputproduct::dependencyEditProductAmount($data);

                // Exclui.
                if ($valid) Depositinputproduct::editProductAmount($data);
            endforeach;

            // Consolida a Entrada.
            Depositinput::find($this->depositinput_id)->update([
                'funded' => true,
            ]);

            // Gera o Relatório em PDF.
            if ($valid) Report::depositinputGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $depositinput_id)
    {
        // Entrada Depósito.
        $depositinput = Depositinput::find($depositinput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositinput_id = $depositinput_id;
        $this->deposit_name = $depositinput->deposit_name;
        $this->deposit_id = $depositinput->deposit_id;
        $this->provider_id = $depositinput->provider_id;
        $this->provider_name = $depositinput->provider_name;
        $this->company_id = $depositinput->company_id;
        $this->company_name = $depositinput->company_name;
        $this->user_id = $depositinput->user_id;
        $this->user_name = $depositinput->user_name;
        $this->key = $depositinput->key;
        $this->number = $depositinput->number;
        $this->range = $depositinput->range;
        $this->total = $depositinput->total;
        $this->issue = date_format(date_create($depositinput->issue), 'd/m/Y H:i:s');
        $this->observation = $depositinput->observation;
        $this->type = $depositinput->type;
        $this->created = $depositinput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositinput->updated_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['depositinput_id'] = $this->depositinput_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['provider_id'] = $this->provider_id;
            $validatedData['provider_name'] = $this->provider_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['company_name'] = $this->company_name;
            $validatedData['user_id'] = $this->user_id;
            $validatedData['user_name'] = $this->user_name;
            $validatedData['key'] = $this->key;
            $validatedData['number'] = $this->number;
            $validatedData['range'] = $this->range;
            $validatedData['total'] = $this->total;
            $validatedData['issue'] = $this->issue;
            $validatedData['observation'] = $this->observation;
            $validatedData['type'] = $this->type;
            $validatedData['created'] = $this->created;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Depositinput::validateErase($data);

            // Executa dependências.
            if ($valid) Depositinput::dependencyErase($data);

            // Exclui.
            if ($valid) Depositinput::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseItem()
     *  excludeItem()
     */
    public function eraseItem()
    {
        // ...
    }
        public function excludeItem(int $depositinputitem_id)
        {
            // Define $validatedData
            $validatedData['depositinputitem_id'] = $depositinputitem_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Depositinputitem::validateErase($data);

            // Executa dependências.
            if ($valid) Depositinputitem::dependencyErase($data);

            // Exclui.
            if ($valid) Depositinputitem::erase($data);

            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseProduct()
     *  excludeProduct()
     */
    public function eraseProduct()
    {
        // ...
    }
        public function excludeProduct(int $depositinputproduct_id)
        {
            // Define $validatedData
            $validatedData['depositinputproduct_id'] = $depositinputproduct_id;
            $validatedData['depositinput_id'] = Depositinputproduct::find($depositinputproduct_id)->depositinput_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Depositinputproduct::validateErase($data);

            // Executa dependências.
            if ($valid) Depositinputproduct::dependencyErase($data);

            // Exclui.
            if ($valid) Depositinputproduct::erase($data);

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
            $valid = Depositinput::validateGenerate($data);

            // Gera relatório.
            if ($valid) Depositinput::generate($data);

            // Executa dependências.
            if ($valid) Depositinput::dependencyGenerate($data);

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
            $valid = Depositinput::validateMail($data);

            // Envia e-mail.
            if ($valid) Depositinput::mail($data);

            // Executa dependências.
            if ($valid) Depositinput::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
