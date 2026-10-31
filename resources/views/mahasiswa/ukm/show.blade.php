@extends('layouts.app')

@section('title', 'Detail UKM - ' . $ukm->nama_ukm)
@section('page-title', 'Detail Unit Kegiatan Mahasiswa')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    {{-- HELPER FORMATTER --}}
    @php
        $fmtTopsisPercent = function ($val) {
            if ($val === null || $val === '') return '-';
            $percent = (float)$val * 100;
            $formatted = number_format($percent, 2, ',', '.');
            if (str_ends_with($formatted, ',00')) {
                $formatted = substr($formatted, 0, -3);
            }
            return $formatted . '%';
        };
    @endphp

    {{-- BREADCRUMB & BACK BUTTON --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('mahasiswa.ukm.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#fecaca] text-[#da2424] hover:bg-[#fef2f2] text-xs font-bold transition shadow-sm">
            ← Kembali ke Katalog UKM
        </a>

        <div class="text-xs text-[#6b7280]">
            Data UKM UMC • Mode: <span class="font-bold text-[#258260]">Read-Only</span>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-2xl px-5 py-4 shadow-sm text-sm font-medium">
            <span class="font-bold text-lg">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="flex items-center gap-3 bg-[#eef2ff] border border-[#c7d2fe] text-[#3730a3] rounded-2xl px-5 py-4 shadow-sm text-sm font-medium">
            <span class="font-bold text-lg">ℹ</span>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-2xl px-5 py-4 shadow-sm text-sm font-medium">
            <span class="font-bold text-lg">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- 1. HERO HEADER CARD --}}
    <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                {{-- LOGO CONTAINER --}}
                <div class="w-24 h-24 rounded-2xl bg-white p-3 border-2 border-white/20 shadow-md flex items-center justify-center flex-shrink-0 overflow-hidden">
                    @if(!empty($ukm->logo))
                        <img src="{{ asset('storage/' . $ukm->logo) }}"
                             alt="Logo {{ $ukm->nama_ukm }}"
                             class="w-full h-full object-contain">
                    @else
                        <div class="font-extrabold text-2xl text-[#8B0000]">
                            {{ strtoupper(substr($ukm->nama_ukm, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-[#fecaca] border border-white/10">
                            ✓ Status: {{ ucfirst($ukm->status ?? 'Aktif') }}
                        </span>

                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 text-amber-300 border border-white/10 flex items-center gap-1">
                            ★ {{ $reviewsCount > 0 ? number_format($avgRating, 1) . ' (' . $reviewsCount . ' Review)' : 'Belum ada review' }}
                        </span>

                        @if($topsisRanking)
                            <span class="px-3 py-1 rounded-full text-xs font-black bg-[#fee2e2] text-[#8B0000]">
                                Rank #{{ $topsisRanking }} TOPSIS ({{ $fmtTopsisPercent($topsisScore) }})
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                        {{ $ukm->nama_ukm }}
                    </h1>

                    <p class="text-xs md:text-sm text-[#fecaca] leading-relaxed max-w-2xl">
                        {{ $ukm->lokasi_sekretariat ? '📍 Sekretariat: ' . $ukm->lokasi_sekretariat : 'Sekretariat: Belum tersedia' }}
                    </p>
                </div>
            </div>

            {{-- AKSI PENDAFTARAN --}}
            <div class="flex-shrink-0 self-start md:self-center">
                @if($registrationStatus)
                    <div class="px-5 py-3 rounded-2xl bg-[#eaf8f2] text-[#258260] border border-[#c9eadc] text-xs font-bold text-center space-y-1">
                        <div>✓ Sudah Mendaftar</div>
                        <div class="text-[11px] font-normal opacity-90">Status: {{ ucfirst(str_replace('_', ' ', $registrationStatus)) }}</div>
                    </div>
                @else
                    <form action="{{ route('mahasiswa.pendaftaran.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ukm_id" value="{{ $ukm->id }}">
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin mendaftar ke {{ $ukm->nama_ukm }}?')"
                                class="px-6 py-3.5 rounded-2xl bg-white hover:bg-gray-100 text-[#da2424] text-xs font-extrabold transition shadow-md flex items-center justify-center gap-2">
                            Daftar {{ $ukm->nama_ukm }} Sekarang →
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

    {{-- GRID UTAMA INFORMASI DETIL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI (2 KOLOM): VISI MISI & PROFIL KRITERIA --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- 2. DESKRIPSI SINGKAT & RINCI --}}
            <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 space-y-4">
                <h2 class="text-base font-bold text-[#1f2937] border-b border-[#fecaca] pb-3">
                    Deskripsi & Profil UKM
                </h2>
                <p class="text-xs md:text-sm text-[#6b7280] leading-relaxed">
                    {{ $ukm->deskripsi ?: 'Belum tersedia penjelasan deskripsi rinci untuk UKM ini.' }}
                </p>
            </div>

            {{-- 3. VISI & MISI --}}
            <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 space-y-5">
                <h2 class="text-base font-bold text-[#1f2937] border-b border-[#fecaca] pb-3">
                    Visi & Misi UKM
                </h2>

                <div class="space-y-4 text-xs md:text-sm">
                    <div>
                        <div class="font-bold text-[#da2424] uppercase tracking-wider text-xs mb-1">Visi:</div>
                        <p class="text-[#1f2937] bg-[#fdf2f2] p-4 rounded-xl border border-[#fecaca] leading-relaxed">
                            {{ $ukm->visi ?: 'Belum tersedia data visi UKM.' }}
                        </p>
                    </div>

                    <div>
                        <div class="font-bold text-[#da2424] uppercase tracking-wider text-xs mb-1">Misi:</div>
                        <p class="text-[#1f2937] bg-[#fdf2f2] p-4 rounded-xl border border-[#fecaca] leading-relaxed whitespace-pre-line">
                            {{ $ukm->misi ?: 'Belum tersedia data misi UKM.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- 4. PROFIL KRITERIA PENILAIAN SPK --}}
            <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-[#fecaca] pb-3">
                    <h2 class="text-base font-bold text-[#1f2937]">
                        Profil Kriteria Penilaian SPK UKM
                    </h2>
                    <span class="text-xs text-[#6b7280]">5 Kriteria Acuan AHP & TOPSIS</span>
                </div>

                @if($criteriaProfiles->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($criteriaProfiles as $cp)
                            <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca] space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded bg-[#fee2e2] text-[#da2424] font-mono text-[10px] font-bold">
                                        {{ $cp->kode }}
                                    </span>
                                    <span class="text-[10px] text-[#6b7280] capitalize font-medium">
                                        {{ $cp->tipe }}
                                    </span>
                                </div>

                                <div class="text-xs font-bold text-[#1f2937] pt-1">
                                    {{ $cp->nama_kriteria }}
                                </div>

                                <div class="text-xs text-[#da2424] font-semibold">
                                    {{ $cp->nama_subkriteria ?: 'Belum ditentukan' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-[#fdf2f2] border border-dashed border-[#fecaca] text-xs text-[#6b7280] text-center">
                        Belum ada konfigurasi profil kriteria khusus untuk UKM ini.
                    </div>
                @endif
            </div>

        </div>

        {{-- KOLOM KANAN (1 KOLOM): INFORMASI PENGURUS & KEGIATAN --}}
        <div class="space-y-8">

            {{-- 5. INFO PENGURUS & KONTAK --}}
            <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 space-y-5">
                <h2 class="text-base font-bold text-[#1f2937] border-b border-[#fecaca] pb-3">
                    Pengurus & Kontak Resmi
                </h2>

                <div class="space-y-4 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-bold text-sm">
                            👨‍🏫
                        </div>
                        <div>
                            <div class="text-[#6b7280] text-[10px] uppercase font-bold">Pembina UKM</div>
                            <div class="font-bold text-[#1f2937] text-sm">{{ $ukm->pembina ?: 'Belum tersedia' }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-bold text-sm">
                            👑
                        </div>
                        <div>
                            <div class="text-[#6b7280] text-[10px] uppercase font-bold">Ketua UKM</div>
                            <div class="font-bold text-[#1f2937] text-sm">{{ $ukm->ketua ?: 'Belum tersedia' }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-bold text-sm">
                            📞
                        </div>
                        <div>
                            <div class="text-[#6b7280] text-[10px] uppercase font-bold">Kontak / WhatsApp</div>
                            <div class="font-bold text-[#da2424] text-sm">{{ $ukm->kontak ?: 'Belum tersedia' }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-bold text-sm">
                            ✉️
                        </div>
                        <div>
                            <div class="text-[#6b7280] text-[10px] uppercase font-bold">Email Resmi</div>
                            <div class="font-bold text-[#1f2937] text-sm">{{ $ukm->email ?: 'Belum tersedia' }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-bold text-sm">
                            📸
                        </div>
                        <div>
                            <div class="text-[#6b7280] text-[10px] uppercase font-bold">Instagram</div>
                            @if($ukm->instagram && $ukm->instagram_username)
                                <a href="{{ $ukm->instagram_url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="font-bold text-[#da2424] text-sm hover:underline inline-flex items-center gap-1">
                                    <span>{{ $ukm->instagram_username }}</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <div class="font-bold text-[#6b7280] text-sm">Belum tersedia</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. JADWAL LATIHAN & SEKRETARIAT --}}
            <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 space-y-5">
                <h2 class="text-base font-bold text-[#1f2937] border-b border-[#fecaca] pb-3">
                    Jadwal Latihan & Lokasi
                </h2>

                <div class="space-y-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] space-y-1">
                        <div class="text-[#6b7280] text-[10px] uppercase font-bold">🗓️ Hari / Waktu Latihan</div>
                        <div class="font-bold text-[#1f2937] text-sm">{{ $ukm->hari_latihan ?: 'Belum tersedia' }}</div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] space-y-1">
                        <div class="text-[#6b7280] text-[10px] uppercase font-bold">📍 Lokasi Sekretariat</div>
                        <div class="font-bold text-[#1f2937] text-sm">{{ $ukm->lokasi_sekretariat ?: 'Belum tersedia' }}</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- =========================================================
        7. SECTION REVIEW PENGGUNA UKM
    ========================================================== --}}
    <div class="bg-white rounded-3xl border border-[#fecaca] shadow-sm p-6 md:p-8 space-y-8" id="section-review">

        {{-- SECTION HEADER & RINGKASAN RATING --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-6 border-b border-[#fecaca]">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#fef2f2] text-[#da2424] border border-[#fecaca]">
                    ⭐ Informasi Tambahan
                </div>
                <h2 class="text-xl md:text-2xl font-black text-[#1f2937]">
                    Review Pengguna UKM
                </h2>
                <p class="text-xs md:text-sm text-[#6b7280]">
                    Pengalaman nyata dan ulasan dari mahasiswa yang pernah terdaftar pada UKM ini.
                </p>
            </div>

            {{-- STATISTIK KARTU RATING --}}
            <div class="flex items-center gap-5 bg-[#fdf2f2] border border-[#fecaca] px-6 py-4 rounded-2xl flex-shrink-0">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-black text-[#da2424]">
                        {{ $reviewsCount > 0 ? number_format($avgRating, 1) : '0.0' }}
                    </div>
                    <div class="text-[10px] uppercase font-bold text-[#6b7280] mt-0.5">
                        dari 5.0
                    </div>
                </div>

                <div class="border-l border-[#fecaca] pl-5 space-y-1">
                    <div class="flex items-center text-amber-400 text-lg leading-none">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($avgRating))
                                <span>★</span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>
                    <div class="text-xs font-bold text-[#1f2937]">
                        {{ $reviewsCount }} {{ $reviewsCount === 1 ? 'Ulasan' : 'Total Ulasan' }}
                    </div>
                    <div class="text-[11px] text-[#6b7280]">
                        Status Terverifikasi
                    </div>
                </div>
            </div>
        </div>

        {{-- =====================================================
            A. FORM / STATUS REVIEW MAHASISWA YANG LOGIN
        ====================================================== --}}
        <div>
            @if($myReview)
                {{-- MAHASISWA SUDAH PERNAH MEMBERIKAN REVIEW --}}
                <div x-data="{ editing: false, rating: {{ $myReview->rating }}, hoverRating: 0 }"
                     class="p-6 rounded-2xl bg-[#fffafa] border-2 border-[#fecaca] space-y-4">
                    
                    {{-- HEADER REVIEW SAYA --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-[#fecaca]/70">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-extrabold text-sm">
                                👤
                            </div>
                            <div>
                                <div class="font-bold text-sm text-[#1f2937]">Ulasan Anda</div>
                                <div class="text-[11px] text-[#6b7280]">
                                    Dikirim pada {{ $myReview->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>

                        {{-- STATUS BADGE --}}
                        <div class="flex items-center gap-2">
                            @if($myReview->status === 'approved')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#eaf8f2] text-[#258260] border border-[#c9eadc] flex items-center gap-1">
                                    <span>✓</span> Disetujui (Tampil ke Publik)
                                </span>
                            @elseif($myReview->status === 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#fffbeb] text-[#b45309] border border-[#fef3c7] flex items-center gap-1">
                                    <span>⏳</span> Menunggu Moderasi Admin
                                </span>
                            @elseif($myReview->status === 'rejected')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#fff0f1] text-[#d93848] border border-[#ffd6da] flex items-center gap-1">
                                    <span>✕</span> Ditolak oleh Admin
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- VIEW MODE --}}
                    <div x-show="!editing" class="space-y-4">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center text-amber-400 text-lg leading-none">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $myReview->rating ? 'text-amber-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                            <span class="text-xs font-bold text-[#1f2937]">({{ $myReview->rating }} dari 5 Bintang)</span>
                        </div>

                        <p class="text-xs md:text-sm text-[#374151] leading-relaxed bg-white p-4 rounded-xl border border-[#fecaca] whitespace-pre-line">
                            {{ $myReview->komentar }}
                        </p>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button"
                                    @click="editing = true"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-[#fecaca] text-[#da2424] hover:bg-[#fef2f2] text-xs font-bold transition shadow-sm">
                                ✏️ Edit Review
                            </button>

                            <form action="{{ route('mahasiswa.reviews.destroy', $myReview->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] hover:bg-[#ffe2e5] text-xs font-bold transition shadow-sm">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- EDIT MODE (INLINE) --}}
                    <div x-show="editing" x-cloak class="space-y-4 pt-2">
                        <form action="{{ route('mahasiswa.reviews.update', $myReview->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            {{-- RATING SELECTOR --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-[#1f2937]">
                                    Ubah Rating Bintang (1–5) <span class="text-[#da2424]">*</span>
                                </label>
                                <input type="hidden" name="rating" :value="rating">
                                <div class="flex items-center gap-2">
                                    <template x-for="star in 5" :key="star">
                                        <button type="button"
                                                @click="rating = star"
                                                @mouseenter="hoverRating = star"
                                                @mouseleave="hoverRating = 0"
                                                class="text-2xl transition-transform hover:scale-125 focus:outline-none"
                                                :class="(hoverRating || rating) >= star ? 'text-amber-400' : 'text-gray-300'">
                                            ★
                                        </button>
                                    </template>
                                    <span class="text-xs font-bold text-[#6b7280] ml-2" x-text="rating + ' Bintang'"></span>
                                </div>
                            </div>

                            {{-- KOMENTAR --}}
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-[#1f2937]">
                                    Komentar / Pengalaman <span class="text-[#da2424]">*</span>
                                </label>
                                <textarea name="komentar"
                                          rows="4"
                                          required
                                          maxlength="1000"
                                          placeholder="Tuliskan pengalaman Anda selama mengikuti kegiatan di UKM ini..."
                                          class="w-full px-4 py-3 text-xs md:text-sm rounded-xl border border-[#fecaca] focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition bg-white leading-relaxed">{{ $myReview->komentar }}</textarea>
                                <div class="text-[11px] text-[#6b7280]">
                                    * Catatan: Jika review diedit, ulasan akan dimoderasi kembali oleh Admin.
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button"
                                        @click="editing = false"
                                        class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-extrabold transition shadow-md">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            @elseif($canReview)
                {{-- MAHASISWA BERHAK MEMBERIKAN REVIEW (TERDAFTAR & BELUM PERNAH REVIEW) --}}
                <div x-data="{ rating: 5, hoverRating: 0 }"
                     class="p-6 md:p-7 rounded-2xl bg-[#fdf2f2] border border-[#fecaca] space-y-5">
                    
                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-[#1f2937] flex items-center gap-2">
                            <span>✍️</span> Tulis Review & Pengalaman Anda
                        </h3>
                        <p class="text-xs text-[#6b7280]">
                            Sebagai mahasiswa yang telah terdaftar di UKM ini, pengalaman Anda sangat berharga bagi rekan mahasiswa lainnya.
                        </p>
                    </div>

                    <form action="{{ route('mahasiswa.reviews.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="ukm_id" value="{{ $ukm->id }}">
                        <input type="hidden" name="rating" :value="rating">

                        {{-- STAR RATING INTERAKTIF --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-[#1f2937]">
                                Berikan Rating <span class="text-[#da2424]">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    <template x-for="star in 5" :key="star">
                                        <button type="button"
                                                @click="rating = star"
                                                @mouseenter="hoverRating = star"
                                                @mouseleave="hoverRating = 0"
                                                class="text-3xl transition-transform hover:scale-125 focus:outline-none"
                                                :class="(hoverRating || rating) >= star ? 'text-amber-400' : 'text-gray-300'">
                                            ★
                                        </button>
                                    </template>
                                </div>
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-white border border-[#fecaca] text-[#da2424]"
                                      x-text="rating + ' Bintang'">
                                </span>
                            </div>
                        </div>

                        {{-- TEXTAREA KOMENTAR --}}
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-[#1f2937]">
                                Komentar & Ulasan Anda <span class="text-[#da2424]">*</span>
                            </label>
                            <textarea name="komentar"
                                      rows="4"
                                      required
                                      maxlength="1000"
                                      placeholder="Ceritakan pengalaman Anda selama bergabung, suasana kegiatan, materi pelatihan, pengurus, atau manfaat yang diperoleh..."
                                      class="w-full px-4 py-3 text-xs md:text-sm rounded-xl border border-[#fecaca] bg-white focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition leading-relaxed"></textarea>
                            <div class="text-[11px] text-[#6b7280]">
                                Review akan melalui proses moderasi oleh Admin sebelum ditampilkan ke publik.
                            </div>
                        </div>

                        <button type="submit"
                                class="px-6 py-3 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-extrabold transition shadow-md flex items-center gap-2">
                            <span>Kirim Review Pengguna</span> →
                        </button>
                    </form>
                </div>

            @else
                {{-- MAHASISWA BELUM MEMENUHI SYARAT (BELUM TERDAFTAR) --}}
                <div class="p-5 rounded-2xl bg-[#fdf2f2] border border-dashed border-[#fecaca] flex items-start gap-4">
                    <div class="text-2xl flex-shrink-0">
                        ℹ️
                    </div>
                    <div class="space-y-1">
                        <div class="text-xs font-bold text-[#1f2937]">
                            Ingin Memberikan Review untuk {{ $ukm->nama_ukm }}?
                        </div>
                        <p class="text-xs text-[#6b7280] leading-relaxed">
                            Berdasarkan kebijakan sistem, ulasan dan penilaian rating hanya dapat diberikan oleh mahasiswa yang <strong>telah terdaftar</strong> pada UKM ini. Silakan mendaftar terlebih dahulu menggunakan tombol pendaftaran di bagian atas halaman.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- =====================================================
            B. DAFTAR REVIEW APPROVED DARI MAHASISWA LAIN
        ====================================================== --}}
        <div class="space-y-5 pt-2">
            <div class="flex items-center justify-between border-b border-[#fecaca] pb-3">
                <h3 class="text-base font-bold text-[#1f2937]">
                    Daftar Ulasan Mahasiswa ({{ $reviewsCount }})
                </h3>
                <span class="text-xs text-[#6b7280]">Urutan Terbaru</span>
            </div>

            @if($approvedReviews->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($approvedReviews as $rev)
                        <div class="p-5 rounded-2xl bg-[#fdf2f2] border border-[#fecaca] space-y-3 flex flex-col justify-between">
                            <div class="space-y-3">
                                {{-- HEADER USER & BINTANG --}}
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center font-extrabold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($rev->mahasiswa->nama ?? 'M', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-[#1f2937]">
                                                {{ $rev->mahasiswa->nama ?? 'Mahasiswa UMC' }}
                                            </div>
                                            <div class="text-[11px] text-[#6b7280]">
                                                {{ $rev->mahasiswa->prodi ?: 'Mahasiswa' }}
                                                @if(!empty($rev->mahasiswa->angkatan))
                                                    • Angkatan {{ $rev->mahasiswa->angkatan }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center text-amber-400 text-sm leading-none flex-shrink-0">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $rev->rating ? 'text-amber-400' : 'text-gray-300' }}">★</span>
                                        @endfor
                                    </div>
                                </div>

                                {{-- ISI KOMENTAR --}}
                                <p class="text-xs text-[#374151] leading-relaxed whitespace-pre-line">
                                    “{{ $rev->komentar }}”
                                </p>
                            </div>

                            {{-- TANGGAL REVIEW --}}
                            <div class="text-[10px] text-[#9ca3af] pt-2 border-t border-[#fecaca]/60">
                                📅 Ulasan pada {{ $rev->created_at->format('d M Y') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 rounded-2xl bg-[#fdf2f2] border border-dashed border-[#fecaca] text-center space-y-2">
                    <div class="text-3xl">🌟</div>
                    <div class="text-xs font-bold text-[#1f2937]">
                        Belum Ada Ulasan yang Disetujui
                    </div>
                    <p class="text-xs text-[#6b7280] max-w-md mx-auto">
                        Belum ada ulasan publik untuk UKM ini. Jadilah mahasiswa pertama yang membagikan pengalaman bermanfaat Anda!
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>

@endsection
