<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clockemployeefunded extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockemployeefundeds';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id',
        'employee_id',

        'allowance',
        'delay',
        'extra',
        'balance',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function clock(){return $this->belongsTo(Clock::class);}
    public function employee(){return $this->belongsTo(Employee::class);}
    
}
