<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outputproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'outputproducts';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'output_id',
        'product_id',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function output(){return $this->belongsTo(Output::class);}
    public function product(){return $this->belongsTo(Product::class);}
}
