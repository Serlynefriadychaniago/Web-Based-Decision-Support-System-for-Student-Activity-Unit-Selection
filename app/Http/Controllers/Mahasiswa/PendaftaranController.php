<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureMahasiswaProfileComplete;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan riwayat pendaftaran UKM mahasiswa.
     */
    public function index()
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum melihat pendaftaran.');
        }

        $mahasiswa = $profileStats['mahasiswa'];

        $pendaftaranList = DB::table('pendaftaran_ukm')
            ->join('ukm', 'pendaftaran_ukm.ukm_id', '=', 'ukm.id')
            ->leftJoin('topsis_results', 'pendaftaran_ukm.topsis_result_id', '=', 'topsis_results.id')
            ->where('pendaftaran_ukm.mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('pendaftaran_ukm.id')
            ->select(
                'pendaftaran_ukm.*',
                'ukm.nama_ukm',
                'ukm.deskripsi as ukm_deskripsi',
                'topsis_results.ranking as topsis_ranking',
                'topsis_results.nilai_preferensi as topsis_score'
            )
            ->get();

        $pendaftaranIds = $pendaftaranList->pluck('id')->toArray();
        $historyLogs = collect();

        if (!empty($pendaftaranIds)) {
            $historyLogs = DB::table('riwayat_status_pendaftaran')
                ->leftJoin('users', 'riwayat_status_pendaftaran.diubah_oleh', '=', 'users.id')
                ->whereIn('pendaftaran_id', $pendaftaranIds)
                ->select('riwayat_status_pendaftaran.*', 'users.name as admin_name')
                ->orderBy('riwayat_status_pendaftaran.id', 'asc')
                ->get()
                ->groupBy('pendaftaran_id');
        }

        return view('mahasiswa.pendaftaran.index', compact('mahasiswa', 'pendaftaranList', 'historyLogs'));
    }

    /**
     * Menampilkan formulir pendaftaran UKM baru.
     */
    public function create($ukmId, Request $request)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum mendaftar UKM.');
        }

        $mahasiswa = $profileStats['mahasiswa'];
        $user = $profileStats['user'];

        $ukm = DB::table('ukm')->where('id', $ukmId)->first();

        if (!$ukm) {
            return redirect()->route('mahasiswa.rekomendasi.index')
                ->with('error', 'UKM yang dipilih tidak ditemukan.');
        }

        // Pengecekan apakah mahasiswa sudah pernah mendaftar ke UKM ini
        $existing = DB::table('pendaftaran_ukm')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('ukm_id', $ukm->id)
            ->first();

        if ($existing) {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Anda sudah mendaftar ke UKM ' . $ukm->nama_ukm . ' sebelumnya.');
        }

        // Ambil data TOPSIS result ID jika ada
        $topsisResultId = $request->query('topsis_result_id');
        $topsisResult = null;

        if ($topsisResultId) {
            $topsisResult = DB::table('topsis_results')
                ->join('topsis_runs', 'topsis_results.topsis_run_id', '=', 'topsis_runs.id')
                ->where('topsis_results.id', $topsisResultId)
                ->where('topsis_runs.mahasiswa_id', $mahasiswa->id)
                ->select('topsis_results.*')
                ->first();

            if (!$topsisResult) {
                $topsisResultId = null;
            }
        }

        // Fallback pencarian hasil TOPSIS terakhir jika tidak ada query param
        if (!$topsisResult) {
            $topsisResult = DB::table('topsis_results')
                ->join('topsis_runs', 'topsis_results.topsis_run_id', '=', 'topsis_runs.id')
                ->where('topsis_runs.mahasiswa_id', $mahasiswa->id)
                ->where('topsis_results.ukm_id', $ukm->id)
                ->orderByDesc('topsis_runs.id')
                ->select('topsis_results.*')
                ->first();

            if ($topsisResult) {
                $topsisResultId = $topsisResult->id;
            }
        }

        return view('mahasiswa.pendaftaran.create', compact('mahasiswa', 'user', 'ukm', 'topsisResultId', 'topsisResult'));
    }

    /**
     * Menyimpan pendaftaran UKM baru oleh mahasiswa.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum melakukan pendaftaran.');
        }

        $mahasiswa = $profileStats['mahasiswa'];

        $request->validate([
            'ukm_id'                       => 'required|exists:ukm,id',
            'topsis_result_id'             => 'nullable|exists:topsis_results,id',
            'alasan_mendaftar'             => 'required|string|max:1000',
            'ketertarikan'                 => 'required|string|max:1000',
            'pengalaman'                   => 'required|string|in:Belum pernah,Pernah sedikit,Cukup berpengalaman,Sangat berpengalaman',
            'detail_pengalaman'            => 'nullable|string|max:1000',
            'keterampilan'                 => 'required|string|max:1000',
            'kesediaan_mengikuti_kegiatan' => 'required|string|in:Tidak siap,Kurang siap,Cukup siap,Siap,Sangat siap',
            'kesesuaian_jadwal'            => 'nullable|string|max:200',
            'kontribusi'                   => 'required|string|max:1000',
            'kesediaan_menaati_aturan'     => 'required|string|in:Ya,Tidak',
            'komitmen_aktif'               => 'required|string|max:200',
        ], [
            'alasan_mendaftar.required'             => 'Alasan memilih UKM wajib diisi.',
            'ketertarikan.required'                 => 'Skor & motivasi minat wajib diisi.',
            'pengalaman.required'                   => 'Pengalaman kegiatan wajib dipilih.',
            'keterampilan.required'                 => 'Keahlian/minat wajib diisi.',
            'kesediaan_mengikuti_kegiatan.required' => 'Tingkat kesiapan latihan wajib dipilih.',
            'kontribusi.required'                   => 'Kemampuan atau target kontribusi wajib diisi.',
            'kesediaan_menaati_aturan.required'     => 'Pernyataan kesediaan menaati aturan wajib dipilih.',
            'komitmen_aktif.required'               => 'Tujuan utama bergabung wajib dipilih.',
        ]);

        $ukmId = $request->input('ukm_id');
        $topsisResultId = $request->input('topsis_result_id');

        // Verifikasi kepemilikan topsis_result_id milik mahasiswa yang sedang login
        if ($topsisResultId) {
            $isValidOwnership = DB::table('topsis_results')
                ->join('topsis_runs', 'topsis_results.topsis_run_id', '=', 'topsis_runs.id')
                ->where('topsis_results.id', $topsisResultId)
                ->where('topsis_runs.mahasiswa_id', $mahasiswa->id)
                ->exists();

            if (!$isValidOwnership) {
                $topsisResultId = null;
            }
        }

        // Cek apakah sudah pernah mendaftar di UKM yang sama
        $existing = DB::table('pendaftaran_ukm')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('ukm_id', $ukmId)
            ->first();

        if ($existing) {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Anda sudah mendaftar di UKM ini sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // Generate nomor pendaftaran unik
            $nomorPendaftaran = 'REG-UKM-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $pendaftaranId = DB::table('pendaftaran_ukm')->insertGetId([
                'nomor_pendaftaran'            => $nomorPendaftaran,
                'mahasiswa_id'                 => $mahasiswa->id,
                'ukm_id'                      => $ukmId,
                'topsis_result_id'            => $topsisResultId,
                'alasan_mendaftar'            => $request->input('alasan_mendaftar'),
                'ketertarikan'                => $request->input('ketertarikan'),
                'pengalaman'                  => $request->input('pengalaman'),
                'detail_pengalaman'           => $request->input('detail_pengalaman'),
                'keterampilan'                => $request->input('keterampilan'),
                'kesediaan_mengikuti_kegiatan' => $request->input('kesediaan_mengikuti_kegiatan'),
                'kesesuaian_jadwal'           => $request->input('kesesuaian_jadwal') ?? 'Siap menyesuaikan',
                'kontribusi'                  => $request->input('kontribusi'),
                'kesediaan_menaati_aturan'    => $request->input('kesediaan_menaati_aturan'),
                'komitmen_aktif'              => $request->input('komitmen_aktif'),
                'persetujuan_data'            => true,
                'persetujuan_keputusan_ukm'   => true,
                'persetujuan'                 => true,
                'status'                      => 'menunggu_verifikasi',
                'tanggal_daftar'              => now(),
            ]);

            // Log riwayat status awal
            DB::table('riwayat_status_pendaftaran')->insert([
                'pendaftaran_id' => $pendaftaranId,
                'status'         => 'menunggu_verifikasi',
                'catatan'        => 'Pendaftaran UKM berhasil dikirim oleh mahasiswa.',
                'diubah_oleh'    => $userId,
                'created_at'     => now(),
            ]);

            DB::commit();

            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('success', 'Pendaftaran UKM berhasil dikirim! Nomor pendaftaran Anda: ' . $nomorPendaftaran);

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mendaftar UKM: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form perbaikan data pendaftaran (jika status perlu_perbaikan).
     */
    public function edit($id)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $mahasiswa = $profileStats['mahasiswa'];
        $user = $profileStats['user'];

        $pendaftaran = DB::table('pendaftaran_ukm')
            ->where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Data pendaftaran tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($pendaftaran->status !== 'perlu_perbaikan') {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Hanya pendaftaran dengan status "perlu perbaikan" yang dapat diperbarui.');
        }

        $ukm = DB::table('ukm')->where('id', $pendaftaran->ukm_id)->first();

        // Ambil catatan admin terakhir dari riwayat status
        $latestLog = DB::table('riwayat_status_pendaftaran')
            ->leftJoin('users', 'riwayat_status_pendaftaran.diubah_oleh', '=', 'users.id')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->where('riwayat_status_pendaftaran.status', 'perlu_perbaikan')
            ->orderByDesc('riwayat_status_pendaftaran.id')
            ->select('riwayat_status_pendaftaran.*', 'users.name as admin_name')
            ->first();

        return view('mahasiswa.pendaftaran.edit', compact('mahasiswa', 'user', 'pendaftaran', 'ukm', 'latestLog'));
    }

    /**
     * Menyimpan perbaikan data pendaftaran UKM oleh mahasiswa.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $mahasiswa = $profileStats['mahasiswa'];

        $pendaftaran = DB::table('pendaftaran_ukm')
            ->where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        if ($pendaftaran->status !== 'perlu_perbaikan') {
            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('error', 'Hanya pendaftaran berstatus perlu perbaikan yang dapat diubah.');
        }

        $request->validate([
            'alasan_mendaftar'             => 'required|string|max:1000',
            'ketertarikan'                 => 'required|string|max:1000',
            'pengalaman'                   => 'required|string|in:Belum pernah,Pernah sedikit,Cukup berpengalaman,Sangat berpengalaman',
            'detail_pengalaman'            => 'nullable|string|max:1000',
            'keterampilan'                 => 'required|string|max:1000',
            'kesediaan_mengikuti_kegiatan' => 'required|string|in:Tidak siap,Kurang siap,Cukup siap,Siap,Sangat siap',
            'kesesuaian_jadwal'            => 'nullable|string|max:200',
            'kontribusi'                   => 'required|string|max:1000',
            'kesediaan_menaati_aturan'     => 'required|string|in:Ya,Tidak',
            'komitmen_aktif'               => 'required|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            DB::table('pendaftaran_ukm')
                ->where('id', $id)
                ->update([
                    'alasan_mendaftar'            => $request->input('alasan_mendaftar'),
                    'ketertarikan'                => $request->input('ketertarikan'),
                    'pengalaman'                  => $request->input('pengalaman'),
                    'detail_pengalaman'           => $request->input('detail_pengalaman'),
                    'keterampilan'                => $request->input('keterampilan'),
                    'kesediaan_mengikuti_kegiatan' => $request->input('kesediaan_mengikuti_kegiatan'),
                    'kesesuaian_jadwal'           => $request->input('kesesuaian_jadwal') ?? 'Siap menyesuaikan',
                    'kontribusi'                  => $request->input('kontribusi'),
                    'kesediaan_menaati_aturan'    => $request->input('kesediaan_menaati_aturan'),
                    'komitmen_aktif'              => $request->input('komitmen_aktif'),
                    'status'                      => 'menunggu_verifikasi',
                    'tanggal_verifikasi'          => null,
                ]);

            DB::table('riwayat_status_pendaftaran')->insert([
                'pendaftaran_id' => $id,
                'status'         => 'menunggu_verifikasi',
                'catatan'        => 'Mahasiswa telah memperbarui data pendaftaran setelah instruksi perbaikan dari Admin.',
                'diubah_oleh'    => $userId,
                'created_at'     => now(),
            ]);

            DB::commit();

            return redirect()->route('mahasiswa.pendaftaran.index')
                ->with('success', 'Data pendaftaran berhasil diperbarui! Status dikembalikan ke Menunggu Verifikasi.');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui data pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Mengunduh PDF rincian data pendaftaran UKM mahasiswa.
     */
    public function downloadPdf($id)
    {
        $userId = Auth::id();
        $profileStats = EnsureMahasiswaProfileComplete::checkProfileStats($userId);

        if (!$profileStats['is_complete']) {
            return redirect()->route('profile.edit')
                ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $mahasiswa = $profileStats['mahasiswa'];
        $user = $profileStats['user'];

        // 1. Ambil data pendaftaran berdasarkan ID
        $pendaftaran = DB::table('pendaftaran_ukm')
            ->join('ukm', 'pendaftaran_ukm.ukm_id', '=', 'ukm.id')
            ->leftJoin('topsis_results', 'pendaftaran_ukm.topsis_result_id', '=', 'topsis_results.id')
            ->where('pendaftaran_ukm.id', $id)
            ->select(
                'pendaftaran_ukm.*',
                'ukm.nama_ukm',
                'ukm.deskripsi as ukm_deskripsi',
                'ukm.pembina as ukm_pembina',
                'ukm.ketua as ukm_ketua',
                'ukm.kontak as ukm_kontak',
                'ukm.email as ukm_email',
                'ukm.hari_latihan as ukm_hari_latihan',
                'topsis_results.ranking as topsis_ranking',
                'topsis_results.nilai_preferensi as topsis_score'
            )
            ->first();

        // 2. OTORISASI SANGAT KETAT: Memastikan pendaftaran milik mahasiswa yang sedang login (Mencegah IDOR)
        if (!$pendaftaran || $pendaftaran->mahasiswa_id != $mahasiswa->id) {
            abort(403, 'Anda tidak memiliki otorisasi untuk mengunduh dokumen pendaftaran ini.');
        }

        // 3. Ambil data riwayat status pendaftaran
        $historyLogs = DB::table('riwayat_status_pendaftaran')
            ->leftJoin('users', 'riwayat_status_pendaftaran.diubah_oleh', '=', 'users.id')
            ->where('pendaftaran_id', $pendaftaran->id)
            ->select('riwayat_status_pendaftaran.*', 'users.name as admin_name')
            ->orderBy('riwayat_status_pendaftaran.id', 'asc')
            ->get();

        // 4. Render PDF menggunakan Barryvdh\DomPDF\Facade\Pdf
        $pdf = Pdf::loadView('mahasiswa.pendaftaran.pdf', compact(
            'mahasiswa',
            'user',
            'pendaftaran',
            'historyLogs'
        ));

        $pdf->setPaper('A4', 'portrait');

        $cleanNomor = preg_replace('/[^A-Za-z0-9]/', '_', $pendaftaran->nomor_pendaftaran ?: 'pendaftaran');
        $fileName = 'Detail_Pendaftaran_UKM_' . $cleanNomor . '.pdf';

        return $pdf->download($fileName);
    }
}
