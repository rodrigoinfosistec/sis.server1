<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inproduce extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'inproduces';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'in_id',
        'produce_id',
        'produce_name',

        'quantity_old',
        'quantity',
        'quantity_diff',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function in(){return $this->belongsTo(In::class);}
    public function produce(){return $this->belongsTo(Produce::class);}
}
