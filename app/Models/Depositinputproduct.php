<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depositinputproduct extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'depositinputproducts';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'depositinput_id',

        'product_id',
        'product_name',

        'quantity',
        'quantity_final',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function depositinput(){return $this->belongsTo(Depositinput::class);}
    public function product(){return $this->belongsTo(Product::class);}
}
