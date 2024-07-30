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
            // Verifica se a NFe não está cadastrada do Depósito.
            if(Depositinput::where(['deposit_id' => $data['validatedData']['deposit_id'], 'key' => Invoice::encodeKey((string)$xmlObject->protNFe->infProt->chNFe)])->doesntExist()):
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
                $message = 'NFe ' . $xmlObject->NFe->infNFe->ide->nNF . ' já cadastrada no Depósito ' . Deposit::find($data['validatedData']['deposit_id'])->name . '.';
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
            'type' => $data['validatedData']['type'],
        ])->id;

        // Percorre todos os itens do XML.
        foreach($data['validatedData']['xmlObject']->NFe->infNFe->det as $item):
            // Verifica se item não está cadastrado no Fornecedor.
            if(Provideritem::where(['code' => $item->prod->cProd, 'provider_id' => $data['validatedData']['provider_id']])->doesntExist()):
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
                Provideritem::where(['code' => $item->prod->cProd, 'provider_id' => $data['validatedData']['provider_id']])->update([
                    'name' => Str::upper($item->prod->xProd),
                    'ean' => !empty($item->prod->cEANTrib) ? $item->prod->cEANTrib : null,
                    'ncm' => $item->prod->NCM,
                    'cfop' => $item->prod->CFOP,
                    'cest' => !empty($item->prod->CEST) ? $item->prod->CEST : null,
                    'measure' => Str::upper($item->prod->uCom),
                ]);

                // Retorna id do item no Fornecedor.
                $provideritem_id = Provideritem::where(['code' => $item->prod->cProd, 'provider_id' => $data['validatedData']['provider_id']])->first()->id;
            endif;

            // Item do Fornecedor.
            $provideritem = Provideritem::find($provideritem_id);

            // Inclui os items na Entrada.
            Depositinputitem::create([
                'depositinput_id' => Depositinput::where(['deposit_id' => $data['validatedData']['deposit_id'], 'key' => $data['validatedData']['key']])->first()->id,
                'provideritem_id' => $provideritem->id,
                'identifier' => $item['nItem'],
            ]);

            // Verifica se o Item do Fornecedor está relacionado com algum Produto.
            if(!empty($provideritem->product_id)):
                // Entrada no Depósito.
                $depositinput = Depositinput::where(['deposit_id' => $data['validatedData']['deposit_id'], 'key' => $data['validatedData']['key']])->first();

                // Multiplicador de quantidade.
                if($provideritem->signal == 'divide'):
                    $quantity_final = $item->prod->qCom / $provideritem->amount;
                else:
                    $quantity_final = $item->prod->qCom * $provideritem->amount;
                endif;

                // Cadastra Produto na Entrada do depósito.
                Depositinputproduct::create([
                    'depositinput_id' => $depositinput->id,
                    'product_id' => $provideritem->product_id,
                    'product_name' => Product::find($provideritem->product_id)->name,
                    'identifier' => $item['nItem'],
                    'quantity' => $item->prod->qCom,
                    'quantity_final' => $quantity_final,
                ]);

                // Atribui Código no relacionamento Productprovider.
                Productprovider::where(['product_id' => $provideritem->product_id, 'provider_id' => $data['validatedData']['provider_id']])->update([
                    'provider_code' => $item->prod->cProd,
                ]);
            endif;
        endforeach;

        // After.
        $after = Depositinput::find($depositinput_id);

        // Auditoria.
        Audit::depositinputAddXml($data, $after);

        // Mensagem.
        $message = 'Entrada no Depósito ' . $after->deposit_name . ' cadastrada com sucesso.';
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
        // ...

        return true;
    }

    /**
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditItemRelates(array $data) : bool {
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
    public static function editItemRelates(array $data) : bool {
        // Item.
        $item = Invoiceitem::find($data['validatedData']['invoiceitem_id']);

        // Atualiza item.
        Invoiceitem::find($data['validatedData']['invoiceitem_id'])->update([
            'equipment'         => $data['validatedData']['equipment'],
            'productgroup_id'   => !empty($data['validatedData']['productgroup_id']) ? $data['validatedData']['productgroup_id'] : null,
            'invoicecsv_id'     => !empty($data['validatedData']['invoicecsv_id']) ? $data['validatedData']['invoicecsv_id']  : null,
            'quantity_final'    => General::encodeFloat3($data['validatedData']['quantity_final']),
            'value_final'       => General::encodeFloat3($data['validatedData']['value_final']),
            'ipi_final'         => General::encodeFloat3($data['validatedData']['ipi_final']),
            'ipi_aliquot_final' => General::encodeFloat3($data['validatedData']['ipi_aliquot_final']),
            'margin'            => General::encodeFloat2($data['validatedData']['margin']),
            'shipping'          => General::encodeFloat2($data['validatedData']['shipping']),
        ]);

        // Mensagem.
        $message = 'Itens da ' . $data['config']['title'] . ' ' . Invoice::find($data['validatedData']['invoice_id'])->number . ' atualizados com sucesso.';
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
    public static function dependencyEditItemRelates(array $data) : bool {
        // Atualiza index.
        Invoiceitem::generateIndex($data);

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
        // Percorre todos os Itens da Saída.
        foreach(Depositinputitem::where('depositinput_id', $data['validatedData']['depositinput_id'])->get() as $key => $depositinputitem):
            // Exclui Item da Saída.
            Depositinputitem::find($depositinputitem->id)->delete();
        endforeach;

        // Percorre todos os Produtos da Saída.
        foreach(Depositinputproduct::where('depositinput_id', $data['validatedData']['depositinput_id'])->get() as $key => $depositinputproduct):
            // Exclui Produto da Saída.
            Depositinputproduct::find($depositinputproduct->id)->delete();
        endforeach;

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
        Depositinput::find($data['validatedData']['depositinput_id'])->delete();

        // Auditoria.
        Audit::depositinputErase($data);

        // Mensagem.
        $message = 'Saída de Depósito excluída com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
