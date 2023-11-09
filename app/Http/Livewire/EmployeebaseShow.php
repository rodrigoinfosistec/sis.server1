<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Employee;

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

    public $search = '';
    public $filter = 'name';

    public $report_id;
    public $mail;
    public $comment;

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
            'config'   => $this->config,
            'employee' => Employee::where(['id' => Auth()->User()->employee_id, 'status' => 1])->first() ?? null,
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
            // ...
        }
}
