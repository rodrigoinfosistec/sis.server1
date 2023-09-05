<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeevacation extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeevacations';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employee_id',
        'employee_name',

        'date_start',
        'date_end',

        'created_at',
        'updated_at',
    ];
    
}
