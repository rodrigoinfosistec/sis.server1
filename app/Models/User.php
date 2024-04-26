<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Nome da tabela.
     */
    protected $table = 'users';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'company_id',
        'company_name',

        'usergroup_id',
        'usergroup_name',

        'employee_id',

        'name',
        'email',

        'password',

        'status',

        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Relaciona Models.
     */
    public function usergroup(){return $this->belongsTo(Usergroup::class);}
    public function employee(){return $this->belongsTo(Employee::class);}

    /**
     * Força o cadastro do Usuário "MASTER" com Grupo de Usuário "DEVELOPMENT".
     * 
     * @return bool true
     */
    public static function insertMaster() : bool {
        // Verifica se o Usuário "MASTER" não está cadastrado.
        if(User::where('name', 'MASTER')->doesntExist()):
            // Verifica se o Grupo de Usuário "DEVELOPMENT" não está cadastrado.
            if(Usergroup::where('name', 'DEVELOPMENT')->doesntExist()):
                // Cadastra o Grupo de Usuário "DEVELOPMENT".
                Usergroup::insertDevelopment();
            endif;

            // Cadastra o Usuário "MASTER" com o Grupo de Usuário "DEVELOPMENT".
            User::create([
                'usergroup_id'   => Usergroup::where('name', 'DEVELOPMENT')->first()->id,
                'usergroup_name' => 'DEVELOPMENT',
                'name'           => 'MASTER',
                'email'          => 'rodrigo.infosistec@gmail.com',
                'password'       => Hash::make(Config::getUserMaster()['hashMaster']),
            ]);
        endif;

        return true;
    }

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Verifica se Senha e Confirma são iguais.
        if($data['validatedData']['password'] != $data['validatedData']['confirm']):
            $message = 'Senhas divergentes.';
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
        User::create([
            'company_id'     => $data['validatedData']['company_id'],
            'company_name'   => Company::find($data['validatedData']['company_id'])->name,
            'usergroup_id'   => $data['validatedData']['usergroup_id'],
            'usergroup_name' => Usergroup::find($data['validatedData']['usergroup_id'])->name,
            'name'           => Str::upper($data['validatedData']['name']),
            'email'          => $data['validatedData']['email'],
            'password'       => Hash::make($data['validatedData']['password']),
        ]);

        // After.
        $after = User::where('email', $data['validatedData']['email'])->first();

        // Auditoria.
        Audit::userAdd($data, $after);

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
        $before = User::find($data['validatedData']['user_id']);

        // Atualiza.
        User::find($data['validatedData']['user_id'])->update([
            'company_id'     => $data['validatedData']['company_id'],
            'company_name'   => Company::find($data['validatedData']['company_id'])->name,
            'usergroup_id'   => $data['validatedData']['usergroup_id'],
            'usergroup_name' => Usergroup::find($data['validatedData']['usergroup_id'])->name,
            'employee_id'    => $data['validatedData']['employee_id'],
            'name'           => Str::upper($data['validatedData']['name']),
            'email'          => $data['validatedData']['email'],
            'status'         => $data['validatedData']['status'],
        ]);

        // After.
        $after = User::find($data['validatedData']['user_id']);

        // Auditoria.
        Audit::userEdit($data, $before, $after);

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
        // Atualiza user_name em Auditoria.
        Audit::where(['user_id' => $data['validatedData']['user_id']])->update([
            'user_name' => Str::upper($data['validatedData']['name']),
        ]);

        // Exclui sessão de Usuário Inativo.
        if(!$data['validatedData']['status']):
            Session::where('user_id', $data['validatedData']['user_id'])->delete();
        endif;

        return true;
    }

    /**
     * Valida atualização de senha.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditPassword(array $data) : bool {
        $message = null;

        // Usuário.
        $user = User::find($data['validatedData']['user_id']);

        // Verifica se senha atual está Correta.
        if(!Hash::check($data['validatedData']['password_old'], $data['validatedData']['confirm_old'])):
            $message = 'Senha atual incorreta.';
        endif;

        // Verifica se Senha e Confirma são iguais.
        if($data['validatedData']['password'] != $data['validatedData']['confirm']):
            $message = 'Senhas divergentes.';
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
     * Atualiza senha.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editPassword(array $data) : bool {
        // Atualiza.
        User::find($data['validatedData']['user_id'])->update([
            'password' => Hash::make($data['validatedData']['password']),
        ]);

        // Auditoria.
        Audit::userEditPassword($data);

        // Mensagem.
        $message = 'Senha do ' . $data['config']['title'] . ' ' .  User::find($data['validatedData']['user_id'])->name . ' atualizada com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências de atualização de senha.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEditPassword(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Valida Reset da senha.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditReset(array $data) : bool {
        $message = null;

        // Usuário.
        $user = User::find($data['validatedData']['user_id']);

        // Verifica se senha atual está Correta.
        if(!Hash::check(auth()->user()->password, $data['validatedData']['password_user'])):
            $message = 'Senha do usuário logado está incorreta.';
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
     * Reseta senha.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editReset(array $data) : bool {
        // Atualiza.
        User::find($data['validatedData']['user_id'])->update([
            'password' => Hash::make('123'),
        ]);

        // Auditoria.
        Audit::userEditReset($data);

        // Mensagem.
        $message = 'Senha do ' . $data['config']['title'] . ' ' .  User::find($data['validatedData']['user_id'])->name . ' resetada com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }

    /**
     * Executa dependências do reset da senha.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyEditReset(array $data) : bool {
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

        // Auditoria.
        if(Audit::where('user_id', $data['validatedData']['user_id'])->exists()):
            $message = $data['config']['title'] . ' ' . User::find($data['validatedData']['user_id'])->name . ' possui movimentação em auditorias.';
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
        // Exclui sessão do Usuário.
        Session::where('user_id', $data['validatedData']['user_id'])->delete();

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
        User::find($data['validatedData']['user_id'])->delete();

        // Auditoria.
        Audit::userErase($data);

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
        if($list = User::where([
            [$data['filter'], 'like', '%'. $data['search'] . '%'],
            ['name', '!=', 'MASTER'],
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

        // Gerar o PDF..
        Report::userGenerate($data);

        // Auditoria.
        Audit::userGenerate($data);

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
        Email::userMail($data);

        // Auditoria.
        Audit::userMail($data);

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
