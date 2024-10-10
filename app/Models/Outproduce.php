<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outproduce extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'outproduces';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'out_id',
        'produce_id',
        'produce_name',

        'quantity_old',
        'quantity',
        'quantity_diff',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function out(){return $this->belongsTo(Out::class);}
    public function produce(){return $this->belongsTo(Produce::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se o Produto não existe ou veio consulta vazia.
        if(Produce::where('id', $data['validatedData']['produce_id'])->doesntExist() OR empty($data['validatedData']['produce_id'])):
            $message = 'Código de Produto:' . $data['validatedData']['produce_id'] .' não encontrado.';
        else:
            // Verifica se o Produto já está cadastrado na Saída.
            if(Outproduce::where(['out_id' => $data['validatedData']['out_id'], 'product_id' => $data['validatedData']['produce_id']])->exists()):
                $message = 'Produto já cadastrado nesta Saída.';
            endif;

            // Verifica se o produto não existe no Depósito.
            if(Producedeposit::where(['produce_id' => $data['validatedData']['produce_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->doesntExist()):
                $message = 'Produto não cadastrado neste Depósito.';
            else:
                // Verifica se o Depósito possui a quantidade de Produto.
                $producedeposit = Producedeposit::where(['produce_id' => $data['validatedData']['produce_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->first();
                $message = 'Quantidade no Depósito:' . General::decodeFloat2($producedeposit->quantity, 2) . ' menor do que a desejada:' . $data['validatedData']['quantity'] . '.';
            endif;
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
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Define quantidade atual do Produto no Depósito.
        if(Producedeposit::where(['produce_id' => $data['validatedData']['produce_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->exists()):
            $qtd_old = Producedeposit::where(['produce_id' => $data['validatedData']['produce_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity;
        else:
            $qtd_old = 0;
        endif;

        $outproduce_id = Outproduce::create([
            'out_id' => $data['validatedData']['out_id'],
            'produce_id' => $data['validatedData']['produce_id'],
            'produce_name' => Product::find($data['validatedData']['produce_id'])->name,
            'quantity_old' => $qtd_old,
            'quantity' => General::encodeFloat3($data['validatedData']['quantity']),
        ])->id;

        // After.
        $after = Outproduce::find($outproduce_id);

        // Mensagem.
        $message = 'Produto ' . $after->produce->name . ' incluído na Saída.';
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
        // ...

        return true;
    }

    /**
     * Exclui.
     * @var array $data
     * 
     * @return bool true
     */
    public static function erase(array $data) : bool {
        $depositoutputproduct = Depositoutputproduct::find($data['validatedData']['depositoutputproduct_id']);

        // Exclui.
        Depositoutputproduct::find($data['validatedData']['depositoutputproduct_id'])->delete();

        // Mensagem.
        $message = 'Produto ' . $depositoutputproduct->product->name . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
