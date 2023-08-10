<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Invoicecsv extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'invoicecsvs';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'invoice_id',

        'code',
        'reference',
        'ean',
        'name',

        'cost',
        'margin',
        'value',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function invoice(){return $this->belongsTo(Invoice::class);}

}
