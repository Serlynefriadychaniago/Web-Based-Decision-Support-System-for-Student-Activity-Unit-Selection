<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Ukm;
use App\Models\ReviewUkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UkmController extends Controller
{
    /**
     * Menampilkan daftar seluruh UKM yang tersedia (Read-Only untuk Mahasiswa).
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = DB::table('ukm');

        if (!empty($search)) {
            $query->where('nama_ukm', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        }

        $ukmList = $query->orderBy('nama_ukm', 'asc')->get();

        // Ambil status pendaftaran yang sudah pernah dilakukan mahasiswa
        $registeredUkms = [];
        $mahasiswa = DB::table('mahasiswa')->where('user_id', Auth::id())->first();
        if ($mahasiswa) {
            $registeredUkms = DB::table('pendaftaran_ukm')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->pluck('status', 'ukm_id')
                ->toArray();
        }

        return view('mahasiswa.ukm.index', compact('ukmList', 'search', 'registeredUkms'));
    }

    /**
     * Menampilkan detail lengkap satu UKM (Read-Only untuk Mahasiswa).
     */
    public function show($id)
    {
        $ukm = Ukm::find($id);

        if (!$ukm) {
            abort(404, 'Data UKM tidak ditemukan.');
        }

        // Ambil profil kriteria UKM (Minat, Bakat, Jadwal, Prestasi, Biaya)
        $criteriaProfiles = DB::table('ukm_kriteria_profile')
            ->join('kriteria', 'ukm_kriteria_profile.kriteria_id', '=', 'kriteria.id')
            ->leftJoin('sub_kriteria', 'ukm_kriteria_profile.sub_kriteria_id', '=', 'sub_kriteria.id')
            ->where('ukm_kriteria_profile.ukm_id', $id)
            ->select(
                'kriteria.kode',
                'kriteria.nama_kriteria',
                'kriteria.tipe',
                'sub_kriteria.nama_subkriteria',
                'ukm_kriteria_profile.nilai'
            )
            ->orderBy('kriteria.id', 'asc')
            ->get();

        // Cek status pendaftaran & rekomendasi mahasiswa untuk UKM ini
        $registrationStatus = null;
        $topsisScore = null;
        $topsisRanking = null;

        $mahasiswa = DB::table('mahasiswa')->where('user_id', Auth::id())->first();

        if ($mahasiswa) {
            $pendaftaran = DB::table('pendaftaran_ukm')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('ukm_id', $id)
                ->first();

            if ($pendaftaran) {
                $registrationStatus = $pendaftaran->status;
            }

            // Ambil rekomendasi TOPSIS mahasiswa jika ada
            $latestRun = DB::table('topsis_runs')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderByDesc('id')
                ->first();

            if ($latestRun) {
                $rec = DB::table('topsis_results')
                    ->where('topsis_run_id', $latestRun->id)
                    ->where('ukm_id', $id)
                    ->first();

                if ($rec) {
                    $topsisScore = $rec->nilai_preferensi;
                    $topsisRanking = $rec->ranking;
                }
            }
        }

        // Ambil ulasan pengguna UKM yang sudah disetujui (Approved)
        $approvedReviews = ReviewUkm::with(['mahasiswa.user'])
            ->where('ukm_id', $id)
            ->approved()
            ->orderByDesc('created_at')
            ->get();

        $reviewsCount = $approvedReviews->count();
        $avgRating = $reviewsCount > 0 ? round((float)$approvedReviews->avg('rating'), 1) : 0.0;

        // Ambil ulasan milik mahasiswa yang sedang login (jika ada)
        $myReview = null;
        if ($mahasiswa) {
            $myReview = ReviewUkm::where('ukm_id', $id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->first();
        }

        // Syarat mahasiswa dapat memberikan review: sudah terdaftar dan belum pernah review
        $canReview = (bool)($mahasiswa && $registrationStatus && !$myReview);

        return view('mahasiswa.ukm.show', compact(
            'ukm',
            'criteriaProfiles',
            'registrationStatus',
            'topsisScore',
            'topsisRanking',
            'approvedReviews',
            'reviewsCount',
            'avgRating',
            'myReview',
            'canReview'
        ));
    }
}
