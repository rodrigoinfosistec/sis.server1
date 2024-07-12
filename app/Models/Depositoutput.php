<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depositoutput extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositoutputs';

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

        'funded',

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
        $depositoutput_id = Depositoutput::create([
            'deposit_id' => $data['validatedData']['deposit_id'],
            'deposit_name' => Deposit::find($data['validatedData']['deposit_id'])->name,
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'observation' => $data['validatedData']['observation'],
        ])->id;

        // After.
        $after = Depositoutput::find($depositoutput_id);

        // Auditoria.
        Audit::depositoutputAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return $depositoutput_id;
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
        // Percorre todos os Produtos da Saída.
        foreach(Depositoutputproduct::where('depositoutput_id', $data['validatedData']['depositoutput_id'])->get() as $key => $depositoutputproduct):
            // Exclui Produto da Saída.
            Depositoutputproduct::find($depositoutputproduct->id)->delete();
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
        Depositoutput::find($data['validatedData']['depositoutput_id'])->delete();

        // Auditoria.
        Audit::depositoutputErase($data);

        // Mensagem.
        $message = 'Saída de Produtos excluída com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Valida Consolidação.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAddFunded(array $data) : bool {
        $message = null;

        // ...

        return true;
    }

    /**
     * Consolida.
     * @var array $data
     * 
     * @return bool true
     */
    public static function addFunded(array $data) : bool {
        // Saída.
        $depositoutput = Depositoutput::find($data['validatedData']['depositoutput_id']);

        // Percorre todos os Produtos da Saída.
        foreach(Depositoutputproduct::where('depositoutput_id', $data['validatedData']['depositoutput_id'])->get() as $key => $depositoutputproduct):
            // Retira Quantidade do Produto no Depósito.
            Productdeposit::where(['product_id' => $depositoutputproduct->product->id, 'deposit_id' => $data['validatedData']['deposit_id']])->update([
                'quantity' => Productdeposit::where(['product_id' => $depositoutputproduct->product->id, 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity - $depositoutputproduct->quantity,
            ]);

            // Atualiza quantidade Total do Produto.
            Product::find($depositoutputproduct->product->id)->update([
                'quantity' => Product::find($depositoutputproduct->product->id)->quantity + $depositoutputproduct->quantity,
            ]);

            // Regista Movimentação do produto.
            Productmoviment::create([
                'product_id' => $depositoutputproduct->product->id,
                'identification' => 'Saída:' . $data['validatedData']['depositoutput_id'] . ' do depósito:' . $data['validatedData']['deposit_id'],
                'quantity' => $depositoutputproduct->quantity,
                'user_id' => auth()->user()->id,
            ]);
        endforeach;

        // Atualiza Consolidação.
        Depositoutput::find($data['validatedData']['depositoutput_id'])->update([
            'funded' => true,
        ]);

        // Mensagem.
        $message = 'Saída Consolidada com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de consolidação.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAddFunded(array $data) : bool {
        // ...

        return true;
    }
}
