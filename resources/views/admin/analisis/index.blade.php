@extends('layouts.app')

@section('title', 'Analisis Data Deskriptif - Admin SPK UKM')
@section('page-title', 'Analisis Data')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    {{-- =====================================================
        HEADER & FILTER
    ====================================================== --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#1f2937] tracking-tight">
                Analisis Data Deskriptif
            </h1>
            <p class="mt-1 text-sm text-[#4b5563]">
                Visualisasi statistik performa rekomendasi AHP-TOPSIS, kuesioner minat, pendaftaran, dan review UKM Universitas Muhammadiyah Cirebon.
            </p>
        </div>

        {{-- FILTER BAR --}}
        <form method="GET" action="{{ route('admin.analisis.index') }}" class="flex flex-wrap items-center gap-3 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm">
            {{-- Filter Angkatan --}}
            <div class="flex items-center gap-2">
                <label for="angkatan" class="text-xs font-semibold text-slate-500 uppercase">Angkatan:</label>
                <select name="angkatan" id="angkatan" class="text-xs py-1.5 px-2.5 border border-slate-300 rounded-lg focus:ring-1 focus:ring-[#8B0000] focus:border-[#8B0000] bg-white text-slate-700">
                    <option value="">Semua Angkatan</option>
                    @foreach($listAngkatan as $a)
                        <option value="{{ $a }}" {{ $selectedAngkatan == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tahun --}}
            <div class="flex items-center gap-2">
                <label for="tahun" class="text-xs font-semibold text-slate-500 uppercase">Tahun:</label>
                <select name="tahun" id="tahun" class="text-xs py-1.5 px-2.5 border border-slate-300 rounded-lg focus:ring-1 focus:ring-[#8B0000] focus:border-[#8B0000] bg-white text-slate-700">
                    <option value="">Semua Tahun</option>
                    @foreach($listTahun as $t)
                        <option value="{{ $t }}" {{ $selectedTahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-3 py-1.5 bg-[#8B0000] hover:bg-[#6b0000] text-white text-xs font-semibold rounded-lg transition-colors duration-150 shadow-sm">
                Filter
            </button>

            @if($selectedAngkatan || $selectedTahun)
                <a href="{{ route('admin.analisis.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-lg transition-colors duration-150">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- =====================================================
        SUMMARY STATISTIC CARDS (4 KARTU)
    ====================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- 1. TOTAL MAHASISWA --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Mahasiswa</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-3xl font-extrabold text-slate-900 leading-none">
                    {{ number_format($totalMahasiswa) }}
                </h3>
                <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                    <span>Laki-laki: <strong>{{ $genderBreakdown['L'] }}</strong></span>
                    <span>•</span>
                    <span>Perempuan: <strong>{{ $genderBreakdown['P'] }}</strong></span>
                </div>
            </div>
        </div>

        {{-- 2. TOTAL PENGISIAN KUESIONER --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kuesioner Selesai</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-3xl font-extrabold text-slate-900 leading-none">
                    {{ number_format($totalKuesionerSelesai) }}
                </h3>
                <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                    <span>Oleh: <strong>{{ $mahasiswaIsiKuesioner }}</strong> mahasiswa</span>
                    <span>•</span>
                    <span>Run TOPSIS: <strong>{{ $totalRekomendasiTopsis }}</strong></span>
                </div>
            </div>
        </div>

        {{-- 3. TOTAL PENDAFTARAN --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pendaftaran</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-3xl font-extrabold text-slate-900 leading-none">
                    {{ number_format($totalPendaftaran) }}
                </h3>
                <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                    <span>Terverifikasi: <strong>{{ $statusCountsRaw['terverifikasi'] ?? 0 }}</strong></span>
                    <span>•</span>
                    <span>Menunggu: <strong>{{ $statusCountsRaw['menunggu_verifikasi'] ?? 0 }}</strong></span>
                </div>
            </div>
        </div>

        {{-- 4. TOTAL REVIEW UKM --}}
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Review</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-3xl font-extrabold text-slate-900 leading-none">
                    {{ number_format($totalReview) }}
                </h3>
                <div class="flex items-center gap-3 mt-2 text-xs text-slate-500">
                    <span>Approved: <strong>{{ $totalReviewApproved }}</strong></span>
                    <span>•</span>
                    <span>Pending: <strong>{{ $totalReviewPending }}</strong></span>
                </div>
            </div>
        </div>

    </div>

    {{-- =====================================================
        HIGHLIGHT CARDS (PERINGKAT & HIGHLIGHT UTAMA)
    ====================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-gradient-to-br from-rose-50 to-white rounded-xl border border-rose-200/80 p-4 shadow-sm">
            <div class="text-[11px] font-bold text-rose-700 uppercase tracking-wider flex items-center gap-1.5">
                <span>🏆 Rekomendasi #1 Terbanyak</span>
            </div>
            <div class="mt-2">
                <div class="font-bold text-slate-900 text-base truncate">{{ $topRekomendasi['nama'] ?? '-' }}</div>
                <div class="text-xs text-rose-600 font-semibold mt-0.5">
                    {{ $topRekomendasi['rekomendasi_rank1'] ?? 0 }} kali rekomendasi Rank 1
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-200/80 p-4 shadow-sm">
            <div class="text-[11px] font-bold text-blue-700 uppercase tracking-wider flex items-center gap-1.5">
                <span>🎯 Pendaftar Terbanyak</span>
            </div>
            <div class="mt-2">
                <div class="font-bold text-slate-900 text-base truncate">{{ $topPendaftar['nama'] ?? '-' }}</div>
                <div class="text-xs text-blue-600 font-semibold mt-0.5">
                    {{ $topPendaftar['pendaftar'] ?? 0 }} mahasiswa mendaftar
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-white rounded-xl border border-amber-200/80 p-4 shadow-sm">
            <div class="text-[11px] font-bold text-amber-700 uppercase tracking-wider flex items-center gap-1.5">
                <span>⭐ Rating Tertinggi</span>
            </div>
            <div class="mt-2">
                <div class="font-bold text-slate-900 text-base truncate">{{ $topRating['nama'] ?? '-' }}</div>
                <div class="text-xs text-amber-600 font-semibold mt-0.5">
                    Skor {{ number_format($topRating['avg_rating'] ?? 0, 1) }} / 5.0
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl border border-emerald-200/80 p-4 shadow-sm">
            <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider flex items-center gap-1.5">
                <span>💬 Ulasan Terbanyak</span>
            </div>
            <div class="mt-2">
                <div class="font-bold text-slate-900 text-base truncate">{{ $topReview['nama'] ?? '-' }}</div>
                <div class="text-xs text-emerald-600 font-semibold mt-0.5">
                    {{ $topReview['total_review'] ?? 0 }} ulasan terverifikasi
                </div>
            </div>
        </div>

    </div>

    {{-- =====================================================
        GRID 4 GRAFIK UTAMA (CHART.JS)
    ====================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- GRAFIK 1: REKOMENDASI UKM --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#8B0000]"></span>
                        Distribusi Rekomendasi Peringkat 1 TOPSIS
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Frekuensi setiap UKM terpilih sebagai rekomendasi prioritas utama (Rank 1).
                    </p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-red-50 text-[#8B0000] rounded-lg border border-red-100">
                    TOPSIS Rank 1
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="chartRekomendasi"></canvas>
            </div>
        </div>

        {{-- GRAFIK 2: PENDAFTARAN UKM --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        Jumlah Pendaftaran Setiap UKM
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Jumlah formulir pendaftaran mahasiswa yang masuk pada masing-masing UKM.
                    </p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg border border-blue-100">
                    Pendaftar
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="chartPendaftaran"></canvas>
            </div>
        </div>

        {{-- GRAFIK 3: RATING UKM --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        Rata-Rata Rating Kepuasan UKM
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Skor kepuasan ulasan anggota terverifikasi (skala 1.0 - 5.0).
                    </p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg border border-amber-100">
                    Skor Rating
                </span>
            </div>
            <div class="relative h-72">
                <canvas id="chartRating"></canvas>
            </div>
        </div>

        {{-- GRAFIK 4: STATUS PENDAFTARAN --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        Distribusi Status Pendaftaran
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Proporsi status verifikasi pendaftaran mahasiswa yang tercatat.
                    </p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">
                    Proporsi Status
                </span>
            </div>
            <div class="relative h-72 flex items-center justify-center">
                <canvas id="chartStatusPendaftaran"></canvas>
            </div>
        </div>

    </div>

    {{-- =====================================================
        TABEL RINGKASAN DATA KOMPARATIF UKM
    ====================================================== --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Tabel Ringkasan Performa Komparatif UKM
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Data agregat komparasi antara rekomendasi TOPSIS, animo pendaftaran, dan ulasan anggota.
                </p>
            </div>
            <span class="text-xs font-medium px-3 py-1 bg-slate-100 text-slate-600 rounded-lg self-start sm:self-auto">
                Total UKM: {{ count($ukmSummary) }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Nama Unit Kegiatan Mahasiswa (UKM)</th>
                        <th class="py-3.5 px-4 text-center">Rekomendasi Rank 1</th>
                        <th class="py-3.5 px-4 text-center">Avg Preferensi</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Pendaftar</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Review</th>
                        <th class="py-3.5 px-4 text-center">Rata-Rata Rating</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ukmSummary as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                            <td class="py-3.5 px-4 text-center font-medium text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $item['nama'] }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    {{ $item['rekomendasi_rank1'] }} kali
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-600">
                                {{ $item['avg_preferensi'] > 0 ? number_format($item['avg_preferensi'], 4) : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $item['pendaftar'] }} orang
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $item['total_review'] }} ulasan
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1 font-bold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full text-xs border border-amber-200">
                                    <svg class="w-3.5 h-3.5 fill-current text-amber-500" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span>{{ number_format($item['avg_rating'], 1) }} / 5.0</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item['status'] === 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        {{ ucfirst($item['status']) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 text-sm">
                                Tidak ada data UKM yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 font-bold text-xs text-slate-700 border-t border-slate-200">
                    <tr>
                        <td colspan="2" class="py-3.5 px-4 text-right uppercase">Total Seluruh UKM:</td>
                        <td class="py-3.5 px-4 text-center text-rose-700">
                            {{ array_sum(array_column($ukmSummary, 'rekomendasi_rank1')) }} kali
                        </td>
                        <td class="py-3.5 px-4 text-center text-slate-400">-</td>
                        <td class="py-3.5 px-4 text-center text-blue-700">
                            {{ array_sum(array_column($ukmSummary, 'pendaftar')) }} orang
                        </td>
                        <td class="py-3.5 px-4 text-center text-emerald-700">
                            {{ array_sum(array_column($ukmSummary, 'total_review')) }} ulasan
                        </td>
                        <td class="py-3.5 px-4 text-center text-amber-700">
                            @php
                                $validRatings = array_filter(array_column($ukmSummary, 'avg_rating'));
                                $overallAvg = count($validRatings) > 0 ? array_sum($validRatings) / count($validRatings) : 0;
                            @endphp
                            Avg: {{ number_format($overallAvg, 2) }}
                        </td>
                        <td class="py-3.5 px-4 text-center text-slate-400">-</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

{{-- =====================================================
    CHART.JS SCRIPTS
====================================================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($chartLabels);
    const brandMaroon = '#8B0000';
    const brandColors = [
        '#8B0000', '#2563eb', '#10b981', '#f59e0b',
        '#8b5cf6', '#06b6d4', '#ec4899', '#f97316'
    ];

    // Chart.js Global Default
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. CHART REKOMENDASI RANK 1
    const ctxRek = document.getElementById('chartRekomendasi');
    if (ctxRek) {
        new Chart(ctxRek, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Terpilih Peringkat 1',
                    data: @json($chartRekomendasiData),
                    backgroundColor: 'rgba(139, 0, 0, 0.85)',
                    borderColor: '#8B0000',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    hoverBackgroundColor: '#8B0000',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Direkomendasikan: ${context.raw} kali (Rank 1)`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 5 },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. CHART PENDAFTARAN UKM
    const ctxPend = document.getElementById('chartPendaftaran');
    if (ctxPend) {
        new Chart(ctxPend, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: @json($chartPendaftaranData),
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderColor: '#2563eb',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    hoverBackgroundColor: '#1d4ed8',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Pendaftar: ${context.raw} mahasiswa`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 2 },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 3. CHART RATING UKM
    const ctxRating = document.getElementById('chartRating');
    if (ctxRating) {
        new Chart(ctxRating, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-Rata Rating',
                    data: @json($chartRatingData),
                    backgroundColor: 'rgba(245, 158, 11, 0.85)',
                    borderColor: '#f59e0b',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    hoverBackgroundColor: '#d97706',
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` Rata-Rata Rating: ${context.raw} / 5.0 ⭐`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        min: 0,
                        max: 5,
                        ticks: { stepSize: 1 },
                        grid: { color: '#f1f5f9' }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 4. CHART STATUS PENDAFTARAN (DOUGHNUT)
    const ctxStatus = document.getElementById('chartStatusPendaftaran');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: @json($chartStatusLabels),
                datasets: [{
                    data: @json($chartStatusData),
                    backgroundColor: [
                        '#10b981', // Terverifikasi
                        '#f59e0b', // Menunggu
                        '#ef4444', // Perlu perbaikan
                        '#64748b', // Ditolak
                        '#6366f1'  // Selesai
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 14,
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.raw} berkas`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

@endsection
