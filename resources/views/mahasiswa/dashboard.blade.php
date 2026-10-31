@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard Mahasiswa')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    {{-- HELPER FORMATTER --}}
    @php
        $fmtScore = function ($val) {
            if ($val === null || $val === '') return '-';
            return number_format((float)$val, 4, ',', '.');
        };

        $fmtTopsisPercent = function ($val) {
            if ($val === null || $val === '') return '-';
            $percent = (float)$val * 100;
            $formatted = number_format($percent, 2, ',', '.');
            if (str_ends_with($formatted, ',00')) {
                $formatted = substr($formatted, 0, -3);
            }
            return $formatted . '%';
        };

        $isProfileComplete = $profileStats['is_complete'] ?? false;
        $filledCount = $profileStats['filled_count'] ?? 0;
        $totalFields = $profileStats['total_fields'] ?? 8;
        $profilePercent = $profileStats['percentage'] ?? 0;
    @endphp

    {{-- 1. WELCOME BANNER --}}
    <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute right-32 -top-10 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-[#fecaca] border border-white/10">
                    🎓 Mahasiswa Universitas Muhammadiyah Cirebon
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Selamat Datang, {{ $mahasiswa->nama ?? auth()->user()->name }}
                </h1>
                <p class="text-xs md:text-sm text-[#fecaca] max-w-2xl leading-relaxed">
                    Sistem Pendukung Keputusan akan membantu Anda menemukan Unit Kegiatan Mahasiswa (UKM) yang paling sesuai berdasarkan kriteria <strong>Minat, Bakat, Jadwal, Target Prestasi, dan Anggaran Biaya</strong>.
                </p>
            </div>

            {{-- AKADEMIK CARD BADGE --}}
            <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-xs space-y-1.5 flex-shrink-0 min-w-[220px]">
                <div class="text-[#fecaca] font-medium flex items-center justify-between">
                    <span>Informasi Akademik</span>
                    <span class="text-[10px] px-2 py-0.5 rounded {{ $isProfileComplete ? 'bg-green-500/20 text-green-200' : 'bg-amber-500/20 text-amber-200' }}">
                        {{ $isProfileComplete ? 'Lengkap ✓' : 'Belum Lengkap' }}
                    </span>
                </div>
                <div class="font-bold text-sm text-white">NIM: {{ $mahasiswa->nim ?? 'Belum diisi' }}</div>
                <div class="text-white/90">{{ $mahasiswa->prodi ?? 'Program Studi' }}</div>
                <div class="text-white/75 text-[11px]">{{ $mahasiswa->fakultas ?? 'Fakultas' }} • {{ $mahasiswa->angkatan ?? 'Angkatan' }}</div>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI SUCCESS / ERROR --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-2xl px-5 py-4 text-sm font-medium">
            <span class="font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-2xl px-5 py-4 text-sm font-medium">
            <span class="font-bold">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- 2. CARD PROFIL BELUM LENGKAP BANNER (JIKA PROFIL BELUM LENGKAP) --}}
    @if(!$isProfileComplete)
        <div class="bg-gradient-to-br from-[#fff7ed] via-white to-[#fff1f2] rounded-3xl border-2 border-amber-400 p-6 shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-500 text-white shadow-sm">
                            ⚠️ WAJIB MELENGKAPI PROFIL
                        </span>
                        <span class="text-xs font-bold text-amber-800">
                            Kelengkapan Profil: {{ $filledCount }} dari {{ $totalFields }} Data ({{ $profilePercent }}%)
                        </span>
                    </div>

                    <h2 class="text-xl font-extrabold text-[#1f2937]">
                        Profil Mahasiswa Belum Lengkap
                    </h2>
                    <p class="text-xs md:text-sm text-[#6b7280] max-w-2xl leading-relaxed">
                        Sesuai dengan prosedur sistem SPK UKM, Anda wajib melengkapi 8 field data profil pribadi dan akademik sebelum diperbolehkan mengisi kuesioner preferensi dan melihat rekomendasi UKM.
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-extrabold shadow-md transition">
                        Lengkapi Profil Sekarang →
                    </a>
                </div>
            </div>

            {{-- DYNAMIC PROGRESS BAR PROFIL --}}
            <div class="pt-3 border-t border-amber-200/60">
                <div class="w-full bg-amber-100 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full bg-amber-500 transition-all duration-700 ease-out" style="width: {{ $profilePercent }}%;"></div>
                </div>
            </div>
        </div>
    @endif

    {{-- 3. CARD STATUS KUESIONER & PROGRESS --}}
    <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-bold text-[#1f2937]">
                        {{ $isProfileComplete ? 'Status Pengisian Kuesioner Preferensi' : '🔒 Kuesioner Terkunci' }}
                    </h2>

                    @if(!$isProfileComplete)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                            🔒 Terkunci (Profil Belum Lengkap)
                        </span>
                    @elseif($hasCompletedKuesioner)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#eaf8f2] text-[#258260]">
                            ✓ Selesai (100%)
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#fff5e5] text-[#b76e00]">
                            ⏳ Belum Mengisi (0%)
                        </span>
                    @endif
                </div>

                <p class="text-xs md:text-sm text-[#6b7280]">
                    @if(!$isProfileComplete)
                        Lengkapi profil Anda terlebih dahulu sebelum mengisi kuesioner preferensi.
                    @elseif($hasCompletedKuesioner)
                        Anda telah menyelesaikan kuesioner 5 kriteria. Jawaban dapat diperbarui kapan saja untuk memperbarui rekomendasi UKM.
                    @else
                        Silakan isi 5 kriteria preferensi Anda (Minat, Bakat, Jadwal, Prestasi, dan Biaya) untuk mendapatkan rekomendasi UKM dinamis.
                    @endif
                </p>
            </div>

            @if(!$isProfileComplete)
                <a href="{{ route('profile.edit') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm transition shadow-sm flex-shrink-0">
                    Lengkapi Profil →
                </a>
            @else
                <a href="{{ route('mahasiswa.kuesioner.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl {{ $hasCompletedKuesioner ? 'bg-[#fef2f2] text-[#da2424] hover:bg-[#fee2e2]' : 'bg-[#da2424] text-white hover:bg-[#b91c1c]' }} font-semibold text-sm transition shadow-sm flex-shrink-0">
                    {{ $hasCompletedKuesioner ? 'Ubah / Lihat Kembali Kuesioner' : 'Mulai Isi Kuesioner Sekarang →' }}
                </a>
            @endif
        </div>

        {{-- VISUAL PROGRESS BAR KUESIONER --}}
        <div class="mt-6 pt-4 border-t border-[#fecaca]">
            <div class="flex justify-between items-center text-xs font-bold mb-2">
                <span class="text-[#6b7280]">Progress Kelengkapan Kuesioner (5 Kriteria)</span>
                <span class="{{ $hasCompletedKuesioner ? 'text-[#258260]' : 'text-amber-600' }}">
                    {{ !$isProfileComplete ? 'Terkunci' : ($hasCompletedKuesioner ? '5 dari 5 Terisi (100%)' : '0 dari 5 Terisi (0%)') }}
                </span>
            </div>
            <div class="w-full bg-[#fecaca] rounded-full h-3 overflow-hidden">
                <div class="h-3 rounded-full transition-all duration-700 ease-out {{ $hasCompletedKuesioner && $isProfileComplete ? 'bg-[#258260] w-full' : 'bg-amber-500 w-0' }}"></div>
            </div>
        </div>
    </div>

    {{-- GRID UTAMA: REKOMENDASI & PENDAFTARAN UKM --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- 4. CARD REKOMENDASI (2 KOLOM) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 flex flex-col justify-between">

            <div>
                <div class="flex items-center justify-between border-b border-[#fecaca] pb-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-[#1f2937]">
                            Rekomendasi UKM Teratas
                        </h2>
                        <p class="text-xs text-[#6b7280] mt-0.5">
                            3 UKM paling sesuai berdasarkan perhitungan rekomendasi preferensi Anda
                        </p>
                    </div>

                    @if($hasCompletedKuesioner && $isProfileComplete)
                        <a href="{{ route('mahasiswa.rekomendasi.index') }}"
                           class="text-xs font-bold text-[#da2424] hover:text-[#8B0000] hover:underline flex items-center gap-1">
                            Lihat Semua 7 UKM →
                        </a>
                    @endif
                </div>

                @if($isProfileComplete && $hasCompletedKuesioner && $topRecommendations->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($topRecommendations as $rec)
                            <div class="p-5 rounded-2xl border {{ $rec->ranking === 1 ? 'border-[#da2424] bg-[#fef2f2]' : 'border-[#fecaca] bg-[#fdf2f2]' }} hover:border-[#da2424] transition flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        @if($rec->ranking === 1)
                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-[#da2424] text-white">
                                                Rank #1 TOP
                                            </span>
                                        @elseif($rec->ranking === 2)
                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-[#8B0000] text-white">
                                                Rank #2
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-black bg-[#a80000] text-white">
                                                Rank #3
                                            </span>
                                        @endif

                                        <span class="text-xs font-mono font-bold text-[#da2424]">
                                            {{ $fmtTopsisPercent($rec->nilai_preferensi) }}
                                        </span>
                                    </div>

                                    <h3 class="font-bold text-[#1f2937] text-base mb-1">
                                        {{ $rec->nama_ukm }}
                                    </h3>

                                    <p class="text-xs text-[#6b7280] line-clamp-3 leading-relaxed">
                                        {{ $rec->deskripsi ?? 'Unit Kegiatan Mahasiswa Universitas Muhammadiyah Cirebon.' }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-[#fecaca]">
                                    <a href="{{ route('mahasiswa.rekomendasi.index') }}"
                                       class="block w-full text-center py-2 rounded-xl bg-[#fee2e2] text-[#8B0000] hover:bg-[#da2424] hover:text-white text-xs font-bold transition">
                                        Daftar UKM Ini →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- INFORMATIVE EMPTY STATE REKOMENDASI --}}
                    <div class="p-8 rounded-2xl bg-[#fdf2f2] border border-dashed border-[#fecaca] text-center space-y-4">
                        <div class="w-14 h-14 rounded-full bg-[#fee2e2] text-[#da2424] flex items-center justify-center mx-auto text-2xl font-bold">
                            ✦
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[#1f2937]">Hasil Rekomendasi Belum Tersedia</h3>
                            <p class="text-xs text-[#6b7280] max-w-md mx-auto mt-1">
                                @if(!$isProfileComplete)
                                    Silakan lengkapi data profil Anda terlebih dahulu agar dapat mengisi kuesioner preferensi.
                                @else
                                    Silakan lengkapi kuesioner preferensi Anda terlebih dahulu agar sistem dapat menampilkan rekomendasi UKM terbaik.
                                @endif
                            </p>
                        </div>
                        @if(!$isProfileComplete)
                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 shadow-sm transition">
                                Lengkapi Profil Sekarang →
                            </a>
                        @else
                            <a href="{{ route('mahasiswa.kuesioner.index') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#da2424] text-white text-xs font-bold hover:bg-[#b91c1c] shadow-sm transition">
                                Isi Kuesioner Preferensi Sekarang
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            @if($isProfileComplete && $hasCompletedKuesioner && $topRecommendations->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-[#fecaca] flex items-center justify-between text-xs text-[#6b7280]">
                    <span>Skor Hasil Rekomendasi (0% s/d 100%)</span>
                    <a href="{{ route('mahasiswa.rekomendasi.index') }}" class="font-bold text-[#da2424] hover:underline">
                        Lihat Selengkapnya →
                    </a>
                </div>
            @endif
        </div>

        {{-- 5. CARD PENDAFTARAN UKM (1 KOLOM) --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 flex flex-col justify-between space-y-6">

            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-[#fecaca] pb-4">
                    <h2 class="text-lg font-bold text-[#1f2937]">
                        Pendaftaran Saya
                    </h2>
                    <span class="px-3 py-1 bg-[#fef2f2] text-[#da2424] text-xs font-mono font-bold rounded-full">
                        Total {{ $pendaftaranStats['total'] }} Pendaftaran
                    </span>
                </div>

                @if($pendaftaranStats['latest'])
                    @php $latestReg = $pendaftaranStats['latest']; @endphp

                    <div class="p-4 rounded-2xl bg-[#fdf2f2] border border-[#fecaca] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono font-bold text-[#da2424]">
                                {{ $latestReg->nomor_pendaftaran }}
                            </span>
                            <span class="text-[11px] text-[#6b7280]">
                                {{ \Carbon\Carbon::parse($latestReg->tanggal_daftar)->format('d M Y') }}
                            </span>
                        </div>

                        <div>
                            <div class="text-xs text-[#6b7280]">UKM Pilihan:</div>
                            <div class="text-base font-bold text-[#1f2937]">{{ $latestReg->nama_ukm }}</div>
                            @if($latestReg->topsis_ranking)
                                <div class="text-xs text-[#da2424] font-mono mt-0.5">
                                    Hasil SPK: Rank #{{ $latestReg->topsis_ranking }} ({{ $fmtTopsisPercent($latestReg->topsis_score) }})
                                </div>
                            @endif
                        </div>

                        <div class="pt-2 border-t border-[#fecaca] flex items-center justify-between">
                            <span class="text-xs text-[#6b7280]">Status Verifikasi:</span>
                            @if($latestReg->status === 'terverifikasi' || $latestReg->status === 'diterima')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#eaf8f2] text-[#258260]">
                                    ✓ Terverifikasi
                                </span>
                            @elseif($latestReg->status === 'perlu_perbaikan' || $latestReg->status === 'ditolak')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600">
                                    ✕ Perlu Perbaikan
                                </span>
                            @elseif($latestReg->status === 'selesai')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                    ✓ Selesai
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#fff5e5] text-[#b76e00]">
                                    ⏳ Menunggu Verifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- EMPTY STATE PENDAFTARAN --}}
                    <div class="p-6 rounded-2xl bg-[#fdf2f2] border border-dashed border-[#fecaca] text-center space-y-2">
                        <div class="text-xs text-[#6b7280]">Belum ada permohonan pendaftaran UKM.</div>
                        <p class="text-xs text-[#1f2937] font-medium">
                            Pilih UKM dari hasil rekomendasi Anda dan ajukan pendaftaran online.
                        </p>
                    </div>
                @endif
            </div>

            <div>
                @if($isProfileComplete)
                    <a href="{{ route('mahasiswa.pendaftaran.index') }}"
                       class="block w-full text-center py-3 rounded-xl bg-[#da2424] text-white text-xs font-bold hover:bg-[#b91c1c] shadow-sm transition">
                        Lihat Seluruh Pendaftaran Saya →
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}"
                       class="block w-full text-center py-3 rounded-xl bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 shadow-sm transition">
                        Lengkapi Profil Terlebih Dahulu →
                    </a>
                @endif
            </div>

        </div>

    </div>

    {{-- 6. QUICK ACTIONS GRID --}}
    <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6">
        <h2 class="text-base font-bold text-[#1f2937] mb-4">
            Akses Cepat Fitur Mahasiswa
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- 1. KUESIONER --}}
            <a href="{{ $isProfileComplete ? route('mahasiswa.kuesioner.index') : route('profile.edit') }}"
               class="group p-5 rounded-2xl border border-[#fecaca] bg-[#fdf2f2] hover:border-[#da2424] hover:bg-[#fef2f2] transition">
                <div class="w-10 h-10 rounded-xl {{ $isProfileComplete ? 'bg-[#fee2e2] text-[#da2424]' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center font-bold mb-3">
                    {{ $isProfileComplete ? '📝' : '🔒' }}
                </div>
                <h3 class="font-bold text-[#1f2937] text-sm">1. Kuesioner Preferensi</h3>
                <p class="text-xs text-[#6b7280] mt-1">{{ $isProfileComplete ? 'Isi 5 kriteria preferensi untuk SPK.' : 'Wajib lengkapi profil terlebih dahulu.' }}</p>
                <span class="block mt-3 text-xs font-bold {{ $isProfileComplete ? 'text-[#da2424]' : 'text-amber-700' }} group-hover:translate-x-1 transition-transform">
                    {{ $isProfileComplete ? 'Buka Kuesioner →' : 'Lengkapi Profil →' }}
                </span>
            </a>

            {{-- 2. HASIL REKOMENDASI --}}
            <a href="{{ $isProfileComplete ? route('mahasiswa.rekomendasi.index') : route('profile.edit') }}"
               class="group p-5 rounded-2xl border border-[#fecaca] bg-[#fdf2f2] hover:border-[#da2424] hover:bg-[#fef2f2] transition">
                <div class="w-10 h-10 rounded-xl {{ $isProfileComplete ? 'bg-[#fee2e2] text-[#da2424]' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center font-bold mb-3">
                    {{ $isProfileComplete ? '📊' : '🔒' }}
                </div>
                <h3 class="font-bold text-[#1f2937] text-sm">2. Hasil Rekomendasi</h3>
                <p class="text-xs text-[#6b7280] mt-1">{{ $isProfileComplete ? 'Hasil perankingan 7 UKM.' : 'Wajib lengkapi profil terlebih dahulu.' }}</p>
                <span class="block mt-3 text-xs font-bold {{ $isProfileComplete ? 'text-[#da2424]' : 'text-amber-700' }} group-hover:translate-x-1 transition-transform">
                    {{ $isProfileComplete ? 'Lihat Rekomendasi →' : 'Lengkapi Profil →' }}
                </span>
            </a>

            {{-- 3. PENDAFTARAN SAYA --}}
            <a href="{{ $isProfileComplete ? route('mahasiswa.pendaftaran.index') : route('profile.edit') }}"
               class="group p-5 rounded-2xl border border-[#fecaca] bg-[#fdf2f2] hover:border-[#da2424] hover:bg-[#fef2f2] transition">
                <div class="w-10 h-10 rounded-xl {{ $isProfileComplete ? 'bg-[#fee2e2] text-[#da2424]' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center font-bold mb-3">
                    {{ $isProfileComplete ? '📋' : '🔒' }}
                </div>
                <h3 class="font-bold text-[#1f2937] text-sm">3. Pendaftaran Saya</h3>
                <p class="text-xs text-[#6b7280] mt-1">{{ $isProfileComplete ? 'Cek status verifikasi & timeline.' : 'Wajib lengkapi profil terlebih dahulu.' }}</p>
                <span class="block mt-3 text-xs font-bold {{ $isProfileComplete ? 'text-[#da2424]' : 'text-amber-700' }} group-hover:translate-x-1 transition-transform">
                    {{ $isProfileComplete ? 'Cek Pendaftaran →' : 'Lengkapi Profil →' }}
                </span>
            </a>

            {{-- 4. PROFIL SAYA --}}
            <a href="{{ route('profile.edit') }}"
               class="group p-5 rounded-2xl border border-[#fecaca] bg-[#fdf2f2] hover:border-[#da2424] hover:bg-[#fef2f2] transition">
                <div class="w-10 h-10 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-bold mb-3">
                    👤
                </div>
                <h3 class="font-bold text-[#1f2937] text-sm">4. Profil Saya</h3>
                <p class="text-xs text-[#6b7280] mt-1">Kelola data profil & password.</p>
                <span class="block mt-3 text-xs font-bold text-[#da2424] group-hover:translate-x-1 transition-transform">
                    Edit Profil →
                </span>
            </a>

        </div>
    </div>

    {{-- 7. GUIDANCE STEP-BY-STEP --}}
    <div class="bg-gradient-to-br from-[#fee2e2] to-[#fdf2f2] rounded-2xl border border-[#f8c4c4] p-6">
        <h3 class="text-sm font-bold text-[#1f2937] mb-4">
            Alur Pemilihan UKM Mahasiswa Universitas Muhammadiyah Cirebon:
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
            <div class="flex items-start gap-3 p-3 rounded-xl bg-white/70 border border-[#fecaca]">
                <span class="w-6 h-6 rounded-full {{ $isProfileComplete ? 'bg-[#258260]' : 'bg-amber-500' }} text-white font-bold flex items-center justify-center flex-shrink-0">1</span>
                <div>
                    <div class="font-bold text-[#1f2937]">Lengkapi 8 Profil</div>
                    <div class="text-[#6b7280] mt-0.5">Isi nama, NIM, email, HP, prodi, fakultas, angkatan, & JK.</div>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 rounded-xl bg-white/70 border border-[#fecaca]">
                <span class="w-6 h-6 rounded-full bg-[#da2424] text-white font-bold flex items-center justify-center flex-shrink-0">2</span>
                <div>
                    <div class="font-bold text-[#1f2937]">Isi Kuesioner Preferensi</div>
                    <div class="text-[#6b7280] mt-0.5">Jawab 5 pertanyaan kriteria sesuai minat, bakat, jadwal, target prestasi, dan biaya.</div>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 rounded-xl bg-white/70 border border-[#fecaca]">
                <span class="w-6 h-6 rounded-full bg-[#8B0000] text-white font-bold flex items-center justify-center flex-shrink-0">3</span>
                <div>
                    <div class="font-bold text-[#1f2937]">Dapatkan Rekomendasi UKM</div>
                    <div class="text-[#6b7280] mt-0.5">Sistem menghitung kecocokan preferensi Anda terhadap 7 UKM secara akademis.</div>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 rounded-xl bg-white/70 border border-[#fecaca]">
                <span class="w-6 h-6 rounded-full bg-[#a80000] text-white font-bold flex items-center justify-center flex-shrink-0">4</span>
                <div>
                    <div class="font-bold text-[#1f2937]">Daftar UKM Pilihan</div>
                    <div class="text-[#6b7280] mt-0.5">Ajukan pendaftaran online ke UKM yang Anda pilih dan pantau status verifikasinya.</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection