<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StudentMatchingService
{
    /**
     * Membentuk Matriks Keputusan X (7 UKM x 5 Kriteria)
     * berdasarkan Jawaban Kuesioner Mahasiswa & Profil 7 UKM dari Database.
     *
     * @param int $questionnaireSessionId
     * @return array
     */
    public function buildDecisionMatrix(int $questionnaireSessionId): array
    {
        // 1. Ambil seluruh Kriteria dari database
        $criteria = DB::table('kriteria')
            ->orderBy('id', 'asc')
            ->get();

        // 2. Ambil 7 UKM
        $ukmList = DB::table('ukm')->orderBy('id')->get();

        // 3. Ambil profil kriteria UKM (ukm_kriteria_profile)
        $profiles = DB::table('ukm_kriteria_profile')->get();

        $ukmProfileMap = [];
        foreach ($profiles as $p) {
            $ukmProfileMap[$p->ukm_id][$p->kriteria_id] = [
                'sub_kriteria_id' => $p->sub_kriteria_id,
                'nilai'           => (float) $p->nilai,
            ];
        }

        // 4. Ambil jawaban kuesioner mahasiswa
        $answers = DB::table('questionnaire_answers')
            ->where('session_id', $questionnaireSessionId)
            ->get();

        $studentAnswerMap = [];
        foreach ($answers as $ans) {
            $subKrit = DB::table('sub_kriteria')->where('id', $ans->sub_kriteria_id)->first();

            $studentAnswerMap[$ans->kriteria_id] = [
                'sub_kriteria_id' => $ans->sub_kriteria_id,
                'nilai'           => $ans->nilai !== null ? (float) $ans->nilai : ($subKrit ? (float) $subKrit->urutan : 3.0),
                'urutan'          => $subKrit ? (float) $subKrit->urutan : 3.0,
            ];
        }

        // 5. Bentuk Matriks Keputusan X (7 UKM x 5 Kriteria)
        $decisionMatrix = [];
        $alternativeData = [];

        foreach ($ukmList as $ukmIdx => $ukm) {
            $row = [];

            foreach ($criteria as $critIdx => $criterion) {
                $critId = $criterion->id;
                $critCode = $criterion->kode;

                $ukmProf = $ukmProfileMap[$ukm->id][$critId] ?? null;
                $studentAns = $studentAnswerMap[$critId] ?? null;

                $score = 1.0; // Baseline minimum score

                if ($ukmProf && $studentAns) {
                    if (in_array($critCode, ['C1', 'C2'])) {
                        /*
                         * ----------------------------------------------------
                         * C1 (MINAT) & C2 (BAKAT): DOMAIN MATCHING
                         * ----------------------------------------------------
                         * Jika sub_kriteria_id pilihan mahasiswa SAMA DENGAN
                         * sub_kriteria_id profil UKM => Score = Nilai Profil UKM (misal 5.0)
                         * Jika BEDA => Score = 1.0 (Baseline Non-Match)
                         */
                        if ((int)$studentAns['sub_kriteria_id'] === (int)$ukmProf['sub_kriteria_id']) {
                            $score = $ukmProf['nilai'] > 0 ? $ukmProf['nilai'] : 5.0;
                        } else {
                            $score = 1.0;
                        }

                    } else {
                        /*
                         * ----------------------------------------------------
                         * C3 (JADWAL), C4 (PRESTASI), C5 (BIAYA): GAP ANALYSIS
                         * ----------------------------------------------------
                         * Hitung Gap = |Nilai/Urutan Mahasiswa - Nilai/Urutan UKM|
                         * Skor Kesesuaian = max(1.0, 5.0 - Gap)
                         */
                        $valMhs = $studentAns['nilai'] ?? $studentAns['urutan'] ?? 3.0;
                        $valUkm = $ukmProf['nilai'] ?? 3.0;

                        $gap = abs($valMhs - $valUkm);
                        $score = max(1.0, 5.0 - $gap);
                    }
                } else if ($ukmProf) {
                    $score = $ukmProf['nilai'] > 0 ? $ukmProf['nilai'] : 1.0;
                }

                $row[] = round($score, 4);
            }

            $decisionMatrix[] = $row;

            $alternativeData[$ukmIdx] = [
                'ukm_id' => $ukm->id,
                'nama'   => $ukm->nama_ukm ?? ('UKM ' . ($ukmIdx + 1)),
            ];
        }

        return [
            'decision_matrix'  => $decisionMatrix,
            'alternative_data' => $alternativeData,
            'criteria'         => $criteria,
            'ukm'              => $ukmList,
        ];
    }
}
