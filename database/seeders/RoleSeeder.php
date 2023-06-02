<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Role::factory()->create([
            'title' => 'admin',
        ]);
        \App\Models\Role::factory()->create([
            'title' => 'user',
        ]);
    }
}
