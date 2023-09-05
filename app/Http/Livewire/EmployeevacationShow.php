<?php

namespace App\Http\Livewire;

use App\Models\Report;

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

            'employee_id'   => ['required', 'min:15', 'max:15', 'unique:employeevacations,employee_id,'.$this->employeevacation_id.''],
            'date_start'    => ['required'],
            'date_end'      => ['required'],
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

        $this->employeevacation_id            = '';
        $this->employee_id                    = '';
        $this->employee_name                   = '';
        $this->date_start     = '';
        $this->date_end       = '';
        $this->journey_start_saturday = '';
        $this->journey_end_saturday   = '';
        $this->created                = '';

        $this->txt = '';
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
        return view('livewire.' . $this->config['employee_name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Employeevacation::exists(),
            'existsReport' => Report::where('folder', $this->config['employee_name'])->exists(),
            'reports'      => Report::where('folder', $this->config['employee_name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeevacation::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('employee_name', 'ASC')->paginate(12),
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
                'employee_id'                    => ['required', 'min:15', 'max:15', 'unique:employeevacations'],
                'employee_name'                   => ['required', 'between:3,60'],
                'date_start'     => ['required'],
                'date_end'       => ['required'],
                'journey_start_saturday' => ['required'],
                'journey_end_saturday'   => ['required'],
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
                'txt' => ['file', 'required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = $txtArray = Employeevacation::validateAddTxt($data);

            // Valida.
            if($valid):
                foreach($txtArray as $key => $employeevacation):
                    // Estende $data['validatedData'].
                    $data['validatedData']['employee_id']                    = Employeevacation::encodePis((string)$employeevacation['employee_id']);
                    $data['validatedData']['employee_name']                   = (string)$employeevacation['employee_name'];
                    $data['validatedData']['date_start']     = '08:00';
                    $data['validatedData']['date_end']       = '17:00';
                    $data['validatedData']['journey_start_saturday'] = '08:00';
                    $data['validatedData']['journey_end_saturday']   = '12:00';

                    if(Employeevacation::where('employee_id', $data['validatedData']['employee_id'])->doesntExist()):
                        // Cadastra.
                        if ($valid) Employeevacation::add($data);

                        // Executa dependências.
                        if ($valid) Employeevacation::dependencyAdd($data);
                    endif;
                endforeach;
            endif;

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/employeevacation');
        }

    /** 
     * detail()
     */
    public function detail(int $employeevacation_id)
    {
        // Empresa.
        $employeevacation = Employeevacation::find($employeevacation_id);

        // Inicializa propriedades dinâmicas.
        $this->employeevacation_id            = $employeevacation->id;
        $this->employee_id                    = $employeevacation->employee_id;
        $this->employee_name                   = $employeevacation->employee_name;
        $this->date_start     = $employeevacation->date_start;
        $this->date_end       = $employeevacation->date_end;
        $this->journey_start_saturday = $employeevacation->journey_start_saturday;
        $this->journey_end_saturday   = $employeevacation->journey_end_saturday;
        $this->created                = $employeevacation->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $employeevacation_id)
    {
        // Empresa.
        $employeevacation = Employeevacation::find($employeevacation_id);

        // Inicializa propriedades dinâmicas.
        $this->employeevacation_id            = $employeevacation->id;
        $this->employee_id                    = $employeevacation->employee_id;
        $this->employee_name                   = $employeevacation->employee_name;
        $this->date_start     = $employeevacation->date_start;
        $this->date_end       = $employeevacation->date_end;
        $this->journey_start_saturday = $employeevacation->journey_start_saturday;
        $this->journey_end_saturday   = $employeevacation->journey_end_saturday;
        $this->created                = $employeevacation->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'employee_id'                    => ['required', 'min:15', 'max:15', 'unique:employeevacations,employee_id,'.$this->employeevacation_id.''],
                'employee_name'                   => ['required', 'between:3,60'],
                'date_start'     => ['required'],
                'date_end'       => ['required'],
                'journey_start_saturday' => ['required'],
                'journey_end_saturday'   => ['required'],
            ]);

            // Estende $validatedData
            $validatedData['employeevacation_id'] = $this->employeevacation_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Employeevacation::validateEdit($data);

            // Atualiza.
            if ($valid) Employeevacation::edit($data);

            // Executa dependências.
            if ($valid) Employeevacation::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeevacation_id)
    {
        // Empresa.
        $employeevacation = Employeevacation::find($employeevacation_id);

        // Inicializa propriedades dinâmicas.
        $this->employeevacation_id            = $employeevacation->id;
        $this->employee_id                    = $employeevacation->employee_id;
        $this->employee_name                   = $employeevacation->employee_name;
        $this->date_start     = $employeevacation->date_start;
        $this->date_end       = $employeevacation->date_end;
        $this->journey_start_saturday = $employeevacation->journey_start_saturday;
        $this->journey_end_saturday   = $employeevacation->journey_end_saturday;
        $this->created                = $employeevacation->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['employeevacation_id']            = $this->employeevacation_id;
            $validatedData['employee_id']                    = $this->employee_id;
            $validatedData['employee_name']                   = $this->employee_name;
            $validatedData['date_start']     = $this->date_start;
            $validatedData['date_end']       = $this->date_end;
            $validatedData['journey_start_saturday'] = $this->journey_start_saturday;
            $validatedData['journey_end_saturday']   = $this->journey_end_saturday;

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
