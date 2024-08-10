<?php

namespace App\Filters;

use DeepCopy\Exception\PropertyException;
use Illuminate\Http\Requet;

use App\Filter\Filter;

class UserFilter extends Filter
{
    protected array $allowedOperatorsFields = [
        'value' => ['gt', 'eq', 'lt', 'gte', 'lte', 'ne'],
        'type' => ['eq', 'ne', 'in'],
        'status' => ['eq', 'ne'],
        'created_at' => ['gt', 'eq', 'lt', 'gte', 'lte', 'ne'],
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