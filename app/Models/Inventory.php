<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Inventory extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'inventories';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'producebrand_id',
        'producebrand_name',

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
    public function producebrand(){return $this->belongsTo(Producebrand::class);}
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

        // Verifica se não possui Produto vinculado com a Marca.
        if(Produce::where('producebrand_id', $data['validatedData']['producebrand_id'])->doesntExist()):
            $message = 'Nenhum Produto vinculado com esta Marca.';
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
        $inventory_id = Inventory::create([
            'producebrand_id' => $data['validatedData']['producebrand_id'],
            'producebrand_name' => Producebrand::find($data['validatedData']['producebrand_id'])->name,
            'deposit_id' => $data['validatedData']['deposit_id'],
            'deposit_name' => Deposit::find($data['validatedData']['deposit_id'])->name,
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'observation' => $data['validatedData']['observation'],
        ])->id;

        // After.
        $after = Inventory::find($inventory_id);

        // Auditoria.
        Audit::inventoryAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return $inventory_id;
    }

    /**
     * Executa dependências de cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAdd(array $data) : bool {
        // Percorre os Produtos da Marca.
        foreach(Produce::where([
            ['producebrand_id', $data['validatedData']['producebrand_id']],
            ['company_id', auth()->user()->company_id],
            ['status', true],
        ])->orderBy('name', 'ASC')->get() as $key => $produce):
            // Cadastra produtos do Balanço.
            Inventoryproduce::create([
                'inventory_id' => $data['validatedData']['inventory_id'],
                'produce_id' => $produce->id,
                'produce_name' => $produce->name,
            ]);
        endforeach;

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
        $inventoryproduce = Inventoryproduce::find($data['validatedData']['inventoryproduce_id']);

        // Atualiza quantidade do Produto no Balanço.
        Inventoryproduce::find($data['validatedData']['inventoryproduce_id'])->update([
            'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
        ]);

        // Verfica se o Produto está vinculado ao depósito.
        if(Producedeposit::where(['produce_id' => $inventoryproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->exists()):
            $quantity_old = Producedeposit::where(['produce_id' => $inventoryproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->first()->quantity;

            // Atualiza quantidade do Produto no Depósito.
            Producedeposit::where(['produce_id' => $inventoryproduce->produce_id, 'deposit_id' => $data['validatedData']['deposit_id']])->update([
                'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
            ]);
        else:
            $quantity_old = 0.00;

            // Vincula o Produto ao Depósito e atualiza quantidade do Produto no Depósito.
            Producedeposit::create([
                'produce_id' => $inventoryproduce->produce_id,
                'deposit_id' => $data['validatedData']['deposit_id'],
                'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
            ]);
        endif;

        // Regista Movimentação do produto.
        Producemoviment::create([
            'produce_id' => $inventoryproduce->produce_id,
            'deposit_id' => $data['validatedData']['deposit_id'],
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'type' => 'balanço',
            'identification' => '{' . 
                'inventory_id:'    . $data['validatedData']['inventory_id']    . ',' .
                'producebrand_id:' . $data['validatedData']['producebrand_id'] . ',' .
            '}',
            'quantity' => General::encodeFloat($data['validatedData']['score'], 7),
        ]);

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
