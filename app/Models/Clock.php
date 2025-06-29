<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clock extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clocks';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'company_id',
        'company_name',

        'start',
        'end',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function company(){return $this->belongsTo(Company::class);}

    /**
     * Intervalo entre dois horários.
     * 
     * @var string $start
     * @var string $end
     * 
     * @return string 
     */
    public static function intervalMinuts(string $start, string $end) {
        $start  = explode( ':', $start );
        $end    = explode( ':', $end );
        $minuts = ($end[0] - $start[0] ) * 60 + $end[1] - $start[1];
        if($minuts < 0) $minuts += 24 * 60;

        return sprintf( '%d:%d', $minuts / 60, $minuts % 60 );
    }

    /**
     * Soma dois horários.
     * 
     * @var string $firstHour
     * @var string $secondHour
     * 
     * @return string 
     */
    public static function sumHours($firstHour, $secondHour) {
        $firstHour  = $firstHour  . ':00';
        $secondHour = $secondHour . ':00';

        $baseDate = date('Y-m-d');
        $baseTime = strtotime($baseDate . ' 00:00:00');
    
        $firstTime = strtotime($baseDate . ' ' . $firstHour) - $baseTime;
        $secondTime = strtotime($baseDate . ' ' . $secondHour) - $baseTime;
    
        $resultTime = $firstTime + $secondTime;

        return date('h:i', $baseTime + $resultTime);
    }

    /**
     * Converte minutos em Time.
     * 
     * @var int $minuts
     * 
     * @return string $time
     */
    public static function minutsToTimeSignal(int $minuts) : string {
        if($minuts > 0):
            $hour   = $minuts / 60;
            $hour   = (int)$hour;
            $minut  = $minuts % 60;
            $signal = '+';
        elseif($minuts < 0):
            $minuts = abs($minuts);
            $hour   = $minuts / 60;
            $hour   = (int)$hour;
            $minut  = $minuts % 60;
            $signal = '-';
        else:
            $hour   = 0;
            $minut  = 0;
            $signal = '';
        endif;

        $time = $signal . str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);

        return $time;
    }

    /**
     * Valida cadastro TXT.
     * @var array $data
     * 
     * @return <array, bool>
     */
    public static function validateAddTxt(array $data){
        $message = null;

        // Salva arquivo, caso seja um txt.
        $txtArray = Report::txtClock($data);

        // Verifica se é um arquivo txt.
        if(empty($txtArray)):
            $message = 'Arquivo deve ser um txt de ponto e correspondente a data solicitada.';
        else:
            // Percorre todos os funcionários do ponto txt.
            foreach($txtArray['pis'] as $key => $pis):
                // Verifica se algum funcionário não está cadastrado.
                if(Employee::where('pis', $pis)->doesntExist()):
                    $message = 'Funcionário com pis ' . $pis . ' não está cadastrado.';
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
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se a data final é maior que a data inicial.
        if($data['validatedData']['start'] > $data['validatedData']['end']):
            $message = 'Data final deve ser maior que a data inicial.';
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
        Clock::create([
            'company_id'   => $data['validatedData']['company_id'],
            'company_name' => Company::find($data['validatedData']['company_id'])->name,
            'start'        => $data['validatedData']['start'],
            'end'          => $data['validatedData']['end'],
        ]);

        // After.
        $after = Clock::where(['start' => $data['validatedData']['start'], 'end' => $data['validatedData']['end'], 'company_id' => $data['validatedData']['company_id']])->orderBy('id', 'DESC')->first();

        // Auditoria.
        Audit::clockAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' da empresa ' . $after->company_name . ' cadastrado com sucesso.';
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
        // Ponto.
        $clock = Clock::where(['start' => $data['validatedData']['start'], 'end' => $data['validatedData']['end'], 'company_id' => $data['validatedData']['company_id']])->orderBy('id', 'DESC')->first();

        // Verifica se existe o array $data['txtArray'].
        if(!empty($data['txtArray'])):
            // Percorre todos os funcionários do txt.
            foreach($data['txtArray']['pis'] as $key => $pis):
                // Funcionário.
                $employee = Employee::where('pis', $pis)->first();

                // Vincula Funcionários ao ponto.
                Clockemployee::create([
                    'clock_id'               => $clock->id,
                    'employee_id'            => $employee->id,
                    'employee_name'          => Employee::find($employee->id)->name,
                    'journey_start_week'     => $employee->journey_start_week,
                    'journey_end_week'       => $employee->journey_end_week,
                    'journey_start_saturday' => $employee->journey_start_saturday,
                    'journey_end_saturday'   => $employee->journey_end_saturday,
                ]);
            endforeach;

            // Percorre todos os eventos.
            foreach($data['txtArray']['event'] as $key => $event):
                // Vincula Eventos ao ponto.
                Clockevent::create([
                    'clock_id'    => $clock->id,
                    'employee_id' => Employee::where('pis', $event['pis'])->first()->id,
                    'event'       => $event['event'],
                    'date'        => $event['date'],
                    'time'        => $event['time'],
                    'code'        => $event['code'],
                ]);
            endforeach;

            // Percorre todos os Funcionários do ponto.
            foreach(Clockemployee::where(['clock_id' => $clock->id])->orderBy('employee_id')->get() as $key => $clockemployee):
                // Cadastra Clockday.
                $date = $data['validatedData']['start'];
                while($date <= $data['validatedData']['end']):
                    // Define a Jornada.
                    if(date_format(date_create($date), 'l') != 'Sunday'):
                        if(date_format(date_create($date), 'l') != 'Saturday'):
                            $journey_start = $clockemployee->journey_start_week;
                            $journey_end   = $clockemployee->journey_end_week;

                            if($clockemployee->employee->trainee):
                                $journey_break = '00:15';
                            else:
                                $journey_break = '01:00';
                            endif;
                        else:
                            $journey_start = $clockemployee->journey_start_saturday;
                            $journey_end   = $clockemployee->journey_end_saturday;
                            $journey_break = null;
                        endif;
                    else:
                        $journey_start = null;
                        $journey_end   = null;
                        $journey_break = null;
                    endif;

                    // Hidrata eventos.
                    $events = Clockevent::where(['clock_id' => $clock->id, 'employee_id' => $clockemployee->employee->id, 'date' => $date])->get();

                    // Verifica se existem eventos.
                    if($events->count() > 0):
                        // Dias não Sábado.
                        if(date_format(date_create($date), 'l') != 'Saturday'):
                            // Input.
                            $input = $events[0]->time;

                            // Break Start.
                            if(!empty($events[1])):
                                $break_start = $events[1]->time;
                            else:
                                $break_start = null;
                            endif;

                            // Break End.
                            if(!empty($events[2])):
                                $break_end = $events[2]->time;
                            else:
                                $break_end = null;
                            endif;

                            // Output.
                            if(!empty($events[3])):
                                $output = $events[3]->time;
                            else:
                                $output = null;
                            endif;

                        // Sábados.
                        else:
                            // Input.
                            $input = $events[0]->time;

                            // Output.
                            if(!empty($events[1])):
                                $output = $events[1]->time;
                            else:
                                $output = null;
                            endif;

                            // Intervalo nullo.
                            $break_start = null;
                            $break_end   = null;
                        endif;
                    else:
                        $input         = null;
                        $break_start   = null;
                        $break_end     = null;
                        $output        = null;
                    endif;

                    // Cadastra Clockday.
                    Clockday::create([
                        'clock_id'      => $clock->id,
                        'employee_id'   => $clockemployee->employee->id,
                        'date'          => $date,
                        'input'         => $input,
                        'break_start'   => $break_start,
                        'break_end'     => $break_end,
                        'output'        => $output,
                        'journey_start' => $journey_start,
                        'journey_end'   => $journey_end,
                        'journey_break' => $journey_break,
                    ]);

                    $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));  
                endwhile;
            endforeach;
        else:
            // Percorre todos os funcionários da empresa.
            foreach(Employee::where(['company_id' => $data['validatedData']['company_id'], 'clock_type' => 'LOCAL'])->get() as $key => $employee):
                // Vincula Funcionários ao ponto.
                Clockemployee::create([
                    'clock_id'               => $clock->id,
                    'employee_id'            => $employee->id,
                    'employee_name'          => Employee::find($employee->id)->name,
                    'journey_start_week'     => $employee->journey_start_week,
                    'journey_end_week'       => $employee->journey_end_week,
                    'journey_start_saturday' => $employee->journey_start_saturday,
                    'journey_end_saturday'   => $employee->journey_end_saturday,
                ]);

                // Cadastra Clockday.
                $date = $data['validatedData']['start'];
                while($date <= $data['validatedData']['end']):

                    // Define a Jornada.
                    if(date_format(date_create($date), 'l') != 'Sunday'):
                        if(date_format(date_create($date), 'l') != 'Saturday'):
                            $journey_start = $employee->journey_start_week;
                            $journey_end   = $employee->journey_end_week;

                            if($employee->employee->trainee):
                                $journey_break = '00:15';
                            else:
                                $journey_break = '01:00';
                            endif;
                        else:
                            $journey_start = $employee->journey_start_saturday;
                            $journey_end   = $employee->journey_end_saturday;
                            $journey_break = null;
                        endif;
                    else:
                        $journey_start = null;
                        $journey_end   = null;
                        $journey_break = null;
                    endif;

                    // Hidrata eventos.
                    $events = Clockregistry::where(['employee_id' => $employee->id, 'date' => $date])->get();

                    // Verifica se existem eventos.
                    if($events->count() > 0):
                        // Dias não Sábado.
                        if(date_format(date_create($date), 'l') != 'Saturday'):
                            // Input.
                            $input = $events[0]->time;

                            // Break Start.
                            if(!empty($events[1])):
                                $break_start = $events[1]->time;
                            else:
                                $break_start = null;
                            endif;

                            // Break End.
                            if(!empty($events[2])):
                                $break_end = $events[2]->time;
                            else:
                                $break_end = null;
                            endif;

                            // Output.
                            if(!empty($events[3])):
                                $output = $events[3]->time;
                            else:
                                $output = null;
                            endif;

                        // Sábados.
                        else:
                            // Input.
                            $input = $events[0]->time;

                            // Output.
                            if(!empty($events[1])):
                                $output = $events[1]->time;
                            else:
                                $output = null;
                            endif;

                            // Intervalo nullo.
                            $break_start = null;
                            $break_end   = null;
                        endif;
                    else:
                        $input         = null;
                        $break_start   = null;
                        $break_end     = null;
                        $output        = null;
                    endif;

                    // Cadastra Clockday.
                    Clockday::create([
                        'clock_id'      => $clock->id,
                        'employee_id'   => $employee->id,
                        'date'          => $date,
                        'input'         => $input,
                        'break_start'   => $break_start,
                        'break_end'     => $break_end,
                        'output'        => $output,
                        'journey_start' => $journey_start,
                        'journey_end'   => $journey_end,
                        'journey_break' => $journey_break,
                    ]);

                    $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));  
                endwhile;
            endforeach;
        endif;

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

        // Evita Excluir com banco consolidado.
        if(Clockfunded::where('clock_id', $data['validatedData']['clock_id'])->exists()):
            $message = 'Ponto já consolidado, não é possível excluir.';
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
     * Executa dependências de exclusão.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyErase(array $data) : bool {
        // Exclui os registros diários de funcionários vinculados ao ponto.
        Clockday::where('clock_id', $data['validatedData']['clock_id'])->delete();

        // Exclui os Funcionários vinculados ao ponto.
        Clockemployee::where('clock_id', $data['validatedData']['clock_id'])->delete();

        // Exclui os Eventos vinculados ao ponto.
        Clockevent::where('clock_id', $data['validatedData']['clock_id'])->delete();

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
        Clock::find($data['validatedData']['clock_id'])->delete();

        // Auditoria.
        Audit::clockErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' da empresa ' .  $data['validatedData']['company_name'] . ' excluído com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Valida geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateGenerate(array $data) : bool {
        $message = null;

        // verifica se existe algum item retornado na pesquisa.
        if($list = Clock::where([
                [$data['filter'], 'like', '%'. $data['search'] . '%'],
            ])->doesntExist()):

            $message = 'Nenhum ítem selecionado.';
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
     * Gera relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function generate(array $data) : bool {
        // Estende $data.
        $data['path']      = public_path('/storage/pdf/' . $data['config']['name'] . '/');
        $data['file_name'] = $data['config']['name'] . '_' . auth()->user()->id . '_' . Str::random(20) . '.pdf';

        // Gera PDF.
        Report::clockGenerate($data);

        // Auditoria.
        Audit::clockGenerate($data);

        // Mensagem.
        $message = 'Relatório PDF gerado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyGenerate(array $data) : bool {
        //...

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
            $message = 'Sem conexão com a internet.';
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
        Email::clockMail($data);

        // Auditoria.
        Audit::clockMail($data);

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
