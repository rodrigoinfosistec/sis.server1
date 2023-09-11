<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Clock;
use App\Models\Clockemployee;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Employeevacation;
use App\Models\Employeeattest;
use App\Models\Employeeabsence;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClockShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'company_name';

    public $report_id;
    public $mail;
    public $comment;

    public $clock_id;
    public $company_id;
    public $company_name;
    public $start;
    public $end;
    public $created;

    public $txt;

    public $date;
    public $name;
    public $start_decode;
    public $end_decode;

    public $employee_id;
    public $employee_name;

    public $clockemployee_id;
    public $clockemployee_clock_id;
    public $clockemployee_employee_id;
    public $clockemployee_employee_name;
    public $clockemployee_employee_pis;
    public $clockemployee_journey_start_week;
    public $clockemployee_journey_end_week;
    public $clockemployee_journey_start_saturday;
    public $clockemployee_journey_end_saturday;
    public $clockemployee_delay_total;
    public $clockemployee_extra_total;
    public $clockemployee_balance_total;
    public $clockemployee_note;
    public $clockemployee_authorized;
    public $clockemployee_company_name;
    public $clockemployee_start_decode;
    public $clockemployee_end_decode;

    public $note;
    public $date_start;
    public $date_end;
    public $discount;
    public $merged;

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

            'company_id' => ['required'],
            'start'      => ['required'],
            'end'        => ['required'],

            'txt' => ['file', 'required'],

            'date' => ['required', 'unique:holidays,date,'.$this->date.''],
            'name' => ['required', 'between:2,255'],

            'employee_id' => ['required'],

            'note'       => ['nullable', 'between:2,255'],
            'date_start' => ['required'],
            'date_end'   => ['required'],
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

        $this->clock_id     = '';
        $this->company_id   = '';
        $this->company_name = '';
        $this->start        = '';
        $this->end          = '';
        $this->created      = '';

        $this->txt = '';

        $this->date         = '';
        $this->name         = '';
        $this->start_decode = '';
        $this->end_decode   = '';

        $this->employee_id = '';
        $this->employee_name = '';

        $this->clockemployee_id                     = '';
        $this->clockemployee_clock_id               = '';
        $this->clockemployee_employee_id            = '';
        $this->clockemployee_employee_name          = '';
        $this->clockemployee_employee_pis           = '';
        $this->clockemployee_journey_start_week     = '';
        $this->clockemployee_journey_end_week       = '';
        $this->clockemployee_journey_start_saturday = '';
        $this->clockemployee_journey_end_saturday   = '';
        $this->clockemployee_delay_total            = '';
        $this->clockemployee_extra_total            = '';
        $this->clockemployee_balance_total          = '';
        $this->clockemployee_note                   = '';
        $this->clockemployee_authorized             = '';
        $this->clockemployee_company_name           = '';
        $this->clockemployee_start_decode           = '';
        $this->clockemployee_end_decode             = '';

        $this->note       = '';
        $this->date_start = '';
        $this->date_end   = '';
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
            'existsItem'   => Clock::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Clock::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('id', 'DESC')->paginate(1),
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
                'company_id' => ['required'],
                'start'      => ['required'],
                'end'        => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Clock::validateAdd($data);

            // Cadastra.
            if ($valid) Clock::add($data);

            // Executa dependências.
            if ($valid) Clock::dependencyAdd($data);

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
                'start'      => ['required'],
                'end'        => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = $txtArray = Clock::validateAddTxt($data);

            // Estende $data
            if ($valid) $data['txtArray'] = $txtArray;

            // Cadastra.
            if ($valid) Clock::add($data);

            // Executa dependências.
            if ($valid) Clock::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/clock');
        }

    /**
     * addHoliday()
     *  registerHoliday()
     */
    public function addHoliday(int $clock_id)
    {
        // Funcionário.
        $clock = Clock::find($clock_id);

        // Inicializa propriedades dinâmicas.
        $this->clock_id     = $clock->id;
        $this->company_id   = $clock->company_id;
        $this->company_name = $clock->company_name;
        $this->start        = $clock->start;
        $this->end          = $clock->end;
        $this->start_decode = General::decodeDate($clock->start);
        $this->end_decode   = General::decodeDate($clock->end);
        $this->created      = $clock->created_at->format('d/m/Y H:i:s');
    }
        public function registerHoliday()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date' => ['required', 'unique:holidays'],
                'name' => ['required', 'between:2,255'],
            ]);

            // Etende $validatedData.
            $validatedData['week'] = Str::upper(General::decodeWeek(date_format(date_create($validatedData['date']), 'l')));
            $validatedData['year'] = date_format(date_create($validatedData['date']), 'Y');

            // Define $data.
            $data['config']['title'] = 'Feriado';
            $data['config']['name']  = $this->config['name'];
            $data['validatedData']   = $validatedData;

            // Valida cadastro.
            $valid = Holiday::validateAdd($data);

            // Cadastra.
            if ($valid) Holiday::add($data);

            // Executa dependências.
            if ($valid) Holiday::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $clock_id)
    {
        // Funcionário.
        $clock = Clock::find($clock_id);

        // Inicializa propriedades dinâmicas.
        $this->clock_id     = $clock->id;
        $this->company_id   = $clock->company_id;
        $this->company_name = $clock->company_name;
        $this->start        = General::decodeDate($clock->start);
        $this->end          = General::decodeDate($clock->end);
        $this->created      = $clock->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $clock_id)
    {
        // Funcionário.
        $clock = Clock::find($clock_id);

        // Inicializa propriedades dinâmicas.
        $this->clock_id     = $clock->id;
        $this->company_id   = $clock->company_id;
        $this->company_name = $clock->company_name;
        $this->start        = General::decodeDate($clock->start);
        $this->end          = General::decodeDate($clock->end);
        $this->created      = $clock->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'company_id' => ['required'],
                'start'      => ['required'],
                'end'        => ['required'],
            ]);

            // Estende $validatedData
            $validatedData['clock_id'] = $this->clock_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Clock::validateEdit($data);

            // Atualiza.
            if ($valid) Clock::edit($data);

            // Executa dependências.
            if ($valid) Clock::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $clock_id)
    {
        // Funcionário.
        $clock = Clock::find($clock_id);

        // Inicializa propriedades dinâmicas.
        $this->clock_id     = $clock->id;
        $this->company_id   = $clock->company_id;
        $this->company_name = $clock->company_name;
        $this->start        = General::decodeDate($clock->start);
        $this->end          = General::decodeDate($clock->end);
        $this->created      = $clock->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['clock_id']     = $this->clock_id;
            $validatedData['company_id']   = $this->company_id;
            $validatedData['company_name'] = $this->company_name;
            $validatedData['start']        = $this->start;
            $validatedData['end']          = $this->end;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Clock::validateErase($data);

            // Executa dependências.
            if ($valid) Clock::dependencyErase($data);

            // Exclui.
            if ($valid) Clock::erase($data);

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
            $valid = Clock::validateGenerate($data);

            // Gera relatório.
            if ($valid) Clock::generate($data);

            // Executa dependências.
            if ($valid) Clock::dependencyGenerate($data);

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
            $valid = Clock::validateMail($data);

            // Envia e-mail.
            if ($valid) Clock::mail($data);

            // Executa dependências.
            if ($valid) Clock::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addEmployee()
     *  registerEmployee()
     */
    public function addEmployee(int $clock_id)
    {
        // Funcionário.
        $clock = Clock::find($clock_id);

        // Inicializa propriedades dinâmicas.
        $this->clock_id     = $clock->id;
        $this->company_id   = $clock->company_id;
        $this->company_name = $clock->company_name;
        $this->start        = $clock->start;
        $this->end          = $clock->end;
        $this->start_decode = General::decodeDate($clock->start);
        $this->end_decode   = General::decodeDate($clock->end);
        $this->created      = $clock->created_at->format('d/m/Y H:i:s');
    }
        public function registerEmployee()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'employee_id' => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['clock_id'] = $this->clock_id;

            // Define $data.
            $data['config']['title'] = 'Funcionário';
            $data['config']['name']  = $this->config['name'];
            $data['validatedData']   = $validatedData;

            // Valida cadastro.
            $valid = Clockemployee::validateAdd($data);

            // Cadastra.
            if ($valid) Clockemployee::add($data);

            // Executa dependências.
            if ($valid) Clockemployee::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addVacationEmployee()
     *  registerVacationEmployee()
     */
    public function addVacationEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id ;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;
        $this->employee_id                          = $clockemployee->employee_id;

        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);
    }
        public function registerVacationEmployee()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date_start'  => ['required'],
                'date_end'    => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_id'] = $this->employee_id;

            // Define $data.
            $data['config']['title'] = 'Férias';
            $data['config']['name']  = $this->config['name'];
            $data['validatedData']   = $validatedData;

            // Valida cadastro.
            $valid = Employeevacation::validateAdd($data);

            // Cadastra.
            if ($valid) Employeevacation::add($data);

            // Executa dependências.
            if ($valid) Employeevacation::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addAttestEmployee()
     *  registerAttestEmployee()
     */
    public function addAttestEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id ;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;
        $this->employee_id                          = $clockemployee->employee_id;

        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);
    }
        public function registerAttestEmployee()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date_start'  => ['required'],
                'date_end'    => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_id'] = $this->employee_id;

            // Define $data.
            $data['config']['title'] = 'Atestado';
            $data['config']['name']  = $this->config['name'];
            $data['validatedData']   = $validatedData;

            // Valida cadastro.
            $valid = Employeeattest::validateAdd($data);

            // Cadastra.
            if ($valid) Employeeattest::add($data);

            // Executa dependências.
            if ($valid) Employeeattest::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addAbsenceEmployee()
     *  registerAbsenceEmployee()
     */
    public function addAbsenceEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id ;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;
        $this->employee_id                          = $clockemployee->employee_id;

        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);
    }
        public function registerAbsenceEmployee()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date_start'  => ['required'],
                'date_end'    => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_id'] = $this->employee_id;

            // Define $data.
            $data['config']['title'] = 'Falta';
            $data['config']['name']  = $this->config['name'];
            $data['validatedData']   = $validatedData;

            // Valida cadastro.
            $valid = Employeeabsence::validateAdd($data);

            // Cadastra.
            if ($valid) Employeeabsence::add($data);

            // Executa dependências.
            if ($valid) Employeeabsence::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addEasyEmployee()
     *  registerEasyEmployee()
     */
    public function addEasyEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id ;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;
        $this->employee_id                          = $clockemployee->employee_id;

        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);
    }
        public function registerEasyEmployee()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date'  => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_id'] = $this->employee_id;
            $this->discount ? $validatedData['discount'] = true : $validatedData['discount'] = false;

            // Define $data.
            $data['config']['title'] = 'Falta';
            $data['config']['name']  = $this->config['name'];
            $data['validatedData']   = $validatedData;

            // Valida cadastro.
            $valid = Employeeeasy::validateAdd($data);

            // Cadastra.
            if ($valid) Employeeeasy::add($data);

            // Executa dependências.
            if ($valid) Employeeeasy::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addEasyEmployee()
     *  registerEasyEmployee()
     */
    public function addAllowanceEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id ;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;
        $this->employee_id                          = $clockemployee->employee_id;

        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);
    }
        public function registerAllowanceEmployee()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date'        => ['required'],
                'start'       => ['required'],
                'end'         => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_id'] = $this->employee_id;
            $this->merged ? $validatedData['merged'] = true : $validatedData['merged'] = false;

            // Define $data.
            $data['config']['title'] = 'Falta';
            $data['config']['name']  = $this->config['name'];
            $data['validatedData']   = $validatedData;

            // Valida cadastro.
            $valid = Employeeallowance::validateAdd($data);

            // Cadastra.
            if ($valid) Employeeallowance::add($data);

            // Executa dependências.
            if ($valid) Employeeallowance::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editEmployeeNote()
     *  modernizeEmployeeNote()
     */
    public function editNoteEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id ;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;
        $this->note                                 = $clockemployee->note;

        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);

    }
        public function modernizeNoteEmployee()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'note' => ['nullable', 'between:2,255'],
            ]);

            // Estende $validatedData.
            $validatedData['clockemployee_id'] = $this->clockemployee_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Clockemployee::validateEditNote($data);

            // Atualiza.
            if ($valid) Clockemployee::editNote($data);

            // Executa dependências.
            if ($valid) Clockemployee::dependencyEditNote($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseEmployee()
     *  excludeEmployee()
     */
    public function eraseEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id ;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;

        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);
    }
        public function excludeEmployee()
        {
            // Define $validatedData
            $validatedData['clockemployee_id']       = $this->clockemployee_id;
            $validatedData['clock_id']               = $this->clockemployee_clock_id;
            $validatedData['employee_id']            = $this->clockemployee_employee_id ;
            $validatedData['employee_name']          = $this->clockemployee_employee_name;
            $validatedData['journey_start_week']     = $this->clockemployee_journey_start_week;
            $validatedData['journey_end_week']       = $this->clockemployee_journey_end_week;
            $validatedData['journey_start_saturday'] = $this->clockemployee_journey_start_saturday;
            $validatedData['journey_end_saturday']   = $this->clockemployee_journey_end_saturday;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Clockemployee::validateErase($data);

            // Executa dependências.
            if ($valid) Clockemployee::dependencyErase($data);

            // Exclui.
            if ($valid) Clockemployee::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
