<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Beri semua permission ke role admin
        $admin = Role::findByName('admin');
        
        if ($admin) {
            $permissions = Permission::all();
            $admin->syncPermissions($permissions);
            $this->command->info('✅ Admin diberikan ' . $permissions->count() . ' permission!');
        } else {
            $this->command->error('❌ Role admin tidak ditemukan!');
        }
    }
}
