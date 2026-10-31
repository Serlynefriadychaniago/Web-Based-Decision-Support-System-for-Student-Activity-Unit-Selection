<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class MahasiswaController extends Controller
{
    /**
     * Menampilkan daftar data mahasiswa.
     */
    public function index(Request $request)
    {
        $search = trim($request->get('q'));

        $query = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->select(
                'mahasiswa.*',
                'users.email',
                'users.username',
                'users.role'
            );

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nim', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.prodi', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $mahasiswaList = $query->orderBy('mahasiswa.nim', 'asc')->paginate(10);

        // Ambil ID mahasiswa pada halaman ini untuk query status efisien (mencegah N+1)
        $mhsIds = $mahasiswaList->pluck('id')->toArray();

        $kuesionerStatusMap = [];
        $pendaftaranCountMap = [];

        if (!empty($mhsIds)) {
            // Check completed questionnaire sessions
            $completedSessions = DB::table('questionnaire_sessions')
                ->whereIn('mahasiswa_id', $mhsIds)
                ->where('status', 'completed')
                ->pluck('mahasiswa_id')
                ->toArray();

            foreach ($mhsIds as $id) {
                $kuesionerStatusMap[$id] = in_array($id, $completedSessions);
            }

            // Count pendaftaran UKM
            $regCounts = DB::table('pendaftaran_ukm')
                ->whereIn('mahasiswa_id', $mhsIds)
                ->select('mahasiswa_id', DB::raw('COUNT(*) as total'))
                ->groupBy('mahasiswa_id')
                ->pluck('total', 'mahasiswa_id')
                ->toArray();

            foreach ($mhsIds as $id) {
                $pendaftaranCountMap[$id] = $regCounts[$id] ?? 0;
            }
        }

        return view('admin.mahasiswa.index', compact(
            'mahasiswaList',
            'search',
            'kuesionerStatusMap',
            'pendaftaranCountMap'
        ));
    }

    /**
     * Menampilkan form tambah mahasiswa baru.
     */
    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    /**
     * Menyimpan data mahasiswa baru dan akun user terkait.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nim'           => 'required|string|max:50|unique:mahasiswa,nim',
            'username'      => 'required|string|max:100|unique:users,username',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6',
            'prodi'         => 'required|string|max:255',
            'fakultas'      => 'nullable|string|max:255',
            'angkatan'      => 'nullable|string|max:10',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp'         => 'nullable|string|max:20',
        ], [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'nim.required'      => 'NIM wajib diisi.',
            'nim.unique'        => 'NIM sudah terdaftar di sistem.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'prodi.required'    => 'Program studi wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat User baru
            $userId = DB::table('users')->insertGetId([
                'name'       => $request->input('nama'),
                'username'   => trim($request->input('username')),
                'email'      => trim(strtolower($request->input('email'))),
                'password'   => Hash::make($request->input('password')),
                'role'       => 'mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Buat Data Mahasiswa
            DB::table('mahasiswa')->insert([
                'user_id'       => $userId,
                'nim'           => trim($request->input('nim')),
                'nama'          => trim($request->input('nama')),
                'prodi'         => trim($request->input('prodi')),
                'fakultas'      => $request->input('fakultas') ? trim($request->input('fakultas')) : 'Teknik',
                'angkatan'      => $request->input('angkatan') ? trim($request->input('angkatan')) : date('Y'),
                'jenis_kelamin' => $request->input('jenis_kelamin') ?? 'L',
                'no_hp'         => $request->input('no_hp') ? trim($request->input('no_hp')) : null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil ditambahkan.');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan data mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail profil, kuesioner, hasil TOPSIS, dan pendaftaran mahasiswa.
     */
    public function show($id)
    {
        $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->where('mahasiswa.id', $id)
            ->select('mahasiswa.*', 'users.email', 'users.username', 'users.role')
            ->first();

        if (!$mahasiswa) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Session kuesioner terakhir
        $latestSession = DB::table('questionnaire_sessions')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('id')
            ->first();

        $kuesionerAnswers = [];
        if ($latestSession) {
            $answers = DB::table('questionnaire_answers')
                ->join('kriteria', 'questionnaire_answers.kriteria_id', '=', 'kriteria.id')
                ->join('sub_kriteria', 'questionnaire_answers.sub_kriteria_id', '=', 'sub_kriteria.id')
                ->where('questionnaire_answers.session_id', $latestSession->id)
                ->select(
                    'kriteria.kode as kriteria_kode',
                    'kriteria.nama_kriteria',
                    'sub_kriteria.nama_subkriteria as nama_sub_kriteria',
                    'questionnaire_answers.nilai'
                )
                ->orderBy('kriteria.kode')
                ->get();

            $kuesionerAnswers = $answers;
        }

        // Hasil TOPSIS rekomendasi terakhir
        $allTopsisRuns = DB::table('topsis_runs')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('id')
            ->get();

        $latestTopsisRun = $allTopsisRuns->first();

        $topsisResults = [];
        if ($latestTopsisRun) {
            $topsisResults = DB::table('topsis_results')
                ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
                ->where('topsis_results.topsis_run_id', $latestTopsisRun->id)
                ->orderBy('topsis_results.ranking')
                ->select('topsis_results.*', 'ukm.nama_ukm')
                ->get();
        }

        // Riwayat pendaftaran UKM
        $pendaftaranList = DB::table('pendaftaran_ukm')
            ->join('ukm', 'pendaftaran_ukm.ukm_id', '=', 'ukm.id')
            ->leftJoin('topsis_results', 'pendaftaran_ukm.topsis_result_id', '=', 'topsis_results.id')
            ->where('pendaftaran_ukm.mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('pendaftaran_ukm.id')
            ->select(
                'pendaftaran_ukm.*',
                'ukm.nama_ukm',
                'topsis_results.ranking as topsis_ranking',
                'topsis_results.nilai_preferensi as topsis_score'
            )
            ->get();

        return view('admin.mahasiswa.show', compact(
            'mahasiswa',
            'latestSession',
            'kuesionerAnswers',
            'allTopsisRuns',
            'latestTopsisRun',
            'topsisResults',
            'pendaftaranList'
        ));
    }

    /**
     * Menampilkan form edit data mahasiswa.
     */
    public function edit($id)
    {
        $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->where('mahasiswa.id', $id)
            ->select('mahasiswa.*', 'users.email', 'users.username')
            ->first();

        if (!$mahasiswa) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Memperbarui data mahasiswa dan user terkait.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();

        if (!$mahasiswa) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $userId = $mahasiswa->user_id;

        $request->validate([
            'nama'          => 'required|string|max:255',
            'nim'           => 'required|string|max:50|unique:mahasiswa,nim,' . $id,
            'username'      => 'required|string|max:100|unique:users,username,' . $userId,
            'email'         => 'required|email|max:255|unique:users,email,' . $userId,
            'password'      => 'nullable|string|min:6',
            'prodi'         => 'required|string|max:255',
            'fakultas'      => 'nullable|string|max:255',
            'angkatan'      => 'nullable|string|max:10',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp'         => 'nullable|string|max:20',
        ], [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'nim.required'      => 'NIM wajib diisi.',
            'nim.unique'        => 'NIM sudah terdaftar di sistem.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.min'      => 'Password minimal 6 karakter.',
            'prodi.required'    => 'Program studi wajib diisi.',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update User
            $userData = [
                'name'       => trim($request->input('nama')),
                'username'   => trim($request->input('username')),
                'email'      => trim(strtolower($request->input('email'))),
                'updated_at' => now(),
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->input('password'));
            }

            DB::table('users')->where('id', $userId)->update($userData);

            // 2. Update Mahasiswa
            DB::table('mahasiswa')->where('id', $id)->update([
                'nim'           => trim($request->input('nim')),
                'nama'          => trim($request->input('nama')),
                'prodi'         => trim($request->input('prodi')),
                'fakultas'      => $request->input('fakultas') ? trim($request->input('fakultas')) : 'Teknik',
                'angkatan'      => $request->input('angkatan') ? trim($request->input('angkatan')) : date('Y'),
                'jenis_kelamin' => $request->input('jenis_kelamin') ?? 'L',
                'no_hp'         => $request->input('no_hp') ? trim($request->input('no_hp')) : null,
                'updated_at'    => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa berhasil diperbarui.');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui data mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data mahasiswa secara aman beserta relasinya.
     */
    public function destroy($id)
    {
        $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();

        if (!$mahasiswa) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        try {
            DB::beginTransaction();

            $userId = $mahasiswa->user_id;

            // Hapus pendaftaran_ukm
            DB::table('pendaftaran_ukm')->where('mahasiswa_id', $id)->delete();

            // Hapus topsis_runs dan anak tabelnya
            $topsisRunIds = DB::table('topsis_runs')->where('mahasiswa_id', $id)->pluck('id');
            if ($topsisRunIds->isNotEmpty()) {
                DB::table('topsis_values')->whereIn('topsis_run_id', $topsisRunIds)->delete();
                DB::table('topsis_ideals')->whereIn('topsis_run_id', $topsisRunIds)->delete();
                DB::table('topsis_results')->whereIn('topsis_run_id', $topsisRunIds)->delete();
                DB::table('topsis_runs')->whereIn('id', $topsisRunIds)->delete();
            }

            // Hapus questionnaire_sessions dan answers
            $sessionIds = DB::table('questionnaire_sessions')->where('mahasiswa_id', $id)->pluck('id');
            if ($sessionIds->isNotEmpty()) {
                DB::table('questionnaire_answers')->whereIn('session_id', $sessionIds)->delete();
                DB::table('questionnaire_sessions')->whereIn('id', $sessionIds)->delete();
            }

            // Hapus data mahasiswa & user
            DB::table('mahasiswa')->where('id', $id)->delete();
            if ($userId) {
                DB::table('users')->where('id', $userId)->delete();
            }

            DB::commit();

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', 'Data mahasiswa dan seluruh riwayatnya berhasil dihapus.');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Mengunduh Laporan Rekomendasi TOPSIS Mahasiswa dalam bentuk PDF (Khusus Admin).
     */
    public function downloadRekomendasiPdf($id, Request $request)
    {
        $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->where('mahasiswa.id', $id)
            ->select('mahasiswa.*', 'users.email', 'users.username', 'users.role')
            ->first();

        if (!$mahasiswa) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $runId = $request->query('run_id');
        $selectedRun = null;

        if ($runId) {
            $selectedRun = DB::table('topsis_runs')
                ->where('id', $runId)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->first();
        }

        if (!$selectedRun) {
            $selectedRun = DB::table('topsis_runs')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderByDesc('id')
                ->first();
        }

        if (!$selectedRun) {
            return redirect()->route('admin.mahasiswa.show', $mahasiswa->id)
                ->with('error', 'Mahasiswa ini belum memiliki riwayat rekomendasi TOPSIS.');
        }

        // Ambil data questionnaire session terkait
        $session = DB::table('questionnaire_sessions')
            ->where('id', $selectedRun->questionnaire_session_id)
            ->first();

        // Hasil perangkingan TOPSIS untuk 7 UKM
        $selectedResults = DB::table('topsis_results')
            ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
            ->where('topsis_run_id', $selectedRun->id)
            ->orderBy('topsis_results.ranking')
            ->select('topsis_results.*', 'ukm.nama_ukm', 'ukm.deskripsi')
            ->get();

        // Jawaban kuesioner mahasiswa pada sesi tersebut
        $studentAnswers = DB::table('questionnaire_answers')
            ->join('kriteria', 'questionnaire_answers.kriteria_id', '=', 'kriteria.id')
            ->join('sub_kriteria', 'questionnaire_answers.sub_kriteria_id', '=', 'sub_kriteria.id')
            ->where('session_id', $selectedRun->questionnaire_session_id)
            ->orderBy('kriteria.id', 'asc')
            ->select(
                'kriteria.kode',
                'kriteria.nama_kriteria',
                'sub_kriteria.nama_subkriteria',
                'questionnaire_answers.nilai'
            )
            ->get();

        // Bobot kriteria AHP yang digunakan saat run tersebut
        $ahpWeights = DB::table('ahp_weights')
            ->join('kriteria', 'ahp_weights.kriteria_id', '=', 'kriteria.id')
            ->where('ahp_run_id', $selectedRun->ahp_run_id)
            ->orderBy('kriteria.id', 'asc')
            ->select(
                'kriteria.kode',
                'kriteria.nama_kriteria',
                'ahp_weights.bobot'
            )
            ->get();

        $pdf = Pdf::loadView('admin.mahasiswa.rekomendasi-pdf', compact(
            'mahasiswa',
            'selectedRun',
            'session',
            'selectedResults',
            'studentAnswers',
            'ahpWeights'
        ));

        $pdf->setPaper('A4', 'portrait');

        $cleanNim = preg_replace('/[^A-Za-z0-9]/', '_', $mahasiswa->nim ?: 'mhs');
        $fileName = 'Laporan_Rekomendasi_UKM_' . $cleanNim . '_Run#' . $selectedRun->id . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Mengunduh Laporan Rekap Hasil Rekomendasi Mahasiswa dalam bentuk PDF A4 Landscape (Khusus Admin).
     */
    public function downloadRekapRekomendasiPdf(Request $request)
    {
        // 1. Ambil ID topsis_run terbaru untuk setiap mahasiswa (mencegah duplikasi 1 baris per mahasiswa)
        $latestRunIds = DB::table('topsis_runs')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('mahasiswa_id')
            ->pluck('id')
            ->toArray();

        $rekapList = [];
        $rank1CountMap = [];
        $totalMahasiswaMengisi = 0;
        $totalRekomendasi = DB::table('topsis_runs')->count();
        $topUkmMode = '-';

        if (!empty($latestRunIds)) {
            $runs = DB::table('topsis_runs')
                ->join('mahasiswa', 'topsis_runs.mahasiswa_id', '=', 'mahasiswa.id')
                ->join('users', 'mahasiswa.user_id', '=', 'users.id')
                ->whereIn('topsis_runs.id', $latestRunIds)
                ->orderBy('mahasiswa.nama', 'asc')
                ->select(
                    'topsis_runs.id as run_id',
                    'topsis_runs.created_at as tanggal_kuesioner',
                    'mahasiswa.id as mhs_id',
                    'mahasiswa.nama',
                    'mahasiswa.nim',
                    'mahasiswa.prodi',
                    'mahasiswa.fakultas',
                    'mahasiswa.angkatan',
                    'users.email'
                )
                ->get();

            foreach ($runs as $r) {
                // Ambil 3 besar hasil rekomendasi TOPSIS (ranking <= 3)
                $results = DB::table('topsis_results')
                    ->join('ukm', 'topsis_results.ukm_id', '=', 'ukm.id')
                    ->where('topsis_run_id', $r->run_id)
                    ->where('ranking', '<=', 3)
                    ->orderBy('ranking', 'asc')
                    ->select('topsis_results.ranking', 'topsis_results.nilai_preferensi', 'ukm.nama_ukm')
                    ->get()
                    ->keyBy('ranking');

                $rank1 = $results->get(1);
                $rank2 = $results->get(2);
                $rank3 = $results->get(3);

                if ($rank1) {
                    $ukmName = $rank1->nama_ukm;
                    $rank1CountMap[$ukmName] = ($rank1CountMap[$ukmName] ?? 0) + 1;
                }

                $rekapList[] = (object)[
                    'nama'              => $r->nama,
                    'nim'               => $r->nim,
                    'prodi'             => $r->prodi,
                    'tanggal_kuesioner' => $r->tanggal_kuesioner,
                    'rank1_ukm'         => $rank1 ? $rank1->nama_ukm : '-',
                    'rank1_score'       => $rank1 ? $rank1->nilai_preferensi : null,
                    'rank2_ukm'         => $rank2 ? $rank2->nama_ukm : '-',
                    'rank2_score'       => $rank2 ? $rank2->nilai_preferensi : null,
                    'rank3_ukm'         => $rank3 ? $rank3->nama_ukm : '-',
                    'rank3_score'       => $rank3 ? $rank3->nilai_preferensi : null,
                ];
            }

            if (!empty($rank1CountMap)) {
                arsort($rank1CountMap);
                $topUkmMode = array_key_first($rank1CountMap);
            }

            $totalMahasiswaMengisi = count($rekapList);
        }

        $pdf = Pdf::loadView('admin.mahasiswa.rekomendasi-rekap-pdf', compact(
            'rekapList',
            'totalMahasiswaMengisi',
            'totalRekomendasi',
            'topUkmMode'
        ));

        // Format A4 Landscape agar tabel 11 kolom tertata rapi
        $pdf->setPaper('A4', 'landscape');

        $fileName = 'Laporan_Rekap_Hasil_Rekomendasi_UKM_Mahasiswa_' . date('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
