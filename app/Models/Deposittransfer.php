<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposittransfer extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'deposittransfers';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'origin_id',
        'origin_name',

        'destiny_id',
        'destiny_name',

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
    public function origin(){return $this->belongsTo(Deposit::class);}
    public function destiny(){return $this->belongsTo(Deposit::class);}
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

        // Verifica se Depósito de Origem e Destino são o mesmo.
        if($data['validatedData']['origin_id'] == $data['validatedData']['destiny_id']):
            $message = 'origem e destino devem ser Depósitos diferentes';
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
        // Cadastra.
        $deposittransfer_id = Deposittransfer::create([
            'origin_id' => $data['validatedData']['origin_id'],
            'origin_name' => Deposit::find($data['validatedData']['origin_id'])->name,
            'destiny_id' => $data['validatedData']['destiny_id'],
            'destiny_name' => Deposit::find($data['validatedData']['destiny_id'])->name,
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'observation' => $data['validatedData']['observation'],
        ])->id;

        // After.
        $after = Deposittransfer::find($deposittransfer_id);

        // Auditoria.
        Audit::deposittransferAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return $deposittransfer_id;
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
        // Percorre todos os Produtos da Transferência.
        foreach(Deposittransferproduct::where('deposittransfer_id', $data['validatedData']['deposittransfer_id'])->get() as $key => $deposittransferproduct):
            // Exclui Produto da Transferência.
            Deposittransferproduct::find($deposittransferproduct->id)->delete();
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
        Deposittransfer::find($data['validatedData']['deposittransfer_id'])->delete();

        // Auditoria.
        Audit::deposittransferErase($data);

        // Mensagem.
        $message = 'Tranferência de Produtos excluída com sucesso.';
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
        // Produto do Balanço.
        $deposittransfer = Deposittransfer::find($data['validatedData']['deposittransfer_id']);

        // Percorre todos os Produtos da Transferência.
        foreach(Deposittransferproduct::where('output_id', $data['validatedData']['deposittransfer_id'])->get() as $key => $deposittransferproduct):
            // Retira Quantidade do Produto no Depósito Origem.
            $productorigin = Productdeposit::where(['product_id' => $deposittransferproduct->product->id, 'deposit_id' => $data['validatedData']['origin_id']])->first();
            $productorigin_quantity = $productorigin->quantity - General::encodeFloat($data['validatedData']['quantity'], 7);
            Productdeposit::where(['product_id' => $deposittransferproduct->product->id, 'deposit_id' => $data['validatedData']['origin_id']])->update([
                'quantity' => $productorigin_quantity,
            ]);

            // Acrescenta Quantidade do Produto no Depósito Destino.
            $productdestiny = Productdeposit::where(['product_id' => $deposittransferproduct->product->id, 'deposit_id' => $data['validatedData']['destiny_id']])->first();
            $productdestiny_quantity = $productdestiny->quantity + General::encodeFloat($data['validatedData']['quantity'], 7);
            Productdeposit::where(['product_id' => $deposittransferproduct->product->id, 'deposit_id' => $data['validatedData']['destiny_id']])->update([
                'quantity' => $productdestiny_quantity,
            ]);

            // Regista Movimentação do produto.
            Productmoviment::create([
                'product_id' => $outputproduct->product->id,
                'identification' => 'Transf. Dep: ' . $data['validatedData']['deposittransfer_id'] . '. De:' . $data['validatedData']['origin_id'] . ' Para:' . $data['validatedData']['destiny_id'],
                'quantity' => General::encodeFloat($data['validatedData']['quantity'], 7),
                'user_id' => auth()->user()->id,
            ]);
        endforeach;

        // Atualiza Consolidação.
        Deposittransfer::find($data['validatedData']['deposittransfer_id'])->update([
            'funded' => true,
        ]);

        // Mensagem.
        $message = 'Tranferência entre Depósitos Consolidada com sucesso.';
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
