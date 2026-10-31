@extends('layouts.app')

@section('title', 'Data Mahasiswa')
@section('page-title', 'Kelola Data Mahasiswa')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#1f2937]">
                Data Mahasiswa
            </h1>
            <p class="text-sm text-[#6b7280] mt-2">
                Kelola data mahasiswa, akun login, status kuesioner SPK, dan pendaftaran UKM.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.mahasiswa.rekomendasi-rekap.pdf') }}"
               target="_blank"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#fef2f2] border border-[#fecaca] text-[#da2424] hover:bg-[#da2424] hover:text-white font-bold text-sm shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Rekap Rekomendasi (PDF)
            </a>

            <a href="{{ route('admin.mahasiswa.create') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#da2424] text-white font-semibold text-sm hover:bg-[#b91c1c] shadow-sm transition">
                <span class="text-lg">+</span>
                Tambah Mahasiswa
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI SUCCESS --}}
    @if(session('success'))
        @php
            $isDelete = str_contains(strtolower(session('success')), 'hapus');
        @endphp

        @if($isDelete)
            <div class="mb-6 flex items-center gap-3 bg-[#fef2f2] border border-[#fecaca] text-[#da2424] rounded-xl px-5 py-4 shadow-sm">
                <span class="w-7 h-7 rounded-lg bg-[#fee2e2] flex items-center justify-center font-bold text-sm text-[#da2424] flex-shrink-0">
                    🗑️
                </span>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @else
            <div class="mb-6 flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-xl px-5 py-4 shadow-sm">
                <span class="font-bold">✓</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
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

        {{-- CARD HEADER & SEARCH --}}
        <div class="px-6 py-5 border-b border-[#fecaca] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Daftar Mahasiswa</h2>
                <p class="text-xs text-[#6b7280] mt-1">Total {{ $mahasiswaList->total() }} Mahasiswa terdaftar</p>
            </div>

            {{-- SEARCH FORM --}}
            <form action="{{ route('admin.mahasiswa.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="q" value="{{ $search }}"
                           placeholder="Cari Nama / NIM / Prodi..."
                           class="w-64 px-4 py-2 text-sm rounded-xl border border-[#fecaca] focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>
                <button type="submit" class="px-4 py-2 bg-[#fef2f2] text-[#da2424] font-semibold text-sm rounded-xl hover:bg-[#fee2e2] transition">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.mahasiswa.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 text-sm rounded-xl hover:bg-gray-200 transition">
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
                        <th class="px-6 py-4">NIM & Mahasiswa</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4">Kontak / User</th>
                        <th class="px-6 py-4 text-center">Status Kuesioner</th>
                        <th class="px-6 py-4 text-center">Pendaftaran UKM</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca] text-sm text-[#1f2937]">
                    @forelse($mahasiswaList as $mhs)
                        @php
                            $isKuesionerFilled = $kuesionerStatusMap[$mhs->id] ?? false;
                            $regCount = $pendaftaranCountMap[$mhs->id] ?? 0;
                        @endphp
                        <tr class="hover:bg-[#fdf2f2]/60 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#1f2937]">{{ $mhs->nama }}</div>
                                <div class="text-xs font-mono text-[#6b7280] mt-0.5">NIM: {{ $mhs->nim }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $mhs->prodi }}</div>
                                <div class="text-xs text-[#6b7280]">{{ $mhs->fakultas ?? 'Fakultas Teknik' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-mono text-[#da2424]">{{ $mhs->email }}</div>
                                <div class="text-xs text-[#6b7280] mt-0.5">{{ $mhs->no_hp ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($isKuesionerFilled)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf8f2] text-[#258260]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#258260]"></span>
                                        Sudah Mengisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#fff5e5] text-[#b76e00]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#b76e00]"></span>
                                        Belum Mengisi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($regCount > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#fef2f2] text-[#da2424]">
                                        {{ $regCount }} UKM
                                    </span>
                                @else
                                    <span class="text-xs text-[#6b7280]">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- DETAIL --}}
                                    <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}"
                                       class="px-3 py-1.5 rounded-lg bg-[#fef2f2] text-[#da2424] text-xs font-semibold hover:bg-[#fee2e2] transition"
                                       title="Lihat Detail">
                                        Detail
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}"
                                       class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold hover:bg-amber-100 transition"
                                       title="Edit Data">
                                        Edit
                                    </a>

                                    {{-- HAPUS --}}
                                    <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa {{ $mhs->nama }}? Seluruh riwayat kuesioner dan pendaftaran UKM akan dihapus.')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 text-xs font-semibold hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#6b7280]">
                                <div class="text-lg font-medium text-[#1f2937]">Belum Ada Data Mahasiswa</div>
                                <p class="text-xs mt-1">Data mahasiswa akan muncul di sini setelah didaftarkan atau mahasiswa membuat akun.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- CARD FOOTER / PAGINATION --}}
        @if($mahasiswaList->hasPages())
            <div class="px-6 py-4 border-t border-[#fecaca] bg-[#fdf2f2]">
                {{ $mahasiswaList->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
