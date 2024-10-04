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

    /**
     * Define Funcionários do Grupo em almoço.
     * @var int $employeegroup_id
     * 
     * @return int $lunch
     */
    public static function getLunch(int $lunch) : int {
        $lunch = 0;
        $today = date('Y-m-d');

        

        return $lunch;
    }
}
