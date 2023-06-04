<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait TraitCrudExecutorMethods
{
    abstract public function model(): Model;

    public function index(): Collection
    {
        return $this->model()::all();
    }

    public function store(array $validated): Model|Builder|Collection
    {
        return $this->model()::query()->create($validated);
    }

    public function show($id): Model|Collection|Builder|array|null
    {
        return $this->model()::query()->find($id);
    }

    public function update($validated, $id): Model|Collection|Builder|array|null
    {
        $model = $this->model()::query()->find($id);
        if (!$model) {
            return null;
        }
        $model->update($validated);
        return $model;
    }

    public function destroy($id): bool|null
    {
        $model = $this->model()::query()->find($id);
        return $model?->delete();
    }
}
