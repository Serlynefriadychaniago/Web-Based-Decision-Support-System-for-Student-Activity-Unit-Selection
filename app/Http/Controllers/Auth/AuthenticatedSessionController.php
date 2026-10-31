<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(Request $request): View|RedirectResponse
    {
        // Jika kedua guard (admin dan mahasiswa) sudah terautentikasi, baru redirect.
        // Jika baru salah satu, izinkan halaman login agar tab lain bisa login role lain.
        if (Auth::guard('admin')->check() && Auth::guard('mahasiswa')->check()) {
            return redirect()->route('landing');
        }

        return view('auth.login');
    }

    /**
     * Memproses login pengguna.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Melakukan autentikasi kredensial (email & password)
        $request->authenticate();

        // Ambil user dari database berdasarkan email yang divalidasi
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        // Regenerate session ID untuk keamanan
        $request->session()->regenerate();

        // =====================================================
        // LOGIN KEPADA GUARD KHUSUS BERDASARKAN ROLE
        // =====================================================

        if ($user->role === 'admin') {
            Auth::guard('admin')->login($user, $request->boolean('remember'));
            Auth::shouldUse('admin');

            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'mahasiswa') {
            Auth::guard('mahasiswa')->login($user, $request->boolean('remember'));
            Auth::shouldUse('mahasiswa');

            return redirect()->route('mahasiswa.dashboard');
        }

        // =====================================================
        // JIKA ROLE TIDAK DIKENALI
        // =====================================================

        Auth::guard('admin')->logout();
        Auth::guard('mahasiswa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Role pengguna tidak dikenali.',
            ]);
    }

    /**
     * Logout pengguna secara selektif per-guard.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $referer = $request->header('referer', '');
        $isTargetingAdmin = str_contains($referer, '/admin') || $request->input('guard') === 'admin';
        $isTargetingMahasiswa = str_contains($referer, '/mahasiswa') || $request->input('guard') === 'mahasiswa';

        if ($isTargetingAdmin) {
            Auth::guard('admin')->logout();
        } elseif ($isTargetingMahasiswa) {
            Auth::guard('mahasiswa')->logout();
        } else {
            // Jika tidak dapat dipastikan dari referer, cek guard mana yang aktif
            if (Auth::guard('admin')->check() && !Auth::guard('mahasiswa')->check()) {
                Auth::guard('admin')->logout();
            } elseif (Auth::guard('mahasiswa')->check() && !Auth::guard('admin')->check()) {
                Auth::guard('mahasiswa')->logout();
            } else {
                // Logout keduanya jika tidak spesifik
                Auth::guard('admin')->logout();
                Auth::guard('mahasiswa')->logout();
            }
        }

        // Jika salah satu guard masih aktif (Admin atau Mahasiswa masih ter-login),
        // JANGAN invalidate seluruh session data, hanya regenerate CSRF token!
        if (Auth::guard('admin')->check() || Auth::guard('mahasiswa')->check()) {
            $request->session()->regenerateToken();
        } else {
            // Jika tidak ada guard yang aktif lagi, baru invalidate session total
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login');
    }
}