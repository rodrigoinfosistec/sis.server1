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
        // Produto do Saída.
        $outproduce = Outproduce::find($data['validatedData']['outproduce_id']);

        // Atualiza quantidade do Produto no Saída.
        Outproduce::find($data['validatedData']['outproduce_id'])->update([
            'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
            'quantity_diff' => 0 - General::encodeFloat($data['validatedData']['score'], 7)
        ]);

        $quantity_old = Producedeposit::where(['produce_id' => $outproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity;

        // Atualiza quantidade do Produto no Depósito.
        Producedeposit::where(['produce_id' => $outproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->update([
            'quantity' => $quantity_old - General::encodeFloat($data['validatedData']['score'], 7),
        ]);

        // Regista Movimentação do produto.
        Producemoviment::create([
            'produce_id' => $outproduce->produce_id,
            'deposit_id' => $data['validatedData']['deposit_id'],
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'type' => 'saída',
            'identification' => '{' . 
                'out_id:'    . $data['validatedData']['out_id']    . ',' .
            '}',
            'quantity' => 0 - General::encodeFloat($data['validatedData']['score'], 7),
        ]);

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

}
