<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Invoiceitem extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'invoiceitems';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'invoice_id',

        'equipment',

        'productgroup_id',
        'invoicecsv_id',

        'signal',
        'amount',

        'identifier',
        'code',
        'ean',
        'name',
        'ncm',
        'cfop',
        'cest',
        'measure',

        'quantity',
        'quantity_final',
        'value',
        'value_final',
        'value_total',
        'value_total_final',

        'ipi',
        'ipi_final',
        'ipi_aliquot',
        'ipi_aliquot_final',

        'margin',
        'shipping',

        'discount',
        'addition',

        'updated',

        'index',

        'price',
        'card',
        'retail',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function invoice(){return $this->belongsTo(Invoice::class);}
    public function productgroup(){return $this->belongsTo(Productgroup::class);}
    public function invoicecsv(){return $this->belongsTo(Invoicecsv::class);}

    /**
     * Define dados do item.
     * @var array $item_data
     */
    public static function item(array $item_data) : array {
        dd($item_data);
        //Define dados do item.
        $item = [
            'equipment'         => $old_item['equipment'],
            'productgroup_id'   => $old_item['productgroup_id'],
            'invoicecsv_id'     => $old_item['invoicecsv_id'],
            'signal'            => $old_item['signal'],
            'amount'            => $old_item['amount'],
            'identifier'        => $invoiceitem['nItem'],
            'code'              => $invoiceitem->prod->cProd,
            'ean'               => Invoiceitem::eanEmpty($invoiceitem->prod->cEANTrib),
            'name'              => Str::upper($invoiceitem->prod->xProd),
            'ncm'               => $invoiceitem->prod->NCM,
            'cfop'              => $invoiceitem->prod->CFOP,
            'cest'              => Invoiceitem::eanEmpty($invoiceitem->prod->CEST),
            'measure'           => Str::upper($invoiceitem->prod->uCom),
            'quantity'          => $invoiceitem->prod->qCom,
            'quantity_final'    => Invoiceitem::quantityFinal((float)$invoiceitem->prod->qCom, Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first()->id),
            'value'             => $invoiceitem->prod->vUnCom,
            'value_final'       => Invoiceitem::valueFinal((float)$invoiceitem->prod->vUnCom, Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first()->id),
            'value_total'       => $invoiceitem->prod->vProd,
            'value_total_final' => Invoiceitem::valueTotalFinal((float)$invoiceitem->prod->vProd, Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first()->id),
            'ipi'               => $item['ipi'],
            'ipi_final'         => $item['ipi_final'],
            'ipi_aliquot'       => $item['ipi_aliquot'],
            'ipi_aliquot_final' => $item['ipi_aliquot_final'],
        ];

        return $item;
    }

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool
     */
    public static function validateAdd(array $data){
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
        // Nota Fiscal.
        $invoice = Invoice::where('key', $data['validatedData']['key'])->first();

        // Negociação com Fornecedor.
        $providerbusiness = Providerbusiness::where('provider_id', $invoice->provider->id)->first();

        // Percorre todos os itens da Nota Fiscal.
        foreach($data['validatedData']['xmlObject']->NFe->infNFe->det as $invoiceitem):
            // Define dados do item.
            $item = Invoiceitem::item([
                'invoice'          => $invoice,
                'providerbusiness' => $providerbusiness,
                'invoiceitem'      => $invoiceitem,
            ]);

            // Cadastra.
            Invoiceitem::create([
                'invoice_id'        => $invoice->id,
                'equipment'         => $item['equipment'],
                'productgroup_id'   => $item['productgroup_id'],
                'invoicecsv_id'     => $item['invoicecsv_id'],
                'signal'            => $item['invoicecsv_id'],
                'amount'            => $item['amount'],
                'identifier'        => $item['identifier'],
                'code'              => $item['code'],
                'ean'               => $item['ean'],
                'name'              => $item['name'],
                'ncm'               => $item['ncm'],
                'cfop'              => $item['cfop'],
                'cest'              => $item['cest'],
                'measure'           => $item['measure'],
                'quantity'          => $item['quantity'],
                'quantity_final'    => $item['quantity_final'],
                'value'             => $item['value'],
                'value_final'       => $item['value_final'],
                'value_total'       => $item['value_total'],
                'value_total_final' => $item['value_total_final'],
                'ipi'               => $item['ipi'],
                'ipi_final'         => $item['ipi_final'],
                'ipi_aliquot'       => $item['ipi_aliquot'],
                'ipi_aliquot_final' => $item['ipi_aliquot_final'],
                'margin'            => $providerbusiness->margin,
                'shipping'          => $providerbusiness->shipping,
                'discount'          => $providerbusiness->discount,
                'addition'          => $providerbusiness->addition,
            ]);
        endforeach;

        return true;
    }

    /**
     * Executa dependências de cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function dependencyAdd(array $data) : bool {
        // ...

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
        // Atualiza item.
        Invoiceitem::find($data['validatedData']['invoiceitem_id'])->update([
            'equipment'         => $data['validatedData']['equipment'],
            'productgroup_id'   => General::idNullable($data['validatedData']['productgroup_id']),
            'invoicecsv_id'     => General::idNullable($data['validatedData']['invoicecsv_id']),
            'quantity_final'    => Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['quantity_final'])),
            'value_final'       => Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['value_final'])),
            'value_total_final' => Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['quantity_final'])) * Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['value_final'])),
            'ipi_final'         => General::encodeFloat3($data['validatedData']['ipi_final']),
            'ipi_aliquot_final' => General::encodeFloat3($data['validatedData']['ipi_aliquot_final']),
            'margin'            => Invoiceitem::valueNotZero(General::encodeFloat2($data['validatedData']['margin'])),
            'shipping'          => General::encodeFloat2($data['validatedData']['shipping']),
            'updated'           => Invoiceitem::itemUpdated($data['validatedData']['productgroup_id'], $data['validatedData']['invoicecsv_id']),
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
    public static function dependencyEdit(array $data) : bool {
        // Atualiza index.
        Invoiceitem::generateIndex($data['validatedData']['invoice_id']);

        return true;
    }

    /**
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditAmount(array $data) : bool {
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
    public static function editAmount(array $data) : bool {
        // Atualiza item.
        Invoiceitem::find($data['validatedData']['invoiceitem_id'])->update([
            'signal' => $data['validatedData']['signal'],
            'amount' => Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['amount'])),
            'index'  => Invoiceitem::valueNotZero(General::encodeFloat2($data['validatedData']['index'])),
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
    public static function dependencyEditAmount(array $data) : bool {
        // ...

        return true;
    }

    /**
     * Valida atualização.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateEditPrice(array $data) : bool {
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
    public static function editPrice(array $data) : bool {
        // Atualiza item.
        Invoiceitem::find($data['validatedData']['invoiceitem_id'])->update([
            'equipment'         => $data['validatedData']['equipment'],
            'productgroup_id'   => General::idNullable($data['validatedData']['productgroup_id']),
            'invoicecsv_id'     => General::idNullable($data['validatedData']['invoicecsv_id']),
            'quantity_final'    => Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['quantity_final'])),
            'value_final'       => Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['value_final'])),
            'value_total_final' => Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['quantity_final'])) * Invoiceitem::valueNotZero(General::encodeFloat3($data['validatedData']['value_final'])),
            'ipi_final'         => General::encodeFloat3($data['validatedData']['ipi_final']),
            'ipi_aliquot_final' => General::encodeFloat3($data['validatedData']['ipi_aliquot_final']),
            'margin'            => Invoiceitem::valueNotZero(General::encodeFloat2($data['validatedData']['margin'])),
            'shipping'          => General::encodeFloat2($data['validatedData']['shipping']),
            'updated'           => Invoiceitem::itemUpdated($data['validatedData']['productgroup_id'], $data['validatedData']['invoicecsv_id']),
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
    public static function dependencyEditPrice(array $data) : bool {
        // Atualiza index.
        Invoiceitem::generateIndex($data['validatedData']['invoice_id']);

        return true;
    }

    /**
     * Atualiza pós Businnes.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editBusiness(array $data) : bool {
        // Percorre as Notas Fiscais que possuem o mesmo Fornecedor desta Negociação.
        foreach(Invoice::where('provider_id', $data['validatedData']['provider_id'])->get() as $key => $invoice):
            // Percorre os itens da Nota Fiscal.
            foreach(Invoiceitem::where('invoice_id', $invoice->id)->get() as $key => $invoiceitem):
                // Atualiza o item da Nota Fiscal.
                Invoiceitem::find($invoiceitem->id)->update([
                    'quantity_final'    => Invoiceitem::quantityFinal((float)$invoiceitem->quantity, $data['validatedData']['providerbusiness_id']),
                    'value_final'       => Invoiceitem::valueFinal((float)$invoiceitem->value, $data['validatedData']['providerbusiness_id']),
                    'value_total_final' => Invoiceitem::valueTotalFinal((float)$invoiceitem->value_total, $data['validatedData']['providerbusiness_id']),
                    'ipi_final'         => Invoiceitem::vIpiEmpty(Invoiceitem::ipiFinal($invoiceitem->ipi, $data['validatedData']['providerbusiness_id'])),
                    'ipi_aliquot_final' => Invoiceitem::pIpiEmpty(Invoiceitem::ipiAliquotFinal($invoiceitem->ipi_aliquot, $data['validatedData']['providerbusiness_id'])),
                    'margin'            => General::encodeFloat2($data['validatedData']['business_margin']),
                    'shipping'          => General::encodeFloat2($data['validatedData']['business_shipping']),
                    'discount'          => General::encodeFloat2($data['validatedData']['business_discount']),
                    'addition'          => General::encodeFloat2($data['validatedData']['business_addition']),
                    'updated'           => false,
                    'index'             => null,
                    'price'             => null,
                    'card'              => null,
                    'retail'            => null,
                ]);
            endforeach;
        endforeach;

        // Reseta eFisco.
        Invoiceefisco::where('invoice_id', $invoice->id)->update([
            'value_invoice' => null,
            'value_final'   => null,
            'ipi_invoice'   => null,
            'ipi_final'     => null,
            'index'         => null,
        ]);

        return true;
    }
}
