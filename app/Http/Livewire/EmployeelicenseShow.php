<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeelicense;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeelicenseShow extends Component
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

    public $employeelicense_id;
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

        $this->employeelicense_id = '';
        $this->employee_id        = '';
        $this->employee_name      = '';
        $this->date_start         = '';
        $this->date_end           = '';
        $this->date_start_encode  = '';
        $this->date_end_encode    = '';
        $this->created            = '';
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
            'existsItem'   => Employeelicense::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeelicense::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->whereIn('employee_id', $array)->orderBy('date_start', 'DESC')->paginate(12),
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
            $valid = Employeelicense::validateAdd($data);

            // Cadastra.
            if ($valid) Employeelicense::add($data);

            // Executa dependências.
            if ($valid) Employeelicense::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $employeelicense_id)
    {
        // Férias.
        $employeelicense = Employeelicense::find($employeelicense_id);

        // Inicializa propriedades dinâmicas.
        $this->employeelicense_id = $employeelicense->id;
        $this->employee_id        = $employeelicense->employee_id;
        $this->employee_name      = $employeelicense->employee_name;
        $this->date_start         = General::decodedate($employeelicense->date_start);
        $this->date_end           = General::decodedate($employeelicense->date_end);
        $this->created            = $employeelicense->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeelicense_id)
    {
        // Férias.
        $employeelicense = Employeelicense::find($employeelicense_id);

        // Inicializa propriedades dinâmicas.
        $this->employeelicense_id = $employeelicense->id;
        $this->employee_id        = $employeelicense->employee_id;
        $this->employee_name      = $employeelicense->employee_name;
        $this->date_start         = General::decodeDate($employeelicense->date_start);
        $this->date_end           = General::decodeDate($employeelicense->date_end);
        $this->date_start_encode  = $employeelicense->date_start;
        $this->date_end_encode    = $employeelicense->date_end;
        $this->created            = $employeelicense->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['employeelicense_id'] = $this->employeelicense_id;
            $validatedData['employee_id']        = $this->employee_id;
            $validatedData['employee_name']      = $this->employee_name;
            $validatedData['date_start']         = $this->date_start;
            $validatedData['date_end']           = $this->date_end;
            $validatedData['date_start_encode']  = $this->date_start_encode;
            $validatedData['date_end_encode']    = $this->date_end_encode;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employeelicense::validateErase($data);

            // Executa dependências.
            if ($valid) Employeelicense::dependencyErase($data);

            // Exclui.
            if ($valid) Employeelicense::erase($data);

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
            $valid = Employeelicense::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employeelicense::generate($data);

            // Executa dependências.
            if ($valid) Employeelicense::dependencyGenerate($data);

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
            $valid = Employeelicense::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeelicense::mail($data);

            // Executa dependências.
            if ($valid) Employeelicense::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
