<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Audit;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class AuditShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'user_name';

    public $report_id;
    public $mail;
    public $comment;

    public $audit_id;
    public $user_id;
    public $user_name;
    public $page_id;
    public $page_name;
    public $extensive;
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

            'user_id'   => ['required'],
            'page_id'   => ['required'],
            'extensive' => ['required', 'between:3,255'],
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

        $this->audit_id  = '';
        $this->user_id   = '';
        $this->user_name = '';
        $this->page_id   = '';
        $this->page_name = '';
        $this->created   = '';
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
            'existsItem'   => Audit::where('user_name', '!=', 'MASTER')->exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(20)->get(),
            'list'         => Audit::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['user_name', '!=', 'MASTER'],
                            ])->orderBy('id', 'DESC')->paginate(12),
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
            //...
        }

    /** 
     * detail()
     */
    public function detail(int $audit_id)
    {
        //...
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $audit_id)
    {
        //...
    }
        public function modernize()
        {
            //...
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $audit_id)
    {
        //...
    }
        public function exclude()
        {
            //...
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
            $valid = Audit::validateGenerate($data);

            // Gera relatório.
            if($valid) Audit::generate($data);

            // Executa dependências.
            if($valid) Audit::dependencyGenerate($data);

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
            $valid = Audit::validateMail($data);

            // Envia e-mail.
            if($valid) Audit::mail($data);

            // Executa dependências.
            if($valid) Audit::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
