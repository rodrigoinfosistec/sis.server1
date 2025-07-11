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
                if(Employee::where('registration', $key)->doesntExist()):
                    $message = 'Funcionário com matrícula ' . $key . ' não está cadastrado.';
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
        foreach($data['txtArray'] as $key => $registration):
            //dd($registration);
            // Percorre todas as datas do funcionário.
            foreach($registration as $key_date => $date):
                //dd($date);
                // Percorre todos os eventos do funcionário na data.
                foreach($date as $key_event => $event):
                    //Verifica se o registro já existe pelo funcionário, data e hora.
                    $employee = Employee::where('registration', $event['registration'])->first();
                    if(
                        !Clockregistry::where([
                            ['employee_id', $employee->id],
                            ['date', $event['date']],
                            ['time', $event['time']],
                        ])->exists()
                    ):
                        // Cadastra.
                        Clockregistry::create([
                            'employee_id'   => $employee->id,
                            'employee_name' => $employee->name,
                            'event'         => $event['event'],
                            'date'          => $event['date'],
                            'time'          => $event['time'],
                            'code'          => Str::upper($event['code']),
                        ]);
                    endif;
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
            $message = 'Folga computada: Dia não autorizado para registrar ponto, será reportado à Gerência.';
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

        // Define Limites Min e Máx (Semana ou Sábado).
        if(date_format(date_create($data['validatedData']['date']), 'l') == 'Saturday'):
            // Sábado.
            $min = $employee->limit_start_saturday;
            $max = $employee->limit_end_saturday;
        else:
            // Semana.
            $min = $employee->limit_start_week;
            $max = $employee->limit_end_week;
        endif;

        // Define Limites Atraso (Semana ou Sábado).
        $time = General::timeToMinuts($data['validatedData']['time']);
        $limit_start = $min + $employee->limit_delay;
        $limit_end   = $max - $employee->limit_delay;

        // Verifica se o Funcionário possui Controle para Ponto.
        if($employee->limit_controll):
            // Verifica se o Ponto não possui acesso irrestrito.
            if(!isset($data['validatedData']['cripto'])):
                // Verifique Limites Min e Máx.
                if($time < $min || $time > $max):
                    $message = 'Ponto fora do horário autorizado, falar com seu gestor.';
                endif;

                // Verifica se é o ponto de chegada.
                if(Clockregistry::where([
                    ['employee_id', $employee->id],
                    ['date', date('Y-m-d')],
                ])->doesntExist()):
                    // Verifica se Funcionário está atrasado.
                    if($time > $limit_start):
                        $message = 'Registro com atraso, falar com sua Gerência.';
                    endif;
                endif;

                // Verifica se Funcionário faz parte de algum Grupo e Empresa.
                if(isset($employee->employeegroup_id) && isset($employee->company_id)):
                    // Verifica se Grupo está vinculado com Empresa.
                    if(Employeegroupcompany::where([
                            ['company_id', $employee->company_id],
                            ['employeegroup_id', $employee->employeegroup_id],
                        ])->exists()
                    ):
                        // Verifica se não é Sábado.
                        if(date_format(date_create($data['validatedData']['date']), 'l') != 'Saturday'):
                            // Verifica se o ponto é para almoço.
                            if(Clockregistry::where([
                                ['employee_id', $employee->id],
                                ['date', date('Y-m-d')],
                            ])->count() == 1):
                                // Verifica autorização para intervalo.
                                if(Employeegroup::getLunch($employee->employeegroup_id)['count'] >=
                                    Employeegroupcompany::where([
                                        ['company_id', $employee->company_id],
                                        ['employeegroup_id', $employee->employeegroup_id],
                                    ])->first()->limit
                                ):
                                    $message = 'Limite para almoço excedido, Falar com seu gostor.';
                                endif;
                            endif;
                        endif;
                    endif;
                endif;
            endif;
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
