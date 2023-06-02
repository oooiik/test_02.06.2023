<?php

namespace App\Executor;

use App\Models\User;
use App\Traits\TraitCrudExecutorMethods;
use Illuminate\Database\Eloquent\Model;

class UserExecutor extends Executor
{
    use TraitCrudExecutorMethods;
    public function model(): Model
    {
        return new User();
    }
}
