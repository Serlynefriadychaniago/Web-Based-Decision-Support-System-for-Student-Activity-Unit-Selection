<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi pengguna baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buat akun mahasiswa
        |--------------------------------------------------------------------------
        |
        | Setiap akun yang melakukan registrasi melalui halaman umum
        | otomatis diberikan role "mahasiswa".
        |
        */

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        event(new Registered($user));

        /*
        |--------------------------------------------------------------------------
        | Setelah registrasi
        |--------------------------------------------------------------------------
        |
        | Pengguna diarahkan ke halaman login terlebih dahulu.
        |
        */

        return redirect()
            ->route('login')
            ->with('status', 'Registrasi berhasil. Silakan login menggunakan akun Anda.');
    }
}