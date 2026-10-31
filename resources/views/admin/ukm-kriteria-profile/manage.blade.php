@extends('layouts.app')

@section('title', 'Kelola Profil Kriteria UKM')
@section('page-title', 'Kelola Profil Kriteria UKM')

@section('content')

{{-- =========================================================
     HEADER
========================================================= --}}

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-7">

    <div>

        <div class="flex items-center gap-3">

            <a href="{{ route('ukm-kriteria-profile.index') }}"
               class="w-10 h-10
                      inline-flex
                      items-center
                      justify-center
                      rounded-xl
                      bg-white
                      border border-[#fecaca]
                      text-gray-600
                      hover:bg-[#fef2f2]
                      hover:text-[#8B0000]
                      transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>

                </svg>

            </a>

            <div>

                <h1 class="text-2xl font-bold text-[#1f2937]">
                    Kelola Profil Kriteria UKM
                </h1>

                <p class="text-sm text-[#6b7280] mt-1">
                    Atur nilai profil kriteria untuk UKM ini.
                </p>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     SUCCESS
========================================================= --}}

@if(session('success'))

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


{{-- =========================================================
     ERROR
========================================================= --}}

@if($errors->any())

    <div class="mb-6
                bg-red-50
                border border-red-200
                text-red-700
                rounded-xl
                p-4">

        <p class="font-semibold mb-2">
            Data belum dapat disimpan.
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
     INFORMASI UKM
========================================================= --}}

