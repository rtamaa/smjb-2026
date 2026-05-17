<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reminder;
use App\Models\Task;

class ReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $task = Task::first();
        
        if ($task) {
         Reminder::create([
            'user_id' => 2,
            'task_id' => $task->id,
            'title' => 'Reminder: Kerjakan Filament Admin Panel',
            'remind_at' => now()->subMinutes(1),
            'type' => 'task',
            'is_sent' => false,
            'sent_at' => null,
            ]);
        }
    }
}
