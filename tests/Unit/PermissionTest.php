<?php

namespace Tests\Unit;

use Tests\TestCase;

class PermissionTest extends TestCase
{
    public function test_index_method()
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

    public function test_permission_show()
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
}
