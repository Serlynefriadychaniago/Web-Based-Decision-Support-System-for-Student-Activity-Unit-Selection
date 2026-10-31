<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Ukm;
use App\Models\UkmKriteriaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UkmKriteriaProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $ukm = Ukm::orderBy('nama_ukm')->get();

        $profiles = UkmKriteriaProfile::with([
            'kriteria',
            'subKriteria'
        ])
            ->orderBy('ukm_id')
            ->orderBy('kriteria_id')
            ->orderBy('sub_kriteria_id')
            ->get()
            ->groupBy(function ($profile) {
                return $profile->ukm_id;
            });

        return view(
            'admin.ukm-kriteria-profile.index',
            compact(
                'ukm',
                'profiles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $ukm = Ukm::orderBy('nama_ukm')->get();

        $kriteria = Kriteria::with([
            'subKriteria' => function ($query) {
                $query
                    ->where('status', 'aktif')
                    ->orderBy('urutan');
            }
        ])
            ->orderBy('kode')
            ->get();

        $subKriteria = SubKriteria::where('status', 'aktif')
            ->orderBy('kriteria_id')
            ->orderBy('urutan')
            ->get();

        $subKriteriaByKriteria = $subKriteria->groupBy(
            'kriteria_id'
        );

        $existingProfiles = UkmKriteriaProfile::get()
            ->groupBy('ukm_id');

        return view(
            'admin.ukm-kriteria-profile.create',
            compact(
                'ukm',
                'kriteria',
                'subKriteria',
                'subKriteriaByKriteria',
                'existingProfiles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $profiles = $request->input('profiles', []);

        if (empty($profiles) || !is_array($profiles)) {
            return back()
                ->withInput()
                ->withErrors([
                    'profiles' => 'Data profil UKM wajib diisi.'
                ]);
        }

        DB::transaction(function () use ($profiles) {

            foreach ($profiles as $ukmId => $kriteriaData) {

                if (!is_array($kriteriaData)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Pastikan UKM benar-benar ada
                |--------------------------------------------------------------------------
                */

                $ukm = Ukm::find($ukmId);

                if (!$ukm) {
                    continue;
                }

                foreach ($kriteriaData as $kriteriaId => $subData) {

                    if (!is_array($subData)) {
                        continue;
                    }

                    $kriteria = Kriteria::find($kriteriaId);

                    if (!$kriteria) {
                        continue;
                    }

                    foreach ($subData as $subKriteriaId => $nilai) {

                        if ($nilai === null || $nilai === '') {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Validasi sub-kriteria
                        |--------------------------------------------------------------------------
                        */

                        $subKriteria = SubKriteria::where(
                            'id',
                            $subKriteriaId
                        )
                            ->where(
                                'kriteria_id',
                                $kriteriaId
                            )
                            ->where(
                                'status',
                                'aktif'
                            )
                            ->first();

                        if (!$subKriteria) {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Konversi nilai
                        |--------------------------------------------------------------------------
                        */

                        $nilai = (float) $nilai;

                        /*
                        |--------------------------------------------------------------------------
                        | SKALA NILAI
                        |--------------------------------------------------------------------------
                        |
                        | C1 Minat = 1–7
                        | C2 Bakat = 1–7
                        | C3 Jadwal = 0–5
                        | C4 Prestasi = 0–5
                        | C5 Biaya = 0–5
                        |
                        */

                        $nilai = max(1, min(5, $nilai));

                        /*
                        |--------------------------------------------------------------------------
                        | SIMPAN / UPDATE
                        |--------------------------------------------------------------------------
                        */

                        DB::table('ukm_kriteria_profile')->updateOrInsert(
                            [
                                'ukm_id' => $ukm->id,
                                'kriteria_id' => $kriteriaId,
                                'sub_kriteria_id' => $subKriteriaId,
                            ],
                            [
                                'nilai' => $nilai,
                            ]
                        );
                    }
                }
            }
        });

        return redirect()
            ->route('ukm-kriteria-profile.index')
            ->with(
                'success',
                'Profil kriteria seluruh UKM berhasil disimpan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MANAGE
    |--------------------------------------------------------------------------
    */

    public function manage(Ukm $ukm)
    {
        $kriteria = Kriteria::with([
            'subKriteria' => function ($query) {
                $query
                    ->where('status', 'aktif')
                    ->orderBy('urutan');
            }
        ])
            ->orderBy('kode')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil profil UKM yang sedang dikelola
        |--------------------------------------------------------------------------
        */

        $profiles = UkmKriteriaProfile::where(
            'ukm_id',
            $ukm->id
        )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Bentuk mapping:
        |
        | kriteria_id-sub_kriteria_id
        |--------------------------------------------------------------------------
        */

        $profileMap = [];
        $existingProfiles = [];

        foreach ($profiles as $profile) {

            $key =
                $profile->kriteria_id .
                '-' .
                $profile->sub_kriteria_id;

            $profileMap[$key] = $profile;
            $existingProfiles[$profile->kriteria_id][$profile->sub_kriteria_id] = $profile->nilai;
        }

        return view(
            'admin.ukm-kriteria-profile.manage',
            [
                'ukm' => $ukm,
                'kriteria' => $kriteria,
                'profiles' => $profileMap,
                'existingProfiles' => $existingProfiles,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE MANAGE
    |--------------------------------------------------------------------------
    |
    | FORM manage.blade.php mengirim:
    |
    | nilai[kriteria_id][sub_kriteria_id]
    |
    |--------------------------------------------------------------------------
    */

    public function saveManage(
        Request $request,
        Ukm $ukm
    ) {
        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA NILAI DARI FORM
        |--------------------------------------------------------------------------
        */

        $nilaiData = $request->input('nilai', []);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        if (
            !is_array($nilaiData) ||
            empty($nilaiData)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'nilai' =>
                        'Tidak ada data profil yang dikirim.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DALAM TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $nilaiData,
            $ukm
        ) {

            foreach (
                $nilaiData as $kriteriaId => $subData
            ) {

                /*
                |--------------------------------------------------------------------------
                | Pastikan data sub-kriteria berupa array
                |--------------------------------------------------------------------------
                */

                if (!is_array($subData)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Cari kriteria
                |--------------------------------------------------------------------------
                */

                $kriteria = Kriteria::find(
                    $kriteriaId
                );

                if (!$kriteria) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Loop sub-kriteria
                |--------------------------------------------------------------------------
                */

                foreach (
                    $subData as $subKriteriaId => $nilai
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lewati nilai kosong
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $nilai === null ||
                        $nilai === ''
                    ) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan sub-kriteria benar
                    |--------------------------------------------------------------------------
                    */

                    $subKriteria = SubKriteria::where(
                        'id',
                        $subKriteriaId
                    )
                        ->where(
                            'kriteria_id',
                            $kriteriaId
                        )
                        ->where(
                            'status',
                            'aktif'
                        )
                        ->first();

                    if (!$subKriteria) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Konversi nilai menjadi angka
                    |--------------------------------------------------------------------------
                    */

                    $nilai = (float) $nilai;

                    /*
                    |--------------------------------------------------------------------------
                    | SKALA NILAI
                    |--------------------------------------------------------------------------
                    |
                    | C1 Minat = 1–7
                    | C2 Bakat = 1–7
                    | C3 Jadwal = 0–5
                    | C4 Prestasi = 0–5
                    | C5 Biaya = 0–5
                    |
                    */

                    $nilai = max(1, min(5, $nilai));

                    /*
                    |--------------------------------------------------------------------------
                    | SIMPAN / UPDATE DATABASE
                    |--------------------------------------------------------------------------
                    |
                    | Tidak menggunakan withoutTimestamps().
                    |
                    | Langsung menggunakan Query Builder agar:
                    |
                    | 1. Tidak terkena error timestamps.
                    | 2. Tidak bergantung pada $fillable model.
                    | 3. Data langsung masuk ke tabel.
                    |--------------------------------------------------------------------------
                    */

                    DB::table(
                        'ukm_kriteria_profile'
                    )->updateOrInsert(
                        [
                            'ukm_id' =>
                                $ukm->id,

                            'kriteria_id' =>
                                $kriteriaId,

                            'sub_kriteria_id' =>
                                $subKriteriaId,
                        ],
                        [
                            'nilai' =>
                                $nilai,
                        ]
                    );
                }
            }
        });

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'ukm-kriteria-profile.manage',
                $ukm->id
            )
            ->with(
                'success',
                'Profil kriteria ' .
                $ukm->nama_ukm .
                ' berhasil disimpan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW UKM
    |--------------------------------------------------------------------------
    */

    public function showUkm(Ukm $ukm)
    {
        $profiles = UkmKriteriaProfile::with([
            'kriteria',
            'subKriteria'
        ])
            ->where(
                'ukm_id',
                $ukm->id
            )
            ->get()
            ->groupBy('kriteria_id');

        $karakteristik = [];

        foreach (
            $profiles as $kriteriaId => $profileGroup
        ) {

            $tertinggi = $profileGroup
                ->sortByDesc('nilai')
                ->first();

            if ($tertinggi) {

                $karakteristik[] = [
                    'kriteria' =>
                        $tertinggi->kriteria,

                    'sub_kriteria' =>
                        $tertinggi->subKriteria,

                    'nilai' =>
                        $tertinggi->nilai,
                ];
            }
        }

        usort(
            $karakteristik,
            function ($a, $b) {

                return strcmp(
                    $a['kriteria']->kode,
                    $b['kriteria']->kode
                );
            }
        );

        return view(
            'admin.ukm-kriteria-profile.show',
            compact(
                'ukm',
                'profiles',
                'karakteristik'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW RECORD
    |--------------------------------------------------------------------------
    */

    public function show(
        UkmKriteriaProfile $ukmKriteriaProfile
    ) {
        $ukmKriteriaProfile->load([
            'ukm',
            'kriteria',
            'subKriteria'
        ]);

        return view(
            'admin.ukm-kriteria-profile.show',
            compact(
                'ukmKriteriaProfile'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        UkmKriteriaProfile $ukmKriteriaProfile
    ) {
        $ukm = Ukm::orderBy(
            'nama_ukm'
        )->get();

        $kriteria = Kriteria::orderBy(
            'kode'
        )->get();

        $subKriteria = SubKriteria::with(
            'kriteria'
        )
            ->where(
                'status',
                'aktif'
            )
            ->orderBy(
                'kriteria_id'
            )
            ->orderBy(
                'urutan'
            )
            ->get();

        return view(
            'admin.ukm-kriteria-profile.edit',
            compact(
                'ukmKriteriaProfile',
                'ukm',
                'kriteria',
                'subKriteria'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        UkmKriteriaProfile $ukmKriteriaProfile
    ) {
        $validated = $request->validate([
            'ukm_id' => [
                'required',
                'exists:ukm,id'
            ],

            'kriteria_id' => [
                'required',
                'exists:kriteria,id'
            ],

            'sub_kriteria_id' => [
                'nullable',
                'exists:sub_kriteria,id'
            ],

            'nilai' => [
                'required',
                'numeric'
            ],
        ]);

        $kriteria = Kriteria::find(
            $validated['kriteria_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Validasi skala C1 dan C2
        |--------------------------------------------------------------------------
        */

        if ($validated['nilai'] < 1 || $validated['nilai'] > 5) {
            return back()
                ->withInput()
                ->withErrors([
                    'nilai' => 'Nilai kriteria harus antara 1 sampai 5.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi sub-kriteria
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['sub_kriteria_id']
            )
        ) {

            $subKriteria = SubKriteria::find(
                $validated['sub_kriteria_id']
            );

            if (
                !$subKriteria ||
                (int) $subKriteria->kriteria_id !==
                (int) $validated['kriteria_id']
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'sub_kriteria_id' =>
                            'Sub-kriteria tidak sesuai dengan kriteria.'
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATABASE
        |--------------------------------------------------------------------------
        */

        DB::table(
            'ukm_kriteria_profile'
        )
            ->where(
                'id',
                $ukmKriteriaProfile->id
            )
            ->update([
                'ukm_id' =>
                    $validated['ukm_id'],

                'kriteria_id' =>
                    $validated['kriteria_id'],

                'sub_kriteria_id' =>
                    $validated['sub_kriteria_id'] ?? null,

                'nilai' =>
                    $validated['nilai'],
            ]);

        return redirect()
            ->route(
                'ukm-kriteria-profile.index'
            )
            ->with(
                'success',
                'Profil kriteria UKM berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        UkmKriteriaProfile $ukmKriteriaProfile
    ) {

        /*
        |--------------------------------------------------------------------------
        | Hapus langsung dari database
        |--------------------------------------------------------------------------
        */

        DB::table(
            'ukm_kriteria_profile'
        )
            ->where(
                'id',
                $ukmKriteriaProfile->id
            )
            ->delete();

        return redirect()
            ->route(
                'ukm-kriteria-profile.index'
            )
            ->with(
                'success',
                'Profil kriteria UKM berhasil dihapus.'
            );
    }
}