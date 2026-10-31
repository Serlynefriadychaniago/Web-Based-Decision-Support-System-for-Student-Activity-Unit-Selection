<?php

namespace App\Http\Controllers;

use App\Services\TopsisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Throwable;

class TopsisController extends Controller
{
    /**
     * ============================================================
     * KONFIGURASI TOPSIS
     * ============================================================
     *
     * Urutan ini HARUS sama dengan bobot AHP:
     *
     * C1 = Minat
     * C2 = Bakat
     * C3 = Jadwal
     * C4 = Prestasi
     * C5 = Biaya
     *
     * 7 alternatif UKM diproses secara otomatis dari database.
     */
    protected array $criteriaCodes = [
        'C1',
        'C2',
        'C3',
        'C4',
        'C5',
    ];

    /**
     * Nama kriteria yang digunakan sistem.
     */
    protected array $criteriaNames = [
        'C1' => 'Minat',
        'C2' => 'Bakat',
        'C3' => 'Jadwal',
        'C4' => 'Prestasi',
        'C5' => 'Biaya',
    ];

    /**
     * Service TOPSIS.
     */
    protected TopsisService $topsisService;

    /**
     * Constructor.
     */
    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    /**
     * ============================================================
     * HALAMAN UTAMA TOPSIS
     * ============================================================
     *
     * Menampilkan:
     *
     * - bobot AHP terbaru
     * - kriteria C1-C5
     * - seluruh UKM
     * - matriks keputusan
     * - status kelengkapan masing-masing UKM
     *
     * PENTING:
     * Matriks keputusan tetap ditampilkan walaupun belum semua
     * nilai profil tersedia.
     *
     * Nilai yang belum tersedia = null.
     *
     * TOPSIS belum boleh dihitung sebelum seluruh matriks
     * 7 x 5 lengkap.
     */
    public function index()
    {
        $criteria = $this->getCriteria();

        $ukm = DB::table('ukm')
            ->orderBy('id')
            ->get();

        $latestAhp = $this->getLatestAhpWeights();

        /*
         * --------------------------------------------------------
         * AMBIL PROFIL UKM
         * --------------------------------------------------------
         */
        $profiles = DB::table('ukm_kriteria_profile')
            ->get();

        /*
         * Bentuk:
         *
         * $profileMap[ukm_id][kriteria_id] = nilai
         */
        $profileMap = [];

        foreach ($profiles as $profile) {

            $ukmId = $profile->ukm_id;
            $criteriaId = $profile->kriteria_id;

            $profileMap[$ukmId][$criteriaId] =
                $profile->nilai ?? null;
        }

        /*
         * --------------------------------------------------------
         * BENTUK MATRIKS KEPUTUSAN UNTUK TAMPILAN
         * --------------------------------------------------------
         *
         * Bentuk:
         *
         * [
         *     [
         *         C1,
         *         C2,
         *         C3,
         *         C4,
         *         C5
         *     ],
         *     ...
         * ]
         *
         * Jika nilai belum tersedia, gunakan null.
         *
         * JANGAN menggunakan 0 sebagai nilai default karena
         * 0 memiliki arti nilai sebenarnya dalam perhitungan.
         */
        $decisionMatrix = [];

        /*
         * Menyimpan status kelengkapan masing-masing UKM.
         */
        $alternativeStatus = [];

        /*
         * Menyimpan jumlah nilai yang sudah tersedia.
         */
        $completedValues = 0;

        /*
         * Total nilai yang seharusnya tersedia:
         *
         * 7 UKM x 5 kriteria = 35.
         */
        $totalRequiredValues =
            $ukm->count() * $criteria->count();

        foreach ($ukm as $index => $item) {

            $row = [];

            $filled = 0;
            $missingCriteria = [];

            foreach ($criteria as $criterion) {

                $value =
                    $profileMap[$item->id][$criterion->id]
                    ?? null;

                /*
                 * Jika ada nilai.
                 */
                if (
                    $value !== null
                    && $value !== ''
                ) {
                    $value = (float) $value;

                    $filled++;
                    $completedValues++;
                } else {
                    $value = null;

                    $missingCriteria[] =
                        $criterion->kode;
                }

                $row[] = $value;
            }

            $decisionMatrix[$index] = $row;

            /*
             * Status UKM.
             */
            $isComplete =
                $filled === $criteria->count();

            $alternativeStatus[$index] = [
                'ukm_id' => $item->id,
                'nama' =>
                    $item->nama_ukm
                    ?? 'UKM ' . ($index + 1),

                'filled' => $filled,

                'required' =>
                    $criteria->count(),

                'complete' =>
                    $isComplete,

                'missing_criteria' =>
                    $missingCriteria,
            ];
        }

        /*
         * --------------------------------------------------------
         * STATUS KESELURUHAN MATRIKS
         * --------------------------------------------------------
         */
        $matrixComplete =
            $ukm->count() === 7
            && $criteria->count() === 5
            && $completedValues === $totalRequiredValues;

        /*
         * Jika jumlah UKM bukan 7, matriks belum siap.
         */
        if ($ukm->count() !== 7) {
            $matrixComplete = false;
        }

        /*
         * Jika kriteria bukan 5, matriks belum siap.
         */
        if ($criteria->count() !== 5) {
            $matrixComplete = false;
        }

        /*
         * Persentase kelengkapan matriks.
         */
        $completionPercentage =
            $totalRequiredValues > 0
                ? round(
                    ($completedValues / $totalRequiredValues) * 100,
                    2
                )
                : 0;

        /*
         * --------------------------------------------------------
         * RETURN VIEW
         * --------------------------------------------------------
         */
        return view('admin.topsis.index', [
            'criteria' => $criteria,

            'ukm' => $ukm,

            /*
             * Bobot hasil AHP terbaru.
             */
            'ahpWeights' =>
                $latestAhp['weights'],

            'ahpRun' =>
                $latestAhp['run'],

            /*
             * Matriks keputusan.
             */
            'decisionMatrix' =>
                $decisionMatrix,

            /*
             * Status masing-masing UKM.
             */
            'alternativeStatus' =>
                $alternativeStatus,

            /*
             * Status keseluruhan.
             */
            'matrixComplete' =>
                $matrixComplete,

            /*
             * Progress pengisian.
             */
            'completedValues' =>
                $completedValues,

            'totalRequiredValues' =>
                $totalRequiredValues,

            'completionPercentage' =>
                $completionPercentage,
        ]);
    }

