<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
class AuthTest extends TestCase
{
    public function test_login_method_success(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['token', 'user']
        ]);
    }

    public function test_login_method_fail(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong_password'
        ]);
        $response->assertStatus(401);
    }

    public function test_register_method_success(): void
    {
        $make = User::factory()->make();

        $response = $this->postJson('/api/auth/register', [
            'name' => $make->name,
            'email' => $make->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => $make->phone,
            'address' => $make->address
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => $make->name,
            'email' => $make->email,
            'phone' => $make->phone,
            'address' => $make->address
        ]);
    }

    public function test_register_method_fail(): void
    {
        $response = $this->postJson('/api/auth/register', []);
        $response->assertStatus(422);

        $make = User::factory()->create();

        $response = $this->postJson('/api/auth/register', [
            'name' => $make->name,
            'email' => $make->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => $make->phone,
            'address' => $make->address
        ]);
        $response->assertStatus(422);
    }

    public function test_me_method_success(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/auth/me');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at']
        ]);
    }

    public function test_me_method_fail(): void
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);
    }

    public function test_logout_method_success(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/auth/logout');
        $response->assertStatus(200);
    }
}
