<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balanceproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'balanceproducts';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'balance_id',
        'product_id',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function balance(){return $this->belongsTo(Balance::class);}
    public function product(){return $this->belongsTo(Product::class);}
}
