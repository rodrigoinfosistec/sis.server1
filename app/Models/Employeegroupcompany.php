<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeegroupcompany extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'employeegroupcompanies';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'employeegroup_id',
        'company_id',

        'limit',

        'created_at',
        'updated_at',
    ];

    /**
     * Relaciona Models.
     */
    public function employeegroup(){return $this->belongsTo(Employeegroup::class);}
    public function company(){return $this->belongsTo(Company::class);}
}
