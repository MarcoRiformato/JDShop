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
     * 
     * IMPORTANT: Set ADMIN_PASSWORD environment variable before running this seeder.
     * Example: ADMIN_PASSWORD=your_secure_password php artisan db:seed
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        
        // Only create admin user if ADMIN_EMAIL and ADMIN_PASSWORD are set
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');
        
        if (!$adminEmail || !$adminPassword) {
            $this->command->warn('Skipping user creation: ADMIN_EMAIL and ADMIN_PASSWORD environment variables not set.');
            $this->command->warn('To create an admin user, set these variables in your .env file and run the seeder again.');
            return;
        }

        // Create admin user
        $user = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Admin'),
                'password' => $adminPassword,
                'role_id' => $adminRole?->id,
            ]
        );
        
        if ($user->wasRecentlyCreated) {
            $this->command->info("Admin user created: {$adminEmail}");
        } else {
            $this->command->info("Admin user already exists: {$adminEmail}");
        }
    }
}

