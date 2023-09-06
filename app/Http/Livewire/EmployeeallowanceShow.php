<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeeallowance;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeallowanceShow extends Component
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

    public $employeeallowance_id;
    public $employee_id;
    public $employee_name;
    public $date;
    public $start;
    public $end;
    public $merged;
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
            'date'        => ['required'],
            'start'       => ['required'],
            'end'         => ['required'],
            'start'       => ['required'],
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

        $this->employeeallowance_id = '';
        $this->employee_id         = '';
        $this->employee_name       = '';
        $this->date                = '';
        $this->start               = '';
        $this->end                 = '';
        $this->merged              = '';
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
            'existsItem'   => Employeeallowance::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeeallowance::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('date', 'DESC')->paginate(12),
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
                'date'        => ['required'],
                'start'       => ['required'],
                'end'         => ['required'],
            ]);

            // Estende $validatedData.
            $this->merged ? $validatedData['merged'] = true : $validatedData['merged'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

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
     * detail()
     */
    public function detail(int $employeeallowance_id)
    {
        // Férias.
        $employeeallowance = Employeeallowance::find($employeeallowance_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeallowance_id = $employeeallowance->id;
        $this->employee_id          = $employeeallowance->employee_id;
        $this->employee_name        = $employeeallowance->employee_name;
        $this->date                 = General::decodedate($employeeallowance->date);
        $this->start                = $employeeallowance->start;
        $this->end                  = $employeeallowance->end;
        $this->merged               = $employeeallowance->merged;
        $this->created              = $employeeallowance->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeeallowance_id)
    {
        // Férias.
        $employeeallowance = Employeeallowance::find($employeeallowance_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeallowance_id = $employeeallowance->id;
        $this->employee_id          = $employeeallowance->employee_id;
        $this->employee_name        = $employeeallowance->employee_name;
        $this->date                 = General::decodedate($employeeallowance->date);
        $this->start                = $employeeallowance->start;
        $this->end                  = $employeeallowance->end;
        $this->merged               = $employeeallowance->merged;
        $this->created              = $employeeallowance->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['employeeallowance_id'] = $this->employeeallowance_id;
            $validatedData['employee_id']         = $this->employee_id;
            $validatedData['employee_name']       = $this->employee_name;
            $validatedData['date']                = $this->date;
            $validatedData['start']               = $this->start;
            $validatedData['end']                 = $this->end;
            $validatedData['merged']              = $this->merged;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employeeallowance::validateErase($data);

            // Executa dependências.
            if ($valid) Employeeallowance::dependencyErase($data);

            // Exclui.
            if ($valid) Employeeallowance::erase($data);

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
            $valid = Employeeallowance::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employeeallowance::generate($data);

            // Executa dependências.
            if ($valid) Employeeallowance::dependencyGenerate($data);

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
            $valid = Employeeallowance::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeeallowance::mail($data);

            // Executa dependências.
            if ($valid) Employeeallowance::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
