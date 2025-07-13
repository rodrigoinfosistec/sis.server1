<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Breakdow;
use App\Models\Deposit;
use App\Models\Producebrand;
use App\Models\Producemeasure;
use App\Models\Company;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class BreakdowShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'producebrand_name';

    public $report_id;
    public $mail;
    public $comment;

    public $breakdow_id;
    public $producebrand_name;
    public $producebrand_id;
    public $deposit_id;
    public $deposit_name;
    public $producemeasure_id;
    public $producemeasure_name;
    public $company_id;
    public $company_name;
    public $list_path;
    public $status;
    public $value;
    public $volume;
    public $description;
    public $created_at;
    public $updated_at;

    public $created;

    public $pdf;

    /**
     * Construtor.
     */
    public function mount($config){
        $this->config = $config;

        // Company
        $company = Company::find(Auth()->user()->company_id);
        $this->company_id = Auth()->user()->company_id;
        $this->company_name = $company->name;

        // Deposit
        if(!empty($company->depositdefault_id)):
            $this->deposit_id = $company->depositdefault_id;
            $this->deposit_name = Deposit::find($this->deposit_id)->name;
        else:
            $this->deposit_id = '';
            $this->deposit_name = '';
        endif;
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

            'producebrand_id'   => ['required'],
            'deposit_id'        => ['required'],
            'producemeasure_id' => ['required'],
            'value'             => ['required'],
            'volume'            => ['required', 'integer', 'min:1'],
            'description'       => ['required', 'between:2,100'],

            'pdf' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:5120'],
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

        $this->breakdow_id         = '';
        $this->producebrand_name   = '';
        $this->producebrand_id     = '';
        $this->producemeasure_id   = '';
        $this->producemeasure_name = '';
        $this->status              = '';
        $this->value               = '';
        $this->volume              = '';
        $this->description         = '';
        $this->created_at          = '';
        $this->updated_at          = '';
        $this->created             = '';

        $this->pdf = '';
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
            'existsItem'   => Breakdow::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Breakdow::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                            ])->orderByRaw("FIELD(status, 'PENDENTE', 'EMBALADO', 'REEMBOLSADO', 'RECOLHIDO', 'DESCARTADO')")->orderBy('id', 'DESC')->paginate(12),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        // Empresa.
        $this->company_id = Auth()->user()->company_id;
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'producebrand_id'   => ['required'],
                'deposit_id'        => ['required'],
                'producemeasure_id' => ['required'],
                'company_id'        => ['required'],
                'value'             => ['required'],
                'volume'            => ['required', 'integer', 'min:1'],
                'description'       => ['required', 'between:2,100'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Breakdow::validateAdd($data);

            // Cadastra.
            if ($valid) Breakdow::add($data);

            // Executa dependências.
            if ($valid) Breakdow::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
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
                'company_id' => ['required'],
                'txt'        => ['file', 'required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = $txtArray = Breakdow::validateAddTxt($data);

            // Valida.
            if($valid):
                foreach($txtArray as $key => $breakdow):
                    // Estende $data['validatedData'].
                    $data['validatedData']['pis']                    = Breakdow::encodePis((string)$breakdow['pis']);
                    $data['validatedData']['name']                   = (string)$breakdow['name'];
                    $data['validatedData']['journey_start_week']     = '08:00';
                    $data['validatedData']['journey_end_week']       = '17:00';
                    $data['validatedData']['journey_start_saturday'] = '08:00';
                    $data['validatedData']['journey_end_saturday']   = '12:00';

                    if(Breakdow::where('pis', $data['validatedData']['pis'])->doesntExist()):
                        // Cadastra.
                        if ($valid) Breakdow::add($data);

                        // Executa dependências.
                        if ($valid) Breakdow::dependencyAdd($data);
                    endif;
                endforeach;
            endif;

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/breakdow');
        }

    /** 
     * detail()
     */
    public function detail(int $breakdow_id)
    {
        // Funcionário.
        $breakdow = Breakdow::find($breakdow_id);

        // Inicializa propriedades dinâmicas.
        $this->breakdow_id            = $breakdow->id;
        $this->company_id             = $breakdow->company_id;
        $this->company_name           = $breakdow->company_name;
        $this->companyoriginal_id     = $breakdow->companyoriginal_id;
        $this->companyoriginal_name   = $breakdow->companyoriginal_name;
        $this->pis                    = $breakdow->pis;
        $this->registration           = $breakdow->registration;
        $this->name                   = $breakdow->name;
        $this->journey_start_week     = $breakdow->journey_start_week;
        $this->journey_end_week       = $breakdow->journey_end_week;
        $this->journey_start_saturday = $breakdow->journey_start_saturday;
        $this->journey_end_saturday   = $breakdow->journey_end_saturday;
        $this->journey                = $breakdow->journey;
        $this->clock_type             = $breakdow->clock_type;
        $this->code                   = $breakdow->code;
        $this->status                 = $breakdow->status;
        $this->trainee                = $breakdow->trainee;
        $this->created                = $breakdow->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $breakdow_id)
    {
        // Funcionário.
        $breakdow = Breakdow::find($breakdow_id);

        // Inicializa propriedades dinâmicas.
        $this->breakdow_id         = $breakdow->id;
        $this->producebrand_name   = $breakdow->producebrand_name;
        $this->producebrand_id     = $breakdow->producebrand_id;
        $this->deposit_id          = $breakdow->deposit_id;
        $this->deposit_name        = $breakdow->deposit_name;
        $this->producemeasure_id   = $breakdow->producemeasure_id;
        $this->producemeasure_name = $breakdow->producemeasure_name;
        $this->company_id          = $breakdow->company_id;
        $this->company_name        = $breakdow->company_name;
        $this->list_path           = $breakdow->list_path;
        $this->status              = $breakdow->status;
        $this->value               = $breakdow->value;
        $this->volume              = $breakdow->volume;
        $this->description         = $breakdow->description;
        $this->created             = $breakdow->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            $validatedData = $this->validate([
                'pdf' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'max:5120'],
            ]);

            // Define $validatedData.
            $validatedData['breakdow_id'] = $this->breakdow_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Breakdow::validateEdit($data);

            // Atualiza.
            if ($valid) Breakdow::edit($data);

            // Executa dependências.
            if ($valid) Breakdow::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editRefunded()
     *  modernizeRefunded()
     */
    public function editRefunded(int $breakdow_id)
    {
        // Funcionário.
        $breakdow = Breakdow::find($breakdow_id);

        // Inicializa propriedades dinâmicas.
        $this->breakdow_id         = $breakdow->id;
        $this->producebrand_name   = $breakdow->producebrand_name;
        $this->producebrand_id     = $breakdow->producebrand_id;
        $this->deposit_id          = $breakdow->deposit_id;
        $this->deposit_name        = $breakdow->deposit_name;
        $this->producemeasure_id   = $breakdow->producemeasure_id;
        $this->producemeasure_name = $breakdow->producemeasure_name;
        $this->company_id          = $breakdow->company_id;
        $this->company_name        = $breakdow->company_name;
        $this->list_path           = $breakdow->list_path;
        $this->status              = $breakdow->status;
        $this->value               = $breakdow->value;
        $this->volume              = $breakdow->volume;
        $this->description         = $breakdow->description;
        $this->created             = $breakdow->created_at->format('d/m/Y H:i:s');
    }
        public function modernizeRefunded()
        {
            // Define $validatedData.
            $validatedData['breakdow_id'] = $this->breakdow_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Breakdow::validateEditRefunded($data);

            // Atualiza.
            if ($valid) Breakdow::editRefunded($data);

            // Executa dependências.
            if ($valid) Breakdow::dependencyEditRefunded($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $breakdow_id)
    {
        // Funcionário.
        $breakdow = Breakdow::find($breakdow_id);

        // Inicializa propriedades dinâmicas.
        $this->breakdow_id         = $breakdow->id;
        $this->producebrand_name   = $breakdow->producebrand_name;
        $this->producebrand_id     = $breakdow->producebrand_id;
        $this->deposit_id          = $breakdow->deposit_id;
        $this->deposit_name        = $breakdow->deposit_name;
        $this->producemeasure_id   = $breakdow->producemeasure_id;
        $this->producemeasure_name = $breakdow->producemeasure_name;
        $this->company_id          = $breakdow->company_id;
        $this->company_name        = $breakdow->company_name;
        $this->list_path           = $breakdow->list_path;
        $this->status              = $breakdow->status;
        $this->value               = $breakdow->value;
        $this->volume              = $breakdow->volume;
        $this->description         = $breakdow->description;
        $this->created             = $breakdow->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['breakdow_id']         = $this->breakdow_id;
            $validatedData['producebrand_name']   = $this->producebrand_name;
            $validatedData['producebrand_id']     = $this->producebrand_id;
            $validatedData['deposit_id']          = $this->deposit_id;
            $validatedData['deposit_name']        = $this->deposit_name;
            $validatedData['producemeasure_id']   = $this->producemeasure_id;
            $validatedData['producemeasure_name'] = $this->producemeasure_name;
            $validatedData['company_id']          = $this->company_id;
            $validatedData['company_name']        = $this->company_name;
            $validatedData['list_path']           = $this->list_path;
            $validatedData['status']              = $this->status;
            $validatedData['value']               = $this->value;
            $validatedData['volume']              = $this->volume;
            $validatedData['description']         = $this->description;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Breakdow::validateErase($data);

            // Executa dependências.
            if ($valid) Breakdow::dependencyErase($data);

            // Exclui.
            if ($valid) Breakdow::erase($data);

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
            $valid = Breakdow::validateGenerate($data);

            // Gera relatório.
            if ($valid) Breakdow::generate($data);

            // Executa dependências.
            if ($valid) Breakdow::dependencyGenerate($data);

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
            $valid = Breakdow::validateMail($data);

            // Envia e-mail.
            if ($valid) Breakdow::mail($data);

            // Executa dependências.
            if ($valid) Breakdow::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
