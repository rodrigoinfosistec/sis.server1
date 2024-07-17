<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Depositinput extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositinputs';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'deposit_name',
        'deposit_id',

        'provider_id',
        'provider_name',

        'company_id',
        'company_name',

        'user_id',
        'user_name',

        'key',
        'number',
        'range',
        'total',

        'issue',

        'observation',

        'funded',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function deposit(){return $this->belongsTo(Deposit::class);}
    public function provider(){return $this->belongsTo(Provider::class);}
    public function company(){return $this->belongsTo(Company::class);}
    public function user(){return $this->belongsTo(User::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return <object, bool>
     */
    public static function validateAddXml(array $data){
        $message = null;

        // Salva arquivo, caso seja um XML.
        $xmlObject = Report::xmlDepositinput($data);

        // Verifica se usuário tem Permissão à Empresa Própria.
        

        // Verifica se é um arquivo XML.
        if (empty($xmlObject)) $message = 'Arquivo deve ser um xml (NFe).';

        // Estende $data
        if (!empty($xmlObject)) $data['validatedData']['chNFe'] = $xmlObject->protNFe->infProt->chNFe;

        // Verifica se a NFe já está cadastrada.
        if(!empty($xmlObject)):
            if(Depositinput::where('key', Invoice::encodeKey((string)$xmlObject->protNFe->infProt->chNFe))->exists()):
                $message = $data['config']['title'] . ' ' . $xmlObject->NFe->infNFe->ide->nNF . ' do Fornecedor ' . $xmlObject->NFe->infNFe->emit->xNome . ' já está cadastrada.';
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
                $dataCompany['validatedData']['price']    = '1';
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

        return $valid;
    }

    /**
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function addXml(array $data) : bool {
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
    public static function dependencyAddXml(array $data) : bool {
        // Cadastra itens CSV da NFe.
        Invoicecsv::add($data);

        // Cadastra itens da NFe.
        Invoiceitem::add($data);

        return true;
    }
}
