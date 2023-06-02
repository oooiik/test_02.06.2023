<?php

namespace App\Executor;

use Illuminate\Database\Eloquent\Model;
abstract class Executor
{
    abstract public function model(): Model;
    public static function make(): static
    {
        return new static();
    }
}
