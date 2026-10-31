@extends('layouts.app')

@section('title', 'Detail Kriteria')
@section('page-title', 'Detail Kriteria')

@section('content')

<div class="page-content">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Detail Kriteria
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Informasi detail kriteria yang digunakan
                dalam sistem pendukung keputusan.
            </p>

        </div>

    </div>


    {{-- =====================================================
        DETAIL CARD
    ====================================================== --}}
    <div class="ui-card overflow-hidden">

        {{-- HEADER CARD --}}
        <div class="px-6 py-5 border-b border-[#fecaca]">

            <div class="flex items-center gap-4">

                <div class="w-11 h-11
                            rounded-xl
                            bg-[#fee2e2]
                            flex items-center justify-center
                            text-[#8B0000]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="22"
                         height="22"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <path d="M4 19V5"></path>
                        <path d="M4 19h16"></path>
                        <path d="M7 16l4-5 3 2 5-7"></path>

                    </svg>

                </div>

                <div>

                    <h2 class="text-lg font-bold text-[#1f2937]">
                        {{ $kriteria->nama_kriteria }}
                    </h2>

                    <p class="text-sm text-[#6b7280] mt-1">
                        Informasi kriteria
                    </p>

                </div>

            </div>

        </div>


        {{-- =================================================
            DATA DETAIL
        ================================================== --}}
        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- KODE --}}
                <div class="rounded-xl
                            bg-[#fdf2f2]
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              uppercase
                              tracking-wide
                              text-[#6b7280]">

                        Kode Kriteria

                    </p>

                    <p class="text-xl
                              font-bold
                              text-[#392f78]
                              mt-2">

                        {{ $kriteria->kode }}

                    </p>

                </div>


                {{-- NAMA --}}
                <div class="rounded-xl
                            bg-[#fdf2f2]
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              uppercase
                              tracking-wide
                              text-[#6b7280]">

                        Nama Kriteria

                    </p>

                    <p class="text-lg
                              font-bold
                              text-[#1f2937]
                              mt-2">

                        {{ $kriteria->nama_kriteria }}

                    </p>

                </div>


                {{-- TIPE --}}
                <div class="rounded-xl
                            bg-[#fdf2f2]
                            border border-[#fecaca]
                            p-5">

                    <p class="text-xs
                              font-semibold
                              uppercase
                              tracking-wide
                              text-[#6b7280]">

                        Tipe

                    </p>

                    @if ($kriteria->tipe === 'benefit')

                        <span class="inline-flex
                                     items-center
                                     mt-2
                                     px-3
                                     py-1.5
                                     rounded-full
                                     bg-green-50
                                     border border-green-200
                                     text-green-700
                                     text-sm
                                     font-semibold">

                            Benefit

                        </span>

                        <p class="text-xs text-[#6b7280] mt-2">
                            Semakin besar semakin baik.
                        </p>

                    @else

                        <span class="inline-flex
                                     items-center
                                     mt-2
                                     px-3
                                     py-1.5
                                     rounded-full
                                     bg-orange-50
                                     border border-orange-200
                                     text-orange-700
                                     text-sm
                                     font-semibold">

                            Cost

                        </span>

                        <p class="text-xs text-[#6b7280] mt-2">
                            Semakin kecil semakin baik.
                        </p>

                    @endif

                </div>

            </div>


            {{-- =================================================
                INFORMASI WAKTU
            ================================================== --}}
            <div class="mt-6
                        rounded-xl
                        border border-[#fecaca]
                        overflow-hidden">

                <div class="px-5 py-4
                            bg-[#f8f6fd]
                            border-b border-[#fecaca]">

                    <h3 class="text-sm
                               font-bold
                               text-[#1f2937]">

                        Informasi Data

                    </h3>

                </div>

                <div class="divide-y divide-[#eeeaf6]">

                    <div class="px-5 py-4
                                flex
                                items-center
                                justify-between
                                gap-5">

                        <span class="text-sm text-[#6b7280]">
                            ID Kriteria
                        </span>

                        <span class="text-sm
                                     font-semibold
                                     text-[#1f2937]">

                            {{ $kriteria->id }}

                        </span>

                    </div>


                    <div class="px-5 py-4
                                flex
                                items-center
                                justify-between
                                gap-5">

                        <span class="text-sm text-[#6b7280]">
                            Dibuat
                        </span>

                        <span class="text-sm
                                     font-semibold
                                     text-[#1f2937]">

                            {{ $kriteria->created_at?->format('d M Y, H:i') ?? '-' }}

                        </span>

                    </div>


                    <div class="px-5 py-4
                                flex
                                items-center
                                justify-between
                                gap-5">

                        <span class="text-sm text-[#6b7280]">
                            Terakhir diperbarui
                        </span>

                        <span class="text-sm
                                     font-semibold
                                     text-[#1f2937]">

                            {{ $kriteria->updated_at?->format('d M Y, H:i') ?? '-' }}

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            FOOTER
        ================================================== --}}
        <div class="px-6 py-5
                    border-t border-[#fecaca]
                    bg-[#fdf2f2]
                    flex items-center
                    justify-between">

            {{-- KEMBALI --}}
            <a href="{{ route('kriteria.index') }}"
               class="inline-flex
                      items-center
                      gap-2
                      px-5
                      py-2.5
                      rounded-lg
                      border border-[#d8d2eb]
                      bg-white
                      hover:bg-[#fef2f2]
                      text-[#392f78]
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

                    <line x1="19"
                          y1="12"
                          x2="5"
                          y2="12"></line>

                    <polyline points="12 19 5 12 12 5"></polyline>

                </svg>

                Kembali

            </a>


            {{-- AKSI --}}
            <div class="flex items-center gap-3">

                <a href="{{ route('kriteria.edit', $kriteria) }}"
                   class="inline-flex
                          items-center
                          gap-2
                          px-5
                          py-2.5
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

                        <path d="M12 20h9"></path>

                        <path d="M16.5 3.5
                                 a2.121 2.121 0 0 1 3 3
                                 L7 19l-4 1 1-4Z"></path>

                    </svg>

                    Edit Kriteria

                </a>

            </div>

        </div>

    </div>

</div>

@endsection