<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clockemployee extends Model
{
   /**
     * Nome da tabela.
     */
    protected $table = 'clockemployees';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'clock_id ',
        'employee_id ',

        'journey_start_week',
        'journey_end_week',
        'journey_start_saturday',
        'journey_end_saturday',

        'delay_total',
        'extra_total',
        'balance_total',

        'note',

        'authorized',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function clock(){return $this->belongsTo(Clock::class);}
    public function employee(){return $this->belongsTo(Employee::class);}
}
