<?php
namespace App\Filters;

use App\Filters\Filter;
use DeepCopy\Exception\PropertyException;

class UserFilter extends Filter
{
    protected array $allowedOperatorsFields = [
        'company_id' => ['eq', 'ne', 'in'],
        'company_name' => ['eq', 'ne', 'in'],
        'status' => ['eq'],
        'created_at' => ['gt', 'gte', 'lt', 'lte', 'eq', 'ne', 'in'],
    ];

    protected array $translateOperatorsFields = [
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'eq' => '=',
        'ne' => '!=',
        'in' => 'in',
    ];

    public function filter(Request $request)
    {
        $where = [];
        $whereIn = [];

        if(empty($this->allowedOperatorsFields)){
            throw new PropertyException("Property allowedOperatorsFields is empty");
        }
    }
}