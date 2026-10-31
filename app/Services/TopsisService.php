<?php

namespace App\Services;

use InvalidArgumentException;

class TopsisService
{
    /**
     * ============================================================
     * KONFIGURASI KRITERIA TOPSIS
     * ============================================================
     *
     * C1 = Minat      → Benefit
     * C2 = Bakat      → Benefit
     * C3 = Jadwal     → Benefit
     * C4 = Prestasi   → Benefit
     * C5 = Biaya      → Cost
     *
     * Urutan ini HARUS sama dengan urutan bobot AHP.
     */
    protected array $criteriaTypes = [
        'C1' => 'benefit',
        'C2' => 'benefit',
        'C3' => 'benefit',
        'C4' => 'benefit',
        'C5' => 'cost',
    ];

    /**
     * ============================================================
     * HITUNG SELURUH PROSES TOPSIS
     * ============================================================
     *
     * @param array $decisionMatrix
     * @param array $weights
     * @param array $criteriaCodes
     *
     * @return array
     */
    public function calculate(
        array $decisionMatrix,
        array $weights,
        array $criteriaCodes = ['C1', 'C2', 'C3', 'C4', 'C5'],
        array $customTypes = []
    ): array {
        /*
        |--------------------------------------------------------------------------
        | 1. VALIDASI DASAR
        |--------------------------------------------------------------------------
        */

        if (empty($decisionMatrix)) {
            throw new InvalidArgumentException(
                'Matriks keputusan TOPSIS tidak boleh kosong.'
            );
        }

        if (empty($weights)) {
            throw new InvalidArgumentException(
                'Bobot AHP tidak boleh kosong.'
            );
        }

        $numberOfAlternatives = count($decisionMatrix);
        $numberOfCriteria = count($criteriaCodes);

        if ($numberOfCriteria === 0) {
            throw new InvalidArgumentException(
                'Kriteria TOPSIS tidak boleh kosong.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. NORMALISASI INPUT
        |--------------------------------------------------------------------------
        */

        $decisionMatrix = $this->normalizeDecisionMatrix(
            $decisionMatrix,
            $numberOfCriteria
        );

        $weights = $this->normalizeWeights(
            $weights,
            $numberOfCriteria
        );

        /*
        |--------------------------------------------------------------------------
        | 3. VALIDASI JUMLAH BOBOT
        |--------------------------------------------------------------------------
        */

        if (count($weights) !== $numberOfCriteria) {
            throw new InvalidArgumentException(
                'Jumlah bobot AHP tidak sama dengan jumlah kriteria TOPSIS.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. VALIDASI TOTAL BOBOT
        |--------------------------------------------------------------------------
        |
        | Bobot AHP idealnya berjumlah 1.
        |
        */

        $weightTotal = array_sum($weights);

        if ($weightTotal <= 0) {
            throw new InvalidArgumentException(
                'Total bobot AHP harus lebih besar dari 0.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. NORMALISASI BOBOT
        |--------------------------------------------------------------------------
        |
        | Ini membuat sistem tetap aman jika bobot yang diterima
        | memiliki pembulatan kecil sehingga totalnya tidak tepat 1.
        |
        */

        if (abs($weightTotal - 1) > 0.000001) {
            foreach ($weights as $index => $weight) {
                $weights[$index] = $weight / $weightTotal;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6. NORMALISASI MATRIKS KEPUTUSAN
        |--------------------------------------------------------------------------
        |
        | Rumus:
        |
        |             xij
        | rij = ------------------
        |        sqrt(sum(xij^2))
        |
        */

        $normalizedMatrix = $this->calculateNormalizedMatrix(
            $decisionMatrix,
            $numberOfAlternatives,
            $numberOfCriteria
        );

        /*
        |--------------------------------------------------------------------------
        | 7. NORMALISASI TERBOBOT
        |--------------------------------------------------------------------------
        |
        | Rumus:
        |
        | vij = rij × wj
        |
        | Bobot wj berasal dari hasil AHP.
        |
        */

        $weightedNormalizedMatrix = $this->calculateWeightedMatrix(
            $normalizedMatrix,
            $weights,
            $numberOfAlternatives,
            $numberOfCriteria
        );

        /*
        |--------------------------------------------------------------------------
        | 8. SOLUSI IDEAL POSITIF DAN NEGATIF
        |--------------------------------------------------------------------------
        |
        | Benefit:
        | A+ = nilai terbesar
        | A- = nilai terkecil
        |
        | Cost:
        | A+ = nilai terkecil
        | A- = nilai terbesar
        |
        */

        $idealPositive = [];
        $idealNegative = [];

        for ($j = 0; $j < $numberOfCriteria; $j++) {

            $columnValues = [];

            for ($i = 0; $i < $numberOfAlternatives; $i++) {
                $columnValues[] =
                    $weightedNormalizedMatrix[$i][$j];
            }

            $criterionCode = $criteriaCodes[$j];

            $type = $customTypes[$criterionCode]
                ?? $this->criteriaTypes[$criterionCode]
                ?? 'benefit';

            if ($type === 'cost') {

                $idealPositive[$j] = min($columnValues);

                $idealNegative[$j] = max($columnValues);

            } else {

                $idealPositive[$j] = max($columnValues);

                $idealNegative[$j] = min($columnValues);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 9. SEPARASI POSITIF (S+)
        |--------------------------------------------------------------------------
        |
        | S+ = sqrt(
        |          Σ(vij - A+j)^2
        |      )
        |
        */

        $separationPositive = [];

        for ($i = 0; $i < $numberOfAlternatives; $i++) {

            $total = 0;

            for ($j = 0; $j < $numberOfCriteria; $j++) {

                $difference =
                    $weightedNormalizedMatrix[$i][$j]
                    - $idealPositive[$j];

                $total += pow($difference, 2);
            }

            $separationPositive[$i] = sqrt($total);
        }

        /*
        |--------------------------------------------------------------------------
        | 10. SEPARASI NEGATIF (S-)
        |--------------------------------------------------------------------------
        |
        | S- = sqrt(
        |          Σ(vij - A-j)^2
        |      )
        |
        */

        $separationNegative = [];

        for ($i = 0; $i < $numberOfAlternatives; $i++) {

            $total = 0;

            for ($j = 0; $j < $numberOfCriteria; $j++) {

                $difference =
                    $weightedNormalizedMatrix[$i][$j]
                    - $idealNegative[$j];

                $total += pow($difference, 2);
            }

            $separationNegative[$i] = sqrt($total);
        }

        /*
        |--------------------------------------------------------------------------
        | 11. NILAI PREFERENSI
        |--------------------------------------------------------------------------
        |
        | Ci = S- / (S- + S+)
        |
        | Semakin mendekati 1 → semakin baik.
        |
        */

        $preferenceValues = [];

        for ($i = 0; $i < $numberOfAlternatives; $i++) {

            $sPlus = $separationPositive[$i];
            $sMinus = $separationNegative[$i];

            $denominator = $sMinus + $sPlus;

            if ($denominator == 0) {

                $preferenceValues[$i] = 0;

            } else {

                $preferenceValues[$i] =
                    $sMinus / $denominator;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 12. RANKING
        |--------------------------------------------------------------------------
        |
        | Nilai preferensi terbesar mendapatkan ranking 1.
        |
        */

        $ranking = [];

        $sortedPreferences = $preferenceValues;

        arsort($sortedPreferences);

        $rank = 1;

        foreach ($sortedPreferences as $index => $value) {

            $ranking[$index] = $rank;

            $rank++;
        }

        /*
        |--------------------------------------------------------------------------
        | 13. RETURN SELURUH HASIL
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | INPUT
            |--------------------------------------------------------------------------
            */

            'decision_matrix' =>
                $decisionMatrix,

            'weights' =>
                $weights,

            'criteria_codes' =>
                $criteriaCodes,

            'criteria_types' =>
                $this->criteriaTypes,

            /*
            |--------------------------------------------------------------------------
            | NORMALISASI
            |--------------------------------------------------------------------------
            */

            'normalized_matrix' =>
                $normalizedMatrix,

            /*
            |--------------------------------------------------------------------------
            | NORMALISASI TERBOBOT
            |--------------------------------------------------------------------------
            */

            'weighted_normalized_matrix' =>
                $weightedNormalizedMatrix,

            /*
            |--------------------------------------------------------------------------
            | SOLUSI IDEAL
            |--------------------------------------------------------------------------
            */

            'ideal_positive' =>
                $idealPositive,

            'ideal_negative' =>
                $idealNegative,

            /*
            |--------------------------------------------------------------------------
            | SEPARASI
            |--------------------------------------------------------------------------
            */

            'separation_positive' =>
                $separationPositive,

            'separation_negative' =>
                $separationNegative,

            /*
            |--------------------------------------------------------------------------
            | PREFERENSI
            |--------------------------------------------------------------------------
            */

            'preference_values' =>
                $preferenceValues,

            /*
            |--------------------------------------------------------------------------
            | RANKING
            |--------------------------------------------------------------------------
            */

            'ranking' =>
                $ranking,
        ];
    }

    /**
     * ============================================================
     * NORMALISASI MATRIKS INPUT
     * ============================================================
     */
    protected function normalizeDecisionMatrix(
        array $matrix,
        int $numberOfCriteria
    ): array {

        $result = [];

        foreach ($matrix as $alternativeIndex => $row) {

            if (!is_array($row)) {
                throw new InvalidArgumentException(
                    "Data alternatif [$alternativeIndex] tidak valid."
                );
            }

            if (count($row) !== $numberOfCriteria) {
                throw new InvalidArgumentException(
                    "Jumlah nilai alternatif [$alternativeIndex] tidak sesuai dengan jumlah kriteria."
                );
            }

            $result[$alternativeIndex] = [];

            foreach ($row as $criterionIndex => $value) {

                if ($value === null || $value === '') {
                    throw new InvalidArgumentException(
                        "Nilai kriteria [$criterionIndex] pada alternatif [$alternativeIndex] belum diisi."
                    );
                }

                if (!is_numeric($value)) {
                    throw new InvalidArgumentException(
                        "Nilai kriteria [$criterionIndex] pada alternatif [$alternativeIndex] harus berupa angka."
                    );
                }

                $value = (float) $value;

                if ($value < 0) {
                    throw new InvalidArgumentException(
                        "Nilai TOPSIS tidak boleh negatif."
                    );
                }

                $result[$alternativeIndex][$criterionIndex] =
                    $value;
            }
        }

        return $result;
    }

    /**
     * ============================================================
     * NORMALISASI BOBOT
     * ============================================================
     */
    protected function normalizeWeights(
        array $weights,
        int $numberOfCriteria
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Bobot bisa diterima sebagai:
        |
        | [0.575, 0.238, 0.096, 0.053, 0.038]
        |
        | atau:
        |
        | ['C1' => 0.575, ...]
        |
        |--------------------------------------------------------------------------
        */

        $result = [];

        if (array_keys($weights) !== range(0, count($weights) - 1)) {

            foreach ($weights as $weight) {
                $result[] = $weight;
            }

        } else {

            $result = array_values($weights);
        }

        if (count($result) !== $numberOfCriteria) {
            throw new InvalidArgumentException(
                'Jumlah bobot tidak sesuai dengan jumlah kriteria.'
            );
        }

        foreach ($result as $index => $weight) {

            if (!is_numeric($weight)) {
                throw new InvalidArgumentException(
                    "Bobot kriteria [$index] harus berupa angka."
                );
            }

            $result[$index] = (float) $weight;

            if ($result[$index] < 0) {
                throw new InvalidArgumentException(
                    "Bobot kriteria [$index] tidak boleh negatif."
                );
            }
        }

        return $result;
    }

    /**
     * ============================================================
     * HITUNG NORMALISASI MATRiks
     * ============================================================
     */
    protected function calculateNormalizedMatrix(
        array $decisionMatrix,
        int $numberOfAlternatives,
        int $numberOfCriteria
    ): array {

        $normalizedMatrix = [];

        /*
        |--------------------------------------------------------------------------
        | Hitung denominator setiap kolom
        |--------------------------------------------------------------------------
        |
        | sqrt(
        |     x1² + x2² + ... + xm²
        | )
        |
        */

        $denominators = [];

        for ($j = 0; $j < $numberOfCriteria; $j++) {

            $sumOfSquares = 0;

            for ($i = 0; $i < $numberOfAlternatives; $i++) {

                $value =
                    $decisionMatrix[$i][$j];

                $sumOfSquares += pow($value, 2);
            }

            $denominators[$j] =
                sqrt($sumOfSquares);
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung rij
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $numberOfAlternatives; $i++) {

            $normalizedMatrix[$i] = [];

            for ($j = 0; $j < $numberOfCriteria; $j++) {

                if ($denominators[$j] == 0) {

                    $normalizedMatrix[$i][$j] = 0;

                } else {

                    $normalizedMatrix[$i][$j] =
                        $decisionMatrix[$i][$j]
                        / $denominators[$j];
                }
            }
        }

        return $normalizedMatrix;
    }

    /**
     * ============================================================
     * HITUNG NORMALISASI TERBOBOT
     * ============================================================
     *
     * vij = rij × wj
     */
    protected function calculateWeightedMatrix(
        array $normalizedMatrix,
        array $weights,
        int $numberOfAlternatives,
        int $numberOfCriteria
    ): array {

        $weightedMatrix = [];

        for ($i = 0; $i < $numberOfAlternatives; $i++) {

            $weightedMatrix[$i] = [];

            for ($j = 0; $j < $numberOfCriteria; $j++) {

                $weightedMatrix[$i][$j] =
                    $normalizedMatrix[$i][$j]
                    * $weights[$j];
            }
        }

        return $weightedMatrix;
    }
}