<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoiceefisco extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'invoiceefiscos';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'invoice_id',
        'productgroup_id',

        'icms',

        'value',

        'value_invoice',
        'value_final',

        'ipi_invoice',
        'ipi_final',

        'index',

        'created_at',
        'updated_at'
    ];

    /**
     * Relaciona Models.
     */
    public function invoice(){return $this->belongsTo(Invoice::class);}
    public function productgroup(){return $this->belongsTo(Productgroup::class);}

    /**
     * Valida cadastro.
     * @var array $data
     * 
     * @return bool true
     */
    public static function validateAdd(array $data) : bool {
        $message = null;

        // Nota Fiscal.
        $invoice = Invoice::find($data['validatedData']['invoice_id']);

        // Verifica se já existe eFisco com a tupla [ Nota Fiscal x Grupo de Produto].
        if($invoiceefisco = Invoiceefisco::where(['invoice_id' => $data['validatedData']['invoice_id'], 'productgroup_id' => $data['validatedData']['efisco_productgroup_id']])->first()):
            $message = 'eFisco ' . $invoiceefisco->productgroup->code . '-' . $invoiceefisco->productgroup->origin . ' já cadastrado na ' . $data['config']['title'] .  ' ' . $invoice->number . ' do Fornecedor ' . $invoice->provider->name;
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
     * Cadastra.
     * @var array $data
     * 
     * @return bool true
     */
    public static function add(array $data) : bool {
        // Cadastra.
        Invoiceefisco::create([
            'invoice_id'      => $data['validatedData']['invoice_id'],
            'productgroup_id' => $data['validatedData']['efisco_productgroup_id'],
            'icms'            => General::encodeFloat2($data['validatedData']['efisco_icms']),
            'value'           => General::encodeFloat2($data['validatedData']['efisco_value']),
        ]);

        // After.
        $after = Invoiceefisco::where(['invoice_id' => $data['validatedData']['invoice_id'], 'productgroup_id' => $data['validatedData']['efisco_productgroup_id']])->first();

        // Auditoria.
        Audit::invoiceEfiscoAdd($data, $after);

        // Mensagem.
        $message = 'eFisco ' . $after->productgroup->code . '-' . $after->productgroup->origin . ' cadastrado na ' . $data['config']['title'] . ' ' . $after->invoice->number . ' do Fornecedor ' . $after->invoice->provider->name;
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
        // Exclui.
        Invoiceefisco::find($data['validatedData']['invoiceefisco_id'])->delete();

        // Auditoria.
        Audit::invoiceEfiscoErase($data);

        // Mensagem.
        $message = 'eFisco ' . $data['validatedData']['efisco_productgroup_code'] . '-' . $data['validatedData']['efisco_productgroup_origin'] . ' excluído na ' . $data['config']['title'] . ' ' . $data['validatedData']['invoice_number'] . ' do Fornecedor ' . $data['validatedData']['provider_name'];
        session()->flash('message', $message);
        session()->flash('color', 'success');

        return true;
    }
}
