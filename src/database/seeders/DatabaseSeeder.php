<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,      // Buat roles & users
            TaskSeeder::class,      // Buat tasks
            ReminderSeeder::class,  // Buat reminders
        ]);
        
        $this->command->info('✅ SEMUA SEEDER SELESAI!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('Siswa: siswa@admin.com / password');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}