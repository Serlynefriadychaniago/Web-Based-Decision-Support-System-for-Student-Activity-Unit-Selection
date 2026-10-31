@extends('layouts.app')

@section('title', 'Edit Kriteria')
@section('page-title', 'Edit Kriteria')

@section('content')

<div class="page-content">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>
            <h1 class="text-2xl font-bold text-[#1f2937]">
                Edit Kriteria
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Perbarui informasi kriteria yang digunakan dalam
                sistem pendukung keputusan.
            </p>
        </div>

    </div>


    {{-- =====================================================
        ERROR VALIDASI
    ====================================================== --}}
    @if ($errors->any())

        <div class="mb-6
                    px-5 py-4
                    bg-red-50
                    border border-red-200
                    rounded-xl
                    text-red-700">

            <div class="flex items-start gap-3">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 flex-shrink-0 mt-0.5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"/>

                </svg>

                <div>

                    <p class="text-sm font-semibold mb-1">
                        Terdapat kesalahan pada data.
                    </p>

                    <ul class="text-sm list-disc list-inside space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- =====================================================
        INFORMASI
    ====================================================== --}}
    <div class="ui-card p-5 mb-6">

        <div class="flex items-start gap-4">

            <div class="w-11 h-11
                        rounded-xl
                        bg-[#fee2e2]
                        flex items-center justify-center
                        text-[#8B0000]
                        flex-shrink-0">

                <svg xmlns="http://www.w3.org/2000/svg"
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
                    Informasi Kriteria
                </h3>

                <p class="text-sm text-[#6b7280] mt-1 leading-6">
                    Silakan ubah data kriteria sesuai kebutuhan.
                    Kriteria ini akan digunakan dalam proses AHP
                    dan TOPSIS.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
        FORM
    ====================================================== --}}
    <form action="{{ route('kriteria.update', ['kriteria' => $kriteria->id]) }}"
          method="POST">

        @csrf

        @method('PUT')


        <div class="ui-card overflow-hidden">

            {{-- =================================================
                HEADER FORM
            ================================================== --}}
            <div class="px-6 py-5 border-b border-[#fecaca]">

                <h2 class="text-lg font-bold text-[#1f2937]">
                    Form Edit Kriteria
                </h2>

                <p class="text-sm text-[#6b7280] mt-1">
                    Perbarui data kriteria berikut.
                </p>

            </div>


            {{-- =================================================
                ISI FORM
            ================================================== --}}
            <div class="p-6 space-y-6">


                {{-- =================================================
                    KODE KRITERIA
                ================================================== --}}
                <div>

                    <label for="kode"
                           class="block text-sm font-semibold
                                  text-[#1f2937] mb-2">

                        Kode Kriteria

                    </label>

                    <input type="text"
                           id="kode"
                           name="kode"
                           value="{{ old('kode', $kriteria->kode) }}"
                           maxlength="10"
                           required
                           class="w-full
                                  rounded-xl
                                  border border-[#d8d2eb]
                                  bg-white
                                  px-4 py-3
                                  text-sm
                                  text-[#1f2937]
                                  outline-none
                                  transition
                                  focus:border-[#7045d9]
                                  focus:ring-2
                                  focus:ring-[#7045d9]/20">

                    @error('kode')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- =================================================
                    NAMA KRITERIA
                ================================================== --}}
                <div>

                    <label for="nama_kriteria"
                           class="block text-sm font-semibold
                                  text-[#1f2937] mb-2">

                        Nama Kriteria

                    </label>

                    <input type="text"
                           id="nama_kriteria"
                           name="nama_kriteria"
                           value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}"
                           maxlength="50"
                           required
                           class="w-full
                                  rounded-xl
                                  border border-[#d8d2eb]
                                  bg-white
                                  px-4 py-3
                                  text-sm
                                  text-[#1f2937]
                                  outline-none
                                  transition
                                  focus:border-[#7045d9]
                                  focus:ring-2
                                  focus:ring-[#7045d9]/20">

                    @error('nama_kriteria')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- =================================================
                    TIPE KRITERIA
                ================================================== --}}
                <div>

                    <label for="tipe"
                           class="block text-sm font-semibold
                                  text-[#1f2937] mb-2">

                        Tipe Kriteria

                    </label>

                    <select id="tipe"
                            name="tipe"
                            required
                            class="w-full
                                   rounded-xl
                                   border border-[#d8d2eb]
                                   bg-white
                                   px-4 py-3
                                   text-sm
                                   text-[#1f2937]
                                   outline-none
                                   transition
                                   focus:border-[#7045d9]
                                   focus:ring-2
                                   focus:ring-[#7045d9]/20">

                        <option value="benefit"
                            {{ old('tipe', $kriteria->tipe) === 'benefit' ? 'selected' : '' }}>

                            Benefit — semakin besar semakin baik

                        </option>

                        <option value="cost"
                            {{ old('tipe', $kriteria->tipe) === 'cost' ? 'selected' : '' }}>

                            Cost — semakin kecil semakin baik

                        </option>

                    </select>

                    @error('tipe')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


            </div>


            {{-- =================================================
                FOOTER FORM
            ================================================== --}}
            <div class="px-6 py-5
                        border-t border-[#fecaca]
                        bg-[#fdf2f2]
                        flex items-center
                        justify-between">


                {{-- KEMBALI --}}
                <a href="{{ route('kriteria.index') }}"
                   class="inline-flex
                          items-center
                          gap-2
                          px-5
                          py-2.5
                          rounded-lg
                          border border-[#d8d2eb]
                          bg-white
                          hover:bg-[#fef2f2]
                          text-[#392f78]
                          text-sm
                          font-semibold
                          transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="18"
                         height="18"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <path d="M19 12H5"/>
                        <path d="M12 19l-7-7 7-7"/>

                    </svg>

                    Kembali

                </a>


                {{-- SIMPAN --}}
                <button type="submit"
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

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="18"
                         height="18"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>

                    </svg>

                    Simpan Perubahan

                </button>

            </div>

        </div>

    </form>

</div>

@endsection