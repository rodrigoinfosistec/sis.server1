<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employees';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'company_id',
        'company_name',

        'pis',
        'name',

        'journey_start_week',
        'journey_end_week',
        'journey_start_saturday',
        'journey_end_saturday',

        'clock_type', // (event/registry)

        'datatime',

        'code',

        'status',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function company(){return $this->belongsTo(Company::class);}

    /**
     * Converte data.
     * @var string $pis_decode
     * 
     * @return string $pis_encode
     */
    public static function encodePis(string $pis_decode) : string {
        $pis_encode = 
            $pis_decode[0] . $pis_decode[1]  . $pis_decode[2] . $pis_decode[3] . 
            '.' . 
            $pis_decode[4] . $pis_decode[5]  . $pis_decode[6] . $pis_decode[7] . $pis_decode[8] . 
            '.' .
            $pis_decode[9] . $pis_decode[10] . 
            '/' . 
            $pis_decode[11]
        ;

        return (string)$pis_encode;
    }

    /**
     * Verifica se Código está vazio.
     * @var <null, int> $code
     * 
     * @return <string, null> $cd
     */
    public static function codeValidateNull($code){
        // Inicializa variável.
        $cd = null;
    
        // Verifica se Código está vazio.
        if (!empty($code)) $cd = Str::upper($code);

        return $cd;
    }

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se final da jornada da semana é maior que o início.
        if($data['validatedData']['journey_start_week'] >= $data['validatedData']['journey_end_week']):
            $message = 'Horário final da jornada da semana deve ser maior que o início da jornada';
        endif;

        // Verifica se final da jornada da semana é maior que o início.
        if($data['validatedData']['journey_start_saturday'] >= $data['validatedData']['journey_end_saturday']):
            $message = 'Horário final da jornada do sábado deve ser maior que o início da jornada';
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
        Employee::create([
            'company_id'             => $data['validatedData']['company_id'],
            'company_name'           => Company::find($data['validatedData']['company_id'])->name,
            'pis'                    => $data['validatedData']['pis'],
            'name'                   => Str::upper($data['validatedData']['name']),
            'journey_start_week'     => $data['validatedData']['journey_start_week'],
            'journey_end_week'       => $data['validatedData']['journey_end_week'],
            'journey_start_saturday' => $data['validatedData']['journey_start_saturday'],
            'journey_end_saturday'   => $data['validatedData']['journey_end_saturday'],
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

    /**
     * Valida cadastro TXT.
     * @var array $data
     * 
     * @return <array, bool>
     */
    public static function validateAddTxt(array $data){
        $message = null;

        // Salva arquivo, caso seja um txt.
        $txtArray = Report::txtEmployee($data);

        // Verifica se é um arquivo txt.
        if(empty($txtArray)):
            $message = 'Arquivo deve ser um txt de colaborador (registro de ponto).';
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
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEdit(array $data) : bool {
        $message = null;

        // Verifica se final da jornada da semana é maior que o início.
        if($data['validatedData']['journey_start_week'] >= $data['validatedData']['journey_end_week']):
            $message = 'Horário final da jornada da semana deve ser maior que o início da jornada';
        endif;

        // Verifica se final da jornada da semana é maior que o início.
        if($data['validatedData']['journey_start_saturday'] >= $data['validatedData']['journey_end_saturday']):
            $message = 'Horário final da jornada do sábado deve ser maior que o início da jornada';
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
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Before.
        $before = Employee::find($data['validatedData']['employee_id']);

        // Atualiza.
        Employee::find($data['validatedData']['employee_id'])->update([
            'company_id'             => $data['validatedData']['company_id'],
            'company_name'           => Company::find($data['validatedData']['company_id'])->name,
            'pis'                    => $data['validatedData']['pis'],
            'name'                   => Str::upper($data['validatedData']['name']),
            'journey_start_week'     => $data['validatedData']['journey_start_week'],
            'journey_end_week'       => $data['validatedData']['journey_end_week'],
            'journey_start_saturday' => $data['validatedData']['journey_start_saturday'],
            'journey_end_saturday'   => $data['validatedData']['journey_end_saturday'],
            'clock_type'             => $data['validatedData']['clock_type'],
            'code'                   => Employee::codeValidateNull($data['validatedData']['code']),
            'status'                 => $data['validatedData']['status'],
        ]);

        // After.
        $after = Employee::find($data['validatedData']['employee_id']);

        // Auditoria.
        Audit::employeeEdit($data, $before, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $after->name . ' atualizado com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEdit(array $data) : bool {
        // ...

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

        $employee = Employee::find($data['validatedData']['employee_id']);

        // Verifica se Funcionário possui Saldo (+/-) no banco de Horas.
        if($employee->datatime != 0):
            if($employee->datatime > 0):
                $hour  = $employee->datatime / 60;
                $hour  = (int)$hour;
                $minut = $employee->datatime % 60;
    
                $time = '+' . str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);
            else:
                $employee->datatime = abs($employee->datatime);
                $hour  = $employee->datatime / 60;
                $hour  = (int)$hour;
                $minut = $employee->datatime % 60;
    
                $time = '-' . str_pad($hour, 2 ,'0' , STR_PAD_LEFT) . ':' . str_pad($minut, 2 ,'0' , STR_PAD_LEFT);
            endif;

            $message = 'Funcionário ' . $employee->name . ' possui ' . $time . 'H no Banco de Horas.';
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
        Employee::find($data['validatedData']['employee_id'])->delete();

        // Auditoria.
        Audit::employeeErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['name'] . ' excluído com sucesso.';
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
        if($list = Employee::where([
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
        Report::employeeGenerate($data);

        // Auditoria.
        Audit::employeeGenerate($data);

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
        Email::employeeMail($data);

        // Auditoria.
        Audit::employeeMail($data);

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
