<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'audits';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'user_id',
        'user_name',

        'page_id',
        'page_name',

        'extensive',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function user(){return $this->belongsTo(User::class);}
    public function page(){return $this->belongsTo(Page::class);}

    /**
     * Organiza os dados de auditoria exibidos.
     * @var string $extensive
     * 
     * @return void
     */
    public static function extensiveData(string $extensive) : void {
        // Ação.
        $a = explode(']', $extensive);

        echo '<span class="text-primary" style="margin-bottom: 20px;">';
        echo substr($a[0], 1) . ' ';

        // Onde.
        $b = explode('{', $a[1]);
        echo $b[0];

        echo '</span><br>';

        // O que.
        $c = explode('}', $b[1]);
        $d = explode(',', $c[0]);

        // Contador.
        $count = count($d) - 1;

        // Dados.
        foreach($d as $key => $item):
            // Remove último dado (vazio).
            if($count != $key):
                $x = explode('=', $item);
                    echo $x[0] . '<span class="text-primary"> = </span>';
                    // Verifica se é dado duplo ">".
                    if(str_contains($x[1], '>')):
                        $y = explode('>', $x[1]);
                            echo '<span class="text-muted">' 
                                    .  $y[0]
                                . '</span>' 

                                . '<i class="bi-caret-right-fill text-danger" style="font-size: 8px;"></i>'

                                . $y[1] . '<br>';
                    else:
                        echo $x[1] . '<br>';
                    endif;
            endif;
        endforeach;
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
        if($list = Audit::where([
                [$data['filter'], 'like', '%'. $data['search'] . '%'],
                ['user_name', '!=', 'MASTER'],
            ])->doesntExist()):

            $message = 'Nenhum item selecionado.';
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
        Report::auditGenerate($data);

        // Auditoria.
        Audit::auditGenerate($data);

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
        Email::auditMail($data);

        // Auditoria.
        Audit::auditMail($data);

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
     * Auditoria Usergroup Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function usergroupAdd(array $data, object $after) : bool {
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
     * Auditoria Usergroup Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function usergroupEdit(array $data, object $before, object $after) : bool {
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
     * Auditoria Usergroup Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function usergroupErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='     . $data['validatedData']['usergroup_id'] . ',' .
                'name='   . $data['validatedData']['name']         . ',' .
                'status=' . $data['validatedData']['status']       . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Usergroup Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function usergroupGenerate(array $data) : bool {
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
     * Auditoria Usergroup Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function usergroupMail(array $data) : bool {
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

    /**
     * Auditoria Usergrouppage Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function usergrouppageAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]Permissão de ' . $data['config']['title'] . ' {' .
                'id='             . $data['usergrouppage_id'] . ',' .
                'usergroup_id='   . $after->usergroup_id      . ',' .
                'usergroup_name=' . $data['name']             . ',' .
                'page_id='        . $data['page_id']          . ',' .
                'page_name='      . $data['page_name']        . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Usergrouppage Erase.
     * @var array $data
     * @var object $before
     * 
     * @return bool true
     */
    public static function usergrouppageErase(array $data, object $before) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu] Permissão de ' . $data['config']['title'] . ' {' .
                'id='             . $data['usergrouppage_id'] . ',' .
                'usergroup_id='   . $before->usergroup_id     . ',' .
                'usergroup_name=' . $data['name']             . ',' .
                'page_id='        . $data['page_id']          . ',' .
                'page_name='      . $data['page_name']        . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria User Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function userAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='             . $after->id             . ',' .
                'usergroup_id='   . $after->usergroup_id   . ',' .
                'usergroup_name=' . $after->usergroup_name . ',' .
                'name='           . $after->name           . ',' .
                'email='          . $after->email          . ',' .
                'status='         . $after->status         . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria User Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function userEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . $data['config']['title'] . ' {' .
                'id='             . $before->id             . '>' . $after->id             . ',' .
                'usergroup_id='   . $before->usergroup_id   . '>' . $after->usergroup_id   . ',' .
                'usergroup_name=' . $before->usergroup_name . '>' . $after->usergroup_name . ',' .
                'name='           . $before->name           . '>' . $after->name           . ',' .
                'email='          . $before->email          . '>' . $after->email          . ',' .
                'status='         . $before->status         . '>' . $after->status         . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria User Edit Password.
     * @var array $data
     * 
     * @return bool true
     */
    public static function userEditPassword(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . 'Senha de ' . $data['config']['title'] . ' {' .
                'id='       . $data['validatedData']['user_id']   . '>' . $data['validatedData']['user_id']   . ',' .
                'name='     . $data['validatedData']['name'] . '>' . $data['validatedData']['name'] . ',' .
                'password=' . '**********'                   . '>' . '**********'                   . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria User Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function userErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='             . $data['validatedData']['user_id']        . ','  .
                'usergroup_id='   . $data['validatedData']['usergroup_id']   . ',' .
                'usergroup_name=' . $data['validatedData']['usergroup_name'] . ',' .
                'name='           . $data['validatedData']['name']           . ',' .
                'email='          . $data['validatedData']['email']          . ',' .
                'status='         . $data['validatedData']['status']         . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria User Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function userGenerate(array $data) : bool {
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
     * Auditoria User Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function userMail(array $data) : bool {
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

    /**
     * Auditoria Audit Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function auditGenerate(array $data) : bool {
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
     * Auditoria Audit Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function auditMail(array $data) : bool {
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
