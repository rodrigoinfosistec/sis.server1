<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Producemeasure extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'producemeasures';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'status',

        'created_at',
        'updated_at',
    ];
}
