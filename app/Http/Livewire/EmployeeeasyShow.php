<?php

namespace App\Http\Livewire;

use App\Models\Report;
use App\Models\General;

use App\Models\Employee;
use App\Models\Employeeeasy;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeeasyShow extends Component
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

    public $employeeeasy_id;
    public $employee_id;
    public $employee_name;
    public $journey;
    public $date;
    public $date_encode;
    public $discount;
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

        $this->employeeeasy_id = '';
        $this->employee_id     = '';
        $this->employee_name   = '';
        $this->journey         = '';
        $this->date            = '';
        $this->discount        = '';
        $this->created         = '';
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
            'existsItem'   => Employeeeasy::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employeeeasy::where([
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
        $this->discount = true;
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'employee_id' => ['required'],
                'date'        => ['required'],
            ]);

            // Estende $validatedData.
            $j = explode(':', Employee::find($validatedData['employee_id'])->journey);
            $validatedData['journey']                    = ($j[0] * 60) + $j[1];
            $this->discount ? $validatedData['discount'] = true : $validatedData['discount'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Employeeeasy::validateAdd($data);

            // Cadastra.
            if ($valid) Employeeeasy::add($data);

            // Executa dependências.
            if ($valid) Employeeeasy::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $employeeeasy_id)
    {
        // Férias.
        $employeeeasy = Employeeeasy::find($employeeeasy_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeeasy_id = $employeeeasy->id;
        $this->employee_id     = $employeeeasy->employee_id;
        $this->employee_name   = $employeeeasy->employee_name;
        $this->date            = General::decodedate($employeeeasy->date);
        $this->discount        = $employeeeasy->discount;
        $this->created         = $employeeeasy->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employeeeasy_id)
    {
        // Férias.
        $employeeeasy = Employeeeasy::find($employeeeasy_id);

        // Inicializa propriedades dinâmicas.
        $this->employeeeasy_id = $employeeeasy->id;
        $this->employee_id     = $employeeeasy->employee_id;
        $this->employee_name   = $employeeeasy->employee_name;
        $this->date            = General::decodedate($employeeeasy->date);
        $this->date_encode     = $employeeeasy->date;
        $this->discount        = $employeeeasy->discount;
        $this->created         = $employeeeasy->created_at->format('d/m/Y H:i:s');

        $this->journey         = $employeeeasy->employee->journey;
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['employeeeasy_id'] = $this->employeeeasy_id;
            $validatedData['employee_id']     = $this->employee_id;
            $validatedData['employee_name']   = $this->employee_name;
            $validatedData['date']            = $this->date;
            $validatedData['date_encode']     = $this->date_encode;
            $validatedData['discount']        = $this->discount;
            $validatedData['journey']         = $this->journey;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Employeeeasy::validateErase($data);

            // Executa dependências.
            if ($valid) Employeeeasy::dependencyErase($data);

            // Exclui.
            if ($valid) Employeeeasy::erase($data);

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
            $valid = Employeeeasy::validateGenerate($data);

            // Gera relatório.
            if ($valid) Employeeeasy::generate($data);

            // Executa dependências.
            if ($valid) Employeeeasy::dependencyGenerate($data);

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
            $valid = Employeeeasy::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeeeasy::mail($data);

            // Executa dependências.
            if ($valid) Employeeeasy::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
