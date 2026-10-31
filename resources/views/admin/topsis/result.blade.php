@extends('layouts.app')

@section('title', 'Hasil Perhitungan TOPSIS')
@section('page-title', 'Hasil Perhitungan TOPSIS')

@section('content')

<div class="page-content">

    {{-- =====================================================
        HELPER FORMAT ANGKA
    ====================================================== --}}
    @php
        $fmt = function ($value, $dec = 4) {
            if ($value === null || $value === '') return '-';
            $f = number_format((float) $value, $dec, ',', '');
            $f = rtrim($f, '0');
            $f = rtrim($f, ',');
            return $f ?: '0';
        };

        // Kriteria sebagai array biasa agar bisa diakses dengan index angka
        $criteriaList = [];
        foreach ($criteria as $c) {
            $criteriaList[] = $c;
        }

        // UKM sebagai array biasa agar bisa diakses dengan index angka
        $ukmList = [];
        foreach ($ukm as $u) {
            $ukmList[] = $u;
        }
    @endphp


    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Hasil Perhitungan TOPSIS
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Perangkingan UKM menggunakan metode
                Technique for Order of Preference by Similarity to Ideal Solution (TOPSIS).
            </p>

        </div>

        <a href="{{ route('topsis.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                  border border-[#fecaca] text-[#8B0000] text-sm font-semibold
                  hover:bg-[#fef2f2] transition flex-shrink-0">

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>

            Kembali ke TOPSIS

        </a>

    </div>


    {{-- =====================================================
        NOTIFIKASI
    ====================================================== --}}
    @if(session('success'))
        <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200
                    rounded-xl text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200
                    rounded-xl text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif


    {{-- =====================================================
        INFO RUN
    ====================================================== --}}
    <div class="ui-card p-5 mb-6">

        <div class="flex items-start gap-4">

            <div class="w-11 h-11 rounded-xl bg-[#fee2e2] flex items-center
                        justify-center text-[#8B0000] flex-shrink-0">

                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19V5"/>
                    <path d="M4 19h16"/>
                    <path d="M7 15l3-3 3 2 5-6"/>
                </svg>

            </div>

            <div class="flex-1">

                <h3 class="font-semibold text-[#1f2937]">
                    Proses TOPSIS #{{ $topsisRunId }} — Berhasil
                </h3>

                <p class="text-sm text-[#6b7280] mt-1">
                    Perhitungan berhasil diselesaikan dan data tersimpan ke database.
                    @if($ahpRun)
                        Menggunakan bobot AHP dari Run #{{ $ahpRun->id }}.
                    @endif
                </p>

            </div>

            <a href="{{ route('topsis.show', $topsisRunId) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-[#da2424] hover:bg-[#b91c1c] text-white text-sm
                      font-semibold transition shadow-sm flex-shrink-0">

                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>

                Lihat Detail

            </a>

        </div>

    </div>


    {{-- =====================================================
        BOBOT AHP
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-[#fecaca] flex items-center gap-3">

            <div class="w-8 h-8 rounded-lg bg-[#fee2e2] flex items-center
                        justify-center text-[#8B0000]">
                <span class="text-xs font-bold">W</span>
            </div>

            <div>
                <h2 class="text-base font-bold text-[#1f2937]">
                    Bobot AHP yang Digunakan
                </h2>
                <p class="text-xs text-[#6b7280] mt-0.5">
                    Bobot prioritas kriteria hasil perhitungan AHP
                </p>
            </div>

        </div>

        <div class="p-6">

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">

                @foreach($criteriaList as $cIdx => $c)

                    <div class="rounded-xl bg-[#fdf2f2] border border-[#fecaca] p-4 text-center">

                        <div class="text-xs font-semibold text-[#8B0000] mb-1">
                            {{ $c->kode }}
                        </div>

                        <div class="text-sm font-medium text-[#1f2937] mb-2">
                            {{ $c->nama_kriteria }}
                        </div>

                        <div class="text-xl font-bold text-[#da2424]">
                            {{ $fmt($weights[$cIdx] ?? null) }}
                        </div>

                        <div class="text-xs text-[#6b7280] mt-1">
                            {{ $c->tipe === 'cost' ? '↓ Cost' : '↑ Benefit' }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- =====================================================
        RANKING FINAL
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-[#fecaca] flex items-center gap-3">

            <div class="w-8 h-8 rounded-lg bg-[#fee2e2] flex items-center
                        justify-center">
                <span class="text-base">🏆</span>
            </div>

            <div>
                <h2 class="text-base font-bold text-[#1f2937]">
                    Ranking UKM — Hasil Akhir TOPSIS
                </h2>
                <p class="text-xs text-[#6b7280] mt-0.5">
                    Diurutkan berdasarkan nilai preferensi Ci (semakin tinggi = semakin direkomendasikan)
                </p>
            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>
                    <tr class="bg-[#fef2f2] text-[#8B0000]">
                        <th class="px-5 py-3 text-center font-semibold w-20">Ranking</th>
                        <th class="px-5 py-3 text-left font-semibold">Nama UKM</th>
                        <th class="px-5 py-3 text-right font-semibold">S⁺ (Jarak Positif)</th>
                        <th class="px-5 py-3 text-right font-semibold">S⁻ (Jarak Negatif)</th>
                        <th class="px-5 py-3 text-right font-semibold">Ci (Nilai Preferensi)</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#fecaca]">

                    @foreach($results as $res)

                        <tr class="{{ $res['ranking'] === 1 ? 'bg-[#fef2f2]' : 'hover:bg-[#fdf2f2]' }} transition">

                            <td class="px-5 py-4 text-center">

                                @if($res['ranking'] <= 3)
                                    @php
                                        $colors = [
                                            1 => 'bg-[#8B0000] text-white',
                                            2 => 'bg-[#a80000] text-white',
                                            3 => 'bg-[#f8c4c4] text-[#5F0000]',
                                        ];
                                        $cls = $colors[$res['ranking']] ?? 'bg-[#fde4e4] text-[#8B0000]';
                                    @endphp
                                    <span class="inline-flex items-center justify-center
                                                 w-8 h-8 rounded-full font-bold text-sm {{ $cls }}">
                                        {{ $res['ranking'] }}
                                    </span>
                                @else
                                     <span class="inline-flex items-center justify-center
                                                 w-8 h-8 rounded-full bg-[#fde4e4] text-[#8B0000] font-bold text-sm">
                                         {{ $res['ranking'] }}
                                     </span>
                                @endif

                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#1f2937]">
                                    {{ $res['nama'] }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-right font-mono text-[#6b7280]">
                                {{ $fmt($res['separation_positive']) }}
                            </td>

                            <td class="px-5 py-4 text-right font-mono text-[#6b7280]">
                                {{ $fmt($res['separation_negative']) }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                <span class="font-bold text-[#da2424] font-mono">
                                    {{ $fmt($res['preference_value']) }}
                                </span>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
        MATRIKS KEPUTUSAN
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-[#fecaca]">
            <h2 class="text-base font-bold text-[#1f2937]">
                Langkah 1 — Matriks Keputusan (X)
            </h2>
            <p class="text-xs text-[#6b7280] mt-0.5">
                Nilai profil masing-masing UKM terhadap setiap kriteria
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#fef2f2] text-[#8B0000]">
                        <th class="px-5 py-3 text-left font-semibold">UKM</th>
                        @foreach($criteriaList as $c)
                            <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">
                                {{ $c->kode }}<br>
                                <span class="font-normal text-xs text-[#6b7280]">{{ $c->nama_kriteria }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca]">
                    @foreach($ukmList as $ukmIdx => $u)
                        <tr class="hover:bg-[#fdf2f2] transition">
                            <td class="px-5 py-3 font-semibold text-[#1f2937]">
                                {{ $u->nama_ukm ?? ('UKM ' . ($ukmIdx + 1)) }}
                            </td>
                            @foreach($criteriaList as $cIdx => $c)
                                <td class="px-4 py-3 text-right font-mono text-[#6b7280]">
                                    {{ $fmt($decisionMatrix[$ukmIdx][$cIdx] ?? null) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>


    {{-- =====================================================
        MATRIKS NORMALISASI
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-[#fecaca]">
            <h2 class="text-base font-bold text-[#1f2937]">
                Langkah 2 — Matriks Normalisasi (R)
            </h2>
            <p class="text-xs text-[#6b7280] mt-0.5">
                rᵢⱼ = xᵢⱼ / √(Σxᵢⱼ²)
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#fef2f2] text-[#8B0000]">
                        <th class="px-5 py-3 text-left font-semibold">UKM</th>
                        @foreach($criteriaList as $c)
                            <th class="px-4 py-3 text-right font-semibold">{{ $c->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca]">
                    @foreach($ukmList as $ukmIdx => $u)
                        <tr class="hover:bg-[#fdf2f2] transition">
                            <td class="px-5 py-3 font-semibold text-[#1f2937]">
                                {{ $u->nama_ukm ?? ('UKM ' . ($ukmIdx + 1)) }}
                            </td>
                            @foreach($criteriaList as $cIdx => $c)
                                <td class="px-4 py-3 text-right font-mono text-[#6b7280]">
                                    {{ $fmt($normalizedMatrix[$ukmIdx][$cIdx] ?? null) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>


    {{-- =====================================================
        MATRIKS NORMALISASI TERBOBOT
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-[#fecaca]">
            <h2 class="text-base font-bold text-[#1f2937]">
                Langkah 3 — Matriks Normalisasi Terbobot (V)
            </h2>
            <p class="text-xs text-[#6b7280] mt-0.5">
                vᵢⱼ = rᵢⱼ × wⱼ (bobot AHP)
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#fef2f2] text-[#8B0000]">
                        <th class="px-5 py-3 text-left font-semibold">UKM</th>
                        @foreach($criteriaList as $c)
                            <th class="px-4 py-3 text-right font-semibold">{{ $c->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca]">
                    @foreach($ukmList as $ukmIdx => $u)
                        <tr class="hover:bg-[#fdf2f2] transition">
                            <td class="px-5 py-3 font-semibold text-[#1f2937]">
                                {{ $u->nama_ukm ?? ('UKM ' . ($ukmIdx + 1)) }}
                            </td>
                            @foreach($criteriaList as $cIdx => $c)
                                <td class="px-4 py-3 text-right font-mono text-[#6b7280]">
                                    {{ $fmt($weightedNormalizedMatrix[$ukmIdx][$cIdx] ?? null) }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>


    {{-- =====================================================
        SOLUSI IDEAL POSITIF & NEGATIF
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-[#fecaca]">
            <h2 class="text-base font-bold text-[#1f2937]">
                Langkah 4 — Solusi Ideal Positif (A⁺) &amp; Negatif (A⁻)
            </h2>
            <p class="text-xs text-[#6b7280] mt-0.5">
                Benefit: A⁺ = max, A⁻ = min &nbsp;|&nbsp; Cost: A⁺ = min, A⁻ = max
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#fef2f2] text-[#8B0000]">
                        <th class="px-5 py-3 text-left font-semibold">Solusi Ideal</th>
                        @foreach($criteriaList as $c)
                            <th class="px-4 py-3 text-right font-semibold">
                                {{ $c->kode }}
                                <span class="text-xs font-normal">({{ $c->tipe }})</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca]">
                    <tr class="bg-green-50">
                        <td class="px-5 py-3 font-semibold text-green-700">
                            A⁺ (Positif)
                        </td>
                        @foreach($criteriaList as $cIdx => $c)
                            <td class="px-4 py-3 text-right font-mono text-green-700 font-semibold">
                                {{ $fmt($idealPositive[$cIdx] ?? null) }}
                            </td>
                        @endforeach
                    </tr>
                    <tr class="bg-red-50">
                        <td class="px-5 py-3 font-semibold text-red-600">
                            A⁻ (Negatif)
                        </td>
                        @foreach($criteriaList as $cIdx => $c)
                            <td class="px-4 py-3 text-right font-mono text-red-600 font-semibold">
                                {{ $fmt($idealNegative[$cIdx] ?? null) }}
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

    </div>


    {{-- =====================================================
        SEPARASI & PREFERENSI
    ====================================================== --}}
    <div class="ui-card overflow-hidden mb-6">

        <div class="px-6 py-4 border-b border-[#fecaca]">
            <h2 class="text-base font-bold text-[#1f2937]">
                Langkah 5 — Jarak Separasi &amp; Nilai Preferensi
            </h2>
            <p class="text-xs text-[#6b7280] mt-0.5">
                S⁺ = jarak ke ideal positif &nbsp;|&nbsp;
                S⁻ = jarak ke ideal negatif &nbsp;|&nbsp;
                Ci = S⁻ / (S⁺ + S⁻)
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#fef2f2] text-[#8B0000]">
                        <th class="px-5 py-3 text-left font-semibold">UKM</th>
                        <th class="px-5 py-3 text-right font-semibold">S⁺</th>
                        <th class="px-5 py-3 text-right font-semibold">S⁻</th>
                        <th class="px-5 py-3 text-right font-semibold">Ci</th>
                        <th class="px-5 py-3 text-center font-semibold">Ranking</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fecaca]">
                    @foreach($ukmList as $ukmIdx => $u)
                        <tr class="hover:bg-[#fdf2f2] transition">
                            <td class="px-5 py-3 font-semibold text-[#1f2937]">
                                {{ $u->nama_ukm ?? ('UKM ' . ($ukmIdx + 1)) }}
                            </td>
                            <td class="px-5 py-3 text-right font-mono text-[#6b7280]">
                                {{ $fmt($separationPositive[$ukmIdx] ?? null) }}
                            </td>
                            <td class="px-5 py-3 text-right font-mono text-[#6b7280]">
                                {{ $fmt($separationNegative[$ukmIdx] ?? null) }}
                            </td>
                            <td class="px-5 py-3 text-right font-mono font-bold text-[#da2424]">
                                {{ $fmt($preferenceValues[$ukmIdx] ?? null) }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                @php $r = $ranking[$ukmIdx] ?? 99; @endphp
                                <span class="inline-flex items-center justify-center w-7 h-7
                                             rounded-full text-xs font-bold
                                             {{ $r <= 3 ? 'bg-[#da2424] text-white' : 'bg-[#fecaca] text-[#8B0000]' }}">
                                    {{ $r <= 99 ? $r : '-' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>


    {{-- =====================================================
        TOMBOL AKSI
    ====================================================== --}}
    <div class="flex items-center justify-between gap-4 mt-2 pb-6">

        <a href="{{ route('topsis.index') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl
                  border border-[#fecaca] text-[#8B0000] text-sm font-semibold
                  hover:bg-[#fef2f2] transition">

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>

            Kembali ke Halaman TOPSIS

        </a>

        <a href="{{ route('topsis.show', $topsisRunId) }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl
                  bg-[#da2424] hover:bg-[#b91c1c] text-white text-sm
                  font-semibold transition shadow-sm">

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>

            Lihat Detail Run #{{ $topsisRunId }}

        </a>

    </div>

</div>

@endsection
