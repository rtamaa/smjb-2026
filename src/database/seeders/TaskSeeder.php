<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $siswa = User::where('email', 'siswa@admin.com')->first();
        
        if ($siswa) {
            $tasks = [
                ['title' => 'Belajar Laravel 11', 'description' => 'Mempelajari dasar-dasar Laravel 11', 'focus_minutes' => 45, 'is_completed' => false],
                ['title' => 'Membuat REST API dengan Sanctum', 'description' => 'Implementasi API authentication', 'focus_minutes' => 60, 'is_completed' => false],
                ['title' => 'Belajar Livewire 3', 'description' => 'Membuat komponen dinamis dengan Livewire', 'focus_minutes' => 50, 'is_completed' => false],
                ['title' => 'Filament Admin Panel', 'description' => 'Membuat admin panel dengan FilamentPHP', 'focus_minutes' => 55, 'is_completed' => false],
                ['title' => 'Tailwind CSS Dasar', 'description' => 'Belajar utility-first CSS framework', 'focus_minutes' => 30, 'is_completed' => false],
                ['title' => 'Database Migration & Seeder', 'description' => 'Memahami migration dan seeder', 'focus_minutes' => 40, 'is_completed' => false],
            ];
            
            foreach ($tasks as $task) {
                Task::create(array_merge($task, ['user_id' => $siswa->id]));
            }
            
            $this->command->info('✅ ' . count($tasks) . ' tugas berhasil ditambahkan!');
        }
    }
}