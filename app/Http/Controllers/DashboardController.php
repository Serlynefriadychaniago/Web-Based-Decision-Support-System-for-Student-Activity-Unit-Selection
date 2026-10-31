<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\EnsureMahasiswaProfileComplete;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard sesuai role pengguna.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $totalUkm = DB::table('ukm')->count();
            $totalKriteria = DB::table('kriteria')->count();
            $totalMahasiswa = DB::table('users')->where('role', 'mahasiswa')->count();
            $totalPendaftaran = DB::table('pendaftaran_ukm')->count();

            return view('admin.dashboard', compact(
                'totalUkm',
                'totalKriteria',
                'totalMahasiswa',
                'totalPendaftaran'
            ));
        }

        if ($user->role === 'mahasiswa') {
            // 1. Ambil Data Mahasiswa & Status Kelengkapan Profil
            $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($user->id);
            $mahasiswa = $profileStats['mahasiswa'];
            $mahasiswaId = $mahasiswa ? $mahasiswa->id : null;

            // 2. Status Kuesioner
            $kuesionerSession = null;
            $hasCompletedKuesioner = false;
            $answeredCount = 0;

            if ($mahasiswaId) {
                $kuesionerSession = DB::table('questionnaire_sessions')
                    ->where('mahasiswa_id', $mahasiswaId)
                    ->orderByDesc('id')
                    ->first();

                if ($kuesionerSession) {
                    $hasCompletedKuesioner = ($kuesionerSession->status === 'completed');
                    $answeredCount = DB::table('questionnaire_answers')
                        ->where('session_id', $kuesionerSession->id)
                        ->count();
                }
            }

            // 3. TOP 3 Rekomendasi TOPSIS Mahasiswa
            $topRecommendations = collect();
            if ($mahasiswaId && $hasCompletedKuesioner && $profileStats['is_complete']) {
                $latestRun = DB::table('topsis_runs')
                    ->where('mahasiswa_id', $mahasiswaId)
                    ->orderByDesc('id')
                    ->first();

                if ($latestRun) {
                    $topRecommendations = DB::table('topsis_results')
                        ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
                        ->where('topsis_run_id', $latestRun->id)
                        ->orderBy('ranking', 'asc')
                        ->take(3)
                        ->select(
                            'topsis_results.*',
                            'ukm.nama_ukm',
                            'ukm.deskripsi'
                        )
                        ->get();
                }
            }

            // 4. Data Pendaftaran UKM
            $pendaftaranStats = [
                'total'          => 0,
                'latest'         => null,
                'status_history' => collect(),
            ];

            if ($mahasiswaId) {
                $pendaftaranStats['total'] = DB::table('pendaftaran_ukm')
                    ->where('mahasiswa_id', $mahasiswaId)
                    ->count();

                $latestReg = DB::table('pendaftaran_ukm')
                    ->join('ukm', 'pendaftaran_ukm.ukm_id', '=', 'ukm.id')
                    ->leftJoin('topsis_results', 'pendaftaran_ukm.topsis_result_id', '=', 'topsis_results.id')
                    ->where('pendaftaran_ukm.mahasiswa_id', $mahasiswaId)
                    ->orderByDesc('pendaftaran_ukm.id')
                    ->select(
                        'pendaftaran_ukm.*',
                        'ukm.nama_ukm',
                        'topsis_results.ranking as topsis_ranking',
                        'topsis_results.nilai_preferensi as topsis_score'
                    )
                    ->first();

                $pendaftaranStats['latest'] = $latestReg;

                if ($latestReg) {
                    $pendaftaranStats['status_history'] = DB::table('riwayat_status_pendaftaran')
                        ->leftJoin('users', 'riwayat_status_pendaftaran.diubah_oleh', '=', 'users.id')
                        ->where('pendaftaran_id', $latestReg->id)
                        ->select('riwayat_status_pendaftaran.*', 'users.name as admin_name')
                        ->orderBy('riwayat_status_pendaftaran.id', 'asc')
                        ->get();
                }
            }

            return view('mahasiswa.dashboard', compact(
                'mahasiswa',
                'profileStats',
                'kuesionerSession',
                'hasCompletedKuesioner',
                'answeredCount',
                'topRecommendations',
                'pendaftaranStats'
            ));
        }

        abort(403, 'Role pengguna tidak dikenali.');
    }
}