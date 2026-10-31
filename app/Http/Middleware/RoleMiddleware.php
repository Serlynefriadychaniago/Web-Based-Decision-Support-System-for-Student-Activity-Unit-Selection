<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pilihan guard sesuai role ('admin' atau 'mahasiswa')
        $guard = $role;

        if (!Auth::guard($guard)->check()) {
            abort(403, 'Anda belum login.');
        }

        $user = Auth::guard($guard)->user();
        if ($user->role !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Set default guard untuk request context saat ini
        Auth::shouldUse($guard);

        return $next($request);
    }
}