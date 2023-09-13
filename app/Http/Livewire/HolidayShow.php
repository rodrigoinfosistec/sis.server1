<?php

namespace App\Http\Livewire;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\General;

use App\Models\Holiday;

use Livewire\WithPagination;
use Livewire\Component;
use Livewire\WithFileUploads;

class HolidayShow extends Component
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

    public $holiday_id;
    public $date;
    public $date_encode;
    public $week;
    public $year;
    public $name;
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

            'date'     => ['required', 'unique:holidays,date,'.$this->holiday_id.''],
            'name'     => ['required', 'between:3,60'],
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

        $this->holiday_id  = '';
        $this->date_encode = '';
        $this->date        = '';
        $this->week        = '';
        $this->year        = '';
        $this->name        = '';
        $this->created     = '';
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
            'existsItem'   => Holiday::exists(),
            'existsReport' => Report::where('folder', $this->config['name'])->exists(),
            'reports'      => Report::where('folder', $this->config['name'])->orderBy('id', 'DESC')->limit(12)->get(),
            'list'         => Holiday::where([
                                [$this->filter, 'like', '%'. $this->search . '%'],
                            ])->orderBy('date', 'DESC')->paginate(12),
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
                'date'     => ['required', 'unique:holidays'],
                'name'     => ['required', 'between:3,60'],
            ]);

            // Etende $validatedData.
            $validatedData['week'] = Str::upper(General::decodeWeek(date_format(date_create($validatedData['date']), 'l')));
            $validatedData['year'] = date_format(date_create($validatedData['date']), 'Y');

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida cadastro.
            $valid = Holiday::validateAdd($data);

            // Cadastra.
            if ($valid) Holiday::add($data);

            // Executa dependências.
            if ($valid) Holiday::dependencyAdd($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }

    /** 
     * detail()
     */
    public function detail(int $holiday_id)
    {
        // Fornecedor.
        $holiday = Holiday::find($holiday_id);

        // Inicializa propriedades dinâmicas.
        $this->holiday_id = $holiday->id;
        $this->date       = General::decodeDate($holiday->date);
        $this->week       = $holiday->week;
        $this->year       = $holiday->year;
        $this->name       = $holiday->name;
        $this->created    = $holiday->created_at->format('d/m/Y H:i:s');
    }

    /**
     * erase()
     *  exclude()
     */
    public function erase(int $holiday_id)
    {
        // Fornecedor.
        $holiday = Holiday::find($holiday_id);

        // Inicializa propriedades dinâmicas.
        $this->holiday_id  = $holiday->id;
        $this->date        = General::decodedate($holiday->date);
        $this->date_encode = $holiday->date;
        $this->week        = $holiday->week;
        $this->year        = $holiday->year;
        $this->name        = $holiday->name;
        $this->created     = $holiday->created_at->format('d/m/Y H:i:s');
    }
        public function exclude()
        {
            // Define $validatedData
            $validatedData['holiday_id']  = $this->holiday_id;
            $validatedData['date']        = $this->date;
            $validatedData['date_encode'] = $this->date_encode;
            $validatedData['week']        = $this->week;
            $validatedData['year']        = $this->year;
            $validatedData['name']        = $this->name;

            // Define $data.
            $data['config']        = $this->config;
            $data['validatedData'] = $validatedData;

            // Valida exclusão.
            $valid = Holiday::validateErase($data);

            // Executa dependências.
            if ($valid) Holiday::dependencyErase($data);

            // Exclui.
            if ($valid) Holiday::erase($data);

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
            $valid = Holiday::validateGenerate($data);

            // Gera relatório.
            if ($valid) Holiday::generate($data);

            // Executa dependências.
            if ($valid) Holiday::dependencyGenerate($data);

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
            $valid = Holiday::validateMail($data);

            // Envia e-mail.
            if ($valid) Holiday::mail($data);

            // Executa dependências.
            if ($valid) Holiday::dependencyMail($data);

            // Fecha modal.
            $this->closeModal();
            $this->dispatchBrowserEvent('close-modal');
        }
}
