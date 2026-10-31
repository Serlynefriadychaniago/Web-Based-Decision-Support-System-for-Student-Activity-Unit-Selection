@extends('layouts.app')

@section('title', 'Profil Kriteria UKM')
@section('page-title', 'Profil Kriteria UKM')

@section('content')

<div class="mb-7">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Profil Kriteria UKM
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Kelola karakteristik dan profil kriteria masing-masing UKM.
            </p>

        </div>

    </div>

</div>


{{-- =========================================================
     SUCCESS
========================================================== --}}

@if(session('success'))
    @php
        $isDelete = str_contains(strtolower(session('success')), 'hapus');
    @endphp

    @if($isDelete)
        <div class="mb-6 flex items-center gap-3 bg-[#fef2f2] border border-[#fecaca] text-[#da2424] rounded-xl px-5 py-4 shadow-sm">
            <span class="w-7 h-7 rounded-lg bg-[#fee2e2] flex items-center justify-center font-bold text-sm text-[#da2424] flex-shrink-0">
                🗑️
            </span>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @else
        <div class="mb-6
                    flex items-center gap-3
                    bg-green-50
                    border border-green-200
                    text-green-700
                    rounded-xl
                    px-4 py-3">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5 flex-shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>

            </svg>

            <p class="text-sm font-medium">
                {{ session('success') }}
            </p>

        </div>
    @endif
@endif


{{-- =========================================================
     ERROR
========================================================== --}}

@if(isset($errors) && $errors->any())

    <div class="mb-6
                bg-red-50
                border border-red-200
                text-red-700
                rounded-xl
                p-4">

        <p class="font-semibold mb-2">
            Data belum dapat diproses.
        </p>

        <ul class="list-disc list-inside text-sm">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =========================================================
     INFO
========================================================== --}}

<div class="mb-6
            bg-[#fef2f2]
            border border-[#fecaca]
            rounded-2xl
            p-5">

    <div class="flex gap-3">

        <div class="w-9 h-9
                    rounded-xl
                    bg-[#fee2e2]
                    text-[#8B0000]
                    flex items-center
                    justify-center
                    flex-shrink-0">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"/>

            </svg>

        </div>


        <div>

            <p class="text-sm font-bold text-[#1f2937]">
                Pengaturan Profil Kriteria UKM
            </p>

            <p class="text-xs text-[#6b7280] mt-1 leading-5">

                Setiap UKM memiliki profil kriteria masing-masing.
                Gunakan tombol <strong>Kelola UKM</strong> untuk mengisi
                atau mengubah nilai sub-kriteria.
                Gunakan tombol <strong>Detail</strong> untuk melihat
                karakteristik UKM berdasarkan nilai tertinggi pada
                setiap kriteria.

            </p>

        </div>

    </div>

</div>


{{-- =========================================================
     GRID UKM
========================================================== --}}

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

    @forelse($ukm as $item)

        @php

            $profileCount = isset($profiles[$item->id])
                ? $profiles[$item->id]->count()
                : 0;

        @endphp


        {{-- =================================================
             CARD UKM
        ================================================== --}}

        <div class="bg-white
                    border border-[#fecaca]
                    rounded-2xl
                    shadow-sm
                    hover:shadow-md
                    transition
                    overflow-hidden">


            {{-- HEADER CARD --}}

            <div class="p-5">

                <div class="flex items-start gap-4">


                    {{-- LOGO --}}

                    <div class="w-14 h-14
                                rounded-2xl
                                bg-[#fee2e2]
                                text-[#8B0000]
                                flex items-center
                                justify-center
                                overflow-hidden
                                flex-shrink-0
                                font-bold
                                text-xl">

                        @if(!empty($item->logo))

                            <img
                                src="{{ asset('storage/' . $item->logo) }}"
                                alt="{{ $item->nama_ukm }}"
                                class="w-full h-full object-cover"
                            >

                        @else

                            {{ strtoupper(substr($item->nama_ukm, 0, 1)) }}

                        @endif

                    </div>


                    {{-- NAMA UKM --}}

                    <div class="min-w-0">

                        <h2 class="font-bold
                                   text-[#1f2937]
                                   text-base
                                   leading-6">

                            {{ $item->nama_ukm }}

                        </h2>

                        <p class="text-xs
                                  text-[#6b7280]
                                  mt-1">

                            Profil Kriteria UKM

                        </p>

                    </div>

                </div>

            </div>


            {{-- STATUS --}}

            <div class="px-5 pb-5">

                <div class="flex items-center justify-between
                            bg-[#fdf2f2]
                            border border-[#fecaca]
                            rounded-xl
                            px-4 py-3">

                    <div>

                        <p class="text-xs text-[#6b7280]">
                            Profil tersimpan
                        </p>

                        <p class="text-sm
                                  font-bold
                                  text-[#1f2937]
                                  mt-1">

                            {{ $profileCount }} data

                        </p>

                    </div>


                    @if($profileCount > 0)

                        <span class="inline-flex
                                     items-center
                                     px-3 py-1
                                     rounded-full
                                     bg-green-100
                                     text-green-700
                                     text-xs
                                     font-semibold">

                            Sudah diisi

                        </span>

                    @else

                        <span class="inline-flex
                                     items-center
                                     px-3 py-1
                                     rounded-full
                                     bg-gray-100
                                     text-gray-600
                                     text-xs
                                     font-semibold">

                            Belum diisi

                        </span>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 BUTTON AREA
            ================================================== --}}

            <div class="px-5 py-4
                        bg-[#fdf2f2]
                        border-t border-[#fecaca]
                        flex gap-2">


                {{-- DETAIL --}}

                <a
                    href="{{ route(
                        'ukm-kriteria-profile.show-ukm',
                        ['ukm' => $item->id]
                    ) }}"
                    class="flex-1
                           inline-flex
                           items-center
                           justify-center
                           gap-2
                           px-3 py-2.5
                           bg-white
                           border border-[#f8c4c4]
                           text-[#8B0000]
                           text-sm
                           font-semibold
                           rounded-xl
                           hover:bg-[#fdf2f2]
                           transition"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                        <circle cx="12"
                                cy="12"
                                r="3"/>

                    </svg>

                    Detail

                </a>


                {{-- KELOLA --}}

                <a
                    href="{{ route(
                        'ukm-kriteria-profile.manage',
                        ['ukm' => $item->id]
                    ) }}"
                    class="flex-1
                           inline-flex
                           items-center
                           justify-center
                           gap-2
                           px-3 py-2.5
                           bg-[#da2424]
                           text-white
                           text-sm
                           font-semibold
                           rounded-xl
                           hover:bg-[#770000]
                           transition"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>

                    </svg>

                    Kelola

                </a>

            </div>

        </div>


    @empty

        <div class="col-span-full
                    bg-white
                    border border-[#fecaca]
                    rounded-2xl
                    p-10
                    text-center">

            <p class="text-sm
                      font-semibold
                      text-[#1f2937]">

                Belum ada data UKM.

            </p>

            <p class="text-xs
                      text-[#6b7280]
                      mt-1">

                Tambahkan data UKM terlebih dahulu.

            </p>

        </div>

    @endforelse

</div>

@endsection