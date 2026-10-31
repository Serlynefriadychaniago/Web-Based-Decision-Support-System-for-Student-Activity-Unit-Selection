<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\AhpRun;
use App\Models\AhpPairwise;
use App\Models\AhpWeight;
use App\Services\AhpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AHPController extends Controller
{
    /**
     * Menampilkan halaman AHP.
     *
     * Jika sudah ada hasil AHP di database,
     * hasil AHP terakhir akan ditampilkan kembali.
     */
    public function index(AhpService $ahpService)
    {
        // Pastikan collection selalu berurutan 0,1,2,3,...
        $kriteria = Kriteria::orderBy('kode')->get()->values();

        // Ambil proses AHP terakhir yang tersimpan
        $ahpRun = AhpRun::orderByDesc('id')->first();

        // Default data jika belum pernah ada proses AHP
        $data = [
            'matrix' => [],
            'columnSums' => [],
            'normalizedMatrix' => [],
            'relativeWeights' => [],
            'weights' => [],
            'weightedSum' => [],
            'consistencyVector' => [],
            'lambdaMax' => 0,
            'ci' => 0,
            'ri' => 0,
            'cr' => 0,
            'isConsistent' => false,
            'status' => null,
        ];

        /*
         * Jika belum ada data AHP,
         * tampilkan halaman kosong.
         */
        if (!$ahpRun) {
            return view(
                'admin.ahp.index',
                array_merge(
                    ['kriteria' => $kriteria],
                    $data
                )
            );
        }

        /*
         * ============================================================
         * AMBIL MATRIX AHP DARI DATABASE
         * ============================================================
         */
        $pairwise = AhpPairwise::where(
            'ahp_run_id',
            $ahpRun->id
        )->get();

        $matrix = [];

        foreach ($kriteria as $baris) {
            $matrix[$baris->id] = [];

            foreach ($kriteria as $kolom) {
                $pair = $pairwise->first(function ($item) use (
                    $baris,
                    $kolom
                ) {
                    return
                        (int) $item->kriteria_row_id === (int) $baris->id
                        &&
                        (int) $item->kriteria_col_id === (int) $kolom->id;
                });

                $matrix[$baris->id][$kolom->id] =
                    $pair
                        ? (float) $pair->nilai
                        : 0;
            }
        }

        /*
         * ============================================================
         * UBAH MATRIX ID MENJADI MATRIX NUMERIK
         * ============================================================
         */
        $numericMatrix = [];

        foreach ($kriteria as $baris) {
            $row = [];

            foreach ($kriteria as $kolom) {
                $row[] = (float) (
                    $matrix[$baris->id][$kolom->id] ?? 0
                );
            }

            $numericMatrix[] = $row;
        }

        /*
         * ============================================================
         * HITUNG ULANG DATA TAMPILAN DARI MATRIX DATABASE
         *
         * Ini BUKAN membuat data baru.
         * Ini hanya menghitung kembali data tampilan
         * berdasarkan matrix yang sudah tersimpan.
         * ============================================================
         */
        try {
            $result = $ahpService->calculate($numericMatrix);
        } catch (\Throwable $e) {
            return view(
                'admin.ahp.index',
                array_merge(
                    [
                        'kriteria' => $kriteria,
                    ],
                    $data
                )
            )->withErrors([
                'nilai' =>
                    'Data AHP tersimpan, tetapi hasil perhitungan tidak dapat ditampilkan: '
                    . $e->getMessage(),
            ]);
        }

        /*
         * ============================================================
         * BOBOT
         * ============================================================
         */
        $weights = [];

        foreach ($kriteria as $index => $item) {
            $weights[$item->id] = [
                'kode' => $item->kode,
                'nama' => $item->nama_kriteria,
                'bobot' => (float) (
                    $result['weights'][$index] ?? 0
                ),
            ];
        }

        /*
         * ============================================================
         * RELATIVE WEIGHTS
         * ============================================================
         */
        $relativeWeights = [];

        if (
            isset($result['relative_weights']) &&
            is_array($result['relative_weights']) &&
            count($result['relative_weights']) === $kriteria->count()
        ) {
            foreach ($kriteria as $index => $item) {
                $relativeWeights[$item->id] = [
                    'kode' => $item->kode,
                    'nama' => $item->nama_kriteria,
                    'bobot' => (float) (
                        $result['relative_weights'][$index] ?? 0
                    ),
                ];
            }
        } else {
            foreach (
                ($result['normalized_matrix'] ?? [])
                as $index => $row
            ) {
                if (!$kriteria->has($index)) {
                    continue;
                }

                $jumlahBaris = array_sum($row);

                $bobotRelatif =
                    $kriteria->count() > 0
                        ? $jumlahBaris / $kriteria->count()
                        : 0;

                $item = $kriteria[$index];

                $relativeWeights[$item->id] = [
                    'kode' => $item->kode,
                    'nama' => $item->nama_kriteria,
                    'bobot' => (float) $bobotRelatif,
                ];
            }
        }

        /*
         * ============================================================
         * CONSISTENCY VECTOR
         * ============================================================
         */
        $consistencyVector = [];

        if (
            isset($result['weighted_sum']) &&
            isset($result['weights']) &&
            count($result['weighted_sum']) === $kriteria->count() &&
            count($result['weights']) === $kriteria->count()
        ) {
            foreach ($kriteria as $index => $item) {
                $weight = (float) (
                    $result['weights'][$index] ?? 0
                );

                $weightedSum = (float) (
                    $result['weighted_sum'][$index] ?? 0
                );

                if (abs($weight) < 0.0000000001) {
                    $eigenValue = 0;
                } else {
                    $eigenValue =
                        $weightedSum / $weight;
                }

                $consistencyVector[] = $eigenValue;
            }
        }

        /*
         * ============================================================
         * LAMBDA MAX
         * ============================================================
         */
        if (
            count($consistencyVector) === $kriteria->count() &&
            $kriteria->count() > 0
        ) {
            $lambdaMax =
                array_sum($consistencyVector)
                / $kriteria->count();
        } else {
            $lambdaMax =
                (float) ($ahpRun->lambda_max ?? 0);
        }

        /*
         * ============================================================
         * CI
         * ============================================================
         */
        $jumlahKriteria = $kriteria->count();

        if ($jumlahKriteria > 1) {
            $ci =
                ($lambdaMax - $jumlahKriteria)
                / ($jumlahKriteria - 1);
        } else {
            $ci = 0;
        }

        /*
         * ============================================================
         * RI
         * ============================================================
         */
        $riTable = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];

        $ri =
            $riTable[$jumlahKriteria] ?? 1.12;

        /*
         * ============================================================
         * CR
         * ============================================================
         */
        if ($ri > 0) {
            $cr = $ci / $ri;
        } else {
            $cr = 0;
        }

        $isConsistent = $cr <= 0.10;

        $status =
            $isConsistent
                ? 'Konsisten'
                : 'Tidak Konsisten';

        /*
         * ============================================================
         * KIRIM HASIL KE VIEW
         * ============================================================
         */
        return view(
            'admin.ahp.index',
            [
                'kriteria' => $kriteria,

                'matrix' =>
                    $result['matrix'] ?? $numericMatrix,

                'columnSums' =>
                    $result['column_sums'] ?? [],

                'normalizedMatrix' =>
                    $result['normalized_matrix'] ?? [],

                'relativeWeights' =>
                    $relativeWeights,

                'weights' =>
                    $weights,

                'weightedSum' =>
                    $result['weighted_sum'] ?? [],

                'consistencyVector' =>
                    $consistencyVector,

                'lambdaMax' =>
                    $lambdaMax,

                'ci' =>
                    $ci,

                'ri' =>
                    $ri,

                'cr' =>
                    $cr,

                'isConsistent' =>
                    $isConsistent,

                'status' =>
                    $status,
            ]
        );
    }

    /**
     * Menyimpan dan menghitung matriks AHP.
     */
    public function store(
        Request $request,
        AhpService $ahpService
    ) {
        $kriteria = Kriteria::orderBy('kode')->get()->values();
        $jumlahKriteria = $kriteria->count();

        /*
         * ============================================================
         * VALIDASI
         * ============================================================
         */
        $request->validate([
            'nilai' => [
                'required',
                'array',
            ],
        ]);

        /*
         * ============================================================
         * BENTUK MATRIX
         * ============================================================
         */
        $matrix = [];

        foreach ($kriteria as $barisIndex => $baris) {
            $matrix[$baris->id] = [];

            foreach ($kriteria as $kolomIndex => $kolom) {

                // Diagonal
                if ($barisIndex === $kolomIndex) {
                    $matrix[$baris->id][$kolom->id] = 1;
                    continue;
                }

                // Segitiga atas
                if ($barisIndex < $kolomIndex) {

                    $value = $request->input(
                        "nilai.{$baris->id}.{$kolom->id}"
                    );

                    if ($value === null || $value === '') {
                        return back()
                            ->withInput()
                            ->withErrors([
                                'nilai' =>
                                    "Perbandingan {$baris->kode} terhadap {$kolom->kode} belum diisi."
                            ]);
                    }

                    $value = (float) $value;

                    if ($value <= 0) {
                        return back()
                            ->withInput()
                            ->withErrors([
                                'nilai' =>
                                    "Nilai perbandingan {$baris->kode} terhadap {$kolom->kode} harus lebih besar dari 0."
                            ]);
                    }

                    $matrix[$baris->id][$kolom->id] =
                        $value;
                }

                // Segitiga bawah
                else {

                    $nilaiAtas =
                        $matrix[$kolom->id][$baris->id];

                    if ($nilaiAtas <= 0) {
                        return back()
                            ->withInput()
                            ->withErrors([
                                'nilai' =>
                                    "Nilai kebalikan untuk {$baris->kode} terhadap {$kolom->kode} tidak dapat dibentuk."
                            ]);
                    }

                    $matrix[$baris->id][$kolom->id] =
                        1 / $nilaiAtas;
                }
            }
        }

        /*
         * ============================================================
         * MATRIX NUMERIK
         * ============================================================
         */
        $numericMatrix = [];

        foreach ($kriteria as $baris) {
            $row = [];

            foreach ($kriteria as $kolom) {
                $row[] =
                    (float) $matrix[$baris->id][$kolom->id];
            }

            $numericMatrix[] = $row;
        }

        /*
         * ============================================================
         * HITUNG AHP
         * ============================================================
         */
        try {
            $result =
                $ahpService->calculate($numericMatrix);
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'nilai' => $e->getMessage(),
                ]);
        }

        /*
         * ============================================================
         * BOBOT
         * ============================================================
         */
        $weights = [];

        foreach ($kriteria as $index => $item) {
            $weights[$item->id] = [
                'kode' => $item->kode,
                'nama' => $item->nama_kriteria,
                'bobot' =>
                    (float) ($result['weights'][$index] ?? 0),
            ];
        }

        /*
         * ============================================================
         * RELATIVE WEIGHTS
         * ============================================================
         */
        $relativeWeights = [];

        if (
            isset($result['relative_weights']) &&
            is_array($result['relative_weights']) &&
            count($result['relative_weights']) === $jumlahKriteria
        ) {
            foreach ($kriteria as $index => $item) {
                $relativeWeights[$item->id] = [
                    'kode' => $item->kode,
                    'nama' => $item->nama_kriteria,
                    'bobot' =>
                        (float) (
                            $result['relative_weights'][$index] ?? 0
                        ),
                ];
            }
        } else {
            foreach (
                ($result['normalized_matrix'] ?? [])
                as $index => $row
            ) {
                if (!$kriteria->has($index)) {
                    continue;
                }

                $jumlahBaris =
                    array_sum($row);

                $bobotRelatif =
                    $jumlahKriteria > 0
                        ? $jumlahBaris / $jumlahKriteria
                        : 0;

                $item =
                    $kriteria[$index];

                $relativeWeights[$item->id] = [
                    'kode' => $item->kode,
                    'nama' => $item->nama_kriteria,
                    'bobot' =>
                        (float) $bobotRelatif,
                ];
            }
        }

        /*
         * ============================================================
         * CONSISTENCY VECTOR
         * ============================================================
         */
        $consistencyVector = [];

        if (
            isset($result['weighted_sum']) &&
            isset($result['weights']) &&
            count($result['weighted_sum']) === $jumlahKriteria &&
            count($result['weights']) === $jumlahKriteria
        ) {
            foreach ($kriteria as $index => $item) {

                $weight =
                    (float) (
                        $result['weights'][$index] ?? 0
                    );

                $weightedSum =
                    (float) (
                        $result['weighted_sum'][$index] ?? 0
                    );

                if (abs($weight) < 0.0000000001) {
                    $eigenValue = 0;
                } else {
                    $eigenValue =
                        $weightedSum / $weight;
                }

                $consistencyVector[] =
                    $eigenValue;
            }
        }

        /*
         * ============================================================
         * LAMBDA MAX
         * ============================================================
         */
        if (
            count($consistencyVector) === $jumlahKriteria &&
            $jumlahKriteria > 0
        ) {
            $lambdaMax =
                array_sum($consistencyVector)
                / $jumlahKriteria;
        } else {
            $lambdaMax =
                (float) ($result['lambda_max'] ?? 0);
        }

        /*
         * ============================================================
         * CI
         * ============================================================
         */
        if ($jumlahKriteria > 1) {
            $ci =
                ($lambdaMax - $jumlahKriteria)
                / ($jumlahKriteria - 1);
        } else {
            $ci = 0;
        }

        /*
         * ============================================================
         * RI
         * ============================================================
         */
        $riTable = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];

        $ri =
            $riTable[$jumlahKriteria] ?? 1.12;

        /*
         * ============================================================
         * CR
         * ============================================================
         */
        if ($ri > 0) {
            $cr =
                $ci / $ri;
        } else {
            $cr = 0;
        }

        $isConsistent =
            $cr <= 0.10;

        $status =
            $isConsistent
                ? 'Konsisten'
                : 'Tidak Konsisten';

        /*
         * ============================================================
         * SIMPAN KE DATABASE
         * ============================================================
         */
        try {

            $ahpRun = DB::transaction(
                function () use (
                    $matrix,
                    $kriteria,
                    $weights,
                    $lambdaMax,
                    $ci,
                    $ri,
                    $cr,
                    $isConsistent
                ) {

                    $ahpRun =
                        AhpRun::create([
                            'created_by' =>
                                Auth::id(),

                            'lambda_max' =>
                                $lambdaMax,

                            'ci' =>
                                $ci,

                            'ri' =>
                                $ri,

                            'cr' =>
                                $cr,

                            'status' =>
                                $isConsistent
                                    ? 'valid'
                                    : 'invalid',
                        ]);

                    /*
                     * Simpan pairwise
                     */
                    foreach ($kriteria as $baris) {
                        foreach ($kriteria as $kolom) {

                            AhpPairwise::create([
                                'ahp_run_id' =>
                                    $ahpRun->id,

                                'kriteria_row_id' =>
                                    $baris->id,

                                'kriteria_col_id' =>
                                    $kolom->id,

                                'nilai' =>
                                    (float) (
                                        $matrix[$baris->id][$kolom->id]
                                    ),
                            ]);
                        }
                    }

                    /*
                     * Simpan bobot
                     */
                    foreach ($kriteria as $item) {

                        AhpWeight::create([
                            'ahp_run_id' =>
                                $ahpRun->id,

                            'kriteria_id' =>
                                $item->id,

                            'bobot' =>
                                (float) (
                                    $weights[$item->id]['bobot']
                                ),
                        ]);
                    }

                    return $ahpRun;
                }
            );

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'nilai' =>
                        'Hasil AHP berhasil dihitung, tetapi gagal disimpan ke database: '
                        . $e->getMessage(),
                ]);
        }

        /*
         * ============================================================
         * PENTING:
         *
         * Jangan return view hasil perhitungan langsung.
         *
         * Redirect ke index supaya index mengambil data
         * dari database.
         * ============================================================
         */
        return redirect()
            ->route('ahp.index')
            ->with('success', 'Hasil AHP berhasil disimpan.');
    }
}