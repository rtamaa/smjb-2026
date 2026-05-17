<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Models\Notification;

class SendReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send pending reminders to notifications';

    public function handle()
    {
        $count = 0;
        
        $reminders = Reminder::where('is_sent', false)
            ->where('remind_at', '<=', now())
            ->get();

        foreach ($reminders as $reminder) {
            Notification::create([
                'user_id' => $reminder->user_id,
                'title' => $reminder->title,
                'body' => "Waktunya mengerjakan tugas!",
                'read_at' => null,
            ]);

            $reminder->update([
                'is_sent' => true,
                'sent_at' => now(),
            ]);
            
            $count++;
        }

        $this->info("Sent {$count} reminders");
    }
}