<div class="bg-white
            border border-[#fecaca]
            rounded-2xl
            shadow-sm
            overflow-hidden
            mb-6">

    <div class="p-6">

        <div class="flex items-center gap-4">

            {{-- LOGO --}}

            <div class="w-16 h-16
                        rounded-2xl
                        bg-[#fee2e2]
                        text-[#8B0000]
                        flex items-center justify-center
                        overflow-hidden
                        flex-shrink-0
                        font-bold
                        text-2xl">

                @if(!empty($ukm->logo))

                    <img src="{{ asset('storage/' . $ukm->logo) }}"
                         alt="{{ $ukm->nama_ukm }}"
                         class="w-full h-full object-cover">

                @else

                    {{ strtoupper(substr($ukm->nama_ukm, 0, 1)) }}

                @endif

            </div>


            {{-- INFORMASI --}}

            <div>

                <p class="text-xs
                          uppercase
                          tracking-wide
                          font-semibold
                          text-[#6b7280]">

                    UKM yang sedang dikelola

                </p>

                <h2 class="text-xl
                           font-bold
                           text-[#1f2937]
                           mt-1">

                    {{ $ukm->nama_ukm }}

                </h2>

                <p class="text-sm
                          text-[#6b7280]
                          mt-1">

                    Pengaturan profil kriteria untuk proses TOPSIS.

                </p>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     INFORMASI SKALA
========================================================= --}}

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
                    flex items-center justify-center
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

            <p class="text-sm
                      font-bold
                      text-[#1f2937]">

                Petunjuk Nilai Profil

            </p>


            <p class="text-xs
                      text-[#6b7280]
                      mt-1
                      leading-5">

                Nilai profil digunakan untuk menggambarkan tingkat
                relevansi UKM terhadap setiap sub-kriteria.

            </p>


            <div class="flex flex-wrap gap-2 mt-3">

                <span class="px-3 py-1
                             rounded-full
                             bg-red-100
                             text-red-700
                             text-xs
                             font-semibold">

                    C1 Minat: 1–5

                </span>


                <span class="px-3 py-1
                             rounded-full
                             bg-red-100
                             text-red-700
                             text-xs
                             font-semibold">

                    C2 Bakat: 1–5

                </span>


                <span class="px-3 py-1
                             rounded-full
                             bg-blue-100
                             text-blue-700
                             text-xs
                             font-semibold">

                    C3 Jadwal: 1–5

                </span>


                <span class="px-3 py-1
                             rounded-full
                             bg-blue-100
                             text-blue-700
                             text-xs
                             font-semibold">

                    C4 Prestasi: 1–5

                </span>


                <span class="px-3 py-1
                             rounded-full
                             bg-blue-100
                             text-blue-700
                             text-xs
                             font-semibold">

                    C5 Biaya: 1–5

                </span>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     FORM
========================================================= --}}

<form action="{{ route('ukm-kriteria-profile.manage.save', $ukm->id) }}"
      method="POST">

    @csrf


    {{-- =====================================================
         KRITERIA
    ====================================================== --}}

    <div class="space-y-6">

        @forelse($kriteria as $item)

            @php

                /*
                |--------------------------------------------------------------------------
                | PENENTUAN SKALA NILAI
                |--------------------------------------------------------------------------
                |
                | C1 Minat    = 1–7
                | C2 Bakat    = 1–7
                | C3 Jadwal   = 0–5
                | C4 Prestasi = 0–5
                | C5 Biaya    = 0–5
                |
                */

                $kodeKriteria = strtoupper(
                    trim($item->kode ?? '')
                );

                $minNilai = 1;
                $maxNilai = 5;

                $subItems = $item->subKriteria;

            @endphp


            {{-- =================================================
                 CARD KRITERIA
            ================================================== --}}

            <div class="bg-white
                        border border-[#fecaca]
                        rounded-2xl
                        shadow-sm
                        overflow-hidden">


                {{-- HEADER KRITERIA --}}

                <div class="px-6 py-5
                            border-b border-[#fecaca]
                            bg-[#fdf2f2]">

                    <div class="flex items-center justify-between gap-4">

                        <div>

                            <div class="flex items-center gap-3">

                                <span class="inline-flex
                                             items-center
                                             justify-center
                                             min-w-[48px]
                                             px-3 py-1.5
                                             rounded-lg
                                             bg-[#fee2e2]
                                             text-[#8B0000]
                                             text-sm
                                             font-bold">

                                    {{ $item->kode }}

                                </span>


                                <h2 class="text-lg
                                           font-bold
                                           text-[#1f2937]">

                                    {{ $item->nama_kriteria }}

                                </h2>

                            </div>


                            <p class="text-xs
                                      text-[#6b7280]
                                      mt-2">

                                Nilai profil:

                                <strong class="text-[#8B0000]">

                                    {{ $minNilai }}–{{ $maxNilai }}

                                </strong>

                            </p>

                        </div>


                        {{-- TIPE KRITERIA --}}

                        @if(($item->tipe ?? '') === 'benefit')

                            <span class="inline-flex
                                         px-3 py-1
                                         rounded-full
                                         bg-green-100
                                         text-green-700
                                         text-xs
                                         font-semibold">

                                Benefit

                            </span>

                        @elseif(($item->tipe ?? '') === 'cost')

                            <span class="inline-flex
                                         px-3 py-1
                                         rounded-full
                                         bg-red-100
                                         text-red-700
                                         text-xs
                                         font-semibold">

                                Cost

                            </span>

                        @endif

                    </div>

                </div>


                {{-- =================================================
                     SUB KRITERIA
                ================================================== --}}

                <div class="p-6">

                    @if($subItems->count() > 0)

                        <div class="space-y-3">

                            @foreach($subItems as $sub)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | NILAI TERSIMPAN
                                    |--------------------------------------------------------------------------
                                    |
                                    | Controller mengirim:
                                    |
                                    | $existingProfiles[
                                    |     kriteria_id
                                    | ][
                                    |     sub_kriteria_id
                                    | ]
                                    |
                                    */

                                    $savedValue =
                                        $existingProfiles[$item->id][$sub->id]
                                        ?? '';


                                    /*
                                    |--------------------------------------------------------------------------
                                    | NILAI OLD INPUT
                                    |--------------------------------------------------------------------------
                                    */

                                    $oldValue = old(
                                        "nilai.{$item->id}.{$sub->id}",
                                        $savedValue
                                    );

                                @endphp


                                {{-- SUB KRITERIA ROW --}}

                                <div class="grid grid-cols-1
                                            md:grid-cols-[1fr_180px]
                                            gap-4
                                            items-center
                                            p-4
                                            bg-[#fdf2f2]
                                            border border-[#fecaca]
                                            rounded-xl">


                                    {{-- NAMA SUB KRITERIA --}}

                                    <div>

                                        <div class="flex items-start gap-3">

                                            <span class="flex-shrink-0
                                                         w-8 h-8
                                                         rounded-lg
                                                         bg-white
                                                         border border-[#e2dcef]
                                                         text-[#8B0000]
                                                         flex items-center justify-center
                                                         text-xs
                                                         font-bold">

                                                {{ $sub->urutan }}

                                            </span>


                                            <div>

                                                <p class="text-sm
                                                          font-semibold
                                                          text-[#1f2937]">

                                                    {{ $sub->nama_subkriteria }}

                                                </p>


                                                <p class="text-xs
                                                          text-[#6b7280]
                                                          mt-1">

                                                    Sub-kriteria
                                                    {{ $sub->urutan }}

                                                </p>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- NILAI PROFIL --}}

                                    <div>

                                        <label class="block
                                                      text-xs
                                                      font-semibold
                                                      text-[#6b7280]
                                                      mb-1.5">

                                            Nilai Profil

                                        </label>


                                        <input
                                            type="number"
                                            name="nilai[{{ $item->id }}][{{ $sub->id }}]"
                                            value="{{ $oldValue }}"
                                            min="{{ $minNilai }}"
                                            max="{{ $maxNilai }}"
                                            step="1"
                                            placeholder="{{ $minNilai }}–{{ $maxNilai }}"
                                            inputmode="numeric"
                                            class="w-full
                                                   rounded-xl
                                                   border-gray-300
                                                   text-center
                                                   font-semibold
                                                   text-[#1f2937]
                                                   focus:border-red-500
                                                   focus:ring-red-500">


                                        <p class="text-[11px]
                                                  text-[#6b7280]
                                                  mt-1
                                                  text-center">

                                            Skala {{ $minNilai }}–{{ $maxNilai }}

                                        </p>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        {{-- =================================================
                             TIDAK ADA SUB KRITERIA
                        ================================================== --}}

                        <div class="py-8
                                    text-center
                                    bg-[#fdf2f2]
                                    rounded-xl
                                    border border-dashed
                                    border-[#ddd7ed]">

                            <div class="w-12 h-12
                                        mx-auto
                                        rounded-xl
                                        bg-[#fef2f2]
                                        text-[#6b7280]
                                        flex items-center justify-center">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-6 h-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.63 19.7h14.74a2 2 0 001.74-3.02l-7.4-12.82a2 2 0 00-3.42 0z"/>

                                </svg>

                            </div>


                            <p class="text-sm
                                      font-semibold
                                      text-[#1f2937]
                                      mt-3">

                                Belum terdapat sub-kriteria untuk
                                {{ $item->nama_kriteria }}.

                            </p>


                            <p class="text-xs
                                      text-[#6b7280]
                                      mt-1">

                                Tambahkan sub-kriteria melalui menu
                                Sub-Kriteria.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        @empty

            {{-- =================================================
                 TIDAK ADA KRITERIA
            ================================================== --}}

            <div class="bg-white
                        border border-[#fecaca]
                        rounded-2xl
                        p-10
                        text-center">

                <div class="w-14 h-14
                            mx-auto
                            rounded-2xl
                            bg-[#fee2e2]
                            text-[#8B0000]
                            flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-7 h-7"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 6v12m6-6H6"/>

                    </svg>

                </div>


                <p class="text-sm
                          font-bold
                          text-[#1f2937]
                          mt-4">

                    Belum ada kriteria.

                </p>


                <p class="text-xs
                          text-[#6b7280]
                          mt-1">

                    Tambahkan data kriteria terlebih dahulu.

                </p>

            </div>

        @endforelse

    </div>


    {{-- =========================================================
         FOOTER BUTTON
    ========================================================== --}}

    <div class="mt-6
                bg-white
                border border-[#fecaca]
                rounded-2xl
                shadow-sm
                px-6 py-5">

        <div class="flex flex-col
                    sm:flex-row
                    items-center
                    justify-between
                    gap-3">


            {{-- KEMBALI --}}

            <a href="{{ route('ukm-kriteria-profile.index') }}"
               class="w-full sm:w-auto
                      inline-flex
                      items-center
                      justify-center
                      gap-2
                      px-5 py-2.5
                      bg-white
                      border border-[#fecaca]
                      text-gray-700
                      text-sm
                      font-semibold
                      rounded-xl
                      hover:bg-[#fef2f2]
                      hover:text-[#8B0000]
                      transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>

                </svg>

                Kembali

            </a>


            {{-- SIMPAN --}}

            <button type="submit"
                    class="w-full sm:w-auto
                           inline-flex
                           items-center
                           justify-center
                           gap-2
                           px-6 py-2.5
                           bg-[#da2424]
                           text-white
                           text-sm
                           font-semibold
                           rounded-xl
                           hover:bg-[#770000]
                           shadow-sm
                           transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>

                </svg>

                Simpan Perubahan

            </button>

        </div>

    </div>

</form>

@endsection