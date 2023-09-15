<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clockfunded extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockfundeds';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id',

        'start',
        'end',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function clock(){return $this->belongsTo(Clock::class);}
    
    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se Ponto já não foi consolidado.
        if(Clockfunded::where('clock_id', )->doesntExist($data['validatedData']['clock_id'])):
            $message = 'Ponto já foi consolidado';
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
    public static function add(array $data) : bool {
        // Clock
        $after = Clock::find($data['validatedData']['clock_id']);

        // Cadastra Consolidação de Ponto.
        Clockfunded::create([
            'clock_id' => $after->id,
            'start'    => $after->start,
            'end'      => $after->end,
        ]);

        // Auditoria.
        Audit::clockfundedAdd($data, $after);

        // Mensagem.
        $message = 'Ponto consolidado com sucesso.';
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
        // Percorre todos os Funcionários do ponto consolidado.
        foreach(Clockemployee::where('clock_id', $data['validatedData']['clock_id'])->get() as $key => $clockemployee):
            // Consolida Ponto Invidual.
            Clockemployeefunded::create([
                'clock_id'    => $clockemployee->clock->id,
                'employee_id' => $clockemployee->employee->id,
                'allowance'   => $clockemployee->allowance_total,
                'delay'       => $clockemployee->delay_total,
                'extra'       => $clockemployee->extra_total,
                'balance'     => $clockemployee->balance_total,
            ]);

            // Converte Saldo em minutos.
            if($clockemployee->balance_total[0] == '+'):

            elseif($clockemployee->balance_total[0] == '-'):

            else:

            endif;

            // Atualiza banvo de Horas do Funcionário.
            Employee::find($clockemployee->employee->id)->update([
                'datatime' => $clockemployee->employee->datatime =+ ($balance_minuts),
            ]);

        endforeach;


        return true;
    }
}
