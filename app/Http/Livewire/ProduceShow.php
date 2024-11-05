<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Produce;
use App\Models\Producemeasure;
use App\Models\Producebrand;
use App\Models\Producedeposit;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProduceShow extends Component
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

    public $produce_id;
    public $name;
    public $reference;
    public $ean;
    public $producebrand_id;
    public $producebrand_name;
    public $producemeasure_id;
    public $producemeasure_name;
    public $observation;
    public $status;
    public $created;
    public $produce_deposit_id;

    public $deposit_id = '';
    public $deposit_name = '';
    public $deposit_nick = '';

    public  $array_deposit = [];

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

            'name' => ['required', 'between:2,255'],
            'reference' => ['required', 'between:2,255'],
            'ean' => ['required', 'numeric', 'min:13', 'unique:produces,ean,'.$this->produce_id.''],
            'produce_deposit_id' => ['required'],
            'producebrand_id' => ['required'],
            'producemeasure_id' => ['required'],
            'observation' => ['nullable', 'between:2,255'],
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

        $this->produce_id          = '';
        $this->name                = '';
        $this->reference           = '';
        $this->ean                 = '';
        $this->producebrand_id     = '';
        $this->producebrand_name   = '';
        $this->producemeasure_id   = '';
        $this->producemeasure_name = '';
        $this->observation         = '';
        $this->status              = '';
        $this->created             = '';
        $this->produce_deposit_id  = '';

        $this->array_deposit = [];
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
        $array = [];
        if($this->deposit_id != ''):
            foreach(Producedeposit::where('deposit_id', $this->deposit_id)->get() as $key => $producedeopsit):
                $array[] = $producedeopsit->produce_id;
            endforeach;
        else:
            foreach(Produce::where(['company_id'=>Auth()->user()->company_id, 'status'=>true])->get() as $key => $produce):
                $array[] = $produce->id;
            endforeach;
        endif;

        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Produce::where(['company_id'=>Auth()->user()->company_id, 'status'=>true])->whereIn('id', $array)->exists(),
            'existsReport' => Report::where(['folder'=>$this->config['name'], 'reference_1'=>auth()->user()->company_id, 'reference_2'=>$this->deposit_id])->exists(),
            'reports'      => Report::where(['folder'=>$this->config['name'], 'reference_1'=>auth()->user()->company_id, 'reference_2'=>$this->deposit_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Produce::where([
                ['company_id', Auth()->user()->company_id],
                [$this->filter, 'like', '%'. $this->search . '%'],
                ['status', true],
            ])->whereIn('id', $array)
            ->orderBy('status', 'DESC')->orderBy('name', 'ASC')->paginate(100),
        ]);
    }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name' => ['required', 'between:2,255'],
                'reference' => ['required', 'between:2,255'],
                'ean' => ['required', 'min:13', 'unique:produces'],
                'produce_deposit_id' => ['required'],
                'producebrand_id' => ['required'],
                'producemeasure_id' => ['required'],
                'observation' => ['nullable', 'between:2,255'],
            ]);

            // Estende $validatedData
            if(!isset($validatedData['observation'])):
                $validatedData['observation'] = '';
            endif;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Produce::validateAdd($data);

            // Cadastra.
            if ($valid) Produce::add($data);

            // Executa dependências.
            if ($valid) Produce::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            //return redirect()->to('/produce');
        }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $produce_id)
    {
        // Produto.
        $produce = Produce::find($produce_id);

        // Inicializa propriedades dinâmicas.
        $this->produce_id = $produce_id;
        $this->name = $produce->name;
        $this->reference = $produce->reference;
        $this->ean = $produce->ean;
        $this->producebrand_id = $produce->producebrand_id;
        $this->producebrand_name = $produce->producebrand_name;
        $this->producemeasure_id = $produce->producemeasure_id;
        $this->producemeasure_name = $produce->producemeasure_name;
        $this->company_id = $produce->company_id;
        $this->observation = $produce->observation;
        $this->status = $produce->status;
        $this->created = $produce->created_at->format('d/m/Y H:i:s');
    }
        public function modernize()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'name' => ['required', 'between:2,255'],
                'reference' => ['required', 'between:2,255'],
                'ean' => ['required', 'numeric', 'min:13', 'unique:produces,ean,'.$this->produce_id.''],
                'producebrand_id' => ['required'],
                'producemeasure_id' => ['required'],
                'observation' => ['nullable', 'between:2,255'],
            ]);

            // Estende $validatedData
            if(!isset($validatedData['observation'])):
                $validatedData['observation'] = '';
            endif;

            // Estende $validatedData
            $validatedData['produce_id'] = $this->produce_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Produce::validateEdit($data);

            // Atualiza.
            if ($valid) Produce::edit($data);

            // Executa dependências.
            if ($valid) Produce::dependencyEdit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * edit()
     *  modernize()
     */
    public function editDeposit(int $produce_id)
    {
        // Produto.
        $produce = Produce::find($produce_id);

        // Inicializa propriedades dinâmicas.
        $this->produce_id = $produce_id;
        $this->name = $produce->name;
        $this->reference = $produce->reference;
        $this->ean = $produce->ean;
        $this->producebrand_id = $produce->producebrand_id;
        $this->producebrand_name = $produce->producebrand_name;
        $this->producemeasure_id = $produce->producemeasure_id;
        $this->producemeasure_name = $produce->producemeasure_name;
        $this->company_id = $produce->company_id;
        $this->observation = $produce->observation;
        $this->status = $produce->status;
        $this->created = $produce->created_at->format('d/m/Y H:i:s');

        // Percorre os Depósitos.
        foreach(Deposit::where(['company_id'=>auth()->user()->company_id, 'status'=>true])->orderBy("name", "ASC")->get() as $key => $deposit):
            // Relaciona Produto aos Depósitos.
            Producedeposit::where(['produce_id' => $produce_id, 'deposit_id' => $deposit->id])->exists() ? $this->array_deposit[$deposit->id] = true : $this->array_deposit[$deposit->id] = false;
        endforeach;
    }
        public function modernizeDeposit()
        {
            // Estende $validatedData
            $validatedData['produce_id'] = $this->produce_id;
            $data['array_deposit'] = $this->array_deposit;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida atualização.
            $valid = Produce::validateEditDeposit($data);

            // Atualiza.
            if ($valid) Produce::editDeposit($data);

            // Executa dependências.
            if ($valid) Produce::dependencyEditDeposit($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $produce_id)
    {
        // Produto.
        $produce = Produce::find($produce_id);

        // Inicializa propriedades dinâmicas.
        $this->produce_id = $produce_id;
        $this->name = $produce->name;
        $this->reference = $produce->reference;
        $this->ean = $produce->ean;
        $this->producebrand_id = $produce->producebrand_id;
        $this->producebrand_name = $produce->producebrand_name;
        $this->producemeasure_id = $produce->producemeasure_id;
        $this->producemeasure_name = $produce->producemeasure_name;
        $this->company_id = $produce->company_id;
        $this->observation = $produce->observation;
        $this->status = $produce->status;
        $this->created = $produce->created_at->format('d/m/Y H:i:s');
    }

    /**
     * generate()
     *  sire()
     */
    public function generate()
    {
        // ...
    }
        public function sire()
        {
            // Define $data.
            $data['config']     = $this->config;
            $data['filter']     = $this->filter;
            $data['search']     = $this->search;
            $data['deposit_id'] = $this->deposit_id;

            // Valida geração de relatório.
            $valid = Produce::validateGenerate($data);

            // Gera relatório.
            if ($valid) Produce::generate($data);

            // Executa dependências.
            if ($valid) Produce::dependencyGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * generateMoviment()
     *  sireMoviment()
     */
    public function generateMoviment(int $produce_id)
    {
        // Produto.
        $produce = Produce::find($produce_id);

        // Inicializa propriedades dinâmicas.
        $this->produce_id = $produce_id;
        $this->name = $produce->name;
        $this->reference = $produce->reference;
        $this->ean = $produce->ean;
        $this->producebrand_id = $produce->producebrand_id;
        $this->producebrand_name = $produce->producebrand_name;
        $this->producemeasure_id = $produce->producemeasure_id;
        $this->producemeasure_name = $produce->producemeasure_name;
        $this->company_id = $produce->company_id;
        $this->observation = $produce->observation;
        $this->status = $produce->status;
        $this->created = $produce->created_at->format('d/m/Y H:i:s');
    }
        public function sireMoviment()
        {
            // Define $validatedData.
            $validatedData['produce_id'] = $this->produce_id;
            $validatedData['name'] = $this->name;
            $validatedData['reference'] = $this->reference;
            $validatedData['ean'] = $this->ean;
            $validatedData['producebrand_id'] = $this->producebrand_id;
            $validatedData['producebrand_name'] = $this->producebrand_name;
            $validatedData['producemeasure_id'] = $this->producemeasure_id;
            $validatedData['producemeasure_name'] = $this->producemeasure_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['observation'] = $this->observation;
            $validatedData['status'] = $this->status;
            $validatedData['created'] = $this->created;

            // Define $data.
            $data['config'] = $this->config;
            $data['filter'] = $this->filter;
            $data['search'] = $this->search;
            $data['validatedData'] = $validatedData;

            // Valida geração de relatório.
            $valid = Produce::validateGenerateMoviment($data);

            // Gera relatório.
            if ($valid) Produce::generateMoviment($data);

            // Executa dependências.
            if ($valid) Produce::dependencyGenerateMoviment($data);

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
            $valid = Produce::validateMail($data);

            // Envia e-mail.
            if ($valid) Produce::mail($data);

            // Executa dependências.
            if ($valid) Produce::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
