<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Produce extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'produces';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'reference',
        'ean',

        'producebrand_id',
        'producebrand_name',
        'producemeasure_id',
        'producemeasure_name',
        'company_id',

        'observation',
        'status',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function producebrand(){return $this->belongsTo(Producebrand::class);}
    public function producemeasure(){return $this->belongsTo(Producemeasure::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // ...

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return true;
    }

    /**
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Cadastra.
        $produce_id = Produce::create([
            'name'                => Str::upper($data['validatedData']['name']),
            'reference'           => $data['validatedData']['reference'],
            'ean'                 => $data['validatedData']['ean'],
            'producebrand_id'     => $data['validatedData']['producebrand_id'],
            'producebrand_name'   => Producebrand::find($data['validatedData']['producebrand_id'])->name,
            'producemeasure_id'   => $data['validatedData']['producemeasure_id'],
            'producemeasure_name' => Producemeasure::find($data['validatedData']['producemeasure_id'])->name,
            'company_id'          => Auth()->user()->company_id,
            'observation'         => $data['validatedData']['observation'],
        ]);

        // After.
        $after = Produce::find($produce_id)->first();

        // Auditoria.
        Audit::produceAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->name . ' cadastrado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAdd(array $data) : bool {
        //...

        return true;
    }

    /**
     * Valida geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateGenerate(array $data) : bool {
        $message = null;

        // Verifica se existe algum item retornado na pesquisa.
        if($list = Produce::where([
                [$data['filter'], 'like', '%'. $data['search'] . '%'],
            ])->doesntExist()):

            $message = 'Nenhum ítem selecionado.';
        endif;

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return true;
    }

    /**
     * Gera relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function generate(array $data) : bool {
        // Estende $data.
        $data['path'] = public_path('/storage/pdf/' . $data['config']['name'] . '/');
        $data['file_name'] = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.pdf';

        // Gera PDF.
        Report::produceGenerate($data);

        // Auditoria.
        Audit::produceGenerate($data);

        // Mensagem.
        $message = 'Relatório PDF gerado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyGenerate(array $data) : bool {
        //...

        return true;
    }

}
