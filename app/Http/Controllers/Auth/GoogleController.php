<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureMahasiswaProfileComplete;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman autentikasi Google OAuth.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Konfigurasi Google Login (GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET) belum diisi pada file .env.',
                ]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback autentikasi setelah persetujuan pengguna dari Google.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            /** @var \Laravel\Socialite\Two\User $googleUser */
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Gagal melakukan login melalui Google: ' . $e->getMessage(),
                ]);
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?: explode('@', $email)[0];

        if (empty($email)) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Google Anda tidak menyediakan alamat email yang valid.',
                ]);
        }

        // =====================================================
        // 1. CARI USER BERDASARKAN GOOGLE_ID ATAU EMAIL
        // =====================================================
        $user = User::where('google_id', $googleId)->first();

        if (!$user) {
            // Jika belum ada berdasarkan google_id, cari berdasarkan email (akun existing)
            $user = User::where('email', $email)->first();

            if ($user) {
                // Tautkan google_id ke akun existing untuk mencegah duplikasi user
                $user->google_id = $googleId;
                $user->save();
            } else {
                // =====================================================
                // 2. BUAT USER MAHASISWA BARU JIKA BELUM ADA
                // =====================================================
                $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode('@', $email)[0]));
                if (empty($baseUsername)) {
                    $baseUsername = 'mhs' . rand(1000, 9999);
                }

                $username = $baseUsername;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $user = User::create([
                    'name'      => $name,
                    'username'  => $username,
                    'email'     => $email,
                    'password'  => Hash::make(Str::random(32)),
                    'role'      => 'mahasiswa',
                    'google_id' => $googleId,
                ]);
            }
        }

        // =====================================================
        // 3. LOGIN MENGGUNAKAN MULTI-GUARD CONTEXT
        // =====================================================
        session()->regenerate();

        if ($user->role === 'admin') {
            Auth::guard('admin')->login($user, true);
            Auth::shouldUse('admin');

            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'mahasiswa') {
            Auth::guard('mahasiswa')->login($user, true);
            Auth::shouldUse('mahasiswa');

            // Periksa kelengkapan 8 field profil mahasiswa menggunakan mekanisme existing
            $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($user->id);

            if (!$profileStats['is_complete']) {
                return redirect()->route('profile.edit')
                    ->with('info', 'Login dengan Google berhasil! Silakan lengkapi profil Anda terlebih dahulu.');
            }

            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        // Jika role tidak dikenali
        Auth::guard('admin')->logout();
        Auth::guard('mahasiswa')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'Role akun pengguna tidak valid.',
        ]);
    }
}
