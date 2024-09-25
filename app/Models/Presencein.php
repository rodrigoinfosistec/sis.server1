<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presencein extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'presenceins';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'company_name',
        'company_id',

        'user_id',
        'user_name',

        'date',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function company(){return $this->belongsTo(Company::class);}
    public function user(){return $this->belongsTo(User::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool
     */
    public static function validateAdd(array $data) : bool {
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
        $presencein_id = Presencein::create([
            'company_name' => $data['validatedData']['company_name'],
            'company_id'   => $data['validatedData']['company_id'],
            'user_id'      => $data['validatedData']['user_id'],
            'user_name'    => $data['validatedData']['user_name'],
            'date'         => $data['validatedData']['date'],
        ])->id;

        // After.
        $after = Presencein::find($presencein_id);

        // Auditoria.
        Audit::precenceinAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' cadastrada com sucesso.';
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
}
