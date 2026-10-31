<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Ukm;
use App\Models\UkmKriteriaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UkmController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     * Menampilkan seluruh data UKM.
     */
    public function index()
    {
        $ukm = Ukm::orderBy('created_at', 'desc')->get();

        return view(
            'admin.ukm.index',
            compact('ukm')
        );
    }


    /**
     * =========================================================
     * CREATE
     * =========================================================
     * Menampilkan form tambah UKM beserta 5 kriteria SPK.
     */
    public function create()
    {
        $kriteriaList = Kriteria::with(['subKriteria' => function ($query) {
            $query->where('status', 'aktif')->orderBy('urutan');
        }])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.ukm.create', compact('kriteriaList'));
    }


    /**
     * =========================================================
     * STORE
     * =========================================================
     * Menyimpan data UKM baru dan profil 5 kriteria SPK.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ukm' => [
                'required',
                'string',
                'max:100',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'visi' => [
                'nullable',
                'string',
            ],

            'misi' => [
                'nullable',
                'string',
            ],

            'pembina' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ketua' => [
                'nullable',
                'string',
                'max:100',
            ],

            'kontak' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
            ],

            'instagram' => [
                'nullable',
                'string',
                'max:255',
            ],

            'lokasi_sekretariat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'hari_latihan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'kriteria_profile' => [
                'nullable',
                'array',
            ],

            'kriteria_profile.*.sub_kriteria_id' => [
                'nullable',
                'exists:sub_kriteria,id',
            ],

            'kriteria_profile.*.nilai' => [
                'nullable',
                'numeric',
                'min:1',
                'max:5',
            ],
        ]);

        if (!empty($validated['instagram'])) {
            $val = trim($validated['instagram']);
            if (!preg_match('/^https?:\/\//i', $val)) {
                $val = 'https://' . ltrim($val, '/');
            }
            $validated['instagram'] = $val;
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD LOGO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {
            $validated['logo'] =
                $request->file('logo')->store(
                    'logo-ukm',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN UKM & PROFIL KRITERIA DALAM TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $request) {
            $ukmData = $validated;
            unset($ukmData['kriteria_profile']);

            $ukm = Ukm::create($ukmData);

            $profiles = $request->input('kriteria_profile', []);
            if (!empty($profiles) && is_array($profiles)) {
                foreach ($profiles as $kriteriaId => $profileData) {
                    if (isset($profileData['nilai']) && $profileData['nilai'] !== null && $profileData['nilai'] !== '') {
                        $subId = !empty($profileData['sub_kriteria_id']) ? $profileData['sub_kriteria_id'] : null;
                        $nilai = (float) $profileData['nilai'];

                        DB::table('ukm_kriteria_profile')->insert([
                            'ukm_id'          => $ukm->id,
                            'kriteria_id'     => $kriteriaId,
                            'sub_kriteria_id' => $subId,
                            'nilai'           => $nilai,
                        ]);
                    }
                }
            }
        });


        return redirect()
            ->route('ukm.index')
            ->with(
                'success',
                'Data UKM berhasil ditambahkan.'
            );
    }


    /**
     * =========================================================
     * SHOW
     * =========================================================
     * Menampilkan detail satu UKM.
     */
    public function show(Ukm $ukm)
    {
        // Ambil seluruh kriteria dari database
        $criteriaList = Kriteria::orderBy('id', 'asc')->get();

        // Ambil profil kriteria UKM dari database
        $ukmProfiles = UkmKriteriaProfile::with(['kriteria', 'subKriteria'])
            ->where('ukm_id', $ukm->id)
            ->get()
            ->keyBy('kriteria_id');

        $criteriaProfiles = [];
        foreach ($criteriaList as $crit) {
            $prof = $ukmProfiles->get($crit->id);
            $criteriaProfiles[] = [
                'kode'          => $crit->kode,
                'nama_kriteria' => $crit->nama_kriteria,
                'tipe'          => $crit->tipe,
                'nilai'         => $prof ? (float)$prof->nilai : null,
                'sub_kriteria'  => $prof && $prof->subKriteria ? $prof->subKriteria->nama_subkriteria : '-',
            ];
        }

        return view(
            'admin.ukm.show',
            compact('ukm', 'criteriaProfiles')
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     * Menampilkan form edit UKM beserta data profil kriteria.
     */
    public function edit(Ukm $ukm)
    {
        $kriteriaList = Kriteria::with(['subKriteria' => function ($query) {
            $query->where('status', 'aktif')->orderBy('urutan');
        }])
            ->orderBy('id', 'asc')
            ->get();

        $existingProfiles = UkmKriteriaProfile::where('ukm_id', $ukm->id)
            ->get()
            ->keyBy('kriteria_id');

        return view(
            'admin.ukm.edit',
            compact('ukm', 'kriteriaList', 'existingProfiles')
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     * Memperbarui data UKM dan profil 5 kriteria SPK.
     */
    public function update(
        Request $request,
        Ukm $ukm
    ) {
        $validated = $request->validate([
            'nama_ukm' => [
                'required',
                'string',
                'max:100',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'visi' => [
                'nullable',
                'string',
            ],

            'misi' => [
                'nullable',
                'string',
            ],

            'pembina' => [
                'nullable',
                'string',
                'max:100',
            ],

            'ketua' => [
                'nullable',
                'string',
                'max:100',
            ],

            'kontak' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
            ],

            'instagram' => [
                'nullable',
                'string',
                'max:255',
            ],

            'lokasi_sekretariat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'hari_latihan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'kriteria_profile' => [
                'nullable',
                'array',
            ],

            'kriteria_profile.*.sub_kriteria_id' => [
                'nullable',
                'exists:sub_kriteria,id',
            ],

            'kriteria_profile.*.nilai' => [
                'nullable',
                'numeric',
                'min:1',
                'max:5',
            ],
        ]);

        if (!empty($validated['instagram'])) {
            $val = trim($validated['instagram']);
            if (!preg_match('/^https?:\/\//i', $val)) {
                $val = 'https://' . ltrim($val, '/');
            }
            $validated['instagram'] = $val;
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD LOGO BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            /*
             * Hapus logo lama jika ada.
             */
            if (
                !empty($ukm->logo) &&
                Storage::disk('public')->exists($ukm->logo)
            ) {

                Storage::disk('public')->delete(
                    $ukm->logo
                );
            }


            /*
             * Simpan logo baru.
             */
            $validated['logo'] =
                $request->file('logo')->store(
                    'logo-ukm',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA UKM & PROFIL KRITERIA DALAM TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $request, $ukm) {
            $ukmData = $validated;
            unset($ukmData['kriteria_profile']);

            $ukm->update($ukmData);

            $profiles = $request->input('kriteria_profile', []);
            if (!empty($profiles) && is_array($profiles)) {
                foreach ($profiles as $kriteriaId => $profileData) {
                    if (isset($profileData['nilai']) && $profileData['nilai'] !== null && $profileData['nilai'] !== '') {
                        $subId = !empty($profileData['sub_kriteria_id']) ? $profileData['sub_kriteria_id'] : null;
                        $nilai = (float) $profileData['nilai'];

                        // Delete existing profile record for this ukm_id & kriteria_id to avoid duplicates if sub_kriteria_id changes
                        DB::table('ukm_kriteria_profile')
                            ->where('ukm_id', $ukm->id)
                            ->where('kriteria_id', $kriteriaId)
                            ->delete();

                        DB::table('ukm_kriteria_profile')->insert([
                            'ukm_id'          => $ukm->id,
                            'kriteria_id'     => $kriteriaId,
                            'sub_kriteria_id' => $subId,
                            'nilai'           => $nilai,
                        ]);
                    }
                }
            }
        });


        return redirect()
            ->route('ukm.index')
            ->with(
                'success',
                'Data UKM berhasil diperbarui.'
            );
    }


    /**
     * =========================================================
     * DESTROY
     * =========================================================
     * Menghapus UKM.
     */
    public function destroy(Ukm $ukm)
    {
        try {
            DB::beginTransaction();

            $ukmId = $ukm->id;

            // 1. Hapus riwayat status pendaftaran & pendaftaran_ukm terkait UKM ini
            $pendaftaranIds = DB::table('pendaftaran_ukm')->where('ukm_id', $ukmId)->pluck('id');
            if ($pendaftaranIds->isNotEmpty()) {
                DB::table('riwayat_status_pendaftaran')->whereIn('pendaftaran_id', $pendaftaranIds)->delete();
                DB::table('pendaftaran_ukm')->whereIn('id', $pendaftaranIds)->delete();
            }

            // 2. Hapus topsis_results & topsis_values terkait UKM ini
            DB::table('topsis_results')->where('ukm_id', $ukmId)->delete();
            DB::table('topsis_values')->where('ukm_id', $ukmId)->delete();

            // 3. Hapus ukm_kriteria_profile terkait UKM ini
            DB::table('ukm_kriteria_profile')->where('ukm_id', $ukmId)->delete();

            // 4. Hapus file logo jika ada
            if (
                !empty($ukm->logo) &&
                Storage::disk('public')->exists($ukm->logo)
            ) {
                Storage::disk('public')->delete($ukm->logo);
            }

            // 5. Hapus data UKM
            $ukm->delete();

            DB::commit();

            return redirect()
                ->route('ukm.index')
                ->with(
                    'success',
                    'Data UKM berhasil dihapus.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('ukm.index')
                ->with(
                    'error',
                    'Gagal menghapus data UKM: ' . $e->getMessage()
                );
        }
    }
}