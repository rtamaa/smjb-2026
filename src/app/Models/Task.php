<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'material_link',
        'focus_minutes', 'is_completed', 'completed_at', 'sort_order'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function focusSessions(): HasMany
    {
        return $this->hasMany(FocusSession::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }
    
    public function complete(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }
    
    public function getTotalFocusMinutesAttribute(): int
    {
        return (int) ($this->focusSessions()
            ->where('is_completed', true)
            ->sum('duration_actual') / 60);
    }
}