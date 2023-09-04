<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employees';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'pis',
        'name',

        'journey_start_week',
        'journey_end_week',
        'journey_start_saturday',
        'journey_end_saturday',

        'created_at',
        'updated_at',
    ];
    
}
