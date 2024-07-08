<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outputproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'outputproducts';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'output_id',
        'product_id',
        'product_name',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function output(){return $this->belongsTo(Output::class);}
    public function product(){return $this->belongsTo(Product::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se o Produto já está cadastrado na Saída.
        if(Outputproduct::where(['output_id' => $data['validatedData']['output_id'], 'product_id' => $data['validatedData']['product_id']])->exists()):
            $message = 'Produto já cadastrado nesta saída.';
        endif;

        // Verifica se o produto não existe no depósito.
        if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->doesntExist()):
            // Cadastra Produto no Depósito.
            Productdeposit::create([
                'product_id' => $data['validatedData']['product_id'],
                'deposit_id' => $data['validatedData']['deposit_id'],
            ]);
        endif;

        // Verifica se o Depósito possui a quantidade de Produto desejada.
        if(Productdeposit::where(['product_id' => $data['validatedData']['product_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity < $data['validatedData']['quantity']):
            $message = 'Quantidade insuficiente no Depósito.';
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
        $outputproduct_id = Outputproduct::create([
            'output_id' => $data['validatedData']['output_id'],
            'product_id' => $data['validatedData']['product_id'],
            'product_name' => Product::find($data['validatedData']['product_id'])->name,
            'quantity' => $data['validatedData']['quantity'],
        ])->id;

        // After.
        $after = Outputproduct::find($outputproduct_id);

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
        $outpuproduct = Outputproduct::find($data['validatedData']['outputproduct_id']);

        // Exclui.
        Outputproduct::find($data['validatedData']['outputproduct_id'])->delete();

        // Auditoria.
        //Audit::providerErase($data);

        // Mensagem.
        $message = 'Produto ' . $outpuproduct->product->name . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
