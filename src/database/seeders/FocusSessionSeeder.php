<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FocusSession;

class FocusSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FocusSession::create([
            'task_id' => 1,
            'user_id' => 2,
            'started_at' => '2026-05-14 13:00:00',
            'ended_at' => '2026-05-14 13:02:00',
            'duration_target' => 120,
            'duration_actual' => 120,
            'is_completed' => true,
            'is_cancelled' => false,
        ]);

        $this->command->info('✅ 2 focus sessions berhasil dibuat!');
        $this->command->info('   - 1 belum selesai');
        $this->command->info('   - 1 sudah selesai (2 menit)');
    }
}