<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Statement;

class StatementTest extends TestCase
{
    // test factory
    public function test_statement_factory(): void
    {
        $statement = Statement::factory()->create();
        $this->assertNotEmpty($statement);
        $this->assertDatabaseHas('statements', ['title' => $statement->title]);
    }

    public function test_index_method_success(): void
    {
        $this->actingAs($this->user);
        $response = $this->getJson(route('statements.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'number',
                    'date',
                    'user_id',
                ],
            ],
        ]);
    }

    public function test_index_method_fail(): void
    {
        $response = $this->getJson(route('statements.index'));
        $response->assertStatus(401);
    }

    public function test_store_method_success(): void
    {
        $this->actingAs($this->user);
        $makeArray = Statement::factory()->make([
            'user_id' => $this->user->id,
        ])->toArray();
        $makeArray['date'] = $makeArray['date']->format('Y-m-d H:i:s');
        $response = $this->postJson(route('statements.store'), $makeArray);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'number',
                'date',
                'user_id',
            ],
        ]);
    }

    public function test_store_method_fail_401(): void
    {
        $response = $this->postJson(route('statements.store'), []);
        $response->assertStatus(401);
    }

    public function test_store_method_fail_422(): void
    {
        $this->actingAs($this->user);
        $response = $this->postJson(route('statements.store'), []);
        $response->assertStatus(422);
    }

    public function test_store_method_fail_422_2(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $makeArray = Statement::factory()->make([
            'user_id' => $this->user->id,
        ])->toArray();
        $makeArray['date'] = $makeArray['date']->format('Y-m-d H:i:s');
        $response = $this->postJson(route('statements.store'), $makeArray);
        $response->assertStatus(422);
    }

    public function test_show_method_success(): void
    {
        $this->actingAs($this->user);
        $statement = Statement::factory()->create();
        $response = $this->getJson(route('statements.show', $statement->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'number',
                'date',
                'user_id',
            ],
        ]);
    }

    public function test_show_method_fail_401(): void
    {
        $statement = Statement::factory()->create();
        $response = $this->getJson(route('statements.show', $statement->id));
        $response->assertStatus(401);
    }

    public function test_show_method_fail_403(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $statement = Statement::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->getJson(route('statements.show', $statement->id));
        $response->assertStatus(403);
    }

    public function test_update_method_success(): void
    {
        $this->actingAs($this->user);
        $statement = Statement::factory()->create();
        $makeArray = Statement::factory()->make()->toArray();
        $makeArray['date'] = $makeArray['date']->format('Y-m-d H:i:s');
        $response = $this->putJson(route('statements.update', $statement->id), $makeArray);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'number',
                'date',
                'user_id',
            ],
        ]);
    }

    public function test_update_method_fail_401(): void
    {
        $statement = Statement::factory()->create();
        $response = $this->putJson(route('statements.update', $statement->id), []);
        $response->assertStatus(401);
    }

    public function test_update_method_fail_403(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $statement = Statement::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->putJson(route('statements.update', $statement->id), []);
        $response->assertStatus(403);
    }

    public function test_update_method_fail_422(): void
    {
        $this->actingAs($this->user);
        $statement = Statement::factory()->create();
        $response = $this->putJson(route('statements.update', $statement->id), [
            'title' => '',
        ]);
        $response->assertStatus(422);
    }

    public function test_destroy_method_success(): void
    {
        $this->actingAs($this->user);
        $statement = Statement::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->deleteJson(route('statements.destroy', $statement->id));
        $response->assertStatus(204);
    }

    public function test_destroy_method_fail_401(): void
    {
        $statement = Statement::factory()->create();
        $response = $this->deleteJson(route('statements.destroy', $statement->id));
        $response->assertStatus(401);
    }

    public function test_destroy_method_fail_403(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $statement = Statement::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $response = $this->deleteJson(route('statements.destroy', $statement->id));
        $response->assertStatus(403);
    }
}
