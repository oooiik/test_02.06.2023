<?php

namespace App\Executor;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthExecutor extends Executor
{
    public function model(): Model
    {
        return new User();
    }

    public function login(array $validated): array|bool
    {
        $token = Auth::attempt($validated);
        if (!$token) {
            return false;
        }
        return [
            'token' => $token,
            'user' => auth()->user()
        ];
    }

    public function logout(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
    }

    public function me(): Model|array|null
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    public function register(array $validated): array|null
    {
        /** @var User $user */
        $user = $this->model()::query()->create($validated);
        $user->assignRole('user');
        $ok = Auth::attempt([
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
