<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Pointevent;
use App\Models\Employee;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class PointeventShow extends Component
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

    public $month;
    public $txt;

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

            'txt' => ['file', 'required'],
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
        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Pointevent::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_3' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_3' => Auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Employee::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                                ['status', 1],
                            ])->orderBy('name', 'ASC')->paginate(100),
        ]);
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
                'txt'        => ['file', 'required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = $txtArray = Pointevent::validateAddTxt($data);

            // Estende $data
            if ($valid) $data['txtArray'] = $txtArray;

            // Cadastra.
            if ($valid) Pointevent::addTxt($data);

            // Executa dependências.
            if ($valid) Pointevent::dependencyAddTxt($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/pointevent');
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
