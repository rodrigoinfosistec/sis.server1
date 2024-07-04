<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Balance extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'balances';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'provider_id',
        'provider_name',

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
    public function provider(){return $this->belongsTo(Provider::class);}
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
    public static function add(array $data) : bool {
        // Cadastra.
        $balance_id = Balance::create([
            'provider_id' => $data['validatedData']['provider_id'],
            'provider_name' => Provider::find($data['validatedData']['provider_id'])->name,
            'deposit_id' => $data['validatedData']['deposit_id'],
            'deposit_name' => Deposit::find($data['validatedData']['deposit_id'])->name,
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'observation' => $data['validatedData']['observation'],
        ])->id;

        // After.
        $after = Balance::find($balance_id);

        // Auditoria.
        Audit::balanceAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return $balance_id;
    }

    /**
     * Executa dependências de cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAdd(array $data) : bool {
        // Percorre 
        foreach(Productprovider::where('provider_id', $data['validatedData']['provider_id']) as $key => $productprovider):
            // Cadastra produtos do Balanço.
            Balanceproduct::create([
                'balance_id' => $data['validatedData']['balance_id'],
                'product_id' =>$productprovider->product_id,
            ]);
        endforeach;

        return true;
    }
}
