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
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditItemAmount(array $data) : bool {
        $message = null;

        // Verifica se o Produto existe.
        if( Product::where('id', $data['validatedData']['product_id'])->doesntExist() ):
            $message = 'Produto ID: ' . $data['validatedData']['product_id'] . ' inexistente!';
        endif;

        // Verifica se o Produto já consta na Entrada.
        if( Depositinputproduct::where('product_id', $data['validatedData']['product_id'])->exists() ):
            $message = 'Produto ID: ' . $data['validatedData']['product_id'] . ' Já existe nesta Entrada.';
        endif;

        // Verifica se produto já está relacionado com outro item deste Fornecedor.
        if( Provideritem::where(['provider_id' => $data['validatedData']['provider_id'], 'product_id' => $data['validatedData']['product_id']])->exists() ):
            $message = 'Produto ID: ' . $data['validatedData']['product_id'] . ' Já relacionado com outro item deste Fornecedor.';
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
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editItemAmount(array $data) : bool {
        // Cadastra Produto na Entrada.
        Depositinputproduct::create([
            'depositinput_id' => $data['validatedData']['depositinput_id'],
            'product_id' => $data['validatedData']['product_id'],
            'product_name' => $data['validatedData']['product_name'],
            'identifier' => $data['validatedData']['identifier'],
            'quantity' => $data['validatedData']['quantity'],
            'quantity_final' => $data['validatedData']['quantity'],
        ]);

        // Relaciona Produto com Item.
        Provideritem::find($data['validatedData']['provideritem_id'])->update([
            'product_id' => $data['validatedData']['product_id'],
        ]);

        // Verifica se Produto não está relacionado com Fornecedor.
        if(Productprovider::where(['product_id' => $data['validatedData']['product_id'], 'provider_id' => $data['validatedData']['provider_id']])->doesntExist()):
            Productprovider::create([
                'product_id' => $data['validatedData']['product_id'],
                'product_code' => $data['validatedData']['product_code'],
                'provider_id' => $data['validatedData']['provider_id'],
                'provider_code' => $data['validatedData']['provider_code'],
            ]);
        else:
            Productprovider::where(['product_id' => $data['validatedData']['product_id'], 'provider_id' => $data['validatedData']['provider_id']])->update([
                'provider_code' => $data['validatedData']['provider_code'],
            ]);
        endif;

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
}
