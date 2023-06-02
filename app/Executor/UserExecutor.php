<?php

namespace App\Executor;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

class UserExecutor extends Executor
{
    public function model(): Model
    {
        return new User();
    }
}
