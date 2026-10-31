@extends('layouts.app')

@section('title', 'Sub-Kriteria')
@section('page-title', 'Sub-Kriteria')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-6">

        <div class="flex flex-col sm:flex-row
                    sm:items-center
                    sm:justify-between
                    gap-4">

            <div>

                <h1 class="text-2xl
                           font-bold
                           text-[#1f2937]">

                    Data Sub-Kriteria

                </h1>

                <p class="text-sm
                          text-[#6b7280]
                          mt-1">

                    Kelola sub-kriteria berdasarkan masing-masing kriteria
                    penilaian.

                </p>

            </div>


            {{-- =================================================
                 TOMBOL TAMBAH
            ================================================== --}}
            <a href="{{ route('sub-kriteria.create') }}"
               class="inline-flex items-center
                      justify-center
                      gap-2
                      px-5 py-3
                      rounded-xl
                      bg-[#da2424]
                      text-white
                      text-sm
                      font-semibold
                      hover:bg-[#770000]
                      shadow-sm
                      transition">

                <svg
                    class="w-4 h-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        d="M12 5v14"
                        stroke-linecap="round"
                    />

                    <path
                        d="M5 12h14"
                        stroke-linecap="round"
                    />
                </svg>

                Tambah Sub-Kriteria

            </a>

        </div>

    </div>


    {{-- =====================================================
         NOTIFIKASI BERHASIL
    ====================================================== --}}
    @if(session('success'))
        @php
            $isDelete = str_contains(strtolower(session('success')), 'hapus');
        @endphp

        @if($isDelete)
            <div class="mb-6 flex items-center justify-between gap-3
                        bg-[#fef2f2] border border-[#fecaca]
                        text-[#da2424]
                        rounded-xl px-5 py-4 shadow-sm">

                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-[#fee2e2] flex items-center justify-center font-bold text-sm text-[#da2424] flex-shrink-0">
                        🗑️
                    </span>

                    <div>
                        <p class="text-sm font-semibold">
                            Data Berhasil Dihapus
                        </p>
                        <p class="text-xs text-[#da2424]/90 mt-0.5 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>

            </div>
        @else
            <div class="mb-6 flex items-start gap-3
                        p-4
                        bg-[#eefbf4]
                        border border-[#ccefdc]
                        rounded-xl
                        text-[#218653]">

                <div class="w-7 h-7
                            rounded-lg
                            bg-white
                            flex items-center justify-center
                            flex-shrink-0
                            font-bold">
                    ✓
                </div>

                <div>
                    <p class="text-sm font-semibold">
                        Berhasil
                    </p>

                    <p class="text-sm mt-0.5">
                        {{ session('success') }}
                    </p>
                </div>

            </div>
        @endif
    @endif


    {{-- =====================================================
         NOTIFIKASI ERROR SESSION
    ====================================================== --}}
    @if(session('error'))

        <div class="mb-6
                    flex items-start gap-3
                    p-4
                    bg-[#fff0f1]
                    border border-[#ffd6da]
                    rounded-xl
                    text-[#d93848]">

            <div class="w-7 h-7
                        rounded-lg
                        bg-white
                        flex items-center justify-center
                        flex-shrink-0
                        font-bold">

                ✕

            </div>

            <div>

                <p class="text-sm font-semibold">
                    Gagal
                </p>

                <p class="text-sm mt-0.5">
                    {{ session('error') }}
                </p>

            </div>

        </div>

    @endif


    {{-- =====================================================
         ERROR
    ====================================================== --}}
    @if(isset($errors) && $errors->any())

        <div class="mb-6
                    p-4
                    bg-red-50
                    border border-red-200
                    rounded-xl">

            <p class="text-sm
                      font-semibold
                      text-red-700
                      mb-2">

                Terdapat kesalahan:

            </p>

            <ul class="list-disc
                       list-inside
                       text-sm
                       text-red-600">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         RINGKASAN
    ====================================================== --}}
    <div class="mb-6
                bg-white
                rounded-2xl
                border border-[#f8c4c4]
                shadow-sm
                px-6 py-5">

        <div class="flex items-center
                    justify-between
                    gap-4">

            <div>

                <p class="text-sm
                          font-semibold
                          text-[#1f2937]">

                    Total Sub-Kriteria

                </p>

                <p class="text-xs
                          text-[#6b7280]
                          mt-1">

                    Seluruh sub-kriteria yang tersedia
                    pada sistem.

                </p>

            </div>


            <div class="px-4 py-2
                        rounded-xl
                        bg-[#fef2f2]
                        text-[#8B0000]
                        font-bold
                        text-lg">

                {{ $subKriteria->count() }}

            </div>

        </div>

    </div>


    {{-- =====================================================
         KELOMPOKKAN BERDASARKAN KRITERIA
    ====================================================== --}}
    @php

        $kelompokKriteria = $subKriteria
            ->sortBy(function ($item) {

                return $item->kriteria->kode ?? '';

            })
            ->groupBy('kriteria_id');

    @endphp


    {{-- =====================================================
         DATA KRITERIA
    ====================================================== --}}
    @forelse($kelompokKriteria as $kriteriaId => $items)

        @php

            $kriteria = $items->first()->kriteria;

        @endphp


        {{-- =================================================
             CARD SETIAP KRITERIA
        ================================================== --}}
        <div class="mb-6
                    bg-white
                    rounded-2xl
                    border border-[#f8c4c4]
                    shadow-sm
                    overflow-hidden">


            {{-- =================================================
                 HEADER KRITERIA
            ================================================== --}}
            <div class="px-6 py-5
                        bg-[#fdf2f2]
                        border-b border-[#fecaca]">

                <div class="flex flex-col
                            sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-4">


                    {{-- INFORMASI KRITERIA --}}
                    <div class="flex items-center gap-4">

                        {{-- ICON --}}
                        <div class="w-12 h-12
                                    rounded-xl
                                    bg-[#fee2e2]
                                    flex items-center
                                    justify-center
                                    text-[#da2424]
                                    flex-shrink-0">

                            <svg
                                class="w-6 h-6"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="8"
                                />

                                <path
                                    d="M12 8v8"
                                    stroke-linecap="round"
                                />

                                <path
                                    d="M8 12h8"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>

                            <div class="flex items-center gap-2">

                                <span class="inline-flex
                                             items-center
                                             px-2.5 py-1
                                             rounded-lg
                                             bg-[#e9e1ff]
                                             text-[#8B0000]
                                             text-xs
                                             font-bold">

                                    {{ $kriteria->kode ?? '-' }}

                                </span>


                                <h2 class="text-lg
                                           font-bold
                                           text-[#1f2937]">

                                    {{ $kriteria->nama_kriteria ?? 'Kriteria' }}

                                </h2>

                            </div>


                            <p class="text-sm
                                      text-[#6b7280]
                                      mt-1">

                                Sub-kriteria untuk
                                {{ $kriteria->nama_kriteria ?? 'kriteria ini' }}.

                            </p>

                        </div>

                    </div>


                    {{-- JUMLAH --}}
                    <div class="inline-flex
                                items-center
                                justify-center
                                px-3 py-2
                                rounded-xl
                                bg-white
                                border border-[#e4def1]
                                text-[#da2424]
                                text-sm
                                font-semibold">

                        {{ $items->count() }} Sub-Kriteria

                    </div>

                </div>

            </div>


            {{-- =================================================
                 LIST SUB-KRITERIA
            ================================================== --}}
            <div class="p-4 sm:p-6">

                <div class="space-y-3">

                    @foreach($items->sortBy('urutan') as $item)

                        <div class="group
                                    flex flex-col
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                    gap-4
                                    p-4
                                    rounded-xl
                                    border border-[#fecaca]
                                    bg-white
                                    hover:bg-[#fdf2f2]
                                    hover:border-[#ddd4f1]
                                    transition">


                            {{-- =================================================
                                 NOMOR + NAMA
                            ================================================== --}}
                            <div class="flex items-center
                                        gap-4
                                        min-w-0">


                                {{-- NOMOR URUTAN --}}
                                <div class="w-10 h-10
                                            rounded-xl
                                            bg-[#fef2f2]
                                            text-[#da2424]
                                            flex items-center
                                            justify-center
                                            font-bold
                                            text-sm
                                            flex-shrink-0">

                                    {{ $item->urutan ?? $loop->iteration }}

                                </div>


                                {{-- NAMA --}}
                                <div class="min-w-0">

                                    <p class="font-semibold
                                              text-[#1f2937]
                                              truncate">

                                        {{ $item->nama_subkriteria }}

                                    </p>


                                    <div class="flex items-center
                                                gap-2
                                                mt-1">

                                        <span class="text-xs
                                                     text-[#6b7280]">

                                            Urutan {{ $item->urutan ?? '-' }}

                                        </span>


                                        <span class="text-[#d5d0df]">
                                            •
                                        </span>


                                        {{-- STATUS --}}
                                        @if($item->status === 'aktif')

                                            <span class="inline-flex
                                                         items-center
                                                         gap-1.5
                                                         text-xs
                                                         font-semibold
                                                         text-[#218653]">

                                                <span class="w-1.5 h-1.5
                                                             rounded-full
                                                             bg-[#2eaa6b]">
                                                </span>

                                                Aktif

                                            </span>

                                        @else

                                            <span class="inline-flex
                                                         items-center
                                                         gap-1.5
                                                         text-xs
                                                         font-semibold
                                                         text-[#6b7280]">

                                                <span class="w-1.5 h-1.5
                                                             rounded-full
                                                             bg-[#9993a8]">
                                                </span>

                                                Tidak Aktif

                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 AKSI
                            ================================================== --}}
                            <div class="flex items-center
                                        gap-2
                                        sm:flex-shrink-0">


                                {{-- LIHAT --}}
                                <a href="{{ route('sub-kriteria.show', $item->id) }}"
                                   class="inline-flex
                                          items-center
                                          justify-center
                                          gap-2
                                          px-3.5 py-2
                                          rounded-lg
                                          border border-[#ddd6f3]
                                          bg-white
                                          text-[#da2424]
                                          text-xs
                                          font-semibold
                                          hover:bg-[#f5f1ff]
                                          transition">

                                    <svg
                                        class="w-4 h-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            d="M2.5 12s3.5-6 9.5-6
                                               9.5 6 9.5 6-3.5 6-9.5 6
                                               -9.5-6-9.5-6z"
                                            stroke-linejoin="round"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="2.5"
                                        />

                                    </svg>

                                    Lihat

                                </a>


                                {{-- EDIT --}}
                                <a href="{{ route('sub-kriteria.edit', $item->id) }}"
                                   class="inline-flex
                                          items-center
                                          justify-center
                                          gap-2
                                          px-3.5 py-2
                                          rounded-lg
                                          bg-[#da2424]
                                          text-white
                                          text-xs
                                          font-semibold
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
                                            d="M16.5 3.5
                                               a2.1 2.1 0 013 3
                                               L8 18
                                               l-4 1
                                               1-4
                                               L16.5 3.5z"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />

                                    </svg>

                                    Edit

                                </a>


                                {{-- HAPUS --}}
                                <form
                                    action="{{ route('sub-kriteria.destroy', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus sub-kriteria ini?')"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex
                                               items-center
                                               justify-center
                                               gap-2
                                               px-3.5 py-2
                                               rounded-lg
                                               border border-[#f1caca]
                                               bg-white
                                               text-[#d14d4d]
                                               text-xs
                                               font-semibold
                                               hover:bg-[#fff5f5]
                                               transition"
                                    >

                                        <svg
                                            class="w-4 h-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                d="M4 7h16"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="M10 11v6"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="M14 11v6"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="M6 7l1 13h10l1-13"
                                                stroke-linejoin="round"
                                            />

                                            <path
                                                d="M9 7V4h6v3"
                                                stroke-linejoin="round"
                                            />

                                        </svg>

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 FOOTER CARD
            ================================================== --}}
            <div class="px-6 py-4
                        bg-[#fdf2f2]
                        border-t border-[#fecaca]">

                <div class="flex items-center
                            justify-between
                            gap-3">

                    <p class="text-xs
                              text-[#6b7280]">

                        {{ $items->count() }} sub-kriteria tersedia.

                    </p>


                    <a href="{{ route('sub-kriteria.create') }}"
                       class="text-xs
                              font-semibold
                              text-[#da2424]
                              hover:text-[#b91c1c]
                              transition">

                        + Tambah Sub-Kriteria

                    </a>

                </div>

            </div>

        </div>

    @empty


        {{-- =================================================
             EMPTY STATE
        ================================================== --}}
        <div class="bg-white
                    rounded-2xl
                    border border-[#f8c4c4]
                    shadow-sm
                    p-12
                    text-center">


            <div class="w-16 h-16
                        mx-auto
                        rounded-2xl
                        bg-[#fee2e2]
                        flex items-center
                        justify-center
                        text-[#da2424]">

                <svg
                    class="w-8 h-8"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                >
                    <rect
                        x="5"
                        y="3"
                        width="14"
                        height="18"
                        rx="2"
                    />

                    <path
                        d="M8 8h8"
                        stroke-linecap="round"
                    />

                    <path
                        d="M8 12h8"
                        stroke-linecap="round"
                    />

                    <path
                        d="M8 16h5"
                        stroke-linecap="round"
                    />

                </svg>

            </div>


            <h2 class="mt-5
                       text-lg
                       font-bold
                       text-[#1f2937]">

                Belum ada data Sub-Kriteria

            </h2>


            <p class="text-sm
                      text-[#6b7280]
                      mt-1">

                Silakan tambahkan sub-kriteria terlebih dahulu.

            </p>


            <a href="{{ route('sub-kriteria.create') }}"
               class="inline-flex
                      items-center
                      justify-center
                      gap-2
                      mt-5
                      px-5 py-3
                      rounded-xl
                      bg-[#da2424]
                      text-white
                      text-sm
                      font-semibold
                      hover:bg-[#770000]
                      transition">

                <svg
                    class="w-4 h-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        d="M12 5v14"
                        stroke-linecap="round"
                    />

                    <path
                        d="M5 12h14"
                        stroke-linecap="round"
                    />

                </svg>

                Tambah Sub-Kriteria

            </a>

        </div>

    @endforelse

</div>

@endsection