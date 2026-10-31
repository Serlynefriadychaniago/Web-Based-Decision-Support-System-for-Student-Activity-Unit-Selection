@extends('layouts.app')

@section('title', 'Perhitungan TOPSIS')
@section('page-title', 'Perhitungan TOPSIS')

@section('content')

<div class="page-content">

    {{-- =====================================================
        HELPER FORMAT ANGKA
    ====================================================== --}}
    @php
        $formatNumber = function ($value, $decimals = 4) {

            if ($value === null || $value === '') {
                return '-';
            }

            $formatted = number_format(
                (float) $value,
                $decimals,
                ',',
                ''
            );

            $formatted = rtrim($formatted, '0');
            $formatted = rtrim($formatted, ',');

            return $formatted;
        };

        /*
        |--------------------------------------------------------------------------
        | DATA VIEW
        |--------------------------------------------------------------------------
        */

        $ukmData = isset($ukm)
            ? $ukm
            : (isset($ukms)
                ? $ukms
                : (isset($alternatives)
                    ? $alternatives
                    : collect()));

        $criteriaData = isset($criteria)
            ? $criteria
            : (isset($kriteria)
                ? $kriteria
                : collect());

        $decisionData = isset($decisionMatrix)
            ? $decisionMatrix
            : [];

        /*
        |--------------------------------------------------------------------------
        | BOBOT AHP
        |--------------------------------------------------------------------------
        */

        $topsisWeights = isset($ahpWeights) && !empty($ahpWeights)
            ? $ahpWeights
            : (isset($weights) ? $weights : []);

        /*
        |--------------------------------------------------------------------------
        | HASIL TOPSIS
        |--------------------------------------------------------------------------
        */

        $topsisResult = isset($result)
            ? $result
            : (isset($topsis) ? $topsis : null);

        /*
        |--------------------------------------------------------------------------
        | DEFAULT KRITERIA
        |--------------------------------------------------------------------------
        |
        | Urutan WAJIB:
        | C1 → C2 → C3 → C4 → C5
        |
        */

        $defaultCriteria = [
            [
                'kode' => 'C1',
                'nama' => 'Minat',
                'type' => 'Benefit',
            ],
            [
                'kode' => 'C2',
                'nama' => 'Bakat',
                'type' => 'Benefit',
            ],
            [
                'kode' => 'C3',
                'nama' => 'Jadwal',
                'type' => 'Benefit',
            ],
            [
                'kode' => 'C4',
                'nama' => 'Prestasi',
                'type' => 'Benefit',
            ],
            [
                'kode' => 'C5',
                'nama' => 'Biaya',
                'type' => 'Cost',
            ],
        ];
    @endphp


    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Perhitungan TOPSIS
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Proses perangkingan alternatif UKM berdasarkan
                bobot kriteria hasil perhitungan AHP.
            </p>

        </div>

    </div>


    {{-- =====================================================
        ERROR
    ====================================================== --}}
    @if(isset($errors) && $errors->any())

        <div class="mb-6 px-5 py-4
                    bg-red-50
                    border border-red-200
                    rounded-xl
                    text-red-700">

            <div class="font-semibold text-sm mb-2">
                Terjadi kesalahan:
            </div>

            <ul class="list-disc list-inside text-sm space-y-1">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
        SESSION SUCCESS
    ====================================================== --}}
    @if(session('success'))

        <div class="mb-6 px-5 py-4
                    bg-green-50
                    border border-green-200
                    rounded-xl
                    text-green-700">

            <div class="flex items-center gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 13l4 4L19 7"/>

                </svg>

                <div class="text-sm font-medium">
                    {{ session('success') }}
                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
        SESSION ERROR
    ====================================================== --}}
    @if(session('error'))

        <div class="mb-6 px-5 py-4
                    bg-red-50
                    border border-red-200
                    rounded-xl
                    text-red-700">

            <div class="flex items-center gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"/>

                </svg>

                <div class="text-sm font-medium">
                    {{ session('error') }}
                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
        INFORMASI TOPSIS
    ====================================================== --}}
    <div class="ui-card p-5 mb-6">

        <div class="flex items-start gap-4">

            <div class="w-11 h-11
                        rounded-xl
                        bg-[#fee2e2]
                        flex items-center justify-center
                        text-[#8B0000]
                        flex-shrink-0">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M4 19V5"/>
                    <path d="M4 19h16"/>
                    <path d="M7 15l3-3 3 2 5-6"/>

                </svg>

            </div>


            <div>

                <h3 class="font-semibold text-[#1f2937]">
                    Perhitungan TOPSIS
                </h3>

                <p class="text-sm text-[#6b7280] mt-1 leading-6">

                    Sistem menggunakan bobot prioritas hasil metode
                    Analytical Hierarchy Process (AHP), kemudian
                    melakukan normalisasi, normalisasi terbobot,
                    menentukan solusi ideal, menghitung separasi,
                    nilai preferensi, dan menghasilkan ranking UKM.

                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
        KRITERIA TOPSIS
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-5 border-b border-[#fecaca]">

            <h2 class="text-lg font-bold text-[#1f2937]">
                Kriteria TOPSIS
            </h2>

            <p class="text-sm text-[#6b7280] mt-1">
                Kriteria dan bobot yang digunakan dalam proses
                TOPSIS. Bobot berasal dari perhitungan AHP terbaru.
            </p>

        </div>


        <div class="p-6">

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr>

                            <th class="border border-[#fecaca]
                                       bg-[#f8f6fd]
                                       px-4 py-3
                                       text-center
                                       text-sm
                                       font-semibold
                                       text-[#1f2937]">
                                Kode
                            </th>

                            <th class="border border-[#fecaca]
                                       bg-[#f8f6fd]
                                       px-4 py-3
                                       text-left
                                       text-sm
                                       font-semibold
                                       text-[#1f2937]">
                                Kriteria
                            </th>

                            <th class="border border-[#fecaca]
                                       bg-[#f8f6fd]
                                       px-4 py-3
                                       text-center
                                       text-sm
                                       font-semibold
                                       text-[#1f2937]">
                                Atribut
                            </th>

                            <th class="border border-[#fecaca]
                                       bg-[#f8f6fd]
                                       px-4 py-3
                                       text-center
                                       text-sm
                                       font-semibold
                                       text-[#1f2937]">
                                Bobot AHP
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($defaultCriteria as $index => $item)

                            @php

                                $weightValue = null;

                                /*
                                |--------------------------------------------------
                                | Array numerik
                                |--------------------------------------------------
                                */

                                if (
                                    is_array($topsisWeights)
                                    && array_key_exists(
                                        $index,
                                        $topsisWeights
                                    )
                                ) {

                                    $weightValue =
                                        is_array(
                                            $topsisWeights[$index]
                                        )
                                            ? (
                                                $topsisWeights[$index]['bobot']
                                                ?? $topsisWeights[$index]['weight']
                                                ?? null
                                            )
                                            : $topsisWeights[$index];
                                }

                                /*
                                |--------------------------------------------------
                                | Array berdasarkan kode
                                |--------------------------------------------------
                                */

                                if (
                                    is_array($topsisWeights)
                                    && array_key_exists(
                                        $item['kode'],
                                        $topsisWeights
                                    )
                                ) {

                                    $weightValue =
                                        is_array(
                                            $topsisWeights[$item['kode']]
                                        )
                                            ? (
                                                $topsisWeights[$item['kode']]['bobot']
                                                ?? $topsisWeights[$item['kode']]['weight']
                                                ?? null
                                            )
                                            : $topsisWeights[$item['kode']];
                                }

                            @endphp


                            <tr>

                                <td class="border border-[#fecaca]
                                           px-4 py-3
                                           text-center
                                           text-sm
                                           font-semibold
                                           text-[#392f78]">

                                    {{ $item['kode'] }}

                                </td>


                                <td class="border border-[#fecaca]
                                           px-4 py-3
                                           text-sm
                                           text-[#55506f]">

                                    {{ $item['nama'] }}

                                </td>


                                <td class="border border-[#fecaca]
                                           px-4 py-3
                                           text-center
                                           text-sm
                                           font-semibold
                                           {{ $item['type'] === 'Cost'
                                                ? 'text-red-600'
                                                : 'text-green-600' }}">

                                    {{ $item['type'] }}

                                </td>


                                <td class="border border-[#fecaca]
                                           px-4 py-3
                                           text-center
                                           text-sm
                                           font-bold
                                           text-[#8B0000]">

                                    @if($weightValue !== null)

                                        {{ $formatNumber($weightValue, 4) }}

                                    @else

                                        <span class="text-[#aaa4bd]">
                                            Belum tersedia
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- INFO BOBOT --}}

            <div class="mt-5
                        px-4 py-3
                        rounded-xl
                        bg-[#f8f6fd]
                        border border-[#fecaca]">

                <div class="flex items-start gap-3">

                    <div class="w-8 h-8
                                rounded-lg
                                bg-[#fee2e2]
                                text-[#8B0000]
                                flex items-center justify-center
                                flex-shrink-0">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="17"
                            height="17"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round">

                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>

                        </svg>

                    </div>


                    <div>

                        <div class="text-sm
                                    font-semibold
                                    text-[#1f2937]">

                            Bobot berasal dari AHP

                        </div>

                        <div class="text-xs
                                    text-[#6b7280]
                                    mt-1">

                            Bobot prioritas kriteria yang telah dihitung
                            menggunakan AHP digunakan sebagai bobot
                            pada proses TOPSIS.

                            @if(isset($ahpRun) && $ahpRun)

                                <span class="font-semibold">
                                    Data AHP terbaru tersedia.
                                </span>

                            @else

                                <span class="font-semibold text-red-600">
                                    Belum terdapat hasil perhitungan AHP.
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        MATRIKS KEPUTUSAN
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-5 border-b border-[#fecaca]">

            <h2 class="text-lg font-bold text-[#1f2937]">
                Matriks Keputusan
            </h2>

            <p class="text-sm text-[#6b7280] mt-1">
                Nilai masing-masing UKM berdasarkan lima kriteria
                yang digunakan dalam proses TOPSIS.
            </p>

        </div>


        <div class="p-6">

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr>

                            <th class="border border-[#fecaca]
                                       bg-[#f8f6fd]
                                       px-4 py-3
                                       text-left
                                       text-xs
                                       font-semibold
                                       text-[#1f2937]">

                                Alternatif

                            </th>


                            @foreach($defaultCriteria as $item)

                                <th class="border border-[#fecaca]
                                           bg-[#f8f6fd]
                                           px-4 py-3
                                           text-center
                                           text-xs
                                           font-semibold
                                           text-[#1f2937]">

                                    {{ $item['kode'] }}

                                    <br>

                                    <span class="font-normal text-[#6b7280]">
                                        {{ $item['nama'] }}
                                    </span>

                                </th>

                            @endforeach

                        </tr>

                    </thead>


                    <tbody>

                        @if(count($ukmData) > 0)

                            @foreach($ukmData as $index => $alternative)

                                @php

                                    if (is_object($alternative)) {

                                        $alternativeName =
                                            $alternative->nama
                                            ?? $alternative->nama_ukm
                                            ?? $alternative->name
                                            ?? 'UKM ' . ($index + 1);

                                    } else {

                                        $alternativeName =
                                            $alternative['nama']
                                            ?? $alternative['nama_ukm']
                                            ?? $alternative['name']
                                            ?? 'UKM ' . ($index + 1);
                                    }

                                    $row =
                                        $decisionData[$index]
                                        ?? [];

                                @endphp


                                <tr>

                                    <td class="border border-[#fecaca]
                                               bg-[#fdf2f2]
                                               px-4 py-3
                                               text-sm
                                               font-semibold
                                               text-[#1f2937]">

                                        {{ $alternativeName }}

                                    </td>


                                    @for($j = 0; $j < 5; $j++)

                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-center
                                                   text-sm
                                                   text-[#55506f]">

                                            @if(
                                                isset($row[$j])
                                                && $row[$j] !== null
                                                && $row[$j] !== ''
                                            )

                                                {{ $formatNumber($row[$j], 4) }}

                                            @else

                                                <span class="text-[#aaa4bd]">
                                                    -
                                                </span>

                                            @endif

                                        </td>

                                    @endfor

                                </tr>

                            @endforeach

                        @else

                            <tr>

                                <td
                                    colspan="6"
                                    class="border border-[#fecaca]
                                           px-4 py-8
                                           text-center
                                           text-sm
                                           text-[#6b7280]">

                                    Data alternatif UKM belum tersedia.

                                </td>

                            </tr>

                        @endif

                    </tbody>

                </table>

            </div>


            {{-- CATATAN --}}

            <div class="mt-5
                        px-4 py-3
                        rounded-xl
                        bg-[#f8f6fd]
                        border border-[#fecaca]">

                <div class="text-xs
                            font-semibold
                            text-[#392f78]
                            mb-1">

                    Catatan Matriks Keputusan

                </div>

                <p class="text-xs
                          text-[#6b7280]
                          leading-5">

                    Matriks keputusan merupakan data awal yang akan
                    dinormalisasi menggunakan metode TOPSIS.
                    Setiap baris merepresentasikan satu alternatif UKM,
                    sedangkan setiap kolom merepresentasikan kriteria.

                </p>

            </div>

        </div>


        {{-- =================================================
            FOOTER ACTION
        ================================================== --}}
        <div class="px-6 py-5
                    border-t border-[#fecaca]
                    bg-[#fdf2f2]
                    flex items-center
                    justify-end">

            <form
                action="{{ route('topsis.calculate') }}"
                method="POST">

                @csrf

                <button
                    type="submit"
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

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path d="M4 19V5"/>
                        <path d="M4 19h16"/>
                        <path d="M7 15l3-3 3 2 5-6"/>

                    </svg>

                    Hitung TOPSIS

                </button>

            </form>

        </div>

    </div>


    {{-- =====================================================
        KETERANGAN ALUR
    ====================================================== --}}
    <div class="ui-card p-5 mb-6">

        <div class="flex items-start gap-4">

            <div class="w-10 h-10
                        rounded-xl
                        bg-[#fee2e2]
                        text-[#8B0000]
                        flex items-center justify-center
                        flex-shrink-0">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>

                </svg>

            </div>


            <div>

                <h3 class="text-sm
                           font-semibold
                           text-[#1f2937]">

                    Alur Perhitungan

                </h3>

                <p class="text-xs
                          text-[#6b7280]
                          mt-1
                          leading-5">

                    Bobot AHP → Matriks Keputusan →
                    Normalisasi → Normalisasi Terbobot →
                    Solusi Ideal Positif & Negatif →
                    Separasi → Nilai Preferensi →
                    Ranking UKM.

                </p>

            </div>

        </div>

    </div>


</div>

@endsection