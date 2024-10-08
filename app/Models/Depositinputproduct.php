<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depositinputproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositinputproducts';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'depositinput_id',

        'product_id',
        'product_name',

        'identifier',

        'quantity',
        'quantity_final',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function depositinput(){return $this->belongsTo(Depositinput::class);}
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
            // Verifica se o Produto já está cadastrado na Entrada.
            if(Depositinputproduct::where(['depositinput_id' => $data['validatedData']['depositinput_id'], 'product_id' => $data['validatedData']['product_id']])->exists()):
                $message = 'Produto já cadastrado nesta Entrada.';
            endif;

            // Verifica se o produto não existe no Depósito.
            if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->doesntExist()):
                // Cadastra Produto no Depósito.
                Productdeposit::create([
                    'product_id' => $data['validatedData']['product_id'],
                    'deposit_id' => $data['validatedData']['deposit_id'],
                ]);
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
        $depositinputproduct_id = Depositinputproduct::create([
            'depositinput_id' => $data['validatedData']['depositinput_id'],
            'product_id' => $data['validatedData']['product_id'],
            'product_name' => Product::find($data['validatedData']['product_id'])->name,
            'quantity' => $data['validatedData']['quantity'],
            'quantity_final' => $data['validatedData']['quantity'],
        ])->id;

        // After.
        $after = Depositinputproduct::find($depositinputproduct_id);

        // Mensagem.
        $message = 'Produto ' . $after->product->name . ' incluído na Entrada.';
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
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditItemAmount(array $data) : bool {
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
    public static function editItemAmount(array $data) : bool {
        // Atualiza a Produto da Entrada.
        Depositinputproduct::find($data['validatedData']['depositinputproduct_id'])->update([
            'quantity_final' => $data['validatedData']['quantity_final'],
        ]);

        // Atualiza Item do Fornecedor.
        Provideritem::find($data['validatedData']['provideritem_id'])->update([
            'signal' => $data['validatedData']['signal'],
            'amount' => $data['validatedData']['amount'],
        ]);

        // Verifica se o Produto não existe no Depósito
        if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->doesntExist()):
            // Cadastra o Produto no Depósito.
            Productdeposit::create([
                'product_id' => $data['validatedData']['product_id'], 
                'deposit_id' => $data['validatedData']['deposit_id'],
            ]);
        endif;

        // Acrescenta a Quantidade no Depósito.
        $newQtdDep = Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity += $data['validatedData']['quantity_final'];
        Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->update([
            'quantity' => $newQtdDep,
        ]);

        // Acrescenta a Quantidade no Produto.
        $newQtdProd = Product::find($data['validatedData']['product_id'])->quantity += $data['validatedData']['quantity_final'];
        Product::find($data['validatedData']['product_id'])->update([
            'quantity' => $newQtdProd,
        ]);

        // Registra Movimentação do Produto.
        Productmoviment::create([
            'product_id' => $data['validatedData']['product_id'],
            'identification' => 'Entrada no Depósito: ' . $data['validatedData']['deposit_id'],
            'quantity' => $data['validatedData']['quantity_final'],
            'user_id' => auth()->user()->id,
        ]);

        // Mensagem.
        $message = 'Quantidades dos Produtos atualizadas com sucesso.';
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
    public static function dependencyEditItemAmount(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditProductAmount(array $data) : bool {
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
    public static function editProductAmount(array $data) : bool {
        // Verifica se o Produto não existe no Depósito
        if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->doesntExist()):
            // Cadastra o Produto no Depósito.
            Productdeposit::create([
                'product_id' => $data['validatedData']['product_id'], 
                'deposit_id' => $data['validatedData']['deposit_id'],
            ]);
        endif;

        // Acrescenta a Quantidade no Depósito.
        $newQtdDep = Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity += $data['validatedData']['quantity_final'];
        Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->update([
            'quantity' => $newQtdDep,
        ]);

        // Acrescenta a Quantidade no Produto.
        $newQtdProd = Product::find($data['validatedData']['product_id'])->quantity += $data['validatedData']['quantity_final'];
        Product::find($data['validatedData']['product_id'])->update([
            'quantity' => $newQtdProd,
        ]);

        // Registra Movimentação do Produto.
        Productmoviment::create([
            'product_id' => $data['validatedData']['product_id'],
            'identification' => 'Entrada no Depósito: ' . $data['validatedData']['deposit_id'],
            'quantity' => $data['validatedData']['quantity_final'],
            'user_id' => auth()->user()->id,
        ]);

        // Mensagem.
        $message = 'Quantidades dos Produtos atualizadas com sucesso.';
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
    public static function dependencyEditProductAmount(array $data) : bool {
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

        // Verifica se não existe Produtos na Entrada.
        if(Depositinputproduct::where('depositinput_id', $data['validatedData']['depositinput_id'])->count() == 1):
            $message = 'Produto não pode ser excluído: Entrada com apenas um Produto!';
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
        // Produto da Entrada.
        $depositinputproduct = Depositinputproduct::find($data['validatedData']['depositinputproduct_id']);

        // Exclui Produto da Entrada.
        Depositinputproduct::find($data['validatedData']['depositinputproduct_id'])->delete();

        // Exclui Item da Entrada.
        Depositinputitem::where(['depositinput_id' => $data['validatedData']['depositinput_id'], 'identifier' => $depositinputproduct->identifier])->delete();

        // Mensagem.
        $message = 'Produto ' . $depositinputproduct->product_name . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
