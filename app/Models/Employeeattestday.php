<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeeattestday extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeeattestdays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employeeattest_id',

        'date',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employeeattest(){return $this->belongsTo(Employeeattest::class);}

}
