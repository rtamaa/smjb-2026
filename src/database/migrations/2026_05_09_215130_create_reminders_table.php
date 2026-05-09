<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->timestamp('remind_at');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->string('type')->default('task');
            $table->timestamps();
            
            $table->index(['user_id', 'remind_at', 'is_sent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};