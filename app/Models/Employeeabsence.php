<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employeeabsence extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeeabsences';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',
        'employee_name',

        'date_start',
        'date_end',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employee(){return $this->belongsTo(Employee::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se final da jornada da semana é maior que o início.
        if($data['validatedData']['date_start'] > $data['validatedData']['date_end']):
            $message = 'Final das faltas deve ser maior que o início das faltas.';
        endif;

        // Percorre todos os dias da férias.
        $y = $data['validatedData']['date_start'];
        while($y <= $data['validatedData']['date_end']):
            // Verifica se alguma data da férias já consta em outra férias.
            if(Employeeabsenceday::where('date', $y)->exists()):
                $message = 'O dia ' . General::decodeDate($y) . ' já consta em outra falta do funcionário.';
            endif;

            $y = date('Y-m-d', strtotime('+1 days', strtotime($y)));
        endwhile;

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
        Employeeabsence::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => Employee::find($data['validatedData']['employee_id'])->name,
            'date_start'    => $data['validatedData']['date_start'],
            'date_end'      => $data['validatedData']['date_end'],
        ]);

        // After.
        $after = Employeeabsence::where(['date_start' => $data['validatedData']['date_start'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Auditoria.
        Audit::employeeabsenceAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' do funcionário ' . $after->employee_name . ' cadastrada com sucesso.';
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
        // Falta de funcionário.
        $employeeabsence = Employeeabsence::where(['date_start' => $data['validatedData']['date_start'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Percorre todas as datas da Falta.
        $y = $data['validatedData']['date_start'];
        while($y <= $data['validatedData']['date_end']):
            // Cadastra dia de Falta.
            Employeeabsenceday::create([
                'employeeabsence_id' => $employeeabsence->id,
                'employee_id'        => $employeeabsence->employee_id,
                'date'               => $y,
            ]);

            $y = date('Y-m-d', strtotime('+1 days', strtotime($y)));
        endwhile;

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
        // Exclui dias das férias.
        Employeeabsenceday::where('employeeabsence_id', $data['validatedData']['employeeabsence_id'])->delete();

        // Percorre todas as datas da Falta.
        $y = $data['validatedData']['date_start_encode'];
        while($y <= $data['validatedData']['date_end_encode']):
            // Desfaz autorização na data.
            Clockday::where(['employee_id' => $data['validatedData']['employee_id'], 'date' => $y])->update([
                'authorized' => false,
            ]);

            $y = date('Y-m-d', strtotime('+1 days', strtotime($y)));
        endwhile;

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
        Employeeabsence::find($data['validatedData']['employeeabsence_id'])->delete();

        // Auditoria.
        Audit::employeeabsenceErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' do funcionário ' .  $data['validatedData']['employee_name'] . ' excluída com sucesso.';
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
        if($list = Employeeabsence::where([
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
        Report::employeeabsenceGenerate($data);

        // Auditoria.
        Audit::employeeabsenceGenerate($data);

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
        Email::employeeabsenceMail($data);

        // Auditoria.
        Audit::employeeabsenceMail($data);

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
