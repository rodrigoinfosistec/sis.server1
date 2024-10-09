<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventoryproduce extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'inventoryproduces';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'inventory_id',
        'produce_id',

        'quantity',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function inventory(){return $this->belongsTo(Inventory::class);}
    public function produce(){return $this->belongsTo(Produce::class);}
}
