<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rupadana\ApiService\Contracts\HasAllowedFields;
use Rupadana\ApiService\Contracts\HasAllowedFilters;
use Rupadana\ApiService\Contracts\HasAllowedSorts;

class Task extends Model implements HasAllowedFields, HasAllowedFilters, HasAllowedSorts
{
    protected $fillable = [
        'user_id', 'title', 'description', 'material_link',
        'focus_minutes', 'is_completed', 'completed_at', 'sort_order'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    // ⭐ INI HARUS DI DALAM CLASS
    protected $attributes = [
        'is_completed' => false,
        'sort_order' => 0,
        'completed_at' => null,
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

    public static function getAllowedFields(): array
    {
        return ['id', 'title', 'description', 'material_link', 'focus_minutes', 'is_completed', 'completed_at', 'user_id', 'created_at'];
    }

    public static function getAllowedSorts(): array
    {
        return ['title', 'focus_minutes', 'is_completed', 'created_at', 'completed_at'];
    }

    public static function getAllowedFilters(): array
    {
        return ['title', 'is_completed', 'user_id'];
    }
}