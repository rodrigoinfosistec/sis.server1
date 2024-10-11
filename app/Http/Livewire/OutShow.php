<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Out;
use App\Models\Outproduce;
use App\Models\Produce;
use App\Models\Producebrand;
use App\Models\Producedeposit;
use App\Models\Deposit;
use App\Models\Company;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class OutShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'deposit_name';

    public $report_id;
    public $mail;
    public $comment;

    public $out_id;
    public $deposit_id;
    public $deposit_name;
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
            'observation' => ['nullable', 'between:2,255'],

            'produce_id' => ['required'],
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

        $this->out_id = '';
        $this->deposit_id = '';
        $this->deposit_name = '';
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
            'existsItem'   => Out::where('company_id', auth()->user()->company_id)->exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_2' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_2' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Out::where([
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
            $valid = Out::validateAdd($data);

            // Cadastra.
            if ($valid) $out_id = Out::add($data);

            // Estende $data['validatedData'].
            if ($valid) $data['validatedData']['out_id'] = $out_id;

            // Executa dependências.
            if ($valid) Out::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addProduce()
     *  registerProduce()
     */
    public function addProduce(int $out_id)
    {
        // Balanço.
        $out = Out::find($out_id);

        // Inicializa propriedades dinâmicas.
        $this->out_id = $out_id;
        $this->deposit_id = $out->deposit_id;
        $this->deposit_name = $out->deposit_name;
        $this->user_id = $out->user_id;
        $this->user_name = $out->user_name;
        $this->observation = $out->observation;
        $this->finished = $out->finished;
        $this->created = $out->created_at->format('d/m/Y H:i:s');
        $this->updated = $out->updated_at->format('d/m/Y H:i:s');
    }
        public function registerProduce()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'produce_id' => ['required'],
                'quantity' => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['out_id'] = $this->out_id;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['quantity_encode'] = General::encodeFloat2($validatedData['quantity']);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Outproduce::validateAdd($data);

            // Cadastra.
            if ($valid) Outproduce::add($data);

            // Executa dependências.
            if ($valid) Outproduce::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $out_id)
    {
        // Balanço.
        $out = Out::find($out_id);

        // Inicializa propriedades dinâmicas.
        $this->out_id = $out_id;
        $this->deposit_id = $out->deposit_id;
        $this->deposit_name = $out->deposit_name;
        $this->user_id = $out->user_id;
        $this->user_name = $out->user_name;
        $this->observation = $out->observation;
        $this->finished = $out->finished;
        $this->created = $out->created_at->format('d/m/Y H:i:s');
        $this->updated = $out->updated_at->format('d/m/Y H:i:s');
    }

    /**
     * edit()
     *  modernize()
     */
    public function edit(int $out_id)
    {
        // Saída.
        $out = Out::find($out_id);

        // Inicializa propriedades dinâmicas.
        $this->out_id = $out->id;
        $this->deposit_id = $out->deposit_id;
        $this->deposit_name = (string)$out->deposit_name;
        $this->company_id = $out->company_id;
        $this->user_id = $out->user_id;
        $this->user_name = (string)$out->user_name;
        $this->observation = (string)$out->observation;
        $this->finished = $out->finished;
        $this->created = $out->created_at->format('d/m/Y H:i:s');

        // Percorre os Produtos da Saída.
        foreach(Outproduce::where('out_id', $out_id)->get() as $key => $outproduce):
            // Inicializa variáveis, dinamicamente.
            $this->array_produce_score[$outproduce->produce->id] = '';

            $this->array_produce_quantity[$outproduce->produce->id] = Producedeposit::where(['produce_id' => $outproduce->produce->id, 'deposit_id' => $this->deposit_id])->first()->quantity;
        endforeach;
    }
        public function modernize()
        {
            // Estende $validatedData.
            $validatedData['out_id'] = $this->out_id;
            $validatedData['deposit_id'] = $this->deposit_id;

            // Percorre os Produtos do Balanço.
            foreach(Outproduce::where('out_id', $this->out_id)->get() as $key => $outproduce):
                // Monta array do Produto do balanço.
                $validatedData['outproduce_id'] = $outproduce->id;
                $validatedData['score'] = $this->array_produce_score[$outproduce->produce->id];

                // Define $data.
                $data['config']        = $this->config;
                $data['validatedData'] = $validatedData;

                // Valida atualização.
                $valid = Out::validateEdit($data);

                // Atualiza.
                if ($valid) Out::edit($data);

                // Executa dependências.
                if ($valid) Out::dependencyEdit($data);
            endforeach;

            // Consolida balanço.
            Out::find($this->out_id)->update([
                'finished' => true,
            ]);

            // Gera o Relatório em PDF.
            Report::outGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $out_id)
    {
        // Saída.
        $out = Out::find($out_id);

        // Inicializa propriedades dinâmicas.
        $this->out_id = $out->id;
        $this->deposit_id = $out->deposit_id;
        $this->deposit_name = (string)$out->deposit_name;
        $this->company_id = $out->company_id;
        $this->user_id = $out->user_id;
        $this->user_name = (string)$out->user_name;
        $this->observation = (string)$out->observation;
        $this->finished = $out->finished;
        $this->created = $out->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['out_id']       = $this->out_id;
            $validatedData['deposit_id']   = $this->deposit_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['company_id']   = $this->company_id;
            $validatedData['user_id']      = $this->user_id;
            $validatedData['user_name']    = $this->user_name;
            $validatedData['observation']  = $this->observation;
            $validatedData['finished']     = $this->finished;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Out::validateErase($data);

            // Executa dependências.
            if ($valid) Out::dependencyErase($data);

            // Exclui.
            if ($valid) Out::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseProduce()
     *  excludeProduce()
     */
    public function eraseProduce(int $outproduce_id)
    {
        // Saída.
        $outproduce = Outproduce::find($outproduce_id);

        // Inicializa propriedades dinâmicas.
        $this->outproduce_id = $outproduce->id;
        $this->out_id        = $outproduce->out_id;
        $this->produce_id    = $outproduce->produce_id;
        $this->produce_name  = (string)$outproduce->produce_name;
        $this->quantity_old  = $outproduce->quantity_old;
        $this->quantity      = $outproduce->quantity;
        $this->quantity_diff = $outproduce->quantity_diff;
        $this->created       = $outproduce->created_at->format('d/m/Y H:i:s');
    }
        public function excludeProduce()
        {
            // Define $validatedData
            $validatedData['outproduce_id'] = $this->outproduce_id;
            $validatedData['out_id']        = $this->out_id;
            $validatedData['produce_id']    = $this->produce_id;
            $validatedData['produce_name']  = $this->produce_name;
            $validatedData['quantity_old']  = $this->quantity_old;
            $validatedData['quantity']      = $this->quantity;
            $validatedData['quantity_diff'] = $this->quantity_diff;
            $validatedData['created']       = $this->created;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Outproduce::validateErase($data);

            // Executa dependências.
            if ($valid) Outproduce::dependencyErase($data);

            // Exclui.
            if ($valid) Outproduce::erase($data);

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
            $valid = Out::validateGenerate($data);

            // Gera relatório.
            if ($valid) Out::generate($data);

            // Executa dependências.
            if ($valid) Out::dependencyGenerate($data);

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
            $valid = Out::validateMail($data);

            // Envia e-mail.
            if ($valid) Out::mail($data);

            // Executa dependências.
            if ($valid) Out::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
