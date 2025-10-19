<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full system access with all permissions',
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access to manage products and content',
            ],
            [
                'name' => 'Editor',
                'slug' => 'editor',
                'description' => 'Can create and edit products',
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to admin panel',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}

