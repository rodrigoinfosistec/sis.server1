<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pointevent extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'pointevents';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',

        'event',
        'date',
        'time',
        'code',

        'type',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employee(){return $this->belongsTo(Employee::class);}
}
