@extends('layouts.app')

@section('title', 'Detail Sub-Kriteria')
@section('page-title', 'Detail Sub-Kriteria')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="mb-6">

        <div class="flex items-start justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold text-[#1f2937]">
                    Detail Sub-Kriteria
                </h1>

                <p class="text-sm text-[#6b7280] mt-1">
                    Informasi lengkap sub-kriteria yang digunakan dalam penilaian.
                </p>

            </div>


            {{-- TOMBOL KEMBALI --}}
            <a href="{{ route('sub-kriteria.index') }}"
               class="inline-flex items-center justify-center
                      gap-2
                      px-4 py-2.5
                      rounded-xl
                      border border-[#ddd6f3]
                      bg-white
                      text-[#1f2937]
                      text-sm font-semibold
                      hover:bg-[#fef2f2]
                      hover:border-[#cfc5ed]
                      transition
                      whitespace-nowrap">

                <span>Kembali</span>

                <svg
                    class="w-4 h-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        d="M9 5l7 7-7 7"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>

            </a>

        </div>

    </div>


    {{-- =====================================================
        CARD UTAMA
    ====================================================== --}}
    <div class="bg-white
                rounded-2xl
                border border-[#f8c4c4]
                shadow-sm
                overflow-hidden">


        {{-- =================================================
            INFORMASI SUB-KRITERIA
        ================================================== --}}
        <div class="p-6 border-b border-[#fecaca]">

            <h2 class="text-lg font-bold text-[#1f2937]">
                Informasi Sub-Kriteria
            </h2>

            <p class="text-sm text-[#6b7280] mt-1">
                Data sub-kriteria yang telah tersimpan pada sistem.
            </p>

        </div>


        {{-- =================================================
            DETAIL DATA
        ================================================== --}}
        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- =================================================
                    KRITERIA
                ================================================== --}}
                <div class="bg-[#fdf2f2]
                            rounded-xl
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              text-[#6b7280]
                              uppercase
                              tracking-wide">

                        Kriteria

                    </p>


                    <div class="mt-3">

                        @if($subKriteria->kriteria)

                            <div class="flex items-center gap-3">

                                {{-- KODE --}}
                                <div class="w-10 h-10
                                            rounded-xl
                                            bg-[#fee2e2]
                                            flex items-center justify-center
                                            text-[#da2424]
                                            font-bold
                                            text-sm">

                                    {{ $subKriteria->kriteria->kode }}

                                </div>


                                {{-- NAMA --}}
                                <div>

                                    <p class="font-bold text-[#1f2937]">

                                        {{ $subKriteria->kriteria->nama_kriteria }}

                                    </p>

                                    <p class="text-xs text-[#6b7280] mt-0.5">

                                        Kriteria penilaian

                                    </p>

                                </div>

                            </div>

                        @else

                            <p class="font-semibold text-gray-500">
                                -
                            </p>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    STATUS
                ================================================== --}}
                <div class="bg-[#fdf2f2]
                            rounded-xl
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              text-[#6b7280]
                              uppercase
                              tracking-wide">

                        Status

                    </p>


                    <div class="mt-3">

                        @if($subKriteria->status === 'aktif')

                            <span class="inline-flex
                                         items-center
                                         gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-[#eaf8f0]
                                         text-[#218653]
                                         text-xs
                                         font-semibold">

                                <span class="w-1.5 h-1.5
                                             rounded-full
                                             bg-[#2eaa6b]">
                                </span>

                                Aktif

                            </span>

                        @else

                            <span class="inline-flex
                                         items-center
                                         gap-2
                                         px-3 py-1.5
                                         rounded-full
                                         bg-[#f3f1f6]
                                         text-[#6b7280]
                                         text-xs
                                         font-semibold">

                                <span class="w-1.5 h-1.5
                                             rounded-full
                                             bg-[#9993a8]">
                                </span>

                                Tidak Aktif

                            </span>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    NAMA SUB-KRITERIA
                ================================================== --}}
                <div class="md:col-span-2
                            bg-[#fef2f2]
                            rounded-xl
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              text-[#6b7280]
                              uppercase
                              tracking-wide">

                        Nama Sub-Kriteria

                    </p>


                    <p class="text-xl
                              font-bold
                              text-[#1f2937]
                              mt-2">

                        {{ $subKriteria->nama_subkriteria }}

                    </p>


                    <p class="text-xs
                              text-[#6b7280]
                              mt-2">

                        Sub-kriteria ini digunakan sebagai parameter
                        penilaian pada kriteria
                        <span class="font-semibold text-[#da2424]">
                            {{ $subKriteria->kriteria->nama_kriteria ?? '-' }}
                        </span>.

                    </p>

                </div>


                {{-- =================================================
                    URUTAN
                ================================================== --}}
                <div class="bg-[#fdf2f2]
                            rounded-xl
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              text-[#6b7280]
                              uppercase
                              tracking-wide">

                        Urutan

                    </p>


                    <div class="flex items-center gap-3 mt-3">

                        <div class="w-10 h-10
                                    rounded-xl
                                    bg-white
                                    border border-[#e4def1]
                                    flex items-center justify-center
                                    text-[#da2424]
                                    font-bold">

                            {{ $subKriteria->urutan ?? '-' }}

                        </div>


                        <div>

                            <p class="font-semibold text-[#1f2937]">
                                Urutan tampilan
                            </p>

                            <p class="text-xs text-[#6b7280] mt-0.5">
                                Menentukan posisi sub-kriteria pada daftar.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ID DATA
                ================================================== --}}
                <div class="bg-[#fdf2f2]
                            rounded-xl
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              text-[#6b7280]
                              uppercase
                              tracking-wide">

                        ID Data

                    </p>


                    <p class="text-lg
                              font-bold
                              text-[#1f2937]
                              mt-3">

                        #{{ $subKriteria->id }}

                    </p>

                    <p class="text-xs text-[#6b7280] mt-1">
                        Identitas data sub-kriteria.
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
            CATATAN
        ====================================================== --}}
        <div class="px-6 pb-6">

            <div class="rounded-xl
                        bg-[#fef2f2]
                        border border-[#fecaca]
                        p-4">

                <div class="flex gap-3">

                    <div class="w-8 h-8
                                rounded-lg
                                bg-white
                                flex items-center justify-center
                                text-[#da2424]
                                font-bold
                                flex-shrink-0">

                        i

                    </div>


                    <div>

                        <p class="text-sm
                                  font-semibold
                                  text-[#1f2937]">

                            Catatan

                        </p>

                        <p class="text-xs
                                  text-[#6b7280]
                                  mt-1
                                  leading-5">

                            Urutan hanya digunakan untuk mengatur posisi
                            sub-kriteria pada daftar. Urutan tidak secara
                            langsung menentukan bobot AHP maupun nilai
                            perhitungan TOPSIS.

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            BUTTON
        ====================================================== --}}
        <div class="px-6 py-5
                    bg-[#fdf2f2]
                    border-t border-[#fecaca]">

            <div class="flex flex-col
                        sm:flex-row
                        justify-end
                        gap-3">


                {{-- KEMBALI --}}
                <a href="{{ route('sub-kriteria.index') }}"
                   class="inline-flex items-center
                          justify-center
                          gap-2
                          px-5 py-3
                          rounded-xl
                          border border-[#ddd6f3]
                          bg-white
                          text-[#55506f]
                          font-semibold
                          text-sm
                          hover:bg-[#f5f2fb]
                          hover:border-[#cfc5ed]
                          transition">

                    <svg
                        class="w-4 h-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            d="M15 19l-7-7 7-7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    Kembali

                </a>


                {{-- EDIT --}}
                <a href="{{ route('sub-kriteria.edit', $subKriteria->id) }}"
                   class="inline-flex items-center
                          justify-center
                          gap-2
                          px-6 py-3
                          rounded-xl
                          bg-[#da2424]
                          text-white
                          font-semibold
                          text-sm
                          hover:bg-[#b91c1c]
                          shadow-sm
                          transition">

                    <svg
                        class="w-4 h-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            d="M12 20h9"
                            stroke-linecap="round"
                        />

                        <path
                            d="M16.5 3.5a2.1 2.1 0 013 3L8 18l-4 1 1-4L16.5 3.5z"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />

                    </svg>

                    Edit Sub-Kriteria

                </a>

            </div>

        </div>

    </div>

</div>

@endsection