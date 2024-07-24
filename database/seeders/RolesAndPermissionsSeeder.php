<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'edit post']);
        Permission::create(['name' => 'delete post']);
        Permission::create(['name' => 'publish post']);
        Permission::create(['name' => 'create post']);

        // Create roles and assign existing permissions
        $role = Role::create(['name' => 'support admin']);
        $role->givePermissionTo('create post');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo([
            'edit post',
            'publish post',
            'create post',
            'delete post',
        ]);

        $role = Role::create(['name' => 'super-admin']);
        // Gets all permissions via Gate::before rule; see AuthServiceProvider
    }
}
