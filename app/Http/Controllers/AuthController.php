<?php

namespace App\Http\Controllers;

use App\Executor\AuthExecutor;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $executorClass = AuthExecutor::class;

    public function login(AuthLoginRequest $request): JsonResponse
    {
        $logged = $this->executor()->login($request->validated());
        if (!$logged) return $this->responseUnauthorized('Invalid credentials');
        $resource = [
            'token' => $logged['token'],
            'user' => new UserResource($logged['user'])
        ];
        return $this->responseSuccess($resource, 'Logged in successfully');
    }

    public function logout(): JsonResponse
    {
        $this->executor()->logout();
        return $this->responseSuccess([], 'Logged out successfully');
    }

    public function me(): JsonResponse
    {
        $user = $this->executor()->me();
        if (!$user) return $this->responseUnauthorized('Not logged in');
        return $this->responseSuccess(new UserResource($user), 'User retrieved successfully');
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $user = $this->executor()->register($request->validated());
        if (!$user) return $this->responseInternalError('Failed to register');
        $resource = [
            'token' => $user['token'],
            'user' => new UserResource($user['user'])
        ];
        return $this->responseCreated($resource, 'Registered successfully');
    }
}
