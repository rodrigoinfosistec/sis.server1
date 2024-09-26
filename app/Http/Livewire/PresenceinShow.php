<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\General;
use App\Models\Report;

use App\Models\Presencein;
use App\Models\Presenceinemployee;

use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class PresenceinShow extends Component
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

    public $presencein_id;
    public $company_name;
    public $company_id;
    public $user_id;
    public $user_name;
    public $date;
    public $created;

    public $array_presenceinemployee = [];

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

        $this->array_presenceinemployee = [];
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
            'existsItem'   => Presencein::where('company_id', auth()->user()->company_id)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Presencein::where([
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
        $this->date = date('Y-m-d');
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
            $valid = Presencein::validateAdd($data);

            // Cadastra.
            if ($valid) Presencein::add($data);

            // Executa dependências.
            if ($valid) Presencein::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * editEmployee()
     *  modernizeEmployee()
     */
    public function editEmployee(int $presencein_id)
    {
        // Presença Entrada.
        $presencein = Presencein::find($presencein_id);

        // Inicializa propriedades dinâmicas.
        $this->presencein_id  = $presencein->id;
        $this->company_name   = $presencein->company_name;
        $this->company_id     = $presencein->company_id;
        $this->user_id        = $presencein->user_id;
        $this->user_name      = $presencein->user_name;
        $this->date           = $presencein->date;
        $this->created        = $presencein->created_at->format('d/m/Y H:i:s');

        // Percorre os Funcionarios da Presença Entrada.
        foreach(Presenceinemployee::where('presencein_id', $this->presencein_id)->orderBy('is_present', 'DESC')->orderBy('employee_name', 'ASC')->get() as $key => $presenceinemployee):
            // Relaciona Grupo de Usuário à Página.
            $presenceinemployee->is_present ? $this->array_presenceinemployee[$presenceinemployee->id] = true: $this->array_presenceinemployee[$presenceinemployee->id] = false;
        endforeach;
    }
        public function modernizeEmployee()
        {
            // Percorre os Funcionários da Presença Entrada.
            foreach(Presenceinemployee::where(['presencein_id' => $this->presencein_id, 'is_present' => false])->get() as $key => $presenceinemployee):
                // Verifica se Funcionário está Presente.
                if($this->array_presenceinemployee[$presenceinemployee->id] == true):
                    // Assinala Precença do Funcionário.
                    Presenceinemployee::editPresence($presenceinemployee->id);
                endif;
            endforeach;

            // Mensagem.
            $message = 'Presença Entrada atualizada com sucesso.';
            session()->flash('message', $message);
            session()->flash('color', 'success');

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
            $valid = Presencein::validateGenerate($data);

            // Gera relatório.
            if ($valid) Presencein::generate($data);

            // Executa dependências.
            if ($valid) Presencein::dependencyGenerate($data);

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
            $valid = Presencein::validateMail($data);

            // Envia e-mail.
            if ($valid) Presencein::mail($data);

            // Executa dependências.
            if ($valid) Presencein::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
