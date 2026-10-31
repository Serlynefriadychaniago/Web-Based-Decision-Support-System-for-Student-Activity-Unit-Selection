<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureMahasiswaProfileComplete;
use App\Services\StudentMatchingService;
use App\Services\TopsisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class KuesionerController extends Controller
{
    protected StudentMatchingService $matchingService;
    protected TopsisService $topsisService;

    public function __construct(
        StudentMatchingService $matchingService,
        TopsisService $topsisService
    ) {
        $this->matchingService = $matchingService;
        $this->topsisService = $topsisService;
    }

    /**
     * Menampilkan form kuesioner / hasil rekomendasi terbaru mahasiswa.
     */
    public function index()
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum mengisi kuesioner.');
        }

        $mahasiswa = $profileStats['mahasiswa'];

        // Ambil seluruh Kriteria dari database
        $criteria = DB::table('kriteria')
            ->orderBy('id', 'asc')
            ->get();

        // Ambil semua sub-kriteria aktif
        $subKriteriaAll = DB::table('sub_kriteria')
            ->where('status', 'aktif')
            ->orderBy('kriteria_id')
            ->orderBy('urutan')
            ->get()
            ->groupBy('kriteria_id');

        // Ambil session kuesioner terakhir
        $latestSession = DB::table('questionnaire_sessions')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'completed')
            ->orderByDesc('id')
            ->first();

        // Jawaban tersimpan dari session terakhir untuk pre-fill form
        $savedAnswers = [];
        if ($latestSession) {
            $answers = DB::table('questionnaire_answers')
                ->where('session_id', $latestSession->id)
                ->get();
            foreach ($answers as $ans) {
                $savedAnswers[$ans->kriteria_id] = $ans->sub_kriteria_id;
            }
        }

        // Ambil run TOPSIS terbaru milik mahasiswa jika ada
        $latestRun = DB::table('topsis_runs')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('id')
            ->first();

        $latestResults = collect();
        if ($latestRun) {
            $latestResults = DB::table('topsis_results')
                ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
                ->where('topsis_run_id', $latestRun->id)
                ->orderBy('topsis_results.ranking')
                ->select(
                    'topsis_results.*',
                    'ukm.nama_ukm',
                    'ukm.deskripsi',
                    'ukm.logo'
                )
                ->get();
        }

        // Cek status pendaftaran yang sudah dilakukan mahasiswa
        $registeredUkms = DB::table('pendaftaran_ukm')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->pluck('status', 'ukm_id')
            ->toArray();

        $isEdit = request()->boolean('edit');
        $hasResults = $latestRun && $latestResults->isNotEmpty();
        $showResults = $hasResults && !$isEdit;

        return view('mahasiswa.kuesioner.index', compact(
            'mahasiswa',
            'criteria',
            'subKriteriaAll',
            'latestSession',
            'savedAnswers',
            'latestRun',
            'latestResults',
            'registeredUkms',
            'isEdit',
            'showResults'
        ));
    }

    /**
     * Menyimpan jawaban kuesioner baru (membuat session baru & topsis_run baru untuk riwayat).
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum mengisi kuesioner.');
        }

        $mahasiswa = $profileStats['mahasiswa'];

        // Validasi input sub_kriteria_id untuk kriteria C1..C5
        $request->validate([
            'answers'   => 'required|array',
            'answers.*' => 'required|exists:sub_kriteria,id',
        ], [
            'answers.required'   => 'Silakan lengkapi seluruh pertanyaan kuesioner.',
            'answers.*.required' => 'Setiap kriteria harus dipilih salah satu jawabannya.',
        ]);

        try {
            DB::beginTransaction();

            // 1. Selalu buat session kuesioner BARU (agar riwayat pengisian lama tetap tersimpan)
            $sessionId = DB::table('questionnaire_sessions')->insertGetId([
                'mahasiswa_id' => $mahasiswa->id,
                'status'       => 'completed',
                'started_at'   => now(),
                'completed_at' => now(),
            ]);

            // 2. Simpan jawaban kuesioner untuk session baru
            foreach ($request->input('answers') as $kriteriaId => $subKriteriaId) {
                $subKrit = DB::table('sub_kriteria')->where('id', $subKriteriaId)->first();
                $nilai = $subKrit ? ($subKrit->urutan ?? 3.0) : 3.0;

                DB::table('questionnaire_answers')->insert([
                    'session_id'      => $sessionId,
                    'kriteria_id'     => $kriteriaId,
                    'sub_kriteria_id' => $subKriteriaId,
                    'nilai'           => $nilai,
                ]);
            }

            // 3. Ambil bobot AHP terbaru dari Admin (tabel ahp_runs & ahp_weights)
            $latestAhp = DB::table('ahp_runs')->where('status', 'valid')->orderByDesc('id')->first();
            if (!$latestAhp) {
                $latestAhp = DB::table('ahp_runs')->orderByDesc('id')->first();
            }

            if (!$latestAhp) {
                DB::rollBack();
                return back()->with('error', 'Bobot AHP belum tersedia. Silakan lakukan perhitungan AHP terlebih dahulu oleh Admin.');
            }

            $criteriaList = DB::table('kriteria')
                ->orderBy('id', 'asc')
                ->get();

            $criteriaCodes = $criteriaList->pluck('kode')->toArray();

            $weightRows = DB::table('ahp_weights')
                ->where('ahp_run_id', $latestAhp->id)
                ->get()
                ->keyBy('kriteria_id');

            if ($weightRows->isEmpty()) {
                DB::rollBack();
                return back()->with('error', 'Bobot AHP kriteria belum dikonfigurasi atau tersimpan di tabel ahp_weights.');
            }

            $weights = [];
            foreach ($criteriaList as $crit) {
                if (isset($weightRows[$crit->id])) {
                    $wVal = $weightRows[$crit->id]->bobot ?? $weightRows[$crit->id]->weight ?? null;
                    if ($wVal !== null) {
                        $weights[] = (float) $wVal;
                    } else {
                        DB::rollBack();
                        return back()->with('error', "Bobot AHP untuk kriteria {$crit->nama_kriteria} ({$crit->kode}) belum tersedia di database.");
                    }
                } else {
                    DB::rollBack();
                    return back()->with('error', "Bobot AHP untuk kriteria {$crit->nama_kriteria} ({$crit->kode}) belum dikonfigurasi oleh Admin.");
                }
            }

            // 4. Jalankan Student Matching & Kalkulasi TOPSIS Mahasiswa
            $matchingData = $this->matchingService->buildDecisionMatrix($sessionId);
            $decisionMatrix = $matchingData['decision_matrix'];
            $alternativeData = $matchingData['alternative_data'];

            $topsisResult = $this->topsisService->calculate(
                $decisionMatrix,
                $weights,
                $criteriaCodes
            );

            // 5. Simpan topsis_runs & topsis_results BARU
            $topsisRunId = DB::table('topsis_runs')->insertGetId([
                'mahasiswa_id'             => $mahasiswa->id,
                'questionnaire_session_id' => $sessionId,
                'ahp_run_id'               => $latestAhp->id,
                'created_at'               => now(),
            ]);

            foreach ($topsisResult['preference_values'] as $idx => $prefValue) {
                DB::table('topsis_results')->insert([
                    'topsis_run_id'    => $topsisRunId,
                    'ukm_id'           => $alternativeData[$idx]['ukm_id'],
                    's_plus'           => $topsisResult['separation_positive'][$idx] ?? 0,
                    's_minus'          => $topsisResult['separation_negative'][$idx] ?? 0,
                    'nilai_preferensi' => $prefValue,
                    'ranking'          => $topsisResult['ranking'][$idx],
                ]);
            }

            DB::commit();

            return redirect()->route('mahasiswa.kuesioner.index')
                ->with('success', 'Kuesioner berhasil disimpan dan hasil rekomendasi TOPSIS telah diperbarui!');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses kuesioner: ' . $e->getMessage());
        }
    }
}
