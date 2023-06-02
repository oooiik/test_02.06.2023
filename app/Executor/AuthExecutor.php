<?php

namespace App\Executor;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuthExecutor extends Executor
{
    public function model(): Model
    {
        return new User();
    }

    public function login(array $validated): object|bool
    {
        $token = auth()->attempt($validated);
        if (!$token) return false;
        return (object)[
            'token' => $token,
            'user' => auth()->user()
        ];
    }

    public function logout(): void
    {
        auth()->logout();
    }

    public function me(): Model|array|null
    {
        return auth()->user();
    }

    public function register(array $validated): object|null
    {
        /** @var User $user */
        $user = $this->model()::query()->create($validated);
        if (!$user) return null;
        $user->assignRole('user');
        $token = auth()->attempt($validated);
        return (object)[
            'token' => $token,
            'user' => $user
        ];
    }

}
