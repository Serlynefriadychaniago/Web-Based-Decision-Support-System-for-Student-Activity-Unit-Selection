@extends('layouts.app')

@section('title', 'Edit UKM - SPK UKM')
@section('page-title', 'Edit UKM')

@section('content')

<div class="page-content">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="flex items-start justify-between mb-7">

        <div>
            <h1 class="text-2xl font-bold text-[#1f2937]">
                Edit Data UKM
            </h1>

            <p class="text-sm text-[#6b7280] mt-1">
                Perbarui informasi Unit Kegiatan Mahasiswa.
            </p>
        </div>

        <a href="{{ route('ukm.index') }}"
           class="inline-flex items-center gap-2
                  px-5 py-2.5
                  rounded-lg
                  bg-gray-100
                  hover:bg-gray-200
                  text-gray-700
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
                <polyline points="12 19 5 12 12 5"/>

            </svg>

            Kembali

        </a>

    </div>


    {{-- =====================================================
        ERROR VALIDASI
    ====================================================== --}}
    @if($errors->any())

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
                          d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 0 0 1.75 3h15.56a2 2 0 0 0 1.75-3l-7.82-14a2 2 0 0 0-3.42 0z"/>

                </svg>

                <div>

                    <p class="font-semibold text-sm mb-1">
                        Terdapat kesalahan:
                    </p>

                    <ul class="list-disc list-inside text-sm space-y-1">

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


    {{-- =====================================================
        SUCCESS
    ====================================================== --}}
    @if(session('success'))

        <div class="mb-6 flex items-center gap-3
                    px-5 py-4
                    bg-green-50
                    border border-green-200
                    rounded-xl
                    text-green-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5 flex-shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>

            </svg>

            <span class="text-sm font-medium">
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
        FORM EDIT UKM
    ====================================================== --}}
    <div class="ui-card overflow-hidden">

        {{-- HEADER CARD --}}
        <div class="px-6 py-5 border-b border-[#fecaca]">

            <h2 class="text-lg font-bold text-[#1f2937]">
                Informasi UKM
            </h2>

            <p class="text-sm text-[#6b7280] mt-1">
                Silakan perbarui data UKM sesuai informasi terbaru.
            </p>

        </div>


        {{-- =================================================
            FORM
        ================================================== --}}
        <form action="{{ route('ukm.update', $ukm->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')


            <div class="p-6 space-y-8">


                {{-- =================================================
                    INFORMASI DASAR
                ================================================== --}}
                <div>

                    <h3 class="text-base font-bold text-[#1f2937] mb-4">
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- NAMA UKM --}}
                        <div>

                            <label for="nama_ukm"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Nama UKM
                                <span class="text-red-500">*</span>

                            </label>

                            <input type="text"
                                   id="nama_ukm"
                                   name="nama_ukm"
                                   value="{{ old('nama_ukm', $ukm->nama_ukm) }}"
                                   required
                                   class="w-full px-4 py-3
                                          rounded-lg
                                          border border-[#fecaca]
                                          bg-white
                                          text-[#1f2937]
                                          text-sm
                                          focus:outline-none
                                          focus:ring-2
                                          focus:ring-[#da2424]
                                          focus:border-transparent">

                            @error('nama_ukm')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- STATUS --}}
                        <div>

                            <label for="status"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Status
                                <span class="text-red-500">*</span>

                            </label>

                            <select id="status"
                                    name="status"
                                    required
                                    class="w-full px-4 py-3
                                           rounded-lg
                                           border border-[#fecaca]
                                           bg-white
                                           text-[#1f2937]
                                           text-sm
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-[#da2424]
                                           focus:border-transparent">

                                <option value="aktif"
                                    {{ old('status', $ukm->status) === 'aktif' ? 'selected' : '' }}>

                                    Aktif

                                </option>

                                <option value="nonaktif"
                                    {{ old('status', $ukm->status) === 'nonaktif' ? 'selected' : '' }}>

                                    Nonaktif

                                </option>

                            </select>

                            @error('status')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- DESKRIPSI --}}
                        <div class="md:col-span-2">

                            <label for="deskripsi"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Deskripsi

                            </label>

                            <textarea id="deskripsi"
                                      name="deskripsi"
                                      rows="4"
                                      class="w-full px-4 py-3
                                             rounded-lg
                                             border border-[#fecaca]
                                             bg-white
                                             text-[#1f2937]
                                             text-sm
                                             resize-none
                                             focus:outline-none
                                             focus:ring-2
                                             focus:ring-[#da2424]
                                             focus:border-transparent">{{ old('deskripsi', $ukm->deskripsi) }}</textarea>

                            @error('deskripsi')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    VISI MISI
                ================================================== --}}
                <div class="border-t border-[#eeeaf6] pt-7">

                    <h3 class="text-base font-bold text-[#1f2937] mb-4">
                        Visi dan Misi
                    </h3>

                    <div class="grid grid-cols-1 gap-5">


                        {{-- VISI --}}
                        <div>

                            <label for="visi"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Visi

                            </label>

                            <textarea id="visi"
                                      name="visi"
                                      rows="3"
                                      class="w-full px-4 py-3
                                             rounded-lg
                                             border border-[#fecaca]
                                             bg-white
                                             text-[#1f2937]
                                             text-sm
                                             resize-none
                                             focus:outline-none
                                             focus:ring-2
                                             focus:ring-[#da2424]
                                             focus:border-transparent">{{ old('visi', $ukm->visi) }}</textarea>

                            @error('visi')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- MISI --}}
                        <div>

                            <label for="misi"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Misi

                            </label>

                            <textarea id="misi"
                                      name="misi"
                                      rows="5"
                                      class="w-full px-4 py-3
                                             rounded-lg
                                             border border-[#fecaca]
                                             bg-white
                                             text-[#1f2937]
                                             text-sm
                                             resize-none
                                             focus:outline-none
                                             focus:ring-2
                                             focus:ring-[#da2424]
                                             focus:border-transparent">{{ old('misi', $ukm->misi) }}</textarea>

                            @error('misi')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PENGURUS
                ================================================== --}}
                <div class="border-t border-[#eeeaf6] pt-7">

                    <h3 class="text-base font-bold text-[#1f2937] mb-4">
                        Pengurus UKM
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- PEMBINA --}}
                        <div>

                            <label for="pembina"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Pembina

                            </label>

                            <input type="text"
                                   id="pembina"
                                   name="pembina"
                                   value="{{ old('pembina', $ukm->pembina) }}"
                                   class="w-full px-4 py-3
                                          rounded-lg
                                          border border-[#fecaca]
                                          bg-white
                                          text-[#1f2937]
                                          text-sm
                                          focus:outline-none
                                          focus:ring-2
                                          focus:ring-[#da2424]
                                          focus:border-transparent">

                            @error('pembina')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- KETUA --}}
                        <div>

                            <label for="ketua"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Ketua

                            </label>

                            <input type="text"
                                   id="ketua"
                                   name="ketua"
                                   value="{{ old('ketua', $ukm->ketua) }}"
                                   class="w-full px-4 py-3
                                          rounded-lg
                                          border border-[#fecaca]
                                          bg-white
                                          text-[#1f2937]
                                          text-sm
                                          focus:outline-none
                                          focus:ring-2
                                          focus:ring-[#da2424]
                                          focus:border-transparent">

                            @error('ketua')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    KONTAK
                ================================================== --}}
                <div class="border-t border-[#eeeaf6] pt-7">

                    <h3 class="text-base font-bold text-[#1f2937] mb-4">
                        Informasi Kontak
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- KONTAK --}}
                        <div>

                            <label for="kontak"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Nomor Kontak

                            </label>

                            <input type="text"
                                   id="kontak"
                                   name="kontak"
                                   value="{{ old('kontak', $ukm->kontak) }}"
                                   class="w-full px-4 py-3
                                          rounded-lg
                                          border border-[#fecaca]
                                          bg-white
                                          text-[#1f2937]
                                          text-sm
                                          focus:outline-none
                                          focus:ring-2
                                          focus:ring-[#da2424]
                                          focus:border-transparent">

                            @error('kontak')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <label for="email"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Email

                            </label>

                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $ukm->email) }}"
                                   class="w-full px-4 py-3
                                          rounded-lg
                                          border border-[#fecaca]
                                          bg-white
                                          text-[#1f2937]
                                          text-sm
                                          focus:outline-none
                                          focus:ring-2
                                          focus:ring-[#da2424]
                                          focus:border-transparent">

                            @error('email')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- INSTAGRAM --}}
                        <div>

                            <label for="instagram"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Instagram

                            </label>

                            <input type="url"
                                   id="instagram"
                                   name="instagram"
                                   value="{{ old('instagram', $ukm->instagram) }}"
                                   placeholder="https://www.instagram.com/ukm_umc/"
                                   class="w-full px-4 py-3
                                          rounded-lg
                                          border border-[#fecaca]
                                          bg-white
                                          text-[#1f2937]
                                          text-sm
                                          focus:outline-none
                                          focus:ring-2
                                          focus:ring-[#da2424]
                                          focus:border-transparent">

                            <p class="text-xs text-gray-500 mt-1">
                                Masukkan link lengkap akun Instagram UKM.
                            </p>

                            @error('instagram')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- LOKASI --}}
                        <div>

                            <label for="lokasi_sekretariat"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Lokasi Sekretariat

                            </label>

                            <input type="text"
                                   id="lokasi_sekretariat"
                                   name="lokasi_sekretariat"
                                   value="{{ old('lokasi_sekretariat', $ukm->lokasi_sekretariat) }}"
                                   class="w-full px-4 py-3
                                          rounded-lg
                                          border border-[#fecaca]
                                          bg-white
                                          text-[#1f2937]
                                          text-sm
                                          focus:outline-none
                                          focus:ring-2
                                          focus:ring-[#da2424]
                                          focus:border-transparent">

                            @error('lokasi_sekretariat')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- HARI LATIHAN --}}
                        <div class="md:col-span-2">

                            <label for="hari_latihan"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Hari / Jadwal Latihan

                            </label>

                            <textarea id="hari_latihan"
                                      name="hari_latihan"
                                      rows="3"
                                      class="w-full px-4 py-3
                                             rounded-lg
                                             border border-[#fecaca]
                                             bg-white
                                             text-[#1f2937]
                                             text-sm
                                             resize-none
                                             focus:outline-none
                                             focus:ring-2
                                             focus:ring-[#da2424]
                                             focus:border-transparent">{{ old('hari_latihan', $ukm->hari_latihan) }}</textarea>

                            @error('hari_latihan')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    LOGO
                ================================================== --}}
                <div class="border-t border-[#eeeaf6] pt-7">

                    <h3 class="text-base font-bold text-[#1f2937] mb-4">
                        Logo UKM
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        {{-- LOGO SAAT INI --}}
                        <div>

                            <p class="text-sm font-semibold text-[#55506f] mb-2">
                                Logo Saat Ini
                            </p>

                            @if(!empty($ukm->logo))

                                <div class="inline-flex
                                            items-center
                                            justify-center
                                            w-32 h-32
                                            rounded-2xl
                                            border
                                            border-[#fecaca]
                                            bg-white
                                            p-3">

                                    <img
                                        src="{{ asset('storage/' . $ukm->logo) }}"
                                        alt="Logo {{ $ukm->nama_ukm }}"
                                        class="max-w-full
                                               max-h-full
                                               object-contain"
                                    >

                                </div>

                            @else

                                <div class="w-32 h-32
                                            rounded-2xl
                                            bg-[#fee2e2]
                                            flex
                                            items-center
                                            justify-center
                                            text-[#da2424]
                                            text-4xl
                                            font-bold">

                                    {{ strtoupper(substr($ukm->nama_ukm ?? 'U', 0, 1)) }}

                                </div>

                                <p class="text-xs text-[#6b7280] mt-2">
                                    Belum ada logo.
                                </p>

                            @endif

                        </div>


                        {{-- UPLOAD LOGO BARU --}}
                        <div>

                            <label for="logo"
                                   class="block text-sm font-semibold text-[#55506f] mb-2">

                                Ganti Logo

                            </label>

                            <input type="file"
                                   id="logo"
                                   name="logo"
                                   accept="image/*"
                                   class="block w-full
                                          text-sm
                                          text-[#55506f]
                                          border
                                          border-[#fecaca]
                                          rounded-lg
                                          cursor-pointer
                                          bg-white
                                          focus:outline-none">

                            <p class="text-xs text-[#6b7280] mt-2">
                                Format gambar JPG, JPEG, PNG, atau WEBP.
                            </p>

                            @error('logo')
                                <p class="mt-1 text-xs text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PARAMETER KRITERIA SPK
                ================================================== --}}
                <div class="border-t border-[#eeeaf6] pt-7">

                    <h3 class="text-base font-bold text-[#1f2937] mb-1">
                        Parameter Kriteria SPK
                    </h3>

                    <p class="text-xs text-[#6b7280] mb-4">
                        Perbarui nilai 5 parameter kriteria SPK untuk UKM ini (Minat, Bakat, Jadwal, Prestasi, Biaya).
                    </p>

                    <div class="space-y-4">

                        @if(isset($kriteriaList) && $kriteriaList->count() > 0)

                            @foreach($kriteriaList as $item)

                                @php
                                    $prof = isset($existingProfiles) ? $existingProfiles->get($item->id) : null;
                                    $savedSubId = $prof ? $prof->sub_kriteria_id : null;
                                    $savedNilai = $prof ? $prof->nilai : 5.0;

                                    $oldSubId = old("kriteria_profile.{$item->id}.sub_kriteria_id", $savedSubId);
                                    $oldNilai = old("kriteria_profile.{$item->id}.nilai", $savedNilai);
                                @endphp

                                <div class="p-4 bg-[#fdf2f2] border border-[#fecaca] rounded-xl space-y-3">

                                    <div class="flex items-center justify-between">

                                        <div class="flex items-center gap-2">

                                            <span class="px-2.5 py-1 rounded-lg bg-[#fee2e2] text-[#da2424] text-xs font-bold font-mono">
                                                {{ $item->kode }}
                                            </span>

                                            <h4 class="text-sm font-bold text-[#1f2937]">
                                                {{ $item->nama_kriteria }}
                                            </h4>

                                        </div>

                                        <span class="text-xs font-semibold text-[#6b7280] capitalize">
                                            Tipe: {{ $item->tipe }}
                                        </span>

                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        {{-- SUB KRITERIA DROPDOWN --}}
                                        @if($item->subKriteria && $item->subKriteria->count() > 0)

                                            <div>

                                                <label class="block text-xs font-semibold text-[#55506f] mb-1.5">
                                                    Sub-Kriteria / Indikator <span class="text-red-500">*</span>
                                                </label>

                                                <select name="kriteria_profile[{{ $item->id }}][sub_kriteria_id]"
                                                        required
                                                        class="w-full px-4 py-3 rounded-lg border border-[#fecaca] bg-white text-[#1f2937] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424] focus:border-transparent">

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

                                            <label class="block text-xs font-semibold text-[#55506f] mb-1.5">
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
                                                   class="w-full px-4 py-3 rounded-lg border border-[#fecaca] bg-white text-[#1f2937] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424] focus:border-transparent">

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        @else

                            <p class="text-sm text-[#6b7280] italic">
                                Data kriteria SPK belum tersedia.
                            </p>

                        @endif

                    </div>

                </div>


            </div>



            {{-- =================================================
                FOOTER FORM
            ================================================== --}}
            <div class="px-6 py-5
                        border-t border-[#fecaca]
                        bg-[#fdf2f2]
                        flex items-center justify-end gap-3">

                <a href="{{ route('ukm.index') }}"
                   class="inline-flex items-center
                          px-5 py-2.5
                          rounded-lg
                          bg-white
                          border border-[#fecaca]
                          text-[#55506f]
                          hover:bg-gray-50
                          text-sm
                          font-semibold
                          transition">

                    Batal

                </a>


                <button type="submit"
                        class="inline-flex items-center gap-2
                               px-5 py-2.5
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

                        <path d="M19 21H5a2 2 0 0 1-2-2V5
                                 a2 2 0 0 1 2-2h11l5 5v11
                                 a2 2 0 0 1-2 2z"/>

                        <polyline points="17 21 17 13 7 13 7 21"/>

                        <polyline points="7 3 7 8 15 8"/>

                    </svg>

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection