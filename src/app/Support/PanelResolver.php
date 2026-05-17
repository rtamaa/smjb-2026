<?php

namespace App\Support;

use App\Models\User;

class PanelResolver
{
    protected static array $panelRoles = [
        'admin' => 'admin',
        'dashboard' => 'siswa',
    ];

    public static function roleForPath(string $path): ?string
    {
        foreach (self::$panelRoles as $prefix => $role) {
            if (str_starts_with($path, $prefix)) {
                return $role;
            }
        }
        return null;
    }

    public static function canAccess(User $user, string $requiredRole): bool
    {
        return $user->hasRole($requiredRole);
    }

    public static function redirectUrl(User $user): string
    {
        if ($user->hasRole('admin')) {
            return '/admin';
        }
        return '/dashboard';
    }
}