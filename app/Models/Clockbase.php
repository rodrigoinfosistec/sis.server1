<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clockbase extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockdays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',

        'start',
        'end',

        'time',

        'description',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employee(){return $this->belongsTo(Employee::class);}
}
