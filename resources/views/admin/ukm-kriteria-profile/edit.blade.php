@extends('layouts.app')

@section('title', 'Kelola Profil Kriteria UKM')
@section('page-title', 'Kelola Profil Kriteria UKM')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-7">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Kelola Profil Kriteria UKM
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Atur nilai profil kriteria untuk UKM
                {{ $ukm->nama_ukm }}.
            </p>

        </div>


        {{-- KEMBALI --}}
        <a href="{{ route('ukm-kriteria-profile.index') }}"
           class="inline-flex
                  items-center
                  gap-2
                  px-5 py-2.5
                  bg-white
                  text-gray-700
                  text-sm
                  font-semibold
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
                      d="M15 19l-7-7 7-7"/>

            </svg>

        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="mb-6
                    bg-green-50
                    border border-green-200
                    text-green-700
                    rounded-xl
                    p-4">

            <p class="text-sm font-semibold">
                {{ session('success') }}
            </p>

        </div>

    @endif


    {{-- ERROR --}}
    @if ($errors->any())

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

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- INFORMASI UKM --}}
    <div class="bg-white
                rounded-2xl
                border border-[#fecaca]
                shadow-sm
                p-6
                mb-6">

        <div class="flex items-center gap-4">

            {{-- LOGO --}}
            <div class="w-16 h-16
                        rounded-2xl
                        bg-[#fee2e2]
                        flex items-center justify-center
                        overflow-hidden
                        text-[#8B0000]
                        font-bold
                        text-xl">

                @if($ukm->logo)

                    <img src="{{ asset('storage/' . $ukm->logo) }}"
                         alt="{{ $ukm->nama_ukm }}"
                         class="w-full h-full object-cover">

                @else

                    {{ strtoupper(substr($ukm->nama_ukm, 0, 1)) }}

                @endif

            </div>


            <div>

                <p class="text-xs
                          font-semibold
                          text-[#6b7280]
                          uppercase
                          tracking-wide">

                    UKM yang sedang dikelola

                </p>

                <h2 class="text-xl
                           font-bold
                           text-[#1f2937]
                           mt-1">

                    {{ $ukm->nama_ukm }}

                </h2>

            </div>

        </div>

    </div>


    {{-- INFO SKALA --}}
    <div class="bg-[#fef2f2]
                border border-[#fecaca]
                rounded-2xl
                p-5
                mb-6">

        <div class="flex gap-3">

            <div class="text-[#8B0000] font-bold text-lg">
                ℹ
            </div>

            <div>

                <p class="text-sm
                          font-semibold
                          text-[#1f2937]">

                    Pengaturan Profil Kriteria UKM

                </p>

                <p class="text-xs
                          text-[#6b7280]
                          mt-1
                          leading-5">

                    Masukkan nilai sesuai tingkat relevansi
                    setiap sub-kriteria terhadap
                    <strong>{{ $ukm->nama_ukm }}</strong>.

                    <br>

                    <strong>C1 Minat dan C2 Bakat</strong>
                    menggunakan skala <strong>1–7</strong>.

                    <br>

                    <strong>C3 Jadwal, C4 Prestasi,
                    dan C5 Biaya</strong>
                    menggunakan skala <strong>0–5</strong>.

                </p>

            </div>

        </div>

    </div>


    {{-- FORM --}}
    <form action="{{ route('ukm-kriteria-profile.update', $ukm->id) }}"
          method="POST">

        @csrf
        @method('PUT')


        @foreach($kriteria as $item)

            @php

                $isC1C2 = in_array(
                    strtoupper($item->kode),
                    ['C1', 'C2']
                );

                $subItems = $item->subKriteria;

            @endphp


            {{-- CARD KRITERIA --}}
            <div class="bg-white
                        rounded-2xl
                        border border-[#fecaca]
                        shadow-sm
                        overflow-hidden
                        mb-6">


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
                                             w-10 h-10
                                             rounded-xl
                                             bg-[#fee2e2]
                                             text-[#8B0000]
                                             font-bold">

                                    {{ $item->kode }}

                                </span>


                                <div>

                                    <h2 class="text-lg
                                               font-bold
                                               text-[#1f2937]">

                                        {{ $item->nama_kriteria }}

                                    </h2>

                                    <p class="text-xs
                                              text-[#6b7280]
                                              mt-0.5">

                                        {{ $isC1C2 ? 'Skala nilai 1–7' : 'Skala nilai 0–5' }}

                                    </p>

                                </div>

                            </div>

                        </div>


                        <span class="hidden sm:inline-flex
                                     px-3 py-1
                                     rounded-full
                                     text-xs
                                     font-semibold
                                     bg-[#fee2e2]
                                     text-[#8B0000]">

                            {{ $subItems->count() }}
                            Sub-Kriteria

                        </span>

                    </div>

                </div>


                {{-- SUB KRITERIA --}}
                <div class="p-6">

                    @if($subItems->count() > 0)

                        <div class="space-y-4">

                            @foreach($subItems as $sub)

                                @php

                                    $key =
                                        $item->id .
                                        '-' .
                                        $sub->id;

                                    $existing =
                                        $profiles->get($key);

                                    $oldValue =
                                        old(
                                            'profiles.' .
                                            $item->id .
                                            '.' .
                                            $sub->id,
                                            $existing?->nilai
                                        );

                                @endphp


                                <div class="rounded-xl
                                            border border-[#f8c4c4]
                                            p-4
                                            hover:border-[#cfc3f0]
                                            transition">

                                    <div class="flex flex-col
                                                md:flex-row
                                                md:items-center
                                                md:justify-between
                                                gap-4">


                                        {{-- NAMA --}}
                                        <div class="flex-1">

                                            <p class="text-sm
                                                      font-semibold
                                                      text-[#1f2937]">

                                                {{ $sub->nama_subkriteria }}

                                            </p>

                                            <p class="text-xs
                                                      text-[#6b7280]
                                                      mt-1">

                                                Nilai Profil

                                                {{ $isC1C2
                                                    ? '(1–7)'
                                                    : '(0–5)' }}

                                            </p>

                                        </div>


                                        {{-- INPUT --}}
                                        <div class="w-full md:w-40">

                                            <input
                                                type="number"
                                                name="profiles[{{ $item->id }}][{{ $sub->id }}]"
                                                value="{{ $oldValue }}"
                                                min="{{ $isC1C2 ? 1 : 0 }}"
                                                max="{{ $isC1C2 ? 7 : 5 }}"
                                                step="1"
                                                required
                                                placeholder="{{ $isC1C2 ? '1–7' : '0–5' }}"
                                                class="w-full
                                                       rounded-xl
                                                       border-gray-300
                                                       text-center
                                                       font-semibold
                                                       text-[#1f2937]
                                                       focus:border-red-500
                                                       focus:ring-red-500">

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center
                                    py-8
                                    text-sm
                                    text-[#6b7280]">

                            Belum terdapat sub-kriteria aktif
                            untuk {{ $item->nama_kriteria }}.

                        </div>

                    @endif

                </div>

            </div>

        @endforeach


        {{-- FOOTER BUTTON --}}
        <div class="flex flex-col
                    sm:flex-row
                    justify-end
                    gap-3
                    pb-8">

            <a href="{{ route('ukm-kriteria-profile.index') }}"
               class="inline-flex
                      items-center
                      justify-center
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

                Batal

            </a>


            <button type="submit"
                    class="inline-flex
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

    </form>

</div>

@endsection