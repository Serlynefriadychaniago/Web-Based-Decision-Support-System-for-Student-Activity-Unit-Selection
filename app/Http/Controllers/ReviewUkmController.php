<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\ReviewUkm;
use App\Models\Ukm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewUkmController extends Controller
{
    /**
     * =========================================================================
     * FITUR MAHASISWA
     * =========================================================================
     */

    /**
     * Menyimpan review baru yang diajukan oleh mahasiswa.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data profil mahasiswa tidak ditemukan. Silakan lengkapi profil terlebih dahulu.');
        }

        $validated = $request->validate([
            'ukm_id'   => 'required|exists:ukm,id',
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:3|max:1000',
        ], [
            'ukm_id.required'   => 'ID UKM wajib ditentukan.',
            'ukm_id.exists'     => 'Data UKM tidak valid.',
            'rating.required'   => 'Rating bintang wajib dipilih (1-5).',
            'rating.integer'    => 'Rating harus berupa angka.',
            'rating.min'        => 'Rating minimal bernilai 1 bintang.',
            'rating.max'        => 'Rating maksimal bernilai 5 bintang.',
            'komentar.required' => 'Komentar ulasan wajib diisi.',
            'komentar.min'      => 'Komentar minimal berisi 3 karakter.',
            'komentar.max'      => 'Komentar maksimal berisi 1000 karakter.',
        ]);

        $ukmId = (int)$validated['ukm_id'];

        // Aturan 2: Mahasiswa hanya boleh mereview UKM yang pernah didaftarkan
        $isRegistered = DB::table('pendaftaran_ukm')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('ukm_id', $ukmId)
            ->exists();

        if (!$isRegistered) {
            return back()->with('error', 'Anda hanya dapat memberikan review kepada UKM yang pernah Anda daftarkan/ikuti.');
        }

        // Aturan 3: Satu mahasiswa hanya boleh memberikan SATU review untuk SATU UKM
        $existingReview = ReviewUkm::where('mahasiswa_id', $mahasiswa->id)
            ->where('ukm_id', $ukmId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah pernah memberikan ulasan untuk UKM ini.');
        }

        // Simpan review dengan status pending untuk moderasi
        ReviewUkm::create([
            'mahasiswa_id' => $mahasiswa->id,
            'ukm_id'       => $ukmId,
            'rating'       => (int)$validated['rating'],
            'komentar'     => trim($validated['komentar']),
            'status'       => 'pending',
        ]);

        return back()->with('success', 'Ulasan Anda berhasil dikirim dan menunggu moderasi admin sebelum ditampilkan ke publik.');
    }

    /**
     * Memperbarui review milik mahasiswa sendiri.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data profil mahasiswa tidak ditemukan.');
        }

        $review = ReviewUkm::findOrFail($id);

        // Security: Mahasiswa hanya dapat mengedit review miliknya sendiri
        if ((int)$review->mahasiswa_id !== (int)$mahasiswa->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah ulasan ini.');
        }

        $validated = $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:3|max:1000',
        ], [
            'rating.required'   => 'Rating bintang wajib dipilih (1-5).',
            'rating.integer'    => 'Rating harus berupa angka.',
            'rating.min'        => 'Rating minimal bernilai 1 bintang.',
            'rating.max'        => 'Rating maksimal bernilai 5 bintang.',
            'komentar.required' => 'Komentar ulasan wajib diisi.',
            'komentar.min'      => 'Komentar minimal berisi 3 karakter.',
            'komentar.max'      => 'Komentar maksimal berisi 1000 karakter.',
        ]);

        // Perbarui data dan kembalikan status ke pending agar dimoderasi kembali
        $review->update([
            'rating'   => (int)$validated['rating'],
            'komentar' => trim($validated['komentar']),
            'status'   => 'pending',
        ]);

        return back()->with('success', 'Ulasan Anda berhasil diperbarui dan dikirim ulang untuk moderasi admin.');
    }

    /**
     * Menghapus review milik mahasiswa sendiri.
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data profil mahasiswa tidak ditemukan.');
        }

        $review = ReviewUkm::findOrFail($id);

        // Security: Mahasiswa hanya dapat menghapus review miliknya sendiri
        if ((int)$review->mahasiswa_id !== (int)$mahasiswa->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus ulasan ini.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan Anda berhasil dihapus.');
    }

    /**
     * =========================================================================
     * FITUR ADMIN
     * =========================================================================
     */

    /**
     * Menampilkan daftar review untuk moderasi admin.
     */
    public function index(Request $request)
    {
        $search = trim($request->get('q', ''));
        $selectedUkm = $request->get('ukm_id');
        $selectedStatus = $request->get('status');

        $query = ReviewUkm::with(['mahasiswa.user', 'ukm']);

        // Filter Pencarian Teks (Nama Mahasiswa, NIM, Isi Komentar)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('komentar', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($m) use ($search) {
                      $m->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  })
                  ->orWhereHas('ukm', function ($u) use ($search) {
                      $u->where('nama_ukm', 'like', "%{$search}%");
                  });
            });
        }

        // Filter UKM
        if (!empty($selectedUkm)) {
            $query->where('ukm_id', $selectedUkm);
        }

        // Filter Status Moderasi
        if (!empty($selectedStatus) && in_array($selectedStatus, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $selectedStatus);
        }

        $reviews = $query->orderByDesc('id')->paginate(10)->withQueryString();
        $ukmList = Ukm::orderBy('nama_ukm')->get();

        // Rekap statistik untuk badge di atas halaman
        $stats = [
            'total'    => ReviewUkm::count(),
            'pending'  => ReviewUkm::where('status', 'pending')->count(),
            'approved' => ReviewUkm::where('status', 'approved')->count(),
            'rejected' => ReviewUkm::where('status', 'rejected')->count(),
        ];

        return view('admin.reviews.index', compact(
            'reviews',
            'ukmList',
            'search',
            'selectedUkm',
            'selectedStatus',
            'stats'
        ));
    }

    /**
     * Moderasi Admin: Menyetujui review.
     */
    public function approve($id)
    {
        $review = ReviewUkm::findOrFail($id);
        $review->update(['status' => 'approved']);

        return back()->with('success', 'Review berhasil disetujui (Approved) dan kini tampil di halaman publik UKM.');
    }

    /**
     * Moderasi Admin: Menolak review.
     */
    public function reject($id)
    {
        $review = ReviewUkm::findOrFail($id);
        $review->update(['status' => 'rejected']);

        return back()->with('info', 'Review berhasil ditolak (Rejected) dan tidak akan ditampilkan ke publik.');
    }

    /**
     * Moderasi Admin: Menghapus review secara permanen.
     */
    public function adminDestroy($id)
    {
        $review = ReviewUkm::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review berhasil dihapus secara permanen.');
    }
}
