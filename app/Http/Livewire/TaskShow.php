<?php

namespace App\Http\Livewire;

use App\Models\Report;

use App\Models\Page;
use App\Models\Task;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaskShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'activity_name';

    public $report_id;
    public $mail;
    public $comment;

    public $task_id;
    public $activity_name;
    public $activity_id;
    public $requester_id;
    public $requester_name;
    public $responsible_id;
    public $responsible_name;
    public $description;
    public $priority;
    public $is_completed;
    public $due_date;
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

            'activity_id'    => ['required'],
            'requester_id'   => ['required'],
            'responsible_id' => ['required'],
            'description'    => ['required', 'between:2,255'],
            'priority'       => ['required'],
            'due_date'       => ['required'],
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

        $this->task_id          = '';
        $this->activity_name    = '';
        $this->activity_id      = '';
        $this->requester_id     = '';
        $this->requester_name   = '';
        $this->responsible_id   = '';
        $this->responsible_name = '';
        $this->description      = '';
        $this->priority         = '';
        $this->is_completed     = '';
        $this->due_date         = '';
        $this->created          = '';
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
            'existsItem'   => Task::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Task::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('created_at', 'DESC')->paginate(12),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        // Valores padrão.
        $this->activity_id = Auth()->user()->id;
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'activity_id'    => ['required'],
                'requester_id'   => ['required'],
                'responsible_id' => ['required'],
                'description'    => ['required', 'between:2,255'],
                'priority'       => ['required'],
                'due_date'       => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Task::validateAdd($data);

            // Cadastra.
            if ($valid) Task::add($data);

            // Executa dependências.
            if ($valid) Task::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $task_id)
    {
        // Tarefa.
        $task = Task::find($task_id);

        // Inicializa propriedades dinâmicas.
        $this->task_id = $task->id;
        $this->activity_name    = $task->activity_name;
        $this->activity_id      = $task->activity_id ;
        $this->requester_id     = $task->requester_id ;
        $this->requester_name   = $task->requester_name;
        $this->responsible_id   = $task->responsible_id ;
        $this->responsible_name = $task->responsible_name;
        $this->description      = $task->description ;
        $this->priority         = $task->priority;
        $this->is_completed     = $task->is_completed ;
        $this->due_date         = $task->due_date;
        $this->created          = $task->created_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $task_id)
    {
        // Tarefa.
        $task = Task::find($task_id);

        // Inicializa propriedades dinâmicas.
        $this->task_id = $task->id;
        $this->activity_name    = $task->activity_name;
        $this->activity_id      = $task->activity_id ;
        $this->requester_id     = $task->requester_id ;
        $this->requester_name   = $task->requester_name;
        $this->responsible_id   = $task->responsible_id ;
        $this->responsible_name = $task->responsible_name;
        $this->description      = $task->description ;
        $this->priority         = $task->priority;
        $this->is_completed     = $task->is_completed ;
        $this->due_date         = $task->due_date;
        $this->created          = $task->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'activity_id'    => ['required'],
                'requester_id'   => ['required'],
                'responsible_id' => ['required'],
                'description'    => ['required', 'between:2,255'],
                'priority'       => ['required'],
                'due_date'       => ['required'],
            ]);

            // Estende $validatedData
            $validatedData['task_id'] = $this->task_id;
            $this->status ? $validatedData['is_completed'] = true : $validatedData['is_completed'] = false;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Task::validateEdit($data);

            // Atualiza.
            if ($valid) Task::edit($data);

            // Executa dependências.
            if ($valid) Task::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $task_id)
    {
        // Tarefa.
        $task = Task::find($task_id);

        // Inicializa propriedades dinâmicas.
        $this->task_id = $task->id;
        $this->activity_name    = $task->activity_name;
        $this->activity_id      = $task->activity_id ;
        $this->requester_id     = $task->requester_id ;
        $this->requester_name   = $task->requester_name;
        $this->responsible_id   = $task->responsible_id ;
        $this->responsible_name = $task->responsible_name;
        $this->description      = $task->description ;
        $this->priority         = $task->priority;
        $this->is_completed     = $task->is_completed ;
        $this->due_date         = $task->due_date;
        $this->created          = $task->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['task_id']          = $this->id;
            $validatedData['activity_name']    = $this->activity_name;
            $validatedData['activity_id']      = $this->activity_id ;
            $validatedData['requester_id']     = $this->requester_id ;
            $validatedData['requester_name']   = $this->requester_name;
            $validatedData['responsible_id']   = $this->responsible_id ;
            $validatedData['responsible_name'] = $this->responsible_name;
            $validatedData['description']      = $this->description ;
            $validatedData['priority']         = $this->priority;
            $validatedData['is_completed']     = $this->is_completed ;
            $validatedData['due_date']          = $this->due_date;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Task::validateErase($data);

            // Executa dependências.
            if ($valid) Task::dependencyErase($data);

            // Exclui.
            if ($valid) Task::erase($data);

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
            $valid = Task::validateGenerate($data);

            // Gera relatório.
            if ($valid) Task::generate($data);

            // Executa dependências.
            if ($valid) Task::dependencyGenerate($data);

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
            $valid = Task::validateMail($data);

            // Envia e-mail.
            if ($valid) Task::mail($data);

            // Executa dependências.
            if ($valid) Task::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
