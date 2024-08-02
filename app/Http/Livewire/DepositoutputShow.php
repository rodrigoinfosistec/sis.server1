<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;

use App\Models\Depositoutput;
use App\Models\Depositoutputproduct;
use App\Models\Product;
use App\Models\Company;
use App\Models\Deposit;
use App\Models\Deposituser;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class DepositoutputShow extends Component
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

    public $depositoutput_id;
    public $deposit_id;
    public $deposit_name;
    public $company_id;
    public $user_id;
    public $user_name;
    public $observation;
    public $funded;
    public $created;
    public $updated;

    public $product_id;
    public $product_name;
    public $quantity;

    public $deposit_company_id;

    public $depositoutputproduct_id;

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
            'observation' => ['required'],

            'product_id' => ['required'],
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

        $this->depositoutput_id = '';
        $this->deposit_id = '';
        $this->deposit_name = '';
        $this->company_id = '';
        $this->user_id = '';
        $this->user_name = '';
        $this->observation = '';
        $this->funded = '';
        $this->created = '';
        $this->updated = '';

        $this->product_id = '';
        $this->product_name = '';
        $this->quantity = '';

        $this->deposit_company_id = '';

        $this->depositoutputproduct_id = '';
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
        foreach(Deposituser::where('user_id', auth()->user()->id)->get() as $key => $deposituser):
            $array[] =  $deposituser->deposit_id;
        endforeach;

        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Depositoutput::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Depositoutput::where([
                ['company_id', auth()->user()->company_id],
                [$this->filter, 'like', '%'. $this->search . '%'],
            ])->whereIn('deposit_id', $array)->whereIn('deposit_id', $array)->orderBy('funded', 'ASC')->orderBy('id', 'DESC')->paginate(100),
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
                'observation' => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Depositoutput::validateAdd($data);

            // Cadastra.
            if ($valid) $depositoutput_id = Depositoutput::add($data);

            // Estende $data['validatedData'].
            if ($valid) $data['validatedData']['depositoutput_id'] = $depositoutput_id;

            // Executa dependências.
            if ($valid) Depositoutput::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addProduct()
     *  registerProduct()
     */
    public function addProduct(int $depositoutput_id)
    {
        // Saída Depósito.
        $depositoutput = Depositoutput::find($depositoutput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositoutput_id = $depositoutput_id;
        $this->deposit_id = $depositoutput->deposit_id;
        $this->deposit_name = $depositoutput->deposit_name;
        $this->user_id = $depositoutput->user_id;
        $this->user_name = $depositoutput->user_name;
        $this->observation = $depositoutput->observation;
        $this->created = $depositoutput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositoutput->updated_at->format('d/m/Y H:i:s');
    }
        public function registerProduct()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'product_id' => ['required'],
                'quantity' => ['required'],
            ]);

            // Estende $validatedData.
            $validatedData['depositoutput_id'] = $this->depositoutput_id;
            $validatedData['deposit_id'] = $this->deposit_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Depositoutputproduct::validateAdd($data);

            // Cadastra.
            if ($valid) Depositoutputproduct::add($data);

            // Executa dependências.
            if ($valid) Depositoutputproduct::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addFunded()
     *  registerFinished()
     */
    public function addFunded(int $depositoutput_id)
    {
        // Saída Depósito.
        $depositoutput = Depositoutput::find($depositoutput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositoutput_id = $depositoutput_id;
        $this->deposit_id = $depositoutput->deposit_id;
        $this->deposit_name = $depositoutput->deposit_name;
        $this->company_id = $depositoutput->company_id;
        $this->user_id = $depositoutput->user_id;
        $this->user_name = $depositoutput->user_name;
        $this->observation = $depositoutput->observation;
        $this->created = $depositoutput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositoutput->updated_at->format('d/m/Y H:i:s');
    }
        public function registerFunded()
        {
            // Define $validatedData
            $validatedData['depositoutput_id'] = $this->depositoutput_id;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['observation'] = $this->observation;
            $validatedData['created'] = $this->created;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Depositoutput::validateAddFunded($data);

            // Executa dependências.
            if ($valid) Depositoutput::dependencyAddFunded($data);

            // Exclui.
            if ($valid) Depositoutput::addFunded($data);

            // Gera o Relatório em PDF.
            if ($valid) Report::depositoutputGenerate($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $depositoutput_id)
    {
        // Saída Depósito.
        $depositoutput = Depositoutput::find($depositoutput_id);

        // Inicializa propriedades dinâmicas.
        $this->depositoutput_id = $depositoutput_id;
        $this->deposit_id = $depositoutput->deposit_id;
        $this->deposit_name = $depositoutput->deposit_name;
        $this->company_id = $depositoutput->company_id;
        $this->user_id = $depositoutput->user_id;
        $this->user_name = $depositoutput->user_name;
        $this->observation = $depositoutput->observation;
        $this->created = $depositoutput->created_at->format('d/m/Y H:i:s');
        $this->updated = $depositoutput->updated_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['depositoutput_id'] = $this->depositoutput_id;
            $validatedData['deposit_id'] = $this->deposit_id;
            $validatedData['deposit_name'] = $this->deposit_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['observation'] = $this->observation;
            $validatedData['created'] = $this->created;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Depositoutput::validateErase($data);

            // Executa dependências.
            if ($valid) Depositoutput::dependencyErase($data);

            // Exclui.
            if ($valid) Depositoutput::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseProduct()
     *  excludeProduct()
     */
    public function eraseProduct(int $depositoutputproduct_id)
    {
        // Produto da Saída.
        $depositoutputproduct = Depositoutputproduct::find($depositoutputproduct_id);

        // Inicializa propriedades dinâmicas.
        $this->depositoutputproduct_id = $depositoutputproduct_id;
        $this->product_name = $depositoutputproduct->product_name;
        $this->deposit_id = $depositoutputproduct->depositoutput->deposit->id;
        $this->deposit_name = $depositoutputproduct->depositoutput->deposit->name;
        $this->quantity = $depositoutputproduct->quantity;
        $this->created = $depositoutputproduct->product->created_at->format('d/m/Y H:i:s');
    }
        public function excludeProduct()
        {
            // Define $validatedData
            $validatedData['depositoutputproduct_id'] = $this->depositoutputproduct_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Depositoutputproduct::validateErase($data);

            // Executa dependências.
            if ($valid) Depositoutputproduct::dependencyErase($data);

            // Exclui.
            if ($valid) Depositoutputproduct::erase($data);

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
            $valid = Depositoutput::validateGenerate($data);

            // Gera relatório.
            if ($valid) Depositoutput::generate($data);

            // Executa dependências.
            if ($valid) Depositoutput::dependencyGenerate($data);

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
            $valid = Depositoutput::validateMail($data);

            // Envia e-mail.
            if ($valid) Depositoutput::mail($data);

            // Executa dependências.
            if ($valid) Depositoutput::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
