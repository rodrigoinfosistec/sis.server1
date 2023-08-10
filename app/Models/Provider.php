<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'providers';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'cnpj',
        'name',
        'nickname',

        'created_at',
        'updated_at',
    ];

}
