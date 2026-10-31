<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureMahasiswaProfileComplete;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekomendasiController extends Controller
{
    /**
     * Menampilkan pusat Laporan & Riwayat Rekomendasi SPK UKM Mahasiswa.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum melihat laporan rekomendasi.');
        }

        $mahasiswa = $profileStats['mahasiswa'];

        // 1. Ambil seluruh riwayat topsis_runs milik mahasiswa ini (newest to oldest)
        $allRunsRaw = DB::table('topsis_runs')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('id')
            ->get();

        $allRuns = collect();
        foreach ($allRunsRaw as $r) {
            // Ambil rekomendasi #1 untuk ringkasan kartu riwayat
            $topResult = DB::table('topsis_results')
                ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
                ->where('topsis_run_id', $r->id)
                ->where('ranking', 1)
                ->select('ukm.nama_ukm', 'topsis_results.nilai_preferensi')
                ->first();

            $r->top_ukm = $topResult ? $topResult->nama_ukm : '-';
            $r->top_nilai = $topResult ? $topResult->nilai_preferensi : null;
            $allRuns->push($r);
        }

        // 2. Tentukan run yang sedang dipilih untuk detail laporan
        $requestedRunId = $request->input('run_id');
        $selectedRun = null;

        if ($requestedRunId) {
            $selectedRun = $allRuns->firstWhere('id', (int) $requestedRunId);
        }

        if (!$selectedRun) {
            $selectedRun = $allRuns->first();
        }

        // 3. Ambil data detail untuk $selectedRun jika ada
        $selectedResults = collect();
        $studentAnswers = collect();
        $ahpWeights = collect();

        if ($selectedRun) {
            // Hasil Perangkingan TOPSIS (7 UKM)
            $selectedResults = DB::table('topsis_results')
                ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
                ->where('topsis_run_id', $selectedRun->id)
                ->orderBy('topsis_results.ranking')
                ->select(
                    'topsis_results.*',
                    'ukm.nama_ukm',
                    'ukm.deskripsi',
                    'ukm.logo'
                )
                ->get();

            // Jawaban Kuesioner Mahasiswa pada session tersebut
            $studentAnswers = DB::table('questionnaire_answers')
                ->join('kriteria', 'questionnaire_answers.kriteria_id', '=', 'kriteria.id')
                ->join('sub_kriteria', 'questionnaire_answers.sub_kriteria_id', '=', 'sub_kriteria.id')
                ->where('session_id', $selectedRun->questionnaire_session_id)
                ->orderBy('kriteria.id', 'asc')
                ->select(
                    'kriteria.kode',
                    'kriteria.nama_kriteria',
                    'sub_kriteria.nama_subkriteria',
                    'questionnaire_answers.nilai'
                )
                ->get();

            // Bobot AHP yang digunakan pada saat run tersebut
            $ahpWeights = DB::table('ahp_weights')
                ->join('kriteria', 'ahp_weights.kriteria_id', '=', 'kriteria.id')
                ->where('ahp_run_id', $selectedRun->ahp_run_id)
                ->orderBy('kriteria.id', 'asc')
                ->select(
                    'kriteria.kode',
                    'kriteria.nama_kriteria',
                    'ahp_weights.bobot'
                )
                ->get();
        }

        // 4. Status pendaftaran UKM
        $registeredUkms = DB::table('pendaftaran_ukm')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->pluck('status', 'ukm_id')
            ->toArray();

        return view('mahasiswa.rekomendasi.index', compact(
            'mahasiswa',
            'allRuns',
            'selectedRun',
            'selectedResults',
            'studentAnswers',
            'ahpWeights',
            'registeredUkms'
        ));
    }

    /**
     * Mengunduh PDF laporan detail riwayat rekomendasi TOPSIS mahasiswa.
     */
    public function downloadPdf($id)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $mahasiswa = $profileStats['mahasiswa'];

        // 1. Ambil data topsis_runs berdasarkan ID
        $selectedRun = DB::table('topsis_runs')
            ->where('id', $id)
            ->first();

        // 2. OTORISASI SANGAT KETAT: Memastikan riwayat milik mahasiswa yang sedang login (Mencegah IDOR)
        if (!$selectedRun || $selectedRun->mahasiswa_id != $mahasiswa->id) {
            abort(403, 'Anda tidak memiliki otorisasi untuk mengunduh riwayat rekomendasi ini.');
        }

        // 3. Ambil data detail hasil TOPSIS untuk run tersebut
        $selectedResults = DB::table('topsis_results')
            ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
            ->where('topsis_run_id', $selectedRun->id)
            ->orderBy('topsis_results.ranking')
            ->select(
                'topsis_results.*',
                'ukm.nama_ukm',
                'ukm.deskripsi',
                'ukm.logo'
            )
            ->get();

        // Jawaban kuesioner mahasiswa pada session tersebut
        $studentAnswers = DB::table('questionnaire_answers')
            ->join('kriteria', 'questionnaire_answers.kriteria_id', '=', 'kriteria.id')
            ->join('sub_kriteria', 'questionnaire_answers.sub_kriteria_id', '=', 'sub_kriteria.id')
            ->where('session_id', $selectedRun->questionnaire_session_id)
            ->orderBy('kriteria.id', 'asc')
            ->select(
                'kriteria.kode',
                'kriteria.nama_kriteria',
                'sub_kriteria.nama_subkriteria',
                'questionnaire_answers.nilai'
            )
            ->get();

        // Bobot AHP yang digunakan pada saat run tersebut
        $ahpWeights = DB::table('ahp_weights')
            ->join('kriteria', 'ahp_weights.kriteria_id', '=', 'kriteria.id')
            ->where('ahp_run_id', $selectedRun->ahp_run_id)
            ->orderBy('kriteria.id', 'asc')
            ->select(
                'kriteria.kode',
                'kriteria.nama_kriteria',
                'ahp_weights.bobot'
            )
            ->get();

        // 4. Render PDF menggunakan Barryvdh\DomPDF\Facade\Pdf
        $pdf = Pdf::loadView('mahasiswa.rekomendasi.pdf', compact(
            'mahasiswa',
            'selectedRun',
            'selectedResults',
            'studentAnswers',
            'ahpWeights'
        ));

        $pdf->setPaper('A4', 'portrait');

        $cleanNim = preg_replace('/[^A-Za-z0-9]/', '_', $mahasiswa->nim ?: 'mhs');
        $fileName = 'Detail_Riwayat_Rekomendasi_UKM_' . $cleanNim . '_#' . $selectedRun->id . '.pdf';

        return $pdf->download($fileName);
    }
}
