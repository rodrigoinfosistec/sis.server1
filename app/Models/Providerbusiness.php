<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Providerbusiness extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'providerbusinesses';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'provider_id',

        'multiplier_type',

        'multiplier_quantity',
        'multiplier_value',

        'multiplier_ipi',
        'multiplier_ipi_aliquot',

        'margin',
        'shipping',

        'discount',
        'addition',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function provider(){return $this->belongsTo(Provider::class);}

        /**
         * Define o multiplicador ser utilizado.
         * @var string $type
         * @var string $quantity
         * @var string $value
         * 
         * @return string $multiplier
         */
        public static function multiplierType(string $type, string $quantity, string $value) : string {
            ($type == 'quantity') ? $multiplier = $quantity :  $multiplier = $value;

            return (string)$multiplier;
        }

        /**
         * Define o multiplicador a ser utilizado.
         * @var string $type
         * @var string $multiplier
         * 
         * @return string $array_multiplier
         */
        public static function multiplier(string $type, string $multiplier) : array {
            // Define o multiplicador a ser utilizado.
            if($type == 'quantity'):
                (string)$quantity = $multiplier;
                (string)$value    = '100,00';
            else:
                (string)$value    = $multiplier;
                (string)$quantity = '100,00';
            endif;

            // Monta array.
            $array_multiplier = [
                'quantity' => $quantity,
                'value'    => $value,
            ];

            return $array_multiplier;
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
        // Estende $data
        $data['validatedData']['provider_id'] = Provider::where('cnpj', $data['validatedData']['cnpj'])->first()->id;

        // Negociação Padrão com o Fornecedor.
        $business_default = Providerbusinessdefault::first();

        // Cadastra.
        Providerbusiness::create([
            'provider_id'            => Provider::find($data['validatedData']['provider_id'])->id,
            'multiplier_type'        => 'value',
            'multiplier_quantity'    => General::encodeFloat2($business_default->multiplier_quantity),
            'multiplier_value'       => General::encodeFloat2($business_default->multiplier_value),
            'multiplier_ipi'         => General::encodeFloat2($business_default->multiplier_ipi),
            'multiplier_ipi_aliquot' => General::encodeFloat2($business_default->multiplier_ipi_aliquot),
            'margin'                 => General::encodeFloat2($business_default->margin),
            'shipping'               => General::encodeFloat2($business_default->shipping),
            'discount'               => General::encodeFloat2($business_default->discount),
            'addition'               => General::encodeFloat2($business_default->addition),
        ]);

        // After.
        $after = Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first();

        // Auditoria.
        Audit::providerBusinessAdd($data, $after);

        // Mensagem.
        $message = 'Nogociação de ' . $data['config']['title'] . ' ' . $after->provider->name . ' cadastrada com sucesso.';
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

        // Verifica se multiplicador é zero(0,00%).
        if(General::encodeFloat2($data['validatedData']['business_multiplier']) < 1):
            $message = "Multiplicador da Nota Fiscal não pode ser menor que 1,00% .";
        endif;

        // Verifica se margem é zero(0,00%).
        if(General::encodeFloat2($data['validatedData']['business_margin']) < 5):
            $message = "Margem de Lucro não pode ser menor que 5,00% .";
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
        // Before.
        $before = Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first();

        // Atualiza.
        Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->update([
            'provider_id'            => $data['validatedData']['provider_id'],
            'multiplier_type'        => $data['validatedData']['business_multiplier_type'],
            'multiplier_quantity'    => ($data['validatedData']['business_multiplier_type'] == 'quantity') ? General::encodeFloat2($multiplier['quantity']) : 100.00,
            'multiplier_value'       => ($data['validatedData']['business_multiplier_type'] == 'value') ? General::encodeFloat2($multiplier['value']) : 100.00,
            'multiplier_ipi'         => General::encodeFloat2($data['validatedData']['business_multiplier_ipi']),
            'multiplier_ipi_aliquot' => General::encodeFloat2($data['validatedData']['business_multiplier_ipi_aliquot']),
            'margin'                 => General::encodeFloat2($data['validatedData']['business_margin']),
            'shipping'               => General::encodeFloat2($data['validatedData']['business_shipping']),
            'discount'               => General::encodeFloat2($data['validatedData']['business_discount']),
            'addition'               => General::encodeFloat2($data['validatedData']['business_addition']),
        ]);

        // After.
        $after = Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first();

        // Auditoria.
        Audit::providerBusinessEdit($data, $before, $after);

        // Mensagem.
        $message = 'Negociação de ' . $data['config']['title'] . ' ' . $after->provider->name . ' atualizada com sucesso.';
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
        // Atualiza Invoiceitem.
        Invoiceitem::editBusiness($data);

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
        // Negociação com o Fornecedor.
        $after = Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->first();

        // Exclui.
        Providerbusiness::where('provider_id', $data['validatedData']['provider_id'])->delete();

        // Auditoria.
        Audit::providerBusinessErase($data, $after);

        // Mensagem.
        $message = 'Negociação de ' . $data['config']['title'] . ' ' .  $data['validatedData']['name'] . ' excluída com sucesso.';
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
