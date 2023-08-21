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
     * Alertas de etapas da Nota Fiscal.
     * @var int $invoice_id
     * 
     * @return array $alerts
     */
    public static function alerts(int $invoice_id) : array {
        // Inicializa o array com a quantidade de alertas.
        $alerts['amount']  = 0;

        // Inicializa o array com as mensagens de alertas.
        $alerts['message'] = [];

        // Verifica se as quantidades de itens da Nota Fiscal e itens CSV estão iguais.
        if(Invoiceitem::where('invoice_id', $invoice_id)->get()->count() != Invoicecsv::where('invoice_id', $invoice_id)->get()->count()):
            // Incrementa a quantidade de alertas.
            $alerts['amount']++;

            // Incrementa a mensagem de alertas.
            $alerts['message'][] = "Quantidade diferentes de itens da Nota Fiscal e itens CSV.";
        endif;

        // Verifica se existem eFiscos vinculados à nota.
        if(Invoiceefisco::where('invoice_id', $invoice_id)->doesntExist()):
            // Incrementa a quantidade de alertas.
            $alerts['amount']++;

            // Incrementa a mensagem de alertas.
            $alerts['message'][] = "Nenhum eFisco vinculado com a Nota Fiscal.";
        endif;

        // Verifica se todos os itens foram atualizados.
        if(Invoiceitem::where(['invoice_id' => $invoice_id, 'updated' => false])->exists()):
            // Incrementa a quantidade de alertas.
            $alerts['amount']++;

            // Incrementa a mensagem de alertas.
            $alerts['message'][] = "Existem itens na Nota Fiscal sem Grupo de Produto ou sem CSV vinculados.";
        endif;

        // Verifica se existe eFisco Vinculado com a Nota, porém não vinculado a nenhum item.
        // Verifica se todos os itens da Nota Fiscal estão atualizados.
        if(Invoiceitem::where(['invoice_id' => $invoice_id, 'updated' => false])->exists()):
            // Verifica se existe eFisco cadastrado na Nota Fiscal.
            if(Invoiceefisco::where('invoice_id', $invoice_id)->exists()):
                // Inicializa array.
                $key = [];

                // Percorre todos os itens da Nota Fiscal.
                foreach(Invoiceitem::where('invoice_id', $invoice_id)->get() as $item):
                    // Monta array com todos eFiscos diferentes vinculados aos itens da Nota Fiscal, sem repetir.
                    $key[$item->productgroup_id] = '';
                endforeach;

                // Verifica se a quantidade de eFiscos utilizados pelos itens é igual a quantidade de eFiscos vinculados à Nota Fiscal.
                if(count($key) != Invoiceefisco::where('invoice_id', $invoice_id)->get()->count()):
                    // Incrementa a quantidade de alertas.
                    $alerts['amount']++;

                    // Incrementa a mensagem de alertas.
                    $alerts['message'][] = 'Existe eFisco(s) vinculado(s) à Nota Fiscal, que não está(ão) vinculado(s) a nenhum item.';
                endif;
            endif;
        endif;

        // Verifica se existe Item CSV Vinculado com a Nota, porém não vinculado a nenhum item.
        // Verifica se todos os itens da Nota Fiscal estão atualizados.
        if(Invoiceitem::updatedAll($invoice_id)):
            // Verifica se existe itens CSV cadastrados na Nota Fiscal.
            if(Invoicecsv::where('invoice_id', $invoice_id)->exists()):
                // Inicializa array.
                $key = [];

                // Percorre todos os itens da Nota Fiscal.
                foreach(Invoiceitem::where('invoice_id', $invoice_id)->get() as $item):
                    // Monta array com todos eFiscos diferentes vinculados aos itens da Nota Fiscal, sem repetir.
                    $key[$item->invoicecsv_id] = '';
                endforeach;

                // Verifica se a quantidade de itens CSV utilizados pelos itens é igual a quantidade de itens CSV vinculados à Nota Fiscal.
                if(count($key) != Invoicecsv::where('invoice_id', $invoice_id)->get()->count()):
                    // Incrementa a quantidade de alertas.
                    $alerts['amount']++;

                    // Incrementa a mensagem de alertas.
                    $alerts['message'][] = 'Existe Item(s) CSV vinculado(s) à Nota Fiscal, que não está(ão) vinculado(s) a nenhum item.';
                endif;
            endif;
        endif;

        // Verifica se todos os eFiscos vinculados a itens estão cadastrados na Nota Fiscal.
        // Verifica se todos os itens da Nota Fiscal estão atualizados.
        if(Invoiceitem::updatedAll($invoice_id)):
            // Percorre todos os itens da Nota Fiscal.
            foreach(Invoiceitem::where('invoice_id', $invoice_id)->get() as $key => $item):
                // verifica em cada item se o Grupo de Produto vinculado a ele está também vinculado à Nota Fiscal.
                if(Invoiceefisco::where(['invoice_id' => $invoice_id, 'productgroup_id' => $item->productgroup_id])->doesntExist()):
                    // Incrementa a quantidade de alertas.
                    $alerts['amount']++;

                    // Incrementa a mensagem de alertas.
                    $alerts['message'][] = 'Existe Item vinculado a Grupo de Produto, que não está cadastrado em eFiscos da Nota Fiscal.';
                endif;
            endforeach;
        endif;

        return $alerts;
    }

    /**
     * Formata Chave NFe.
     * @var string $key
     * 
     * @return string $key_format
     */
    public static function encodeKey(string $key) : string {
        // Formata Chave NFe.
        $key_format = 
            $key[0]  . $key[1]  . $key[2]  . $key[3] . 
            ' ' .
            $key[4]  . $key[5]  . $key[6]  . $key[7] .
            ' ' .
            $key[8]  . $key[9]  . $key[10] . $key[11] .
            ' ' .
            $key[12] . $key[13] . $key[14] . $key[15] .
            ' ' .
            $key[16] . $key[17] . $key[18] . $key[19] .
            ' ' .
            $key[20] . $key[21] . $key[22] . $key[23] .
            ' ' .
            $key[24] . $key[25] . $key[26] . $key[27] .
            ' ' .
            $key[28] . $key[29] . $key[30] . $key[31] .
            ' ' .
            $key[32] . $key[33] . $key[34] . $key[35] .
            ' ' .
            $key[36] . $key[37] . $key[38] . $key[39] .
            ' ' .
            $key[40] . $key[41] . $key[42] . $key[43]
        ;

        return (string)$key_format;
    }

    /**
     * Formata Número NFe.
     * @var string $number
     * 
     * @return string $number_format
     */
    public static function encodeNumber(string $number) : string {
        // Força o tamanho do número com 9 dígitos.
        $number = str_pad($number, 9, 0, STR_PAD_LEFT);

        // Formata Número NFe.
        $number_format = 
            $number[0] . $number[1] . $number[2] .
            '.' .
            $number[3] . $number[4] . $number[5] .
            '.' .
            $number[6] . $number[7] . $number[8]
        ;

        return (string)$number_format;
    }

    /**
     * Formata Série NFe.
     * @var string $range
     * 
     * @return string $range_format
     */
    public static function encodeRange(string $range) : string {
        // Formata Série NFe com 3 dígitos.
        $range_format = str_pad($range, 3, 0, STR_PAD_LEFT);

        return (string)$range_format;
    }

    /**
     * Formata Data NFe em formato americano.
     * @var string $issue
     * 
     * @return string $issue_format
     */
    public static function encodeIssue(string $issue) : string {
        // Retira o 'T' vindo da data no XML.
        $issue = explode('T', $issue);

        // Retira o '-' vindo da data no XML.
        $issue[1] = explode('-', $issue[1]);

        // Formata Data NFe.
        $issue_format = $issue[0] . ' ' . $issue[1][0];

        return (string)$issue_format;
    }

    /**
     * Formata Data NFe em formato brasileiro.
     * @var string $issue_format
     * 
     * @return string $issue
     */
    public static function decodeIssue(string $issue_format) : string {
        // Formata Data NFe em formato brasileiro.
        $issue = date_format(date_create($issue_format), 'd/m/y H:i');

        return (string)$issue;
    }

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
