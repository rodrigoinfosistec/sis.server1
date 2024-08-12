<?php
namespace App\Filters;

use Illuminate\Http\Request;
use DeepCopy\Exception\PropertyException;

abstract class Filter
{
    protected array $allowedOperatorsFields = [];

    protected array $translateOperatorsFields = [
        'eq' => '=',
        'ne' => '!=',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
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

        foreach($this->allowedOperatorsFields as $param => $operators):
            $queryOperator = $request->query($param);
            if($queryOperator):
                var_dump($queryOperator);
                foreach($queryOperator as $operator => $value):
                    if(!in_array($operator, $operators)):
                        throw new Exception("{$param} does not have {$operator} operator");
                    endif;

                    if(str_contains($value, '[')):
                        $whereIn[] = [
                            $param,
                            explode(',', str_replace(['[', ']'], ['', ''], $value)),
                            $value,
                        ];
                    else:
                        $where[] = [
                            $param,
                            $this->allowedOperatorsFields[$operator],
                            $value,
                        ];
                    endif;
                endforeach;
            endif;
        endforeach;

        if(empty($where) && empty($whereIn)):
            return [];
        endif;

        return [
            'where' => $where,
            'whereIn' => $whereIn,
        ];
    }
}