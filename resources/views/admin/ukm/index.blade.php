@extends('layouts.app')

@section('title', 'Data UKM')
@section('page-title', 'Data UKM')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-[#1f2937]">
                Data Unit Kegiatan Mahasiswa
            </h1>

            <p class="text-sm text-[#6b7280] mt-2">
                Kelola data UKM Universitas Muhammadiyah Cirebon.
            </p>

        </div>


        <a href="{{ route('ukm.create') }}"
           class="inline-flex items-center justify-center gap-2
                  px-5 py-3 rounded-xl
                  bg-[#da2424] text-white
                  font-semibold text-sm
                  hover:bg-[#b91c1c]
                  shadow-sm transition">

            <span class="text-lg">
                +
            </span>

            Tambah UKM

        </a>

    </div>


    {{-- SUCCESS MESSAGE --}}
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


    {{-- ERROR MESSAGE --}}
    @if(session('error'))

        <div class="mb-6 flex items-center gap-3
                    bg-[#fff0f1] border border-[#ffd6da]
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


    {{-- CARD TABLE --}}
    <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm overflow-hidden">


        {{-- CARD HEADER --}}
        <div class="px-6 py-5 border-b border-[#fecaca]
                    flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h2 class="text-lg font-bold text-[#1f2937]">
                    Daftar UKM
                </h2>

                <p class="text-xs text-[#6b7280] mt-1">
                    Total {{ $ukm->count() }} UKM terdaftar
                </p>

            </div>


            <div class="text-sm text-[#6b7280]">
                Data Unit Kegiatan Mahasiswa
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
                            UKM
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-[#6b7280] uppercase">
                            Pembina
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-[#6b7280] uppercase">
                            Ketua
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-[#6b7280] uppercase">
                            Kontak
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-[#6b7280] uppercase">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-[#6b7280] uppercase">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-[#f0edf7]">

                    @forelse($ukm as $item)

                        <tr class="hover:bg-[#fcfbff] transition">


                            {{-- NO --}}
                            <td class="px-6 py-5 text-sm text-[#6b7280]">
                                {{ $loop->iteration }}
                            </td>


                            {{-- UKM --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-3">

                                    <div class="w-11 h-11 rounded-xl bg-[#fee2e2]
                                                flex items-center justify-center
                                                text-[#da2424] font-bold overflow-hidden">

                                        @if($item->logo)

                                            <img src="{{ asset('storage/'.$item->logo) }}"
                                                 alt="Logo {{ $item->nama_ukm }}"
                                                 class="w-full h-full object-cover">

                                        @else

                                            {{ strtoupper(substr($item->nama_ukm, 0, 1)) }}

                                        @endif

                                    </div>


                                    <div>

                                        <p class="font-semibold text-[#1f2937]">
                                            {{ $item->nama_ukm }}
                                        </p>

                                        <p class="text-xs text-[#6b7280] mt-1">
                                            {{ $item->email ?? 'Email belum tersedia' }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- PEMBINA --}}
                            <td class="px-6 py-5 text-sm text-[#55506f]">
                                {{ $item->pembina ?? '-' }}
                            </td>


                            {{-- KETUA --}}
                            <td class="px-6 py-5 text-sm text-[#55506f]">
                                {{ $item->ketua ?? '-' }}
                            </td>


                            {{-- KONTAK --}}
                            <td class="px-6 py-5 text-sm text-[#55506f]">
                                {{ $item->kontak ?? '-' }}
                            </td>


                            {{-- STATUS --}}
                            <td class="px-6 py-5 text-center">

                                @if($item->status === 'aktif')

                                    <span class="inline-flex items-center gap-1
                                                 px-3 py-1.5 rounded-full
                                                 bg-[#e8f8f1]
                                                 text-[#258260]
                                                 text-xs font-semibold">

                                        <span class="w-1.5 h-1.5 rounded-full bg-[#38a878]"></span>

                                        Aktif

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1
                                                 px-3 py-1.5 rounded-full
                                                 bg-[#fff0f1]
                                                 text-[#c95d68]
                                                 text-xs font-semibold">

                                        <span class="w-1.5 h-1.5 rounded-full bg-[#dc6975]"></span>

                                        Nonaktif

                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- DETAIL --}}
                                    <a href="{{ route('ukm.show', $item) }}"
                                       class="px-3 py-2 rounded-lg
                                              bg-[#eef4ff]
                                              text-[#4267b2]
                                              text-xs font-semibold
                                              hover:bg-[#dfeaff]
                                              transition">

                                        Detail

                                    </a>


                                    {{-- EDIT --}}
                                    <a href="{{ route('ukm.edit', $item) }}"
                                       class="px-3 py-2 rounded-lg
                                              bg-[#fee2e2]
                                              text-[#da2424]
                                              text-xs font-semibold
                                              hover:bg-[#dfd4ff]
                                              transition">

                                        Edit

                                    </a>


                                    {{-- HAPUS --}}
                                    <form action="{{ route('ukm.destroy', $item) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus UKM ini?')">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-3 py-2 rounded-lg
                                                       bg-[#fff0f1]
                                                       text-[#c95d68]
                                                       text-xs font-semibold
                                                       hover:bg-[#ffe1e4]
                                                       transition">

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-6 py-16 text-center">

                                <div class="mx-auto w-16 h-16 rounded-2xl bg-[#f1edff]
                                            flex items-center justify-center
                                            text-[#da2424] text-2xl">

                                    ▣

                                </div>

                                <h3 class="mt-4 font-semibold text-[#1f2937]">
                                    Belum ada data UKM
                                </h3>

                                <p class="text-sm text-[#6b7280] mt-1">
                                    Silakan tambahkan data UKM terlebih dahulu.
                                </p>

                                <a href="{{ route('ukm.create') }}"
                                   class="inline-flex mt-5 px-5 py-2.5
                                          rounded-xl bg-[#da2424]
                                          text-white text-sm font-semibold
                                          hover:bg-[#b91c1c]">

                                    + Tambah UKM

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