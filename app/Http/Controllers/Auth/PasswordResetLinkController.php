<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Menampilkan halaman "Lupa Password".
     */
    public function create()
    {
        return view('auth.forgot-password');
    }


    /**
     * Membuat token reset dan langsung membuka
     * halaman reset password.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | NORMALISASI EMAIL
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'email' => strtolower(trim($request->email)),
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI EMAIL
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
            ],
        ], [

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CARI USER
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'email',
            $request->email
        )->first();


        /*
        |--------------------------------------------------------------------------
        | JIKA EMAIL TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            return back()
                ->withInput()
                ->withErrors([
                    'email' =>
                        'Email tersebut tidak terdaftar dalam sistem.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | BUAT TOKEN
        |--------------------------------------------------------------------------
        */

        $token = Password::broker()->createToken($user);


        /*
        |--------------------------------------------------------------------------
        | LANGSUNG KE HALAMAN RESET PASSWORD
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