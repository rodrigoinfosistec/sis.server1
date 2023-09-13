<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeevacation;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeevacationShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'employee_name';

    public $report_id;
    public $mail;
    public $comment;

    public $employeevacation_id;
    public $employee_id;
    public $employee_name;
    public $date_start;
    public $date_end;
    public $date_start_encode;
    public $date_end_encode;
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

            'employee_id' => ['required'],
            'date_start'  => ['required'],
            'date_end'    => ['required'],
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

        $this->employeevacation_id = '';
        $this->employee_id         = '';
        $this->employee_name       = '';
        $this->date_start          = '';
        $this->date_end            = '';
        $this->date_start_encode   = '';
        $this->date_end_encode     = '';
        $this->created             = '';
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
            'existsItem'   => Employeevacation::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeevacation::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('date_start', 'DESC')->paginate(12),
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
                'employee_id' => ['required'],
                'date_start'  => ['required'],
                'date_end'    => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

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
     * detail()
     */
    public function detail(int $employeevacation_id)
    {
        // Férias.
        $employeevacation = Employeevacation::find($employeevacation_id);

        // Inicializa propriedades dinâmicas.
        $this->employeevacation_id = $employeevacation->id;
        $this->employee_id         = $employeevacation->employee_id;
        $this->employee_name       = $employeevacation->employee_name;
        $this->date_start          = General::decodedate($employeevacation->date_start);
        $this->date_end            = General::decodedate($employeevacation->date_end);
        $this->created             = $employeevacation->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeevacation_id)
    {
        // Férias.
        $employeevacation = Employeevacation::find($employeevacation_id);

        // Inicializa propriedades dinâmicas.
        $this->employeevacation_id = $employeevacation->id;
        $this->employee_id         = $employeevacation->employee_id;
        $this->employee_name       = $employeevacation->employee_name;
        $this->date_start          = General::decodeDate($employeeabsence->date_start);
        $this->date_end            = General::decodeDate($employeeabsence->date_end);
        $this->date_start_encode   = $employeeabsence->date_start;
        $this->date_end_encode     = $employeeabsence->date_end;
        $this->created             = $employeevacation->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['employeevacation_id'] = $this->employeevacation_id;
            $validatedData['employee_id']         = $this->employee_id;
            $validatedData['employee_name']       = $this->employee_name;
            $validatedData['date_start']          = $this->date_start;
            $validatedData['date_end']            = $this->date_end;
            $validatedData['date_start_encode']   = $this->date_start_encode;
            $validatedData['date_end_encode']     = $this->date_end_encode;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employeevacation::validateErase($data);

            // Executa dependências.
            if ($valid) Employeevacation::dependencyErase($data);

            // Exclui.
            if ($valid) Employeevacation::erase($data);

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
            $valid = Employeevacation::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employeevacation::generate($data);

            // Executa dependências.
            if ($valid) Employeevacation::dependencyGenerate($data);

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
            $valid = Employeevacation::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeevacation::mail($data);

            // Executa dependências.
            if ($valid) Employeevacation::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
