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

    public function login(array $validated): array|bool
    {
        $token = auth()->attempt($validated);
        if (!$token) return false;
        return [
            'token' => $token,
            'user' => auth()->user()
        ];
    }

    public function logout(): bool
    {
        auth()->logout();
        return true;
    }

    public function me(): Model|array|null
    {
        return auth()->user();
    }

    public function register(array $validated): Model|array|null
    {
        /** @var User $user */
        $user = $this->model()::query()->create($validated);
        if (!$user) return null;
        $user->assignRole('user');
        return $this->login([
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);
    }

}
