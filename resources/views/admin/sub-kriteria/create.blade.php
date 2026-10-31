@extends('layouts.app')

@section('title', 'Tambah Sub-Kriteria')
@section('page-title', 'Tambah Sub-Kriteria')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="flex items-start justify-between mb-7">

        <div>

            <h1 class="text-2xl font-bold text-[#1f2937]">
                Tambah Sub-Kriteria
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Tambahkan sub-kriteria baru untuk kebutuhan penilaian.
            </p>

        </div>


        {{-- KEMBALI --}}

        <a href="{{ route('sub-kriteria.index') }}"
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
                      d="M9 5l7 7-7 7"/>

            </svg>

        </a>

    </div>


    {{-- =====================================================
         ERROR
    ====================================================== --}}

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

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         FORM
    ====================================================== --}}

    <form action="{{ route('sub-kriteria.store') }}"
          method="POST">

        @csrf


        <div class="bg-white
                    rounded-2xl
                    border border-[#fecaca]
                    shadow-sm
                    overflow-hidden">


            {{-- HEADER FORM --}}

            <div class="px-6 py-5
                        border-b border-[#fecaca]">

                <h2 class="text-lg
                           font-semibold
                           text-[#1f2937]">

                    Informasi Sub-Kriteria

                </h2>

                <p class="text-sm
                          text-[#6b7280]
                          mt-1">

                    Isi data sub-kriteria dengan lengkap.

                </p>

            </div>


            {{-- ISI FORM --}}

            <div class="p-6 space-y-6">


                {{-- KRITERIA --}}

                <div>

                    <label for="kriteria_id"
                           class="block
                                  text-sm
                                  font-semibold
                                  text-gray-700
                                  mb-2">

                        Kriteria
                        <span class="text-red-500">*</span>

                    </label>


                    <select id="kriteria_id"
                            name="kriteria_id"
                            required
                            class="w-full
                                   rounded-xl
                                   border-gray-300
                                   focus:border-red-500
                                   focus:ring-red-500">

                        <option value="">
                            -- Pilih Kriteria --
                        </option>


                        @foreach($kriteria as $item)

                            <option value="{{ $item->id }}"
                                {{ old('kriteria_id') == $item->id ? 'selected' : '' }}>

                                {{ $item->kode }} - {{ $item->nama_kriteria }}

                            </option>

                        @endforeach

                    </select>


                    @error('kriteria_id')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- NAMA SUB-KRITERIA --}}

                <div>

                    <label for="nama_subkriteria"
                           class="block
                                  text-sm
                                  font-semibold
                                  text-gray-700
                                  mb-2">

                        Nama Sub-Kriteria
                        <span class="text-red-500">*</span>

                    </label>


                    <input type="text"
                           id="nama_subkriteria"
                           name="nama_subkriteria"
                           value="{{ old('nama_subkriteria') }}"
                           required
                           maxlength="150"
                           placeholder="Contoh: Sangat Berminat"
                           class="w-full
                                  rounded-xl
                                  border-gray-300
                                  focus:border-red-500
                                  focus:ring-red-500">


                    @error('nama_subkriteria')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- URUTAN --}}

                <div>

                    <label for="urutan"
                           class="block
                                  text-sm
                                  font-semibold
                                  text-gray-700
                                  mb-2">

                        Urutan

                    </label>


                    <input type="number"
                           id="urutan"
                           name="urutan"
                           value="{{ old('urutan') }}"
                           min="1"
                           placeholder="Contoh: 1"
                           class="w-full
                                  rounded-xl
                                  border-gray-300
                                  focus:border-red-500
                                  focus:ring-red-500">


                    @error('urutan')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- STATUS --}}

                <div>

                    <label for="status"
                           class="block
                                  text-sm
                                  font-semibold
                                  text-gray-700
                                  mb-2">

                        Status
                        <span class="text-red-500">*</span>

                    </label>


                    <select id="status"
                            name="status"
                            required
                            class="w-full
                                   rounded-xl
                                   border-gray-300
                                   focus:border-red-500
                                   focus:ring-red-500">

                        <option value="aktif"
                            {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>

                            Aktif

                        </option>

                        <option value="nonaktif"
                            {{ old('status') === 'nonaktif' ? 'selected' : '' }}>

                            Nonaktif

                        </option>

                    </select>


                    @error('status')

                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


            </div>


            {{-- =================================================
                 FOOTER FORM
            ================================================== --}}

            <div class="px-6 py-5
                        bg-[#fdf2f2]
                        border-t border-[#fecaca]
                        flex items-center justify-end gap-3">


                {{-- BATAL --}}

                <a href="{{ route('sub-kriteria.index') }}"
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


                {{-- SIMPAN --}}

                <button type="submit"
                        class="inline-flex
                               items-center
                               gap-2
                               px-5 py-2.5
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

                    Simpan Sub-Kriteria

                </button>

            </div>

        </div>

    </form>

</div>

@endsection