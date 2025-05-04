<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'tasks';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'activity_name',
        'activity_id',

        'requester_id',
        'requester_name',

        'responsible_id',
        'responsible_name',

        'description',

        'priority',

        'is_completed',

        'due_date',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function activity(){return $this->belongsTo(Activity::class);}
    public function requester(){return $this->belongsTo(User::class);}
    public function responsible(){return $this->belongsTo(User::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
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
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Cadastra.
        $task_id = Task::create([
            'activity_id'      => $data['validatedData']['activity_id'],
            'activity_name'    => Activity::find($data['validatedData']['activity_id'])->name,
            'requester_id'     => $data['validatedData']['requester_id'],
            'requester_name'   => User::find($data['validatedData']['requester_id'])->name,
            'responsible_id'   => $data['validatedData']['responsible_id'],
            'responsible_name' => User::find($data['validatedData']['responsible_id'])->name,
            'description'      => $data['validatedData']['description'],
            'priority'         => $data['validatedData']['priority'],
            'due_date'         => $data['validatedData']['due_date'],
        ]);

        // After.
        $after = Task::find($task_id);

        // Auditoria.
        Audit::taskAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->activity_name . ' cadastrada com sucesso.';
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
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEdit(array $data) : bool {
        $message = null;

        // Tarefa.
        //$task = Task::find($data['validatedData']['task_id']);

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
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Before.
        $before = Task::find($data['validatedData']['task_id']);

        // Atualiza.
        Task::find($data['validatedData']['task_id'])->update([
            'activity_id'      => $data['validatedData']['activity_id'],
            'activity_name'    => Activity::find($data['validatedData']['activity_id'])->name,
            'requester_id'     => $data['validatedData']['requester_id'],
            'requester_name'   => User::find($data['validatedData']['requester_id'])->name,
            'responsible_id'   => $data['validatedData']['responsible_id'],
            'responsible_name' => User::find($data['validatedData']['responsible_id'])->name,
            'description'      => $data['validatedData']['description'],
            'priority'         => $data['validatedData']['priority'],
            'due_date'         => $data['validatedData']['due_date'],
        ]);

        // After.
        $after = Task::find($data['validatedData']['task_id']);

        // Auditoria.
        Audit::taskEdit($data, $before, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $after->activity_name . ' atualizada com sucesso.';
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
        Task::find($data['validatedData']['task_id'])->delete();

        // Auditoria.
        Audit::taskErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['activity_name'] . ' excluída com sucesso.';
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
        if($list = Task::where([
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

        // Gera o PDF.
        Report::taskGenerate($data);

        // Auditoria.
        Audit::taskGenerate($data);

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
        Email::taskMail($data);

        // Auditoria.
        Audit::taskMail($data);

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
