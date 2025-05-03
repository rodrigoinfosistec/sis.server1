<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Producebrand extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'producebrands';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'status',

        'created_at',
        'updated_at',
    ];

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
        Producebrand::create([
            'name' => Str::upper($data['validatedData']['name']),
        ]);

        // After.
        $after = Producebrand::where('name', Str::upper($data['validatedData']['name']))->first();

        // Auditoria.
        Audit::producebrandAdd($data, $after);

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
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEdit(array $data) : bool {
        $message = null;

        // Marca.
        $producebrand = Producebrand::find($data['validatedData']['producebrand_id']);

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
        $before = Producebrand::find($data['validatedData']['producebrand_id']);

        // Atualiza.
        Producebrand::find($data['validatedData']['producebrand_id'])->update([
            'name'   => Str::upper($data['validatedData']['name']),
            'status' => $data['validatedData']['status'],
        ]);

        // After.
        $after = Producebrand::find($data['validatedData']['producebrand_id']);

        // Auditoria.
        Audit::producebrandEdit($data, $before, $after);

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
        Producebrand::find($data['validatedData']['producebrand_id'])->delete();

        // Auditoria.
        Audit::producebrandErase($data);

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
        if($list = Producebrand::where([
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
        Report::producebrandGenerate($data);

        // Auditoria.
        Audit::producebrandGenerate($data);

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
        Email::producebrandMail($data);

        // Auditoria.
        Audit::producebrandMail($data);

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

    /**
     * Auditoria Producebrand Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function producebrandAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='     . $after->id     . ',' .
                'name='   . $after->name   . ',' .
                'status=' . $after->status . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Producebrand Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function producebrandEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . $data['config']['title'] . ' {' .
                'id='     . $before->id     . '>' . $after->id     . ',' .
                'name='   . $before->name   . '>' . $after->name   . ',' .
                'status=' . $before->status . '>' . $after->status . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Producebrand Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function producebrandErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='     . $data['validatedData']['bank_id'] . ',' .
                'name='   . $data['validatedData']['name']    . ',' .
                'status=' . $data['validatedData']['status']  . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Producebrand Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function producebrandGenerate(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[gerou relatório]' . $data['config']['title'] . '{' .
                'folder='    . $data['config']['name'] . ',' .
                'file_name=' . $data['file_name']      . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Producebrand Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function producebrandMail(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[enviou e-mail]' . $data['config']['title'] . '{' .
                'folder='    . $data['config']['name']                    . ',' .
                'report_id=' . $data['validatedData']['report_id']        . ',' .
                'email='     . $data['validatedData']['mail']             . ',' .
                'subject='   . 'Relatório de ' . $data['config']['title'] . ',' .
                'title='     . $data['config']['title']                   . ',' .
                'comment='   . $data['validatedData']['comment']          . ',' .
            '}',
        ]);

        return true;
    }

}
