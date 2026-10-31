@extends('layouts.app')

@section('title', 'Detail Profil UKM - SPK UKM')
@section('page-title', 'Detail Profil UKM')

@section('content')

<div class="page-content">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>

            <div class="flex items-center gap-3 mb-2">

                <a
                    href="{{ route('ukm-kriteria-profile.index') }}"
                    class="inline-flex items-center justify-center
                           w-9 h-9
                           rounded-lg
                           bg-[#f3efff]
                           text-[#da2424]
                           hover:bg-[#fee2e2]
                           transition"
                    title="Kembali"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="18"
                         height="18"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <path d="M19 12H5"/>
                        <path d="M12 19l-7-7 7-7"/>

                    </svg>

                </a>

                <h1 class="text-2xl font-bold text-[#1f2937]">
                    Detail Profil UKM
                </h1>

            </div>

            <p class="text-sm text-[#6b7280]">
                Informasi karakteristik dan profil kriteria
                {{ $ukm->nama_ukm }}.
            </p>

        </div>


        {{-- EDIT PROFIL --}}
        <a
            href="{{ route('ukm-kriteria-profile.manage', $ukm->id) }}"
            class="inline-flex items-center gap-2
                   px-5 py-2.5
                   rounded-lg
                   bg-[#da2424]
                   hover:bg-[#b91c1c]
                   text-white
                   text-sm
                   font-semibold
                   transition
                   shadow-sm"
        >

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

            Kelola Profil

        </a>

    </div>


    {{-- =====================================================
        INFORMASI UKM
    ====================================================== --}}
    <div class="ui-card mb-6 overflow-hidden">

        <div class="p-6">

            <div class="flex items-start gap-5">

                {{-- LOGO --}}
                <div class="flex-shrink-0">

                    @if($ukm->logo)

                        <img
                            src="{{ asset('storage/' . $ukm->logo) }}"
                            alt="Logo {{ $ukm->nama_ukm }}"
                            class="w-20 h-20
                                   rounded-2xl
                                   object-contain
                                   border border-[#fecaca]
                                   bg-white
                                   p-2"
                        >

                    @else

                        <div class="w-20 h-20
                                    rounded-2xl
                                    bg-[#fee2e2]
                                    flex
                                    items-center
                                    justify-center
                                    text-[#da2424]
                                    text-2xl
                                    font-bold">

                            {{ strtoupper(substr($ukm->nama_ukm, 0, 1)) }}

                        </div>

                    @endif

                </div>


                {{-- INFORMASI --}}
                <div class="flex-1">

                    <div class="flex items-center gap-3 flex-wrap">

                        <h2 class="text-xl font-bold text-[#1f2937]">
                            {{ $ukm->nama_ukm }}
                        </h2>


                        @if($ukm->status === 'aktif')

                            <span class="inline-flex items-center
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

                            <span class="inline-flex items-center
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


                    @if($ukm->deskripsi)

                        <p class="text-sm
                                  text-[#6b7280]
                                  mt-2
                                  leading-relaxed">

                            {{ $ukm->deskripsi }}

                        </p>

                    @endif


                    <div class="grid grid-cols-1 md:grid-cols-3
                                gap-4
                                mt-5">

                        {{-- KETUA --}}
                        <div>

                            <p class="text-xs
                                      font-semibold
                                      text-[#9993ad]
                                      uppercase
                                      tracking-wide">

                                Ketua UKM

                            </p>

                            <p class="text-sm
                                      font-medium
                                      text-[#55506f]
                                      mt-1">

                                {{ $ukm->ketua ?: '-' }}

                            </p>

                        </div>


                        {{-- PEMBINA --}}
                        <div>

                            <p class="text-xs
                                      font-semibold
                                      text-[#9993ad]
                                      uppercase
                                      tracking-wide">

                                Pembina

                            </p>

                            <p class="text-sm
                                      font-medium
                                      text-[#55506f]
                                      mt-1">

                                {{ $ukm->pembina ?: '-' }}

                            </p>

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <p class="text-xs
                                      font-semibold
                                      text-[#9993ad]
                                      uppercase
                                      tracking-wide">

                                Email

                            </p>

                            <p class="text-sm
                                      font-medium
                                      text-[#55506f]
                                      mt-1">

                                {{ $ukm->email ?: '-' }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        KARAKTERISTIK UKM
    ====================================================== --}}
    <div class="ui-card overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5
                    border-b border-[#fecaca]">

            <h2 class="text-lg font-bold text-[#1f2937]">
                Karakteristik UKM
            </h2>

            <p class="text-sm text-[#6b7280] mt-1">

                Nilai karakteristik yang digunakan sebagai
                parameter dalam proses SPK.

            </p>

        </div>


        {{-- CONTENT --}}
        <div class="p-6">

            @if(count($karakteristik) > 0)

                <div class="grid grid-cols-1
                            md:grid-cols-2
                            xl:grid-cols-3
                            gap-5">

                    @foreach($karakteristik as $item)

                        <div class="rounded-2xl
                                    border border-[#e5e0f0]
                                    bg-[#fdf2f2]
                                    p-5
                                    hover:shadow-sm
                                    transition">

                            {{-- HEADER KRITERIA --}}
                            <div class="flex items-start
                                        justify-between
                                        gap-3">

                                <div>

                                    <span class="inline-flex
                                                 items-center
                                                 px-2.5 py-1
                                                 rounded-lg
                                                 bg-[#fee2e2]
                                                 text-[#da2424]
                                                 text-xs
                                                 font-bold">

                                        {{ $item['kriteria']->kode }}

                                    </span>


                                    <h3 class="text-base
                                               font-bold
                                               text-[#1f2937]
                                               mt-3">

                                        {{ $item['kriteria']->nama_kriteria }}

                                    </h3>

                                </div>


                                {{-- NILAI --}}
                                <div class="text-right">

                                    <div class="text-2xl
                                                font-bold
                                                text-[#da2424]">

                                        {{ number_format((float) $item['nilai'], 2) }}

                                    </div>

                                    <div class="text-xs
                                                text-[#9993ad]">

                                        Nilai

                                    </div>

                                </div>

                            </div>


                            {{-- SUB KRITERIA --}}
                            <div class="mt-5
                                        pt-4
                                        border-t border-[#f8c4c4]">

                                <p class="text-xs
                                          font-semibold
                                          text-[#9993ad]
                                          uppercase
                                          tracking-wide">

                                    Karakteristik

                                </p>


                                @if($item['sub_kriteria'])

                                    <p class="text-sm
                                              font-semibold
                                              text-[#55506f]
                                              mt-2">

                                        {{ $item['sub_kriteria']->nama_subkriteria }}

                                    </p>

                                @else

                                    <p class="text-sm
                                              text-gray-400
                                              mt-2">

                                        Tidak menggunakan sub-kriteria.

                                    </p>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                {{-- DATA KOSONG --}}
                <div class="py-12
                            text-center">

                    <div class="w-14 h-14
                                rounded-2xl
                                bg-[#fee2e2]
                                flex
                                items-center
                                justify-center
                                text-[#da2424]
                                mx-auto">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="25"
                             height="25"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round">

                            <path d="M12 2v20"/>
                            <path d="M2 12h20"/>

                        </svg>

                    </div>


                    <h3 class="text-base
                               font-semibold
                               text-[#1f2937]
                               mt-4">

                        Belum Ada Profil Kriteria

                    </h3>


                    <p class="text-sm
                              text-[#6b7280]
                              mt-1">

                        Profil karakteristik untuk UKM ini
                        belum diatur.

                    </p>


                    <a
                        href="{{ route('ukm-kriteria-profile.manage', $ukm->id) }}"
                        class="inline-flex
                               items-center
                               gap-2
                               mt-5
                               px-4 py-2.5
                               rounded-lg
                               bg-[#da2424]
                               hover:bg-[#b91c1c]
                               text-white
                               text-sm
                               font-semibold"
                    >

                        Kelola Profil

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection