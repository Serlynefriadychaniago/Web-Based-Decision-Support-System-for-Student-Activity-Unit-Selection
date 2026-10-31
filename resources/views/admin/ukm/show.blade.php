@extends('layouts.app')

@section('title', 'Detail UKM - SPK UKM')
@section('page-title', 'Detail UKM')

@section('content')

<div class="page-content">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div class="flex items-start gap-4">

            {{-- BACK --}}
            <a href="{{ route('ukm.index') }}"
               class="mt-1 inline-flex items-center justify-center
                      w-10 h-10
                      rounded-xl
                      bg-white
                      border border-[#fecaca]
                      text-[#da2424]
                      hover:bg-[#f8f6fd]
                      transition"
               title="Kembali">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="19"
                     height="19"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <line x1="19"
                          y1="12"
                          x2="5"
                          y2="12"/>

                    <polyline points="12 19 5 12 12 5"/>

                </svg>

            </a>


            <div>

                <h1 class="text-2xl font-bold text-[#1f2937]">
                    Detail UKM
                </h1>

                <p class="text-sm text-[#6b7280] mt-1">
                    Informasi lengkap mengenai Unit Kegiatan Mahasiswa.
                </p>

            </div>

        </div>


        {{-- TOMBOL AKSI --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('ukm-kriteria-profile.manage', $ukm->id) }}"
               class="inline-flex items-center gap-2
                      px-5 py-2.5
                      rounded-lg
                      bg-[#fee2e2]
                      hover:bg-[#fecaca]
                      text-[#da2424]
                      text-sm
                      font-semibold
                      transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="18"
                     height="18"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                Kelola Profil Kriteria
            </a>

            <a href="{{ route('ukm.edit', $ukm->id) }}"
               class="inline-flex items-center gap-2
                      px-5 py-2.5
                      rounded-lg
                      bg-[#da2424]
                      hover:bg-[#b91c1c]
                      text-white
                      text-sm
                      font-semibold
                      transition
                      shadow-sm">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="18"
                     height="18"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="M12 20h9"/>

                    <path d="M16.5 3.5
                             a2.121 2.121 0 0 1 3 3
                             L7 19l-4 1 1-4Z"/>

                </svg>

                Edit UKM

            </a>
        </div>

    </div>


    {{-- =====================================================
        INFORMASI UTAMA
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="p-6">

            <div class="flex flex-col md:flex-row
                        items-start
                        gap-6">

                {{-- =================================================
                    LOGO
                ================================================== --}}
                <div class="flex-shrink-0">

                    @if($ukm->logo)

                        <div class="w-32 h-32
                                    rounded-2xl
                                    border border-[#fecaca]
                                    bg-white
                                    flex items-center
                                    justify-center
                                    p-3">

                            <img
                                src="{{ asset('storage/' . $ukm->logo) }}"
                                alt="Logo {{ $ukm->nama_ukm }}"
                                class="w-full h-full
                                       object-contain
                                       rounded-xl"
                            >

                        </div>

                    @else

                        <div class="w-32 h-32
                                    rounded-2xl
                                    bg-[#fee2e2]
                                    flex items-center
                                    justify-center
                                    text-[#da2424]
                                    text-4xl
                                    font-bold">

                            {{ strtoupper(substr($ukm->nama_ukm, 0, 1)) }}

                        </div>

                    @endif

                </div>


                {{-- =================================================
                    NAMA & STATUS
                ================================================== --}}
                <div class="flex-1">

                    <div class="flex flex-col sm:flex-row
                                sm:items-center
                                gap-3">

                        <h2 class="text-2xl
                                   font-bold
                                   text-[#1f2937]">

                            {{ $ukm->nama_ukm }}

                        </h2>


                        {{-- STATUS --}}
                        @if($ukm->status === 'aktif')

                            <span class="inline-flex
                                         items-center
                                         w-fit
                                         px-3 py-1
                                         rounded-full
                                         bg-green-50
                                         text-green-700
                                         border border-green-200
                                         text-xs
                                         font-semibold">

                                <span class="w-1.5 h-1.5
                                             rounded-full
                                             bg-green-500
                                             mr-2">
                                </span>

                                Aktif

                            </span>

                        @else

                            <span class="inline-flex
                                         items-center
                                         w-fit
                                         px-3 py-1
                                         rounded-full
                                         bg-gray-100
                                         text-gray-600
                                         border border-gray-200
                                         text-xs
                                         font-semibold">

                                <span class="w-1.5 h-1.5
                                             rounded-full
                                             bg-gray-400
                                             mr-2">
                                </span>

                                Nonaktif

                            </span>

                        @endif

                    </div>


                    {{-- DESKRIPSI SINGKAT --}}
                    @if($ukm->deskripsi)

                        <p class="text-sm
                                  text-[#6b7280]
                                  leading-7
                                  mt-4">

                            {{ $ukm->deskripsi }}

                        </p>

                    @else

                        <p class="text-sm
                                  text-[#aaa5ba]
                                  italic
                                  mt-4">

                            Belum ada deskripsi UKM.

                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        PROFIL 5 KRITERIA SPK (DATA ACUAN TOPSIS)
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-5 border-b border-[#fecaca] flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-xl bg-[#fee2e2] flex items-center justify-center text-[#da2424] flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-[#1f2937]">
                        Profil 5 Kriteria SPK
                    </h2>
                    <p class="text-xs text-[#6b7280] mt-0.5">
                        Nilai dan indikator profil UKM yang digunakan sebagai data acuan untuk proses TOPSIS.
                    </p>
                </div>

            </div>

            <a href="{{ route('ukm-kriteria-profile.manage', $ukm->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#fee2e2] hover:bg-[#fecaca] text-[#da2424] text-xs font-bold transition flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                </svg>
                Kelola Profil Kriteria
            </a>

        </div>

        <div class="p-6 overflow-x-auto">

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#fecaca] text-xs font-bold text-[#6b7280] uppercase tracking-wider bg-[#fdf2f2]">
                        <th class="py-3 px-4 rounded-l-xl">Kode</th>
                        <th class="py-3 px-4">Kriteria</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4 text-center">Profil UKM</th>
                        <th class="py-3 px-4 rounded-r-xl">Indikator / Sub-Kriteria</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca] text-sm text-[#1f2937]">
                    @forelse($criteriaProfiles as $cp)
                        <tr class="hover:bg-[#fdf2f2] transition">
                            <td class="py-3.5 px-4 font-bold text-[#da2424]">
                                <span class="px-2.5 py-1 rounded-lg bg-[#fee2e2] text-[#da2424] font-mono text-xs font-bold">
                                    {{ $cp['kode'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#1f2937]">
                                {{ $cp['nama_kriteria'] }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if(strtolower($cp['tipe']) === 'benefit')
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-green-50 text-green-700 border border-green-200 text-xs font-bold capitalize">
                                        Benefit
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold capitalize">
                                        Cost
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-extrabold text-base text-[#da2424]">
                                {{ $cp['nilai'] !== null ? number_format($cp['nilai'], 2) : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-medium text-[#55506f]">
                                {{ $cp['sub_kriteria'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-sm text-gray-500 italic">
                                Belum ada data profil kriteria untuk UKM ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>


    {{-- =====================================================
        GRID INFORMASI
    ====================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


        {{-- =================================================
            VISI & MISI
        ================================================== --}}
        <div class="ui-card overflow-hidden">

            <div class="px-6 py-5
                        border-b border-[#fecaca]">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10
                                rounded-xl
                                bg-[#fee2e2]
                                flex items-center
                                justify-center
                                text-[#da2424]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20"
                             height="20"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <circle cx="12"
                                    cy="12"
                                    r="10"/>

                            <line x1="2"
                                  y1="12"
                                  x2="22"
                                  y2="12"/>

                            <path d="M12 2
                                     a15.3 15.3 0 0 1
                                     4 10
                                     15.3 15.3 0 0 1
                                     -4 10
                                     15.3 15.3 0 0 1
                                     -4-10
                                     15.3 15.3 0 0 1
                                     4-10z"/>

                        </svg>

                    </div>

                    <div>

                        <h2 class="text-lg
                                   font-bold
                                   text-[#1f2937]">

                            Visi & Misi

                        </h2>

                        <p class="text-xs
                                  text-[#6b7280]
                                  mt-0.5">

                            Tujuan dan arah organisasi UKM.

                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6 space-y-6">

                {{-- VISI --}}
                <div>

                    <h3 class="text-sm
                               font-semibold
                               text-[#da2424]
                               mb-2">

                        Visi

                    </h3>

                    @if($ukm->visi)

                        <p class="text-sm
                                  text-[#55506f]
                                  leading-7">

                            {{ $ukm->visi }}

                        </p>

                    @else

                        <p class="text-sm
                                  text-[#aaa5ba]
                                  italic">

                            Belum ada data visi.

                        </p>

                    @endif

                </div>


                {{-- MISI --}}
                <div>

                    <h3 class="text-sm
                               font-semibold
                               text-[#da2424]
                               mb-2">

                        Misi

                    </h3>

                    @if($ukm->misi)

                        <p class="text-sm
                                  text-[#55506f]
                                  leading-7
                                  whitespace-pre-line">

                            {{ $ukm->misi }}

                        </p>

                    @else

                        <p class="text-sm
                                  text-[#aaa5ba]
                                  italic">

                            Belum ada data misi.

                        </p>

                    @endif

                </div>

            </div>

        </div>


        {{-- =================================================
            PENGELOLA
        ================================================== --}}
        <div class="ui-card overflow-hidden">

            <div class="px-6 py-5
                        border-b border-[#fecaca]">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10
                                rounded-xl
                                bg-[#fee2e2]
                                flex items-center
                                justify-center
                                text-[#da2424]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20"
                             height="20"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M20 21v-2
                                     a4 4 0 0 0-4-4H8
                                     a4 4 0 0 0-4 4v2"/>

                            <circle cx="12"
                                    cy="7"
                                    r="4"/>

                        </svg>

                    </div>

                    <div>

                        <h2 class="text-lg
                                   font-bold
                                   text-[#1f2937]">

                            Pengelola UKM

                        </h2>

                        <p class="text-xs
                                  text-[#6b7280]
                                  mt-0.5">

                            Informasi pembina dan ketua.

                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6 space-y-5">

                {{-- PEMBINA --}}
                <div class="flex items-start gap-4">

                    <div class="w-10 h-10
                                rounded-lg
                                bg-[#f8f6fd]
                                flex items-center
                                justify-center
                                text-[#da2424]
                                flex-shrink-0">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="18"
                             height="18"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <circle cx="12"
                                    cy="7"
                                    r="4"/>

                            <path d="M5.5 21
                                     a6.5 6.5 0 0 1
                                     13 0"/>

                        </svg>

                    </div>

                    <div>

                        <p class="text-xs
                                  text-[#6b7280]">

                            Pembina

                        </p>

                        <p class="text-sm
                                  font-semibold
                                  text-[#1f2937]
                                  mt-1">

                            {{ $ukm->pembina ?: '-' }}

                        </p>

                    </div>

                </div>


                {{-- KETUA --}}
                <div class="flex items-start gap-4">

                    <div class="w-10 h-10
                                rounded-lg
                                bg-[#f8f6fd]
                                flex items-center
                                justify-center
                                text-[#da2424]
                                flex-shrink-0">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="18"
                             height="18"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <circle cx="12"
                                    cy="7"
                                    r="4"/>

                            <path d="M5.5 21
                                     a6.5 6.5 0 0 1
                                     13 0"/>

                        </svg>

                    </div>

                    <div>

                        <p class="text-xs
                                  text-[#6b7280]">

                            Ketua

                        </p>

                        <p class="text-sm
                                  font-semibold
                                  text-[#1f2937]
                                  mt-1">

                            {{ $ukm->ketua ?: '-' }}

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            INFORMASI KONTAK
        ================================================== --}}
        <div class="ui-card overflow-hidden">

            <div class="px-6 py-5
                        border-b border-[#fecaca]">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10
                                rounded-xl
                                bg-[#fee2e2]
                                flex items-center
                                justify-center
                                text-[#da2424]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20"
                             height="20"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M22 16.92v3
                                     a2 2 0 0 1-2.18 2
                                     19.79 19.79 0 0 1-8.63-3.07
                                     19.5 19.5 0 0 1-6-6
                                     A19.79 19.79 0 0 1
                                     2.12 4.18
                                     A2 2 0 0 1
                                     4.11 2h3
                                     a2 2 0 0 1 2 1.72
                                     12.84 12.84 0 0 0
                                     .7 2.81
                                     a2 2 0 0 1-.45 2.11
                                     L8.09 9.91
                                     a16 16 0 0 0 6 6
                                     l1.27-1.27
                                     a2 2 0 0 1
                                     2.11-.45
                                     12.84 12.84 0 0 0
                                     2.81.7
                                     A2 2 0 0 1
                                     22 16.92z"/>

                        </svg>

                    </div>

                    <div>

                        <h2 class="text-lg
                                   font-bold
                                   text-[#1f2937]">

                            Informasi Kontak

                        </h2>

                        <p class="text-xs
                                  text-[#6b7280]
                                  mt-0.5">

                            Informasi yang dapat dihubungi mahasiswa.

                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6 space-y-5">

                {{-- KONTAK --}}
                <div>

                    <p class="text-xs
                              text-[#6b7280]">

                        Nomor Kontak

                    </p>

                    <p class="text-sm
                              font-semibold
                              text-[#1f2937]
                              mt-1">

                        {{ $ukm->kontak ?: '-' }}

                    </p>

                </div>


                {{-- EMAIL --}}
                <div>

                    <p class="text-xs
                              text-[#6b7280]">

                        Email

                    </p>

                    @if($ukm->email)

                        <a href="mailto:{{ $ukm->email }}"
                           class="text-sm
                                  font-semibold
                                  text-[#da2424]
                                  hover:underline
                                  mt-1
                                  inline-block">

                            {{ $ukm->email }}

                        </a>

                    @else

                        <p class="text-sm
                                  font-semibold
                                  text-[#1f2937]
                                  mt-1">

                            -

                        </p>

                    @endif

                </div>


                {{-- INSTAGRAM --}}
                <div>

                    <p class="text-xs
                              text-[#6b7280]">

                        Instagram

                    </p>

                    @if($ukm->instagram && $ukm->instagram_username)

                        <a href="{{ $ukm->instagram_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5
                                  text-sm
                                  font-semibold
                                  text-[#da2424]
                                  hover:underline
                                  mt-1">

                            <span>{{ $ukm->instagram_username }}</span>

                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>

                        </a>

                    @else

                        <p class="text-sm
                                  font-semibold
                                  text-[#1f2937]
                                  mt-1">

                            -

                        </p>

                    @endif

                </div>

            </div>

        </div>


        {{-- =================================================
            LOKASI & JADWAL
        ================================================== --}}
        <div class="ui-card overflow-hidden">

            <div class="px-6 py-5
                        border-b border-[#fecaca]">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10
                                rounded-xl
                                bg-[#fee2e2]
                                flex items-center
                                justify-center
                                text-[#da2424]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20"
                             height="20"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M20 10
                                     c0 5-8 12-8 12
                                     S4 15 4 10
                                     a8 8 0 1 1 16 0Z"/>

                            <circle cx="12"
                                    cy="10"
                                    r="3"/>

                        </svg>

                    </div>

                    <div>

                        <h2 class="text-lg
                                   font-bold
                                   text-[#1f2937]">

                            Lokasi & Jadwal

                        </h2>

                        <p class="text-xs
                                  text-[#6b7280]
                                  mt-0.5">

                            Informasi sekretariat dan latihan.

                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6 space-y-5">

                {{-- SEKRETARIAT --}}
                <div class="flex items-start gap-4">

                    <div class="w-10 h-10
                                rounded-lg
                                bg-[#f8f6fd]
                                flex items-center
                                justify-center
                                text-[#da2424]
                                flex-shrink-0">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="18"
                             height="18"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M3 21h18"/>
                            <path d="M5 21V5l7-3 7 3v16"/>
                            <path d="M9 21v-6h6v6"/>
                            <path d="M9 9h.01"/>
                            <path d="M15 9h.01"/>
                            <path d="M9 12h.01"/>
                            <path d="M15 12h.01"/>

                        </svg>

                    </div>

                    <div>

                        <p class="text-xs
                                  text-[#6b7280]">

                            Lokasi Sekretariat

                        </p>

                        <p class="text-sm
                                  font-semibold
                                  text-[#1f2937]
                                  mt-1
                                  leading-6">

                            {{ $ukm->lokasi_sekretariat ?: '-' }}

                        </p>

                    </div>

                </div>


                {{-- JADWAL --}}
                <div class="flex items-start gap-4">

                    <div class="w-10 h-10
                                rounded-lg
                                bg-[#f8f6fd]
                                flex items-center
                                justify-center
                                text-[#da2424]
                                flex-shrink-0">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="18"
                             height="18"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <rect x="3"
                                  y="4"
                                  width="18"
                                  height="18"
                                  rx="2"
                                  ry="2"/>

                            <line x1="16"
                                  y1="2"
                                  x2="16"
                                  y2="6"/>

                            <line x1="8"
                                  y1="2"
                                  x2="8"
                                  y2="6"/>

                            <line x1="3"
                                  y1="10"
                                  x2="21"
                                  y2="10"/>

                        </svg>

                    </div>

                    <div>

                        <p class="text-xs
                                  text-[#6b7280]">

                            Hari / Jadwal Latihan

                        </p>

                        <p class="text-sm
                                  font-semibold
                                  text-[#1f2937]
                                  mt-1
                                  leading-6">

                            {{ $ukm->hari_latihan ?: '-' }}

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        FOOTER ACTION
    ====================================================== --}}
    <div class="mt-6 flex items-center justify-between">

        <a href="{{ route('ukm.index') }}"
           class="inline-flex items-center gap-2
                  px-5 py-2.5
                  rounded-lg
                  border border-[#fecaca]
                  bg-white
                  text-[#55506f]
                  hover:bg-[#f8f6fd]
                  text-sm
                  font-semibold
                  transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="17"
                 height="17"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <line x1="19"
                      y1="12"
                      x2="5"
                      y2="12"/>

                <polyline points="12 19 5 12 12 5"/>

            </svg>

            Kembali ke Data UKM

        </a>


        <a href="{{ route('ukm.edit', $ukm->id) }}"
           class="inline-flex items-center gap-2
                  px-5 py-2.5
                  rounded-lg
                  bg-[#da2424]
                  hover:bg-[#b91c1c]
                  text-white
                  text-sm
                  font-semibold
                  transition
                  shadow-sm">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="17"
                 height="17"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M12 20h9"/>

                <path d="M16.5 3.5
                         a2.121 2.121 0 0 1 3 3
                         L7 19l-4 1 1-4Z"/>

            </svg>

            Edit UKM

        </a>

    </div>

</div>

@endsection