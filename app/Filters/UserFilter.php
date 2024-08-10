<?php

namespace App\Filters;

use DeepCopy\Exception\PropertyException;
use Illuminate\Http\Requet;

use App\Filters\Filter;

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