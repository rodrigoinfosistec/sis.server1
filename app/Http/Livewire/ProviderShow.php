<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Provider;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProviderShow extends Component
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

    public $provider_id;
    public $cnpj;
    public $name;
    public $nickname;
    public $created;

    public $xml;

    public $business_id;
    public $business_multiplier_type;
    public $business_multiplier;
    public $business_multiplier_quantity;
    public $business_multiplier_value;
    public $business_multiplier_ipi;
    public $business_multiplier_ipi_aliquot;
    public $business_margin;
    public $business_shipping;
    public $business_discount;
    public $business_addition;

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

            'cnpj'     => ['required', 'min:18', 'max:18', 'unique:providers,cnpj,'.$this->provider_id.''],
            'name'     => ['required', 'between:3,60'],
            'nickname' => ['nullable', 'between:3,60'],

            'xml' => ['file', 'required'],

            'business_multiplier_type'        => ['required'],
            'business_multiplier'             => ['required'],
            'business_multiplier_ipi'         => ['required'],
            'business_multiplier_ipi_aliquot' => ['required'],
            'business_margin'                 => ['required'],
            'business_shipping'               => ['required'],
            'business_discount'               => ['required'],
            'business_addition'               => ['required'],
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

        $this->provider_id = '';
        $this->cnpj        = '';
        $this->name        = '';
        $this->nickname    = '';
        $this->created     = '';

        $this->xml = '';

        $this->business_id                     = '';
        $this->business_multiplier_type        = '';
        $this->business_multiplier             = '';
        $this->business_multiplier_quantity    = '';
        $this->business_multiplier_value       = '';
        $this->business_multiplier_ipi         = '';
        $this->business_multiplier_ipi_aliquot = '';
        $this->business_margin                 = '';
        $this->business_shipping               = '';
        $this->business_discount               = '';
        $this->business_addition               = '';
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
            'existsItem'   => Provider::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Provider::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
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
                'cnpj'     => ['required', 'min:18', 'max:18', 'unique:providers'],
                'name'     => ['required', 'between:3,60'],
                'nickname' => ['nullable', 'between:3,60'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Provider::validateAdd($data);

            // Cadastra.
            if ($valid) Provider::add($data);

            // Executa dependências.
            if ($valid) Provider::dependencyAdd($data);

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
                'xml' => ['file', 'required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Provider::validateAddXml($data); 

            // Valida.
            if($valid):
                // Atribui Objeto.
                $xmlObject = $valid;

                // Estende $data['validatedData'].
                $data['validatedData']['cnpj']     = Provider::encodeCnpj((string)$xmlObject->NFe->infNFe->emit->CNPJ);
                $data['validatedData']['name']     = (string)$xmlObject->NFe->infNFe->emit->xNome;
                $data['validatedData']['nickname'] = (string)$xmlObject->NFe->infNFe->emit->xFant;
            endif;

            // Cadastra.
            if ($valid) Provider::add($data);

            // Executa dependências.
            if ($valid) Provider::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/provider');
        }

    /** 
     * detail()
     */
    public function detail(int $provider_id)
    {
        // Fornecedor.
        $provider = Provider::find($provider_id);

        // Inicializa propriedades dinâmicas.
        $this->provider_id = $provider->id;
        $this->cnpj        = $provider->cnpj;
        $this->name        = $provider->name;
        $this->nickname    = $provider->nickname;
        $this->created     = $provider->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $provider_id)
    {
        // Fornecedor.
        $provider = Provider::find($provider_id);

        // Inicializa propriedades dinâmicas.
        $this->provider_id = $provider->id;
        $this->cnpj        = $provider->cnpj;
        $this->name        = $provider->name;
        $this->nickname    = $provider->nickname;
        $this->created     = $provider->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'cnpj'     => ['required', 'min:18', 'max:18', 'unique:providers,cnpj,'.$this->provider_id.''],
                'name'     => ['required', 'between:3,60'],
                'nickname' => ['nullable', 'between:3,60'],
            ]);

            // Estende $validatedData
            $validatedData['provider_id'] = $this->provider_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Provider::validateEdit($data);

            // Atualiza.
            if ($valid) Provider::edit($data);

            // Executa dependências.
            if ($valid) Provider::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $provider_id)
    {
        // Fornecedor.
        $provider = Provider::find($provider_id);

        // Inicializa propriedades dinâmicas.
        $this->provider_id = $provider->id;
        $this->cnpj        = $provider->cnpj;
        $this->name        = $provider->name;
        $this->nickname    = $provider->nickname;
        $this->created     = $provider->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['provider_id'] = $this->provider_id;
            $validatedData['cnpj']       = $this->cnpj;
            $validatedData['name']       = $this->name;
            $validatedData['nickname']   = $this->nickname;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Provider::validateErase($data);

            // Executa dependências.
            if ($valid) Provider::dependencyErase($data);

            // Exclui.
            if ($valid) Provider::erase($data);

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
            $valid = Provider::validateGenerate($data);

            // Gera relatório.
            if ($valid) Provider::generate($data);

            // Executa dependências.
            if ($valid) Provider::dependencyGenerate($data);

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
            $valid = Provider::validateMail($data);

            // Envia e-mail.
            if ($valid) Provider::mail($data);

            // Executa dependências.
            if ($valid) Provider::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
