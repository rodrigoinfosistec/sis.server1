<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeeabsenceday extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeeabsencedays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employeeabsence_id',
        'employee_id',

        'date',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employeeabsence(){return $this->belongsTo(Employeeabsence::class);}
    public function employee(){return $this->belongsTo(Employee::class);}

}
