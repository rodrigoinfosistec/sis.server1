<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\General;
use App\Models\Report;

use App\Models\Deposit;
use App\Models\Depositinput;
use App\Models\Depositinputproduct;

use App\Models\Provider;
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
    public $filter = 'deposit_name';

    public $report_id;
    public $mail;
    public $comment;

    public $depositinput_id;
    public $deposit_name;
    public $deposit_id;
    public $provider_id;
    public $provider_name;
    public $company_id;
    public $company_name;
    public $user_id;
    public $user_name;
    public $key;
    public $number;
    public $range;
    public $total;
    public $issue;
    public $created;

    public $xml;

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

            'xml' => ['file', 'required'],
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

        $this->depositinput_id = '';
        $this->deposit_name = '';
        $this->deposit_id = '';
        $this->provider_id = '';
        $this->provider_name = '';
        $this->company_id = '';
        $this->company_name = '';
        $this->user_id = '';
        $this->user_name = '';
        $this->key = '';
        $this->number = '';
        $this->range = '';
        $this->total = '';
        $this->issue = '';
        $this->created = '';

        $this->xml = '';
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
            'existsItem'   => Depositinput::exists(),
            'existsReport' => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->exists(),
            'reports'      => Report::where(['folder' => $this->config['name'], 'reference_1' => Auth()->user()->company_id])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Invoice::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ['company_id', Auth()->user()->company_id],
                            ])->orderBy('id', 'DESC')->paginate(25),
        ]);
    }
}
