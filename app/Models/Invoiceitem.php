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

        'ipi',
        'ipi_final',
        'ipi_aliquot',
        'ipi_aliquot_final',

        'margin',
        'shipping',

        'discount',
        'addition',

        'index',

        'cost_total',
        'cost',

        'price',
        'card',
        'retail',

        'price_csv',
        'card_csv',
        'retail_csv',

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
     * Arredonda Preço.
     * @var float $value
     * 
     * @return float $index
     */
    public static function roundUp($value, $places=0)
    {
        if($places < 0):
            $places = 0;
        endif;
        $mult = pow(10, $places);
        return ceil($value * $mult) / $mult;
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
     * Verifica se o item já foi atribuido a outra Nota Fiscal.
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
     * Verifica se todos os itens csv estão sendo utilizados.
     * @var int $invoice_id
     * 
     * @return bool $csv
     */
    public static function csvOk(int $invoice_id) : bool {
        $csv = false;

        // Conta CSV.
        $qtd_csv_invoice = Invoicecsv::where('invoice_id', $invoice_id)->get()->count();
        $key_csv = [];
        foreach(Invoiceitem::where('invoice_id', $invoice_id)->get() as $item):
            if (!empty($item->invoicecsv_id)) $key_csv[$item->invoicecsv_id] = '';
        endforeach;
        $qtd_csv_select = count($key_csv);

        if ($qtd_csv_invoice == $qtd_csv_select) $csv = true;

        return (bool)$csv;
    }

    /**
     * Verifica se todos os eFiscos estão sendo utilizados.
     * @var int $invoice_id
     * 
     * @return bool $efisco
     */
    public static function itemOk(int $invoice_id) : bool {
        $efisco = false;

        // Conta Efisco.
        $qtd_efisco_invoice = Invoiceefisco::where('invoice_id', $invoice_id)->get()->count();
        $key_efisco = [];
        foreach(Invoiceitem::where('invoice_id', $invoice_id)->get() as $item):
            if (!empty($item->productgroup_id)) $key_efisco[$item->productgroup_id] = '';
        endforeach;
        $qtd_efisco_select = count($key_efisco);

        if ($qtd_efisco_invoice == $qtd_efisco_select) $efisco = true;

        return (bool)$efisco;
    }

    /**
     * Verifica se todos os indices foram calculados
     * 
     * @return bool $index
     */
    public static function indexOk(int $invoice_id) : bool {
        $index = false;

        if (Invoiceitem::where(['invoice_id' => $invoice_id, 'index' => NULL])->doesntExist()) $index = true;

        return (bool)$index;
    }

    /**
     * Verifica se todos os eFiscos estão sendo utilizados.
     * @var int $invoice_id
     * 
     * @return bool $price
     */
    public static function priceOk(int $invoice_id) : bool {
        $price = false;

        if (Invoiceitem::where(['invoice_id' => $invoice_id, 'price' => NULL])->doesntExist()) $price = true;

        return (bool)$price;
    }

    /**
     * Gerar o index dos eFiscos.
     * @var int $invoice_id
     * 
     * @return bool $generate
     */
    public static function generateIndex(array $data){
        // Inicializa variável.
        $generate = null;

        // Verifica se todos os itens possuem item CSV e Grupo de Produto atribuído.
        if(Invoiceitem::csvOk($data['validatedData']['invoice_id']) && Invoiceitem::itemOk($data['validatedData']['invoice_id'])):
            // Percorre todos eFiscos da Nota Fiscal.
            foreach(Invoiceefisco::where('invoice_id', $data['validatedData']['invoice_id'])->get() as $key_efisco => $efisco):
                // Atrinui valor inicial zero (0.00) às variáveis.
                $efisco_value_invoice[$efisco->id] = 0.00;
                $efisco_value_final[$efisco->id]   = 0.00;
                $efisco_ipi[$efisco->id]           = 0.00;
                $efisco_ipi_final[$efisco->id]     = 0.00;

                //Percorre todos os itens da Nota Fiscal.
                foreach(Invoiceitem::where('invoice_id', $data['validatedData']['invoice_id'])->get() as $key_item => $item):
                    // Verifica se Grupo de Produto do eFisco é o mesmo do item.
                    if((int)$efisco->productgroup_id == (int)$item->productgroup_id):
                        // Incrementa os valores.
                        $efisco_value_invoice[$efisco->id] = $efisco_value_invoice[$efisco->id] + ($item->value * $item->quantity);
                        $efisco_value_final[$efisco->id]   = $efisco_value_final[$efisco->id]   + ($item->value_final * $item->quantity_final);
                        $efisco_ipi[$efisco->id]           = $efisco_ipi[$efisco->id]           + $item->ipi;
                        $efisco_ipi_final[$efisco->id]     = $efisco_ipi_final[$efisco->id]     + $item->ipi_final;
                    endif;
                endforeach;

                // Atribui o ICMS.
                $efisco_icms[$efisco->id] = $efisco->icms;

                // Monta a Referência.
                $reference[$efisco->id] = ($efisco_icms[$efisco->id] / ($efisco_value_final[$efisco->id] + $efisco_ipi_final[$efisco->id])) * 100.00;

                // Monta o Index.
                $index[$efisco->id] = Invoiceitem::formatIndex(100.00 - $reference[$efisco->id]);

                // Atualiza eFisco, definindo o index.
                Invoiceefisco::find($efisco->id)->update([
                    'value_invoice' => $efisco_value_invoice[$efisco->id],
                    'value_final'   => $efisco_value_final[$efisco->id],
                    'ipi_invoice'   => $efisco_ipi[$efisco->id],
                    'ipi_final'     => $efisco_ipi_final[$efisco->id],
                    'index'         => $index[$efisco->id],
                ]);

                //Percorre todos os itens da Nota Fiscal com este efisco.
                Invoiceitem::where(['invoice_id' => $data['validatedData']['invoice_id'], 'productgroup_id' => $efisco->productgroup_id])->update([
                    'index' => $index[$efisco->id],
                ]);
            endforeach;

            // Monta retorno. Útil para consulta.
            $generate = [
            'efisco_value_invoice' => $efisco_value_invoice,
            'efisco_value_final'   => $efisco_value_final,
            'efisco_ipi'           => $efisco_ipi,
            'efisco_ipi_final'     => $efisco_ipi_final,
            'efisco_icms'          => $efisco_icms,
            'reference'            => $reference,
            'index'                => $index,
            ];
        endif;

        return $generate;
    }

    /**
     * // Define o Tipo de Preço.
     * @var array $data
     * 
     * @return bool true
     */
    public static function price(array $data) : bool {
        // Define o Tipo de Preço.
        switch((int)Invoice::find($data['validatedData']['invoice_id'])->company->price):
            case 1  : Invoiceitem::price1($data); break;
            case 2  : Invoiceitem::price2($data); break;
            default : Invoiceitem::price1($data); break;
        endswitch;

        return true;
    }

    /**
     * Preço Tipo 1.
     * @var array $data
     * 
     * @return void
     */
    public static function price1(array $data) : void {
        // Item.
        $item = Invoiceitem::find($data['validatedData']['invoiceitem_id']);

        // Cálculo Custo.
        $cost_begin         = $item->value_final * $item->quantity_final;
        $cost_plus_index    = ($cost_begin / $item->index) * 100;
        $cost_plus_ipi      = $cost_plus_index + (($cost_plus_index * $item->ipi_aliquot_final) / 100);
        $cost_plus_shipping = $cost_plus_ipi + (($cost_plus_ipi * $item->shipping) / 100);
        // Custo.
        $cost_full = ($cost_plus_shipping / $item->quantity_final) / $item->amount;
        $cost      = Invoiceitem::roundUp($cost_full, 2);

        // Custo Total di item.
        if($item->signal == '/'):
            $cost_total = ($cost * $item->amount) * $item->quantity_final;
        else:
            $cost_total = ($cost / $item->amount) * $item->quantity_final;
        endif;

        // Preço Final.
        $price_full = $cost + (($cost * $item->margin) / 100);
        $price = Invoiceitem::roundUp($price_full, 2);

        // Preço Cartão.
        $card_full = $price / 0.9;
        $card = Invoiceitem::roundUp($card_full, 2);

        // Cálculo Preço Varejo - Define a Percentagem sobre o cartão.
        if($card_full <= 20)                             : $retail_aliquot = 50;
            elseif($card_full > 20 && $card_full <= 50)  : $retail_aliquot = 40;
            elseif($card_full > 50 && $card_full <= 80)  : $retail_aliquot = 30;
            elseif($card_full > 80 && $card_full <= 200) : $retail_aliquot = 20;
            else                                         : $retail_aliquot = 15;
        endif;
        $retail_full = $card_full + (($card_full * $retail_aliquot) / 100);
        $retail_nick = Invoiceitem::roundUp($retail_full, 2);
        // Ajusta Centavos.
        $integer  = (int)$retail_nick;
        $fraction = $retail_nick - $integer;
        if($retail_nick > 3):
            if($fraction >= 0 && $fraction <= 0.25):
                $integer = $integer - 1;
                $fraction = 0.99;
            elseif($fraction >= 0.26 && $fraction <= 0.8):
                $fraction = 0.5;
            else:
                $fraction = 0.99;
            endif;
        else:
            if($fraction >= 0 && $fraction <= 0.6):
                $fraction = 0.5;
            else:
                $fraction = 0.99;
            endif;
        endif;
        // Preço Varejo.
        $retail = Invoiceitem::roundUp($integer + $fraction, 2);

        // Preço CSV Final.
        $price_csv = (float)$item->invoicecsv->value;

        // Preço CSV Cartão.
        $card_csv_full = $price_csv / 0.9;
        $card_csv      = Invoiceitem::roundUp($card_csv_full, 2);

        // Cálculo Preço CSV Varejo - Define a Percentagem sobre o cartão.
        if($card_csv_full <= 20)                                 : $retail_csv_aliquot = 50;
            elseif($card_csv_full > 20 && $card_csv_full <= 50)  : $retail_csv_aliquot = 40;
            elseif($card_csv_full > 50 && $card_csv_full <= 80)  : $retail_csv_aliquot = 30;
            elseif($card_csv_full > 80 && $card_csv_full <= 200) : $retail_csv_aliquot = 20;
            else                                                 : $retail_csv_aliquot = 15;
        endif;
        $retail_csv_full = $card_csv_full + (($card_csv_full * $retail_csv_aliquot) / 100);
        $retail_csv_nick = Invoiceitem::roundUp($retail_csv_full, 2);
        // Ajusta Centavos.
        $integer  = (int)$retail_csv_nick;
        $fraction = $retail_csv_nick - $integer;
        if($retail_csv_nick > 3):
            if($fraction >= 0 && $fraction <= 0.25):
                $integer = $integer - 1;
                $fraction = 0.99;
            elseif($fraction >= 0.26 && $fraction <= 0.8):
                $fraction = 0.5;
            else:
                $fraction = 0.99;
            endif;
        else:
            if($fraction >= 0 && $fraction <= 0.6):
                $fraction = 0.5;
            else:
                $fraction = 0.99;
            endif;
        endif;
        // Preço CSV Varejo.
        $retail_csv = Invoiceitem::roundUp($integer + $fraction, 2);

        // Evita centavos em produtos com preço 0
        if(!$price_csv > 0) $retail_csv = 0;

        // Atualiza os Preços do item.
        Invoiceitem::find($item->id)->update([
            'cost_total' => $cost_total,
            'cost'       => $cost,
            'price'      => $price,
            'card'       => $card,
            'retail'     => $retail,
            'price_csv'  => $price_csv,
            'card_csv'   => $card_csv,
            'retail_csv' => $retail_csv,
        ]);
    }

    /**
     * Preço Tipo 2.
     * @var array $data
     * 
     * @return void
     */
    public static function price2(array $data) : void {
        // Item.
        $item = Invoiceitem::find($data['validatedData']['invoiceitem_id']);

        // Cálculo Custo.
        $cost_begin         = $item->value_final * $item->quantity_final;
        $cost_plus_index    = ($cost_begin / $item->index) * 100;
        $cost_plus_ipi      = $cost_plus_index + (($cost_plus_index * $item->ipi_aliquot_final) / 100);
        $cost_plus_shipping = $cost_plus_ipi + (($cost_plus_ipi * $item->shipping) / 100);
        // Custo.
        $cost_full = ($cost_plus_shipping / $item->quantity_final) / $item->amount;
        $cost      = Invoiceitem::roundUp($cost_full, 2);

        // Custo Total di item.
        if($item->signal == '/'):
            $cost_total = ($cost * $item->amount) * $item->quantity_final;
        else:
            $cost_total = ($cost / $item->amount) * $item->quantity_final;
        endif;

        // Preço Final.
        $price_full = $cost + (($cost * $item->margin) / 100) / 0.9;
        $price = Invoiceitem::roundUp($price_full, 2);

        // Preço Cartão.
        $card_full = $price_full / 0.95;
        $card = Invoiceitem::roundUp($card_full, 2);

        // Cálculo Preço Varejo - Define a Percentagem sobre o cartão.
        if($card_full <= 20)                            : $retail_aliquot = 70;
            elseif($card_full > 20 && $card_full <= 30) : $retail_aliquot = 80;
            else                                        : $retail_aliquot = 85;
        endif;
        // Preço Varejo.
        $retail_full = $card_full + (($card_full * $retail_aliquot) / 100);
        $retail = Invoiceitem::roundUp($retail_full, 2);

        // Preço em caso de item ser Equipamento.
        if($item->equipament):
            // Preço Final em caso de item ser Equipamento.
            $price = $card;

            // Preço Cartão em caso de item ser Equipamento.
            $card = Invoiceitem::roundUp($price / 0.9, 2);

            // Preço Varejo em caso de item ser Equipamento.
            $retail = 0.00;
        endif;

        // Preço CSV Final.
        $price_csv = $item->invoicecsv->value;

        // Preço CSV Cartão.
        $card_csv_full = $price_csv / 0.95;
        $card_csv      = Invoiceitem::roundUp($card_csv_full, 2);

        // Cálculo Preço CSV Varejo - Define a Percentagem sobre o cartão.
        if($card_csv_full <= 20)                                 : $retail_csv_aliquot = 70;
            elseif($card_csv_full > 20 && $card_csv_full <= 30)  : $retail_csv_aliquot = 80;
            else                                                 : $retail_csv_aliquot = 85;
        endif;
        // Preço CSV Varejo.
        $retail_csv_full = $card_csv_full + (($card_csv_full * $retail_csv_aliquot) / 100);
        $retail_csv = Invoiceitem::roundUp($retail_csv_full, 2);

        // Preço CSV em caso de item ser Equipamento.
        if($item->equipament):
            // Preço CSV Cartão em caso de item ser Equipamento.
            $price_csv = $card_csv;

            // Preço CSV Cartão em caso de item ser Equipamento.
            $card_csv = Invoiceitem::roundUp($price_csv / 0.9, 2);

            // Preço CSV Varejo em caso de item ser Equipamento.
            $retail_csv = 0.00;
        endif;

        // Atualiza os Preços do item.
        Invoiceitem::find($item->id)->update([
            'cost_total' => $cost_total,
            'cost'       => $cost,
            'price'      => $price,
            'card'       => $card,
            'retail'     => $retail,
            'price_csv'  => $price_csv,
            'card_csv'   => $card_csv,
            'retail_csv' => $retail_csv,
        ]);
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
        $providerbusiness = Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first();

        // Percorre todos os itens da Nota Fiscal.
        foreach($data['validatedData']['xmlObject']->NFe->infNFe->det as $invoiceitem):
            // Busca dados do último cadastro do item, caso exista.
            $old_item = Invoiceitem::oldItem((int)$invoice->id, (string)$invoiceitem->prod->cProd);

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
                'ean'               => !empty($invoiceitem->prod->cEANTrib) ? $invoiceitem->prod->cEANTrib : null,
                'name'              => Str::upper($invoiceitem->prod->xProd),
                'ncm'               => $invoiceitem->prod->NCM,
                'cfop'              => $invoiceitem->prod->CFOP,
                'cest'              => !empty($invoiceitem->prod->CEST) ? $invoiceitem->prod->CEST : null,
                'measure'           => Str::upper($invoiceitem->prod->uCom),

                'quantity'          => $invoiceitem->prod->qCom,
                'quantity_final'    => (((float)$invoiceitem->prod->qCom / (float)$providerbusiness->multiplier_quantity) * 100),

                'value'             => $invoiceitem->prod->vUnCom,
                'value_final'       => (((float)$invoiceitem->prod->vUnCom / (float)$providerbusiness->multiplier_value) * 100),

                'ipi'               => !empty($invoiceitem->imposto->IPI->IPITrib->vIPI) ? $invoiceitem->imposto->IPI->IPITrib->vIPI : 0.00,
                'ipi_final'         => !empty($invoiceitem->imposto->IPI->IPITrib->vIPI) ? (((float)$invoiceitem->imposto->IPI->IPITrib->vIPI / (float)$providerbusiness->multiplier_ipi) * 100) : 0.00,

                'ipi_aliquot'       => !empty($invoiceitem->imposto->IPI->IPITrib->pIPI) ? $invoiceitem->imposto->IPI->IPITrib->pIPI : 0.00,
                'ipi_aliquot_final' => !empty($invoiceitem->imposto->IPI->IPITrib->pIPI) ? (((float)$invoiceitem->imposto->IPI->IPITrib->pIPI / (float)$providerbusiness->multiplier_ipi_aliquot) * 100) : 0.00,

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
     * Atualiza pós Businnes.
     * @var array $data
     * 
     * @return bool true
     */
    public static function editBusiness(array $data) : bool {
        // Negociação com Fornecedor.
        $providerbusiness = Providerbusiness::where('provider_id', Invoice::find($data['validatedData']['invoice_id'])->provider_id)->first();

        // Percorre os itens da Nota Fiscal.
        foreach(Invoiceitem::where('invoice_id', $data['validatedData']['invoice_id'])->get() as $key => $invoiceitem):
            // Atualiza o item da Nota Fiscal.
            Invoiceitem::find($invoiceitem->id)->update([
                'quantity_final'    => (($invoiceitem->quantity / $providerbusiness->multiplier_quantity) * 100),
                'value_final'       => (($invoiceitem->value / $providerbusiness->multiplier_value) * 100),
                'value_total_final' => (($invoiceitem->value_total / $providerbusiness->multiplier_value) * 100),
                'ipi_final'         => ($providerbusiness->multiplier_ipi > 0) ? (($invoiceitem->ipi / $providerbusiness->multiplier_ipi) * 100) : 0.00,
                'ipi_aliquot_final' => ($providerbusiness->multiplier_ipi_aliquot > 0) ? (($invoiceitem->ipi_aliquot / $providerbusiness->multiplier_ipi_aliquot) * 100) : 0.00,

                'margin'            => $providerbusiness->margin,
                'shipping'          => $providerbusiness->shipping,
                'discount'          => $providerbusiness->discount,
                'addition'          => $providerbusiness->addition,

                'index'             => null,
                'cost'              => null,
                'price'             => null,
                'card'              => null,
                'retail'            => null,
                'price_csv'         => null,
                'card_csv'          => null,
                'retail_csv'        => null,
            ]);
        endforeach;

        // Reseta eFisco.
        Invoiceefisco::where('invoice_id', $data['validatedData']['invoice_id'])->update([
            'value_invoice' => null,
            'value_final'   => null,
            'ipi_invoice'   => null,
            'ipi_final'     => null,
            'index'         => null,
        ]);

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

        // Verifica se as quantidades de itens da Nota Fiscal e itens CSV estão iguais.
        if(Invoiceitem::where('invoice_id', $data['validatedData']['invoice_id'])->get()->count() != Invoicecsv::where('invoice_id', $data['validatedData']['invoice_id'])->get()->count()):
            $message = 'Quantidade diferente de itens da Nota Fiscal(' . Invoiceitem::where('invoice_id', $data['validatedData']['invoice_id'])->get()->count() . ') e itens CSV(' . Invoicecsv::where('invoice_id', $data['validatedData']['invoice_id'])->get()->count() . ').';
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
     * Atualiza.
     * @var array $data
     * 
     * @return bool true
     */
    public static function edit(array $data) : bool {
        // Item.
        $item = Invoiceitem::find($data['validatedData']['invoiceitem_id']);

        // Atualiza item.
        Invoiceitem::find($data['validatedData']['invoiceitem_id'])->update([
            'equipment'         => $data['validatedData']['equipment'],
            'productgroup_id'   => !empty($data['validatedData']['productgroup_id']) ? $data['validatedData']['productgroup_id'] : null,
            'invoicecsv_id'     => !empty($data['validatedData']['invoicecsv_id']) ? $data['validatedData']['invoicecsv_id']  : null,
            'quantity_final'    => (General::encodeFloat3($data['validatedData']['quantity_final']) > $item->quantity) ? General::encodeFloat3($data['validatedData']['quantity_final']) : $item->quantity,
            'value_final'       => (General::encodeFloat3($data['validatedData']['value_final']) > $item->value) ? General::encodeFloat3($data['validatedData']['value_final']) : $item->value,
            'ipi_final'         => General::encodeFloat3($data['validatedData']['ipi_final']),
            'ipi_aliquot_final' => General::encodeFloat3($data['validatedData']['ipi_aliquot_final']),
            'margin'            => (General::encodeFloat2($data['validatedData']['margin']) > $item->margin) ? General::encodeFloat2($data['validatedData']['margin']) : $item->margin,
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
    public static function dependencyEdit(array $data) : bool {
        // Atualiza index.
        Invoiceitem::generateIndex($data);

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
            'amount' => ($data['validatedData']['amount'] > 0) ? General::encodeFloat3($data['validatedData']['amount']) : 1.00,
            'index'  => ($data['validatedData']['index'] > 0) ? General::encodeFloat2($data['validatedData']['index']) : 100.00,
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
        // Define preços.
        Invoiceitem::price($data);

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
        // Item.
        $item = Invoiceitem::find($data['validatedData']['invoiceitem_id']);

        // Valida o hold e hold all.
        if(($data['validatedData']['hold'] || $data['validatedData']['hold_all']) && $item->price_csv > 0):
            $price  = $item->price_csv;
            $card   = $item->card_csv;
            $retail = $item->retail_csv;
        else:
            $price  = General::encodeFloat2($data['validatedData']['price']);
            $card   = General::encodeFloat2($data['validatedData']['card']);
            $retail = General::encodeFloat2($data['validatedData']['retail']);
        endif;

        // Atualiza Preços.
        Invoiceitem::find($data['validatedData']['invoiceitem_id'])->update([
            'price'  => $price,
            'card'   => $card,
            'retail' => $retail,
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
        // ...

        return true;
    }

}
