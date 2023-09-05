<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeeattest;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeattestShow extends Component
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

    public $employeeattest_id;
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

        $this->employeeattest_id = '';
        $this->employee_id         = '';
        $this->employee_name       = '';
        $this->date_start          = '';
        $this->date_end            = '';
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
            'existsItem'   => Employeeattest::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeeattest::where([
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
     * detail()
     */
    public function detail(int $employeeattest_id)
    {
        // Férias.
        $employeeattest = Employeeattest::find($employeeattest_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeattest_id = $employeeattest->id;
        $this->employee_id         = $employeeattest->employee_id;
        $this->employee_name       = $employeeattest->employee_name;
        $this->date_start          = General::decodedate($employeeattest->date_start);
        $this->date_end            = General::decodedate($employeeattest->date_end);
        $this->created             = $employeeattest->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeeattest_id)
    {
        // Férias.
        $employeeattest = Employeeattest::find($employeeattest_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeattest_id = $employeeattest->id;
        $this->employee_id         = $employeeattest->employee_id;
        $this->employee_name       = $employeeattest->employee_name;
        $this->date_start          = General::decodedate($employeeattest->date_start);
        $this->date_end            = General::decodedate($employeeattest->date_end);
        $this->created             = $employeeattest->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['employeeattest_id'] = $this->employeeattest_id;
            $validatedData['employee_id']         = $this->employee_id;
            $validatedData['employee_name']       = $this->employee_name;
            $validatedData['date_start']          = $this->date_start;
            $validatedData['date_end']            = $this->date_end;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employeeattest::validateErase($data);

            // Executa dependências.
            if ($valid) Employeeattest::dependencyErase($data);

            // Exclui.
            if ($valid) Employeeattest::erase($data);

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
            $valid = Employeeattest::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employeeattest::generate($data);

            // Executa dependências.
            if ($valid) Employeeattest::dependencyGenerate($data);

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
            $valid = Employeeattest::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeeattest::mail($data);

            // Executa dependências.
            if ($valid) Employeeattest::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
