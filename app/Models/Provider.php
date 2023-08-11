<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'providers';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'cnpj',
        'name',
        'nickname',

        'created_at',
        'updated_at',
    ];

    /**
     * Evita Nome Fantasia igual à Razão Social.
     * @var int $id
     * 
     * @return string $nickname
     */
    public static function nicknameNoRepeatName($id){
        $provider = Provider::find($id);

        if(empty($provider->nickname) || $provider->nickname == $provider->name):
            return null;
        else:
            return $provider->nickname;
        endif;
    }

    /**
     * Verifica se está vazio.
     * @var <null, int> $id
     * 
     * @return array $provider
     */
    public static function validateNull($id) : array {
        // Inicializa variáveis.
        $provider = [
            'provider_id'   => null,
            'provider_name' => null,
        ];
    
        // Verifica se está vazio.
        if(!empty($id)):
            $provider = [
                'provider_id'   => $id,
                'provider_name' => Provider::find($id)->name,
            ];
        endif;

        return $provider;
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

        return $cnpj_format;
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
        Provider::create([
            'cnpj'     => $data['validatedData']['cnpj'],
            'name'     => Str::upper($data['validatedData']['name']),
            'nickname' => Provider::nicknameValidateNull($data['validatedData']['nickname']),
        ]);

        // After.
        $after = Provider::where('cnpj', $data['validatedData']['cnpj'])->first();

        // Auditoria.
        Audit::providerAdd($data, $after);

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
        // Negociação com o Fornecedor.
        Providerbusiness::add($data);

        return true;
    }

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAddXml(array $data){
        $message = null;

        // Salva o arquivo, caso exista.
        $xmlObject = Report::xmlProvider($data);

        // Verifica se é um arquivo xml.
        if(empty($xmlObject)):
            $message = 'Arquivo deve ser um xml (NFe).';
        endif;

        // Verifica se o fornecedor já está cadastrado.
        if(!empty($xmlObject)):
            if(Provider::where('cnpj', Provider::encodeCnpj((string)$xmlObject->NFe->infNFe->emit->CNPJ))->exists()):
                $message = $data['config']['title'] . ' ' . $xmlObject->NFe->infNFe->emit->xNome . ' já está cadastrado.';
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
        $before = Provider::find($data['validatedData']['provider_id']);

        // Atualiza.
        Provider::find($data['validatedData']['provider_id'])->update([
            'cnpj'     => $data['validatedData']['cnpj'],
            'name'     => Str::upper($data['validatedData']['name']),
            'nickname' => Provider::nicknameValidateNull($data['validatedData']['nickname']),
        ]);

        // After.
        $after = Provider::find($data['validatedData']['provider_id']);

        // Auditoria.
        Audit::providerEdit($data, $before, $after);

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
        // Nota Fiscal.
        Invoice::where(['provider_id' => $data['validatedData']['provider_id']])->update([
            'provider_name' => Str::upper($data['validatedData']['name']),
        ]);

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

        // Nota Fiscal.
        $invoice = Invoice::where(['provider_id' => $data['validatedData']['provider_id']])->get();
        if($invoice->count() > 0):
            $message = $data['config']['title'] . ' ' . Provider::find($data['validatedData']['provider_id'])->name . ' utilizada em nota fiscal ' . Invoice::where(['provider_id' => $data['validatedData']['provider_id']])->first()->number . '.';
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
        // Negociação com o Fornecedor.
        Providerbusiness::erase($data);

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
        Provider::find($data['validatedData']['provider_id'])->delete();

        // Auditoria.
        Audit::providerErase($data);

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
        if($list = Provider::where([
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
        Report::providerGenerate($data);

        // Auditoria.
        Audit::providerGenerate($data);

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
        Email::providerMail($data);

        // Auditoria.
        Audit::providerMail($data);

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
