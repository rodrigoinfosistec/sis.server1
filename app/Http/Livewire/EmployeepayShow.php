<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeepay;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeepayShow extends Component
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

    public $employeepay_id;
    public $employee_id;
    public $employee_name;
    public $date;
    public $date_encode;
    public $time;
    public $minut = 0;
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
            'time'        => ['required', 'numeric', 'min:0'],
            'minut'       => ['required', 'numeric', 'min:0'],
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

        $this->employeepay_id = '';
        $this->employee_id    = '';
        $this->employee_name  = '';
        $this->date           = '';
        $this->date_encode    = '';
        $this->time           = '';
        $this->minut          = 0;
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
            'existsItem'   => Employeepay::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeepay::where([
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
                'time'        => ['required', 'numeric', 'min:0'],
                'minut'       => ['required', 'numeric', 'min:0'],
            ]);

            // Monta $validatedData['time']
            $validatedData['time_old'] = $validatedData['time'];
            $validatedData['time'] = $validatedData['time'] . ':' . $validatedData['minut'];

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Employeepay::validateAdd($data);

            // Cadastra.
            if ($valid) Employeepay::add($data);

            // Executa dependências.
            if ($valid) Employeepay::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $employeepay_id)
    {
        // Pagamento de Horas.
        $employeepay = Employeepay::find($employeepay_id);

        // Inicializa propriedades dinâmicas.
        $this->employeepay_id = $employeepay->id;
        $this->employee_id     = $employeepay->employee_id;
        $this->employee_name   = $employeepay->employee_name;
        $this->date            = General::decodedate($employeepay->date);
        $this->date_encode     = $employeepay->date;
        $this->time            = $employeepay->time;
        $this->created         = $employeepay->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeepay_id)
    {
        // Pagamento de Horas.
        $employeepay = Employeepay::find($employeepay_id);

        // Inicializa propriedades dinâmicas.
        $this->employeepay_id = $employeepay->id;
        $this->employee_id     = $employeepay->employee_id;
        $this->employee_name   = $employeepay->employee_name;
        $this->date            = General::decodedate($employeepay->date);
        $this->date_encode     = $employeepay->date;
        $this->time            = $employeepay->time;
        $this->created         = $employeepay->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['employeepay_id'] = $this->employeepay_id;
            $validatedData['employee_id']     = $this->employee_id;
            $validatedData['employee_name']   = $this->employee_name;
            $validatedData['date']            = $this->date;
            $validatedData['date_encode']     = $this->date_encode;
            $validatedData['time']            = $this->time;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employeepay::validateErase($data);

            // Executa dependências.
            if ($valid) Employeepay::dependencyErase($data);

            // Exclui.
            if ($valid) Employeepay::erase($data);

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
            $valid = Employeepay::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employeepay::generate($data);

            // Executa dependências.
            if ($valid) Employeepay::dependencyGenerate($data);

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
            $valid = Employeepay::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeepay::mail($data);

            // Executa dependências.
            if ($valid) Employeepay::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
