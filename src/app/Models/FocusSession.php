<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rupadana\ApiService\Contracts\HasAllowedFields;
use Rupadana\ApiService\Contracts\HasAllowedFilters;
use Rupadana\ApiService\Contracts\HasAllowedSorts;

class FocusSession extends Model implements HasAllowedFields, HasAllowedFilters, HasAllowedSorts
{
    protected $fillable = [
        'task_id', 'user_id', 'started_at', 'ended_at',
        'duration_target', 'duration_actual', 'is_completed', 'is_cancelled'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_completed' => 'boolean',
        'is_cancelled' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function complete(): void
    {
        $this->update([
            'ended_at' => now(),
            'duration_actual' => $this->started_at->diffInSeconds(now()),
            'is_completed' => true,
        ]);
    }
    
    public function cancel(): void
    {
        $this->update([
            'ended_at' => now(),
            'duration_actual' => $this->started_at->diffInSeconds(now()),
            'is_cancelled' => true,
        ]);
    }

    public static function getAllowedFields(): array
    {
        return ['id', 'task_id', 'user_id', 'started_at', 'ended_at', 'duration_target', 'duration_actual', 'is_completed', 'is_cancelled', 'created_at'];
    }

    public static function getAllowedSorts(): array
    {
        return ['started_at', 'duration_target', 'duration_actual', 'is_completed', 'created_at'];
    }

    public static function getAllowedFilters(): array
    {
        return ['task_id', 'user_id', 'is_completed'];
    }
}