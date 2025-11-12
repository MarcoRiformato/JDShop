<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductionUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates the production admin users with secure random passwords.
     * Passwords will be displayed in the console - SAVE THEM!
     */
    public function run(): void
    {
        $this->command->info('Creating production users...');
        $this->command->newLine();

        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $editorRole = Role::where('slug', 'editor')->first();

        if (!$adminRole || !$editorRole) {
            $this->command->error('Roles not found! Please run RoleSeeder first.');
            return;
        }

        // Define users
        $users = [
            [
                'name' => 'Jacopo',
                'email' => 'dandrea@jdeventsrl.com',
                'role_id' => $adminRole->id,
                'role_name' => 'Admin',
            ],
            [
                'name' => 'Sara',
                'email' => 'longo@jdsetup.com',
                'role_id' => $adminRole->id,
                'role_name' => 'Admin',
            ],
            [
                'name' => 'Caterina',
                'email' => 'campana@jdeventsrl.com',
                'role_id' => $adminRole->id,
                'role_name' => 'Admin',
            ],
            [
                'name' => 'Anna',
                'email' => 'villa@jdsetup.com',
                'role_id' => $adminRole->id,
                'role_name' => 'Admin',
            ],
            [
                'name' => 'Magazzino',
                'email' => 'magazzino@jdsetup.com',
                'role_id' => $editorRole->id,
                'role_name' => 'Editor',
            ],
        ];

        $credentials = [];

        foreach ($users as $userData) {
            // Generate secure random password
            $password = Str::random(16);
            
            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($password),
                    'role_id' => $userData['role_id'],
                ]
            );

            $credentials[] = [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $password,
                'role' => $userData['role_name'],
            ];

            $this->command->info("✓ Created user: {$userData['name']} ({$userData['email']})");
        }

        // Display credentials
        $this->command->newLine();
        $this->command->warn('╔════════════════════════════════════════════════════════════════╗');
        $this->command->warn('║  SAVE THESE CREDENTIALS - THEY WILL NOT BE SHOWN AGAIN!       ║');
        $this->command->warn('╚════════════════════════════════════════════════════════════════╝');
        $this->command->newLine();

        foreach ($credentials as $cred) {
            $this->command->line("┌─ {$cred['name']} ({$cred['role']})");
            $this->command->line("│  Email:    {$cred['email']}");
            $this->command->line("│  Password: {$cred['password']}");
            $this->command->line("└─");
            $this->command->newLine();
        }

        $this->command->warn('⚠️  Share these credentials securely with each user!');
        $this->command->warn('⚠️  Users should change their passwords after first login.');
    }
}

