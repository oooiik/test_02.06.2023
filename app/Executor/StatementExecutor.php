<?php

namespace App\Executor;

use App\Models\Statement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitCrudExecutorMethods;

class StatementExecutor extends Executor
{
    use TraitCrudExecutorMethods;

    public function model(): Model
    {
        return new Statement();
    }
}
