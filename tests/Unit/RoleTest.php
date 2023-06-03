<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    // test factory
    public function test_role_factory()
    {
        $role = Role::factory()->create();
        $this->assertNotEmpty($role);
        $this->assertDatabaseHas('roles', ['title' => $role->title]);
    }

    public function test_role_has_permissions()
    {
        /** @var Role $role */
        $role = Role::factory()->create();
        $permissions = Permission::query()->limit(3)->get();
        $role->syncPermissions($permissions);
        $this->assertTrue($role->hasPermission($permissions[0]));
        $this->assertTrue($role->hasPermission($permissions[1]));
        $this->assertTrue($role->hasPermission($permissions[2]));
    }

    public function test_role_has_not_permission()
    {
        /** @var Role $role */
        $role = Role::factory()->create();
        $permission = Permission::first();
        $this->assertFalse($role->hasPermission($permission));
    }

    public function test_index_method()
    {
        $this->actingAs($this->user);
        $response = $this->getJson(route('roles.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'permissions',
                ],
            ],
        ]);
    }

    public function test_store_method()
    {
        $this->actingAs($this->user);
        $response = $this->postJson(route('roles.store'), [
            'title' => 'test',
            'permissions' => Permission::limit(3)->get()->pluck('id'),
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'permissions',
            ],
        ]);
        $this->assertDatabaseHas('roles', ['title' => 'test']);
    }

    public function test_show_method()
    {
        $this->actingAs($this->user);
        $role = Role::factory()->create();
        $response = $this->getJson(route('roles.show', $role->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'permissions',
            ],
        ]);
    }

    public function test_update_method()
    {
        $this->actingAs($this->user);
        $role = Role::factory()->create();
        $response = $this->putJson(route('roles.update', $role->id), [
            'title' => 'test',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'permissions',
            ],
        ]);
        $this->assertDatabaseHas('roles', ['title' => 'test']);
    }

    public function test_destroy_method()
    {
        $this->actingAs($this->user);
        $role = Role::factory()->create();
        $response = $this->deleteJson(route('roles.destroy', $role->id));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
