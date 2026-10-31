@extends('layouts.app')

@section('title', 'Tambah Kriteria')
@section('page-title', 'Tambah Kriteria')

@section('content')

<div class="max-w-6xl mx-auto">

    {{-- ===================================================== --}}
    {{-- HEADER HALAMAN --}}
    {{-- ===================================================== --}}

    <div class="flex items-start justify-between mb-6">

        {{-- JUDUL --}}
        <div>

            <h1 class="text-2xl font-bold text-gray-900">
                Tambah Data Kriteria
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Tambahkan kriteria yang akan digunakan dalam proses AHP dan TOPSIS.
            </p>

        </div>


        {{-- BUTTON KEMBALI --}}
        <a href="{{ route('kriteria.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5
                  bg-gray-100 border border-gray-200
                  text-gray-700 text-sm font-semibold
                  rounded-xl
                  hover:bg-gray-200 hover:border-gray-300
                  transition duration-200">

            <span>Kembali</span>

            {{-- ICON PANAH KIRI --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9 5l7 7-7 7" />

            </svg>

        </a>

    </div>


    {{-- ===================================================== --}}
    {{-- ERROR VALIDASI --}}
    {{-- ===================================================== --}}

    @if($errors->any())

        <div class="mb-6 p-4
                    bg-red-50
                    border border-red-200
                    rounded-xl">

            <div class="flex items-start gap-3">

                {{-- ICON --}}
                <div class="flex-shrink-0">

                    <div class="w-9 h-9 rounded-full
                                bg-red-100
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-red-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 9v3.75m0 3.75h.007M10.29 3.86l-7.12 12a2 2 0 001.72 3h14.22a2 2 0 001.72-3l-7.12-12a2 2 0 00-3.44 0z" />

                        </svg>

                    </div>

                </div>


                {{-- PESAN ERROR --}}
                <div>

                    <p class="font-semibold text-red-700 mb-1">
                        Data belum dapat disimpan
                    </p>

                    <ul class="list-disc list-inside
                               text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ===================================================== --}}
    {{-- FORM UTAMA --}}
    {{-- ===================================================== --}}

    <div class="bg-white
                border border-gray-200
                rounded-2xl
                shadow-sm
                overflow-hidden">

        <form method="POST"
              action="{{ route('kriteria.store') }}">

            @csrf


            {{-- ================================================= --}}
            {{-- HEADER FORM --}}
            {{-- ================================================= --}}

            <div class="px-6 py-5
                        border-b border-gray-200">

                <div class="flex items-center gap-3">

                    {{-- ICON --}}
                    <div class="w-10 h-10
                                rounded-xl
                                bg-red-100
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-[#da2424]"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5h6" />

                        </svg>

                    </div>


                    <div>

                        <h2 class="text-lg font-semibold text-gray-900">
                            Informasi Kriteria
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Isi data kriteria dengan lengkap dan benar.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ISI FORM --}}
            {{-- ================================================= --}}

            <div class="p-6 space-y-7">


                {{-- ================================================= --}}
                {{-- KODE KRITERIA --}}
                {{-- ================================================= --}}

                <div>

                    <label for="kode"
                           class="block text-sm font-semibold
                                  text-gray-700 mb-2">

                        Kode Kriteria

                        <span class="text-red-500">*</span>

                    </label>


                    <input
                        type="text"
                        id="kode"
                        name="kode"
                        value="{{ old('kode') }}"
                        placeholder="Contoh: C1"
                        maxlength="10"
                        required
                        class="w-full
                               px-4 py-3
                               border border-gray-300
                               rounded-xl
                               text-sm
                               text-gray-800
                               placeholder-gray-400
                               focus:outline-none
                               focus:ring-2
                               focus:ring-red-500
                               focus:border-red-500
                               transition
                               uppercase">


                    <p class="mt-2 text-xs text-gray-500">

                        Gunakan kode unik, misalnya
                        <span class="font-semibold">C1</span>,
                        <span class="font-semibold">C2</span>,
                        <span class="font-semibold">C3</span>,
                        dan seterusnya.

                    </p>


                    @error('kode')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- NAMA KRITERIA --}}
                {{-- ================================================= --}}

                <div>

                    <label for="nama_kriteria"
                           class="block text-sm font-semibold
                                  text-gray-700 mb-2">

                        Nama Kriteria

                        <span class="text-red-500">*</span>

                    </label>


                    <input
                        type="text"
                        id="nama_kriteria"
                        name="nama_kriteria"
                        value="{{ old('nama_kriteria') }}"
                        placeholder="Contoh: Minat"
                        maxlength="50"
                        required
                        class="w-full
                               px-4 py-3
                               border border-gray-300
                               rounded-xl
                               text-sm
                               text-gray-800
                               placeholder-gray-400
                               focus:outline-none
                               focus:ring-2
                               focus:ring-red-500
                               focus:border-red-500
                               transition">


                    @error('nama_kriteria')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- TIPE KRITERIA --}}
                {{-- ================================================= --}}

                <div>

                    <label for="tipe"
                           class="block text-sm font-semibold
                                  text-gray-700 mb-2">

                        Tipe Kriteria

                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        id="tipe"
                        name="tipe"
                        required
                        class="w-full
                               px-4 py-3
                               border border-gray-300
                               rounded-xl
                               text-sm
                               text-gray-800
                               bg-white
                               focus:outline-none
                               focus:ring-2
                               focus:ring-red-500
                               focus:border-red-500
                               transition">

                        <option value="">
                            -- Pilih Tipe Kriteria --
                        </option>

                        <option value="benefit"
                            {{ old('tipe') === 'benefit' ? 'selected' : '' }}>

                            Benefit

                        </option>

                        <option value="cost"
                            {{ old('tipe') === 'cost' ? 'selected' : '' }}>

                            Cost

                        </option>

                    </select>


                    {{-- PENJELASAN TIPE --}}
                    <div class="mt-4
                                grid grid-cols-1
                                md:grid-cols-2
                                gap-4">


                        {{-- BENEFIT --}}
                        <div class="p-4
                                    bg-green-50
                                    border border-green-200
                                    rounded-xl">

                            <div class="flex items-start gap-3">

                                <div class="w-9 h-9
                                            rounded-lg
                                            bg-green-100
                                            flex items-center justify-center
                                            flex-shrink-0">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5 text-green-600"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M5 10l7-7m0 0l7 7m-7-7v18" />

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-sm
                                              font-semibold
                                              text-green-700">

                                        Benefit

                                    </p>

                                    <p class="text-xs
                                              text-green-600
                                              mt-1
                                              leading-relaxed">

                                        Semakin tinggi nilai kriteria,
                                        semakin baik.

                                    </p>

                                </div>

                            </div>

                        </div>


                        {{-- COST --}}
                        <div class="p-4
                                    bg-red-50
                                    border border-red-200
                                    rounded-xl">

                            <div class="flex items-start gap-3">

                                <div class="w-9 h-9
                                            rounded-lg
                                            bg-red-100
                                            flex items-center
                                            justify-center
                                            flex-shrink-0">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5 text-red-600"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M19 14l-7 7m0 0l-7-7m7 7V3" />

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-sm
                                              font-semibold
                                              text-red-700">

                                        Cost

                                    </p>

                                    <p class="text-xs
                                              text-red-600
                                              mt-1
                                              leading-relaxed">

                                        Semakin rendah nilai kriteria,
                                        semakin baik.

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    @error('tipe')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FOOTER FORM --}}
            {{-- ================================================= --}}

            <div class="px-6 py-5
                        bg-gray-50
                        border-t border-gray-200
                        flex items-center
                        justify-end gap-3">


                {{-- BATAL --}}
                <a href="{{ route('kriteria.index') }}"
                   class="inline-flex items-center
                          px-5 py-2.5
                          bg-white
                          border border-gray-300
                          text-gray-700
                          text-sm font-semibold
                          rounded-xl
                          hover:bg-gray-100
                          hover:border-gray-400
                          transition">

                    Batal

                </a>


                {{-- SIMPAN --}}
                <button
                    type="submit"
                    class="inline-flex items-center
                           gap-2
                           px-5 py-2.5
                           bg-[#da2424]
                           text-white
                           text-sm font-semibold
                           rounded-xl
                           shadow-sm
                           hover:bg-[#b91c1c]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-red-500
                           focus:ring-offset-2
                           transition">


                    {{-- ICON SAVE --}}
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 5a2 2 0 012-2h8.586a2 2 0 011.414.586l2.414 2.414A2 2 0 0120 7.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 3v5h6V3M9 21v-6h6v6" />

                    </svg>


                    Simpan Kriteria

                </button>

            </div>

        </form>

    </div>

</div>

@endsection