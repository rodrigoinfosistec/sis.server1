<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'holidays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'user_id',

        'date',
        'week',
        'year',
        'name',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function user(){return $this->belongsTo(User::class);}
}
