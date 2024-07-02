<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productmeasure extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'productmeasures';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',
        'quantity',

        'created_at',
        'updated_at',
    ];
}
