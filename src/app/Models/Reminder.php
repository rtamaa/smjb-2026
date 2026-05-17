<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rupadana\ApiService\Contracts\HasAllowedFields;
use Rupadana\ApiService\Contracts\HasAllowedFilters;
use Rupadana\ApiService\Contracts\HasAllowedSorts;

class Reminder extends Model implements HasAllowedFields, HasAllowedFilters, HasAllowedSorts
{
    protected $fillable = [
        'user_id', 'task_id', 'title', 'remind_at', 'is_sent', 'sent_at', 'type'
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public static function getAllowedFields(): array
    {
        return ['id', 'user_id', 'task_id', 'title', 'remind_at', 'is_sent', 'sent_at', 'type', 'created_at'];
    }

    public static function getAllowedSorts(): array
    {
        return ['title', 'remind_at', 'is_sent', 'created_at'];
    }

    public static function getAllowedFilters(): array
    {
        return ['user_id', 'task_id', 'is_sent', 'type'];
    }
}