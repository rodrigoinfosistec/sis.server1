<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Rapier extends Model
{
    use HasFactory;

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
        ])->id;

        // After.
        $after = Produce::find($produce_id);

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
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEdit(array $data) : bool {
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
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Before.
        $before = Produce::find($data['validatedData']['produce_id']);

        // Atualiza.
        Produce::find($data['validatedData']['produce_id'])->update([
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
        $after = Produce::find($data['validatedData']['produce_id']);

        // Auditoria.
        Audit::produceEdit($data, $before, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $after->name . ' atualizada com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEdit(array $data) : bool {
        // ...

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

        // Define $array.
        $array = [];
        if($data['deposit_id'] != ''):
            foreach(Producedeposit::where('deposit_id', $data['deposit_id'])->get() as $key => $producedeopsit):
                $array[] = $producedeopsit->produce_id;
            endforeach;

            // Verifica se existe algum item retornado na pesquisa.
            if(Produce::where([
                ['company_id', Auth()->user()->company_id],
                [$data['filter'], 'like', '%'. $data['search'] . '%'],
                ['status', true],
            ])->whereIn('id', $array)->doesntExist()):
                $message = 'Nenhum ítem selecionado.';
            endif;

            // Verifica se houve Pesquisa.
            if($data['search'] == ''):
                $message = 'Nenhuma Pesquisa efetuada.';
            endif;
        else:
            $message = 'Nenhum Depósito selecionado.';
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
        Report::rapierGenerate($data);

        // Auditoria.
        Audit::rapierGenerate($data);

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

    /**
     * Valida geração de relatório Movimentação.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateGenerateMoviment(array $data) : bool {
        $message = null;

        // Verifica se existe alguma movimentação item retornado na pesquisa.
        if($list = Producemoviment::where([
                ['produce_id', $data['validatedData']['produce_id']],
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
    public static function generateMoviment(array $data) : bool {
        // Estende $data.
        $data['path'] = public_path('/storage/pdf/rapiermoviment/');
        $data['file_name'] = 'rapiermoviment_' . auth()->user()->id . '_' . Str::random(20) . '.pdf';

        // Gera PDF.
        Report::rapierMovimentGenerate($data);

        // Auditoria.
        Audit::rapierMovimentGenerate($data);

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
    public static function dependencyGenerateMoviment(array $data) : bool {
        //...

        return true;
    }

}
