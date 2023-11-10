<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Employee;
use App\Models\Employeebase;
use App\Models\Clockbase;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeebaseShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $mail;
    public $comment;

    public $company;

    public $identify = false;

    /**
     * Construtor.
     */
    public function mount($config){
        $this->config  = $config;
        $this->mail    = 'dpgrupoamm23@gmail.com';
        $this->company = Employee::find(Auth()->User()->employee_id)->company_name;
    }

    /**
     * Valida campos gerais.
     */
    protected function rules()
    {
        return [
            'comment'=> ['required', 'between:2,255'],
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
        $this->comment  = '';
        $this->identify = false;
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
            'config'    => $this->config,
            'employee'  => Employee::where(['id' => Auth()->User()->employee_id, 'status' => 1])->first() ?? null,
            'clockbase' => Clockbase::where(['employee_id' => Auth()->User()->employee_id, 'description' => 'Consolidação Banco de Horas'])->orderBy('id', 'DESC')->first() ?? null,
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        // ...
    }
        public function register()
        {
            // ...
        }

    /** 
     * detail()
     */
    public function detail(int $employee_id)
    {
        // ...
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $employee_id)
    {
        // ...
    }
        public function modernize()
        {
            // ...
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $employee_id)
    {
        // ...
    }
        public function exclude()
        {
            // ...
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
            // ...
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
                'comment' => ['required', 'between:2,255'],
            ]);

            // Estende.
            $validatedData['mail']          = $this->mail;
            $validatedData['company']       = $this->company;
            $validatedData['employee_name'] = 'teste'; //Employee::find(Auth()->User()->employee_id)->first()->name;
            $this->identify ? $validatedData['identify'] = true : $validatedData['identify'] = false;

            // Define $data
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida envio do e-mail.
            $valid = Employeebase::validateMail($data);

            // Envia e-mail.
            if ($valid) Employeebase::mail($data);

            // Executa dependências.
            if ($valid) Employeebase::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
