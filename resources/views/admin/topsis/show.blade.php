@extends('layouts.app')

@section('title', 'Detail Hasil TOPSIS #' . $run->id)
@section('page-title', 'Detail Hasil TOPSIS')

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
    @endphp


    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Detail Hasil TOPSIS #{{ $run->id }}
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Rincian ranking UKM dari proses perhitungan TOPSIS yang tersimpan di database.
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
                    Proses TOPSIS #{{ $run->id }}
                </h3>

                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">

                    <div>
                        <div class="text-xs text-[#6b7280]">Tanggal Dibuat</div>
                        <div class="text-sm font-semibold text-[#1f2937] mt-0.5">
                            {{ $run->created_at
                                ? \Carbon\Carbon::parse($run->created_at)->format('d M Y, H:i')
                                : '-' }}
                        </div>
                    </div>

                    @if(isset($run->ahp_run_id) && $run->ahp_run_id)
                        <div>
                            <div class="text-xs text-[#6b7280]">AHP Run</div>
                            <div class="text-sm font-semibold text-[#1f2937] mt-0.5">
                                #{{ $run->ahp_run_id }}
                            </div>
                        </div>
                    @endif

                    @if(isset($run->status) && $run->status)
                        <div>
                            <div class="text-xs text-[#6b7280]">Status</div>
                            <div class="mt-0.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                             text-xs font-semibold
                                             {{ $run->status === 'completed'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($run->status) }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <div class="text-xs text-[#6b7280]">Jumlah Hasil</div>
                        <div class="text-sm font-semibold text-[#1f2937] mt-0.5">
                            {{ $results->count() }} UKM
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
        TABEL RANKING DARI DATABASE
    ====================================================== --}}
    @if($results->isNotEmpty())

        <div class="ui-card overflow-hidden mb-6">

            <div class="px-6 py-4 border-b border-[#fecaca] flex items-center gap-3">

                <div class="w-8 h-8 rounded-lg bg-[#fee2e2] flex items-center
                            justify-center">
                    <span class="text-base">🏆</span>
                </div>

                <div>
                    <h2 class="text-base font-bold text-[#1f2937]">
                        Ranking UKM
                    </h2>
                    <p class="text-xs text-[#6b7280] mt-0.5">
                        Hasil akhir perangkingan berdasarkan nilai preferensi TOPSIS
                    </p>
                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-[#fef2f2] text-[#8B0000]">
                            <th class="px-5 py-3 text-center font-semibold w-20">Ranking</th>
                            <th class="px-5 py-3 text-left font-semibold">Nama UKM</th>

                            {{-- Tampilkan kolom S+/S- jika tersedia di database --}}
                            @if(isset($results->first()->s_plus))
                                <th class="px-5 py-3 text-right font-semibold">S⁺</th>
                            @endif

                            @if(isset($results->first()->s_minus))
                                <th class="px-5 py-3 text-right font-semibold">S⁻</th>
                            @endif

                            {{-- Cek nama kolom nilai preferensi --}}
                            @if(isset($results->first()->nilai_preferensi))
                                <th class="px-5 py-3 text-right font-semibold">Nilai Preferensi (Ci)</th>
                            @elseif(isset($results->first()->nilai))
                                <th class="px-5 py-3 text-right font-semibold">Nilai Preferensi (Ci)</th>
                            @endif

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#fecaca]">

                        @foreach($results as $result)

                            @php
                                $rankVal = $result->ranking ?? 99;
                                $nilaiPref = $result->nilai_preferensi ?? $result->nilai ?? null;
                            @endphp

                            <tr class="{{ $rankVal === 1 ? 'bg-[#fef2f2]' : 'hover:bg-[#fdf2f2]' }} transition">

                                <td class="px-5 py-4 text-center">

                                    @if($rankVal <= 3)
                                        @php
                                            $rankColors = [
                                                1 => 'bg-[#8B0000] text-white',
                                                2 => 'bg-[#a80000] text-white',
                                                3 => 'bg-[#f8c4c4] text-[#5F0000]',
                                            ];
                                            $rankCls = $rankColors[$rankVal] ?? 'bg-[#fde4e4] text-[#8B0000]';
                                        @endphp
                                        <span class="inline-flex items-center justify-center
                                                     w-8 h-8 rounded-full font-bold text-sm {{ $rankCls }}">
                                            {{ $rankVal }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center
                                                     w-8 h-8 rounded-full bg-[#fecaca]
                                                     text-[#8B0000] font-bold text-sm">
                                            {{ $rankVal }}
                                        </span>
                                    @endif

                                </td>

                                <td class="px-5 py-4">
                                    <div class="font-semibold text-[#1f2937]">
                                        {{-- Field nama UKM: kolom database adalah nama_ukm --}}
                                        {{ $result->nama_ukm ?? ('UKM #' . $result->ukm_id) }}
                                    </div>
                                </td>

                                @if(isset($results->first()->s_plus))
                                    <td class="px-5 py-4 text-right font-mono text-[#6b7280]">
                                        {{ $fmt($result->s_plus) }}
                                    </td>
                                @endif

                                @if(isset($results->first()->s_minus))
                                    <td class="px-5 py-4 text-right font-mono text-[#6b7280]">
                                        {{ $fmt($result->s_minus) }}
                                    </td>
                                @endif

                                @if(isset($results->first()->nilai_preferensi) || isset($results->first()->nilai))
                                    <td class="px-5 py-4 text-right">
                                        <span class="font-bold text-[#da2424] font-mono">
                                            {{ $fmt($nilaiPref) }}
                                        </span>
                                    </td>
                                @endif

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @else

        {{-- Tidak ada hasil --}}
        <div class="ui-card p-8 mb-6 text-center">

            <div class="w-14 h-14 rounded-full bg-[#fecaca] flex items-center
                        justify-center mx-auto mb-4">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="#8B0000"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4"/>
                    <path d="M12 16h.01"/>
                </svg>

            </div>

            <h3 class="text-base font-semibold text-[#1f2937]">
                Tidak Ada Hasil
            </h3>

            <p class="text-sm text-[#6b7280] mt-1">
                Data hasil TOPSIS untuk proses #{{ $run->id }} belum tersedia.
            </p>

        </div>

    @endif


    {{-- =====================================================
        TOMBOL AKSI
    ====================================================== --}}
    <div class="flex items-center gap-4 mt-2 pb-6">

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

        <a href="{{ route('ahp.index') }}"
           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl
                  border border-[#fecaca] text-[#8B0000] text-sm font-semibold
                  hover:bg-[#fef2f2] transition">

            Lihat AHP

        </a>

    </div>

</div>

@endsection
