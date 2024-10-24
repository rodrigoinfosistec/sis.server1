<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\In;
use App\Models\Inproduce;
use App\Models\Produce;
use App\Models\Producebrand;
use App\Models\Producedeposit;
use App\Models\Deposit;
use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class InShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'producebrand_name';

    public $report_id;
    public $mail;
    public $comment;

    public $in_id;
    public $deposit_id;
    public $deposit_name;
    public $producebrand_id;
    public $producebrand_name;
    public $company_id;
    public $user_id;
    public $user_name;
    public $observation;
    public $finished;
    public $created;
    public $updated;

    public $produce_id;
    public $produce_name;
    public $quantity;

    public $array_produce_id = [];
    public $array_produce_name = [];
    public $array_produce_reference = [];
    public $array_produce_ean = [];
    public $array_produce_quantity = [];

    public $array_produce_score = [];

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

            'deposit_id' => ['required'],
            'producebrand_id' => ['required'],
            'observation' => ['nullable', 'between:2,255'],

            'produce_id' => ['required'],
            'quantity' => ['required'],
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

        $this->in_id = '';
        $this->deposit_id = '';
        $this->deposit_name = '';
        $this->producebrand_id = '';
        $this->producebrand_name = '';
        $this->user_id = '';
        $this->user_name = '';
        $this->observation = '';
        $this->finished = '';
        $this->created = '';
        $this->updated = '';

        $this->produce_id = '';
        $this->produce_name = '';
        $this->quantity = '';

        $this->array_produce_id = [];
        $this->array_produce_name = [];
        $this->array_produce_reference = [];
        $this->array_produce_ean = [];
        $this->array_produce_quantity = [];

        $this->array_produce_score = [];
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
            'existsItem'   => In::where('company_id', auth()->user()->company_id)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_2' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_2' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => In::where([
                            ['company_id', auth()->user()->company_id],
                            [$this->filter, 'like', '%'. $this->search . '%'],
                        ])->orderBy('finished', 'ASC')->orderBy('id', 'DESC')->paginate(100),
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
                'deposit_id' => ['required'],
                'producebrand_id' => ['required'],
                'observation' => ['nullable', 'between:2,255'],
            ]);

            // Estende $validatedData.
            if(!isset($validatedData['observation'])):
                $validatedData['observation'] = '';
            endif;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = In::validateAdd($data);

            // Cadastra.
            if ($valid) $in_id = In::add($data);

            // Estende $data['validatedData'].
            if ($valid) $data['validatedData']['in_id'] = $in_id;

            // Executa dependências.
            if ($valid) In::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addProduce()
     *  registerProduce()
     */
    public function addProduce(int $in_id)
    {
        // Entrada.
        $in = In::find($in_id);

        // Inicializa propriedades dinâmicas.
        $this->in_id = $in_id;
        $this->deposit_id = $in ->deposit_id;
        $this->deposit_name = $in ->deposit_name;
        $this->producebrand_id = $in ->producebrand_id;
        $this->producebrand_name = $in ->producebrand_name;
        $this->user_id = $in ->user_id;
        $this->user_name = $in ->user_name;
        $this->observation = $in ->observation;
        $this->finished = $in ->finished;
        $this->created = $in ->created_at->format('d/m/Y H:i:s');
        $this->updated = $in ->updated_at->format('d/m/Y H:i:s');
    }
        public function registerProduce()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'produce_id' => ['required'],
                'quantity' => ['required', 'numeric', 'min:1'],
            ]);

            // Estende $validatedData.
            $validatedData['in_id'] = $this->in_id;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['quantity_encode'] = $validatedData['quantity'];
            $validatedData['quantity'] = General::decodeFloat2($validatedData['quantity']);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Inproduce::validateAdd($data);

            // Cadastra.
            if ($valid) Inproduce::add($data);

            // Executa dependências.
            if ($valid) Inproduce::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $in_id)
    {
        // Entrada.
        $in = In::find($in_id);

        // Inicializa propriedades dinâmicas.
        $this->in_id = $in_id;
        $this->deposit_id = $in ->deposit_id;
        $this->deposit_name = $in ->deposit_name;
        $this->producebrand_id = $in ->producebrand_id;
        $this->producebrand_name = $in ->producebrand_name;
        $this->user_id = $in ->user_id;
        $this->user_name = $in ->user_name;
        $this->observation = $in ->observation;
        $this->finished = $in ->finished;
        $this->created = $in ->created_at->format('d/m/Y H:i:s');
        $this->updated = $in ->updated_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $in_id)
    {
        // Entrada.
        $in = In::find($in_id);

        // Inicializa propriedades dinâmicas.
        $this->in_id = $in ->id;
        $this->deposit_id = $in ->deposit_id;
        $this->deposit_name = (string)$in ->deposit_name;
        $this->producebrand_id = $in ->producebrand_id;
        $this->producebrand_name = $in ->producebrand_name;
        $this->company_id = $in ->company_id;
        $this->user_id = $in ->user_id;
        $this->user_name = (string)$in ->user_name;
        $this->observation = (string)$in ->observation;
        $this->finished = $in ->finished;
        $this->created = $in ->created_at->format('d/m/Y H:i:s');

        // Percorre os Produtos da Entrada.
        foreach(Inproduce::where('in_id', $in_id)->get() as $key => $inproduce):
            // Inicializa variáveis, dinamicamente.
            $this->array_produce_score[$inproduce->produce->id] = (int)$inproduce->quantity;

            $this->array_produce_quantity[$inproduce->produce->id] = Producedeposit::where(['produce_id' => $inproduce->produce->id, 'deposit_id' => $this->deposit_id])->first()->quantity;
        endforeach;
    }
        public function modernize()
        {
            // Estende $validatedData.
            $validatedData['in_id'] = $this->in_id;
            $validatedData['deposit_id'] = $this->deposit_id;

            // Percorre os Produtos da Entrada.
            foreach(Inproduce::where('in_id', $this->in_id)->get() as $key => $inproduce):
                // Monta array do Produto da Entrada.
                $validatedData['inproduce_id'] = $inproduce->id;
                $validatedData['score'] = $this->array_produce_score[$inproduce->produce->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = In::validateEdit($data);

                // Atualiza.
                if ($valid) In::edit($data);

                // Executa dependências.
                if ($valid) In::dependencyEdit($data);
            endforeach;

            // Consolida entrada.
            In::find($this->in_id)->update([
                'finished' => true,
            ]);

            // Gera o Relatório em PDF.
            Report::inGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $in_id)
    {
        // Entrada.
        $in = In::find($in_id);

        // Inicializa propriedades dinâmicas.
        $this->in_id = $in ->id;
        $this->deposit_id = $in ->deposit_id;
        $this->deposit_name = (string)$in ->deposit_name;
        $this->producebrand_id = $in ->producebrand_id;
        $this->producebrand_name = $in ->producebrand_name;
        $this->company_id = $in ->company_id;
        $this->user_id = $in ->user_id;
        $this->user_name = (string)$in ->user_name;
        $this->observation = (string)$in ->observation;
        $this->finished = $in ->finished;
        $this->created = $in ->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['in_id']       = $this->in_id;
            $validatedData['deposit_id']   = $this->deposit_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['producebrand_id']   = $this->producebrand_id;
            $validatedData['producebrand_name'] = $this->producebrand_name;
            $validatedData['company_id']   = $this->company_id;
            $validatedData['user_id']      = $this->user_id;
            $validatedData['user_name']    = $this->user_name;
            $validatedData['observation']  = $this->observation;
            $validatedData['finished']     = $this->finished;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = In::validateErase($data);

            // Executa dependências.
            if ($valid) In::dependencyErase($data);

            // Exclui.
            if ($valid) In::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseProduce()
     *  excludeProduce()
     */
    public function eraseProduce(int $inproduce_id)
    {
        // ...
    }
        public function excludeProduce(int $inproduce_id)
        {
            // Entrada.
            $inproduce = Inproduce::find($inproduce_id);

            // Define $validatedData
            $validatedData['inproduce_id'] = $inproduce->id;
            $validatedData['in_id']        = $inproduce->in_id;
            $validatedData['produce_id']    = $inproduce->produce_id;
            $validatedData['produce_name']  = (string)$inproduce->produce_name;
            $validatedData['quantity_old']  = $inproduce->quantity_old;
            $validatedData['quantity']      = $inproduce->quantity;
            $validatedData['quantity_diff'] = $inproduce->quantity_diff;
            $validatedData['created']       = $inproduce->created_at->format('d/m/Y H:i:s');

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Inproduce::validateErase($data);

            // Executa dependências.
            if ($valid) Inproduce::dependencyErase($data);

            // Exclui.
            if ($valid) Inproduce::erase($data);

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
            $valid = In::validateGenerate($data);

            // Gera relatório.
            if ($valid) In::generate($data);

            // Executa dependências.
            if ($valid) In::dependencyGenerate($data);

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
            $valid = In::validateMail($data);

            // Envia e-mail.
            if ($valid) In::mail($data);

            // Executa dependências.
            if ($valid) In::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
