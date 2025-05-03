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
     * @return array $array
     */
    public static function getLunch(int $employeegroup_id) : array {
        // Inicializa variável.
        $count = 0;
        $employees = [];

        // Percorre os Funcionários do Grupo.
        foreach(Employee::where([
            ['company_id', Auth()->user()->company_id],
            ['employeegroup_id', $employeegroup_id],
            ['status', true],
        ])->get() as $key => $employee):
            if(Clockregistry::where([
                ['employee_id', $employee->id],
                ['date', date('Y-m-d')],
            ])->count() == 2):
                $count++;
                $employees[] = $employee->name;
            endif;
        endforeach;

        return [
            'count'     => $count,
            'employees' => $employees,
        ];
    }

}
