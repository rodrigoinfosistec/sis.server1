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
                'company_id='     . $after->company_id     . ',' .
                'company_name='   . $after->company_name   . ',' .
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
                'company_id='     . $before->company_id     . '>' . $after->company_id     . ',' .
                'company_name='   . $before->company_name   . '>' . $after->company_name   . ',' .
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
                'id='             . $data['validatedData']['user_id']        . ',' .
                'company_id='     . $data['validatedData']['company_id']     . ',' .
                'company_name='   . $data['validatedData']['company_name']   . ',' .
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

    /**
     * Auditoria Company Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function companyAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='       . $after->id       . ',' .
                'cnpj='     . $after->cnpj     . ',' .
                'name='     . $after->name     . ',' .
                'nickname=' . $after->nickname . ',' .
                'price='    . $after->price    . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Company Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function companyEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . $data['config']['title'] . ' {' .
                'id='       . $before->id       . '>' . $after->id       . ',' .
                'cnpj='     . $before->cnpj     . '>' . $after->cnpj     . ',' .
                'name='     . $before->name     . '>' . $after->name     . ',' .
                'nickname=' . $before->nickname . '>' . $after->nickname . ',' .
                'price='    . $before->price    . '>' . $after->price . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Company Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function companyErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='       . $data['validatedData']['company_id'] . ',' .
                'cnpj='     . $data['validatedData']['cnpj']       . ',' .
                'name='     . $data['validatedData']['name']       . ',' .
                'nickname=' . $data['validatedData']['nickname']   . ',' .
                'price='    . $data['validatedData']['price']      . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Company Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function companyGenerate(array $data) : bool {
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
     * Auditoria Company Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function companyMail(array $data) : bool {
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
     * Auditoria Provider Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function providerAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='       . $after->id       . ',' .
                'cnpj='     . $after->cnpj     . ',' .
                'name='     . $after->name     . ',' .
                'nickname=' . $after->nickname . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Provider Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function providerEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . $data['config']['title'] . ' {' .
                'id='       . $before->id       . '>' . $after->id       . ',' .
                'cnpj='     . $before->cnpj     . '>' . $after->cnpj     . ',' .
                'name='     . $before->name     . '>' . $after->name     . ',' .
                'nickname=' . $before->nickname . '>' . $after->nickname . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Provider Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function providerErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='       . $data['validatedData']['provider_id'] . ',' .
                'cnpj='     . $data['validatedData']['cnpj']        . ',' .
                'name='     . $data['validatedData']['name']        . ',' .
                'nickname=' . $data['validatedData']['nickname']    . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Provider Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function providerGenerate(array $data) : bool {
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
     * Auditoria Provider Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function providerMail(array $data) : bool {
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
     * Auditoria Business Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function providerBusinessAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . 'Negociação de ' . $data['config']['title'] . '{' .
                'provider_id='            . $after->provider_id            . ',' .
                'multiplier_type='        . $after->multiplier_type        . ',' .
                'multiplier_quantity='    . $after->multiplier_quantity    . ',' .
                'multiplier_value='       . $after->multiplier_value       . ',' .
                'multiplier_ipi='         . $after->multiplier_ipi         . ',' .
                'multiplier_ipi_aliquot=' . $after->multiplier_ipi_aliquot . ',' .
                'margin='                 . $after->margin                 . ',' .
                'shipping='               . $after->shipping               . ',' .
                'discount='               . $after->discount               . ',' .
                'addition='               . $after->addition               . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Business Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function providerBusinessEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . 'Negociação de ' . $data['config']['title'] . ' {' .
                'provider_id='            . $before->provider_id            . '>' . $after->provider_id            . ',' .
                'multiplier_type='        . $before->multiplier_type        . '>' . $after->multiplier_type        . ',' .
                'multiplier_quantity='    . $before->multiplier_quantity    . '>' . $after->multiplier_quantity    . ',' .
                'multiplier_value='       . $before->multiplier_value       . '>' . $after->multiplier_value       . ',' .
                'multiplier_ipi='         . $before->multiplier_ipi         . '>' . $after->multiplier_ipi         . ',' .
                'multiplier_ipi_aliquot=' . $before->multiplier_ipi_aliquot . '>' . $after->multiplier_ipi_aliquot . ',' .
                'margin='                 . $before->margin                 . '>' . $after->margin                 . ',' .
                'shipping='               . $before->shipping               . '>' . $after->shipping               . ',' .
                'discount='               . $before->discount               . '>' . $after->discount               . ',' .
                'addition='               . $before->addition               . '>' . $after->addition               . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Business Erase.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function providerBusinessErase(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . 'Negociação de ' . $data['config']['title'] . '{' .
                'provider_id='            . $after->provider_id            . ',' .
                'multiplier_type='        . $after->multiplier_type        . ',' .
                'multiplier_quantity='    . $after->multiplier_quantity    . ',' .
                'multiplier_value='       . $after->multiplier_value       . ',' .
                'multiplier_ipi='         . $after->multiplier_ipi         . ',' .
                'multiplier_ipi_aliquot=' . $after->multiplier_ipi_aliquot . ',' .
                'margin='                 . $after->margin                 . ',' .
                'shipping='               . $after->shipping               . ',' .
                'discount='               . $after->discount               . ',' .
                'addition='               . $after->addition               . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria ProviderBusinessDefault Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function providerBusinessDefaultEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . 'Negociação padrão de ' . $data['config']['title'] . ' {' .
                'multiplier_quantity='    . $before->multiplier_quantity    . '>' . $after->multiplier_quantity    . ',' .
                'multiplier_value='       . $before->multiplier_value       . '>' . $after->multiplier_value       . ',' .
                'multiplier_ipi='         . $before->multiplier_ipi         . '>' . $after->multiplier_ipi         . ',' .
                'multiplier_ipi_aliquot=' . $before->multiplier_ipi_aliquot . '>' . $after->multiplier_ipi_aliquot . ',' .
                'margin='                 . $before->margin                 . '>' . $after->margin                 . ',' .
                'shipping='               . $before->shipping               . '>' . $after->shipping               . ',' .
                'discount='               . $before->discount               . '>' . $after->discount               . ',' .
                'addition='               . $before->addition               . '>' . $after->addition               . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Productgroup Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function productgroupAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='     . $after->id     . ',' .
                'code='   . $after->code   . ',' .
                'origin=' . $after->origin . ',' .
                'name='   . $after->name   . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Productgroup Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function productgroupEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . $data['config']['title'] . ' {' .
                'id='     . $before->id     . '>' . $after->id     . ',' .
                'code='   . $before->code   . '>' . $after->code   . ',' .
                'origin=' . $before->origin . '>' . $after->origin . ',' .
                'name='   . $before->name   . '>' . $after->name   . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Productgroup Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function productgroupErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='     . $data['validatedData']['productgroup_id'] . ',' .
                'code='   . $data['validatedData']['code']            . ',' .
                'origin=' . $data['validatedData']['origin']          . ',' .
                'name='   . $data['validatedData']['name']            . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Productgroup Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function productgroupGenerate(array $data) : bool {
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
     * Auditoria Productgroup Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function productgroupMail(array $data) : bool {
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
     * Auditoria Invoice Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function invoiceAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='            . $after->id            . ',' .
                'provider_id='   . $after->provider_id   . ',' .
                'provider_name=' . $after->provider_name . ',' .
                'company_id='    . $after->company_id    . ',' .
                'company_name='  . $after->company_name  . ',' .
                'key='           . $after->key           . ',' .
                'number='        . $after->number        . ',' .
                'range='         . $after->range         . ',' .
                'total='         . $after->total         . ',' .
                'issue='         . $after->issue         . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Invoice Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='            . $data['validatedData']['invoice_id']    . ',' .
                'provider_id='   . $data['validatedData']['provider_id']   . ',' .
                'provider_name=' . $data['validatedData']['provider_name'] . ',' .
                'company_id='    . $data['validatedData']['company_id']    . ',' .
                'company_name='  . $data['validatedData']['company_name']  . ',' .
                'key='           . $data['validatedData']['key']           . ',' .
                'number='        . $data['validatedData']['number']        . ',' .
                'range='         . $data['validatedData']['range']         . ',' .
                'total='         . $data['validatedData']['total']         . ',' .
                'issue='         . $data['validatedData']['issue']         . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Invoice Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceGenerate(array $data) : bool {
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
     * Auditoria Invoice Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceMail(array $data) : bool {
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
     * Auditoria eFisco Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function invoiceEfiscoAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . 'eFisco de ' . $data['config']['title'] . '{' .
                'id='              . $after->id              . ',' .
                'invoice_id='      . $after->invoice_id      . ',' .
                'productgroup_id=' . $after->productgroup_id . ',' .
                'icms='            . $after->icms            . ',' .
                'value='           . $after->value           . ',' .
                'value_invoice='   . $after->value_invoice   . ',' .
                'value_final='     . $after->value_final     . ',' .
                'ipi_invoice='     . $after->ipi_invoice     . ',' .
                'ipi_final='       . $after->ipi_final       . ',' .
                'index='           . $after->index           . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria eFisco Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceEfiscoErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . 'eFisco de ' . $data['config']['title'] . '{' .
                'id='              . $data['validatedData']['invoiceefisco_id']       . ',' .
                'invoice_id='      . $data['validatedData']['invoice_id']             . ',' .
                'productgroup_id=' . $data['validatedData']['efisco_productgroup_id'] . ',' .
                'icms='            . $data['validatedData']['efisco_icms']            . ',' .
                'value='           . $data['validatedData']['efisco_value']           . ',' .
                'value_invoice='   . $data['validatedData']['efisco_value_invoice']   . ',' .
                'value_final='     . $data['validatedData']['efisco_value_final']     . ',' .
                'ipi_invoice='     . $data['validatedData']['efisco_ipi_invoice']     . ',' .
                'ipi_final='       . $data['validatedData']['efisco_ipi_final']       . ',' .
                'index='           . $data['validatedData']['efisco_index']           . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Invoice Mail Price.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoiceMailPrice(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[enviou e-mail]' . 'Preço' . '{' .
                'folder='    . 'price'                             . ',' .
                'report_id=' . $data['validatedData']['report_id'] . ',' .
                'email='     . $data['validatedData']['mail']      . ',' .
                'subject='   . 'Relatório de Preço'                . ',' .
                'title='     . $data['config']['title']            . ',' .
                'comment='   . $data['validatedData']['comment']   . ',' .
            '}',
        ]);

        return true;
    }

    
    /**
     * Auditoria Invoice Price Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function invoicePriceGenerate(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[calculou]' . 'Preço de Nota Fiscal' . '{' .
                'invoice_id=' . $data['invoice_id'] . ',' .
                'folder='     . 'price'             . ',' .
                'file_name='  . $data['file_name']  . ',' .
            '}',
        ]);

        return true;
    }
    
    /**
     * Auditoria Holiday Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function holidayAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='   . $after->id   . ',' .
                'date=' . $after->date . ',' .
                'week=' . $after->week . ',' .
                'year=' . $after->year . ',' .
                'name=' . $after->name . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Holiday Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function holidayErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='   . $data['validatedData']['holiday_id'] . ',' .
                'date=' . $data['validatedData']['date']       . ',' .
                'week=' . $data['validatedData']['week']       . ',' .
                'year=' . $data['validatedData']['year']       . ',' .
                'name=' . $data['validatedData']['name']       . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Holiday Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function holidayGenerate(array $data) : bool {
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
     * Auditoria Holiday Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function holidayMail(array $data) : bool {
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
     * Auditoria Employee Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function employeeAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='                     . $after->id                     . ',' .
                'company_id='             . $after->company_id             . ',' .
                'company_name='           . $after->company_name           . ',' .
                'pis='                    . $after->pis                    . ',' .
                'name='                   . $after->name                   . ',' .
                'journey_start_week='     . $after->journey_start_week     . ',' .
                'journey_end_week='       . $after->journey_end_week       . ',' .
                'journey_start_saturday=' . $after->journey_start_saturday . ',' .
                'journey_end_saturday='   . $after->journey_end_saturday   . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Edit.
     * @var array $data
     * @var object $before
     * @var object $after
     * 
     * @return bool true
     */
    public static function employeeEdit(array $data, object $before, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[atualizou]' . $data['config']['title'] . ' {' .
                'id='                     . $before->id                     . '>' . $after->id                     . ',' .
                'company_id='             . $before->company_id             . '>' . $after->company_id             . ',' .
                'company_name='           . $before->company_name           . '>' . $after->company_name           . ',' .
                'pis='                    . $before->pis                    . '>' . $after->pis                    . ',' .
                'name='                   . $before->name                   . '>' . $after->name                   . ',' .
                'journey_start_week='     . $before->journey_start_week     . '>' . $after->journey_start_week     . ',' .
                'journey_end_week='       . $before->journey_end_week       . '>' . $after->journey_end_week       . ',' .
                'journey_start_saturday=' . $before->journey_start_saturday . '>' . $after->journey_start_saturday . ',' .
                'journey_end_saturday='   . $before->journey_end_saturday   . '>' . $after->journey_end_saturday   . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='                     . $data['validatedData']['employee_id']            . ',' .
                'company_id='             . $data['validatedData']['company_id']             . ',' .
                'company_name='           . $data['validatedData']['company_name']           . ',' .
                'pis='                    . $data['validatedData']['pis']                    . ',' .
                'name='                   . $data['validatedData']['name']                   . ',' .
                'journey_start_week='     . $data['validatedData']['journey_start_week']     . ',' .
                'journey_end_week='       . $data['validatedData']['journey_end_week']       . ',' .
                'journey_start_saturday=' . $data['validatedData']['journey_start_saturday'] . ',' .
                'journey_end_saturday='   . $data['validatedData']['journey_end_saturday']   . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeGenerate(array $data) : bool {
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
     * Auditoria Employee Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeMail(array $data) : bool {
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
     * Auditoria Employee Vacation Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function employeevacationAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='            . $after->id            . ',' .
                'employee_id='   . $after->employee_id   . ',' .
                'employee_name=' . $after->employee_name . ',' .
                'date_start='    . $after->date_start    . ',' .
                'date_end='      . $after->date_end      . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Vacation Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeevacationErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='            . $data['validatedData']['employeevacation_id'] . ',' .
                'employee_id='   . $data['validatedData']['employee_id']         . ',' .
                'employee_name=' . $data['validatedData']['employee_name']       . ',' .
                'date_start='    . $data['validatedData']['date_start']          . ',' .
                'date_end='      . $data['validatedData']['date_end']            . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Vacation Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeevacationGenerate(array $data) : bool {
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
     * Auditoria Employee Vacation Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeevacationMail(array $data) : bool {
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
     * Auditoria Employee Attest Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function employeeattestAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='            . $after->id            . ',' .
                'employee_id='   . $after->employee_id   . ',' .
                'employee_name=' . $after->employee_name . ',' .
                'date_start='    . $after->date_start    . ',' .
                'date_end='      . $after->date_end      . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Attest Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeattestErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='            . $data['validatedData']['employeeattest_id'] . ',' .
                'employee_id='   . $data['validatedData']['employee_id']         . ',' .
                'employee_name=' . $data['validatedData']['employee_name']       . ',' .
                'date_start='    . $data['validatedData']['date_start']          . ',' .
                'date_end='      . $data['validatedData']['date_end']            . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Attest Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeattestGenerate(array $data) : bool {
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
     * Auditoria Employee Attest Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeattestMail(array $data) : bool {
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
     * Auditoria Employee Absence Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function employeeabsenceAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='            . $after->id            . ',' .
                'employee_id='   . $after->employee_id   . ',' .
                'employee_name=' . $after->employee_name . ',' .
                'date_start='    . $after->date_start    . ',' .
                'date_end='      . $after->date_end      . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Absence Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeabsenceErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='            . $data['validatedData']['employeeabsence_id'] . ',' .
                'employee_id='   . $data['validatedData']['employee_id']         . ',' .
                'employee_name=' . $data['validatedData']['employee_name']       . ',' .
                'date_start='    . $data['validatedData']['date_start']          . ',' .
                'date_end='      . $data['validatedData']['date_end']            . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Absence Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeabsenceGenerate(array $data) : bool {
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
     * Auditoria Employee Absence Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeabsenceMail(array $data) : bool {
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
     * Auditoria Employee Allowance Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function employeeallowanceAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='            . $after->id            . ',' .
                'employee_id='   . $after->employee_id   . ',' .
                'employee_name=' . $after->employee_name . ',' .
                'date='          . $after->date          . ',' .
                'start='         . $after->start         . ',' .
                'end='           . $after->end           . ',' .
                'merged='        . $after->merged        . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Allowance Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeallowanceErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='            . $data['validatedData']['employeeallowance_id'] . ',' .
                'employee_id='   . $data['validatedData']['employee_id']          . ',' .
                'employee_name=' . $data['validatedData']['employee_name']        . ',' .
                'date='          . $data['validatedData']['date']                 . ',' .
                'start='         . $data['validatedData']['start']                . ',' .
                'end='           . $data['validatedData']['end']                  . ',' .
                'merged='        . $data['validatedData']['merged']               . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Allowance Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeallowanceGenerate(array $data) : bool {
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
     * Auditoria Employee Allowance Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeallowanceMail(array $data) : bool {
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
     * Auditoria Employee Easy Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function employeeeasyAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='            . $after->id            . ',' .
                'employee_id='   . $after->employee_id   . ',' .
                'employee_name=' . $after->employee_name . ',' .
                'date='          . $after->date          . ',' .
                'discount='      . $after->discount      . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Easy Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeeasyErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='            . $data['validatedData']['employeeeasy_id'] . ',' .
                'employee_id='   . $data['validatedData']['employee_id']     . ',' .
                'employee_name=' . $data['validatedData']['employee_name']   . ',' .
                'date='          . $data['validatedData']['date']            . ',' .
                'discount='      . $data['validatedData']['discount']        . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Employee Easy Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeeasyGenerate(array $data) : bool {
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
     * Auditoria Employee Easy Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function employeeeasyMail(array $data) : bool {
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
     * Auditoria Clock Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function clockAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='           . $after->id           . ',' .
                'company_id='   . $after->company_id   . ',' .
                'company_name=' . $after->company_name . ',' .
                'start='        . $after->start        . ',' .
                'end='          . $after->end          . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Clock Erase.
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockErase(array $data) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[excluíu]' . $data['config']['title'] . '{' .
                'id='            . $data['validatedData']['clock_id']     . ',' .
                'company_id='    . $data['validatedData']['company_id']   . ',' .
                'company_name= ' . $data['validatedData']['company_name'] . ',' .
                'start='         . $data['validatedData']['start']        . ',' .
                'end='           . $data['validatedData']['end']          . ',' .
            '}',
        ]);

        return true;
    }

    /**
     * Auditoria Clock Generate.
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockGenerate(array $data) : bool {
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
     * Auditoria Clock Mail.
     * @var array $data
     * 
     * @return bool true
     */
    public static function clockMail(array $data) : bool {
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
     * Auditoria Clock Employee Add.
     * @var array $data
     * @var object $after
     * 
     * @return bool true
     */
    public static function clockemployeeAdd(array $data, object $after) : bool {
        Audit::create([
            'user_id'   => auth()->user()->id,
            'user_name' => Str::upper(auth()->user()->name),
            'page_id'   => Page::where('name', $data['config']['name'])->first()->id,
            'page_name' => $data['config']['name'],
            'extensive' => '[cadastrou]' . $data['config']['title'] . '{' .
                'id='                     . $after->id                     . ',' .
                'clock_id='               . $after->clock_id               . ',' .
                'employee_id='            . $after->employee_id            . ',' .
                'journey_start_week='     . $after->journey_start_week     . ',' .
                'journey_end_week='       . $after->journey_end_week       . ',' .
                'journey_start_saturday=' . $after->journey_start_saturday . ',' .
                'journey_end_saturday='   . $after->journey_end_saturday   . ',' .
            '}',
        ]);

        return true;
    }

}
