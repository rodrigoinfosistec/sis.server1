<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productdeposit extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'productdeposits';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'product_id',

        'deposit_id',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function product(){return $this->belongsTo(Product::class);}
    public function deposit(){return $this->belongsTo(Deposit::class);}
}
