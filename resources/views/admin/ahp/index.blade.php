@extends('layouts.app')

@section('title', 'Perhitungan AHP')

@section('page-title', 'Perhitungan AHP')

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

            // Hilangkan angka 0 di belakang desimal
            $formatted = rtrim($formatted, '0');

            // Hilangkan koma jika tidak ada angka desimal
            $formatted = rtrim($formatted, ',');

            return $formatted;
        };

    @endphp


    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Perhitungan AHP
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Analisis bobot kriteria menggunakan metode
                Analytical Hierarchy Process (AHP).
            </p>

        </div>

    </div>


    {{-- =====================================================
        NOTIFIKASI ERROR
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
        INFORMASI AHP
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

                    <path d="M12 20V10"/>
                    <path d="M18 20V4"/>
                    <path d="M6 20v-6"/>

                </svg>

            </div>

            <div>

                <h3 class="font-semibold text-[#1f2937]">
                    Perbandingan Kriteria
                </h3>

                <p class="text-sm text-[#6b7280] mt-1 leading-6">
                    Bandingkan tingkat kepentingan antar kriteria.
                    Anda hanya perlu mengisi satu arah perbandingan.
                    Nilai kebalikan atau reciprocal akan dihitung
                    otomatis oleh sistem.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
        TOMBOL UBAH NILAI MATRIKS
    ====================================================== --}}
    <div class="ui-card p-5 mb-6">

        <div class="flex items-center justify-between gap-4">

            <div>

                <div class="text-sm font-semibold text-[#1f2937]">
                    Nilai Matriks Perbandingan
                </div>

                <div class="text-xs text-[#6b7280] mt-1">
                    Ubah nilai perbandingan antar kriteria
                    jika diperlukan.
                </div>

            </div>

            <button
                type="button"
                id="toggleMatrixForm"
                onclick="toggleMatrixForm()"
                class="inline-flex
                       items-center
                       gap-2
                       px-4
                       py-2.5
                       rounded-lg
                       bg-[#da2424]
                       hover:bg-[#b91c1c]
                       text-white
                       text-sm
                       font-semibold
                       transition
                       shadow-sm
                       flex-shrink-0">

                <svg
                    id="toggleMatrixIcon"
                    xmlns="http://www.w3.org/2000/svg"
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>

                </svg>

                <span id="toggleMatrixText">
                    Ubah Nilai Matriks Perbandingan
                </span>

            </button>

        </div>

    </div>


    {{-- =====================================================
        FORM PERBANDINGAN
        HIDDEN DEFAULT
    ====================================================== --}}
    <div
        id="matrixFormContainer"
        class="ui-card overflow-hidden mb-6 {{ (isset($errors) && $errors->any()) ? '' : 'hidden' }}">

        <div class="px-6 py-5 border-b border-[#fecaca]">

            <h2 class="text-lg font-bold text-[#1f2937]">
                Perbandingan Berpasangan
            </h2>

            <p class="text-sm text-[#6b7280] mt-1">
                Isi 10 perbandingan antar 5 kriteria.
                Nilai 1 berarti kedua kriteria sama penting,
                sedangkan nilai 9 berarti kriteria pertama
                mutlak lebih penting.
            </p>

        </div>


        {{-- =================================================
            FORM
        ================================================== --}}
        <form
            action="{{ route('ahp.store') }}"
            method="POST">

            @csrf

            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- =================================================
                        LOOP PASANGAN KRITERIA
                    ================================================== --}}
                    @foreach($kriteria as $index => $baris)

                        @foreach($kriteria as $kolomIndex => $kolom)

                            @if($index < $kolomIndex)

                                @php

                                    $oldValue = old(
                                        "nilai.{$baris->id}.{$kolom->id}"
                                    );

                                @endphp


                                <div class="rounded-xl
                                            border border-[#fecaca]
                                            bg-[#fdf2f2]
                                            p-5">

                                    {{-- HEADER PASANGAN --}}
                                    <div class="flex items-center justify-between mb-4">

                                        <div>

                                            <div class="text-xs
                                                        font-medium
                                                        text-[#6b7280]">

                                                Perbandingan

                                            </div>

                                            <div class="text-base
                                                        font-bold
                                                        text-[#1f2937]
                                                        mt-1">

                                                {{ $baris->kode }}
                                                —
                                                {{ $kolom->kode }}

                                            </div>

                                        </div>


                                        <div class="px-3 py-1
                                                    rounded-full
                                                    bg-[#fee2e2]
                                                    text-[#8B0000]
                                                    text-xs
                                                    font-semibold">

                                            {{ $index + 1 }}

                                        </div>

                                    </div>


                                    {{-- NAMA KRITERIA --}}
                                    <div class="flex items-center
                                                justify-between
                                                gap-4
                                                mb-4">

                                        <div class="flex-1">

                                            <div class="text-sm
                                                        font-semibold
                                                        text-[#1f2937]">

                                                {{ $baris->nama_kriteria }}

                                            </div>

                                            <div class="text-xs
                                                        text-[#6b7280]
                                                        mt-1">

                                                {{ $baris->kode }}

                                            </div>

                                        </div>


                                        <div class="text-xs
                                                    font-semibold
                                                    text-[#6b7280]">

                                            terhadap

                                        </div>


                                        <div class="flex-1 text-right">

                                            <div class="text-sm
                                                        font-semibold
                                                        text-[#1f2937]">

                                                {{ $kolom->nama_kriteria }}

                                            </div>

                                            <div class="text-xs
                                                        text-[#6b7280]
                                                        mt-1">

                                                {{ $kolom->kode }}

                                            </div>

                                        </div>

                                    </div>


                                    {{-- INPUT NILAI --}}
                                    <div>

                                        <label class="block
                                                      text-xs
                                                      font-medium
                                                      text-[#6b7280]
                                                      mb-2">

                                            Nilai perbandingan

                                        </label>


                                        <select
                                            name="nilai[{{ $baris->id }}][{{ $kolom->id }}]"
                                            required
                                            class="w-full
                                                   rounded-lg
                                                   border-[#d8d2eb]
                                                   bg-white
                                                   text-sm
                                                   text-[#1f2937]
                                                   focus:border-[#7045d9]
                                                   focus:ring-[#7045d9]">

                                            <option value="">
                                                Pilih nilai perbandingan
                                            </option>


                                            <option
                                                value="0.111111"
                                                {{ $oldValue == '0.111111' ? 'selected' : '' }}>

                                                1/9 —
                                                {{ $kolom->nama_kriteria }}
                                                jauh lebih penting

                                            </option>


                                            <option
                                                value="0.142857"
                                                {{ $oldValue == '0.142857' ? 'selected' : '' }}>

                                                1/7 —
                                                {{ $kolom->nama_kriteria }}
                                                sangat lebih penting

                                            </option>


                                            <option
                                                value="0.2"
                                                {{ $oldValue == '0.2' ? 'selected' : '' }}>

                                                1/5 —
                                                {{ $kolom->nama_kriteria }}
                                                lebih penting

                                            </option>


                                            <option
                                                value="0.333333"
                                                {{ $oldValue == '0.333333' ? 'selected' : '' }}>

                                                1/3 —
                                                {{ $kolom->nama_kriteria }}
                                                sedikit lebih penting

                                            </option>


                                            <option
                                                value="1"
                                                {{ $oldValue == '1' ? 'selected' : '' }}>

                                                1 — Sama penting

                                            </option>


                                            <option
                                                value="3"
                                                {{ $oldValue == '3' ? 'selected' : '' }}>

                                                3 —
                                                {{ $baris->nama_kriteria }}
                                                sedikit lebih penting

                                            </option>


                                            <option
                                                value="5"
                                                {{ $oldValue == '5' ? 'selected' : '' }}>

                                                5 —
                                                {{ $baris->nama_kriteria }}
                                                lebih penting

                                            </option>


                                            <option
                                                value="7"
                                                {{ $oldValue == '7' ? 'selected' : '' }}>

                                                7 —
                                                {{ $baris->nama_kriteria }}
                                                sangat lebih penting

                                            </option>


                                            <option
                                                value="9"
                                                {{ $oldValue == '9' ? 'selected' : '' }}>

                                                9 —
                                                {{ $baris->nama_kriteria }}
                                                mutlak lebih penting

                                            </option>

                                        </select>

                                    </div>


                                    {{-- PENJELASAN RECIPROCAL --}}
                                    <div class="mt-3
                                                px-3 py-2
                                                rounded-lg
                                                bg-white
                                                border border-[#eeeaf6]">

                                        <p class="text-xs
                                                  text-[#6b7280]
                                                  leading-5">

                                            <span class="font-semibold text-[#392f78]">
                                                Catatan:
                                            </span>

                                            Sistem akan otomatis membuat
                                            nilai kebalikan.
                                            Contoh nilai 3 akan menghasilkan
                                            nilai 1/3 pada arah sebaliknya.

                                        </p>

                                    </div>

                                </div>

                            @endif

                        @endforeach

                    @endforeach

                </div>


                {{-- =================================================
                    INFORMASI JUMLAH PERBANDINGAN
                ================================================== --}}
                <div class="mt-6
                            px-4 py-3
                            rounded-xl
                            bg-[#f8f6fd]
                            border border-[#fecaca]">

                    <div class="flex items-center gap-3">

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

                                10 perbandingan harus diisi

                            </div>

                            <div class="text-xs
                                        text-[#6b7280]
                                        mt-1">

                                Sistem akan membentuk matriks
                                5 × 5 secara otomatis.

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                FOOTER FORM
            ================================================== --}}
            <div class="px-6 py-5
                        border-t border-[#fecaca]
                        bg-[#fdf2f2]
                        flex items-center
                        justify-end">

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

                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>

                    </svg>

                    Simpan & Hitung AHP

                </button>

            </div>

        </form>

    </div>


    {{-- =====================================================
        HASIL PERHITUNGAN
    ====================================================== --}}
    @if(isset($weights))

        <div class="ui-card mt-6 overflow-hidden">

            {{-- =================================================
                HEADER HASIL
            ================================================== --}}
            <div class="px-6 py-5 border-b border-[#fecaca]">

                <h2 class="text-lg font-bold text-[#1f2937]">
                    Hasil Perhitungan AHP
                </h2>

                <p class="text-sm text-[#6b7280] mt-1">
                    Hasil matriks, normalisasi, bobot prioritas,
                    vektor, dan uji konsistensi.
                </p>

            </div>


            {{-- =================================================
                BOBOT PRIORITAS
            ================================================== --}}
            <div class="p-6">

                <h3 class="text-sm
                           font-bold
                           text-[#1f2937]
                           mb-4">

                    Bobot Prioritas Kriteria

                </h3>

                <p class="text-xs
                          text-[#6b7280]
                          mb-4">

                    Bobot prioritas diperoleh dari rata-rata
                    nilai setiap baris pada matriks normalisasi.

                </p>


                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr>

                                <th class="border border-[#fecaca]
                                           bg-[#f8f6fd]
                                           px-4 py-3
                                           text-left
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

                                    Bobot Prioritas

                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($weights as $weight)

                                <tr>

                                    <td class="border border-[#fecaca]
                                               px-4 py-3
                                               text-sm
                                               font-semibold
                                               text-[#392f78]">

                                        {{ $weight['kode'] }}

                                    </td>


                                    <td class="border border-[#fecaca]
                                               px-4 py-3
                                               text-sm
                                               text-[#55506f]">

                                        {{ $weight['nama'] }}

                                    </td>


                                    <td class="border border-[#fecaca]
                                               px-4 py-3
                                               text-center
                                               text-sm
                                               font-bold
                                               text-[#8B0000]">

                                        {{ $formatNumber($weight['bobot'], 4) }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =================================================
                MATRIKS AHP
            ================================================== --}}
            <div class="px-6 pb-6">

                <h3 class="text-sm
                           font-bold
                           text-[#1f2937]
                           mb-4">

                    Matriks Perbandingan

                </h3>


                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr>

                                <th class="border border-[#fecaca]
                                           bg-[#f8f6fd]
                                           px-3 py-3
                                           text-center
                                           text-xs
                                           font-semibold
                                           text-[#1f2937]">

                                    Kriteria

                                </th>


                                @foreach($kriteria as $item)

                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-3 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        {{ $item->kode }}

                                    </th>

                                @endforeach

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($matrix as $rowIndex => $row)

                                <tr>

                                    <td class="border border-[#fecaca]
                                               bg-[#fdf2f2]
                                               px-3 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        {{ $kriteria[$rowIndex]->kode }}

                                    </td>


                                    @foreach($row as $value)

                                        <td class="border border-[#fecaca]
                                                   px-3 py-3
                                                   text-center
                                                   text-xs
                                                   text-[#55506f]">

                                            {{ $formatNumber($value, 4) }}

                                        </td>

                                    @endforeach

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =================================================
                JUMLAH KOLOM
            ================================================== --}}
            <div class="px-6 pb-6">

                <h3 class="text-sm
                           font-bold
                           text-[#1f2937]
                           mb-4">

                    Jumlah Setiap Kolom

                </h3>


                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr>

                                @foreach($kriteria as $item)

                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-3 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        {{ $item->kode }}

                                    </th>

                                @endforeach

                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                @foreach($columnSums as $sum)

                                    <td class="border border-[#fecaca]
                                               px-3 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#392f78]">

                                        {{ $formatNumber($sum, 4) }}

                                    </td>

                                @endforeach

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =================================================
                MATRIKS NORMALISASI
            ================================================== --}}
            <div class="px-6 pb-6">

                <h3 class="text-sm
                           font-bold
                           text-[#1f2937]
                           mb-4">

                    Matriks Normalisasi

                </h3>


                <div class="overflow-x-auto">

                    <table class="w-full border-collapse">

                        <thead>

                            <tr>

                                <th class="border border-[#fecaca]
                                           bg-[#f8f6fd]
                                           px-3 py-3
                                           text-center
                                           text-xs
                                           font-semibold
                                           text-[#1f2937]">

                                    Kriteria

                                </th>


                                @foreach($kriteria as $item)

                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-3 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        {{ $item->kode }}

                                    </th>

                                @endforeach

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($normalizedMatrix as $rowIndex => $row)

                                <tr>

                                    <td class="border border-[#fecaca]
                                               bg-[#fdf2f2]
                                               px-3 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        {{ $kriteria[$rowIndex]->kode }}

                                    </td>


                                    @foreach($row as $value)

                                        <td class="border border-[#fecaca]
                                                   px-3 py-3
                                                   text-center
                                                   text-xs
                                                   text-[#55506f]">

                                            {{ $formatNumber($value, 4) }}

                                        </td>

                                    @endforeach

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =================================================
                VEKTOR
            ================================================== --}}
            @if(isset($vector))

                <div class="px-6 pb-6">

                    <h3 class="text-sm
                               font-bold
                               text-[#1f2937]
                               mb-4">

                        Vektor

                    </h3>


                    <p class="text-xs
                              text-[#6b7280]
                              mb-4">

                        Vektor dihitung berdasarkan perkalian
                        nilai pada setiap baris matriks normalisasi
                        dengan bobot prioritas setiap kriteria,
                        kemudian seluruh hasil perkalian dijumlahkan.

                    </p>


                    <div class="mb-4
                                px-4 py-3
                                rounded-xl
                                bg-[#f8f6fd]
                                border border-[#fecaca]">

                        <div class="text-xs
                                    font-semibold
                                    text-[#392f78]
                                    mb-1">

                            Rumus Vektor

                        </div>


                        <div class="text-sm
                                    text-[#55506f]">

                            Vektor =
                            (Normalisasi × Bobot C1) +
                            (Normalisasi × Bobot C2) +
                            (Normalisasi × Bobot C3) +
                            (Normalisasi × Bobot C4) +
                            (Normalisasi × Bobot C5)

                        </div>

                    </div>


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

                                        Kode

                                    </th>


                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-4 py-3
                                               text-left
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        Kriteria

                                    </th>


                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-4 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        Vektor

                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($vector as $index => $value)

                                    <tr>

                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-sm
                                                   font-semibold
                                                   text-[#392f78]">

                                            {{ $kriteria[$index]->kode }}

                                        </td>


                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-sm
                                                   text-[#55506f]">

                                            {{ $kriteria[$index]->nama_kriteria }}

                                        </td>


                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-center
                                                   text-sm
                                                   font-bold
                                                   text-[#8B0000]">

                                            {{ $formatNumber($value, 4) }}

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif


            {{-- =================================================
                WEIGHTED SUM
            ================================================== --}}
            @if(isset($weightedSum))

                <div class="px-6 pb-6">

                    <h3 class="text-sm
                               font-bold
                               text-[#1f2937]
                               mb-4">

                        Weighted Sum Vector

                    </h3>


                    <p class="text-xs
                              text-[#6b7280]
                              mb-4">

                        Weighted Sum Vector digunakan dalam
                        proses pengujian konsistensi AHP.

                    </p>


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

                                        Kode

                                    </th>


                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-4 py-3
                                               text-left
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        Kriteria

                                    </th>


                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-4 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        Weighted Sum

                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($weightedSum as $index => $value)

                                    <tr>

                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-sm
                                                   font-semibold
                                                   text-[#392f78]">

                                            {{ $kriteria[$index]->kode }}

                                        </td>


                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-sm
                                                   text-[#55506f]">

                                            {{ $kriteria[$index]->nama_kriteria }}

                                        </td>


                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-center
                                                   text-sm
                                                   font-bold
                                                   text-[#8B0000]">

                                            {{ $formatNumber($value, 4) }}

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif


            {{-- =================================================
                CONSISTENCY VECTOR
            ================================================== --}}
            @if(isset($consistencyVector))

                <div class="px-6 pb-6">

                    <h3 class="text-sm
                               font-bold
                               text-[#1f2937]
                               mb-4">

                        Consistency Vector

                    </h3>


                    <p class="text-xs
                              text-[#6b7280]
                              mb-4">

                        Consistency Vector diperoleh dari
                        Weighted Sum Vector dibagi dengan
                        Bobot Prioritas masing-masing kriteria.

                    </p>


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

                                        Kode

                                    </th>


                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-4 py-3
                                               text-left
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        Kriteria

                                    </th>


                                    <th class="border border-[#fecaca]
                                               bg-[#f8f6fd]
                                               px-4 py-3
                                               text-center
                                               text-xs
                                               font-semibold
                                               text-[#1f2937]">

                                        Consistency Vector

                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($consistencyVector as $index => $value)

                                    <tr>

                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-sm
                                                   font-semibold
                                                   text-[#392f78]">

                                            {{ $kriteria[$index]->kode }}

                                        </td>


                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-sm
                                                   text-[#55506f]">

                                            {{ $kriteria[$index]->nama_kriteria }}

                                        </td>


                                        <td class="border border-[#fecaca]
                                                   px-4 py-3
                                                   text-center
                                                   text-sm
                                                   font-bold
                                                   text-[#8B0000]">

                                            {{ $formatNumber($value, 4) }}

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endif


            {{-- =================================================
                UJI KONSISTENSI
            ================================================== --}}
            <div class="px-6 pb-6">

                <h3 class="text-sm
                           font-bold
                           text-[#1f2937]
                           mb-4">

                    Uji Konsistensi

                </h3>


                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- LAMBDA MAX --}}
                    <div class="rounded-xl
                                border border-[#fecaca]
                                bg-[#fdf2f2]
                                p-5">

                        <div class="text-xs
                                    font-medium
                                    text-[#6b7280]
                                    mb-2">

                            λ Maksimum

                        </div>


                        <div class="text-2xl
                                    font-bold
                                    text-[#1f2937]">

                            {{ $formatNumber($lambdaMax, 4) }}

                        </div>

                    </div>


                    {{-- CI --}}
                    <div class="rounded-xl
                                border border-[#fecaca]
                                bg-[#fdf2f2]
                                p-5">

                        <div class="text-xs
                                    font-medium
                                    text-[#6b7280]
                                    mb-2">

                            Consistency Index (CI)

                        </div>


                        <div class="text-2xl
                                    font-bold
                                    text-[#1f2937]">

                            {{ $formatNumber($ci, 4) }}

                        </div>

                    </div>


                    {{-- RI --}}
                    <div class="rounded-xl
                                border border-[#fecaca]
                                bg-[#fdf2f2]
                                p-5">

                        <div class="text-xs
                                    font-medium
                                    text-[#6b7280]
                                    mb-2">

                            Random Index (RI)

                        </div>


                        <div class="text-2xl
                                    font-bold
                                    text-[#1f2937]">

                            {{ $formatNumber($ri, 4) }}

                        </div>

                    </div>


                    {{-- CR --}}
                    <div class="rounded-xl
                                border border-[#fecaca]
                                bg-[#fdf2f2]
                                p-5">

                        <div class="text-xs
                                    font-medium
                                    text-[#6b7280]
                                    mb-2">

                            Consistency Ratio (CR)

                        </div>


                        <div class="text-2xl
                                    font-bold
                                    {{ $isConsistent
                                        ? 'text-green-600'
                                        : 'text-red-600' }}">

                            {{ $formatNumber($cr, 4) }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    STATUS
                ================================================== --}}
                @if($isConsistent)

                    <div class="mt-5
                                flex items-center gap-3
                                px-5 py-4
                                rounded-xl
                                bg-green-50
                                border border-green-200
                                text-green-700">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 flex-shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"/>

                        </svg>


                        <div>

                            <div class="font-semibold text-sm">
                                Matriks Konsisten
                            </div>

                            <div class="text-xs mt-1">

                                Nilai CR ≤ 0,10.
                                Bobot kriteria dapat digunakan
                                untuk proses TOPSIS.

                            </div>

                        </div>

                    </div>

                @else

                    <div class="mt-5
                                flex items-center gap-3
                                px-5 py-4
                                rounded-xl
                                bg-red-50
                                border border-red-200
                                text-red-700">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6 flex-shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"/>

                        </svg>


                        <div>

                            <div class="font-semibold text-sm">
                                Matriks Tidak Konsisten
                            </div>

                            <div class="text-xs mt-1">

                                Nilai CR > 0,10.
                                Silakan periksa kembali
                                perbandingan antar kriteria.

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- =====================================================
        SKALA AHP
    ====================================================== --}}
    <div class="ui-card mt-6 overflow-hidden">

        <div class="px-6 py-5 border-b border-[#fecaca]">

            <h2 class="text-lg font-bold text-[#1f2937]">
                Skala Perbandingan AHP
            </h2>

            <p class="text-sm text-[#6b7280] mt-1">
                Gunakan skala berikut sebagai pedoman pengisian.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-[#f8f6fd]">

                        <th class="px-6 py-3
                                   text-left
                                   text-sm
                                   font-semibold
                                   text-[#1f2937]">

                            Nilai

                        </th>


                        <th class="px-6 py-3
                                   text-left
                                   text-sm
                                   font-semibold
                                   text-[#1f2937]">

                            Tingkat Kepentingan

                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr class="border-t border-[#eeeaf6]">

                        <td class="px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-[#392f78]">

                            1

                        </td>


                        <td class="px-6 py-3
                                   text-sm
                                   text-[#55506f]">

                            Sama penting

                        </td>

                    </tr>


                    <tr class="border-t border-[#eeeaf6]">

                        <td class="px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-[#392f78]">

                            3

                        </td>


                        <td class="px-6 py-3
                                   text-sm
                                   text-[#55506f]">

                            Sedikit lebih penting

                        </td>

                    </tr>


                    <tr class="border-t border-[#eeeaf6]">

                        <td class="px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-[#392f78]">

                            5

                        </td>


                        <td class="px-6 py-3
                                   text-sm
                                   text-[#55506f]">

                            Lebih penting

                        </td>

                    </tr>


                    <tr class="border-t border-[#eeeaf6]">

                        <td class="px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-[#392f78]">

                            7

                        </td>


                        <td class="px-6 py-3
                                   text-sm
                                   text-[#55506f]">

                            Sangat lebih penting

                        </td>

                    </tr>


                    <tr class="border-t border-[#eeeaf6]">

                        <td class="px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-[#392f78]">

                            9

                        </td>


                        <td class="px-6 py-3
                                   text-sm
                                   text-[#55506f]">

                            Mutlak lebih penting

                        </td>

                    </tr>


                    <tr class="border-t border-[#eeeaf6]">

                        <td class="px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-[#392f78]">

                            1/3, 1/5, 1/7, 1/9

                        </td>


                        <td class="px-6 py-3
                                   text-sm
                                   text-[#55506f]">

                            Nilai kebalikan ketika kriteria kedua
                            lebih penting daripada kriteria pertama.

                        </td>

                    </tr>


                    <tr class="border-t border-[#eeeaf6]">

                        <td class="px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-[#392f78]">

                            2, 4, 6, 8

                        </td>


                        <td class="px-6 py-3
                                   text-sm
                                   text-[#55506f]">

                            Nilai kompromi antara dua tingkat
                            kepentingan yang berdekatan.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =====================================================
    JAVASCRIPT
    TOGGLE FORM NILAI MATRIKS
====================================================== --}}
<script>

    function toggleMatrixForm() {

        const formContainer =
            document.getElementById('matrixFormContainer');

        const buttonText =
            document.getElementById('toggleMatrixText');

        const buttonIcon =
            document.getElementById('toggleMatrixIcon');

        if (formContainer.classList.contains('hidden')) {

            // Tampilkan form
            formContainer.classList.remove('hidden');

            buttonText.textContent =
                'Tutup Form Nilai Matriks';

            buttonIcon.innerHTML = `
                <path d="M18 6L6 18"/>
                <path d="M6 6l12 12"/>
            `;

            // Scroll otomatis ke form
            setTimeout(function () {

                formContainer.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

            }, 100);

        } else {

            // Sembunyikan form
            formContainer.classList.add('hidden');

            buttonText.textContent =
                'Ubah Nilai Matriks Perbandingan';

            buttonIcon.innerHTML = `
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            `;

        }

    }

</script>

@endsection