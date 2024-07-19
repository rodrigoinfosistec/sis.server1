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

        // Verifica se é um arquivo XML.
        if(!empty($xmlObject)):
            // Verifica se a NFe não está cadastrada.
            if(Depositinput::where('key', Invoice::encodeKey((string)$xmlObject->protNFe->infProt->chNFe))->doesntExist()):
                //Verifica se a Empresa Própria está cadastrada.
                if(Company::where('cnpj', Company::encodeCnpj((string)$xmlObject->NFe->infNFe->dest->CNPJ))->exists()):
                    // Verifica se Usuário tem Permissão na Empresa Própria.
                    if(Company::where('cnpj', Company::encodeCnpj((string)$xmlObject->NFe->infNFe->dest->CNPJ))->first()->id == auth()->user()->company_id):
                         // Estende $data
                        $data['validatedData']['chNFe'] = $xmlObject->protNFe->infProt->chNFe;

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
                    else:
                        $message = 'Usuário não tem permissão na Empresa Própria.';
                    endif;
                else:
                    $message = 'Empresa Própria não cadastrada.';
                endif;
            else:
                $message = 'Nota Fiscal ' . $xmlObject->NFe->infNFe->ide->nNF . ' do Fornecedor ' . $xmlObject->NFe->infNFe->emit->xNome . ' já está cadastrada.';
            endif;
        else:
            $message = 'Arquivo deve ser um xml (NFe).';
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
        $depositinput_id = Depositinput::create([
            'deposit_name' => $data['validatedData']['deposit_name'],
            'deposit_id'   => $data['validatedData']['deposit_id'],
            'provider_id' => $data['validatedData']['provider_id'],
            'provider_name' => $data['validatedData']['provider_name'],
            'company_id' => $data['validatedData']['company_id'],
            'company_name' => $data['validatedData']['company_name'],
            'user_id' => $data['validatedData']['user_id'],
            'user_name' => $data['validatedData']['user_name'],
            'key' => $data['validatedData']['key'],
            'number' => $data['validatedData']['number'],
            'range' => $data['validatedData']['range'],
            'total' => $data['validatedData']['total'],
            'issue' => $data['validatedData']['issue'],
            'observation' => $data['validatedData']['observation'],
        ])->id;

        // After.
        $after = Depositinput::find($depositinput_id);

        // Auditoria.
        Audit::depositinputAddXml($data, $after);

        // Mensagem.
        $message = 'Nota Fiscal ' . $after->number . '  do Forcenedor ' . $after->provider_name . ' cadastrada com sucesso.';
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
        // Percorre todos os itens da Nota Fiscal.
        foreach($data['validatedData']['xmlObject']->NFe->infNFe->det as $item):
            // Verifica se item não está cadastrado no Fornecedor.
            if(Provideritem::where(['code' => $item->code, 'provider_id' => $data['validatedData']['provider_id']])->doesntExist()):
                // Cadastra o item no Fornecedor.
                $provideritem_id = Provideritem::create([
                'name' => Str::upper($item->prod->xProd),
                'code' => $item->prod->cProd,
                'ean' => !empty($item->prod->cEANTrib) ? $item->prod->cEANTrib : null,
                'ncm' => $item->prod->NCM,
                'cfop' => $item->prod->CFOP,
                'cest' => !empty($item->prod->CEST) ? $item->prod->CEST : null,
                'measure' => Str::upper($item->prod->uCom),
                'provider_name'=> $data['validatedData']['provider_name'],
                'provider_id'=> $data['validatedData']['provider_id'],
                ])->id;
            else:
                // Retorna id do item no Fornecedor.
                $provideritem_id = Provideritem::where(['code' => $item->code, 'provider_id' => $data['validatedData']['provider_id']])->first();
            endif;

            
        endforeach;

        return true;
    }
}
