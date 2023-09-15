<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clockfunded extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockfundeds';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id',

        'start',
        'end',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function clock(){return $this->belongsTo(Clock::class);}

    
}
