<?php

namespace App\Executor;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TraitCrudExecutorMethods;

class RoleExecutor extends Executor
{
    use TraitCrudExecutorMethods;

    public function model(): Model
    {
        return new Role();
    }

    public function store(array $validated): Model
    {
        /** @var Role $model */
        $model = $this->model()::query()->create($validated);
        $model->syncPermissions($validated['permissions']);
        return $model;
    }

    public function update(array $validated, $id): Model|null
    {
        /** @var Role $model */
        $model = $this->model()::query()->find($id);
        if (!$model) return null;
        $model->update($validated);
        if (isset($validated['permissions'])) $model->syncPermissions($validated['permissions']);
        return $model;
    }
}
