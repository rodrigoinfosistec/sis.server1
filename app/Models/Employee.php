<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employees';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'pis',
        'name',

        'journey_start_week',
        'journey_end_week',
        'journey_start_saturday',
        'journey_end_saturday',

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
        Employee::create([
            'pis'                    => $data['validatedData']['pis'],
            'name'                   => Str::upper($data['validatedData']['name']),
            'journey_start_week'     => $data['validatedData']['journey_start_week'],
            'journey_end_week'       => $data['validatedData']['journey_end_week'],
            'journey_start_saturday' => $data['validatedData']['journey_start_saturday'],
            'journey_end_saturday'   => $data['validatedData']['journey_end_saturday'],
        ]);

        // After.
        $after = Employee::where('pis', $data['validatedData']['pis'])->first();

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
        $xmlObject = Report::xmlEmployee($data);

        // Verifica se é um arquivo xml.
        if(empty($xmlObject)):
            $message = 'Arquivo deve ser um xml (NFe).';
        endif;

        // Verifica se a empresa já está cadastrada.
        if(!empty($xmlObject)):
            if(Employee::where('pis', Employee::encodeCnpj($xmlObject->NFe->infNFe->dest->CNPJ))->first()):
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
        $txtArray = Report::txtEmployee($data);

        // Verifica se é um arquivo txt.
        if(empty($txtArray)):
            $message = 'Arquivo deve ser um txt de empregador (registro de ponto).';
        endif;

        // Verifica se a empresa já está cadastrada.
        if(!empty($txtArray)):
            if(Employee::where('pis', Employee::encodeCnpj($txtArray['pis']))->first()):
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
        $before = Employee::find($data['validatedData']['company_id']);

        // Atualiza.
        Employee::find($data['validatedData']['company_id'])->update([
            'pis'     => $data['validatedData']['pis'],
            'name'     => Str::upper($data['validatedData']['name']),
            'nickname' => Employee::nicknameValidateNull($data['validatedData']['nickname']),
            'price'    => $data['validatedData']['price'],
        ]);

        // After.
        $after = Employee::find($data['validatedData']['company_id']);

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
            $message = $data['config']['title'] . ' ' . Employee::find($data['validatedData']['company_id'])->name . ' utilizada em nota fiscal ' . Invoice::where(['company_id' => $data['validatedData']['company_id']])->first()->number . '.';
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
        Employee::find($data['validatedData']['company_id'])->delete();

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
        if($list = Employee::where([
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
