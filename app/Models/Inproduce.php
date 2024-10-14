<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inproduce extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'inproduces';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'in_id',
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
    public function in(){return $this->belongsTo(In::class);}
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
            // Verifica se o Produto já está cadastrado na Entrada.
            if(Inproduce::where(['in_id' => $data['validatedData']['in_id'], 'produce_id' => $data['validatedData']['produce_id']])->exists()):
                $message = 'Produto já cadastrado nesta Entrada.';
            endif;

            // Verifica se o produto não existe no Depósito.
            if(Producedeposit::where(['produce_id' => $data['validatedData']['produce_id'], 'deposit_id' => $data['validatedData']['deposit_id']])->doesntExist()):
                $message = 'Produto não cadastrado neste Depósito.';
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
        $inproduce_id = Inproduce::create([
            'in_id' => $data['validatedData']['in_id'],
            'produce_id' => $data['validatedData']['produce_id'],
            'produce_name' => Produce::find($data['validatedData']['produce_id'])->name,
            'quantity' => General::encodeFloat3($data['validatedData']['quantity']),
        ])->id;

        // After.
        $after = Inproduce::find($inproduce_id);

        // Mensagem.
        $message = 'Produto ' . $after->produce->name . ' incluído na Entrada.';
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
        $inproduce = Inproduce::find($data['validatedData']['inproduce_id']);

        // Exclui.
        Inproduce::find($data['validatedData']['inproduce_id'])->delete();

        // Mensagem.
        $message = 'Produto ' . $inproduce->produce->name . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
