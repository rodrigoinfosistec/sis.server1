<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposittransferproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'deposittransferproducts';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'deposittransfer_id',
        'product_id',
        'product_name',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function deposittransfer(){return $this->belongsTo(Deposittransfer::class);}
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
            // Verifica se o Produto já está cadastrado na Tranferência.
            if(Deposittransferproduct::where(['deposittransfer_id' => $data['validatedData']['deposittransfer_id'], 'product_id' => $data['validatedData']['product_id']])->exists()):
                $message = 'Produto já cadastrado nesta saída.';
            endif;

            // Verifica se o produto não existe no Depósito Origem.
            if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['origin_id']])->doesntExist()):
                // Cadastra Produto no Depósito Origem.
                Productdeposit::create([
                    'product_id' => $data['validatedData']['product_id'],
                    'deposit_id' => $data['validatedData']['origin_id'],
                ]);
            endif;

            // Verifica se o produto não existe no Depósito Destino.
            if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['destiny_id']])->doesntExist()):
                // Cadastra Produto no Depósito Destino.
                Productdeposit::create([
                    'product_id' => $data['validatedData']['product_id'],
                    'deposit_id' => $data['validatedData']['destiny_id'],
                ]);
            endif;

            // Verifica se o Depósito Origem possui a quantidade de Produto transferida.
            if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['origin_id']])->first()->quantity < General::encodeFloat($data['validatedData']['quantity'], 7)):
                $productdeposit = Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['origin_id']])->first();
                $message = 'Quantidade no Depósito Origem:' . General::decodeFloat2($productdeposit->quantity, 2) . ' menor do que a desejada:' . $data['validatedData']['quantity'] . '.';
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
        $deposittransferproduct_id = Deposittransferproduct::create([
            'deposittransfer_id' => $data['validatedData']['deposittransfer_id'],
            'product_id' => $data['validatedData']['product_id'],
            'product_name' => Product::find($data['validatedData']['product_id'])->name,
            'quantity' => $data['validatedData']['quantity'],
        ])->id;

        // After.
        $after = Deposittransferproduct::find($deposittransferproduct_id);

        // Mensagem.
        $message = 'Produto ' . $after->product->name . ' incluído na Transferência.';
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
        $deposittransferproduct = Deposittransferproduct::find($data['validatedData']['deposittransferproduct_id']);

        // Exclui.
        Deposittransferproduct::find($data['validatedData']['deposittransferproduct_id'])->delete();

        // Mensagem.
        $message = 'Produto ' . $deposittransferproduct->product->name . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
