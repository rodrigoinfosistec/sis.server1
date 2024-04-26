<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'companies';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'cnpj',
        'name',
        'nickname',

        'price',

        'limit_start',
        'limit_end',

        'created_at',
        'updated_at',
    ];

    /**
     * Esconde Nome Fantasia que seja igual à Razão Social.
     * @var int $id
     * 
     * @return string $nickname
     */
    public static function nicknameNoRepeatName($id){
        // Empresa.
        $company = Company::find($id);

        // Verifica se Nome Fantasia é igual à Razão Social.
        if(empty($company->nickname) || $company->nickname == $company->name):
            return null;
        else:
            return $company->nickname;
        endif;
    }

    /**
     * Verifica se [id, name] está vazio.
     * @var <null, int> $id
     * 
     * @return array $company
     */
    public static function validateNull($id) : array {
        $company = [
            'company_id'   => null,
            'company_name' => null,
        ];
    
        if(!empty($id)):
            $company = [
                'company_id'   => $id,
                'company_name' => Company::find($id)->name,
            ];
        endif;

        return $company;
    }

    /**
     * Verifica se Nickname está vazio.
     * @var <null, int> $nickname
     * 
     * @return <string, null> $nick
     */
    public static function nicknameValidateNull($nickname){
        // Inicializa variável.
        $nick = null;
    
        // Verifica se Nickname está vazio.
        if (!empty($nickname)) $nick = Str::upper($nickname);

        return $nick;
    }

    /**
     * Formata CNPJ.
     * @var string $cnpj
     * 
     * @return $cnpj_format
     */
    public static function encodeCnpj(string $cnpj) : string {
        // Formata CNPJ.
        $cnpj_format = 
            $cnpj[0]  . $cnpj[1] . 
            '.'       . 
            $cnpj[2]  . $cnpj[3] . $cnpj[4]  . 
            '.'       . 
            $cnpj[5]  . $cnpj[6] . $cnpj[7]  . 
            '/'       . 
            $cnpj[8]  . $cnpj[9] . $cnpj[10] . $cnpj[11] . 
            '-' . 
            $cnpj[12] . $cnpj[13]
        ;

        return (string)$cnpj_format;
    }

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
        Company::create([
            'cnpj'     => $data['validatedData']['cnpj'],
            'name'     => Str::upper($data['validatedData']['name']),
            'nickname' => Company::nicknameValidateNull($data['validatedData']['nickname']),
            'price'    => $data['validatedData']['price'],
        ]);

        // After.
        $after = Company::where('cnpj', $data['validatedData']['cnpj'])->first();

        // Auditoria.
        Audit::companyAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->name . ' cadastrada com sucesso.';
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
     * Valida cadastro XML.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAddXml(array $data){
        $message = null;

        // Salva o arquivo, caso seja um xml.
        $xmlObject = Report::xmlCompany($data);

        // Verifica se é um arquivo xml.
        if(empty($xmlObject)):
            $message = 'Arquivo deve ser um xml (NFe).';
        endif;

        // Verifica se a empresa já está cadastrada.
        if(!empty($xmlObject)):
            if(Company::where('cnpj', Company::encodeCnpj($xmlObject->NFe->infNFe->dest->CNPJ))->first()):
                $message = $data['config']['title'] . ' ' . Str::upper($xmlObject->NFe->infNFe->dest->xNome) . ' já está cadastrada.';
            endif;
        endif;

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        return $xmlObject;
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
        $txtArray = Report::txtCompany($data);

        // Verifica se é um arquivo txt.
        if(empty($txtArray)):
            $message = 'Arquivo deve ser um txt de empregador (registro de ponto).';
        endif;

        // Verifica se a empresa já está cadastrada.
        if(!empty($txtArray)):
            if(Company::where('cnpj', Company::encodeCnpj($txtArray['cnpj']))->first()):
                $message = $data['config']['title'] . ' ' . Str::upper($txtArray['name']) . ' já está cadastrada.';

                // Exclui o arquivo.
                unlink($txtArray['path']);
            endif;
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
        $before = Company::find($data['validatedData']['company_id']);

        // Atualiza.
        Company::find($data['validatedData']['company_id'])->update([
            'cnpj'     => $data['validatedData']['cnpj'],
            'name'     => Str::upper($data['validatedData']['name']),
            'nickname' => Company::nicknameValidateNull($data['validatedData']['nickname']),
            'price'    => $data['validatedData']['price'],
        ]);

        // After.
        $after = Company::find($data['validatedData']['company_id']);

        // Auditoria.
        Audit::companyEdit($data, $before, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $after->name . ' atualizada com sucesso.';
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
        // Atualiza o nome da Empresa nas Notas Fiscais com ela relacionadas.
        Invoice::where(['company_id' => $data['validatedData']['company_id']])->update([
            'company_name' => Str::upper($data['validatedData']['name']),
        ]);

        // Atualiza o nome da Empresa nos Usuários com ela relacionadas.
        User::where(['company_id' => $data['validatedData']['company_id']])->update([
            'company_name' => Str::upper($data['validatedData']['name']),
        ]);

        // Atualiza o nome da Empresa nos Funcionários com ela relacionadas.
        Employee::where(['company_id' => $data['validatedData']['company_id']])->update([
            'company_name' => Str::upper($data['validatedData']['name']),
        ]);

        // Atualiza o nome da Empresa nos Pontos com ela relacionadas.
        Clock::where(['company_id' => $data['validatedData']['company_id']])->update([
            'company_name' => Str::upper($data['validatedData']['name']),
        ]);

        return true;
    }

    /**
     * Valida atualização do Limite.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditLimit(array $data) : bool {
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
    public static function editLimit(array $data) : bool {
        // Before.
        $before = Company::find($data['validatedData']['company_id']);

        // Atualiza.
        Company::find($data['validatedData']['company_id'])->update([
            'limit_start' => General::timeToMinuts($data['validatedData']['limit_start']),
            'limit_end'   => General::timeToMinuts($data['validatedData']['limit_end']),
        ]);

        // After.
        $after = Company::find($data['validatedData']['company_id']);

        // Auditoria.
        Audit::companyEditLimit($data, $before, $after);

        // Mensagem.
        $message = 'Limites de Ponto da ' . $data['config']['title'] . ' ' .  $after->name . ' atualizados com sucesso.';
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
    public static function dependencyEditLimit(array $data) : bool {
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

        // Verifica se alguma Nota Fiscal já utiliza a empresa.
        if(Invoice::where(['company_id' => $data['validatedData']['company_id']])->exists()):
            $message = $data['config']['title'] . ' ' . Company::find($data['validatedData']['company_id'])->name . ' utilizada em nota fiscal ' . Invoice::where(['company_id' => $data['validatedData']['company_id']])->first()->number . '.';
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
        Company::find($data['validatedData']['company_id'])->delete();

        // Auditoria.
        Audit::companyErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['name'] . ' excluída com sucesso.';
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
        if($list = Company::where([
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
        Report::companyGenerate($data);

        // Auditoria.
        Audit::companyGenerate($data);

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
        Email::companyMail($data);

        // Auditoria.
        Audit::companyMail($data);

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
