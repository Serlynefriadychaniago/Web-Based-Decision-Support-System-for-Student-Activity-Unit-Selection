@extends('layouts.app')

@section('title', 'Data Kriteria')
@section('page-title', 'Kriteria')

@section('content')

<div class="max-w-7xl mx-auto">


    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-[#1f2937]">
                Data Kriteria
            </h1>

            <p class="text-sm text-[#6b7280] mt-2">
                Kelola kriteria yang digunakan dalam proses AHP dan TOPSIS.
            </p>

        </div>


        <a href="{{ route('kriteria.create') }}"
           class="inline-flex items-center justify-center gap-2
                  px-5 py-3 rounded-xl
                  bg-[#da2424] text-white
                  font-semibold text-sm
                  hover:bg-[#b91c1c]
                  shadow-sm transition">

            <span class="text-lg">
                +
            </span>

            Tambah Kriteria

        </a>

    </div>


    {{-- SUCCESS --}}
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

                    <span class="text-sm font-semibold">
                        {{ session('success') }}
                    </span>
                </div>

            </div>
        @else
            <div class="mb-6 flex items-center justify-between gap-3
                        bg-[#eaf8f2] border border-[#c9eadc]
                        text-[#258260]
                        rounded-xl px-5 py-4 shadow-sm">

                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-[#d1f2e4] flex items-center justify-center font-bold text-sm text-[#258260] flex-shrink-0">
                        ✓
                    </span>

                    <span class="text-sm font-medium">
                        {{ session('success') }}
                    </span>
                </div>

            </div>
        @endif
    @endif


    {{-- ERROR SESSION --}}
    @if(session('error'))

        <div class="mb-6 flex items-center gap-3
                    bg-[#fff0f1]
                    border border-[#ffd6da]
                    text-[#d93848]
                    rounded-xl px-5 py-4">

            <span class="font-bold">
                ✕
            </span>

            <span class="text-sm font-medium">
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- ERROR --}}
    @if(isset($errors) && $errors->any())

        <div class="mb-6 bg-[#fff0f1]
                    border border-[#ffd6da]
                    text-[#c95d68]
                    rounded-xl px-5 py-4">

            <ul class="list-disc list-inside text-sm space-y-1">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- INFO CARD --}}
    <div class="mb-6 rounded-2xl
                bg-gradient-to-r from-[#fee2e2] to-[#f8f5ff]
                border border-[#dfd4ff]
                p-5">

        <div class="flex items-start gap-4">

            <div class="w-11 h-11 rounded-xl bg-white
                        flex items-center justify-center
                        text-[#da2424] font-bold shadow-sm">

                AHP

            </div>

            <div>

                <h3 class="font-semibold text-[#1f2937]">
                    Kriteria Penilaian
                </h3>

                <p class="text-sm text-[#6b7280] mt-1 leading-6">
                    Kriteria digunakan sebagai dasar pembobotan AHP
                    dan proses perangkingan TOPSIS.
                </p>

            </div>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="bg-white rounded-2xl
                border border-[#f8c4c4]
                shadow-sm overflow-hidden">


        {{-- HEADER TABLE --}}
        <div class="px-6 py-5
                    border-b border-[#fecaca]
                    flex items-center justify-between">

            <div>

                <h2 class="text-lg font-bold text-[#1f2937]">
                    Daftar Kriteria
                </h2>

                <p class="text-xs text-[#6b7280] mt-1">
                    Total {{ $kriteria->count() }} kriteria
                </p>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-[#fdf2f2]">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold text-[#6b7280] uppercase">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-[#6b7280] uppercase">
                            Kode
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-[#6b7280] uppercase">
                            Nama Kriteria
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-[#6b7280] uppercase">
                            Tipe
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-[#6b7280] uppercase">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#f0edf7]">

                    @forelse($kriteria as $item)

                        <tr class="hover:bg-[#fcfbff] transition">


                            {{-- NO --}}
                            <td class="px-6 py-5 text-sm text-[#6b7280]">
                                {{ $loop->iteration }}
                            </td>


                            {{-- KODE --}}
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center
                                             px-3 py-1.5 rounded-lg
                                             bg-[#fee2e2]
                                             text-[#da2424]
                                             text-xs font-bold">

                                    {{ $item->kode }}

                                </span>

                            </td>


                            {{-- NAMA --}}
                            <td class="px-6 py-5">

                                <p class="text-sm font-semibold text-[#1f2937]">

                                    {{ $item->nama_kriteria }}

                                </p>

                                <p class="text-xs text-[#9993ae] mt-1">

                                    Digunakan dalam perhitungan SPK

                                </p>

                            </td>


                            {{-- TIPE --}}
                            <td class="px-6 py-5 text-center">

                                @if($item->tipe === 'benefit')

                                    <span class="inline-flex items-center gap-1
                                                 px-3 py-1.5 rounded-full
                                                 bg-[#e8f8f1]
                                                 text-[#258260]
                                                 text-xs font-semibold">

                                        <span class="w-1.5 h-1.5 rounded-full bg-[#38a878]"></span>

                                        Benefit

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1
                                                 px-3 py-1.5 rounded-full
                                                 bg-[#fff0f1]
                                                 text-[#c95d68]
                                                 text-xs font-semibold">

                                        <span class="w-1.5 h-1.5 rounded-full bg-[#dc6975]"></span>

                                        Cost

                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center justify-center gap-2">

                                    <a href="{{ route('kriteria.edit', $item) }}"
                                       class="px-3 py-2 rounded-lg
                                              bg-[#fee2e2]
                                              text-[#da2424]
                                              text-xs font-semibold
                                              hover:bg-[#dfd4ff]">

                                        Edit

                                    </a>


                                    <form action="{{ route('kriteria.destroy', $item) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg
                                                       bg-[#fff0f1]
                                                       text-[#c95d68]
                                                       text-xs font-semibold
                                                       hover:bg-[#ffe1e4]">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="5" class="px-6 py-16 text-center">

                                <div class="mx-auto w-16 h-16 rounded-2xl
                                            bg-[#f1edff]
                                            flex items-center justify-center
                                            text-[#da2424] text-2xl">

                                    ☷

                                </div>

                                <h3 class="mt-4 font-semibold text-[#1f2937]">
                                    Belum ada data kriteria
                                </h3>

                                <p class="text-sm text-[#6b7280] mt-1">
                                    Silakan tambahkan kriteria terlebih dahulu.
                                </p>

                                <a href="{{ route('kriteria.create') }}"
                                   class="inline-flex mt-5
                                          px-5 py-2.5 rounded-xl
                                          bg-[#da2424]
                                          text-white
                                          text-sm font-semibold
                                          hover:bg-[#b91c1c]">

                                    + Tambah Kriteria

                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection