<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Invoice extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'invoices';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'provider_id',
        'provider_name',

        'company_id',
        'company_name',

        'key',
        'number',
        'range',
        'total',

        'issue',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function provider(){return $this->belongsTo(Provider::class);}
    public function company(){return $this->belongsTo(Company::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAdd(array $data){
        $message = null;

        // Salva arquivo, caso seja um XML.
        $xmlObject = Report::xmlInvoice($data);

        // Verifica se é um arquivo XML.
        if (empty($xmlObject)) $message = 'Arquivo deve ser um xml (NFe).';

        // Estende $data
        if (!empty($xmlObject)) $data['validatedData']['chNFe'] = $xmlObject->protNFe->infProt->chNFe;

        // Salva o arquivo, caso seja um CSV.
        if (!empty($xmlObject)) $CsvArray = Report::csvInvoice($data);

        // Verifica se é um arquivo CSV.
        if(!empty($xmlObject)):
            if (empty($CsvArray)) $message = 'Arquivo deve ser um csv de produtos.';
        endif;

        // Verifica se a NFe já está cadastrada.
        if(!empty($xmlObject) && !empty($CsvArray)):
            if(Invoice::where('key', Invoice::encodeKey((string)$xmlObject->protNFe->infProt->chNFe))->exists()):
                $message = $data['config']['title'] . ' ' . $xmlObject->NFe->infNFe->ide->nNF . ' do Fornecedor ' . $xmlObject->NFe->infNFe->emit->xNome . ' já está cadastrada.';
            endif;
        endif;

        // Verifica se quantidade de itens (XML x CSV) são iguais.
        if(!empty($xmlObject) && !empty($CsvArray)):
            if(count($xmlObject->NFe->infNFe->det) != count($CsvArray)):
                $message = $data['config']['title'] . ' e CSV possuem quantidade de itens diferente.';
            endif;
        endif;

        // Cadastra Fornecedor, caso não exista.
        if(!empty($xmlObject) && !empty($CsvArray)):
            // Verfica se o Fornecedor não está cadastrado.
            if(Provider::where('cnpj', Provider::encodeCnpj((string)$xmlObject->NFe->infNFe->emit->CNPJ))->doesntExist()):
                // Monta array.
                $dataProvider['validatedData']['cnpj']     = Provider::encodeCnpj((string)$xmlObject->NFe->infNFe->emit->CNPJ);
                $dataProvider['validatedData']['name']     = (string)$xmlObject->NFe->infNFe->emit->xNome;
                $dataProvider['validatedData']['nickname'] = (string)$xmlObject->NFe->infNFe->emit->xFant;
                $dataProvider['config']                    = $data['config'];

                // Cadastra Fornecedor.
                Provider::add($dataProvider);

                // Cadastra a Negociação com o Fornecedor.
                Providerbusiness::add($dataProvider);
            endif;
        endif;

        // Cadastra Empresa, caso não exista.
        if(!empty($xmlObject) && !empty($CsvArray)):
            // Verfica a Empresa não está cadastrada.
            if(Company::where('cnpj', Company::encodeCnpj((string)$xmlObject->NFe->infNFe->dest->CNPJ))->doesntExist()):
                // Monta array.
                $dataCompany['validatedData']['cnpj']     = Company::encodeCnpj((string)$xmlObject->NFe->infNFe->dest->CNPJ);
                $dataCompany['validatedData']['name']     = (string)$xmlObject->NFe->infNFe->dest->xNome;
                $dataCompany['validatedData']['nickname'] = (string)$xmlObject->NFe->infNFe->dest->xFant;
                $dataCompany['config']                    = $data['config'];

                // Cadastra a Empresa.
                Company::add($dataCompany);
            endif;
        endif;

        // Desvio.
        if(!empty($message)):
            session()->flash('message', $message );
            session()->flash('color', 'danger');

            return false;
        endif;

        // Atribui retorno, caso aprovado nas validações anteriores.
        $valid['xmlObject'] = $xmlObject;
        $valid['CsvArray']  = $CsvArray;

        return $valid;
    }

    /**
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Cadastra.
        Invoice::create([
            'provider_id'   => $data['validatedData']['provider_id'],
            'provider_name' => $data['validatedData']['provider_name'],
            'company_id'    => $data['validatedData']['company_id'],
            'company_name'  => $data['validatedData']['company_name'],
            'key'           => $data['validatedData']['key'],
            'number'        => $data['validatedData']['number'],
            'range'         => $data['validatedData']['range'],
            'total'         => $data['validatedData']['total'],
            'issue'         => $data['validatedData']['issue'],
        ]);

        // After.
        $after = Invoice::where('key', $data['validatedData']['key'])->first();

        // Auditoria.
        Audit::invoiceAdd($data, $after);

        // Mensagem.
        $message = $data['config']['title'] . ' ' . $after->number . '  do Forcenedor ' . $after->provider_name . ' cadastrada com sucesso.';
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
        // Cadastra itens CSV da NFe.
        Invoicecsv::add($data);

        // Cadastra itens da NFe.
        Invoiceitem::add($data);

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
        // Exclui eFiscos da Nota Fiscal.
        Invoiceefisco::where('invoice_id', $data['validatedData']['invoice_id'])->delete();

        // Exclui itens da Nota Fiscal.
        Invoiceitem::where('invoice_id', $data['validatedData']['invoice_id'])->delete();

        // Exclui itens CSV da Nota Fiscal.
        Invoicecsv::where('invoice_id', $data['validatedData']['invoice_id'])->delete();

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
        Invoice::find($data['validatedData']['invoice_id'])->delete();

        // Auditoria.
        Audit::invoiceErase($data);

        // Mensagem.
        $message = $data['config']['title'] . ' ' .  $data['validatedData']['number'] . ' do Fornecedor ' . $data['validatedData']['provider_name'] . ' excluída com sucesso.';
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
        if($list = Invoice::where([
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
        Report::invoiceGenerate($data);

        // Auditoria.
        Audit::invoiceGenerate($data);

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
        Email::invoiceMail($data);

        // Auditoria.
        Audit::invoiceMail($data);

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
