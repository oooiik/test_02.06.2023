<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait TraitCrudExecutorMethods
{
    use TraitCacheForCrudExecutor;

    abstract public function model(): Model;

    public function modelName(): string
    {
        return strtolower(class_basename($this->model()));
    }

    public function index(): Collection|array|null
    {
        return $this->cacheGetOrSet('index', function () {
            return $this->model()::query()->get();
        });
    }

    public function store(array $validated): Model|Builder|Collection
    {
        $this->cacheForgot('index');
        return $this->model()::query()->create($validated);
    }

    public function show($id): Model|Collection|Builder|array|null
    {
        return $this->cacheGetOrSet('show_' . $id, function () use ($id) {
            return $this->model()::query()->find($id);
        });
    }

    public function update($validated, $id): Model|Collection|Builder|array|null
    {
        $this->cacheForgot('index');
        $model = $this->cacheGetOrSet('show_' . $id, function () use ($id) {
            return $this->model()::query()->find($id);
        });
        if (!$model) {
            return null;
        }
        $model->update($validated);
        $this->cacheSet('show_' . $id, $model);
        return $model;
    }

    public function destroy($id): bool|null
    {
        $this->cacheForgot('index');
        $model = $this->cacheGetOrSet('show_' . $id, function () use ($id) {
            return $this->model()::query()->find($id);
        });
        if (!$model) {
            return null;
        }
        $this->cacheForgot('show_' . $id);
        return $model->delete();
    }
}
