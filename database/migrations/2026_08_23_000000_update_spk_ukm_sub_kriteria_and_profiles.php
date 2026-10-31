<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update sub_kriteria untuk C1 (Minat)
        $c1SubKriteria = [
            2 => ['nama' => 'Minat pada Seni Vokal & Musik (Bernyanyi / Pertunjukan)', 'urutan' => 1],
            3 => ['nama' => 'Minat pada Pendakian, Survival & Alam Bebas (Petualangan / Lingkungan)', 'urutan' => 2],
            4 => ['nama' => 'Minat pada Teknologi Informasi & Komputer (Pemrograman / IT)', 'urutan' => 3],
            5 => ['nama' => 'Minat pada Konseling Sebaya, Edukasi & Informasi Kesehatan', 'urutan' => 4],
            6 => ['nama' => 'Minat pada Seni Peran, Teater, Sastra & Panggung', 'urutan' => 5],
            7 => ['nama' => 'Minat pada Aktivitas Olahraga, Latihan Fisik & Kompetisi', 'urutan' => 6],
            8 => ['nama' => 'Minat pada Musik, Marching Band & Pertunjukan Musik', 'urutan' => 7],
        ];

        foreach ($c1SubKriteria as $id => $data) {
            DB::table('sub_kriteria')->updateOrInsert(
                ['id' => $id],
                [
                    'kriteria_id'      => 1,
                    'nama_subkriteria' => $data['nama'],
                    'urutan'           => $data['urutan'],
                    'status'           => 'aktif',
                    'updated_at'       => now(),
                ]
            );
        }

        // 2. Update sub_kriteria untuk C2 (Bakat)
        $c2SubKriteria = [
            9  => ['nama' => 'Bakat Seni Vokal & Kemampuan Bermusik (Vokal / Musik)', 'urutan' => 1],
            10 => ['nama' => 'Kemampuan Kegiatan Alam Bebas & Ketahanan Fisik (Outdoor / Survival)', 'urutan' => 2],
            11 => ['nama' => 'Kemampuan Komputer, IT & Pemrograman (Logika / Pemrograman)', 'urutan' => 3],
            12 => ['nama' => 'Kemampuan Komunikasi, Konseling & Edukasi Kesehatan', 'urutan' => 4],
            13 => ['nama' => 'Kemampuan Akting, Seni Peran & Sastra / Panggung', 'urutan' => 5],
            14 => ['nama' => 'Bakat Olahraga, Ketangkasan Fisik & Atletis', 'urutan' => 6],
            15 => ['nama' => 'Kemampuan Musikalitas, Memainkan Alat Musik & Ritme', 'urutan' => 7],
        ];

        foreach ($c2SubKriteria as $id => $data) {
            DB::table('sub_kriteria')->updateOrInsert(
                ['id' => $id],
                [
                    'kriteria_id'      => 2,
                    'nama_subkriteria' => $data['nama'],
                    'urutan'           => $data['urutan'],
                    'status'           => 'aktif',
                    'updated_at'       => now(),
                ]
            );
        }

        // 3. Mapping profil 5 kriteria untuk 7 UKM
        $ukms = DB::table('ukm')->get();
        $ukmMap = [];
        foreach ($ukms as $u) {
            $namaLower = strtolower($u->nama_ukm);
            if (str_contains($namaLower, 'psm')) {
                $ukmMap['psm'] = $u->id;
            } elseif (str_contains($namaLower, 'mapala')) {
                $ukmMap['mapala'] = $u->id;
            } elseif (str_contains($namaLower, 'computer')) {
                $ukmMap['computer'] = $u->id;
            } elseif (str_contains($namaLower, 'pik')) {
                $ukmMap['pikm'] = $u->id;
            } elseif (str_contains($namaLower, 'teater')) {
                $ukmMap['teater'] = $u->id;
            } elseif (str_contains($namaLower, 'olahraga')) {
                $ukmMap['olahraga'] = $u->id;
            } elseif (str_contains($namaLower, 'marching')) {
                $ukmMap['marching'] = $u->id;
            }
        }

        $profilesConfig = [
            'psm' => [
                ['kriteria_id' => 1, 'sub_kriteria_id' => 2,  'nilai' => 5.00],
                ['kriteria_id' => 2, 'sub_kriteria_id' => 9,  'nilai' => 5.00],
                ['kriteria_id' => 3, 'sub_kriteria_id' => 17, 'nilai' => 4.00],
                ['kriteria_id' => 4, 'sub_kriteria_id' => 22, 'nilai' => 4.00],
                ['kriteria_id' => 5, 'sub_kriteria_id' => 28, 'nilai' => 3.00],
            ],
            'mapala' => [
                ['kriteria_id' => 1, 'sub_kriteria_id' => 3,  'nilai' => 5.00],
                ['kriteria_id' => 2, 'sub_kriteria_id' => 10, 'nilai' => 5.00],
                ['kriteria_id' => 3, 'sub_kriteria_id' => 16, 'nilai' => 5.00],
                ['kriteria_id' => 4, 'sub_kriteria_id' => 22, 'nilai' => 4.00],
                ['kriteria_id' => 5, 'sub_kriteria_id' => 28, 'nilai' => 3.00],
            ],
            'computer' => [
                ['kriteria_id' => 1, 'sub_kriteria_id' => 4,  'nilai' => 5.00],
                ['kriteria_id' => 2, 'sub_kriteria_id' => 11, 'nilai' => 5.00],
                ['kriteria_id' => 3, 'sub_kriteria_id' => 18, 'nilai' => 3.00],
                ['kriteria_id' => 4, 'sub_kriteria_id' => 21, 'nilai' => 5.00],
                ['kriteria_id' => 5, 'sub_kriteria_id' => 27, 'nilai' => 2.00],
            ],
            'pikm' => [
                ['kriteria_id' => 1, 'sub_kriteria_id' => 5,  'nilai' => 5.00],
                ['kriteria_id' => 2, 'sub_kriteria_id' => 12, 'nilai' => 5.00],
                ['kriteria_id' => 3, 'sub_kriteria_id' => 17, 'nilai' => 4.00],
                ['kriteria_id' => 4, 'sub_kriteria_id' => 23, 'nilai' => 3.00],
                ['kriteria_id' => 5, 'sub_kriteria_id' => 29, 'nilai' => 4.00],
            ],
            'teater' => [
                ['kriteria_id' => 1, 'sub_kriteria_id' => 6,  'nilai' => 5.00],
                ['kriteria_id' => 2, 'sub_kriteria_id' => 13, 'nilai' => 5.00],
                ['kriteria_id' => 3, 'sub_kriteria_id' => 18, 'nilai' => 3.00],
                ['kriteria_id' => 4, 'sub_kriteria_id' => 23, 'nilai' => 3.00],
                ['kriteria_id' => 5, 'sub_kriteria_id' => 29, 'nilai' => 4.00],
            ],
            'olahraga' => [
                ['kriteria_id' => 1, 'sub_kriteria_id' => 7,  'nilai' => 5.00],
                ['kriteria_id' => 2, 'sub_kriteria_id' => 14, 'nilai' => 5.00],
                ['kriteria_id' => 3, 'sub_kriteria_id' => 17, 'nilai' => 4.00],
                ['kriteria_id' => 4, 'sub_kriteria_id' => 21, 'nilai' => 5.00],
                ['kriteria_id' => 5, 'sub_kriteria_id' => 27, 'nilai' => 2.00],
            ],
            'marching' => [
                ['kriteria_id' => 1, 'sub_kriteria_id' => 8,  'nilai' => 5.00],
                ['kriteria_id' => 2, 'sub_kriteria_id' => 15, 'nilai' => 5.00],
                ['kriteria_id' => 3, 'sub_kriteria_id' => 18, 'nilai' => 3.00],
                ['kriteria_id' => 4, 'sub_kriteria_id' => 22, 'nilai' => 4.00],
                ['kriteria_id' => 5, 'sub_kriteria_id' => 28, 'nilai' => 3.00],
            ],
        ];

        foreach ($profilesConfig as $key => $items) {
            if (isset($ukmMap[$key])) {
                $ukmId = $ukmMap[$key];
                foreach ($items as $item) {
                    // Hapus entri lama untuk ukm_id & kriteria_id jika sub_kriteria_id berbeda
                    DB::table('ukm_kriteria_profile')
                        ->where('ukm_id', $ukmId)
                        ->where('kriteria_id', $item['kriteria_id'])
                        ->delete();

                    DB::table('ukm_kriteria_profile')->insert([
                        'ukm_id'          => $ukmId,
                        'kriteria_id'     => $item['kriteria_id'],
                        'sub_kriteria_id' => $item['sub_kriteria_id'],
                        'nilai'           => $item['nilai'],
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
