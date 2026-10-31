@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#1f2937]">
            Selamat Datang, {{ auth()->user()->name }}
        </h1>
        <p class="mt-2 text-[#4b5563]">
            Kelola sistem pendukung keputusan pemilihan UKM Universitas Muhammadiyah Cirebon dari halaman ini.
        </p>
    </div>

    {{-- SUMMARY CARDS (4 KARTU RINGKASAN HORIZONTAL) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- 1. TOTAL UKM --}}
        <div class="bg-white rounded-xl border border-[#f8c4c4] p-5 shadow-sm hover:shadow-md hover:border-[#8B0000]/40 transition duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#fdf2f2] text-[#8B0000] flex items-center justify-center flex-shrink-0 border border-[#f8c4c4]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a2 2 0 012-2h2a2 2 0 012 2v5m-6 0h6M9 7h1m-1 4h1m4-4h1m-1 4h1" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">TOTAL UKM</span>
                    <h3 class="text-2xl font-extrabold text-[#1f2937] mt-0.5 leading-none">
                        {{ $totalUkm ?? \App\Models\Ukm::count() }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- 2. TOTAL KRITERIA --}}
        <div class="bg-white rounded-xl border border-[#f8c4c4] p-5 shadow-sm hover:shadow-md hover:border-[#8B0000]/40 transition duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#fdf2f2] text-[#8B0000] flex items-center justify-center flex-shrink-0 border border-[#f8c4c4]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">TOTAL KRITERIA</span>
                    <h3 class="text-2xl font-extrabold text-[#1f2937] mt-0.5 leading-none">
                        {{ $totalKriteria ?? \App\Models\Kriteria::count() }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- 3. TOTAL MAHASISWA --}}
        <div class="bg-white rounded-xl border border-[#f8c4c4] p-5 shadow-sm hover:shadow-md hover:border-[#8B0000]/40 transition duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#fdf2f2] text-[#8B0000] flex items-center justify-center flex-shrink-0 border border-[#f8c4c4]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14c6.627 0 12 2.239 12 5v2H0v-2c0-2.761 5.373-5 12-5zM12 11a4.5 4.5 0 100-9 4.5 4.5 0 000 9z" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">TOTAL MAHASISWA</span>
                    <h3 class="text-2xl font-extrabold text-[#1f2937] mt-0.5 leading-none">
                        {{ $totalMahasiswa ?? \App\Models\User::where('role', 'mahasiswa')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- 4. TOTAL PENDAFTARAN --}}
        <div class="bg-white rounded-xl border border-[#f8c4c4] p-5 shadow-sm hover:shadow-md hover:border-[#8B0000]/40 transition duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#fdf2f2] text-[#8B0000] flex items-center justify-center flex-shrink-0 border border-[#f8c4c4]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">TOTAL PENDAFTARAN</span>
                    <h3 class="text-2xl font-extrabold text-[#1f2937] mt-0.5 leading-none">
                        {{ $totalPendaftaran ?? \Illuminate\Support\Facades\DB::table('pendaftaran_ukm')->count() }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    {{-- CONTENT GRID --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- QUICK ACTION --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-[#fecaca] shadow-sm">

            <div class="p-6 border-b border-[#fecaca]">
                <h2 class="text-lg font-bold text-[#1f2937]">
                    Akses Cepat Pengelolaan Sistem
                </h2>
                <p class="text-sm text-[#6b7280] mt-1">
                    Kelola data kriteria, bobot AHP, TOPSIS, data mahasiswa, dan verifikasi pendaftaran.
                </p>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- DATA UKM --}}
                <a href="{{ route('ukm.index') }}"
                   class="group p-5 rounded-2xl border border-[#fecaca] hover:border-[#8B0000]/40 hover:bg-[#fdf2f2] transition">
                    <div class="w-11 h-11 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#8B0000] mb-4 font-bold">
                        ▣
                    </div>
                    <h3 class="font-semibold text-[#1f2937]">Data UKM</h3>
                    <p class="text-xs text-[#6b7280] mt-1">Kelola data 7 Unit Kegiatan Mahasiswa.</p>
                    <span class="block mt-4 text-xs font-bold text-[#8B0000] group-hover:translate-x-1 transition-transform">
                        Kelola Data →
                    </span>
                </a>

                {{-- DATA KRITERIA --}}
                <a href="{{ route('kriteria.index') }}"
                   class="group p-5 rounded-2xl border border-[#fecaca] hover:border-[#8B0000]/40 hover:bg-[#fdf2f2] transition">
                    <div class="w-11 h-11 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#8B0000] mb-4 font-bold">
                        ☷
                    </div>
                    <h3 class="font-semibold text-[#1f2937]">Kriteria & Sub</h3>
                    <p class="text-xs text-[#6b7280] mt-1">Kelola 5 Kriteria & 29 Sub-Kriteria.</p>
                    <span class="block mt-4 text-xs font-bold text-[#8B0000] group-hover:translate-x-1 transition-transform">
                        Kelola Kriteria →
                    </span>
                </a>

                {{-- PERHITUNGAN AHP --}}
                <a href="{{ route('ahp.index') }}"
                   class="group p-5 rounded-2xl border border-[#fecaca] hover:border-[#8B0000]/40 hover:bg-[#fdf2f2] transition">
                    <div class="w-11 h-11 rounded-xl bg-[#e8f8f1] flex items-center justify-center text-[#24926b] mb-4 font-bold">
                        ◈
                    </div>
                    <h3 class="font-semibold text-[#1f2937]">Perhitungan AHP</h3>
                    <p class="text-xs text-[#6b7280] mt-1">Input perbandingan & bobot kriteria.</p>
                    <span class="block mt-4 text-xs font-bold text-[#24926b] group-hover:translate-x-1 transition-transform">
                        Hitung AHP →
                    </span>
                </a>

                {{-- TOPSIS ADMIN --}}
                <a href="{{ route('topsis.index') }}"
                   class="group p-5 rounded-2xl border border-[#fecaca] hover:border-[#8B0000]/40 hover:bg-[#fdf2f2] transition">
                    <div class="w-11 h-11 rounded-xl bg-[#fff1f2] flex items-center justify-center text-[#d96b76] mb-4 font-bold">
                        ▥
                    </div>
                    <h3 class="font-semibold text-[#1f2937]">Hasil TOPSIS Admin</h3>
                    <p class="text-xs text-[#6b7280] mt-1">Perangkingan UKM berbasis matriks profil.</p>
                    <span class="block mt-4 text-xs font-bold text-[#d96b76] group-hover:translate-x-1 transition-transform">
                        Lihat TOPSIS →
                    </span>
                </a>

                {{-- DATA MAHASISWA --}}
                <a href="{{ route('admin.mahasiswa.index') }}"
                   class="group p-5 rounded-2xl border border-[#fecaca] hover:border-[#8B0000]/40 hover:bg-[#fdf2f2] transition">
                    <div class="w-11 h-11 rounded-xl bg-[#e9f8f2] flex items-center justify-center text-[#3b9d75] mb-4 font-bold">
                        ♙
                    </div>
                    <h3 class="font-semibold text-[#1f2937]">Data Mahasiswa</h3>
                    <p class="text-xs text-[#6b7280] mt-1">Kelola data mahasiswa & akun login.</p>
                    <span class="block mt-4 text-xs font-bold text-[#3b9d75] group-hover:translate-x-1 transition-transform">
                        Kelola Mahasiswa →
                    </span>
                </a>

                {{-- PENDAFTARAN UKM --}}
                <a href="{{ route('admin.pendaftaran.index') }}"
                   class="group p-5 rounded-2xl border border-[#fecaca] hover:border-[#8B0000]/40 hover:bg-[#fdf2f2] transition">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 mb-4 font-bold">
                        📋
                    </div>
                    <h3 class="font-semibold text-[#1f2937]">Pendaftaran UKM</h3>
                    <p class="text-xs text-[#6b7280] mt-1">Verifikasi permohonan daftar UKM.</p>
                    <span class="block mt-4 text-xs font-bold text-amber-600 group-hover:translate-x-1 transition-transform">
                        Verifikasi Pendaftaran →
                    </span>
                </a>

            </div>

        </div>

        {{-- INFORMASI METODE SPK --}}
        <div class="bg-gradient-to-br from-[#fee2e2] to-[#fdf2f2] rounded-2xl border border-[#f8c4c4] p-6 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-[#8B0000] text-xl shadow-sm font-bold">
                    ✦
                </div>

                <h2 class="text-lg font-bold text-[#1f2937] mt-5">
                    SPK Pemilihan UKM UMC
                </h2>

                <p class="text-xs text-[#4b5563] leading-5 mt-3">
                    Sistem ini merekomendasikan UKM kepada mahasiswa secara presisi menggunakan metode penggabungan AHP & TOPSIS.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#8B0000] text-white flex items-center justify-center text-xs font-bold">1</div>
                        <span class="text-xs text-[#374151] font-medium">Bobot Kriteria AHP Global (Admin)</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#a80000] text-white flex items-center justify-center text-xs font-bold">2</div>
                        <span class="text-xs text-[#374151] font-medium">Matching & Gap Analysis Kuesioner</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#770000] text-white flex items-center justify-center text-xs font-bold">3</div>
                        <span class="text-xs text-[#374151] font-medium">Perangkingan TOPSIS Dinamis</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-[#f8c4c4] text-[11px] text-[#6b7280] font-mono">
                Status Penelitian Skripsi: Ready for Demo
            </div>
        </div>

    </div>

</div>

@endsection