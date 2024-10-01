<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clockregistry extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockregistries';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',
        'employee_name',

        'event',
        'date',
        'time',
        'photo_link',
        'code',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employee(){return $this->belongsTo(Employee::class);}

    /**
     * Valida cadastro TXT.
     * @var array $data
     * 
     * @return <array, bool>
     */
    public static function validateAddTxt(array $data){
        $message = null;

        // Salva arquivo, caso seja um txt.
        $txtArray = Report::txtClockregistry($data);

        // Verifica se existem dados a serem cadastrados.
        if(!isset($txtArray)):
            $message = 'Registros de Ponto já cadastrados ou inexistentes.';
        endif;

        // Verifica se é um arquivo txt.
        if($txtArray == 'none'):
            $message = 'Arquivo deve ser um txt de ponto.';

            // Reseta variável.
            $txtArray = null;
        endif;

        if(!empty($txtArray) && isset($txtArray)):
            // Percorre todos os funcionários do ponto txt.
            foreach($txtArray as $key => $employee):
                // Verifica se algum funcionário não está cadastrado.
                if(Employee::where('pis', $key)->doesntExist()):
                    $message = 'Funcionário com pis ' . $key . ' não está cadastrado.';
                endif;
            endforeach;
        endif;

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return $txtArray;
    }

    /**
     * Cadastra TXT.
     * @var array $data
     * 
     * @return bool true
     */
    public static function addTxt(array $data) : bool {
        // Percorre todos os funcionários.
        foreach($data['txtArray'] as $key => $pis):
            // Percorre todas as datas do funcionário.
            foreach($pis as $key_date => $date):
                // Percorre todos os eventos do funcionário na data.
                foreach($date as $key_event => $event):
                    // Cadastra.
                    Clockregistry::create([
                        'employee_id'   => Employee::where('pis', $event['pis'])->first()->id,
                        'employee_name' => Employee::where('pis', $event['pis'])->first()->name,
                        'event'         => $event['event'],
                        'date'          => $event['date'],
                        'time'          => $event['time'],
                        'code'          => $event['code'],
                    ]);
                endforeach;
            endforeach;
        endforeach;

        // Mensagem.
        $message = 'Registros cadastrados com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de cadastro TXT.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAddTxt(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Funcionário.
        $employee = Employee::find($data['validatedData']['employee_id']);

        // Verifica se este Registro já foi efetuado.
        if(Clockregistry::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date'], 'time' => $data['validatedData']['time']])->exists()):
            $message = 'Registro já efetuado em ' . date_format(date_create($data['validatedData']['date']), 'd/m/Y') . ' ' . $data['validatedData']['time'] . '.';
        endif;

        // Verifica se o tipo de registro do Funcionário é 'ALTERNATIVO'.
        if($employee->clock_type != 'REGISTRY'):
            $message = 'Sem permissão para utilizar esta função.';
        endif;

        // Verifica se data é domingo.
        if(date_format(date_create($data['validatedData']['date']), 'l') == 'Sunday'):
            $message = 'Domingo: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se data é Feriado.
        if(Holiday::where(['date' => $data['validatedData']['date']])->exists()):
            $message = 'Feriado: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário está de Férias na data.
        if(Employeevacationday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date']])->exists()):
            $message = 'Férias: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário está de Folga na data.
        if(Employeeeasy::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date']])->exists()):
            $message = 'Falta computada: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário está de Licença na data.
        if(Employeelicenseday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date']])->exists()):
            $message = 'Licença médica: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário está de Atestado na data.
        if(Employeeattestday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date']])->exists()):
            $message = 'Atestado médico: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Verifica se Funcionário Faltou na data.
        if(Employeeabsenceday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date']])->exists()):
            $message = 'Falta computada: Dia não autorizado para registrar ponto, será reportado à Gerência.';
        endif;

        // Define o Horário Permitido para Registrar o Ponto (Semana ou Sábado).
        if(date_format(date_create($data['validatedData']['date']), 'l') == 'Saturday'):
            $min  = $employee->limit_start_saturday;
            $max  = $employee->limit_end_saturday;
        else:
            $min  = $employee->limit_start_week;
            $max  = $employee->limit_end_week;
        endif;

        //Verifica se está fora do Horário Permitido.
        $time = General::timeToMinuts($data['validatedData']['time']);
        $limit_start = $min + 65;
        $limit_end   = $max - 65;

        if($employee->limit_controll):
            if(!isset($data['validatedData']['cripto'])):
                if(!(
                    // Entre o limite start e + 65 minutos.
                    (($time >= $min)       && ($time <= $limit_start )) ||
        
                    // Entre 10h e 16h.
                    //(($time >= 600)        && ($time <= 960))           ||
        
                    // Entre o limite end e - 65 minutos.
                    (($time >= $limit_end) && ($time <= $max ))
                )):
                    $message = 'Registro de Ponto fora do horário autorizado, falar com sua Gerência.';
                endif;
            endif;
        endif;

        // if($employee->limit_controll):
        //     if(!isset($data['validatedData']['cripto'])):
        //         if(($time < $min) || ($time > $max )):
        //             $message = 'Registro de Ponto fora do horário autorizado, falar com sua Gerência.';
        //         endif;
        //     endif;
        // endif;

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
        Clockregistry::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => $data['validatedData']['employee_name'],
            'date'          => $data['validatedData']['date'],
            'time'          => $data['validatedData']['time'],
        ]);

        // After.
        $after = Clockregistry::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date'], 'time' => $data['validatedData']['time']])->first();

        // Auditoria.
        Audit::clockregistryAdd($data, $after);

        // Mensagem.
        $message = 'Ponto: ' . date_format(date_create($data['validatedData']['date']), 'd/m') . ' às ' . $data['validatedData']['time'] . ' registrado.';
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

    /**
     * Valida edição de data.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditDate(array $data) : bool {
        $message = null;
        
        dd($data);

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return true;
    }

    /**
     * Edita Data.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editDate(array $data) : bool {
        // Cadastra.
        Clockregistry::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => $data['validatedData']['employee_name'],
            'date'          => $data['validatedData']['date'],
            'time'          => $data['validatedData']['time'],
        ]);

        // After.
        $after = Clockregistry::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $data['validatedData']['date'], 'time' => $data['validatedData']['time']])->first();

        // Auditoria.
        Audit::clockregistryEditDate($data, $after);

        // Mensagem.
        $message = 'Registro de ponto em ' . date_format(date_create($data['validatedData']['date']), 'd/m/Y') . ' ' . $data['validatedData']['time'] . ' cadastrado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de edição de data.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEditDate(array $data) : bool {
        //...

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
        // ...

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
        Clockregistry::find($data['validatedData']['clockregistry_id'])->delete();

        // Auditoria.
        Audit::clockregistryErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' do funcionário ' .  $data['validatedData']['employee_name'] . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
