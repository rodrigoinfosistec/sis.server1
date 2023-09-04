<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'holidays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'date',
        'week',
        'year',
        'name',

        'created_at',
        'updated_at',
    ];

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
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
        Holiday::create([
            'date' => $data['validatedData']['date'],
            'week' => $data['validatedData']['week'],
            'year' => $data['validatedData']['year'],
            'name' => Str::upper($data['validatedData']['name']),
        ]);

        // After.
        $after = Holiday::where('date', $data['validatedData']['date'])->first();

        // Auditoria.
        Audit::holidayAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->name . ' cadastrado com sucesso.';
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
        //...

        return true;
    }

}
