<?php

namespace Tests\Unit;

use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_index_method_success()
    {
        $this->actingAs($this->user);
        $response = $this->getJson(route('permissions.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'model',
                    'action',
                ],
            ],
        ]);
    }

    public function test_index_method_fail()
    {
        $response = $this->getJson(route('permissions.index'));
        $response->assertStatus(401);
    }

    public function test_show_method_success()
    {
        $this->actingAs($this->user);
        $response = $this->getJson(route('permissions.show', ['permission' => 1]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'model',
                'action',
            ],
        ]);
    }

    public function test_show_method_fail()
    {
        $response = $this->getJson(route('permissions.show', ['permission' => 1]));
        $response->assertStatus(401);
    }
}
