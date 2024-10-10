<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outproduce extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'outproduces';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'out_id',
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
    public function out(){return $this->belongsTo(Out::class);}
    public function produce(){return $this->belongsTo(Produce::class);}
}
