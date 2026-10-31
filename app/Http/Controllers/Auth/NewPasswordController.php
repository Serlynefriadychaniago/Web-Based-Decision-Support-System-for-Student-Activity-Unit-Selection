<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    /**
     * Menampilkan halaman reset password.
     */
    public function create(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'request' => $request,
            'token'   => $token,
        ]);
    }


    /**
     * Memproses password baru.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'email' => strtolower(trim($request->email)),
        ]);

        $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'email' => [
                'required',
                'string',
                'email',
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ], [

            'email.required' => 'Email wajib diisi.',

            'email.email' => 'Format email tidak valid.',

            'password.required' => 'Password baru wajib diisi.',

            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            'password.min' => 'Password minimal 8 karakter.',

            'token.required' => 'Token reset password tidak ditemukan.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | RESET PASSWORD
        |--------------------------------------------------------------------------
        */

        $status = Password::reset(

            [
                'email' => $request->email,

                'password' => $request->password,

                'password_confirmation' =>
                    $request->password_confirmation,

                'token' => $request->token,
            ],

            function (User $user, string $password) {

                $user->forceFill([

                    'password' => Hash::make($password),

                    'remember_token' => Str::random(60),

                ])->save();


                event(new PasswordReset($user));
            }
        );


        /*
        |--------------------------------------------------------------------------
        | HASIL RESET
        |--------------------------------------------------------------------------
        */

        if ($status === Password::PASSWORD_RESET) {

            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Password berhasil diubah. Silakan masuk menggunakan password baru.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | TOKEN / EMAIL TIDAK VALID
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $this->translatePasswordStatus($status),
            ]);
    }


    /**
     * Mengubah pesan error Laravel menjadi Bahasa Indonesia.
     */
    private function translatePasswordStatus(string $status): string
    {
        return match ($status) {

            Password::INVALID_TOKEN =>
                'Token reset password tidak valid atau sudah kedaluwarsa.',

            Password::INVALID_USER =>
                'Email tersebut tidak ditemukan.',

            Password::RESET_THROTTLED =>
                'Terlalu banyak percobaan. Silakan coba lagi nanti.',

            default =>
                'Reset password gagal. Silakan coba lagi.',
        };
    }
}