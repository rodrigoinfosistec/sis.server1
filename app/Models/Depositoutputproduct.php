<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depositoutputproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositoutputproducts';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'depositoutput_id',
        'product_id',
        'product_name',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function depositoutput(){return $this->belongsTo(Depositoutput::class);}
    public function product(){return $this->belongsTo(Product::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se o Produto existe, pelo código id passado.
        if(Product::where('id', $data['validatedData']['product_id'])->doesntExist() OR empty($data['validatedData']['product_id'])):
            $message = 'Código de Produto:' . $data['validatedData']['product_id'] .' não encontrado.';
        else:
            // Verifica se o Produto já está cadastrado na Saída.
            if(Depositoutputproduct::where(['depositoutput_id' => $data['validatedData']['depositoutput_id'], 'product_id' => $data['validatedData']['product_id']])->exists()):
                $message = 'Produto já cadastrado nesta saída.';
            endif;

            // Verifica se o produto não existe no Depósito.
            if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->doesntExist()):
                // Cadastra Produto no Depósito.
                Productdeposit::create([
                    'product_id' => $data['validatedData']['product_id'],
                    'deposit_id' => $data['validatedData']['deposit_id'],
                ]);
            endif;

            // Verifica se o Depósito possui a quantidade de Produto.
            if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity < General::encodeFloat($data['validatedData']['quantity'], 7)):
                $productdeposit = Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->first();
                $message = 'Quantidade no Depósito:' . General::decodeFloat2($productdeposit->quantity, 2) . ' menor do que a desejada:' . $data['validatedData']['quantity'] . '.';
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
        $depositoutputproduct_id = Depositoutputproduct::create([
            'depositoutput_id' => $data['validatedData']['depositoutput_id'],
            'product_id' => $data['validatedData']['product_id'],
            'product_name' => Product::find($data['validatedData']['product_id'])->name,
            'quantity' => General::encodeFloat3($data['validatedData']['quantity']),
        ])->id;

        // After.
        $after = Depositoutputproduct::find($depositoutputproduct_id);

        // Mensagem.
        $message = 'Produto ' . $after->product->name . ' incluído na Saída.';
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
