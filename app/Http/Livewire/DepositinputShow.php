<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\General;
use App\Models\Report;

use App\Models\Deposit;
use App\Models\Depositinput;
use App\Models\Depositinputproduct;

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
    public $created;

    public $xml;

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
        $this->created = '';

        $this->xml = '';
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
     * addXml()
     *  register()
     */
    public function addXml()
    {
        //...
    }
        public function registerXml()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'xml' => ['required', 'file'],
                'deposit_id' => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Depositinput::validateAdd($data);

            // Valida.
            if($valid):
                // Inicializa objeto xml.
                $xmlObject = $valid['xmlObject'];

                // Provider.
                $provider = Provider::where('cnpj', Provider::encodeCnpj((string)$xmlObject->NFe->infNFe->emit->CNPJ))->first();

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
}
