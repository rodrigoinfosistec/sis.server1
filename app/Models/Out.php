<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Out extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'outs';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'deposit_id',
        'deposit_name',

        'company_id',

        'user_id',
        'user_name',

        'observation',

        'finished',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function deposit(){return $this->belongsTo(Deposit::class);}
    public function company(){return $this->belongsTo(Company::class);}
    public function user(){return $this->belongsTo(User::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAdd(array $data){
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
    public static function add(array $data) {
        // Cadastra.
        $out_id = Out::create([
            'deposit_id' => $data['validatedData']['deposit_id'],
            'deposit_name' => Deposit::find($data['validatedData']['deposit_id'])->name,
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'observation' => Str::upper($data['validatedData']['observation']),
        ])->id;

        // After.
        $after = Out::find($out_id);

        // Auditoria.
        Audit::outAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrada com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return $out_id;
    }

    /**
     * Executa dependências de cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAdd(array $data) : bool {
        // ...

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

        // Percorre todos os produtos da Saída.
        foreach(Outproduce::where('out_id', $data['validatedData']['out_id'])->get() as $key => $outproduce):
            // Quantidade do Produto no depósito.
            $qtd_dep = Producedeposit::where(['produce_id' => $outproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity;

            // Verifica se existe a quantidade no Depósito.
            if($qtd_dep < $data['validatedData'][$outproduce->id]['score']):
                $message = "Produto " . $outproduce->produce_name . " com quantidade indisponível no Depósito.";
            endif;

            // Quantidade mínima permitida: 1.
            if($data['validatedData'][$outproduce->id]['score'] < 1):
                $message = "Entrada de " . $outproduce->produce_name . " deve ser no mímimo com 1.";
            endif;
        endforeach;

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
        // Percorre todos os produtos da Saída.
        foreach(Outproduce::where('out_id', $data['validatedData']['out_id'])->get() as $key => $outproduce):
            $quantity_old = Producedeposit::where(['produce_id' => $outproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity;

            // Atualiza quantidade do Produto no Depósito.
            Producedeposit::where(['produce_id' => $outproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->update([
                'quantity' => $quantity_old - General::encodeFloat($data['validatedData'][$outproduce->id]['score'], 7),
            ]);

            // Atualiza quantidade do Produto no Saída.
            Outproduce::find($outproduce->id)->update([
                'quantity_old' => $quantity_old,
                'quantity' => General::encodeFloat($data['validatedData'][$outproduce->id]['score'], 7),
                'quantity_diff' => 0 - General::encodeFloat($data['validatedData'][$outproduce->id]['score'], 7)
            ]);

            // Regista Movimentação do produto.
            Producemoviment::create([
                'produce_id' => $outproduce->produce_id,
                'deposit_id' => $data['validatedData']['deposit_id'],
                'company_id' => auth()->user()->company_id,
                'user_id' => auth()->user()->id,
                'type' => 'saida',
                'identification' => '{' . 
                    'out_id:'    . $data['validatedData']['out_id']    . ',' .
                '}',
                'quantity' => 0 - General::encodeFloat($data['validatedData'][$outproduce->id]['score'], 7),
            ]);
        endforeach;

        // Mensagem.
        $message = 'Saída Consolidada';
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
     * Valida exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateErase(array $data) : bool {
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
     * Executa dependências de exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyErase(array $data) : bool {
        // Percorre todos os Produtos da Saída.
        foreach(Outproduce::where('out_id', $data['validatedData']['out_id'])->get() as $key => $outproduce):
            // Exclui Produto da Saída.
            Outproduce::find($outproduce->id)->delete();
        endforeach;

        return true;
    }

    /**
     * Exclui.
     * @var array $data
     * 
     * @return bool true
     */
    public static function erase(array $data) : bool {
        // Exclui.
        Out::find($data['validatedData']['out_id'])->delete();

        // Auditoria.
        Audit::outErase($data);

        // Mensagem.
        $message = 'Saída de Produtos excluída com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
