<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPendaftaranController extends Controller
{
    /**
     * Menampilkan halaman Laporan Rekap Pendaftaran UKM untuk Admin.
     */
    public function index(Request $request)
    {
        $search          = trim($request->get('q'));
        $selectedUkm     = $request->get('ukm_id');
        $selectedStatus  = $request->get('status');
        $selectedPeriode = $request->get('periode');

        // Build filtered query & data list
        $queryData = $this->buildPendaftaranQuery($search, $selectedUkm, $selectedStatus, $selectedPeriode);
        $pendaftaranList = $queryData->orderByDesc('pendaftaran_ukm.id')->paginate(15)->withQueryString();

        // Calculate dynamic stats from database
        $stats = $this->calculateSummaryStats();

        $ukmList = DB::table('ukm')->orderBy('nama_ukm')->get();

        return view('admin.laporan.pendaftaran.index', compact(
            'pendaftaranList',
            'ukmList',
            'stats',
            'search',
            'selectedUkm',
            'selectedStatus',
            'selectedPeriode'
        ));
    }

    /**
     * Mengunduh Laporan Rekap Pendaftaran UKM dalam bentuk PDF (A4 Landscape).
     */
    public function downloadPdf(Request $request)
    {
        $search          = trim($request->get('q'));
        $selectedUkm     = $request->get('ukm_id');
        $selectedStatus  = $request->get('status');
        $selectedPeriode = $request->get('periode');

        $queryData = $this->buildPendaftaranQuery($search, $selectedUkm, $selectedStatus, $selectedPeriode);
        $pendaftaranList = $queryData->orderByDesc('pendaftaran_ukm.id')->get();

        $stats = $this->calculateSummaryStats();
        $ukmList = DB::table('ukm')->orderBy('nama_ukm')->get();

        // Selected UKM Name for report header
        $selectedUkmName = 'Semua UKM';
        if ($selectedUkm) {
            $foundUkm = $ukmList->firstWhere('id', $selectedUkm);
            if ($foundUkm) {
                $selectedUkmName = $foundUkm->nama_ukm;
            }
        }

        $pdf = Pdf::loadView('admin.laporan.pendaftaran.pdf', compact(
            'pendaftaranList',
            'stats',
            'search',
            'selectedUkm',
            'selectedUkmName',
            'selectedStatus',
            'selectedPeriode'
        ));

        $pdf->setPaper('A4', 'landscape');

        $fileName = 'Laporan_Rekap_Pendaftaran_UKM_' . date('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Helper untuk membangun Query Pendaftaran dengan filter & search.
     */
    private function buildPendaftaranQuery($search, $selectedUkm, $selectedStatus, $selectedPeriode)
    {
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
                'mahasiswa.fakultas',
                'users.email',
                'ukm.nama_ukm',
                'topsis_results.ranking as topsis_ranking',
                'topsis_results.nilai_preferensi as topsis_score'
            );

        // Search Text (Nomor Pendaftaran, Nama Mahasiswa, NIM)
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

        // Filter Status
        if (!empty($selectedStatus)) {
            if ($selectedStatus === 'terverifikasi') {
                $query->whereIn('pendaftaran_ukm.status', ['terverifikasi', 'diterima']);
            } elseif ($selectedStatus === 'perlu_perbaikan') {
                $query->whereIn('pendaftaran_ukm.status', ['perlu_perbaikan', 'ditolak']);
            } else {
                $query->where('pendaftaran_ukm.status', $selectedStatus);
            }
        }

        // Filter Tanggal / Periode
        if (!empty($selectedPeriode)) {
            if ($selectedPeriode === 'today') {
                $query->whereDate('pendaftaran_ukm.tanggal_daftar', Carbon::today());
            } elseif ($selectedPeriode === 'this_week') {
                $query->whereBetween('pendaftaran_ukm.tanggal_daftar', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($selectedPeriode === 'this_month') {
                $query->whereYear('pendaftaran_ukm.tanggal_daftar', Carbon::now()->year)
                      ->whereMonth('pendaftaran_ukm.tanggal_daftar', Carbon::now()->month);
            }
        }

        return $query;
    }

    /**
     * Helper untuk menghitung statistik ringkasan dinamis dari database.
     */
    private function calculateSummaryStats()
    {
        return [
            'total_pendaftar'     => DB::table('pendaftaran_ukm')->count(),
            'total_ukm_pendaftar' => DB::table('pendaftaran_ukm')->distinct()->count('ukm_id'),
            'menunggu_verifikasi' => DB::table('pendaftaran_ukm')->where('status', 'menunggu_verifikasi')->count(),
            'terverifikasi'       => DB::table('pendaftaran_ukm')->whereIn('status', ['terverifikasi', 'diterima'])->count(),
            'perlu_perbaikan'     => DB::table('pendaftaran_ukm')->whereIn('status', ['perlu_perbaikan', 'ditolak'])->count(),
            'selesai'             => DB::table('pendaftaran_ukm')->where('status', 'selesai')->count(),
        ];
    }
}
