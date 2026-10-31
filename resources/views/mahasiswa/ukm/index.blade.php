@extends('layouts.app')

@section('title', 'Data UKM UMC')
@section('page-title', 'Data Unit Kegiatan Mahasiswa')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">

    {{-- 1. HEADER BANNER --}}
    <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-[#fecaca] border border-white/10">
                    🏫 Katalog Unit Kegiatan Mahasiswa
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Data UKM Universitas Muhammadiyah Cirebon
                </h1>
                <p class="text-xs md:text-sm text-[#fecaca] max-w-2xl leading-relaxed">
                    Jelajahi seluruh Unit Kegiatan Mahasiswa (UKM) resmi di UMC. Dapatkan informasi lengkap mengenai bidang kegiatan, visi misi, profil, jadwal latihan, dan kontak pengurus.
                </p>
            </div>

            <div class="flex-shrink-0">
                <a href="{{ route('mahasiswa.rekomendasi.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-white text-[#da2424] hover:bg-gray-100 text-xs font-extrabold transition shadow-sm">
                    📊 Lihat Rekomendasi TOPSIS Saya
                </a>
            </div>
        </div>
    </div>

    {{-- 2. SEARCH BAR & FILTER --}}
    <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-5">
        <form action="{{ route('mahasiswa.ukm.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#6b7280]">
                    🔎
                </div>
                <input type="text"
                       name="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Cari berdasarkan nama UKM atau deskripsi kegiatan..."
                       class="w-full pl-11 pr-4 py-3 rounded-xl border border-[#fecaca] text-xs font-medium text-[#1f2937] focus:border-[#da2424] focus:ring-2 focus:ring-[#da2424]/20 outline-none transition">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold transition shadow-sm">
                    Cari UKM
                </button>

                @if(!empty($search))
                    <a href="{{ route('mahasiswa.ukm.index') }}"
                       class="w-full sm:w-auto px-4 py-3 rounded-xl bg-[#fef2f2] text-[#da2424] hover:bg-[#fee2e2] text-xs font-bold transition text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 3. GRID CATALOG UKM --}}
    @if($ukmList->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ukmList as $u)
                @php $regStatus = $registeredUkms[$u->id] ?? null; @endphp
                <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm hover:border-[#da2424] transition flex flex-col justify-between overflow-hidden group">
                    
                    <div class="p-6 space-y-4">
                        {{-- LOGO & HEADER --}}
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-[#fdf2f2] border border-[#fecaca] flex items-center justify-center p-2 flex-shrink-0 overflow-hidden shadow-sm group-hover:scale-105 transition-transform">
                                @if(!empty($u->logo))
                                    <img src="{{ asset('storage/' . $u->logo) }}"
                                         alt="{{ $u->nama_ukm }}"
                                         class="w-full h-full object-contain">
                                @else
                                    <span class="text-2xl font-black text-[#da2424]">🏛️</span>
                                @endif
                            </div>

                            <div class="space-y-1 flex-1 min-w-0">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#eaf8f2] text-[#258260]">
                                    ✓ {{ ucfirst($u->status ?? 'Aktif') }}
                                </span>
                                <h3 class="font-extrabold text-[#1f2937] text-base truncate group-hover:text-[#da2424] transition-colors">
                                    {{ $u->nama_ukm }}
                                </h3>
                                <p class="text-xs text-[#6b7280] truncate">
                                    {{ $u->lokasi_sekretariat ? '📍 ' . $u->lokasi_sekretariat : 'Sekretariat: Belum tersedia' }}
                                </p>
                            </div>
                        </div>

                        {{-- DESKRIPSI --}}
                        <p class="text-xs text-[#6b7280] leading-relaxed line-clamp-3">
                            {{ $u->deskripsi ?: 'Belum tersedia deskripsi rinci untuk UKM ini.' }}
                        </p>

                        {{-- INFORMASI RINGKAS --}}
                        <div class="grid grid-cols-2 gap-2 pt-3 border-t border-[#fecaca] text-xs">
                            <div class="bg-[#fdf2f2] p-2.5 rounded-xl border border-[#fecaca]">
                                <div class="text-[10px] text-[#6b7280] uppercase font-bold">Hari Latihan</div>
                                <div class="font-bold text-[#1f2937] truncate mt-0.5">
                                    {{ $u->hari_latihan ?: 'Belum tersedia' }}
                                </div>
                            </div>
                            <div class="bg-[#fdf2f2] p-2.5 rounded-xl border border-[#fecaca]">
                                <div class="text-[10px] text-[#6b7280] uppercase font-bold">Kontak</div>
                                <div class="font-bold text-[#da2424] truncate mt-0.5">
                                    {{ $u->kontak ?: 'Belum tersedia' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER ACTION --}}
                    <div class="px-6 py-4 bg-[#fdf2f2] border-t border-[#fecaca] flex items-center justify-between gap-3">
                        @if($regStatus)
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-[#eaf8f2] text-[#258260] border border-[#c9eadc]">
                                ✓ {{ ucfirst(str_replace('_', ' ', $regStatus)) }}
                            </span>
                        @else
                            <span class="text-xs text-[#6b7280] italic">Read-Only</span>
                        @endif

                        <a href="{{ route('mahasiswa.ukm.show', $u->id) }}"
                           class="px-4 py-2 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-sm transition inline-flex items-center gap-1">
                            Lihat Detail UKM →
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-12 text-center space-y-4">
            <div class="w-16 h-16 rounded-full bg-[#fee2e2] text-[#da2424] flex items-center justify-center mx-auto text-3xl font-bold">
                🔍
            </div>
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">UKM Tidak Ditemukan</h2>
                <p class="text-xs text-[#6b7280] max-w-md mx-auto mt-1">
                    Tidak ada Unit Kegiatan Mahasiswa yang sesuai dengan kata kunci pencarian "{{ $search }}".
                </p>
            </div>
            <a href="{{ route('mahasiswa.ukm.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#da2424] text-white text-xs font-bold hover:bg-[#b91c1c] shadow-md transition">
                Tampilkan Seluruh UKM
            </a>
        </div>
    @endif

</div>

@endsection
