<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Clockregistryemployee;
use App\Models\Clockregistry;
use App\Models\Employee;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClockregistryemployeeShow extends Component
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

    public $month;
    public $month_end;
    public $txt;

    public $employee_id;
    public $pis;
    public $name;
    public $time;

    public $array_events = [];
    public $times_more;

    public $date;
    public $input;
    public $break_start;
    public $break_end;
    public $output;

    /**
     * Construtor.
     */
    public function mount($config){
        $this->config = $config;
        $this->month  = date('Y-m');
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

            'employee_id' => ['required'],
            'time'        => ['required'],

            'txt' => ['file', 'required'],

            'date'        => ['date', 'required'],
            'input'       => ['required'],
            'break_start' => ['required'],
            'break_end'   => ['required'],
            'output'      => ['required'],
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

        $this->txt = '';

        $this->employee_id  = '';
        $this->pis          = '';
        $this->name         = '';
        $this->time         = '';

        $this->array_events = [];
        $this->times_more   = '';

        $this->month_end   = '';

        $this->date        = '';
        $this->input       = '';
        $this->break_start = '';
        $this->break_end   = '';
        $this->output      = '';
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
            'existsItem'   => Clockregistry::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_3' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_3' => Auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employee::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                                ['status', 1],
                            ])->orderBy('name', 'ASC')->paginate(100),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        // Ultimo dia do mês.
        $x = explode('-', $this->month);
        $this->month_end = cal_days_in_month(CAL_GREGORIAN, $x[1], $x[0]);
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'employee_id' => ['required'],
                'date'        => ['required'],
                'time'        => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_name'] = Employee::find($validatedData['employee_id'])->name;

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
                'txt'        => ['file', 'required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = $txtArray = Clockregistry::validateAddTxt($data);

            // Estende $data
            if ($valid) $data['txtArray'] = $txtArray;

            // Cadastra.
            if ($valid) Clockregistry::addTxt($data);

            // Executa dependências.
            if ($valid) Clockregistry::dependencyAddTxt($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/clockregistryemployee');
        }

    /**
     * editDate()
     *  modernizeDate()
     */
    public function editDate(int $id)
    {
        // Funcionário.
        $Employee = Employee::find($id);

        // Define propriedades dinâmicas.
        $this->employee_id = $Employee->id;
        $this->pis         = $Employee->pis;
        $this->name        = $Employee->name;

        // Último dia do mês.
        $x = explode('-', $this->month);
        $this->month_end = cal_days_in_month(CAL_GREGORIAN, $x[1], $x[0]);
    }
        public function modernizeDate()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date'        => ['date', 'required'],
                'input'       => ['required'],
                'break_start' => ['required'],
                'break_end'   => ['required'],
                'output'      => ['required'],
            ]);

            // Estende validatedData.
            $data['validatedData']['employee_id'] = $this->employee_id;
            $data['validatedData']['type']        = 'alternative';

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Pointevent::validateAddDate($data);

            // Cadastra.
            if ($valid) Pointevent::addDate($data);

            // Executa dependências.
            if ($valid) Pointevent::dependencyAddDate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editMonth()
     *  modernizeMonth()
     */
    public function editMonth(int $id)
    {
        // Funcionário.
        $Employee = Employee::find($id);

        // Define propriedades dinâmicas.
        $this->pis  = $Employee->pis;
        $this->name = $Employee->name;

        // Variáveis úteis.
        $x     = explode('-', $this->month); 
        $month = $x[1]; 
        $year  = $x[0];
        $days  = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $start = $this->month . '-01';
        $end   = $this->month . '-' . $days;

        $this->times_more = 0;
        $date = $start;
        // Percorre todas as dias do mês.
        while($date <= $end):
            // Eventos do Funcionário na data.
            $events = Pointevent::where(['employee_id' => $id, 'date' => $date])->orderBy('time', 'ASC')->get();

            // Verifica se extistem eventos do Funcionário na data.
            if($events->count() > 0):
                // Eventos do Funcionário na data.
                $events = Pointevent::where(['employee_id' => $id, 'date' => $date])->orderBy('time', 'ASC')->get();

                // Percorre todos os eventos do Funcionário na data.
                foreach($events as $key => $event):
                    // Ordena eventos por data.
                    $this->array_events[$date][] = $event;
                endforeach;
            endif;

            // Define a maior quantidade de eventos em um dia.
            if ($events->count() > $this->times_more) $this->times_more = $events->count();

            // Incrementa $date.
            $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));
        endwhile;
    }
        public function modernizeMonth()
        {
            // ...
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
            // ...
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
            // ...
        }
}
