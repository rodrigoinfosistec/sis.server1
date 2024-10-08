<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Clock;
use App\Models\Clockemployee;
use App\Models\Clockevent;
use App\Models\Clockday;
use App\Models\Clockfunded;
use App\Models\Clockemployeefunded;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\Employeevacation;
use App\Models\Employeeattest;
use App\Models\Employeeabsence;
use App\Models\Employeeallowance;
use App\Models\Employeeeasy;

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
    public $clockemployee_journey;
    public $clockemployee_delay_total;
    public $clockemployee_extra_total;
    public $clockemployee_balance_total;
    public $clockemployee_note;
    public $clockemployee_authorized;
    public $clockemployee_company_name;
    public $clockemployee_start_decode;
    public $clockemployee_end_decode;
    public $clockemployee_clock_start;
    public $clockemployee_clock_end;

    public $note;
    public $date_start;
    public $date_end;
    public $discount;
    public $merged;

    public $clock_start;
    public $clock_end;

    public $array_date_input         = [];
    public $array_date_break_start   = [];
    public $array_date_break_end     = [];
    public $array_date_output        = [];
    public $array_date_journey_start = [];
    public $array_date_journey_end   = [];
    public $array_date_journey_break = [];
    public $array_date_allowance     = [];
    public $array_date_delay         = [];
    public $array_date_extra         = [];
    public $array_date_balance       = [];
    public $array_date_events        = [];

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
        $this->clockemployee_journey                = '';
        $this->clockemployee_delay_total            = '';
        $this->clockemployee_extra_total            = '';
        $this->clockemployee_balance_total          = '';
        $this->clockemployee_note                   = '';
        $this->clockemployee_authorized             = '';
        $this->clockemployee_company_name           = '';
        $this->clockemployee_start_decode           = '';
        $this->clockemployee_end_decode             = '';
        $this->clockemployee_clock_start            = '';
        $this->clockemployee_clock_end              = '';

        $this->note       = '';
        $this->date_start = '';
        $this->date_end   = '';
        $this->discount   = '';
        $this->merged     = '';

        $this->clock_start = '';
        $this->clock_end   = '';

        $this->array_date_input         = [];
        $this->array_date_break_start   = [];
        $this->array_date_break_end     = [];
        $this->array_date_output        = [];
        $this->array_date_journey_start = [];
        $this->array_date_journey_end   = [];
        $this->array_date_journey_break = [];
        $this->array_date_allowance     = [];
        $this->array_date_delay         = [];
        $this->array_date_extra         = [];
        $this->array_date_balance       = [];
        $this->array_date_events        = [];
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
        //$end   = Clock::orderBy('end', 'DESC')->first()->end;
        //$start = General::subMonths($end, "3");

        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Clock::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_3' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_3' => Auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Clock::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                            ])->whereBetween('end', ['2024-01-01', '2024-09-01'])->orderBy('id', 'DESC')->paginate(1),
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
     * addFunded()
     *  registerFunded()
     */
    public function addFunded(int $clock_id)
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
        public function registerFunded()
        {
            // Estende $validatedData.
            $validatedData['clock_id']   = $this->clock_id;
            $validatedData['company_id'] = $this->company_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Clockfunded::validateAdd($data);

            // Cadastra.
            if ($valid) Clockfunded::add($data);

            // Executa dependências.
            if ($valid) Clockfunded::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * mailFunded()
     *  sendFunded()
     */
    public function mailFunded(int $clock_id)
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
        public function sendFunded()
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
            $valid = Clockfunded::validateMail($data);

            // Envia e-mail.
            if ($valid) Clockfunded::mail($data);

            // Executa dependências.
            if ($valid) Clockfunded::dependencyMail($data);

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

        $this->clockemployee_clock_start            = $clockemployee->clock->start;
        $this->clockemployee_clock_end              = $clockemployee->clock->end;

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
            $data['config']['title'] = 'Abono';
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
        $this->clockemployee_journey                = $clockemployee->employee->journey;
        $this->employee_id                          = $clockemployee->employee_id;
        $this->discount                             = true;

        $this->clockemployee_clock_start            = $clockemployee->clock->start;
        $this->clockemployee_clock_end              = $clockemployee->clock->end;

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
            $validatedData['employee_id']                = $this->employee_id;
            $j                                           = explode(':', $this->clockemployee_journey);
            $validatedData['journey']                    = ($j[0] * 60) + $j[1];
            $this->discount ? $validatedData['discount'] = true : $validatedData['discount'] = false;

            // Define $data.
            $data['config']['title'] = 'Folga';
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
     * editClockEmployee()
     *  modernizeClockEmployee()
     */
    public function editClockEmployee(int $clockemployee_id)
    {
        // Funcionário.
        $clockemployee = Clockemployee::find($clockemployee_id);

        // Inicializa propriedades dinâmicas.
        $this->clockemployee_id                     = $clockemployee->id;
        $this->clockemployee_clock_id               = $clockemployee->clock_id ;
        $this->clockemployee_employee_id            = $clockemployee->employee_id;
        $this->clockemployee_employee_name          = $clockemployee->employee_name;
        $this->clockemployee_employee_pis           = $clockemployee->employee->pis;
        $this->clockemployee_journey_start_week     = $clockemployee->journey_start_week;
        $this->clockemployee_journey_end_week       = $clockemployee->journey_end_week;
        $this->clockemployee_journey_start_saturday = $clockemployee->journey_start_saturday;
        $this->clockemployee_journey_end_saturday   = $clockemployee->journey_end_saturday;
        $this->note                                 = $clockemployee->note;
        $this->clock_start                          = $clockemployee->clock->start;
        $this->clock_end                            = $clockemployee->clock->end;
        $this->clockemployee_company_name           = $clockemployee->clock->company_name;
        $this->clockemployee_start_decode           = General::decodeDate($clockemployee->clock->start);
        $this->clockemployee_end_decode             = General::decodeDate($clockemployee->clock->end);

        $date = $this->clock_start;
        while($date <= $this->clock_end):
            $clockday = Clockday::where(['clock_id' => $this->clockemployee_clock_id, 'employee_id' => $this->clockemployee_employee_id, 'date' => $date])->first();

            $this->array_date_input[$date]       = $clockday->input;
            $this->array_date_break_start[$date] = $clockday->break_start;
            $this->array_date_break_end[$date]   = $clockday->break_end;
            $this->array_date_output[$date]      = $clockday->output;

            $this->array_date_journey_start[$date] = $clockday->journey_start;
            $this->array_date_journey_end[$date]   = $clockday->journey_end;
            $this->array_date_journey_break[$date] = $clockday->journey_break;

            // Incrementa $date.
            $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));  
        endwhile;
    }
        public function modernizeClockEmployee()
        {
            // Estende $validatedData.
            $validatedData['clockemployee_id'] = $this->clockemployee_id;
            $validatedData['clock_id']         = $this->clockemployee_clock_id;
            $validatedData['employee_id']      = $this->clockemployee_employee_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Percorre todas as datas do Funcionário.
            $date = $this->clock_start;
            while($date <= $this->clock_end):
                // Estende $data.
                $data['date']          =  $date;
                $data['input']         =  $this->array_date_input[$date];
                $data['break_start']   =  $this->array_date_break_start[$date];
                $data['break_end']     =  $this->array_date_break_end[$date];
                $data['output']        =  $this->array_date_output[$date];
                $data['journey_start'] =  $this->array_date_journey_start[$date];
                $data['journey_end']   =  $this->array_date_journey_end[$date];
                $data['journey_break'] =  $this->array_date_journey_break[$date];

                // Valida atualização.
                $valid = Clockday::validateEdit($data);

                // Atualiza.
                if ($valid) Clockday::edit($data);

                // Executa dependências.
                if ($valid) Clockday::dependencyEdit($data);

                // Incrementa $date.
                $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));
            endwhile;

            // Percorre todos os dias do Ponto do Funcionário.
            if(Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id'], 'authorized' => false])->doesntExist()):
                // Estende $data.
                $data['clock_id']         = $data['validatedData']['clock_id'];
                $data['employee_id']      = $data['validatedData']['employee_id'];
                $data['clockemployee_id'] = $data['validatedData']['clockemployee_id'];

                // Inicializa variáveis.
                $allowance_minuts = 0;
                $delay_minuts     = 0;
                $extra_minuts     = 0;
                $balance_minuts   = 0;

                // Percorre todas as datas do Funcionário.
                foreach(Clockday::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id']])->get() as $key => $clockday):
                    // Abono.
                    if(!empty($clockday->allowance)):
                        $al = explode(':', $clockday->allowance);
                        $allowance_minuts += (($al[0] * 60) + $al[1]);
                    endif;

                    // Atraso.
                    if(!empty($clockday->delay)):
                        $de = explode(':', $clockday->delay);
                        $delay_minuts += (($de[0] * 60) + $de[1]);
                    endif;

                    // Extra.
                    if(!empty($clockday->extra)):
                        $ex = explode(':', $clockday->extra);
                        $extra_minuts += (($ex[0] * 60) + $ex[1]);
                    endif;

                    // Saldo.
                    if(!empty($clockday->balance)):
                        if($clockday->balance[0] == '+'):
                            $explode = explode('+', $clockday->balance);
                            $ba = explode(':', $explode[1]);
                            $balance_minuts += (($ba[0] * 60) + $ba[1]);
                        elseif($clockday->balance[0] == '-'):
                            $explode = explode('-', $clockday->balance);
                            $ba = explode(':', $explode[1]);
                            $balance_minuts += ((($ba[0] * 60) + $ba[1]) * -1);
                        endif;
                    endif;
                endforeach;

                // Abono.
                $al_hour  = $allowance_minuts / 60;
                $al_hour  = (int)$al_hour;
                $al_minut = $allowance_minuts % 60;
                $allowance_total = str_pad($al_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($al_minut, 2 ,'0' , STR_PAD_LEFT);

                // Atraso.
                $de_hour  = $delay_minuts / 60;
                $de_hour  = (int)$de_hour;
                $de_minut = $delay_minuts % 60;
                $delay_total = str_pad($de_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($de_minut, 2 ,'0' , STR_PAD_LEFT);

                // Extra.
                $ex_hour  = $extra_minuts / 60;
                $ex_hour  = (int)$ex_hour;
                $ex_minut = $extra_minuts % 60;
                $extra_total = str_pad($ex_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($ex_minut, 2 ,'0' , STR_PAD_LEFT);

                // Saldo.
                if($balance_minuts > 0):
                    $ba_hour  = $balance_minuts / 60;
                    $ba_hour  = (int)$ba_hour;
                    $ba_minut = $balance_minuts % 60;

                    $signal = '+';
                elseif($balance_minuts < 0):
                    $balance_minuts = abs($balance_minuts);
                    $ba_hour  = $balance_minuts / 60;
                    $ba_hour  = (int)$ba_hour;
                    $ba_minut = $balance_minuts % 60;

                    $signal = '-';
                else:
                    $ba_hour  = 0;
                    $ba_minut = 0;

                    $signal = '';
                endif;
                $balance_total = $signal . str_pad($ba_hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($ba_minut, 2 ,'0' , STR_PAD_LEFT);

                // Atualiza Funcionário de Ponto.
                Clockemployee::where(['clock_id' => $data['validatedData']['clock_id'], 'employee_id' => $data['validatedData']['employee_id']])->update([
                    'allowance_total' => $allowance_total,
                    'delay_total'     => $delay_total,
                    'extra_total'     => $extra_total,
                    'balance_total'   => $balance_total,
                ]);

                // Gera o PDF.
                Clockemployee::generatePdf($data);
            endif;

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editNoteEmployeeNote()
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

    /**
     * mailEmployee()
     *  sendEmployee()
     */
    public function mailEmployee(int $clockemployee_id)
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
        public function sendEmployee()
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
            $valid = Clockemployee::validateMail($data);

            // Envia e-mail.
            if ($valid) Clockemployee::mail($data);

            // Executa dependências.
            if ($valid) Clockemployee::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
