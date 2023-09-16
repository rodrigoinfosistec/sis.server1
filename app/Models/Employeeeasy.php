<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employeeeasy extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeeeasies';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',
        'employee_name',

        'date',

        'discount',

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

        // Verifica se a data do abono já consta em outro abono.
        if(Employeeeasy::where('date', $data['validatedData']['date'])->exists()):
            $message = 'O dia ' . General::decodeDate($data['validatedData']['date']) . ' já consta em outra folga do funcionário.';
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
        Employeeeasy::create([
            'employee_id'   => $data['validatedData']['employee_id'],
            'employee_name' => Employee::find($data['validatedData']['employee_id'])->name,
            'date'          => $data['validatedData']['date'],
            'discount'      => $data['validatedData']['discount'],
        ]);

        // After.
        $after = Employeeeasy::where(['date' => $data['validatedData']['date'], 'employee_id' => $data['validatedData']['employee_id']])->first();

        // Auditoria.
        Audit::employeeeasyAdd($data, $after);

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
        // Subtrai Banco de Horas.
        if($data['validatedData']['discount']):
            // Define as Horas a serem descontadas.
            (date_format(date_create($data['validatedData']['date']), 'l') == 'Saturday') ? $minuts = -240 : $minuts = -480;

            // Atualiza Banco de Horas.
            $employee = Employee::find($data['validatedData']['employee_id']);

            Employee::find($data['validatedData']['employee_id'])->update([
                'datatime' => ($employee->datatime + ($minuts)),
            ]);

            // Registra Movimento do Banco de Horas.
            Clockbase::create([
                'employee_id' => $data['validatedData']['employee_id'],
                'start'       => $data['validatedData']['date'],
                'end'         => $data['validatedData']['date'],
                'time'        => $minuts,
                'description' => 'Folga',
            ]);
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
        // Desfaz autorização na data.
        Clockday::where(['employee_id' => $data['validatedData']['employee_id'],'date' => $data['validatedData']['date_encode']])->update([
            'authorized' => false,
        ]);

        // Subtrai Banco de Horas.
        if($data['validatedData']['discount']):
            // Define as Horas a serem descontadas.
            (date_format(date_create($data['validatedData']['date']), 'l') == 'Saturday') ? $minuts = 240 : $minuts = 480;

            // Atualiza Banco de Horas.
            $employee = Employee::find($data['validatedData']['employee_id']);

            Employee::find($data['validatedData']['employee_id'])->update([
                'datatime' => ($employee->datatime + ($minuts)),
            ]);

            // Registra Movimento do Banco de Horas.
            Clockbase::create([
                'employee_id' => $data['validatedData']['employee_id'],
                'start'       => $data['validatedData']['date_encode'],
                'end'         => $data['validatedData']['date_encode'],
                'time'        => $minuts,
                'description' => 'Folga Excluída',
            ]);
        endif;

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
        Employeeeasy::find($data['validatedData']['employeeeasy_id'])->delete();

        // Auditoria.
        Audit::employeeeasyErase($data);

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
        if($list = Employeeeasy::where([
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
        Report::employeeeasyGenerate($data);

        // Auditoria.
        Audit::employeeeasyGenerate($data);

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
        Email::employeeeasyMail($data);

        // Auditoria.
        Audit::employeeeasyMail($data);

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
