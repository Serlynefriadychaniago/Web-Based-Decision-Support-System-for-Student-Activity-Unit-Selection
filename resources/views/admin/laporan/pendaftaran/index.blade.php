@extends('layouts.app')

@section('title', 'Laporan Rekap Pendaftaran UKM')
@section('page-title', 'Laporan Rekap Pendaftaran UKM')

@section('content')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- HELPER FORMATTER --}}
    @php
        $fmtPercent = function ($val) {
            if ($val === null || $val === '') return '-';
            $percent = (float)$val * 100;
            $formatted = number_format($percent, 2, ',', '.');
            if (str_ends_with($formatted, ',00')) {
                $formatted = substr($formatted, 0, -3);
            }
            return $formatted . '%';
        };
    @endphp

    {{-- HEADER & TOP ACTIONS --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#1f2937]">
                Laporan Rekap Pendaftaran UKM
            </h1>
            <p class="text-xs md:text-sm text-[#6b7280] mt-1">
                Rekapitulasi seluruh mahasiswa yang telah melakukan pendaftaran UKM di Universitas Muhammadiyah Cirebon.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.laporan.pendaftaran.pdf', request()->query()) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white font-extrabold text-xs shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Download PDF</span>
            </a>
        </div>
    </div>

    {{-- SUMMARY STATS CARDS (6 STATS DINAMIS) --}}
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-4 text-center space-y-1">
            <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Total Pendaftar</div>
            <div class="text-xl font-extrabold text-[#1f2937]">{{ $stats['total_pendaftar'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-4 text-center space-y-1">
            <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">UKM Berpendaftar</div>
            <div class="text-xl font-extrabold text-[#da2424]">{{ $stats['total_ukm_pendaftar'] }} UKM</div>
        </div>

        <div class="bg-white rounded-2xl border border-[#ffe2b3] bg-[#fff5e5]/40 shadow-sm p-4 text-center space-y-1">
            <div class="text-[11px] font-bold text-[#b76e00] uppercase tracking-wider">Menunggu Verifikasi</div>
            <div class="text-xl font-extrabold text-[#b76e00]">{{ $stats['menunggu_verifikasi'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-[#c9eadc] bg-[#eaf8f2]/40 shadow-sm p-4 text-center space-y-1">
            <div class="text-[11px] font-bold text-[#258260] uppercase tracking-wider">Terverifikasi</div>
            <div class="text-xl font-extrabold text-[#258260]">{{ $stats['terverifikasi'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 bg-amber-50/40 shadow-sm p-4 text-center space-y-1">
            <div class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Perlu Perbaikan</div>
            <div class="text-xl font-extrabold text-amber-700">{{ $stats['perlu_perbaikan'] }}</div>
        </div>

        <div class="bg-white rounded-2xl border border-blue-200 bg-blue-50/40 shadow-sm p-4 text-center space-y-1">
            <div class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Selesai</div>
            <div class="text-xl font-extrabold text-blue-700">{{ $stats['selesai'] }}</div>
        </div>
    </div>

    {{-- CARD TABEL & FILTER FORM --}}
    <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm overflow-hidden space-y-4">

        {{-- FILTER BAR --}}
        <div class="px-6 py-4 border-b border-[#fecaca] bg-[#fdf2f2]/70 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-[#1f2937]">Tabel Rekapitulasi Pendaftaran</h2>
                <p class="text-xs text-[#6b7280] mt-0.5">Menampilkan {{ $pendaftaranList->firstItem() ?? 0 }} - {{ $pendaftaranList->lastItem() ?? 0 }} dari {{ $pendaftaranList->total() }} data</p>
            </div>

            <form action="{{ route('admin.laporan.pendaftaran.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                {{-- SEARCH --}}
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="No. Reg / Nama / NIM..."
                       class="w-40 md:w-52 px-3 py-2 text-xs rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">

                {{-- FILTER UKM --}}
                <select name="ukm_id" class="px-3 py-2 text-xs rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                    <option value="">-- Semua UKM --</option>
                    @foreach($ukmList as $u)
                        <option value="{{ $u->id }}" {{ $selectedUkm == $u->id ? 'selected' : '' }}>
                            {{ $u->nama_ukm }}
                        </option>
                    @endforeach
                </select>

                {{-- FILTER STATUS --}}
                <select name="status" class="px-3 py-2 text-xs rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_verifikasi" {{ $selectedStatus == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="terverifikasi" {{ $selectedStatus == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="perlu_perbaikan" {{ $selectedStatus == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                    <option value="selesai" {{ $selectedStatus == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                {{-- FILTER PERIODE --}}
                <select name="periode" class="px-3 py-2 text-xs rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                    <option value="">-- Semua Periode --</option>
                    <option value="today" {{ $selectedPeriode == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ $selectedPeriode == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ $selectedPeriode == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#da2424] text-white font-bold text-xs rounded-xl hover:bg-[#b91c1c] transition shadow-sm">
                    Filter
                </button>

                @if($search || $selectedUkm || $selectedStatus || $selectedPeriode)
                    <a href="{{ route('admin.laporan.pendaftaran.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- MAIN TABEL REKAP --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#fdf2f2] text-[#4b5563] text-[11px] uppercase tracking-wider font-bold border-b border-[#fecaca]">
                    <tr>
                        <th class="px-4 py-3 text-center" style="width: 40px;">No</th>
                        <th class="px-4 py-3">No. Pendaftaran</th>
                        <th class="px-4 py-3">Nama Mahasiswa</th>
                        <th class="px-4 py-3 text-center">NIM</th>
                        <th class="px-4 py-3">Program Studi</th>
                        <th class="px-4 py-3">UKM yang Dipilih</th>
                        <th class="px-4 py-3 text-center">Tgl Pendaftaran</th>
                        <th class="px-4 py-3 text-center">Rank SPK</th>
                        <th class="px-4 py-3 text-center">Nilai Rekomendasi</th>
                        <th class="px-4 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca] text-xs text-[#1f2937]">
                    @forelse($pendaftaranList as $idx => $reg)
                        @php
                            $rowNo = $pendaftaranList->firstItem() + $idx;

                            $statusMap = [
                                'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'cls' => 'bg-[#fff5e5] text-[#b76e00]'],
                                'terverifikasi'       => ['label' => 'Terverifikasi',       'cls' => 'bg-[#eaf8f2] text-[#258260]'],
                                'diterima'            => ['label' => 'Terverifikasi',       'cls' => 'bg-[#eaf8f2] text-[#258260]'],
                                'perlu_perbaikan'     => ['label' => 'Perlu Perbaikan',     'cls' => 'bg-amber-50 text-amber-700'],
                                'ditolak'             => ['label' => 'Perlu Perbaikan',     'cls' => 'bg-amber-50 text-amber-700'],
                                'selesai'             => ['label' => 'Selesai',             'cls' => 'bg-blue-50 text-blue-700'],
                            ];
                            $st = $statusMap[$reg->status] ?? ['label' => ucwords(str_replace('_', ' ', $reg->status)), 'cls' => 'bg-gray-100 text-gray-700'];
                        @endphp
                        <tr class="hover:bg-[#fcfbff] transition">
                            <td class="px-4 py-3 text-center font-bold text-[#6b7280]">{{ $rowNo }}</td>
                            <td class="px-4 py-3 font-mono font-bold text-[#da2424]">{{ $reg->nomor_pendaftaran }}</td>
                            <td class="px-4 py-3 font-bold text-[#1f2937]">{{ $reg->nama_mahasiswa }}</td>
                            <td class="px-4 py-3 text-center font-mono text-[#4b5563]">{{ $reg->nim }}</td>
                            <td class="px-4 py-3 text-[#4b5563]">{{ $reg->prodi }}</td>
                            <td class="px-4 py-3 font-semibold text-[#1f2937]">{{ $reg->nama_ukm }}</td>
                            <td class="px-4 py-3 text-center text-[#6b7280]">
                                {{ \Carbon\Carbon::parse($reg->tanggal_daftar)->translatedFormat('d-m-Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-[#da2424]">
                                {{ $reg->topsis_ranking ? '#' . $reg->topsis_ranking : '-' }}
                            </td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-[#da2424]">
                                {{ $fmtPercent($reg->topsis_score) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $st['cls'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-[#6b7280]">
                                <div class="text-base font-bold text-[#1f2937]">Belum Ada Permohonan Pendaftaran</div>
                                <p class="text-xs mt-1">Tidak ada data pendaftaran yang memenuhi filter saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($pendaftaranList->hasPages())
            <div class="px-6 py-4 border-t border-[#fecaca] bg-[#fdf2f2]">
                {{ $pendaftaranList->links() }}
            </div>
        @endif

    </div>
</div>
@endsection