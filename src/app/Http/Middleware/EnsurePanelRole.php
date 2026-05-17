<?php

namespace App\Http\Middleware;

use App\Support\PanelResolver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePanelRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $requiredRole = PanelResolver::roleForPath($request->path());

        if ($requiredRole === null) {
            return $next($request);
        }

        $user = Auth::user();

        if (!PanelResolver::canAccess($user, $requiredRole)) {
            return redirect()->to(PanelResolver::redirectUrl($user));
        }

        return $next($request);
    }
}