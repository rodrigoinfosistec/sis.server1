<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeegroup extends Model
{
    use HasFactory;

    /**
     * Nome da tabela.
     */
    protected $table = 'employeegroups';

    /**
     * Campos manipuláveis.
     */
    protected $fillable = [
        'name',

        'status',
        'limit',

        'created_at',
        'updated_at',
    ];
}
