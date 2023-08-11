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
     * Formata index.
     * @var float $value
     * 
     * @return float $index
     */
    public static function formatIndex(float $value) : float {
        // Força o número com 9 dígitos.
        $value = number_format($value, '9', '.');

        // Separa o inteiro e o decimal.
        $x = explode('.', $value);

        // Formata index.
        $index = $x[0] . '.' . $x[1][0] . '0';

        return (float)$index;
    }

    /**
     * Teste se todos os itens da NFe possuem o campo updated true.
     * @var int $invoice_id
     * 
     * @return bool $updated
     */
    public static function updatedAll(int $invoice_id) : bool {
        // Inicializa variável.
        $updated = false;

        // Verifica não existe nehum item sem atualização do updated.
        if(Invoiceitem::where(['invoice_id' => $invoice_id, 'updated' => false])->doesntExist()):
            $updated = true;
        endif;

        return $updated;
    }

    /**
     * Verifica se Cest está vazio.
     * @var <null, int> $cest
     * 
     * @return string $cest
     */
    public static function cestEmpty($cest) : string {
        // Verifica se Cest está vazio.
        if (!$cest) $cest = null;

        return (string)$cest;
    }

    /**
     * Verifica se valor IPI está vazio.
     * @var <null, int> $vIpi
     * 
     * @return float $vIpi
     */
    public static function vIpiEmpty($vIpi) : float {
        // Verifica se valor IPI está vazio.
        if (!empty($vIpi)) $vIpi = 0.000;

        return (float)$vIpi;
    }

    /**
     * Verifica se alíquota IPI está vazia.
     * @var <null, int> $pIpi
     * 
     * @return float $pIpi
     */
    public static function pIpiEmpty($pIpi) : float {
        // Verifica se alíquota IPI está vazia.
        if (!$pIpi) $pIpi = 0.000;

        return (float)$pIpi;
    }

    /**
     * Verifica se Ean está vazio.
     * @var <null, int> $ean
     * 
     * @return string $ean
     */
    public static function eanEmpty($ean) : string {
        // Verifica se Ean está vazio.
        if (!$ean) $ean = null;

        return (string)$ean;
    }

    /**
     * Gera a quantidade final.
     * @var float $quantity
     * @var int $providerbusiness_id
     * 
     * @return float $quantity_final
     */
    public static function quantityFinal(float $quantity, int $providerbusiness_id) : float {
        // Gera a quantidade final.
        (float)$quantity_final = ($quantity / (float)Providerbusiness::find($providerbusiness_id)->multiplier_quantity) * 100;

        return $quantity_final;
    }

    /**
     * Gera o valor final.
     * @var float $value
     * @var int $providerbusiness_id
     * 
     * @return float $value_final
     */
    public static function valueFinal(float $value, int $providerbusiness_id) : float {
        // Gera o valor final.
        (float)$value_final = ($value /Providerbusiness::find($providerbusiness_id)->multiplier_value) * 100;

        return $value_final;
    }

    /**
     * Gera o valor total final.
     * @var float $value_total
     * @var int $providerbusiness_id
     * 
     * @return float $value_total_final
     */
    public static function valueTotalFinal(float $value_total, int $providerbusiness_id) : float {
        // Gera o valor total final.
        (float)$value_total_final = ($value_total / Providerbusiness::find($providerbusiness_id)->multiplier_value) * 100;

        return $value_total_final;
    }

    /**
     * Gera o valor ipi final.
     * @var <float, null> $ipi
     * @var int $providerbusiness_id
     * 
     * @return float $ipi_final
     */
    public static function ipiFinal($ipi, int $providerbusiness_id){
        // Verifica se ipi está vazio.
        if($ipi):
            // verifica se o muktiplicador ipi está vazio.
            if(Providerbusiness::find($providerbusiness_id)->multiplier_ipi):
                // Gera o valor ipi final.
                $ipi_final = ($ipi / Providerbusiness::find($providerbusiness_id)->multiplier_ipi) * 100;
            else:
                $ipi_final = 0.000;
            endif;
        else:
            $ipi_final = $ipi;
        endif;

        return $ipi_final;
    }

    /**
     * Evita o valor 0.
     * @var float $value
     * 
     * @return float $value
     */
    public static function valueNotZero(float $value) : float {
        // Evita o valor 0.
        if ($value <= 0)  $value = 1.00;

        return $value;
    }

    /**
     * Converte quantidade e valor.
     * @var array $data,
     * 
     * @return array $value
     */
    public static function signalAmount(array $data) : array {
        // Converte quantidade e valor.
        if($data['signal'] == '/'):
            $quantity_final = Invoiceitem::valueNotZero(General::encodeFloat3($data['quantity_final'])) * General::encodeFloat3($data['amount']);
            $value_final    = General::encodeFloat3($data['value_final'])                               / General::encodeFloat3($data['amount']);
        else:
            $quantity_final = Invoiceitem::valueNotZero(General::encodeFloat3($data['quantity_final'])) / General::encodeFloat3($data['amount']);
            $value_final    = General::encodeFloat3($data['value_final'])                               * General::encodeFloat3($data['amount']);
        endif;

        // Monta array
        $value = [
            'quantity_final'    => (float)$quantity_final,
            'value_final'       => (float)$value_final,
            'value_total_final' => (float)$quantity_final * $value_final,
        ];

        return $value;
    }

    /**
     * Gera o valor ipi aliquot final.
     * @var <float, null> $ipi_aliquot
     * @var int $providerbusiness_id
     * 
     * @return float $ipi_aliquot_final
     */
    public static function ipiAliquotFinal($ipi_aliquot, int $providerbusiness_id){
        // Verifica se ipi_aliquot está vazio.
        if($ipi_aliquot):
            // Verifica se multiplicador aliquota ipi está vazia.
            if(Providerbusiness::find($providerbusiness_id)->multiplier_ipi_aliquot):
                // Gera o valor ipi aliquot final.
                $ipi_aliquot_final = ($ipi_aliquot / Providerbusiness::find($providerbusiness_id)->multiplier_ipi_aliquot) * 100;
            else:
                $ipi_aliquot_final = 0.000;
            endif;
        else:
            $ipi_aliquot_final = $ipi_aliquot;
        endif;

        return $ipi_aliquot_final;
    }

    /**
     * Verifica se item já foi atribuido a outra Nota Fiscal.
     * @var int $invoice_id
     * @var string $code
     * 
     * @return array $$array_old_item
     */
    public static function oldItem(int $invoice_id, string $code) : array {
        // Inicializa as variáveis.
        $equipment       = false;
        $productgroup_id = null;
        $invoicecsv_id   = null;
        $signal          = '/';
        $amount          = 1.000;

        // Percorre todos os itens com o mesmo codigo, exceto o desta Nota Fiscal.
        foreach(Invoiceitem::where([['code', $code], ['invoice_id', '!=', $invoice_id]])->orderBy('id', 'DESC')->get() as $key_item => $item):
            // Verifica se o Fornecedor do item é o mesmo desta Nota Fiscal.
            if($item->invoice->provider->id == Invoice::find($invoice_id)->provider_id):
                // Verifica se as variáveis já foram atribuidas.
                if(!$assigned):
                    // Verifica se existe invoice CSV vinculado ao produto.
                    if(!empty($item->invoicecsv_id)):
                        // Recupera o invoicecsv, caso exista.
                        if($csv = Invoicecsv::where(['invoice_id' => $invoice_id, 'code' => Invoicecsv::find($item->invoicecsv_id)->code])->first()):
                            $invoicecsv_id = $csv->id;
                        endif;
                    endif;

                    // Atribui as variáveis.
                    $equipment       = $item->equipment;
                    $productgroup_id = $item->productgroup_id;
                    $signal          = $item->signal;
                    $amount          = $item->amount;

                    // Força a saída do loop ao encontrar a primeira ocorrência.
                    break;
                endif;
            endif;
        endforeach;

        $array_old_item = [
            'equipment'       => $equipment,
            'productgroup_id' => $productgroup_id,
            'invoicecsv_id'   => $invoicecsv_id,
            'signal'          => $signal,
            'amount'          => $amount,
        ];

        return $array_old_item;
    }

    /**
     * Trata o campo updated.
     * @var <null, id> $productgroup_id
     * @var <null, id> $invoicecsv_id
     * 
     * @return bool $updated
     */
    public static function itemUpdated($productgroup_id, $invoicecsv_id) : bool {
        // Inicializa a variável.
        $updated = false;

        if (!empty($productgroup_id) && !empty($invoicecsv_id)) $updated = true;

        return (bool)$updated;
    }

    /**
     * Gerar o index dos eFiscos.
     * @var int $invoice_id
     * 
     * @return bool $generate
     */
    public static function generateIndex(int $invoice_id){
        // Inicializa variável.
        $generate = null;

        // Verifica se todos os itens possuem Item CSV e Grupo de Produto atribuído.
        if(Invoiceitem::updatedAll($invoice_id)):
            // Inicializa o array.
            $efisco_value = [];

            // Percorre todos eFiscos da Nota Fiscal.
            foreach(Invoiceefisco::where('invoice_id', $invoice_id)->get() as $key_efisco => $efisco):
                // Atrinui valor inicial zero (0.00) às variáveis.
                $efisco_value_total[$efisco->id]       = 0.00;
                $efisco_value_total_final[$efisco->id] = 0.00;
                $efisco_ipi[$efisco->id]               = 0.00;
                $efisco_ipi_final[$efisco->id]         = 0.00;

                //Percorre todos os itens da Nota Fiscal.
                foreach(Invoiceitem::where('invoice_id', $invoice_id)->get() as $key_item => $item):
                    // Verifica se Grupo de Produto do eFisco é o mesmo do item.
                    if($efisco->productgroup_id == $item->productgroup_id):
                        // Incrementa os valores.
                        $efisco_value_total[$efisco->id]       = $efisco_value_total[$efisco->id]       + $item->value_total;
                        $efisco_value_total_final[$efisco->id] = $efisco_value_total_final[$efisco->id] + $item->value_total_final;
                        $efisco_ipi[$efisco->id]               = $efisco_ipi[$efisco->id]               + $item->ipi;
                        $efisco_ipi_final[$efisco->id]         = $efisco_ipi_final[$efisco->id]         + $item->ipi_final;
                    endif;
                endforeach;

                // Atribui o ICMS.
                $efisco_icms[$efisco->id] = $efisco->icms;

                // Monta a Referência.
                $reference[$efisco->id] = ($efisco_icms[$efisco->id] / ($efisco_value_total_final[$efisco->id] + $efisco_ipi_final[$efisco->id])) * 100.00;

                // Monta o Index.
                $index[$efisco->id] = Invoiceitem::formatIndex(100.00 - $reference[$efisco->id]);

                // Atualiza eFisco, definindo o index.
                Invoiceefisco::find($efisco->id)->update([
                    'value_invoice' => $efisco_value_total[$efisco->id],
                    'value_final'   => $efisco_value_total_final[$efisco->id],
                    'ipi_invoice'   => $efisco_ipi[$efisco->id],
                    'ipi_final'     => $efisco_ipi_final[$efisco->id],
                    'index'         => $index[$efisco->id],
                ]);

                //Percorre todos os itens da Nota Fiscal com este efisco.
                Invoiceitem::where(['invoice_id' => $invoice_id, 'productgroup_id' => $efisco->productgroup_id])->update([
                    'index' => $index[$efisco->id],
                ]);
            endforeach;

            // Monta retorno. Útil para consulta.
            $generate = [
            'efisco_value_total'       => $efisco_value_total,
            'efisco_value_total_final' => $efisco_value_total_final,
            'efisco_ipi'               => $efisco_ipi,
            'efisco_ipi_final'         => $efisco_ipi_final,
            'efisco_icms'              => $efisco_icms,
            'reference'                => $reference,
            'index'                    => $index,
            ];
        endif;

        return $generate;
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
            // Busca dados do último cadastro do item, caso exista.
            $old_item = Invoiceitem::oldItem((int)$invoice->id, (string)$invoiceitem->prod->cProd);

            // Trata IPI.
            if(!empty($invoiceitem->imposto->IPI->IPITrib->vIPI) && !empty($invoiceitem->imposto->IPI->IPITrib->pIPI)):
                $vIPI = $invoiceitem->imposto->IPI->IPITrib->vIPI;
                $pIPI = $invoiceitem->imposto->IPI->IPITrib->pIPI;
            else:
                $vIPI = 0.000;
                $pIPI = 0.000;
            endif;

            // Cadastra.
            Invoiceitem::create([
                'invoice_id'        => $invoice->id,
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
                'ipi'               => !empty($invoiceitem->imposto->IPI->IPITrib->vIPI) ? $invoiceitem->imposto->IPI->IPITrib->vIPI : 0.000,
                'ipi_final'         => !empty(Invoiceitem::ipiFinal($invoiceitem->imposto->IPI->IPITrib->vIPI, Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first()->id)),
                'ipi_aliquot'       => Invoiceitem::pIpiEmpty($invoiceitem->imposto->IPI->IPITrib->pIPI),
                'ipi_aliquot_final' => Invoiceitem::pIpiEmpty(Invoiceitem::ipiAliquotFinal($invoiceitem->imposto->IPI->IPITrib->pIPI, Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first()->id)),
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