    /**
     * ============================================================
     * PROSES TOPSIS
     * ============================================================
     *
     * TOPSIS hanya dijalankan jika:
     *
     * - bobot AHP tersedia
     * - terdapat tepat 5 kriteria
     * - terdapat tepat 7 UKM
     * - seluruh 35 nilai matriks tersedia
     */
    public function calculate(Request $request)
    {
        try {

            /*
             * ----------------------------------------------------
             * 1. VALIDASI SESSION MAHASISWA
             * ----------------------------------------------------
             */
            $questionnaireSessionId =
                $request->input('questionnaire_session_id')
                ?? $request->input('session_id');

            if (!$questionnaireSessionId && Auth::check()) {

                $mahasiswaId = $this->getMahasiswaId();

                if ($mahasiswaId) {

                    $questionnaireSessionId =
                        DB::table('questionnaire_sessions')
                            ->where(
                                'mahasiswa_id',
                                $mahasiswaId
                            )
                            ->orderByDesc('id')
                            ->value('id');
                }
            }

            /*
             * ----------------------------------------------------
             * 2. AMBIL BOBOT AHP TERBARU
             * ----------------------------------------------------
             */
            $ahpData =
                $this->getLatestAhpWeights();

            if (empty($ahpData['weights'])) {

                return $this->errorResponse(
                    $request,
                    'Bobot AHP belum tersedia. Silakan lakukan perhitungan AHP terlebih dahulu.'
                );
            }

            $weights =
                $ahpData['weights'];

            /*
             * Harus ada 5 bobot.
             */
            if (count($weights) !== 5) {

                return $this->errorResponse(
                    $request,
                    'Jumlah bobot AHP harus tepat 5 kriteria.'
                );
            }

            /*
             * ----------------------------------------------------
             * 3. AMBIL KRITERIA
             * ----------------------------------------------------
             */
            $criteria =
                $this->getCriteria();

            if ($criteria->count() !== 5) {

                return $this->errorResponse(
                    $request,
                    'Data kriteria harus terdiri dari 5 kriteria: Minat, Bakat, Jadwal, Prestasi, dan Biaya.'
                );
            }

            /*
             * ----------------------------------------------------
             * 4. AMBIL 7 UKM
             * ----------------------------------------------------
             */
            $ukmList =
                DB::table('ukm')
                    ->orderBy('id')
                    ->get();

            if ($ukmList->isEmpty()) {

                return $this->errorResponse(
                    $request,
                    'Data alternatif UKM belum tersedia.'
                );
            }

            /*
             * TOPSIS penelitian menggunakan tepat 7 alternatif.
             */
            if ($ukmList->count() !== 7) {

                return $this->errorResponse(
                    $request,
                    'Jumlah alternatif UKM harus 7. Saat ini ditemukan '
                    . $ukmList->count()
                    . ' UKM.'
                );
            }

            /*
             * ----------------------------------------------------
             * 5. AMBIL PROFIL UKM
             * ----------------------------------------------------
             */
            $profiles =
                DB::table('ukm_kriteria_profile')
                    ->get();

            /*
             * Map profil:
             *
             * [ukm_id][kriteria_id] = nilai
             */
            $profileMap = [];

            foreach ($profiles as $profile) {

                $profileMap[$profile->ukm_id]
                    [$profile->kriteria_id] =
                        $profile->nilai ?? null;
            }

            /*
             * ----------------------------------------------------
             * 6. VALIDASI PROFIL
             * ----------------------------------------------------
             *
             * Di halaman index kita boleh menampilkan data parsial.
             *
             * Tetapi ketika tombol "Hitung TOPSIS" ditekan,
             * seluruh 7 x 5 nilai WAJIB tersedia.
             */
            foreach ($ukmList as $ukm) {

                foreach ($criteria as $criterion) {

                    $value =
                        $profileMap[$ukm->id]
                            [$criterion->id]
                            ?? null;

                    if (
                        $value === null
                        || $value === ''
                    ) {

                        $ukmName =
                            $ukm->nama_ukm
                            ?? 'ID ' . $ukm->id;

                        return $this->errorResponse(
                            $request,
                            "Matriks keputusan belum lengkap. Nilai {$criterion->nama_kriteria} untuk UKM {$ukmName} belum tersedia. Lengkapi seluruh profil 7 UKM terlebih dahulu."
                        );
                    }
                }
            }

            /*
             * ----------------------------------------------------
             * 7. AMBIL JAWABAN KUESIONER
             * ----------------------------------------------------
             *
             * Tetap dipertahankan untuk kebutuhan integrasi
             * role mahasiswa.
             */
            $questionnaireAnswers =
                collect();

            if ($questionnaireSessionId) {

                $questionnaireAnswers =
                    DB::table(
                        'questionnaire_answers'
                    )
                    ->where(
                        'questionnaire_session_id',
                        $questionnaireSessionId
                    )
                    ->get();
            }

            /*
             * Map jawaban mahasiswa.
             */
            $answerMap = [];

            foreach ($questionnaireAnswers as $answer) {

                if (
                    isset($answer->kriteria_id)
                    && isset($answer->nilai)
                ) {

                    $answerMap[
                        $answer->kriteria_id
                    ] =
                        (float) $answer->nilai;
                }
            }

            /*
             * ----------------------------------------------------
             * 8. BENTUK MATRIKS KEPUTUSAN
             * ----------------------------------------------------
             */
            $decisionMatrix = [];

            $alternativeData = [];

            foreach (
                $ukmList as $index => $ukm
            ) {

                $row = [];

                foreach ($criteria as $criterion) {

                    $criterionId =
                        $criterion->id;

                    $criterionCode =
                        $criterion->kode;

                    $value =
                        $profileMap[$ukm->id]
                            [$criterionId]
                            ?? null;

                    /*
                     * Pada tahap calculate seharusnya tidak null
                     * karena sudah divalidasi di atas.
                     */
                    if (
                        $value === null
                        || $value === ''
                    ) {

                        return $this->errorResponse(
                            $request,
                            "Nilai kriteria {$criterion->nama_kriteria} untuk UKM "
                            . (
                                $ukm->nama_ukm
                                ?? 'ID ' . $ukm->id
                            )
                            . ' belum tersedia.'
                        );
                    }

                    $value =
                        (float) $value;

                    /*
                     * Nilai tidak boleh negatif.
                     */
                    if ($value < 0) {

                        return $this->errorResponse(
                            $request,
                            "Nilai {$criterion->nama_kriteria} pada UKM "
                            . (
                                $ukm->nama_ukm
                                ?? 'ID ' . $ukm->id
                            )
                            . ' tidak boleh negatif.'
                        );
                    }

                    /*
                     * C1 dan C2 tetap menggunakan nilai profil UKM.
                     *
                     * Jawaban kuesioner mahasiswa belum mengubah
                     * matriks admin pada tahap ini.
                     *
                     * Hal ini penting supaya perhitungan admin
                     * tetap menjadi data dasar/objektif UKM.
                     */
                    if ($criterionCode === 'C1') {

                        $value =
                            (float) $value;
                    }

                    if ($criterionCode === 'C2') {

                        $value =
                            (float) $value;
                    }

                    $row[] =
                        $value;
                }

                $decisionMatrix[] =
                    $row;

                $alternativeData[$index] = [
                    'ukm_id' =>
                        $ukm->id,

                    'nama' =>
                        $ukm->nama_ukm
                        ?? 'UKM ' . ($index + 1),
                ];
            }

            /*
             * ----------------------------------------------------
             * 9. JALANKAN TOPSIS SERVICE
             * ----------------------------------------------------
             */
            $topsisResult =
                $this->topsisService->calculate(
                    $decisionMatrix,
                    $weights,
                    $this->criteriaCodes
                );

            /*
             * ----------------------------------------------------
             * 10. TAMBAHKAN INFORMASI UKM
             * ----------------------------------------------------
             */
            $rankingResults = [];

            foreach (
                $topsisResult['preference_values']
                as $index => $value
            ) {

                $rankingResults[] = [
                    'ukm_id' =>
                        $alternativeData[$index]['ukm_id'],

                    'nama' =>
                        $alternativeData[$index]['nama'],

                    'preference_value' =>
                        $value,

                    'ranking' =>
                        $topsisResult['ranking'][$index],

                    'separation_positive' =>
                        $topsisResult[
                            'separation_positive'
                        ][$index],

                    'separation_negative' =>
                        $topsisResult[
                            'separation_negative'
                        ][$index],
                ];
            }

            /*
             * Urutkan berdasarkan ranking.
             */
            usort(
                $rankingResults,
                function ($a, $b) {
                    return
                        $a['ranking']
                        <=>
                        $b['ranking'];
                }
            );

            /*
             * ----------------------------------------------------
             * 11. SIMPAN HASIL TOPSIS
             * ----------------------------------------------------
             */
            $topsisRunId =
                $this->saveTopsisRun(
                    $questionnaireSessionId,
                    $ahpData,
                    $topsisResult,
                    $decisionMatrix,
                    $alternativeData,
                    $rankingResults
                );

            /*
             * ----------------------------------------------------
             * 12. RESPONSE JSON
             * ----------------------------------------------------
             */
            if ($request->expectsJson()) {

                return response()->json([
                    'success' => true,

                    'message' =>
                        'Perhitungan TOPSIS berhasil.',

                    'topsis_run_id' =>
                        $topsisRunId,

                    'weights' =>
                        $weights,

                    'decision_matrix' =>
                        $topsisResult[
                            'decision_matrix'
                        ],

                    'normalized_matrix' =>
                        $topsisResult[
                            'normalized_matrix'
                        ],

                    'weighted_normalized_matrix' =>
                        $topsisResult[
                            'weighted_normalized_matrix'
                        ],

                    'ideal_positive' =>
                        $topsisResult[
                            'ideal_positive'
                        ],

                    'ideal_negative' =>
                        $topsisResult[
                            'ideal_negative'
                        ],

                    'separation_positive' =>
                        $topsisResult[
                            'separation_positive'
                        ],

                    'separation_negative' =>
                        $topsisResult[
                            'separation_negative'
                        ],

                    'preference_values' =>
                        $topsisResult[
                            'preference_values'
                        ],

                    'ranking' =>
                        $topsisResult[
                            'ranking'
                        ],

                    'results' =>
                        $rankingResults,
                ]);
            }

            /*
             * ----------------------------------------------------
             * 13. TAMPILKAN HASIL
             * ----------------------------------------------------
             */
            return view(
                'admin.topsis.result',
                [
                    'topsisRunId' =>
                        $topsisRunId,

                    'ahpRun' =>
                        $ahpData['run'],

                    'weights' =>
                        $weights,

                    'criteria' =>
                        $criteria,

                    'ukm' =>
                        $ukmList,

                    'decisionMatrix' =>
                        $topsisResult[
                            'decision_matrix'
                        ],

                    'normalizedMatrix' =>
                        $topsisResult[
                            'normalized_matrix'
                        ],

                    'weightedNormalizedMatrix' =>
                        $topsisResult[
                            'weighted_normalized_matrix'
                        ],

                    'idealPositive' =>
                        $topsisResult[
                            'ideal_positive'
                        ],

                    'idealNegative' =>
                        $topsisResult[
                            'ideal_negative'
                        ],

                    'separationPositive' =>
                        $topsisResult[
                            'separation_positive'
                        ],

                    'separationNegative' =>
                        $topsisResult[
                            'separation_negative'
                        ],

                    'preferenceValues' =>
                        $topsisResult[
                            'preference_values'
                        ],

                    'ranking' =>
                        $topsisResult[
                            'ranking'
                        ],

                    'results' =>
                        $rankingResults,

                    'questionnaireSessionId' =>
                        $questionnaireSessionId,
                ]
            );

        } catch (Throwable $e) {

            /*
             * Simpan error Laravel.
             */
            Log::error(
                'TOPSIS calculation error',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );

            /*
             * JSON / AJAX.
             */
            if ($request->expectsJson()) {

                return response()->json([
                    'success' => false,

                    'message' =>
                        'Terjadi kesalahan saat menghitung TOPSIS.',

                    'error' =>
                        $e->getMessage(),
                ], 500);
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menghitung TOPSIS: '
                    . $e->getMessage()
                );
        }
    }

