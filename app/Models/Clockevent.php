<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clockevent extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockevents';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id',
        'employee_id',

        'event',
        'date',
        'time',
        'code',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function clock(){return $this->belongsTo(Clock::class);}
    public function employee(){return $this->belongsTo(Employee::class);}
}
