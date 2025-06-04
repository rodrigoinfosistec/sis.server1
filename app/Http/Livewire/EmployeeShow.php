<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Company;
use App\Models\Employeegroup;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeShow extends Component
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

    public $employee_id;
    public $company_id;
    public $company_name;
    public $companyoriginal_id;
    public $companyoriginal_name;
    public $employeegroup_id;
    public $employeegroup_name;
    public $pis;
    public $registration;
    public $name;
    public $cpf;
    public $rg;
    public $cnh;
    public $ctps;
    public $journey_start_week;
    public $journey_end_week;
    public $journey_start_saturday;
    public $journey_end_saturday;
    public $journey;
    public $limit_controll;
    public $limit_start_week;
    public $limit_end_week;
    public $limit_start_saturday;
    public $limit_end_saturday;
    public $clock_type;
    public $code;
    public $status;
    public $trainee;
    public $created;

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

            'company_id'             => ['required'],
            'companyoriginal_id'     => ['required'],
            'employeegroup_id'       => ['required'],
            'pis'                    => ['required', 'min:15', 'max:15', 'unique:employees,pis,'.$this->employee_id.''],
            'registration'           => ['required', 'between:1,10'],
            'name'                   => ['required', 'between:3,60'],
            'journey_start_week'     => ['required'],
            'journey_end_week'       => ['required'],
            'journey_start_saturday' => ['required'],
            'journey_end_saturday'   => ['required'],
            'journey'                => ['required'],
            'limit_start_week'       => ['required'],
            'limit_end_week'         => ['required'],
            'limit_start_saturday'   => ['required'],
            'limit_end_saturday'     => ['required'],
            'clock_type'             => ['required'],
            'code'                   => ['nullable', 'min:4', 'max:4', 'unique:employees,code,'.$this->employee_id.''],

            'txt' => ['file', 'required'],

            'cpf'  => ['nullable', 'min:14', 'max:14', 'unique:employees,cpf,'.$this->employee_id.''],
            'rg'   => ['nullable'],
            'cnh'  => ['nullable'],
            'ctps' => ['nullable'],
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

        $this->employee_id            = '';
        $this->company_id             = '';
        $this->company_name           = '';
        $this->companyoriginal_id     = '';
        $this->companyoriginal_name   = '';
        $this->employeegroup_id       = '';
        $this->employeegroup_name     = '';
        $this->pis                    = '';
        $this->registration           = '';
        $this->name                   = '';
        $this->cpf                    = '';
        $this->rg                     = '';
        $this->cnh                    = '';
        $this->ctps                   = '';
        $this->journey_start_week     = '';
        $this->journey_end_week       = '';
        $this->journey_start_saturday = '';
        $this->journey_end_saturday   = '';
        $this->journey                = '';
        $this->limit_controll         = '';
        $this->limit_start_week       = '';
        $this->limit_end_week         = '';
        $this->limit_start_saturday   = '';
        $this->limit_end_saturday     = '';
        $this->clock_type             = '';
        $this->code                   = '';
        $this->status                 = '';
        $this->trainee                = '';
        $this->created                = '';

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
            'existsItem'   => Employee::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employee::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                            ])->orderBy('status', 'DESC')->orderBy('trainee', 'ASC')->orderBy('name', 'ASC')->paginate(12),
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
                'company_id'             => ['required'],
                'pis'                    => ['required', 'min:15', 'max:15', 'unique:employees'],
                'registration'           => ['required', 'between:1,10'],
                'name'                   => ['required', 'between:3,60'],
                'journey_start_week'     => ['required'],
                'journey_end_week'       => ['required'],
                'journey_start_saturday' => ['required'],
                'journey_end_saturday'   => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Employee::validateAdd($data);

            // Cadastra.
            if ($valid) Employee::add($data);

            // Executa dependências.
            if ($valid) Employee::dependencyAdd($data);

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
            $valid = $txtArray = Employee::validateAddTxt($data);

            // Valida.
            if($valid):
                foreach($txtArray as $key => $employee):
                    // Estende $data['validatedData'].
                    $data['validatedData']['pis']                    = Employee::encodePis((string)$employee['pis']);
                    $data['validatedData']['name']                   = (string)$employee['name'];
                    $data['validatedData']['journey_start_week']     = '08:00';
                    $data['validatedData']['journey_end_week']       = '17:00';
                    $data['validatedData']['journey_start_saturday'] = '08:00';
                    $data['validatedData']['journey_end_saturday']   = '12:00';

                    if(Employee::where('pis', $data['validatedData']['pis'])->doesntExist()):
                        // Cadastra.
                        if ($valid) Employee::add($data);

                        // Executa dependências.
                        if ($valid) Employee::dependencyAdd($data);
                    endif;
                endforeach;
            endif;

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/employee');
        }

    /** 
     * detail()
     */
    public function detail(int $employee_id)
    {
        // Funcionário.
        $employee = Employee::find($employee_id);

        // Inicializa propriedades dinâmicas.
        $this->employee_id            = $employee->id;
        $this->company_id             = $employee->company_id;
        $this->company_name           = $employee->company_name;
        $this->companyoriginal_id     = $employee->companyoriginal_id;
        $this->companyoriginal_name   = $employee->companyoriginal_name;
        $this->pis                    = $employee->pis;
        $this->registration           = $employee->registration;
        $this->name                   = $employee->name;
        $this->journey_start_week     = $employee->journey_start_week;
        $this->journey_end_week       = $employee->journey_end_week;
        $this->journey_start_saturday = $employee->journey_start_saturday;
        $this->journey_end_saturday   = $employee->journey_end_saturday;
        $this->journey                = $employee->journey;
        $this->clock_type             = $employee->clock_type;
        $this->code                   = $employee->code;
        $this->status                 = $employee->status;
        $this->trainee                = $employee->trainee;
        $this->created                = $employee->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $employee_id)
    {
        // Funcionário.
        $employee = Employee::find($employee_id);

        // Inicializa propriedades dinâmicas.
        $this->employee_id            = $employee->id;
        $this->company_id             = $employee->company_id;
        $this->company_name           = $employee->company_name;
        $this->companyoriginal_id     = $employee->companyoriginal_id;
        $this->companyoriginal_name   = $employee->companyoriginal_name;
        $this->employeegroup_id       = $employee->employeegroup_id;
        $this->employeegroup_name     = $employee->employeegroup_name;
        $this->pis                    = $employee->pis;
        $this->registration           = $employee->registration;
        $this->name                   = $employee->name;
        $this->journey_start_week     = $employee->journey_start_week;
        $this->journey_end_week       = $employee->journey_end_week;
        $this->journey_start_saturday = $employee->journey_start_saturday;
        $this->journey_end_saturday   = $employee->journey_end_saturday;
        $this->journey                = $employee->journey;
        $this->limit_controll         = $employee->limit_controll;
        $this->clock_type             = $employee->clock_type;
        $this->code                   = $employee->code;
        $this->status                 = $employee->status;
        $this->trainee                = $employee->trainee;
        $this->created                = $employee->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'company_id'             => ['required'],
                'companyoriginal_id'     => ['required'],
                'employeegroup_id'       => ['required'],
                'pis'                    => ['required', 'min:15', 'max:15', 'unique:employees,pis,'.$this->employee_id.''],
                'registration'           => ['required', 'between:1,10'],
                'name'                   => ['required', 'between:3,60'],
                'journey_start_week'     => ['required'],
                'journey_end_week'       => ['required'],
                'journey_start_saturday' => ['required'],
                'journey_end_saturday'   => ['required'],
                'journey'                => ['required'],
                'clock_type'             => ['required'],
                'code'                   => ['nullable', 'min:4', 'max:4', 'unique:employees,code,'.$this->employee_id.''],
            ]);

            // Estende $validatedData
            $validatedData['employee_id']                            = $this->employee_id;
            $this->status ? $validatedData['status']                 = true : $validatedData['status'] = false;
            $this->trainee ? $validatedData['trainee']               = true : $validatedData['trainee'] = false;
            $this->limit_controll ? $validatedData['limit_controll'] = true : $validatedData['limit_controll'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Employee::validateEdit($data);

            // Atualiza.
            if ($valid) Employee::edit($data);

            // Executa dependências.
            if ($valid) Employee::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editDoc()
     *  modernizeDoc()
     */
    public function editDoc(int $employee_id)
    {
        // Funcionário.
        $employee = Employee::find($employee_id);

        // Inicializa propriedades dinâmicas.
        $this->employee_id  = $employee->id;
        $this->company_id   = $employee->company_id;
        $this->company_name = $employee->company_name;
        $this->pis          = $employee->pis;
        $this->name         = $employee->name;
        $this->cpf          = $employee->cpf;
        $this->rg           = $employee->rg;
        $this->cnh          = $employee->cnh;
        $this->ctps         = $employee->ctps;
    }
        public function modernizeDoc()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'cpf'  => ['nullable', 'min:14', 'max:14', 'unique:employees,cpf,'.$this->employee_id.''],
                'rg'   => ['nullable'],
                'cnh'  => ['nullable'],
                'ctps' => ['nullable'],
            ]);

            // Estende $validatedData
            $validatedData['employee_id'] = $this->employee_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Employee::validateEditDoc($data);

            // Atualiza.
            if ($valid) Employee::editDoc($data);

            // Executa dependências.
            if ($valid) Employee::dependencyEditDoc($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editLimit()
     *  modernizeLimit()
     */
    public function editLimit(int $employee_id)
    {
        // Funcionário.
        $employee = Employee::find($employee_id);

        // Inicializa propriedades dinâmicas.
        $this->employee_id  = $employee->id;
        $this->company_id   = $employee->company_id;
        $this->company_name = $employee->company_name;
        $this->pis          = $employee->pis;
        $this->registration = $employee->registration;
        $this->name         = $employee->name;
        $this->cpf          = $employee->cpf;
        $this->rg           = $employee->rg;
        $this->cnh          = $employee->cnh;
        $this->ctps         = $employee->ctps;

        $this->limit_start_week     = General::minutsToTime($employee->limit_start_week);
        $this->limit_end_week       = General::minutsToTime($employee->limit_end_week);
        $this->limit_start_saturday = General::minutsToTime($employee->limit_start_saturday);
        $this->limit_end_saturday   = General::minutsToTime($employee->limit_end_saturday);
    }
        public function modernizeLimit()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'limit_start_week'     => ['required'],
                'limit_end_week'       => ['required'],
                'limit_start_saturday' => ['required'],
                'limit_end_saturday'   => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_id'] = $this->employee_id;
            $validatedData['name']        = $this->name;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Employee::validateEditLimit($data);

            // Atualiza.
            if ($valid) Employee::editLimit($data);

            // Executa dependências.
            if ($valid) Employee::dependencyEditLimit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employee_id)
    {
        // Funcionário.
        $employee = Employee::find($employee_id);

        // Inicializa propriedades dinâmicas.
        $this->employee_id            = $employee->id;
        $this->company_id             = $employee->company_id;
        $this->company_name           = $employee->company_name;
        $this->companyoriginal_id     = $employee->companyoriginal_id;
        $this->companyoriginal_name   = $employee->companyoriginal_name;
        $this->pis                    = $employee->pis;
        $this->registration           = $employee->registration;
        $this->name                   = $employee->name;
        $this->journey_start_week     = $employee->journey_start_week;
        $this->journey_end_week       = $employee->journey_end_week;
        $this->journey_start_saturday = $employee->journey_start_saturday;
        $this->journey_end_saturday   = $employee->journey_end_saturday;
        $this->journey                = $employee->journey;
        $this->clock_type             = $employee->clock_type;
        $this->code                   = $employee->code;
        $this->status                 = $employee->status;
        $this->trainee                = $employee->trainee;
        $this->created                = $employee->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['employee_id']            = $this->employee_id;
            $validatedData['company_id']             = $this->company_id;
            $validatedData['company_name']           = $this->company_name;
            $validatedData['companyoriginal_id']     = $this->companyoriginal_id;
            $validatedData['companyoriginal_name']   = $this->companyoriginal_name;
            $validatedData['pis']                    = $this->pis;
            $validatedData['registration']           = $this->registration;
            $validatedData['name']                   = $this->name;
            $validatedData['journey_start_week']     = $this->journey_start_week;
            $validatedData['journey_end_week']       = $this->journey_end_week;
            $validatedData['journey_start_saturday'] = $this->journey_start_saturday;
            $validatedData['journey_end_saturday']   = $this->journey_end_saturday;
            $validatedData['clock_type']             = $this->clock_type;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employee::validateErase($data);

            // Executa dependências.
            if ($valid) Employee::dependencyErase($data);

            // Exclui.
            if ($valid) Employee::erase($data);

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
            $valid = Employee::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employee::generate($data);

            // Executa dependências.
            if ($valid) Employee::dependencyGenerate($data);

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
            $valid = Employee::validateMail($data);

            // Envia e-mail.
            if ($valid) Employee::mail($data);

            // Executa dependências.
            if ($valid) Employee::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
