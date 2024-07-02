<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'products';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'code',
        'reference',
        'ean',

        'cost',
        'margin',
        'value',

        'signal',
        'amount',

        'productgroup_id',
        'productmeasure_id',

        'status',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function productgroup(){return $this->belongsTo(Productgroup::class);}
    public function productmeasure(){return $this->belongsTo(Productmeasure::class);}
}
