<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class In extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'ins';

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
        $in_id = In::create([
            'deposit_id' => $data['validatedData']['deposit_id'],
            'deposit_name' => Deposit::find($data['validatedData']['deposit_id'])->name,
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'observation' => Str::upper($data['validatedData']['observation']),
        ])->id;

        // After.
        $after = In::find($in_id);

        // Auditoria.
        Audit::inAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrada com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return $in_id;
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

        // ...

        return true;
    }

    /**
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Produto do Entrada.
        $inproduce = Inproduce::find($data['validatedData']['inproduce_id']);

        $quantity_old = Producedeposit::where(['produce_id' => $inproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity;

        // Atualiza quantidade do Produto no Depósito.
        Producedeposit::where(['produce_id' => $inproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->update([
            'quantity' => $quantity_old + General::encodeFloat($data['validatedData']['score'], 7),
        ]);

        // Atualiza quantidade do Produto no Entrada.
        Inproduce::find($data['validatedData']['inproduce_id'])->update([
            'quantity_old' => $quantity_old,
            'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
            'quantity_diff' => General::encodeFloat($data['validatedData']['score'], 7)
        ]);

        // Regista Movimentação do produto.
        Producemoviment::create([
            'produce_id' => $inproduce->produce_id,
            'deposit_id' => $data['validatedData']['deposit_id'],
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'type' => 'entrada',
            'identification' => '{' . 
                'in_id:'    . $data['validatedData']['in_id']    . ',' .
            '}',
            'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
        ]);

        // Mensagem.
        $message = 'Entrada Consolidada';
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
        // Percorre todos os Produtos da Entrada.
        foreach(Inproduce::where('in_id', $data['validatedData']['in_id'])->get() as $key => $inproduce):
            // Exclui Produto da Entrada.
            Inproduce::find($inproduce->id)->delete();
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
        In::find($data['validatedData']['in_id'])->delete();

        // Auditoria.
        Audit::inErase($data);

        // Mensagem.
        $message = 'Entrada de Produtos excluída com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
