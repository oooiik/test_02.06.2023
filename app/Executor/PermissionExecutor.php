<?php

namespace App\Executor;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitCrudExecutorMethods;

class PermissionExecutor extends Executor
{
    public function model(): Model
    {
        return new Permission();
    }

    public function index(): array|Collection
    {
        return $this->model()->all();
    }

    public function show(int $id): ?Model
    {
        return $this->model()->query()->find($id);
    }
}
