<?php

namespace App\Executor;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AuthExecutor extends Executor
{
    public function model(): Model
    {
        return new User();
    }

    public function login(array $validated): array|bool
    {
        $ok = auth()->attempt($validated);
        if (!$ok) return false;
        return [
            'token' => auth()->user()->createToken('auth_token')->plainTextToken,
            'user' => auth()->user()
        ];
    }

    public function logout(): void
    {
        auth()->user()->tokens()->delete();
    }

    public function me(): Model|array|null
    {
        return auth()->user();
    }

    public function register(array $validated): array|null
    {
        /** @var User $user */
        $user = $this->model()::query()->create($validated);
        if (!$user) {
            Log::error('Error creating user');
            return null;
        }
        $user->assignRole('user');
        $ok = auth()->attempt([
            'email' => $user->email,
            'password' => $validated['password']
        ]);
        if (!$ok) {
            Log::error('Error creating user');
            return null;
        }
        return [
            'token' => auth()->user()->createToken('auth_token')->plainTextToken,
            'user' => $user
        ];
    }

}
