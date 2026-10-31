<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureMahasiswaProfileComplete
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Middleware ini hanya berlaku untuk role mahasiswa
        if ($user && $user->role === 'mahasiswa') {
            $profileStats = static::checkProfileStats($user->id);

            if (!$profileStats['is_complete']) {
                // Abaikan jika request menuju route profile, logout, atau dashboard
                if ($request->routeIs('profile.*') || $request->routeIs('logout') || $request->routeIs('mahasiswa.dashboard')) {
                    return $next($request);
                }

                return redirect()->route('profile.edit')
                    ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum mengisi kuesioner.');
            }
        }

        return $next($request);
    }

    /**
     * Pengecekan kelengkapan 8 field profil mahasiswa secara konsisten.
     */
    public static function checkProfileStats($userId): array
    {
        $user = DB::table('users')->where('id', $userId)->first();
        $mahasiswa = DB::table('mahasiswa')->where('user_id', $userId)->first();

        $fields = [
            'nama'          => !empty($mahasiswa->nama) ? $mahasiswa->nama : (!empty($user->name) ? $user->name : null),
            'nim'           => !empty($mahasiswa->nim) ? $mahasiswa->nim : null,
            'email'         => !empty($user->email) ? $user->email : null,
            'no_hp'         => !empty($mahasiswa->no_hp) ? $mahasiswa->no_hp : null,
            'prodi'         => !empty($mahasiswa->prodi) ? $mahasiswa->prodi : null,
            'fakultas'      => !empty($mahasiswa->fakultas) ? $mahasiswa->fakultas : null,
            'angkatan'      => !empty($mahasiswa->angkatan) ? $mahasiswa->angkatan : null,
            'jenis_kelamin' => !empty($mahasiswa->jenis_kelamin) ? $mahasiswa->jenis_kelamin : null,
        ];

        $totalFields = count($fields);
        $filledCount = 0;
        $missingFields = [];

        foreach ($fields as $key => $value) {
            if ($value !== null && trim((string)$value) !== '') {
                $filledCount++;
            } else {
                $missingFields[] = $key;
            }
        }

        $percentage = round(($filledCount / $totalFields) * 100, 1);
        $isComplete = ($filledCount === $totalFields);

        return [
            'is_complete'    => $isComplete,
            'filled_count'   => $filledCount,
            'total_fields'   => $totalFields,
            'percentage'     => $percentage,
            'missing_fields' => $missingFields,
            'fields'         => $fields,
            'mahasiswa'      => $mahasiswa,
            'user'           => $user,
        ];
    }
}
