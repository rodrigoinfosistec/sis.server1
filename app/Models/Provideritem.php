<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Provideritem extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'provideritems';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',
        'code',
        'ean',
        'ncm',
        'cfop',
        'cest',
        'measure',

        'signal',
        'amount',

        'provider_name',
        'provider_id',

        'product_id',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function provider(){return $this->belongsTo(Provider::class);}
    public function product(){return $this->belongsTo(Product::class);}
}
