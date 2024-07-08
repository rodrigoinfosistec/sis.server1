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
    public static function validateAdd(array $data){
        $message = null;

        // Verifica se o Produto já está cadastrado na Saída.
        if(Outputproduct::where(['output_id' => $data['validatedData']['output_id'], 'product_id' => $data['validatedData']['product_id']])->exists()):
            $message = 'Produto já cadastrado nesta saída.';
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
    public static function add(array $data) {
        $outputproduct_id = Outputproduct::create([
            'outout_id' => $data['validatedData']['output_id'],
            'product_id' => $data['validatedData']['product_id'],
            'quantity' => $data['validatedData']['quantity'],
        ])->id;

        // After.
        $after = Output::find($outputproduct_id);

        // Mensagem.
        $message = 'Produto ' . $after->product->name . ' incluído na Saída.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return $output_id;
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
}
