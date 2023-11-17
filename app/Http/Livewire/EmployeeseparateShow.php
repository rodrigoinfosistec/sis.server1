<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeeseparate;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeseparateShow extends Component
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

    public $employeeseparate_id;
    public $employee_id;
    public $employee_name;
    public $date;
    public $date_encode;
    public $time;
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
            'time'        => ['required'],
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

        $this->employeeseparate_id = '';
        $this->employee_id    = '';
        $this->employee_name  = '';
        $this->date           = '';
        $this->date_encode    = '';
        $this->time           = '';
        $this->created        = '';
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
        // Inicializa variável.
        $array = [];

        // Monta o array.
        foreach(Employee::where('company_id', Auth()->user()->company_id)->get() as $key => $employee):
            $array[] =  $employee->id;
        endforeach;

        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Employeeseparate::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeeseparate::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ])->whereIn('employee_id', $array)->orderBy('date', 'DESC')->paginate(12),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        // Inicializa propriedades dinâmicas.
        $this->time = true;
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'employee_id' => ['required'],
                'date'        => ['required'],
                'time'        => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Employeeseparate::validateAdd($data);

            // Cadastra.
            if ($valid) Employeeseparate::add($data);

            // Executa dependências.
            if ($valid) Employeeseparate::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $employeeseparate_id)
    {
        // Pagamento de Horas.
        $employeeseparate = Employeeseparate::find($employeeseparate_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeseparate_id = $employeeseparate->id;
        $this->employee_id     = $employeeseparate->employee_id;
        $this->employee_name   = $employeeseparate->employee_name;
        $this->date            = General::decodedate($employeeseparate->date);
        $this->date_encode     = $employeeseparate->date;
        $this->time            = $employeeseparate->time;
        $this->created         = $employeeseparate->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeeseparate_id)
    {
        // Pagamento de Horas.
        $employeeseparate = Employeeseparate::find($employeeseparate_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeseparate_id = $employeeseparate->id;
        $this->employee_id     = $employeeseparate->employee_id;
        $this->employee_name   = $employeeseparate->employee_name;
        $this->date            = General::decodedate($employeeseparate->date);
        $this->date_encode     = $employeeseparate->date;
        $this->time            = $employeeseparate->time;
        $this->created         = $employeeseparate->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['employeeseparate_id'] = $this->employeeseparate_id;
            $validatedData['employee_id']     = $this->employee_id;
            $validatedData['employee_name']   = $this->employee_name;
            $validatedData['date']            = $this->date;
            $validatedData['date_encode']     = $this->date_encode;
            $validatedData['time']            = $this->time;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employeeseparate::validateErase($data);

            // Executa dependências.
            if ($valid) Employeeseparate::dependencyErase($data);

            // Exclui.
            if ($valid) Employeeseparate::erase($data);

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
            $valid = Employeeseparate::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employeeseparate::generate($data);

            // Executa dependências.
            if ($valid) Employeeseparate::dependencyGenerate($data);

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
            $valid = Employeeseparate::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeeseparate::mail($data);

            // Executa dependências.
            if ($valid) Employeeseparate::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
