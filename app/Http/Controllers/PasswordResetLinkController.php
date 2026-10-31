<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Halaman Lupa Password
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses Lupa Password
     *
     * Mode lokal:
     * - cek email
     * - buat token
     * - simpan token ke password_reset_tokens
     * - langsung redirect ke halaman reset password
     *
     * Tidak menggunakan email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari user berdasarkan email
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            strtolower(trim($request->email))
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Jika email tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email tersebut tidak terdaftar.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Buat token reset password
        |--------------------------------------------------------------------------
        |
        | Token plaintext akan digunakan untuk URL.
        | Laravel otomatis menyimpan versi hash-nya
        | di tabel password_reset_tokens.
        |
        */

        $token = Password::broker()->createToken($user);

        /*
        |--------------------------------------------------------------------------
        | Langsung ke halaman Reset Password
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'password.reset',
            [
                'token' => $token,
                'email' => $user->email,
            ]
        );
    }
}