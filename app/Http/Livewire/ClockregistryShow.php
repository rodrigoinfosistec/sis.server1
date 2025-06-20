<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use File;

use App\Models\Employee;
use App\Models\Clockregistry;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClockregistryShow extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshChildren' => 'refreshMe'];

    public function refreshMe(){}

    public $config;

    public $search = '';
    public $filter = 'employee_name';

    public $report_id;
    public $mail;
    public $comment;

    public $clockregistry_id;
    public $employee_id;
    public $employee_name;
    public $date;
    public $date_encode;
    public $time;
    public $photo;
    public $photo_link;
    public $created;

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

            'employee_id' => ['required'],
            'date'        => ['required'],
            'time'        => ['required'],
            'photo'       => ['required'],

            'txt'         => ['file', 'required'],
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

        $this->clockregistry_id = '';
        $this->employee_id      = '';
        $this->employee_name    = '';
        $this->date             = '';
        $this->date_encode      = '';
        $this->time             = '';
        $this->photo            = '';
        $this->photo_link       = '';
        $this->created          = '';

        $this->txt              = '';
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
    public function render() {
        // Inicializa variável.
        $array = [];

        // Monta o array.
        foreach(Employee::where('company_id', Auth()->user()->company_id)->get() as $key => $employee):
            $array[] =  $employee->id;
        endforeach;

        return view('livewire.' . $this->config['name'] . '-show', [
            'config'       => $this->config,
            'existsItem'   => Employee::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Clockregistry::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                                ])->whereIn('employee_id', $array)->orderBy('date', 'DESC')->orderBy('employee_name', 'ASC')->orderBy('time', 'DESC')->limit(2000)->paginate(50),
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
                'txt' => ['file', 'required'],
            ]);

            // Define $validatedData
            $validatedData['company_id'] = auth()->user()->company_id;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = $txtArray = Clockregistry::validateAddTxt($data);

            // Estende $data
            if ($valid) $data['txtArray'] = $txtArray;

            // Cadastra.
            if ($valid) Clockregistry::addTxt($data);

            // Executa dependências.
            if ($valid) Clockregistry::dependencyAddTxt($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
            return redirect()->to('/clockregistry');
        }

    /**
     * add()
     *  register()
     */
    public function add()
    {
        // ...
    }
        public function register()
        {
            // Valida campos.
            $validatedData = $this->validate([
                'employee_id' => ['required'],
                'date'        => ['required'],
                'time'        => ['required'],
                //'photo'       => ['required', 'image'],
                
            ]);

            // Estende $validatedData.
            $validatedData['employee_name'] = Employee::find($validatedData['employee_id'])->name;
            $validatedData['cripto']        = true;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Clockregistry::validateAdd($data);

            // Cadastra.
            if ($valid) Clockregistry::add($data);

            // Executa dependências.
            if ($valid) Clockregistry::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $clockregistry_id)
    {
        // Pagamento de Horas.
        $clockregistry = Clockregistry::find($clockregistry_id);

        // Inicializa propriedades dinâmicas.
        $this->clockregistry_id = $clockregistry->id;
        $this->employee_id      = $clockregistry->employee_id;
        $this->employee_name    = $clockregistry->employee_name;
        $this->date             = General::decodedate($clockregistry->date);
        $this->date_encode      = $clockregistry->date;
        $this->time             = $clockregistry->time;
        $this->photo_link       = $clockregistry->photo_link;
        $this->created          = $clockregistry->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData.
            $validatedData['clockregistry_id'] = $this->clockregistry_id;
            $validatedData['employee_id']      = $this->employee_id;
            $validatedData['employee_name']    = $this->employee_name;
            $validatedData['date']             = $this->date;
            $validatedData['date_encode']      = $this->date_encode;
            $validatedData['time']             = $this->time;
            $validatedData['photo_link']       = $this->photo_link;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Clockregistry::validateErase($data);

            // Executa dependências.
            if ($valid) Clockregistry::dependencyErase($data);

            // Exclui.
            if ($valid) Clockregistry::erase($data);

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
