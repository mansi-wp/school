<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request ensuring user has required role.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super Admin and Admin user_type bypasses role check
        if ($user->user_type === 'admin' || $user->hasRole('super-admin')) {
            return $next($request);
        }

        if (!$user->hasRole($role)) {
            abort(403, 'Unauthorized access: role not granted.');
        }

        return $next($request);
    }
}
