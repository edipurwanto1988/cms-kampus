<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'description' => 'Can view the dashboard'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Can manage users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'description' => 'Can manage roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'description' => 'Can manage permissions'],
            ['name' => 'View Reports', 'slug' => 'view-reports', 'description' => 'Can view reports'],
            ['name' => 'Edit Content', 'slug' => 'edit-content', 'description' => 'Can edit content'],
            ['name' => 'Publish Content', 'slug' => 'publish-content', 'description' => 'Can publish content'],
            ['name' => 'Delete Content', 'slug' => 'delete-content', 'description' => 'Can delete content'],
            ['name' => 'Access Pages', 'slug' => 'access-pages', 'description' => 'Can access http://127.0.0.1:8000/pages'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['description']
                ]
            );
        }

        // Create roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrative access'],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Content editor access'],
            ['name' => 'Viewer', 'slug' => 'viewer', 'description' => 'Read-only access'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'description' => $role['description']
                ]
            );
        }

        // Assign permissions to roles
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $admin = Role::where('slug', 'admin')->first();
        $editor = Role::where('slug', 'editor')->first();
        $viewer = Role::where('slug', 'viewer')->first();

        // Super Admin gets all permissions
        $superAdmin->permissions()->sync(Permission::all());

        // Admin permissions
        $admin->permissions()->sync([
            Permission::where('slug', 'view-dashboard')->first()->id,
            Permission::where('slug', 'manage-users')->first()->id,
            Permission::where('slug', 'manage-roles')->first()->id,
            Permission::where('slug', 'view-reports')->first()->id,
            Permission::where('slug', 'edit-content')->first()->id,
            Permission::where('slug', 'publish-content')->first()->id,
            Permission::where('slug', 'delete-content')->first()->id,
            Permission::where('slug', 'access-pages')->first()->id,
        ]);

        // Editor permissions
        $editor->permissions()->sync([
            Permission::where('slug', 'view-dashboard')->first()->id,
            Permission::where('slug', 'edit-content')->first()->id,
            Permission::where('slug', 'publish-content')->first()->id,
            Permission::where('slug', 'access-pages')->first()->id,
        ]);

        // Viewer permissions
        $viewer->permissions()->sync([
            Permission::where('slug', 'view-dashboard')->first()->id,
            Permission::where('slug', 'view-reports')->first()->id,
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}