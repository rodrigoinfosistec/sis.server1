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

}
