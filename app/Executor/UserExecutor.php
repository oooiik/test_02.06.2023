<?php

namespace App\Executor;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitCrudExecutorMethods;

class UserExecutor extends Executor
{
    use TraitCrudExecutorMethods;

    public function model(): Model
    {
        return new User();
    }
}
