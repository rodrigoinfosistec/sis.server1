<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeevacationday extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeevacationdays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employeevacation_id',
        'employee_id',

        'date',

        'created_at',
        'updated_at',
    ];
    
    /**
     * Relaciona Models.
     */
    public function employeevacation(){return $this->belongsTo(Employeevacation::class);}
    public function employee(){return $this->belongsTo(Employee::class);}

}
