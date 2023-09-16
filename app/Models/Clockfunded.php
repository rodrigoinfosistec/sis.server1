<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

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
        if(Clockfunded::where('clock_id', $data['validatedData']['clock_id'])->exists()):
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
                $explode = explode('+', $clockemployee->balance_total);
                $b = explode(':', $explode[1]);
                $balance_minuts = (($b[0] * 60) + $b[1]);

            elseif($clockemployee->balance_total[0] == '-'):
                $explode = explode('-', $clockemployee->balance_total);
                $b = explode(':', $explode[1]);
                $balance_minuts = ((($b[0] * 60) + $b[1]) * -1);
            else:
                $balance_minuts = 0;
            endif;

            // Atualiza banvo de Horas do Funcionário.
            Employee::find($clockemployee->employee->id)->update([
                'datatime' => $clockemployee->employee->datatime + ($balance_minuts),
            ]);

            // Registra Movimento do Banco de Horas.
            Clockbase::create([
                'employee_id' => $clockemployee->employee->id,
                'start'       => $clockemployee->clock->start,
                'end'         => $clockemployee->clock->end,
                'time'        => $balance_minuts,
                'description' => 'Consolidação Banco de Horas',
            ]);
        endforeach;

        // Gera PDF.
        Clockfunded::generate($data);

        return true;
    }
    
    /**
     * Gera relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function generate(array $data) : bool {
        // Estende $data.
        $data['path']       = public_path('/storage/pdf/clockfunded/');
        $data['file_name']  = 'clockfunded_' . auth()->user()->id . '_' . $data['validatedData']['clock_id'] . '_' . Str::random(10) . '.pdf';

        // Gera PDF.
        Report::clockfundedGenerate($data);

        // Auditoria
        Audit::clockfundedGenerate($data);

        return true;
    }
    
    /**
     * Valida envio de e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateMail(array $data) : bool {
        $message = null;

        // Verifica conexão com a internet.
        if(checkdnsrr('google.com') < 1):
            $message = 'Sem conexão com a internet..';
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
     * Envia e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function mail(array $data) : bool {
        // Envia e-mail.
        Email::clockefundedMail($data);

        // Auditoria.
        Audit::clockfundedMail($data);

        // Mensagem.
        $message = 'E-mail para ' . $data['validatedData']['mail'] . ' enviado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de envio de e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyMail(array $data) : bool {
        //...

        return true;
    }
}
