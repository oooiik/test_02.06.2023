<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Role $admin */
        $admin = Role::where('title', 'admin')->first();
        $admin->syncPermissions(Permission::all());

        /** @var Role $user */
        $user = Role::where('title', 'user')->first();
        $user->syncPermissions(
            Permission::whereIn('action', [
                'create-my',
                'read-my',
                'update-my',
                'delete-my',
            ])->get()
        );
    }
}
