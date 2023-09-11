<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Clock;
use App\Models\Clockemployee;
use App\Models\Holiday;
use App\Models\Employee;

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
                            ])->orderBy('company_name', 'ASC')->paginate(1),
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
}
