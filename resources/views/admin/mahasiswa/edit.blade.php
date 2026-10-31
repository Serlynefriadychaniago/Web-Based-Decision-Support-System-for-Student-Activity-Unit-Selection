@extends('layouts.app')

@section('title', 'Edit Mahasiswa')
@section('page-title', 'Edit Data Mahasiswa')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#1f2937]">
                Edit Data Mahasiswa
            </h1>
            <p class="text-sm text-[#6b7280] mt-2">
                Perbarui informasi akademik dan akun login mahasiswa: <strong>{{ $mahasiswa->nama }}</strong>
            </p>
        </div>

        <a href="{{ route('admin.mahasiswa.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-[#fecaca] text-[#da2424] font-semibold text-sm hover:bg-[#fef2f2] transition">
            ← Kembali
        </a>
    </div>

    {{-- NOTIFIKASI ERROR VALIDASI --}}
    @if(isset($errors) && $errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">
            <div class="font-bold mb-1">Terjadi kesalahan input:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm overflow-hidden p-8">
        <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="border-b border-[#fecaca] pb-4 mb-4">
                <h3 class="text-base font-bold text-[#1f2937]">1. Informasi Akademik & Pribadi</h3>
                <p class="text-xs text-[#6b7280] mt-0.5">Perbarui identitas mahasiswa Universitas Muhammadiyah Cirebon.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- NIM --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">NIM <span class="text-red-500">*</span></label>
                    <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>

                {{-- NAMA LENGKAP --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>

                {{-- PROGRAM STUDI --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Program Studi <span class="text-red-500">*</span></label>
                    <input type="text" name="prodi" value="{{ old('prodi', $mahasiswa->prodi) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>

                {{-- FAKULTAS --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Fakultas</label>
                    <input type="text" name="fakultas" value="{{ old('fakultas', $mahasiswa->fakultas) }}"
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>

                {{-- ANGKATAN --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Angkatan</label>
                    <input type="text" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>

                {{-- JENIS KELAMIN --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                            class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition bg-white">
                        <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                {{-- NO HP --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>
            </div>

            <div class="border-b border-[#fecaca] pb-4 mb-4 pt-4">
                <h3 class="text-base font-bold text-[#1f2937]">2. Akun Login Sistem</h3>
                <p class="text-xs text-[#6b7280] mt-0.5">Kosongkan kolom password jika tidak ingin mengganti password.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- USERNAME --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $mahasiswa->username) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $mahasiswa->email) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>

                {{-- PASSWORD BARU (OPTIONAL) --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">Password Baru (Opsional)</label>
                    <input type="password" name="password" placeholder="Isi jika ingin mereset password"
                           class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-sm focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition">
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-[#fecaca]">
                <a href="{{ route('admin.mahasiswa.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-[#da2424] text-white font-semibold text-sm hover:bg-[#b91c1c] shadow-sm transition">
                    Perbarui Data Mahasiswa
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
