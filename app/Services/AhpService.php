<?php

namespace App\Services;

class AhpService
{
    /**
     * Nilai Random Index (RI) berdasarkan jumlah kriteria.
     */
    protected array $randomIndex = [
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
        11 => 1.51,
        12 => 1.48,
        13 => 1.56,
        14 => 1.57,
        15 => 1.59,
    ];

    /**
     * Menghitung seluruh proses AHP.
     *
     * @param array $matrix
     * @return array
     */
    public function calculate(array $matrix): array
    {
        $n = count($matrix);

        if ($n === 0) {
            throw new \InvalidArgumentException(
                'Matriks AHP tidak boleh kosong.'
            );
        }

        if (!isset($this->randomIndex[$n])) {
            throw new \InvalidArgumentException(
                'Jumlah kriteria belum memiliki nilai Random Index.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Pastikan matriks berbentuk numerik
        |--------------------------------------------------------------------------
        */
        $matrix = $this->normalizeInputMatrix($matrix, $n);

        /*
        |--------------------------------------------------------------------------
        | 2. Hitung jumlah setiap kolom
        |--------------------------------------------------------------------------
        */
        $columnSums = array_fill(0, $n, 0);

        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Matriks Normalisasi
        |--------------------------------------------------------------------------
        */
        $normalizedMatrix = [];

        for ($i = 0; $i < $n; $i++) {
            $normalizedMatrix[$i] = [];

            for ($j = 0; $j < $n; $j++) {

                if ($columnSums[$j] == 0) {
                    $normalizedMatrix[$i][$j] = 0;
                } else {
                    $normalizedMatrix[$i][$j] =
                        $matrix[$i][$j] / $columnSums[$j];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Bobot Prioritas
        |--------------------------------------------------------------------------
        |
        | Bobot prioritas diperoleh dari rata-rata setiap baris
        | matriks normalisasi.
        |
        | Rumus:
        |
        | Wi = jumlah baris normalisasi / jumlah kriteria
        |
        */
        $priorityWeights = [];

        for ($i = 0; $i < $n; $i++) {

            $rowTotal = array_sum($normalizedMatrix[$i]);

            $priorityWeights[$i] = $rowTotal / $n;
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Vektor sesuai rumus Excel
        |--------------------------------------------------------------------------
        |
        | Rumus Excel:
        |
        | =(D44*$I$44)
        | +(E44*$I$45)
        | +(F44*$I$46)
        | +(G44*$I$47)
        | +(H44*$I$48)
        |
        | Artinya:
        |
        | Vektor_i =
        | (Normalisasi_i1 × Bobot_1)
        | +(Normalisasi_i2 × Bobot_2)
        | +(Normalisasi_i3 × Bobot_3)
        | +(Normalisasi_i4 × Bobot_4)
        | +(Normalisasi_i5 × Bobot_5)
        |
        */
        $vector = [];

        for ($i = 0; $i < $n; $i++) {

            $total = 0;

            for ($j = 0; $j < $n; $j++) {

                $total +=
                    $normalizedMatrix[$i][$j]
                    * $priorityWeights[$j];
            }

            $vector[$i] = $total;
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Weighted Sum Vector
        |--------------------------------------------------------------------------
        |
        | Untuk uji konsistensi AHP standar:
        |
        | Weighted Sum Vector = Matriks Awal × Bobot Prioritas
        |
        */
        $weightedSum = [];

        for ($i = 0; $i < $n; $i++) {

            $total = 0;

            for ($j = 0; $j < $n; $j++) {

                $total +=
                    $matrix[$i][$j]
                    * $priorityWeights[$j];
            }

            $weightedSum[$i] = $total;
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Consistency Vector
        |--------------------------------------------------------------------------
        |
        | Rumus:
        |
        | Consistency Vector =
        | Weighted Sum / Bobot Prioritas
        |
        */
        $consistencyVector = [];

        for ($i = 0; $i < $n; $i++) {

            if ($priorityWeights[$i] == 0) {

                $consistencyVector[$i] = 0;

            } else {

                $consistencyVector[$i] =
                    $weightedSum[$i]
                    / $priorityWeights[$i];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Lambda Max
        |--------------------------------------------------------------------------
        */
        $lambdaMax =
            array_sum($consistencyVector) / $n;

        /*
        |--------------------------------------------------------------------------
        | 9. Consistency Index (CI)
        |--------------------------------------------------------------------------
        */
        if ($n <= 1) {

            $ci = 0;

        } else {

            $ci =
                ($lambdaMax - $n)
                / ($n - 1);
        }

        /*
        |--------------------------------------------------------------------------
        | 10. Random Index (RI)
        |--------------------------------------------------------------------------
        */
        $ri = $this->randomIndex[$n];

        /*
        |--------------------------------------------------------------------------
        | 11. Consistency Ratio (CR)
        |--------------------------------------------------------------------------
        */
        if ($ri == 0) {

            $cr = 0;

        } else {

            $cr = $ci / $ri;
        }

        /*
        |--------------------------------------------------------------------------
        | 12. Status Konsistensi
        |--------------------------------------------------------------------------
        */
        $isConsistent = $cr <= 0.10;

        /*
        |--------------------------------------------------------------------------
        | 13. Return seluruh hasil
        |--------------------------------------------------------------------------
        */
        return [

            // Matriks awal
            'matrix' => $matrix,

            // Jumlah kolom
            'column_sums' => $columnSums,

            // Matriks normalisasi
            'normalized_matrix' => $normalizedMatrix,

            // Bobot prioritas
            'priority_weights' => $priorityWeights,

            // Tetap disediakan agar kompatibel dengan controller lama
            'relative_weights' => $priorityWeights,

            // Bobot yang digunakan TOPSIS
            'weights' => $priorityWeights,

            // VEKTOR SESUAI RUMUS EXCEL
            'vector' => $vector,

            // Weighted Sum untuk consistency
            'weighted_sum' => $weightedSum,

            // Consistency Vector
            'consistency_vector' => $consistencyVector,

            // Lambda Max
            'lambda_max' => $lambdaMax,

            // CI
            'ci' => $ci,

            // RI
            'ri' => $ri,

            // CR
            'cr' => $cr,

            // Status
            'is_consistent' => $isConsistent,

            'status' => $isConsistent
                ? 'Konsisten'
                : 'Tidak Konsisten',
        ];
    }

    /**
     * Membersihkan dan memastikan matriks valid.
     */
    protected function normalizeInputMatrix(
        array $matrix,
        int $n
    ): array {

        $result = [];

        for ($i = 0; $i < $n; $i++) {

            $result[$i] = [];

            for ($j = 0; $j < $n; $j++) {

                if (!isset($matrix[$i][$j])) {

                    throw new \InvalidArgumentException(
                        "Nilai matriks [$i][$j] tidak ditemukan."
                    );
                }

                $value = (float) $matrix[$i][$j];

                if ($value <= 0) {

                    throw new \InvalidArgumentException(
                        "Nilai matriks harus lebih besar dari 0."
                    );
                }

                $result[$i][$j] = $value;
            }
        }

        return $result;
    }
}