<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pointevent extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'pointevents';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',

        'event',
        'date',
        'time',
        'code',

        'type',

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
        $txtArray = Report::txtPointevent($data);

        // Verifica se é um arquivo txt.
        if(empty($txtArray)):
            $message = 'Arquivo deve ser um txt de ponto.';
        endif;

        // Verifica se existem dados a serem cadastrados.
        if(!isset($txtArray)):
            $message = 'Eventos já cadastrados ou inexistentes.';
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
        // Percorre todos os funcionários..
        foreach($data['txtArray'] as $key => $pis):
            // Percorre todas as datas do funcionário.
            foreach($pis as $key_date => $date):
                // Percorre todos os eventos do funcionário na data.
                foreach($date as $key_event => $event):
                    // Cadastra.
                    Pointevent::create([
                        'employee_id' => Employee::where('pis', $event['pis'])->first()->id,
                        'event'       => $event['event'],
                        'date'        => $event['date'],
                        'time'        => $event['time'],
                        'code'        => $event['code'],
                        'type'        => $event['type'],
                    ]);
                endforeach;
            endforeach;
        endforeach;

        // Mensagem.
        $message = 'Eventos cadastrados com sucesso.';
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
     * Valida geração de relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateGenerate(array $data) : bool {
        $message = null;

        // ...

        return true;
    }

    /**
     * Gera relatório.
     * @var array $data
     * 
     * @return bool true
     */
    public static function generate(array $data) : bool {
        // ...

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

        // ...

        return true;
    }

    /**
     * Envia e-mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function mail(array $data) : bool {
        // ...

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
