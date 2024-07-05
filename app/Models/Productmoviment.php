<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productmoviment extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'productmoviments';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'product_id',

        'identification',

        'quantity',

        'user_id',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function product(){return $this->belongsTo(Product::class);}
    public function user(){return $this->belongsTo(User::class);}
}
