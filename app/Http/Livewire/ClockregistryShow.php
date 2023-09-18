<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Employee;
use App\Models\Clockregistry;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClockregistryShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'date';

    public $report_id;
    public $mail;
    public $comment;

    public $employee_id;
    public $date;
    public $time;
    public $code;
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

            'code' => ['required', 'between:4,10'],
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

        $this->employee_id = '';
        $this->date        = '';
        $this->time        = '';
        $this->code        = '';
        $this->created     = '';
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
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Clockregistry::where([
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
                'code' => ['required', 'between:4,10'],
            ]);

            // Estende $validatedData.
            if(Employee::where('code', $validatedData['code'])->exists()):
                $validatedData['employee_id'] = Employee::where('code', $validatedData['code'])->first()->id;
                $validatedData['date']        = date('Y-m-d');
                $validatedData['time']        = date('H:i');
                $validatedData['valid']       = true;
            else:
                $validatedData['valid'] = false;
            endif;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Clockregistry::validateAdd($data);

            // Cadastra.
            if ($valid) Clockregistry::add($data);

            // Executa dependências.
            if ($valid) Clockregistry::dependencyAdd($data);

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
