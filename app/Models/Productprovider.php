<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productprovider extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'productproviders';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'product_id',
        'product_code',

        'provider_id',
        'provider_code',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function product(){return $this->belongsTo(Product::class);}
    public function provider(){return $this->belongsTo(Provider::class);}
}