    /**
     * ============================================================
     * AMBIL KRITERIA
     * ============================================================
     *
     * Selalu C1 → C2 → C3 → C4 → C5.
     */
    protected function getCriteria()
    {
        return DB::table('kriteria')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * ============================================================
     * AMBIL BOBOT AHP TERBARU
     * ============================================================
     */
    protected function getLatestAhpWeights(): array
    {
        /*
         * Cari run AHP terakhir.
         */
        $run =
            DB::table('ahp_runs')
                ->orderByDesc('id')
                ->first();

        if (!$run) {

            return [
                'run' => null,
                'weights' => [],
            ];
        }

        /*
         * Ambil bobot dari run tersebut.
         */
        $weightRows =
            DB::table('ahp_weights')
                ->where(
                    'ahp_run_id',
                    $run->id
                )
                ->get();

        if ($weightRows->isEmpty()) {

            return [
                'run' => $run,
                'weights' => [],
            ];
        }

        /*
         * Kriteria C1-C5.
         */
        $criteria =
            $this->getCriteria();

        $weightsByCriteriaId = [];

        foreach ($weightRows as $weightRow) {

            /*
             * Mendukung nama kolom:
             *
             * bobot
             * weight
             */
            $weight =
                $weightRow->bobot
                ?? $weightRow->weight
                ?? null;

            if ($weight === null) {
                continue;
            }

            $weightsByCriteriaId[
                $weightRow->kriteria_id
            ] =
                (float) $weight;
        }

        /*
         * Susun persis:
         *
         * C1
         * C2
         * C3
         * C4
         * C5
         */
        $weights = [];

        foreach ($criteria as $criterion) {

            if (
                !array_key_exists(
                    $criterion->id,
                    $weightsByCriteriaId
                )
            ) {

                return [
                    'run' => $run,
                    'weights' => [],
                ];
            }

            $weights[] =
                $weightsByCriteriaId[
                    $criterion->id
                ];
        }

        /*
         * Normalisasi bobot.
         */
        $total =
            array_sum($weights);

        if (
            $total > 0
            && abs($total - 1) > 0.000001
        ) {

            foreach (
                $weights as $index => $weight
            ) {

                $weights[$index] =
                    $weight / $total;
            }
        }

        return [
            'run' => $run,
            'weights' => $weights,
        ];
    }

    /**
     * ============================================================
     * AMBIL ID MAHASISWA
     * ============================================================
     */
    protected function getMahasiswaId()
    {
        if (!Auth::check()) {
            return null;
        }

        $userId =
            Auth::id();

        return DB::table('mahasiswa')
            ->where(
                'user_id',
                $userId
            )
            ->value('id');
    }

    /**
     * ============================================================
     * SIMPAN HASIL TOPSIS
     * ============================================================
     */
    protected function saveTopsisRun(
        $questionnaireSessionId,
        array $ahpData,
        array $topsisResult,
        array $decisionMatrix,
        array $alternativeData,
        array $rankingResults
    ) {
        return DB::transaction(
            function () use (
                $questionnaireSessionId,
                $ahpData,
                $topsisResult,
                $decisionMatrix,
                $alternativeData,
                $rankingResults
            ) {

                /*
                 * ------------------------------------------------
                 * TOPSIS RUN
                 * ------------------------------------------------
                 */
                $runData = [];

                if (
                    Schema::hasColumn(
                        'topsis_runs',
                        'questionnaire_session_id'
                    )
                ) {

                    $runData[
                        'questionnaire_session_id'
                    ] =
                        $questionnaireSessionId;
                }

                if (
                    Schema::hasColumn(
                        'topsis_runs',
                        'ahp_run_id'
                    )
                ) {

                    $runData['ahp_run_id'] =
                        $ahpData['run']->id
                        ?? null;
                }

                if (
                    Schema::hasColumn(
                        'topsis_runs',
                        'status'
                    )
                ) {

                    $runData['status'] =
                        'completed';
                }

                if (
                    Schema::hasColumn(
                        'topsis_runs',
                        'created_at'
                    )
                ) {

                    $runData['created_at'] =
                        now();
                }

                if (
                    Schema::hasColumn(
                        'topsis_runs',
                        'updated_at'
                    )
                ) {

                    $runData['updated_at'] =
                        now();
                }

                $topsisRunId =
                    DB::table('topsis_runs')
                        ->insertGetId(
                            $runData
                        );

                /*
                 * ------------------------------------------------
                 * TOPSIS VALUES
                 * ------------------------------------------------
                 */
                foreach (
                    $alternativeData as $index => $alternative
                ) {

                    $ukmId =
                        $alternative['ukm_id'];

                    foreach (
                        $this->criteriaCodes
                        as $j => $code
                    ) {

                        $criterion =
                            DB::table('kriteria')
                                ->where(
                                    'kode',
                                    $code
                                )
                                ->first();

                        $criteriaId =
                            $criterion->id
                            ?? null;

                        $valueData = [
                            'topsis_run_id' =>
                                $topsisRunId,

                            'ukm_id' =>
                                $ukmId,

                            'kriteria_id' =>
                                $criteriaId,
                        ];

                        /*
                         * Nilai awal.
                         */
                        /*
                         * Nilai awal (kolom aktual DB: nilai_x).
                         */
                        if (
                            Schema::hasColumn(
                                'topsis_values',
                                'nilai_x'
                            )
                        ) {

                            $valueData['nilai_x'] =
                                $decisionMatrix[
                                    $index
                                ][$j];
                        }

                        /*
                         * Normalisasi.
                         */
                        /*
                         * Normalisasi (kolom aktual DB: nilai_r).
                         */
                        if (
                            Schema::hasColumn(
                                'topsis_values',
                                'nilai_r'
                            )
                        ) {

                            $valueData['nilai_r'] =
                                $topsisResult[
                                    'normalized_matrix'
                                ][$index][$j];
                        }

                        /*
                         * Normalisasi terbobot.
                         */
                        /*
                         * Normalisasi terbobot (kolom aktual DB: nilai_v).
                         */
                        if (
                            Schema::hasColumn(
                                'topsis_values',
                                'nilai_v'
                            )
                        ) {

                            $valueData['nilai_v'] =
                                $topsisResult[
                                    'weighted_normalized_matrix'
                                ][$index][$j];
                        }

                        if (
                            Schema::hasColumn(
                                'topsis_values',
                                'created_at'
                            )
                        ) {

                            $valueData[
                                'created_at'
                            ] = now();
                        }

                        if (
                            Schema::hasColumn(
                                'topsis_values',
                                'updated_at'
                            )
                        ) {

                            $valueData[
                                'updated_at'
                            ] = now();
                        }

                        DB::table(
                            'topsis_values'
                        )->insert(
                            $valueData
                        );
                    }
                }

                /*
                 * ------------------------------------------------
                 * TOPSIS IDEALS
                 * ------------------------------------------------
                 */
                foreach (
                    $this->criteriaCodes
                    as $j => $code
                ) {

                    $criterion =
                        DB::table('kriteria')
                            ->where(
                                'kode',
                                $code
                            )
                            ->first();

                    $idealData = [
                        'topsis_run_id' =>
                            $topsisRunId,

                        'kriteria_id' =>
                            $criterion->id
                            ?? null,
                    ];

                    /*
                     * Ideal positif (kolom aktual DB: ideal_positif).
                     */
                    if (
                        Schema::hasColumn(
                            'topsis_ideals',
                            'ideal_positif'
                        )
                    ) {

                        $idealData['ideal_positif'] =
                            $topsisResult[
                                'ideal_positive'
                            ][$j];
                    }

                    /*
                     * Ideal negatif (kolom aktual DB: ideal_negatif).
                     */
                    if (
                        Schema::hasColumn(
                            'topsis_ideals',
                            'ideal_negatif'
                        )
                    ) {

                        $idealData['ideal_negatif'] =
                            $topsisResult[
                                'ideal_negative'
                            ][$j];
                    }

                    if (
                        Schema::hasColumn(
                            'topsis_ideals',
                            'created_at'
                        )
                    ) {

                        $idealData[
                            'created_at'
                        ] = now();
                    }

                    if (
                        Schema::hasColumn(
                            'topsis_ideals',
                            'updated_at'
                        )
                    ) {

                        $idealData[
                            'updated_at'
                        ] = now();
                    }

                    DB::table(
                        'topsis_ideals'
                    )->insert(
                        $idealData
                    );
                }

                /*
                 * ------------------------------------------------
                 * TOPSIS RESULTS
                 * ------------------------------------------------
                 */
                foreach (
                    $rankingResults as $result
                ) {

                    $resultData = [
                        'topsis_run_id' =>
                            $topsisRunId,

                        'ukm_id' =>
                            $result['ukm_id'],
                    ];

                    /*
                     * Nilai preferensi.
                     */
                    if (
                        Schema::hasColumn(
                            'topsis_results',
                            'nilai_preferensi'
                        )
                    ) {

                        $resultData[
                            'nilai_preferensi'
                        ] =
                            $result[
                                'preference_value'
                            ];
                    }

                    /*
                     * Alternatif kolom nilai.
                     */
                    elseif (
                        Schema::hasColumn(
                            'topsis_results',
                            'nilai'
                        )
                    ) {

                        $resultData['nilai'] =
                            $result[
                                'preference_value'
                            ];
                    }

                    /*
                     * Ranking.
                     */
                    if (
                        Schema::hasColumn(
                            'topsis_results',
                            'ranking'
                        )
                    ) {

                        $resultData['ranking'] =
                            $result['ranking'];
                    }

                    /*
                     * Jarak positif.
                     */
                    /*
                     * Jarak ke ideal positif (kolom aktual DB: s_plus).
                     */
                    if (
                        Schema::hasColumn(
                            'topsis_results',
                            's_plus'
                        )
                    ) {

                        $resultData['s_plus'] =
                            $result[
                                'separation_positive'
                            ];
                    }

                    /*
                     * Jarak ke ideal negatif (kolom aktual DB: s_minus).
                     */
                    if (
                        Schema::hasColumn(
                            'topsis_results',
                            's_minus'
                        )
                    ) {

                        $resultData['s_minus'] =
                            $result[
                                'separation_negative'
                            ];
                    }

                    if (
                        Schema::hasColumn(
                            'topsis_results',
                            'created_at'
                        )
                    ) {

                        $resultData[
                            'created_at'
                        ] = now();
                    }

                    if (
                        Schema::hasColumn(
                            'topsis_results',
                            'updated_at'
                        )
                    ) {

                        $resultData[
                            'updated_at'
                        ] = now();
                    }

                    DB::table(
                        'topsis_results'
                    )->insert(
                        $resultData
                    );
                }

                return $topsisRunId;
            }
        );
    }

    /**
     * ============================================================
     * RESPONSE ERROR
     * ============================================================
     */
    protected function errorResponse(
        Request $request,
        string $message
    ) {

        if ($request->expectsJson()) {

            return response()->json([
                'success' => false,

                'message' =>
                    $message,
            ], 422);
        }

        return back()
            ->withInput()
            ->with(
                'error',
                $message
            );
    }

    /**
     * ============================================================
     * LIHAT HASIL TOPSIS BERDASARKAN RUN
     * ============================================================
     */
    public function show($id)
    {
        $run =
            DB::table('topsis_runs')
                ->where(
                    'id',
                    $id
                )
                ->first();

        if (!$run) {

            return back()
                ->with(
                    'error',
                    'Data proses TOPSIS tidak ditemukan.'
                );
        }

        $results =
            DB::table('topsis_results')
                ->join(
                    'ukm',
                    'ukm.id',
                    '=',
                    'topsis_results.ukm_id'
                )
                ->where(
                    'topsis_results.topsis_run_id',
                    $id
                )
                ->orderBy(
                    'topsis_results.ranking'
                )
                ->select(
                    'topsis_results.*',
                    'ukm.nama_ukm'
                )
                ->get();

        return view(
            'admin.topsis.show',
            [
                'run' =>
                    $run,

                'results' =>
                    $results,
            ]
        );
    }
}