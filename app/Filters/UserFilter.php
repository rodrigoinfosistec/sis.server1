<?php
namespace App\Filters;

use App\Filters\Filter;
use DeepCopy\Exception\PropertyException;

class UserFilter extends Filter
{
    protected array $allowedOperatorsFields = [
        'company_id' => ['eq', 'ne', 'in'],
        'company_name' => ['eq', 'ne', 'in', 'like'],
        'usergroup_id' => ['eq', 'ne', 'in'],
        'usergroup_name' => ['eq', 'ne', 'in', 'like'],
        'name' => ['eq', 'ne', 'in', 'like'],
        'email' => ['like'],
        'status' => ['eq', 'ne', 'in', 'like'],
        'created_at' => ['gt', 'gte', 'lt', 'lte', 'eq', 'ne', 'in'],
    ];
}