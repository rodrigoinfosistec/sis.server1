<?php
namespace App\Filters;

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
        'like' => 'like',
    ];

    public function filter(Request $request)
    {
        $where = [];
        $whereNot = [];
        $whereIn = [];
        $whereNotIn = [];

        if(empty($this->allowedOperatorsFields)){
            throw new PropertyException("Property allowedOperatorsFields is empty");
        }

        foreach($this->allowedOperatorsFields as $param => $operators){
            $queryOperator = $request->query();
        }
    }
}