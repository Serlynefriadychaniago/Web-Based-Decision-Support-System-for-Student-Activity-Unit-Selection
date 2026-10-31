@extends('layouts.app')

@section('title', 'Pendaftaran UKM')
@section('page-title', 'Verifikasi Pendaftaran UKM')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#1f2937]">
                Verifikasi Pendaftaran UKM
            </h1>
            <p class="text-sm text-[#6b7280] mt-2">
                Kelola dan verifikasi permohonan pendaftaran UKM yang diajukan oleh mahasiswa.
            </p>
        </div>

        <a href="{{ route('admin.laporan.pendaftaran.index') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#fef2f2] border border-[#fecaca] text-[#da2424] hover:bg-[#da2424] hover:text-white font-bold text-sm shadow-sm transition">
            📊 Laporan Rekap Pendaftaran
        </a>
    </div>

    {{-- NOTIFIKASI SUCCESS --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-xl px-5 py-4">
            <span class="font-bold">✓</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- NOTIFIKASI INFO --}}
    @if(session('info'))
        <div class="mb-6 flex items-center gap-3 bg-[#eef2ff] border border-[#c7d2fe] text-[#3730a3] rounded-xl px-5 py-4">
            <span class="font-bold">i</span>
            <span class="text-sm font-medium">{{ session('info') }}</span>
        </div>
    @endif

    {{-- NOTIFIKASI ERROR --}}
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-xl px-5 py-4">
            <span class="font-bold">✕</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- CARD TABLE --}}
    <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm overflow-hidden">

        {{-- CARD HEADER & FILTER FORM --}}
        <div class="px-6 py-5 border-b border-[#fecaca] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Daftar Permohonan Pendaftaran</h2>
                <p class="text-xs text-[#6b7280] mt-1">Total {{ $pendaftaranList->total() }} pendaftaran tercatat</p>
            </div>

            {{-- FILTERS FORM --}}
            <form action="{{ route('admin.pendaftaran.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                {{-- SEARCH --}}
                <input type="text" name="q" value="{{ $search }}"
                       placeholder="No. Reg / Nama / NIM..."
                       class="w-48 md:w-56 px-4 py-2 text-sm rounded-xl border border-[#fecaca] focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">

                {{-- FILTER UKM --}}
                <select name="ukm_id" class="px-3 py-2 text-sm rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                    <option value="">-- Semua UKM --</option>
                    @foreach($ukmList as $u)
                        <option value="{{ $u->id }}" {{ $selectedUkm == $u->id ? 'selected' : '' }}>
                            {{ $u->nama_ukm }}
                        </option>
                    @endforeach
                </select>

                {{-- FILTER STATUS --}}
                <select name="status" class="px-3 py-2 text-sm rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_verifikasi" {{ $selectedStatus == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="terverifikasi" {{ ($selectedStatus == 'terverifikasi' || $selectedStatus == 'diterima') ? 'selected' : '' }}>Terverifikasi / Diterima</option>
                    <option value="perlu_perbaikan" {{ ($selectedStatus == 'perlu_perbaikan' || $selectedStatus == 'ditolak') ? 'selected' : '' }}>Perlu Perbaikan / Ditolak</option>
                    <option value="selesai" {{ $selectedStatus == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#fef2f2] text-[#da2424] font-semibold text-sm rounded-xl hover:bg-[#fee2e2] transition">
                    Filter
                </button>

                @if($search || $selectedUkm || $selectedStatus)
                    <a href="{{ route('admin.pendaftaran.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 text-sm rounded-xl hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-[#fdf2f2] text-[#4b5563] text-xs uppercase tracking-wider font-semibold border-b border-[#fecaca]">
                    <tr>
                        <th class="px-6 py-4">No. Pendaftaran & Tgl</th>
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">UKM Tujuan</th>
                        <th class="px-6 py-4 text-center">Hasil SPK (Ci)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca] text-sm text-[#1f2937]">
                    @forelse($pendaftaranList as $reg)
                        <tr class="hover:bg-[#fdf2f2]/60 transition">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-[#da2424] text-xs">{{ $reg->nomor_pendaftaran }}</div>
                                <div class="text-xs text-[#6b7280] mt-0.5">
                                    {{ \Carbon\Carbon::parse($reg->tanggal_daftar)->format('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#1f2937]">{{ $reg->nama_mahasiswa }}</div>
                                <div class="text-xs text-[#6b7280]">NIM: {{ $reg->nim }} • {{ $reg->prodi }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-[#1f2937]">
                                {{ $reg->nama_ukm }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($reg->topsis_ranking)
                                    <div class="font-mono text-xs font-bold text-[#da2424]">
                                        Rank #{{ $reg->topsis_ranking }}
                                    </div>
                                    <div class="text-[11px] font-mono text-[#6b7280]">
                                        Ci: {{ number_format((float)$reg->topsis_score, 4) }}
                                    </div>
                                @else
                                    <span class="text-xs text-[#6b7280]">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($reg->status === 'terverifikasi' || $reg->status === 'diterima')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf8f2] text-[#258260]">
                                        ✓ Terverifikasi / Diterima
                                    </span>
                                @elseif($reg->status === 'perlu_perbaikan' || $reg->status === 'ditolak')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                                        ✕ Ditolak / Perbaikan
                                    </span>
                                @elseif($reg->status === 'selesai')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                        ✓ Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#fff5e5] text-[#b76e00]">
                                        ⏳ Menunggu Verifikasi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.pendaftaran.show', $reg->id) }}"
                                   class="px-4 py-2 rounded-xl bg-[#da2424] text-white text-xs font-semibold hover:bg-[#b91c1c] shadow-sm transition inline-block">
                                    Detail & Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#6b7280]">
                                <div class="text-lg font-medium text-[#1f2937]">Belum Ada Permohonan Pendaftaran</div>
                                <p class="text-xs mt-1">Data pendaftaran UKM yang dikirim oleh mahasiswa akan tampil di sini.</p>
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
