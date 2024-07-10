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
}
