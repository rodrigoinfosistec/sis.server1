<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeelicenseday extends Model
{
    /**
     * Nome da tabela.
     */
    protected $table = 'employeelicensedays';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employeelicense_id',
        'employee_id',

        'date',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employeelicense(){return $this->belongsTo(Employeelicense::class);}
    public function employee(){return $this->belongsTo(Employee::class);}

}
