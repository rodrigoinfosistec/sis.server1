<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;

use App\Models\Deposittransfer;
use App\Models\Deposittransferproduct;
use App\Models\Product;
use App\Models\Company;
use App\Models\Deposit;
use App\Models\Deposituser;
use App\Models\User;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeposittransferShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'origin_name';

    public $report_id;
    public $mail;
    public $comment;

    public $deposittransfer_id;
    public $origin_id;
    public $origin_name;
    public $destiny_id;
    public $destiny_name;
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

    public $deposit_id;
    public $deposit_name;
    public $deposit_company_id;

    public $deposittransferproduct_id;

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

            'origin_id' => ['required'],
            'destiny_id' => ['required'],
            'observation' => ['required'],

            'product_id' => ['required'],
            'quantity' => ['required', 'numeric'],
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

        $this->deposittransfer_id = '';
        $this->origin_id = '';
        $this->origin_name = '';
        $this->destiny_id = '';
        $this->destiny_name = '';
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

        $this->deposit_id = '';
        $this->deposit_name = '';
        $this->deposit_company_id = '';

        $this->deposittransferproduct_id = '';
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
            'existsItem'   => Deposittransfer::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Deposittransfer::where([
                ['company_id', auth()->user()->company_id],
                [$this->filter, 'like', '%'. $this->search . '%'],
            ])->whereIn('origin_id', $array)->whereIn('destiny_id', $array)->orderBy('funded', 'ASC')->orderBy('id', 'DESC')->paginate(100),
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
                'origin_id' => ['required'],
                'destiny_id' => ['required'],
                'observation' => ['required'],
            ]);

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Deposittransfer::validateAdd($data);

            // Cadastra.
            if ($valid) $deposittransfer_id = Deposittransfer::add($data);

            // Estende $data['validatedData'].
            if ($valid) $data['validatedData']['deposittransfer_id'] = $deposittransfer_id;

            // Executa dependências.
            if ($valid) Deposittransfer::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addProduct()
     *  registerProduct()
     */
    public function addProduct(int $deposittransfer_id)
    {
        // Transferência Depósito.
        $deposittransfer = Deposittransfer::find($deposittransfer_id);

        // Inicializa propriedades dinâmicas.
        $this->deposittransfer_id = $deposittransfer_id;
        $this->origin_id = $deposittransfer->origin_id;
        $this->origin_name = $deposittransfer->origin_name;
        $this->destiny_id = $deposittransfer->destiny_id;
        $this->destiny_name = $deposittransfer->destiny_name;
        $this->user_id = $deposittransfer->user_id;
        $this->user_name = $deposittransfer->user_name;
        $this->observation = $deposittransfer->observation;
        $this->created = $deposittransfer->created_at->format('d/m/Y H:i:s');
        $this->updated = $deposittransfer->updated_at->format('d/m/Y H:i:s');
    }
        public function registerProduct()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'product_id' => ['required'],
                'quantity' => ['required', 'numeric', 'min:0.1'],
            ]);

            // Estende $validatedData.
            $validatedData['deposittransfer_id'] = $this->deposittransfer_id;
            $validatedData['origin_id'] = $this->origin_id;
            $validatedData['destiny_id'] = $this->destiny_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Deposittransferproduct::validateAdd($data);

            // Cadastra.
            if ($valid) Deposittransferproduct::add($data);

            // Executa dependências.
            if ($valid) Deposittransferproduct::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * addFunded()
     *  registerFinished()
     */
    public function addFunded(int $deposittransfer_id)
    {
        // Transferência Depósito.
        $deposittransfer = Deposittransfer::find($deposittransfer_id);

        // Inicializa propriedades dinâmicas.
        $this->deposittransfer_id = $deposittransfer_id;
        $this->origin_id = $deposittransfer->origin_id;
        $this->origin_name = $deposittransfer->origin_name;
        $this->destiny_id = $deposittransfer->destiny_id;
        $this->destiny_name = $deposittransfer->destiny_name;
        $this->company_id = $deposittransfer->company_id;
        $this->user_id = $deposittransfer->user_id;
        $this->user_name = $deposittransfer->user_name;
        $this->observation = $deposittransfer->observation;
        $this->created = $deposittransfer->created_at->format('d/m/Y H:i:s');
        $this->updated = $deposittransfer->updated_at->format('d/m/Y H:i:s');
    }
        public function registerFunded()
        {
            // Define $validatedData
            $validatedData['deposittransfer_id'] = $this->deposittransfer_id;
            $validatedData['origin_id'] = $this->origin_id;
            $validatedData['origin_name'] = $this->origin_name;
            $validatedData['destiny_id'] = $this->destiny_id;
            $validatedData['destiny_name'] = $this->destiny_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['observation'] = $this->observation;
            $validatedData['created'] = $this->created;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Deposittransfer::validateAddFunded($data);

            // Executa dependências.
            if ($valid) Deposittransfer::dependencyAddFunded($data);

            // Exclui.
            if ($valid) Deposittransfer::addFunded($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $deposittransfer_id)
    {
        // Transferência Depósito.
        $deposittransfer = Deposittransfer::find($deposittransfer_id);

        // Inicializa propriedades dinâmicas.
        $this->deposittransfer_id = $deposittransfer_id;
        $this->origin_id = $deposittransfer->origin_id;
        $this->origin_name = $deposittransfer->origin_name;
        $this->destiny_id = $deposittransfer->destiny_id;
        $this->destiny_name = $deposittransfer->destiny_name;
        $this->company_id = $deposittransfer->company_id;
        $this->user_id = $deposittransfer->user_id;
        $this->user_name = $deposittransfer->user_name;
        $this->observation = $deposittransfer->observation;
        $this->created = $deposittransfer->created_at->format('d/m/Y H:i:s');
        $this->updated = $deposittransfer->updated_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['deposittransfer_id'] = $this->deposittransfer_id;
            $validatedData['origin_id'] = $this->origin_id;
            $validatedData['origin_name'] = $this->origin_name;
            $validatedData['destiny_id'] = $this->destiny_id;
            $validatedData['destiny_name'] = $this->destiny_name;
            $validatedData['company_id'] = $this->company_id;
            $validatedData['observation'] = $this->observation;
            $validatedData['created'] = $this->created;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Deposittransfer::validateErase($data);

            // Executa dependências.
            if ($valid) Deposittransfer::dependencyErase($data);

            // Exclui.
            if ($valid) Deposittransfer::erase($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * eraseProduct()
     *  excludeProduct()
     */
    public function eraseProduct(int $deposittransferproduct_id)
    {
        // Produto da Saída.
        $deposittransferproduct = Deposittransferproduct::find($deposittransferproduct_id);

        // Inicializa propriedades dinâmicas.
        $this->deposittransferproduct_id = $deposittransferproduct_id;
        $this->product_name = $deposittransferproduct->product_name;
        $this->origin_name = $deposittransferproduct->deposittransfer->origin->name;
        $this->destiny_name = $deposittransferproduct->deposittransfer->destiny->name;
        $this->quantity = $deposittransferproduct->quantity;
        $this->created = $deposittransferproduct->product->created_at->format('d/m/Y H:i:s');
    }
        public function excludeProduct()
        {
            // Define $validatedData
            $validatedData['deposittransferproduct_id'] = $this->deposittransferproduct_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Deposittransferproduct::validateErase($data);

            // Executa dependências.
            if ($valid) Deposittransferproduct::dependencyErase($data);

            // Exclui.
            if ($valid) Deposittransferproduct::erase($data);

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
            $valid = Deposittransfer::validateGenerate($data);

            // Gera relatório.
            if ($valid) Deposittransfer::generate($data);

            // Executa dependências.
            if ($valid) Deposittransfer::dependencyGenerate($data);

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
            $valid = Deposittransfer::validateMail($data);

            // Envia e-mail.
            if ($valid) Deposittransfer::mail($data);

            // Executa dependências.
            if ($valid) Deposittransfer::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
