<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clockemployee extends Model
{
   /**
     * Nome da tabela.
     */
    protected $table = 'clockemployees';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id',
        'employee_id',

        'journey_start_week',
        'journey_end_week',
        'journey_start_saturday',
        'journey_end_saturday',

        'delay_total',
        'extra_total',
        'balance_total',

        'note',

        'authorized',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function clock(){return $this->belongsTo(Clock::class);}
    public function employee(){return $this->belongsTo(Employee::class);}

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
        Clockemployee::create([
            'clock_id '              => $data['validatedData']['company_id'],
            'employee_id '           => Company::find($data['validatedData']['company_id'])->name,
            'journey_start_week'     => $data['validatedData']['pis'],
            'journey_end_week'       => Str::upper($data['validatedData']['name']),
            'journey_start_saturday' => $data['validatedData']['journey_start_week'],
            'journey_end_saturday'   => $data['validatedData']['journey_end_week'],
        ]);

        // After.
        $after = Employee::where('pis', $data['validatedData']['pis'])->first();

        // Auditoria.
        Audit::employeeAdd($data, $after);

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