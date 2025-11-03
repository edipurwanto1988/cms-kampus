<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            RolePermissionSeeder::class,
        ]);

        // Create admin user for testing
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@cms.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Assign Super Admin role to the admin user
        $admin->assignRole(\App\Models\Role::where('slug', 'super-admin')->first());

        // Create test user with viewer role
        $viewer = User::create([
            'name' => 'Test User',
            'email' => 'viewer@cms.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // Assign Viewer role to the test user
        $viewer->assignRole(\App\Models\Role::where('slug', 'viewer')->first());

        $this->command->info('Admin user created: admin@cms.com / password');
        $this->command->info('Test user created: viewer@cms.com / password');
    }
}
