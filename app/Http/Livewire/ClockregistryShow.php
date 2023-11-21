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
    public $filter = 'employee_id';

    public $report_id;
    public $mail;
    public $comment;

    public $employee_id;
    public $date;
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
        // Inicializa variável.
        $array = [];

        // Monta o array.
        foreach(Employee::where('company_id', Auth()->user()->company_id)->get() as $key => $employee):
            $array[] =  $employee->id;
        endforeach;

        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Employee::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Clockregistry::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ])->whereIn('employee_id', $array)->orderBy('date', 'DESC')->orderBy('time', 'ASC')->paginate(100),
        ]);
    }

    /**
     * addRegistry()
     *  registerRegistry()
     */
    public function addRegistry(int $employee_id)
    {
        // Employee id.
        $this->employee_id = $employee_id;
    }
        public function registerRegistry()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date' => ['required'],
                'time' => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['employee_id'] = $this->employee_id;

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
            // ...
        }
}
