<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [];

        $models = [
            'User',
            'Role',
        ];

        $actions = [
            'create',
            'read',
            'update',
            'delete',
        ];

        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissions[] = [
                    'model' => strtolower($model),
                    'action' => $action,
                ];
            }
        }

        \App\Models\Permission::insert($permissions);
    }
}
