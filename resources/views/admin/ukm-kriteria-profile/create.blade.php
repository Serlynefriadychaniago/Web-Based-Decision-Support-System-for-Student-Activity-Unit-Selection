@extends('layouts.app')

@section('title', 'Pengaturan Profil Kriteria UKM')
@section('page-title', 'Pengaturan Profil Kriteria UKM')

@section('content')

{{-- =========================================================
     HEADER
========================================================= --}}

<div class="flex items-start justify-between mb-7">

    <div>

        <h1 class="text-2xl font-bold text-[#1f2937]">
            Pengaturan Profil Kriteria UKM
        </h1>

        <p class="text-sm text-[#6b7280] mt-1">
            Atur nilai profil kriteria untuk masing-masing UKM.
        </p>

    </div>


    <a href="{{ route('ukm-kriteria-profile.index') }}"
       class="inline-flex items-center gap-2
              px-5 py-2.5
              bg-white
              text-gray-700
              text-sm font-semibold
              rounded-xl
              border border-[#fecaca]
              hover:bg-[#fef2f2]
              hover:text-[#8B0000]
              transition">

        Kembali

        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="1.8">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M9 5l7 7-7 7"/>

        </svg>

    </a>

</div>


{{-- =========================================================
     ERROR
========================================================= --}}

@if ($errors->any())

    <div class="mb-6
                bg-red-50
                border border-red-200
                rounded-xl
                p-4">

        <p class="font-semibold text-red-700 mb-2">
            Data belum dapat disimpan.
        </p>

        <ul class="list-disc list-inside text-sm text-red-600">

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =========================================================
     INFORMASI SKALA
========================================================= --}}

<div class="mb-6
            bg-[#fdf2f2]
            border border-[#e4d9ff]
            rounded-2xl
            p-5">

    <div class="flex items-start gap-3">

        <div class="w-9 h-9
                    rounded-xl
                    bg-[#e7dcff]
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
                      d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>

            </svg>

        </div>


        <div>

            <h3 class="font-semibold text-[#1f2937]">
                Pengaturan Profil Kriteria UKM
            </h3>

            <p class="text-sm text-[#6b7280] mt-1">
                Setiap UKM memiliki profil kriteria masing-masing.
                Masukkan nilai sesuai tingkat relevansi UKM terhadap
                kriteria dan sub-kriteria yang tersedia.
            </p>

            <div class="mt-3 flex flex-wrap gap-3 text-xs">

                <span class="inline-flex items-center
                             px-3 py-1.5
                             rounded-full
                             bg-[#eadfff]
                             text-[#8B0000]
                             font-semibold">

                    C1 Minat: 1–7

                </span>


                <span class="inline-flex items-center
                             px-3 py-1.5
                             rounded-full
                             bg-[#eadfff]
                             text-[#8B0000]
                             font-semibold">

                    C2 Bakat: 1–7

                </span>


                <span class="inline-flex items-center
                             px-3 py-1.5
                             rounded-full
                             bg-[#eeeefc]
                             text-[#55527a]
                             font-semibold">

                    C3–C5: 0–5

                </span>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     FORM
========================================================= --}}

<form action="{{ route('ukm-kriteria-profile.store') }}"
      method="POST">

    @csrf


    {{-- =====================================================
         7 UKM
    ====================================================== --}}

    <div class="space-y-6">

        @foreach($ukm as $ukmItem)

            @php

                $ukmProfiles =
                    $existingProfiles->get($ukmItem->id, collect())
                    ->keyBy(function ($profile) {

                        return $profile->kriteria_id
                            . '-'
                            . $profile->sub_kriteria_id;

                    });

            @endphp


            {{-- =================================================
                 CARD UKM
            ================================================== --}}

            <div class="bg-white
                        rounded-2xl
                        border border-[#fecaca]
                        shadow-sm
                        overflow-hidden">


                {{-- =================================================
                     HEADER UKM
                ================================================== --}}

                <div class="px-5 py-4
                            bg-[#fdf2f2]
                            border-b border-[#fecaca]">

                    <div class="flex items-center gap-4">


                        {{-- NOMOR --}}

                        <div class="w-10 h-10
                                    rounded-xl
                                    bg-[#da2424]
                                    text-white
                                    flex items-center justify-center
                                    font-bold">

                            {{ $loop->iteration }}

                        </div>


                        {{-- LOGO --}}

                        <div class="w-12 h-12
                                    rounded-xl
                                    bg-[#fee2e2]
                                    overflow-hidden
                                    flex items-center justify-center
                                    text-[#8B0000]
                                    font-bold">

                            @if($ukmItem->logo)

                                <img src="{{ asset('storage/' . $ukmItem->logo) }}"
                                     alt="{{ $ukmItem->nama_ukm }}"
                                     class="w-full h-full object-cover">

                            @else

                                {{ strtoupper(substr($ukmItem->nama_ukm, 0, 1)) }}

                            @endif

                        </div>


                        {{-- NAMA UKM --}}

                        <div>

                            <h2 class="text-lg font-bold text-[#1f2937]">

                                {{ $ukmItem->nama_ukm }}

                            </h2>

                            <p class="text-xs text-[#6b7280] mt-0.5">

                                Profil kriteria UKM

                            </p>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     KRITERIA
                ================================================== --}}

                <div class="p-5 space-y-5">


                    @foreach($kriteria as $kriteriaItem)

                        @php

                            $kode =
                                strtoupper(trim($kriteriaItem->kode));

                            $isMinatBakat =
                                in_array($kode, ['C1', 'C2']);

                            $minValue =
                                $isMinatBakat ? 1 : 0;

                            $maxValue =
                                $isMinatBakat ? 7 : 5;

                            $subItems =
                                $subKriteriaByKriteria
                                ->get($kriteriaItem->id, collect());

                        @endphp


                        {{-- =================================================
                             CARD KRITERIA
                        ================================================== --}}

                        <div class="border
                                    border-[#fecaca]
                                    rounded-2xl
                                    overflow-hidden">


                            {{-- HEADER KRITERIA --}}

                            <div class="px-4 py-3
                                        bg-[#fdf2f2]
                                        border-b border-[#fecaca]">

                                <div class="flex items-center gap-3">

                                    <span class="inline-flex
                                                 items-center
                                                 justify-center
                                                 min-w-[34px]
                                                 h-7
                                                 px-2
                                                 rounded-lg
                                                 bg-[#fee2e2]
                                                 text-[#8B0000]
                                                 text-xs
                                                 font-bold">

                                        {{ $kode }}

                                    </span>


                                    <div>

                                        <h3 class="text-sm
                                                   font-bold
                                                   text-[#1f2937]">

                                            {{ $kriteriaItem->nama_kriteria }}

                                        </h3>

                                        <p class="text-xs
                                                  text-[#6b7280]">

                                            {{ $isMinatBakat
                                                ? 'Skala nilai 1–7'
                                                : 'Skala nilai 0–5'
                                            }}

                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 SUB KRITERIA
                            ================================================== --}}

                            @if($subItems->count() > 0)

                                <div>

                                    @foreach($subItems as $subItem)

                                        @php

                                            $profileKey =
                                                $kriteriaItem->id
                                                . '-'
                                                . $subItem->id;

                                            $existingProfile =
                                                $ukmProfiles
                                                ->get($profileKey);

                                            $oldValue =
                                                old(
                                                    "profiles.{$ukmItem->id}.{$kriteriaItem->id}.{$subItem->id}",
                                                    $existingProfile->nilai ?? ''
                                                );

                                        @endphp


                                        <div class="grid
                                                    grid-cols-1
                                                    md:grid-cols-[1fr_180px]
                                                    items-center
                                                    gap-4
                                                    px-4 py-4
                                                    border-b
                                                    border-[#fecaca]
                                                    last:border-b-0">


                                            {{-- NAMA SUB --}}

                                            <div>

                                                <p class="text-sm
                                                          font-medium
                                                          text-[#4b4767]">

                                                    {{ $subItem->nama_subkriteria }}

                                                </p>

                                            </div>


                                            {{-- NILAI --}}

                                            <div>

                                                <label class="block
                                                              text-xs
                                                              font-semibold
                                                              text-[#6b7280]
                                                              mb-1.5">

                                                    Nilai Profil

                                                </label>


                                                <input type="number"
                                                       name="profiles[{{ $ukmItem->id }}][{{ $kriteriaItem->id }}][{{ $subItem->id }}]"
                                                       value="{{ $oldValue }}"
                                                       min="{{ $minValue }}"
                                                       max="{{ $maxValue }}"
                                                       step="1"
                                                       required
                                                       placeholder="{{ $minValue }} - {{ $maxValue }}"
                                                       class="w-full
                                                              rounded-xl
                                                              border-[#d8d2e7]
                                                              text-sm
                                                              text-[#1f2937]
                                                              focus:border-[#8B0000]
                                                              focus:ring-[#8B0000]">

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                {{-- =============================================
                                     JIKA TIDAK ADA SUB-KRITERIA
                                ============================================== --}}

                                @php

                                    $profileKey =
                                        $kriteriaItem->id . '-0';

                                    $existingProfile =
                                        $ukmProfiles->get($profileKey);

                                    $oldValue =
                                        old(
                                            "profiles.{$ukmItem->id}.{$kriteriaItem->id}.0",
                                            $existingProfile->nilai ?? ''
                                        );

                                @endphp


                                <div class="px-4 py-4">

                                    <div class="grid
                                                grid-cols-1
                                                md:grid-cols-[1fr_180px]
                                                items-center
                                                gap-4">


                                        <div>

                                            <p class="text-sm
                                                      font-medium
                                                      text-[#4b4767]">

                                                Nilai {{ $kriteriaItem->nama_kriteria }}

                                            </p>

                                        </div>


                                        <div>

                                            <label class="block
                                                          text-xs
                                                          font-semibold
                                                          text-[#6b7280]
                                                          mb-1.5">

                                                Nilai Profil

                                            </label>


                                            <input type="number"
                                                   name="profiles[{{ $ukmItem->id }}][{{ $kriteriaItem->id }}][0]"
                                                   value="{{ $oldValue }}"
                                                   min="{{ $minValue }}"
                                                   max="{{ $maxValue }}"
                                                   step="1"
                                                   required
                                                   placeholder="{{ $minValue }} - {{ $maxValue }}"
                                                   class="w-full
                                                          rounded-xl
                                                          border-[#d8d2e7]
                                                          text-sm
                                                          text-[#1f2937]
                                                          focus:border-[#8B0000]
                                                          focus:ring-[#8B0000]">

                                        </div>

                                    </div>

                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            </div>

        @endforeach

    </div>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <div class="mt-6
                bg-white
                rounded-2xl
                border border-[#fecaca]
                shadow-sm
                px-6 py-5">

        <div class="flex
                    flex-col
                    sm:flex-row
                    items-center
                    justify-between
                    gap-4">


            <div>

                <p class="text-sm
                          font-semibold
                          text-[#1f2937]">

                    Profil UKM

                </p>

                <p class="text-xs
                          text-[#6b7280]
                          mt-1">

                    Pastikan seluruh nilai profil sudah sesuai
                    sebelum menyimpan.

                </p>

            </div>


            <div class="flex items-center gap-3">


                <a href="{{ route('ukm-kriteria-profile.index') }}"
                   class="px-5 py-2.5
                          bg-white
                          border border-gray-300
                          text-gray-700
                          text-sm
                          font-semibold
                          rounded-xl
                          hover:bg-gray-50
                          transition">

                    Batal

                </a>


                <button type="submit"
                        class="inline-flex
                               items-center
                               gap-2
                               px-6 py-2.5
                               bg-[#da2424]
                               text-white
                               text-sm
                               font-semibold
                               rounded-xl
                               hover:bg-[#770000]
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

                    Simpan Semua Profil

                </button>

            </div>

        </div>

    </div>

</form>

@endsection