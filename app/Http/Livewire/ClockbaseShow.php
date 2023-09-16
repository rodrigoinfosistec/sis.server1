<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Employee;
use App\Models\Employeeeasy;
use App\Models\Clockbase;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClockbaseShow extends Component
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
    public $journey_start_week;
    public $journey_end_week;
    public $journey_start_saturday;
    public $journey_end_saturday;
    public $clock_type;
    public $created;

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
            'pis'                    => ['required', 'min:15', 'max:15', 'unique:employees,pis,'.$this->employee_id.''],
            'name'                   => ['required', 'between:3,60'],
            'journey_start_week'     => ['required'],
            'journey_end_week'       => ['required'],
            'journey_start_saturday' => ['required'],
            'journey_end_saturday'   => ['required'],
            'clock_type'             => ['required'],
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
        $this->journey_start_week     = '';
        $this->journey_end_week       = '';
        $this->journey_start_saturday = '';
        $this->journey_end_saturday   = '';
        $this->clock_type             = '';
        $this->created                = '';
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
            'existsReport' => Report::where('folder', 'clockbase')->exists(),
            'reports'      => Report::where('folder', 'clockbase')->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employee::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                            ])->orderBy('name', 'ASC')->paginate(10),
        ]);
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
        $this->clock_type             = $employee->clock_type;
        $this->created                = $employee->created_at->format('d/m/Y H:i:s');
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
