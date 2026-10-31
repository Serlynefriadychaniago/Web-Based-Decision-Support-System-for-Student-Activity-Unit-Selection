<?php

namespace Tests\Unit;

use App\Services\StudentMatchingService;
use App\Services\TopsisService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SPKMatchingTest extends TestCase
{
    public function test_student_matching_and_topsis_calculation_for_computer_education()
    {
        // 1. Verify 7 UKMs exist in database
        $ukmCount = DB::table('ukm')->count();
        $this->assertGreaterThanOrEqual(7, $ukmCount);

        // 2. Verify all 7 UKMs have C1..C5 profile entries
        $profileCount = DB::table('ukm_kriteria_profile')->count();
        $this->assertGreaterThanOrEqual(35, $profileCount);

        // 3. Create dummy student & questionnaire session
        $userId = DB::table('users')->insertGetId([
            'name'       => 'Test Student IT',
            'username'   => 'testmhs_it_' . time() . '_' . rand(1, 999),
            'email'      => 'testmhs_it_' . time() . '_' . rand(1, 999) . '@example.com',
            'password'   => bcrypt('password'),
            'role'       => 'mahasiswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mhsId = DB::table('mahasiswa')->insertGetId([
            'user_id'    => $userId,
            'nim'        => '12345' . rand(1000, 9999),
            'nama'       => 'Mahasiswa IT',
            'prodi'      => 'Teknik Informatika',
            'fakultas'   => 'Teknik',
            'angkatan'   => '2023',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sessionId = DB::table('questionnaire_sessions')->insertGetId([
            'mahasiswa_id' => $mhsId,
            'status'       => 'completed',
            'started_at'   => now(),
            'completed_at' => now(),
        ]);

        // Student chooses IT & Komputer (C1 sub_kriteria_id = 4, C2 sub_kriteria_id = 11)
        DB::table('questionnaire_answers')->insert([
            ['session_id' => $sessionId, 'kriteria_id' => 1, 'sub_kriteria_id' => 4,  'nilai' => 5.0],
            ['session_id' => $sessionId, 'kriteria_id' => 2, 'sub_kriteria_id' => 11, 'nilai' => 5.0],
            ['session_id' => $sessionId, 'kriteria_id' => 3, 'sub_kriteria_id' => 18, 'nilai' => 3.0],
            ['session_id' => $sessionId, 'kriteria_id' => 4, 'sub_kriteria_id' => 21, 'nilai' => 5.0],
            ['session_id' => $sessionId, 'kriteria_id' => 5, 'sub_kriteria_id' => 27, 'nilai' => 2.0],
        ]);

        // 4. Build Decision Matrix via StudentMatchingService
        $matchingService = new StudentMatchingService();
        $matchingData = $matchingService->buildDecisionMatrix($sessionId);

        $this->assertNotEmpty($matchingData['decision_matrix']);
        $this->assertCount(7, $matchingData['decision_matrix']);

        // 5. Calculate TOPSIS
        $topsisService = new TopsisService();
        $weights = [0.35, 0.25, 0.15, 0.15, 0.10];
        $criteriaCodes = ['C1', 'C2', 'C3', 'C4', 'C5'];

        $result = $topsisService->calculate(
            $matchingData['decision_matrix'],
            $weights,
            $criteriaCodes
        );

        // Find ranking of Computer Education
        $computerEducationIdx = null;
        foreach ($matchingData['alternative_data'] as $idx => $alt) {
            if (str_contains(strtolower($alt['nama']), 'computer')) {
                $computerEducationIdx = $idx;
                break;
            }
        }

        $this->assertNotNull($computerEducationIdx);
        $this->assertEquals(1, $result['ranking'][$computerEducationIdx]);
    }

    public function test_student_matching_and_topsis_calculation_for_psm()
    {
        $userId = DB::table('users')->insertGetId([
            'name'       => 'Test Student PSM',
            'username'   => 'testmhs_psm_' . time() . '_' . rand(1, 999),
            'email'      => 'testmhs_psm_' . time() . '_' . rand(1, 999) . '@example.com',
            'password'   => bcrypt('password'),
            'role'       => 'mahasiswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mhsId = DB::table('mahasiswa')->insertGetId([
            'user_id'    => $userId,
            'nim'        => '12345' . rand(1000, 9999),
            'nama'       => 'Mahasiswa Seni Vokal',
            'prodi'      => 'Seni Musik',
            'fakultas'   => 'Seni',
            'angkatan'   => '2023',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sessionId = DB::table('questionnaire_sessions')->insertGetId([
            'mahasiswa_id' => $mhsId,
            'status'       => 'completed',
            'started_at'   => now(),
            'completed_at' => now(),
        ]);

        // Student chooses PSM (C1 sub_kriteria_id = 2, C2 sub_kriteria_id = 9)
        DB::table('questionnaire_answers')->insert([
            ['session_id' => $sessionId, 'kriteria_id' => 1, 'sub_kriteria_id' => 2,  'nilai' => 5.0],
            ['session_id' => $sessionId, 'kriteria_id' => 2, 'sub_kriteria_id' => 9,  'nilai' => 5.0],
            ['session_id' => $sessionId, 'kriteria_id' => 3, 'sub_kriteria_id' => 17, 'nilai' => 4.0],
            ['session_id' => $sessionId, 'kriteria_id' => 4, 'sub_kriteria_id' => 22, 'nilai' => 4.0],
            ['session_id' => $sessionId, 'kriteria_id' => 5, 'sub_kriteria_id' => 28, 'nilai' => 3.0],
        ]);

        $matchingService = new StudentMatchingService();
        $matchingData = $matchingService->buildDecisionMatrix($sessionId);

        $topsisService = new TopsisService();
        $weights = [0.35, 0.25, 0.15, 0.15, 0.10];
        $criteriaCodes = ['C1', 'C2', 'C3', 'C4', 'C5'];

        $result = $topsisService->calculate(
            $matchingData['decision_matrix'],
            $weights,
            $criteriaCodes
        );

        $psmIdx = null;
        foreach ($matchingData['alternative_data'] as $idx => $alt) {
            if (str_contains(strtolower($alt['nama']), 'psm')) {
                $psmIdx = $idx;
                break;
            }
        }

        $this->assertNotNull($psmIdx);
        $this->assertEquals(1, $result['ranking'][$psmIdx]);
    }
}
