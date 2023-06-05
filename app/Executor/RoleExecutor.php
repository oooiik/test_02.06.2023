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
        $this->cacheForgot('index');
        /** @var Role $model */
        $model = $this->model()::query()->create($validated);
        $model->syncPermissions($validated['permissions']);
        return $model;
    }

    public function update(array $validated, $id): Model|null
    {
        $this->cacheForgot('index');
        /** @var ?Role $model */
        $model = $this->cacheGetOrSet('show_' . $id, function () use ($id) {
            return $this->model()::query()->find($id);
        });
        if (is_null($model)) {
            return null;
        }
        $model->update($validated);
        if (isset($validated['permissions'])) {
            $model->syncPermissions($validated['permissions']);
        }
        $this->cacheSet('show_' . $id, $model);
        return $model;
    }
}
