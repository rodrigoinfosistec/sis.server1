<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Company;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyShow extends Component
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

    public $company_id;
    public $cnpj;
    public $name;
    public $nickname;
    public $price;
    public $limit_start;
    public $limit_end;

    public $created;

    public $xml;
    public $txt;

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

            'cnpj'     => ['required', 'min:18', 'max:18', 'unique:companies,cnpj,'.$this->company_id.''],
            'name'     => ['required', 'between:3,60'],
            'nickname' => ['nullable', 'between:3,60'],
            'price'    => ['required'],

            'limit_start' => ['required'],
            'limit_end'   => ['required'],

            'txt' => ['file', 'required'],
            'xml' => ['file', 'required'],
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

        $this->company_id  = '';
        $this->cnpj        = '';
        $this->name        = '';
        $this->nickname    = '';
        $this->price       = '';
        $this->limit_start = '';
        $this->limit_end   = '';
        $this->created     = '';

        $this->xml = '';
        $this->txt = '';
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
            'existsItem'   => Company::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Company::where([
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
                'cnpj'     => ['required', 'min:18', 'max:18', 'unique:companies'],
                'name'     => ['required', 'between:3,60'],
                'nickname' => ['nullable', 'between:3,60'],
                'price'    => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Company::validateAdd($data);

            // Cadastra.
            if ($valid) Company::add($data);

            // Executa dependências.
            if ($valid) Company::dependencyAdd($data);

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
            $valid = Company::validateAddXml($data); 

            // valida.
            if($valid):
                // Atribui Objeto.
                $xmlObject = $valid;

                // Estende $data['validatedData'].
                $data['validatedData']['cnpj']     = Company::encodeCnpj((string)$xmlObject->NFe->infNFe->dest->CNPJ);
                $data['validatedData']['name']     = (string)$xmlObject->NFe->infNFe->dest->xNome;
                $data['validatedData']['nickname'] = (string)$xmlObject->NFe->infNFe->dest->xFant;
                $data['validatedData']['price']    = '1';
            endif;

            // Cadastra.
            if ($valid) Company::add($data);

            // Executa dependências.
            if ($valid) Company::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/company');
        }

    /**
     * addTxt()
     *  registerTxt()
     */
    public function addTxt()
    {
        //...
    }
        public function registerTxt()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'txt' => ['file', 'required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = $txtArray = Company::validateAddTxt($data); 

            // Valida.
            if($valid):
                // Estende $data['validatedData'].
                $data['validatedData']['cnpj']     = Company::encodeCnpj((string)$txtArray['cnpj']);
                $data['validatedData']['name']     = (string)$txtArray['name'];
                $data['validatedData']['nickname'] = (string)$txtArray['nickname'];
                $data['validatedData']['price']    = '1';
            endif;

            // Cadastra.
            if ($valid) Company::add($data);

            // Executa dependências.
            if ($valid) Company::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/company');
        }

    /** 
     * detail()
     */
    public function detail(int $company_id)
    {
        // Empresa.
        $company = Company::find($company_id);

        // Inicializa propriedades dinâmicas.
        $this->company_id = $company->id;
        $this->cnpj       = $company->cnpj;
        $this->name       = $company->name;
        $this->nickname   = $company->nickname;
        $this->price      = $company->price;
        $this->created    = $company->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $company_id)
    {
        // Empresa.
        $company = Company::find($company_id);

        // Inicializa propriedades dinâmicas.
        $this->company_id = $company->id;
        $this->cnpj       = $company->cnpj;
        $this->name       = $company->name;
        $this->nickname   = $company->nickname;
        $this->price      = $company->price;
        $this->created    = $company->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'cnpj'     => ['required', 'min:18', 'max:18', 'unique:companies,cnpj,'.$this->company_id.''],
                'name'     => ['required', 'between:3,60'],
                'nickname' => ['nullable', 'between:3,60'],
                'price'    => ['required'],
            ]);

            // Estende $validatedData
            $validatedData['company_id'] = $this->company_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Company::validateEdit($data);

            // Atualiza.
            if ($valid) Company::edit($data);

            // Executa dependências.
            if ($valid) Company::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editLimit()
     *  modernizeLimit()
     */
    public function editLimit(int $company_id)
    {
        // Empresa.
        $company = Company::find($company_id);

        // Inicializa propriedades dinâmicas.
        $this->company_id = $company->id;
        $this->name       = $company->name;
        $this->nickname   = $company->nickname;
        $this->price      = $company->price;
        $this->created    = $company->created_at->format('d/m/Y H:i:s');
    }
        public function modernizeLimit()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'limit_start' => ['required'],
                'limit_end'   => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['company_id'] = $this->company_id;
            $validatedData['name']       = $this->name;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Company::validateEditLimit($data);

            // Atualiza.
            if ($valid) Company::editLimit($data);

            // Executa dependências.
            if ($valid) Company::dependencyEditLimit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $company_id)
    {
        // Empresa.
        $company = Company::find($company_id);

        // Inicializa propriedades dinâmicas.
        $this->company_id = $company->id;
        $this->cnpj       = $company->cnpj;
        $this->name       = $company->name;
        $this->nickname   = $company->nickname;
        $this->price      = $company->price;
        $this->created    = $company->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['company_id'] = $this->company_id;
            $validatedData['cnpj']       = $this->cnpj;
            $validatedData['name']       = $this->name;
            $validatedData['nickname']   = $this->nickname;
            $validatedData['price']      = $this->price;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Company::validateErase($data);

            // Executa dependências.
            if ($valid) Company::dependencyErase($data);

            // Exclui.
            if ($valid) Company::erase($data);

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
            $valid = Company::validateGenerate($data);

            // Gera relatório.
            if ($valid) Company::generate($data);

            // Executa dependências.
            if ($valid) Company::dependencyGenerate($data);

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
            $valid = Company::validateMail($data);

            // Envia e-mail.
            if ($valid) Company::mail($data);

            // Executa dependências.
            if ($valid) Company::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
