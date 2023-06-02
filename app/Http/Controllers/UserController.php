<?php

namespace App\Http\Controllers;

use App\Executor\UserExecutor;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $executorClass = UserExecutor::class;

    public function index(): JsonResponse
    {
        $data = $this->executor()->index();
        $collection = UserResource::collection($data);
        return $this->responseSuccess($collection, 'Users retrieved successfully');
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $created = $this->executor()->store($request->validated());
        $resource = new UserResource($created);
        return $this->responseCreated($resource, 'User created successfully');
    }

    public function show(int $id): JsonResponse
    {
        $model = $this->executor()->show($id);
        if (!$model) return $this->responseNotFound('User not found');
        $resource = new UserResource($model);
        return $this->responseSuccess($resource, 'User retrieved successfully');
    }

    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $model = $this->executor()->update($request->validated(), $id);
        if (!$model) return $this->responseNotFound('User not found');
        $resource = new UserResource($model);
        return $this->responseSuccess($resource, 'User updated successfully');
    }

    public function destroy(int $id): JsonResponse
    {
        $success = $this->executor()->destroy($id);
        if (!$success) return $this->responseNotFound('User not found');
        return $this->responseSuccess([], 'User deleted successfully');
    }
}
