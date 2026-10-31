<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Ukm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalisisController extends Controller
{
    /**
     * Menampilkan halaman Analisis Data Deskriptif untuk Admin.
     */
    public function index(Request $request): View
    {
        $selectedAngkatan = $request->query('angkatan');
        $selectedTahun = $request->query('tahun');

        // =========================================================================
        // 1. DATA MAHASISWA
        // =========================================================================
        $mhsQuery = Mahasiswa::query();
        if ($selectedAngkatan) {
            $mhsQuery->where('angkatan', $selectedAngkatan);
        }

        $totalMahasiswa = $mhsQuery->count();
        $totalAkunMahasiswa = User::where('role', 'mahasiswa')->count();
        $genderBreakdown = [
            'L' => (clone $mhsQuery)->where('jenis_kelamin', 'L')->count(),
            'P' => (clone $mhsQuery)->where('jenis_kelamin', 'P')->count(),
        ];

        // Daftar angkatan untuk filter
        $listAngkatan = Mahasiswa::select('angkatan')
            ->distinct()
            ->whereNotNull('angkatan')
            ->orderByDesc('angkatan')
            ->pluck('angkatan');

        // =========================================================================
        // 2. DATA KUESIONER MINAT
        // =========================================================================
        $kuesionerQuery = DB::table('questionnaire_sessions');
        if ($selectedAngkatan) {
            $kuesionerQuery->join('mahasiswa', 'questionnaire_sessions.mahasiswa_id', '=', 'mahasiswa.id')
                ->where('mahasiswa.angkatan', $selectedAngkatan);
        }

        $totalSesiKuesioner = (clone $kuesionerQuery)->count();
        $totalKuesionerSelesai = (clone $kuesionerQuery)->where('questionnaire_sessions.status', 'completed')->count();
        $mahasiswaIsiKuesioner = (clone $kuesionerQuery)->distinct('questionnaire_sessions.mahasiswa_id')->count('questionnaire_sessions.mahasiswa_id');

        // =========================================================================
        // 3. DATA REKOMENDASI TOPSIS (PERINGKAT 1)
        // =========================================================================
        $totalRekomendasiTopsis = DB::table('topsis_runs')->count();

        // Ambil data semua UKM aktif
        $allUkm = Ukm::orderBy('nama_ukm', 'asc')->get();

        // Query distribusi Rekomendasi Peringkat 1
        $rank1Query = DB::table('topsis_results')
            ->where('ranking', 1);

        if ($selectedTahun) {
            $rank1Query->join('topsis_runs', 'topsis_results.topsis_run_id', '=', 'topsis_runs.id')
                ->whereYear('topsis_runs.created_at', $selectedTahun);
        }

        $rank1Data = $rank1Query
            ->select('ukm_id', DB::raw('count(*) as total_rank1'), DB::raw('avg(nilai_preferensi) as avg_pref'))
            ->groupBy('ukm_id')
            ->pluck('total_rank1', 'ukm_id')
            ->toArray();

        $prefData = DB::table('topsis_results')
            ->where('ranking', 1)
            ->select('ukm_id', DB::raw('avg(nilai_preferensi) as avg_pref'))
            ->groupBy('ukm_id')
            ->pluck('avg_pref', 'ukm_id')
            ->toArray();

        // =========================================================================
        // 4. DATA PENDAFTARAN UKM
        // =========================================================================
        $pendaftaranQuery = DB::table('pendaftaran_ukm');
        if ($selectedTahun) {
            $pendaftaranQuery->whereYear('tanggal_daftar', $selectedTahun);
        }
        if ($selectedAngkatan) {
            $pendaftaranQuery->join('mahasiswa', 'pendaftaran_ukm.mahasiswa_id', '=', 'mahasiswa.id')
                ->where('mahasiswa.angkatan', $selectedAngkatan);
        }

        $totalPendaftaran = (clone $pendaftaranQuery)->count();

        // Sebaran status pendaftaran aktual dari database
        $statusCountsRaw = (clone $pendaftaranQuery)
            ->select('pendaftaran_ukm.status', DB::raw('count(*) as count'))
            ->groupBy('pendaftaran_ukm.status')
            ->pluck('count', 'status')
            ->toArray();

        $statusMapping = [
            'terverifikasi'       => 'Terverifikasi / Diterima',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'perlu_perbaikan'     => 'Perlu Perbaikan / Revisi',
            'ditolak'             => 'Ditolak',
            'selesai'             => 'Selesai',
        ];

        $pendaftarPerUkmRaw = (clone $pendaftaranQuery)
            ->select('pendaftaran_ukm.ukm_id', DB::raw('count(*) as total'))
            ->groupBy('pendaftaran_ukm.ukm_id')
            ->pluck('total', 'ukm_id')
            ->toArray();

        // =========================================================================
        // 5. DATA REVIEW UKM
        // =========================================================================
        $reviewQuery = DB::table('review_ukm');
        if ($selectedTahun) {
            $reviewQuery->whereYear('created_at', $selectedTahun);
        }

        $totalReview = (clone $reviewQuery)->count();
        $totalReviewApproved = (clone $reviewQuery)->where('status', 'approved')->count();
        $totalReviewPending = (clone $reviewQuery)->where('status', 'pending')->count();

        $reviewPerUkmRaw = (clone $reviewQuery)
            ->where('status', 'approved')
            ->select('ukm_id', DB::raw('count(*) as total_review'), DB::raw('avg(rating) as avg_rating'))
            ->groupBy('ukm_id')
            ->get()
            ->keyBy('ukm_id');

        // =========================================================================
        // 6. GABUNGKAN DATA RINGKASAN PER UKM & PREPARASI DATA CHART
        // =========================================================================
        $ukmSummary = [];
        $chartLabels = [];
        $chartRekomendasiData = [];
        $chartPendaftaranData = [];
        $chartRatingData = [];

        foreach ($allUkm as $ukm) {
            $ukmId = $ukm->id;
            $namaUkm = $ukm->nama_ukm;

            $jmlRank1 = $rank1Data[$ukmId] ?? 0;
            $avgPref = isset($prefData[$ukmId]) ? round($prefData[$ukmId], 4) : 0;
            $jmlPendaftar = $pendaftarPerUkmRaw[$ukmId] ?? 0;
            $reviewInfo = $reviewPerUkmRaw->get($ukmId);
            $jmlReview = $reviewInfo ? $reviewInfo->total_review : 0;
            $avgRating = $reviewInfo ? round($reviewInfo->avg_rating, 2) : 0;

            // Rasio pendaftar dibanding rekomendasi rank 1 (jika ada)
            $conversionRate = $jmlRank1 > 0 ? round(($jmlPendaftar / $jmlRank1) * 100, 1) : null;

            $ukmSummary[] = [
                'id'              => $ukmId,
                'nama'            => $namaUkm,
                'status'          => $ukm->status,
                'rekomendasi_rank1' => $jmlRank1,
                'avg_preferensi'  => $avgPref,
                'pendaftar'       => $jmlPendaftar,
                'total_review'    => $jmlReview,
                'avg_rating'      => $avgRating,
                'conversion_rate' => $conversionRate,
            ];

            $chartLabels[] = $namaUkm;
            $chartRekomendasiData[] = $jmlRank1;
            $chartPendaftaranData[] = $jmlPendaftar;
            $chartRatingData[] = $avgRating;
        }

        // =========================================================================
        // 7. HIGHLIGHT METRICS (UKM TERBAIK TIAP KATEGORI)
        // =========================================================================
        $topRekomendasi = collect($ukmSummary)->sortByDesc('rekomendasi_rank1')->first();
        $topPendaftar = collect($ukmSummary)->sortByDesc('pendaftar')->first();
        $topRating = collect($ukmSummary)->sortByDesc('avg_rating')->first();
        $topReview = collect($ukmSummary)->sortByDesc('total_review')->first();

        // Chart Status Pendaftaran
        $chartStatusLabels = [];
        $chartStatusData = [];
        foreach ($statusCountsRaw as $statusKey => $cnt) {
            $chartStatusLabels[] = $statusMapping[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey));
            $chartStatusData[] = $cnt;
        }

        // Daftar tahun pendaftaran untuk filter
        $listTahun = DB::table('pendaftaran_ukm')
            ->select(DB::raw('YEAR(tanggal_daftar) as tahun'))
            ->distinct()
            ->whereNotNull('tanggal_daftar')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.analisis.index', compact(
            'totalMahasiswa',
            'totalAkunMahasiswa',
            'genderBreakdown',
            'totalSesiKuesioner',
            'totalKuesionerSelesai',
            'mahasiswaIsiKuesioner',
            'totalRekomendasiTopsis',
            'totalPendaftaran',
            'statusCountsRaw',
            'statusMapping',
            'totalReview',
            'totalReviewApproved',
            'totalReviewPending',
            'ukmSummary',
            'topRekomendasi',
            'topPendaftar',
            'topRating',
            'topReview',
            'chartLabels',
            'chartRekomendasiData',
            'chartPendaftaranData',
            'chartRatingData',
            'chartStatusLabels',
            'chartStatusData',
            'listAngkatan',
            'listTahun',
            'selectedAngkatan',
            'selectedTahun'
        ));
    }
}
