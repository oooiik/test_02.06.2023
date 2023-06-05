<?php

namespace App\Filters;

use Oooiik\LaravelQueryFilter\Filters\QueryFilter;

class StatementFilter extends QueryFilter
{

    public function user_id(int $id = null): void
    {
        $this->builder->where('user_id', $id);
    }
}
