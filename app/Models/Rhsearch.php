<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Rhsearch extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'rhsearches';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',
        'link',
        'icon',

        'status',

        'created_at',
        'updated_at',
    ];
}
