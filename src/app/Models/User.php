<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'display_name', 'push_subscription', 'avatar_url',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'push_subscription' => 'array',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('sort_order');
    }
    
    public function focusSessions()
    {
        return $this->hasMany(FocusSession::class);
    }
    
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function getTodayFocusMinutesAttribute()
    {
        return (int) ($this->focusSessions()
            ->whereDate('started_at', today())
            ->where('is_completed', true)
            ->sum('duration_actual') / 60);
    }
    
    public function getTotalFocusMinutesAttribute()
    {
        return (int) ($this->focusSessions()
            ->where('is_completed', true)
            ->sum('duration_actual') / 60);
    }
}