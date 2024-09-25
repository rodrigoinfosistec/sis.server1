<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\General;
use App\Models\Report;

use App\Models\Precencein;

use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class DepositinputShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter;

    public $report_id;
    public $mail;
    public $comment;

    public $presencein_id;
    public $company_name;
    public $company_id;
    public $user_id;
    public $user_name;
    public $date;
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

            'date' => ['required'],
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
        $this->mail = '';
        $this->comment = '';

        $this->presencein_id = '';
        $this->company_name  = '';
        $this->company_id    = '';
        $this->user_id       = '';
        $this->user_name     = '';
        $this->date          = '';
        $this->created       = '';
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
            'existsItem'   => Precencein::where('company_id', auth()->user()->company_id)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->orderBy('date', 'DESC')->limit(12)->get(),
            'list'         => Precencein::where([
                [$this->filter, 'like', '%'. $this->search . '%'],
                ['company_id', Auth()->user()->company_id],
            ])->orderBy('date', 'DESC')->paginate(100),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        $this->date = date('Y-m-d H:i:s');
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'date' => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['company_name'] = Auth()->user()->company_name;
            $validatedData['company_id'] = Auth()->user()->company_id;
            $validatedData['user_id'] = Auth()->user()->id;
            $validatedData['user_name'] = Auth()->user()->name;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Precencein::validateAdd($data);

            // Cadastra.
            if ($valid) Precencein::add($data);

            // Executa dependências.
            if ($valid) Precencein::dependencyAdd($data);

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
            $valid = Precencein::validateGenerate($data);

            // Gera relatório.
            if ($valid) Precencein::generate($data);

            // Executa dependências.
            if ($valid) Precencein::dependencyGenerate($data);

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
            $valid = Precencein::validateMail($data);

            // Envia e-mail.
            if ($valid) Precencein::mail($data);

            // Executa dependências.
            if ($valid) Precencein::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
