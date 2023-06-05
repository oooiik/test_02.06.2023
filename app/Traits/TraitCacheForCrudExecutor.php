<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait TraitCacheForCrudExecutor
{
    abstract public function modelName(): string;

    protected function cacheSet(string $method, $data, $ttl = null): void
    {
        Cache::set($this->modelName() . '_' . $method, $data, $ttl);
    }

    protected function cacheGetOrSet(string $method, $callback, $ttl = null): mixed
    {
        $key = $this->modelName() . '_' . $method;
        if (!Cache::has($key)) {
            $this->cacheSet($method, $callback(), $ttl);
        }
        return Cache::get($key);
    }

    protected function cacheForgot(string $method): void
    {
        Cache::forget($this->modelName() . '_' . $method);
    }
}
