<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubKriteriaController extends Controller
{
    /**
     * Menampilkan seluruh sub-kriteria.
     */
    public function index()
    {
        $subKriteria = SubKriteria::with('kriteria')
            ->orderBy('kriteria_id')
            ->orderBy('urutan')
            ->get();

        return view('admin.sub-kriteria.index', compact('subKriteria'));
    }

    /**
     * Menampilkan form tambah sub-kriteria.
     */
    public function create()
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        return view('admin.sub-kriteria.create', compact('kriteria'));
    }

    /**
     * Menyimpan sub-kriteria baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kriteria_id' => ['required', 'exists:kriteria,id'],
            'nama_subkriteria' => ['required', 'string', 'max:150'],
            'urutan' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        SubKriteria::create($validated);

        return redirect()
            ->route('sub-kriteria.index')
            ->with('success', 'Sub-kriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail sub-kriteria.
     */
    public function show(SubKriteria $subKriteria)
    {
        $subKriteria->load('kriteria');

        return view('admin.sub-kriteria.show', compact('subKriteria'));
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(SubKriteria $subKriteria)
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        return view('admin.sub-kriteria.edit', compact(
            'subKriteria',
            'kriteria'
        ));
    }

    /**
     * Memperbarui sub-kriteria.
     */
    public function update(Request $request, SubKriteria $subKriteria)
    {
        $validated = $request->validate([
            'kriteria_id' => ['required', 'exists:kriteria,id'],
            'nama_subkriteria' => ['required', 'string', 'max:150'],
            'urutan' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $subKriteria->update($validated);

        return redirect()
            ->route('sub-kriteria.index')
            ->with('success', 'Sub-kriteria berhasil diperbarui.');
    }

    /**
     * Menghapus sub-kriteria secara aman beserta seluruh relasinya.
     */
    public function destroy(SubKriteria $subKriteria)
    {
        try {
            DB::beginTransaction();

            $subKriteriaId = $subKriteria->id;

            // Hapus relasi sub_kriteria di ukm_kriteria_profile dan questionnaire_answers
            DB::table('ukm_kriteria_profile')->where('sub_kriteria_id', $subKriteriaId)->delete();
            DB::table('questionnaire_answers')->where('sub_kriteria_id', $subKriteriaId)->delete();

            $subKriteria->delete();

            DB::commit();

            return redirect()
                ->route('sub-kriteria.index')
                ->with('success', 'Sub-kriteria berhasil dihapus.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('sub-kriteria.index')
                ->with('error', 'Gagal menghapus sub-kriteria: ' . $e->getMessage());
        }
    }
}