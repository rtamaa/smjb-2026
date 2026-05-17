<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'siswa']);
        
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Administrator', 'display_name' => 'Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');
        
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@admin.com'],
            ['name' => 'Siswa', 'display_name' => 'Siswa', 'password' => bcrypt('password')]
        );
        $siswa->assignRole('siswa');
        
        $this->command->info('✅ Roles dan users berhasil dibuat!');
    }
}