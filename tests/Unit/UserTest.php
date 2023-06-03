<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_factory(): void
    {
        $user = User::factory()->make();
        $this->assertNotNull($user);

        $user = User::factory()->create();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('users', $user->toArray());

        $users = User::factory()->count(3)->create();
        $this->assertCount(3, $users);
    }

    public function test_index_method(): void
    {
        $this->actingAs($this->user);
        $response = $this->getJson('/api/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_store_method_success(): void
    {
        $this->actingAs($this->user);
        $make = User::factory()->make();
        $makeArray = $make->toArray();
        $makeArray['password'] = 'password';
        $makeArray['password_confirmation'] = $makeArray['password'];

        $response = $this->postJson('/api/users', $makeArray);
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('users', $make->makeHidden('email_verified_at')->toArray());
    }

    public function test_store_method_fail(): void
    {
        $this->actingAs($this->user);
        $response = $this->postJson('/api/users', []);
        $response->assertStatus(422);
    }

    public function test_show_method_success(): void
    {
        $this->actingAs($this->user);
        $user = User::factory()->create();

        $response = $this->getJson('/api/users/' . $user->id);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_show_method_fail(): void
    {
        $this->actingAs($this->user);
        $response = $this->getJson('/api/users/0');
        $response->assertStatus(404);
    }

    public function test_update_method_success(): void
    {
        $this->actingAs($this->user);
        $user = User::factory()->create();
        $make = User::factory()->make();

        $response = $this->putJson('/api/users/' . $user->id, $make->toArray());
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('users', array_merge($make->toArray(), ['id' => $user->id]));
    }

    public function test_update_method_fail(): void
    {
        $this->actingAs($this->user);
        $response = $this->putJson('/api/users/0', []);
        $response->assertStatus(404);
    }

    public function test_destroy_method_success(): void
    {
        $this->actingAs($this->user);
        $user = User::factory()->create();

        $response = $this->deleteJson('/api/users/' . $user->id);
        $response->assertStatus(204);

        $response->assertJsonStructure([
            'data' => [],
        ]);

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    public function test_destroy_method_fail(): void
    {
        $this->actingAs($this->user);
        $response = $this->deleteJson('/api/users/0');
        $response->assertStatus(404);
    }

}
