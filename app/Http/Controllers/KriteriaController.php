<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
    /**
     * Menampilkan daftar seluruh kriteria.
     */
    public function index()
    {
        $kriteria = Kriteria::orderBy('kode')->get();

        return view('admin.kriteria.index', compact('kriteria'));
    }

    /**
     * Menampilkan form tambah kriteria.
     */
    public function create()
    {
        return view('admin.kriteria.create');
    }

    /**
     * Menyimpan kriteria baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:10', 'unique:kriteria,kode'],
            'nama_kriteria' => ['required', 'string', 'max:50'],
            'tipe' => ['required', 'in:benefit,cost'],
        ]);

        Kriteria::create($validated);

        return redirect()
            ->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail kriteria.
     */
    public function show(Kriteria $kriteria)
    {
        return view('admin.kriteria.show', compact('kriteria'));
    }

    /**
     * Menampilkan form edit kriteria.
     */
    public function edit(Kriteria $kriteria)
    {
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    /**
     * Memperbarui data kriteria.
     */
    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:10',
                'unique:kriteria,kode,' . $kriteria->id,
            ],
            'nama_kriteria' => ['required', 'string', 'max:50'],
            'tipe' => ['required', 'in:benefit,cost'],
        ]);

        $kriteria->update($validated);

        return redirect()
            ->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Menghapus kriteria secara aman beserta seluruh relasinya.
     */
    public function destroy(Kriteria $kriteria)
    {
        try {
            DB::beginTransaction();

            $kriteriaId = $kriteria->id;

            // Hapus relasi data kriteria pada tabel terkait
            DB::table('ukm_kriteria_profile')->where('kriteria_id', $kriteriaId)->delete();
            DB::table('questionnaire_answers')->where('kriteria_id', $kriteriaId)->delete();
            DB::table('sub_kriteria')->where('kriteria_id', $kriteriaId)->delete();
            DB::table('ahp_pairwise')->where('kriteria_row_id', $kriteriaId)->orWhere('kriteria_col_id', $kriteriaId)->delete();
            DB::table('ahp_weights')->where('kriteria_id', $kriteriaId)->delete();
            DB::table('topsis_values')->where('kriteria_id', $kriteriaId)->delete();
            DB::table('topsis_ideals')->where('kriteria_id', $kriteriaId)->delete();

            $kriteria->delete();

            DB::commit();

            return redirect()
                ->route('kriteria.index')
                ->with('success', 'Kriteria berhasil dihapus.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('kriteria.index')
                ->with('error', 'Gagal menghapus kriteria: ' . $e->getMessage());
        }
    }
}