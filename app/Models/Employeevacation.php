<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employeevacation extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeevacations';

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
            $message = 'Final das férias deve ser maior que o início da férias.';
        endif;

        // Percorre todos os dias da férias.
        $y = $data['validatedData']['date_start'];
        while($y <= $data['validatedData']['date_end']):
            // Verifica se alguma data da férias já consta em outra férias.
            if(Employeevacationday::where('date', $y)->exists()):
                $message = 'O dia ' . General::decodeDate($y) . ' já consta em outra férias do funcionário.';
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
        Employeevacation::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => Employee::find($data['validatedData']['employee_id'])->name,
            'date_start'    => $data['validatedData']['date_start'],
            'date_end'      => $data['validatedData']['date_end'],
        ]);

        // After.
        $after = Employeevacation::where(['date_start' => $data['validatedData']['date_start'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Auditoria.
        Audit::employeevacationAdd($data, $after);

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
        // Férias de funcionário.
        $employeevacation = Employeevacation::where(['date_start' => $data['validatedData']['date_start'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Percorre todas as datas da Férias.
        $y = $data['validatedData']['date_start'];
        while($y <= $data['validatedData']['date_end']):
            // Cadastra dia de Férias.
            Employeevacationday::create([
                'employeevacation_id' => $employeevacation->id,
                'employee_id'         => $employeevacation->employee_id,
                'date'                => $y,
            ]);

            // Incrementa.
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
        Employeevacationday::where('employeevacation_id', $data['validatedData']['employeevacation_id'])->delete();

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
        Employeevacation::find($data['validatedData']['employeevacation_id'])->delete();

        // Auditoria.
        Audit::employeevacationErase($data);

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
        if($list = Employeevacation::where([
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
        Report::employeevacationGenerate($data);

        // Auditoria.
        Audit::employeevacationGenerate($data);

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
        Email::employeevacationMail($data);

        // Auditoria.
        Audit::employeevacationMail($data);

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
