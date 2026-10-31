<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan daftar pendaftaran UKM oleh mahasiswa.
     */
    public function index(Request $request)
    {
        $search = trim($request->get('q'));
        $selectedUkm = $request->get('ukm_id');
        $selectedStatus = $request->get('status');

        $query = DB::table('pendaftaran_ukm')
            ->join('mahasiswa', 'pendaftaran_ukm.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('ukm', 'pendaftaran_ukm.ukm_id', '=', 'ukm.id')
            ->leftJoin('topsis_results', 'pendaftaran_ukm.topsis_result_id', '=', 'topsis_results.id')
            ->select(
                'pendaftaran_ukm.*',
                'mahasiswa.nama as nama_mahasiswa',
                'mahasiswa.nim',
                'mahasiswa.prodi',
                'users.email',
                'ukm.nama_ukm',
                'topsis_results.ranking as topsis_ranking',
                'topsis_results.nilai_preferensi as topsis_score'
            );

        // Filter Pencarian Text (Nomor Pendaftaran, Nama Mahasiswa, NIM)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('pendaftaran_ukm.nomor_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nim', 'like', "%{$search}%");
            });
        }

        // Filter UKM
        if (!empty($selectedUkm)) {
            $query->where('pendaftaran_ukm.ukm_id', $selectedUkm);
        }

        // Filter Status (Pemetaan alias status UI ke Enum DB)
        if (!empty($selectedStatus)) {
            $mappedStatus = $selectedStatus;
            if ($selectedStatus === 'diterima') $mappedStatus = 'terverifikasi';
            if ($selectedStatus === 'ditolak') $mappedStatus = 'perlu_perbaikan';
            $query->where('pendaftaran_ukm.status', $mappedStatus);
        }

        $pendaftaranList = $query->orderByDesc('pendaftaran_ukm.id')->paginate(10);
        $ukmList = DB::table('ukm')->orderBy('nama_ukm')->get();

        return view('admin.pendaftaran.index', compact(
            'pendaftaranList',
            'ukmList',
            'search',
            'selectedUkm',
            'selectedStatus'
        ));
    }

    /**
     * Menampilkan detail pendaftaran UKM, data SPK, dan riwayat status.
     */
    public function show($id)
    {
        $pendaftaran = DB::table('pendaftaran_ukm')
            ->join('mahasiswa', 'pendaftaran_ukm.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('users', 'mahasiswa.user_id', '=', 'users.id')
            ->join('ukm', 'pendaftaran_ukm.ukm_id', '=', 'ukm.id')
            ->leftJoin('topsis_results', 'pendaftaran_ukm.topsis_result_id', '=', 'topsis_results.id')
            ->leftJoin('topsis_runs', 'topsis_results.topsis_run_id', '=', 'topsis_runs.id')
            ->where('pendaftaran_ukm.id', $id)
            ->select(
                'pendaftaran_ukm.*',
                'mahasiswa.id as mhs_id',
                'mahasiswa.nama as nama_mahasiswa',
                'mahasiswa.nim',
                'mahasiswa.prodi',
                'mahasiswa.fakultas',
                'mahasiswa.angkatan',
                'mahasiswa.jenis_kelamin',
                'mahasiswa.no_hp',
                'users.email',
                'users.username',
                'ukm.nama_ukm',
                'ukm.deskripsi as ukm_deskripsi',
                'topsis_results.ranking as topsis_ranking',
                'topsis_results.nilai_preferensi as topsis_score',
                'topsis_results.s_plus',
                'topsis_results.s_minus',
                'topsis_runs.questionnaire_session_id'
            )
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('admin.pendaftaran.index')
                ->with('error', 'Data pendaftaran UKM tidak ditemukan.');
        }

        // Ambil jawaban kuesioner jika ada questionnaire_session_id
        $kuesionerAnswers = [];
        $sessionId = $pendaftaran->questionnaire_session_id;

        if (!$sessionId) {
            $latestSession = DB::table('questionnaire_sessions')
                ->where('mahasiswa_id', $pendaftaran->mhs_id)
                ->orderByDesc('id')
                ->first();
            $sessionId = $latestSession ? $latestSession->id : null;
        }

        if ($sessionId) {
            $kuesionerAnswers = DB::table('questionnaire_answers')
                ->join('kriteria', 'questionnaire_answers.kriteria_id', '=', 'kriteria.id')
                ->join('sub_kriteria', 'questionnaire_answers.sub_kriteria_id', '=', 'sub_kriteria.id')
                ->where('questionnaire_answers.session_id', $sessionId)
                ->select(
                    'kriteria.kode as kriteria_kode',
                    'kriteria.nama_kriteria',
                    'sub_kriteria.nama_subkriteria as nama_sub_kriteria',
                    'questionnaire_answers.nilai'
                )
                ->orderBy('kriteria.kode')
                ->get();
        }

        // Ambil riwayat status dari tabel riwayat_status_pendaftaran (dengan JOIN ke users)
        $riwayatStatus = DB::table('riwayat_status_pendaftaran')
            ->leftJoin('users', 'riwayat_status_pendaftaran.diubah_oleh', '=', 'users.id')
            ->where('riwayat_status_pendaftaran.pendaftaran_id', $id)
            ->select('riwayat_status_pendaftaran.*', 'users.name as admin_name')
            ->orderBy('riwayat_status_pendaftaran.id', 'asc')
            ->get();

        return view('admin.pendaftaran.show', compact(
            'pendaftaran',
            'kuesionerAnswers',
            'riwayatStatus'
        ));
    }

    /**
     * Memverifikasi pendaftaran UKM (Terima / Tolak) dan menyimpan log riwayat status.
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:terverifikasi,perlu_perbaikan,selesai,diterima,ditolak',
            'catatan' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Pilih status verifikasi.',
            'status.in'       => 'Status verifikasi tidak valid.',
        ]);

        $pendaftaran = DB::table('pendaftaran_ukm')->where('id', $id)->first();

        if (!$pendaftaran) {
            return redirect()->route('admin.pendaftaran.index')
                ->with('error', 'Data pendaftaran UKM tidak ditemukan.');
        }

        $inputStatus = $request->input('status');
        $catatan = trim($request->input('catatan'));

        // Pemetaan alias status input ke Enum DB (terverifikasi / perlu_perbaikan / selesai)
        $statusMap = [
            'diterima'        => 'terverifikasi',
            'ditolak'         => 'perlu_perbaikan',
            'terverifikasi'   => 'terverifikasi',
            'perlu_perbaikan' => 'perlu_perbaikan',
            'selesai'         => 'selesai',
        ];

        $targetDbStatus = $statusMap[$inputStatus] ?? 'terverifikasi';

        if ($pendaftaran->status === $targetDbStatus) {
            return back()->with('info', 'Pendaftaran ini sudah berstatus "' . str_replace('_', ' ', $targetDbStatus) . '".');
        }

        try {
            DB::beginTransaction();

            $adminId = Auth::id();
            $adminName = Auth::user()->name ?? 'Administrator';

            // 1. Update status pendaftaran_ukm
            DB::table('pendaftaran_ukm')
                ->where('id', $id)
                ->update([
                    'status'             => $targetDbStatus,
                    'tanggal_verifikasi' => now(),
                ]);

            // 2. Simpan riwayat status pendaftaran (diubah_oleh menerima ID integer User)
            $logCatatan = $catatan !== '' ? $catatan : ('Status diubah menjadi "' . str_replace('_', ' ', $targetDbStatus) . '" oleh admin ' . $adminName . '.');

            DB::table('riwayat_status_pendaftaran')->insert([
                'pendaftaran_id' => $id,
                'status'         => $targetDbStatus,
                'catatan'        => $logCatatan,
                'diubah_oleh'    => $adminId,
                'created_at'     => now(),
            ]);

            DB::commit();

            $statusText = strtoupper(str_replace('_', ' ', $targetDbStatus));
            return redirect()->route('admin.pendaftaran.show', $id)
                ->with('success', 'Status pendaftaran berhasil diperbarui menjadi ' . $statusText . '!');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status pendaftaran: ' . $e->getMessage());
        }
    }
}
