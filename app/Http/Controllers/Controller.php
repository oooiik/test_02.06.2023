<?php

namespace App\Http\Controllers;

use App\Executor\Executor;
use App\Traits\TraitJsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use TraitJsonResponse;

    protected $executorClass; // This is the class name of the executor

    protected function executor(): Executor
    {
        return $this->executorClass::make();
    }

    protected function model(): Model
    {
        return $this->executor()->model();
    }
}
