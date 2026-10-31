<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetAuthGuard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = ltrim($request->path(), '/');

        if (str_starts_with($path, 'admin')) {
            if (Auth::guard('admin')->check()) {
                Auth::shouldUse('admin');
            }
        } elseif (str_starts_with($path, 'mahasiswa')) {
            if (Auth::guard('mahasiswa')->check()) {
                Auth::shouldUse('mahasiswa');
            }
        } else {
            // Halaman umum (seperti /profile, /, dll)
            $referer = $request->header('referer', '');
            
            if (str_contains($referer, '/mahasiswa') && Auth::guard('mahasiswa')->check()) {
                Auth::shouldUse('mahasiswa');
            } elseif (str_contains($referer, '/admin') && Auth::guard('admin')->check()) {
                Auth::shouldUse('admin');
            } elseif (Auth::guard('mahasiswa')->check() && !Auth::guard('admin')->check()) {
                Auth::shouldUse('mahasiswa');
            } elseif (Auth::guard('admin')->check() && !Auth::guard('mahasiswa')->check()) {
                Auth::shouldUse('admin');
            } elseif (Auth::guard('mahasiswa')->check()) {
                Auth::shouldUse('mahasiswa');
            } elseif (Auth::guard('admin')->check()) {
                Auth::shouldUse('admin');
            }
        }

        return $next($request);
    }
}
