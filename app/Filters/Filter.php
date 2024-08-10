<?php

namespace App\Filter;

use DeepCopy\Exception\PropertyException;

abstract class Filter
{
    protected array $allowedOperatorsFields = [];

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

        foreach($this->allowedOperatorsFields as $param => $operators){
            $queryOperator = $requst->query()
;        }
    }
}