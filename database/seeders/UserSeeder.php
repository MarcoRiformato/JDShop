<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $editorRole = Role::where('slug', 'editor')->first();

        // Create super admin user
        User::firstOrCreate(
            ['email' => 'admin@jdshop.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole?->id,
            ]
        );

        // Create regular admin user
        User::firstOrCreate(
            ['email' => 'manager@jdshop.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password'),
                'role_id' => $adminRole?->id,
            ]
        );

        // Create editor user
        User::firstOrCreate(
            ['email' => 'editor@jdshop.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('password'),
                'role_id' => $editorRole?->id,
            ]
        );
    }
}

