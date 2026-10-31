@extends('layouts.app')

@section('title', 'Tambah UKM - SPK UKM')

@section('page-title', 'Tambah UKM')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="flex items-center justify-between mb-6">

        {{-- JUDUL --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Tambah Data UKM
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan data Unit Kegiatan Mahasiswa.
            </p>
        </div>

        {{-- TOMBOL KEMBALI --}}
        <a href="{{ route('ukm.index') }}"
           class="inline-flex items-center gap-2
                  px-5 py-2.5
                  bg-gray-100
                  text-gray-700
                  font-medium
                  rounded-xl
                  border border-gray-200
                  hover:bg-gray-200
                  transition">

            <span>Kembali</span>

            {{-- ICON PANAH --}}
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 5l7 7-7 7"
                />
            </svg>

        </a>

    </div>


    {{-- =====================================================
         PESAN ERROR
    ====================================================== --}}
    @if ($errors->any())

        <div class="mb-6
                    bg-red-50
                    border border-red-200
                    text-red-700
                    rounded-xl
                    p-4">

            <div class="flex items-start gap-3">

                {{-- ICON --}}
                <div class="flex-shrink-0">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-red-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3.75h.007M10.29 3.86l-7.5 13A1.5 1.5 0 004.09 19h15.82a1.5 1.5 0 001.3-2.14l-7.5-13a1.5 1.5 0 00-2.6 0z"
                        />
                    </svg>

                </div>

                <div>

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

            </div>

        </div>

    @endif


    {{-- =====================================================
         FORM
    ====================================================== --}}
    <form
        action="{{ route('ukm.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="bg-white
                    rounded-2xl
                    border border-gray-200
                    shadow-sm
                    overflow-hidden">


            {{-- =================================================
                 INFORMASI UKM
            ================================================== --}}
            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-800">
                    Informasi UKM
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Masukkan informasi dasar mengenai UKM.
                </p>

            </div>


            <div class="p-6 space-y-6">


                {{-- =================================================
                     NAMA UKM
                ================================================== --}}
                <div>

                    <label
                        for="nama_ukm"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Nama UKM
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="nama_ukm"
                        name="nama_ukm"
                        value="{{ old('nama_ukm') }}"
                        placeholder="Contoh: Computer Education"
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
                               transition"
                    >

                    @error('nama_ukm')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- =================================================
                     LOGO UKM
                ================================================== --}}
                <div>

                    <label
                        for="logo"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Logo UKM
                    </label>

                    <div class="border-2
                                border-dashed
                                border-gray-300
                                rounded-2xl
                                p-8
                                bg-gray-50
                                hover:border-red-400
                                transition">

                        <div class="flex flex-col items-center justify-center">


                            {{-- PREVIEW --}}
                            <div
                                id="logo-preview-container"
                                class="hidden mb-5"
                            >

                                <img
                                    id="logo-preview"
                                    src="#"
                                    alt="Preview Logo"
                                    class="w-32 h-32
                                           object-contain
                                           rounded-xl
                                           border border-gray-200
                                           bg-white
                                           p-2
                                           shadow-sm"
                                >

                            </div>


                            {{-- PLACEHOLDER ICON --}}
                            <div
                                id="logo-placeholder"
                                class="w-20 h-20
                                       rounded-full
                                       bg-red-100
                                       flex items-center justify-center
                                       mb-5"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-10 h-10 text-[#da2424]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8.5 8.5h.01"
                                    />

                                </svg>

                            </div>


                            {{-- BUTTON PILIH LOGO --}}
                            <label
                                for="logo"
                                class="cursor-pointer
                                       inline-flex
                                       items-center
                                       gap-2
                                       px-5 py-2.5
                                       bg-[#da2424]
                                       text-white
                                       rounded-xl
                                       font-semibold
                                       text-sm
                                       hover:bg-[#b91c1c]
                                       transition
                                       shadow-sm"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>

                                Pilih Logo

                            </label>


                            <input
                                type="file"
                                id="logo"
                                name="logo"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                            >


                            <p class="mt-3 text-sm text-gray-500 text-center">
                                JPG, JPEG, PNG, atau WEBP
                            </p>

                            <p class="text-xs text-gray-400 mt-1">
                                Maksimal 2 MB
                            </p>

                        </div>

                    </div>


                    @error('logo')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- =================================================
                     DESKRIPSI
                ================================================== --}}
                <div>

                    <label
                        for="deskripsi"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Deskripsi
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        placeholder="Jelaskan secara singkat mengenai UKM..."
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
                               transition"
                    >{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- =================================================
                     VISI
                ================================================== --}}
                <div>

                    <label
                        for="visi"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Visi
                    </label>

                    <textarea
                        id="visi"
                        name="visi"
                        rows="3"
                        placeholder="Masukkan visi UKM..."
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
                               transition"
                    >{{ old('visi') }}</textarea>

                </div>


                {{-- =================================================
                     MISI
                ================================================== --}}
                <div>

                    <label
                        for="misi"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Misi
                    </label>

                    <textarea
                        id="misi"
                        name="misi"
                        rows="4"
                        placeholder="Masukkan misi UKM..."
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
                               transition"
                    >{{ old('misi') }}</textarea>

                </div>

            </div>


            {{-- =====================================================
                 PENGURUS DAN KONTAK
            ====================================================== --}}
            <div class="px-6 py-5 border-t border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-800">
                    Pengurus dan Kontak
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informasi pengurus dan kontak UKM.
                </p>

            </div>


            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">


                {{-- PEMBINA --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Pembina
                    </label>

                    <input
                        type="text"
                        name="pembina"
                        value="{{ old('pembina') }}"
                        placeholder="Nama pembina"
                        class="w-full px-4 py-3 border border-gray-300
                               rounded-xl text-sm
                               focus:outline-none focus:ring-2
                               focus:ring-red-500 focus:border-red-500"
                    >

                </div>


                {{-- KETUA --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Ketua
                    </label>

                    <input
                        type="text"
                        name="ketua"
                        value="{{ old('ketua') }}"
                        placeholder="Nama ketua"
                        class="w-full px-4 py-3 border border-gray-300
                               rounded-xl text-sm
                               focus:outline-none focus:ring-2
                               focus:ring-red-500 focus:border-red-500"
                    >

                </div>


                {{-- KONTAK --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor Kontak
                    </label>

                    <input
                        type="text"
                        name="kontak"
                        value="{{ old('kontak') }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full px-4 py-3 border border-gray-300
                               rounded-xl text-sm
                               focus:outline-none focus:ring-2
                               focus:ring-red-500 focus:border-red-500"
                    >

                </div>


                {{-- EMAIL --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email UKM
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="ukm@umc.ac.id"
                        class="w-full px-4 py-3 border border-gray-300
                               rounded-xl text-sm
                               focus:outline-none focus:ring-2
                               focus:ring-red-500 focus:border-red-500"
                    >

                </div>


                {{-- INSTAGRAM --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Instagram
                    </label>

                    <input
                        type="url"
                        name="instagram"
                        value="{{ old('instagram') }}"
                        placeholder="https://www.instagram.com/ukm_umc/"
                        class="w-full px-4 py-3 border border-gray-300
                               rounded-xl text-sm
                               focus:outline-none focus:ring-2
                               focus:ring-red-500 focus:border-red-500"
                    >

                    <p class="text-xs text-gray-500 mt-1">
                        Masukkan link lengkap akun Instagram UKM.
                    </p>

                </div>


                {{-- SEKRETARIAT --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Lokasi Sekretariat
                    </label>

                    <input
                        type="text"
                        name="lokasi_sekretariat"
                        value="{{ old('lokasi_sekretariat') }}"
                        placeholder="Lokasi sekretariat"
                        class="w-full px-4 py-3 border border-gray-300
                               rounded-xl text-sm
                               focus:outline-none focus:ring-2
                               focus:ring-red-500 focus:border-red-500"
                    >

                </div>

            </div>


            {{-- =====================================================
                 KEGIATAN
            ====================================================== --}}
            <div class="px-6 py-5 border-t border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-800">
                    Kegiatan UKM
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informasi jadwal kegiatan UKM.
                </p>

            </div>


            <div class="p-6">

                <label
                    for="hari_latihan"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Jadwal Latihan
                </label>

                <input
                    type="text"
                    id="hari_latihan"
                    name="hari_latihan"
                    value="{{ old('hari_latihan') }}"
                    placeholder="Contoh: Senin dan Rabu, 16.00 - 18.00"
                    class="w-full px-4 py-3 border border-gray-300
                           rounded-xl text-sm
                           focus:outline-none focus:ring-2
                           focus:ring-red-500 focus:border-red-500"
                >

            </div>


            {{-- =====================================================
                 STATUS
            ====================================================== --}}
            <div class="p-6 border-t border-gray-200">

                <label
                    for="status"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Status UKM
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full md:w-1/2 px-4 py-3
                           border border-gray-300
                           rounded-xl text-sm
                           focus:outline-none
                           focus:ring-2
                           focus:ring-red-500
                           focus:border-red-500"
                >

                    <option value="aktif"
                        {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="nonaktif"
                        {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>

                </select>

            </div>


            {{-- =====================================================
                 PARAMETER KRITERIA SPK
            ====================================================== --}}
            <div class="px-6 py-5 border-t border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-800">
                    Parameter Kriteria SPK
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Tentukan nilai dan indikator profil UKM untuk 5 kriteria TOPSIS (Minat, Bakat, Jadwal, Prestasi, Biaya).
                </p>

            </div>


            <div class="p-6 space-y-5">

                @if(isset($kriteriaList) && $kriteriaList->count() > 0)

                    @foreach($kriteriaList as $item)

                        @php
                            $oldSubId = old("kriteria_profile.{$item->id}.sub_kriteria_id");
                            $oldNilai = old("kriteria_profile.{$item->id}.nilai", 5.0);
                        @endphp

                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-3">

                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-2">

                                    <span class="px-2.5 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-bold font-mono">
                                        {{ $item->kode }}
                                    </span>

                                    <h3 class="text-sm font-bold text-gray-800">
                                        {{ $item->nama_kriteria }}
                                    </h3>

                                </div>

                                <span class="text-xs font-semibold text-gray-500 capitalize">
                                    Tipe: {{ $item->tipe }}
                                </span>

                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- SUB KRITERIA DROPDOWN --}}
                                @if($item->subKriteria && $item->subKriteria->count() > 0)

                                    <div>

                                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                            Sub-Kriteria / Indikator <span class="text-red-500">*</span>
                                        </label>

                                        <select name="kriteria_profile[{{ $item->id }}][sub_kriteria_id]"
                                                required
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">

                                            <option value="">-- Pilih Sub-Kriteria --</option>

                                            @foreach($item->subKriteria as $sub)

                                                <option value="{{ $sub->id }}" {{ (string)$oldSubId === (string)$sub->id ? 'selected' : '' }}>
                                                    {{ $sub->nama_subkriteria }}
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                @endif

                                {{-- NILAI INPUT --}}
                                <div>

                                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Nilai Parameter (Skala 1 - 5) <span class="text-red-500">*</span>
                                    </label>

                                    <input type="number"
                                           name="kriteria_profile[{{ $item->id }}][nilai]"
                                           value="{{ $oldNilai }}"
                                           min="1"
                                           max="5"
                                           step="0.5"
                                           required
                                           placeholder="1 - 5"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">

                                </div>

                            </div>

                        </div>

                    @endforeach

                @else

                    <p class="text-sm text-gray-500 italic">
                        Data kriteria SPK belum tersedia.
                    </p>

                @endif

            </div>



            {{-- =====================================================
                 BUTTON SIMPAN
            ====================================================== --}}
            <div class="px-6 py-5
                        bg-gray-50
                        border-t border-gray-200
                        flex items-center
                        justify-end gap-3">

                {{-- BATAL --}}
                <a
                    href="{{ route('ukm.index') }}"
                    class="inline-flex items-center
                           px-5 py-2.5
                           bg-white
                           border border-gray-300
                           text-gray-700
                           font-semibold
                           rounded-xl
                           hover:bg-gray-100
                           transition"
                >
                    Batal
                </a>


                {{-- SIMPAN --}}
                <button
                    type="submit"
                    class="inline-flex items-center gap-2
                           px-6 py-2.5
                           bg-[#da2424]
                           text-white
                           font-semibold
                           rounded-xl
                           hover:bg-[#b91c1c]
                           focus:outline-none
                           focus:ring-2
                           focus:ring-red-500
                           focus:ring-offset-2
                           transition
                           shadow-sm"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    Simpan UKM

                </button>

            </div>

        </div>

    </form>

</div>


{{-- =====================================================
     PREVIEW LOGO
====================================================== --}}
<script>

    document.getElementById('logo').addEventListener('change', function(event) {

        const file = event.target.files[0];

        const preview = document.getElementById('logo-preview');
        const previewContainer = document.getElementById('logo-preview-container');
        const placeholder = document.getElementById('logo-placeholder');

        if (!file) {

            previewContainer.classList.add('hidden');
            placeholder.classList.remove('hidden');
            preview.src = '#';

            return;
        }


        // CEK UKURAN FILE
        if (file.size > 2 * 1024 * 1024) {

            alert('Ukuran logo maksimal 2 MB.');

            event.target.value = '';

            previewContainer.classList.add('hidden');
            placeholder.classList.remove('hidden');

            return;
        }


        // CEK TIPE FILE
        if (!file.type.startsWith('image/')) {

            alert('File yang dipilih harus berupa gambar.');

            event.target.value = '';

            previewContainer.classList.add('hidden');
            placeholder.classList.remove('hidden');

            return;
        }


        // PREVIEW GAMBAR
        const reader = new FileReader();

        reader.onload = function(e) {

            preview.src = e.target.result;

            previewContainer.classList.remove('hidden');
            placeholder.classList.add('hidden');

        };

        reader.readAsDataURL(file);

    });

</script>

@endsection