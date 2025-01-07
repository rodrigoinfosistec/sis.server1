<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeepoint;
use App\Models\Employeegroup;
use App\Models\Employeegroupcompany;
use App\Models\Employeeregistry;

use App\Models\Clockregistry;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeepointShow extends Component
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
    public $pis;
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
    public $code_aux;
    public $status;
    public $trainee;
    public $created;

    public $date;
    public $employee;

    public $txt;

    public $register_employee_id;
    public $register_employee_name;

    public $array_employeegroup_id = [];
    public $array_employeegroup_limit = [];

    public $array_employeegroupcompany_id    = [];
    public $array_employeegroupcompany_limit = [];

    public $array_employee_start     = [];
    public $array_employee_end       = [];
    public $array_employee_start_sat = [];
    public $array_employee_end_sat   = [];
    public $array_employee_delay     = [];

    /**
     * Construtor.
     */
    public function mount($config){
        $this->config = $config;
        $this->date = date('Y-m-d');
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
            'pis'                    => ['required', 'min:15', 'max:15', 'unique:employees,pis,'.$this->employee_id.''],
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
            'code'                   => ['nullable', 'between:4,10', 'unique:employees,code'],

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
        $this->pis                    = '';
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
        $this->code_aux               = '';
        $this->status                 = '';
        $this->trainee                = '';
        $this->created                = '';
        
        $this->employee = '';
        
        $this->register_employee_id   = '';
        $this->register_employee_name = '';

        $this->array_employeegroup_id    = [];
        $this->array_employeegroup_limit = [];

        $this->array_employeegroupcompany_id   = [];
        $this->array_employeegroupcompany_limit = [];

        $this->array_employee_start     = [];
        $this->array_employee_end       = [];
        $this->array_employee_start_sat = [];
        $this->array_employee_end_sat   = [];
        $this->array_employee_delay     = [];

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
                                ['employeegroup_id', '!=', null],
                                ['status', true],
                                ['limit_controll', true],
                            ])->orderBy('trainee', 'ASC')->orderBy('name', 'ASC')->paginate(100),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add(int $register_employee_id)
    {
        $this->register_employee_id   = $register_employee_id;
        $this->register_employee_name = Employee::find($register_employee_id)->name;
    }
        public function register()
        {
            // Define $validatedData
            $validatedData['employee_id']   = $this->register_employee_id;
            $validatedData['employee_name'] = $this->register_employee_name;
            $validatedData['date']          = date('Y-m-d');
            $validatedData['time']          = date('H:i');
            $validatedData['photo']         = null;
            $validatedData['cripto']        = true;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Clockregistry::validateAdd($data);

            // Cadastra.
            if ($valid) Clockregistry::add($data);

            // Executa dependências.
            if ($valid) Clockregistry::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
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
        $this->pis                    = $employee->pis;
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
        $this->pis                    = $employee->pis;
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
                'pis'                    => ['required', 'min:15', 'max:15', 'unique:employees,pis,'.$this->employee_id.''],
                'name'                   => ['required', 'between:3,60'],
                'journey_start_week'     => ['required'],
                'journey_end_week'       => ['required'],
                'journey_start_saturday' => ['required'],
                'journey_end_saturday'   => ['required'],
                'journey'                => ['required'],
                'clock_type'             => ['required'],
                'code'                   => ['nullable', 'between:4,10', 'unique:employees,code'],
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
     * editEmployeegroup()
     *  modernizeEmployeegroup()
     */
    public function editEmployeegroup()
    {
        // Percorre os Grupos.
        foreach(Employeegroup::where('status', true)->get() as $key => $employeegroup):
            // Employeegroupcompany.
            $employeegroupcompany = Employeegroupcompany::where([
                ['employeegroup_id', $employeegroup->id],
                ['company_id', Auth()->user()->company_id],
            ])->first();

            // Define as variáveis dinâmicas.
            $this->array_employeegroup_id[$employeegroup->id]           = $employeegroup->id;
            $this->array_employeegroup_limit[$employeegroup->id]        = $employeegroup->limit;
            $this->array_employeegroupcompany_id[$employeegroup->id]    = $employeegroupcompany->id;
            $this->array_employeegroupcompany_limit[$employeegroup->id] = $employeegroupcompany->limit;
        endforeach;
    }
        public function modernizeEmployeegroup()
        {
            // Percorre os Grupos.
            foreach(Employeegroup::where('status', true)->get() as $key => $employeegroup):
                // Verifica se Grupo está definido.
                if(isset($this->array_employeegroupcompany_id[$employeegroup->id])):
                    Employeegroupcompany::find($this->array_employeegroupcompany_id[$employeegroup->id])->update([
                        'limit' => $this->array_employeegroupcompany_limit[$employeegroup->id],
                    ]);
                endif;
            endforeach;

            // Mensagem.
            session()->flash('message', 'Grupos atualizados com sucesso.');
            session()->flash('color', 'success');

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editEmployeeregistry()
     *  modernizeEmployeeregistry()
     */
    public function editEmployeeregistry()
    {
        // Percorre os Grupos.
        foreach(Employee::where([
                ['company_id', Auth()->user()->company_id],
                ['limit_controll', true],
                ['clock_type', 'REGISTRY'],
                ['status', true],
                ['employeegroup_id', '!=', null],
            ])
            //->whereIn('employeegroup_id', [1, 2, 3, 9, 10, 12, 13])
            ->get() as $key => $employee):

            // Define as variáveis dinâmicas.
            $this->array_employee_start[$employee->id]     = General::minutsToTime($employee->limit_start_week);
            $this->array_employee_end[$employee->id]       = General::minutsToTime($employee->limit_end_week);
            $this->array_employee_start_sat[$employee->id] = General::minutsToTime($employee->limit_start_saturday);
            $this->array_employee_end_sat[$employee->id]   = General::minutsToTime($employee->limit_end_saturday);
            $this->array_employee_delay[$employee->id]     = General::minutsToTime($employee->limit_delay);
        endforeach;
    }
        public function modernizeEmployeeregistry()
        {
            // Percorre os Grupos.
            foreach(Employee::where([
                ['company_id', Auth()->user()->company_id],
                ['limit_controll', true],
                ['clock_type', 'REGISTRY'],
                ['status', true],
                ['employeegroup_id', '!=', null],
            ])->whereIn('employeegroup_id', [1, 2, 3, 9, 10, 12, 13])
            ->get() as $key => $employee):
                // Verifica se é Sábado.
                if(date_format(date_create(date('Y-m-d')), 'l') == 'Saturday'):
                    Employee::find($employee->id)->update([
                        'limit_start_saturday' => General::timeToMinuts($this->array_employee_start_sat[$employee->id]),
                        'limit_end_saturday'   => General::timeToMinuts($this->array_employee_end_sat[$employee->id]),
                        'limit_delay'          => General::timeToMinuts($this->array_employee_delay[$employee->id]),
                    ]);
                else:
                    Employee::find($employee->id)->update([
                        'limit_start_week' => General::timeToMinuts($this->array_employee_start[$employee->id]),
                        'limit_end_week'   => General::timeToMinuts($this->array_employee_end[$employee->id]),
                        'limit_delay'      => General::timeToMinuts($this->array_employee_delay[$employee->id]),
                    ]);
                endif;
            endforeach;

            // Mensagem.
            session()->flash('message', 'Limites atualizados com sucesso.');
            session()->flash('color', 'success');

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
        $this->pis                    = $employee->pis;
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
            $validatedData['pis']                    = $this->pis;
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
