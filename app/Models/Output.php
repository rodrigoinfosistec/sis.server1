<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Output extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'outputs';

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
        $output_id = Output::create([
            'deposit_id' => $data['validatedData']['deposit_id'],
            'deposit_name' => Deposit::find($data['validatedData']['deposit_id'])->name,
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'observation' => $data['validatedData']['observation'],
        ])->id;

        // After.
        $after = Output::find($output_id);

        // Auditoria.
        Audit::outputAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrado com sucesso.';
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
        // Produto do Balanço.
        $outputproduct = Outputproduct::find($data['validatedData']['outputproduct_id']);

        // Atualiza quantidade do Produto na Saída.
        Outputproduct::find($data['validatedData']['outputproduct_id'])->update([
            'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
        ]);

        //Verfica se o Produto está vinculado ao depósito.
        if(Productdeposit::where(['product_id' => $outputproduct->product_id, 'deposit_id' => $data['validatedData']['deposit_id']])->exists()):
            // Atualiza quantidade do Produto no Depósito.
            Productdeposit::where(['product_id' => $outputproduct->product_id, 'deposit_id' => $data['validatedData']['deposit_id']])->update([
                'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
            ]);
        else:
            // Vincula o Produto ao Depósito e atualiza quantidade do Produto no Depósito.
            Productdeposit::create([
                'product_id' => $outputproduct->product_id,
                'deposit_id' => $data['validatedData']['deposit_id'],
                'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
            ]);
        endif;

        // Atualiza quantidade Total do Produto.
        $quantity_last = Product::find($outputproduct->product_id)->quantity;
        $quantity_new = $quantity_last + General::encodeFloat($data['validatedData']['score'], 7);
        Product::find($outputproduct->product_id)->update([
            'quantity' => $quantity_new,
        ]);

        // Verifica se quantidade não é Zero (0).
        if(General::encodeFloat($data['validatedData']['score'], 7) != 0):
            // Regista Movimentação do produto.
            Productmoviment::create([
                'product_id' => $outputproduct->product_id,
                'identification' => 'Balanço:' . $data['validatedData']['output_id'],
                'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
                'user_id' => auth()->user()->id,
            ]);
        endif;

        // Mensagem.
        $message = 'Balanço Consolidado';
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
