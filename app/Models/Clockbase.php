<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Clockbase extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'clockbases';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'user_id',
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
    public function user(){return $this->belongsTo(User::class);}
    public function employee(){return $this->belongsTo(Employee::class);}
}
