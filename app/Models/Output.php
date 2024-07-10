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
     * Valida Atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAddFinished(array $data) : bool {
        $message = null;

        // ...

        return true;
    }

    /**
     * Atualiza..
     * @var array $data
     * 
     * @return bool true
     */
    public static function addFinished(array $data) : bool {
        // Produto do Balanço.
        $output = Output::find($data['validatedData']['output_id']);

        // Atualiza Finalização/Consolidação.
        Output::find($data['validatedData']['output_id'])->update([
            'finished' => true,
        ]);

        // Percorre todos os Produtos da Saída.
        foreach(Outputproduct::where('output_id', $data['validatedData']['output_id'])->get() as $key => $outputproduct):
            // Atualiza quantidade do Produto no Depósito.
            $productdeposit = Productdeposit::where(['product_id' => $outputproduct->product->id, 'deposit_id' => $data['validatedData']['deposit_id']])->first();
            $productdeposit_quantity = $productdeposit->quantity - General::encodeFloat($data['validatedData']['quantity'], 7);
            Productdeposit::where(['product_id' => $outputproduct->product->id, 'deposit_id' => $data['validatedData']['deposit_id']])->update([
                'quantity' => $productdeposit_quantity,
            ]);

            // Atualiza quantidade Total do Produto.
            $quantity_last = Product::find($outputproduct->product->id)->quantity;
            $quantity_new = $quantity_last - General::encodeFloat($data['validatedData']['quantity'], 7);
            Product::find($outputproduct->product->id)->update([
                'quantity' => $quantity_new,
            ]);

            // Regista Movimentação do produto.
            Productmoviment::create([
                'product_id' => $outputproduct->product->id,
                'identification' => 'Saída de Produto:' . $data['validatedData']['output_id'],
                'quantity' => General::encodeFloat($data['validatedData']['quantity'], 7),
                'user_id' => auth()->user()->id,
            ]);
        endforeach;

        // Mensagem.
        $message = 'Saída de Produtos Consolidada com sucesso.';
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
    public static function dependencyAddFinished(array $data) : bool {
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
        // Percorre todos os Produtos da Saída.
        foreach(Outputproduct::where('output_id', $data['validatedData']['output_id'])->get() as $key => $outputproduct):
            // Exclui Produto da Saída.
            Outputproduct::find($outputproduct->id)->delete();
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
        Output::find($data['validatedData']['output_id'])->delete();

        // Auditoria.
        Audit::outputErase($data);

        // Mensagem.
        $message = 'Saída de Produtos excluída com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
