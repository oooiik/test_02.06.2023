<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@test.com')->first();
        $admin->assignRole('admin');

        $users = User::where('id', '!=', $admin->id)->get();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
    }
}
