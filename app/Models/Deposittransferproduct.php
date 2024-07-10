<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposittransferproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'deposittransfers';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'deposittransfer_id',
        'product_id',
        'product_name',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function deposittransfer(){return $this->belongsTo(Deposittransfer::class);}
    public function product(){return $this->belongsTo(Product::class);}
}
