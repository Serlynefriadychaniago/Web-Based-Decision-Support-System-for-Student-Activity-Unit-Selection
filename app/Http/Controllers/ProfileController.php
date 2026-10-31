<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Middleware\EnsureMahasiswaProfileComplete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Ambil user terautentikasi berdasarkan context guard & referer.
     */
    protected function getActiveUser(Request $request)
    {
        $referer = $request->header('referer', '');

        if (str_contains($referer, '/mahasiswa') && Auth::guard('mahasiswa')->check()) {
            return Auth::guard('mahasiswa')->user();
        }

        if (str_contains($referer, '/admin') && Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        }

        if (Auth::guard('mahasiswa')->check() && !Auth::guard('admin')->check()) {
            return Auth::guard('mahasiswa')->user();
        }

        if (Auth::guard('admin')->check() && !Auth::guard('mahasiswa')->check()) {
            return Auth::guard('admin')->user();
        }

        return $request->user() ?: (Auth::guard('mahasiswa')->user() ?: Auth::guard('admin')->user());
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $this->getActiveUser($request);
        $mahasiswa = null;

        if ($user && $user->role === 'mahasiswa') {
            // Selalu ambil data mahasiswa berbasis Auth::user() untuk mencegah IDOR
            $mahasiswa = DB::table('mahasiswa')
                ->where('user_id', $user->id)
                ->first();
        }

        return view('profile.edit', [
            'user'      => $user,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $this->getActiveUser($request);

        // 1. Update data User (nama & email)
        $namaLengkap = $request->input('nama') ?: $request->input('name');
        $user->name = $namaLengkap;
        $user->email = $request->input('email');

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 2. Update data Mahasiswa jika role pengguna adalah mahasiswa
        if ($user->role === 'mahasiswa') {
            $mahasiswaRec = DB::table('mahasiswa')
                ->where('user_id', $user->id)
                ->first();

            $jkVal = $request->input('jenis_kelamin');
            if ($jkVal === 'Laki-laki') $jkVal = 'L';
            if ($jkVal === 'Perempuan') $jkVal = 'P';

            $mahasiswaData = [
                'nama'          => $user->name,
                'nim'           => trim($request->input('nim')),
                'no_hp'         => trim($request->input('no_hp')),
                'prodi'         => trim($request->input('prodi')),
                'fakultas'      => trim($request->input('fakultas')),
                'angkatan'      => trim((string)$request->input('angkatan')),
                'jenis_kelamin' => $jkVal,
                'updated_at'    => now(),
            ];

            if ($mahasiswaRec) {
                // Jangan izinkan pengubahan user_id atau role
                DB::table('mahasiswa')
                    ->where('user_id', $user->id)
                    ->update($mahasiswaData);
            } else {
                // Jika record mahasiswa belum ada, buat baru
                $mahasiswaData['user_id'] = $user->id;
                $mahasiswaData['created_at'] = now();
                DB::table('mahasiswa')->insert($mahasiswaData);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $this->getActiveUser($request);

        Auth::guard('admin')->logout();
        Auth::guard('mahasiswa')->logout();

        if ($user) {
            $user->delete();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
