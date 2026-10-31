@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    {{-- HELPER PENGECEKAN KELENGKAPAN PROFIL --}}
    @php
        $userId = auth()->user()->id;
        $profileStats = \App\Http\Middleware\EnsureMahasiswaProfileComplete::checkProfileStats($userId);
        $isComplete = $profileStats['is_complete'];
        $filledCount = $profileStats['filled_count'];
        $totalFields = $profileStats['total_fields'];
        $percentage = $profileStats['percentage'];

        $jkRaw = $mahasiswa->jenis_kelamin ?? null;
        $jkText = 'Belum diisi';
        if ($jkRaw === 'L' || $jkRaw === 'Laki-laki') $jkText = 'Laki-laki';
        if ($jkRaw === 'P' || $jkRaw === 'Perempuan') $jkText = 'Perempuan';
    @endphp

    {{-- HEADER BANNER SEDERHANA & CLEAN --}}
    <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 text-xs font-medium text-[#fecaca]">
                👤 Pengaturan Pengguna
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">
                Profil Saya
            </h1>
            <p class="text-xs text-[#fecaca]">
                Informasi akun dan data pengguna sistem SPK UKM
            </p>
        </div>

        <div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white text-[#da2424] hover:bg-gray-100 text-xs font-bold transition shadow-sm">
                    ← Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white text-[#da2424] hover:bg-gray-100 text-xs font-bold transition shadow-sm">
                    ← Kembali ke Dashboard
                </a>
            @endif
        </div>
    </div>

    {{-- NOTIFIKASI SUCCESS / ERROR --}}
    @if (session('status') === 'profile-updated' || session('success'))
        <div class="flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-xl px-4 py-3 text-xs font-medium">
            <span class="font-bold text-sm">✓</span>
            <span>Profil Anda berhasil diperbarui!</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-xl px-4 py-3 text-xs font-medium">
            <span class="font-bold text-sm">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-xl p-4 text-xs space-y-1.5">
            <div class="font-bold flex items-center gap-2">
                <span>⚠️ Terjadi kesalahan validasi input:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-[11px] pl-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HANYA 1 CARD UTAMA UNTUK PROFIL SAYA --}}
    <div x-data="{ isEditing: {{ $errors->any() ? 'true' : 'false' }} }"
         class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 sm:p-8 space-y-6">

        {{-- HEADER IN-CARD PROFIL --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-[#fecaca] gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#da2424] text-white font-extrabold text-2xl flex items-center justify-center shadow-md flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <h2 class="text-lg sm:text-xl font-bold text-[#1f2937] leading-tight">
                        {{ $mahasiswa->nama ?? $user->name }}
                    </h2>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-[#6b7280]">
                        <span class="break-all">{{ $user->email }}</span>
                        <span>•</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#fef2f2] text-[#da2424]">
                            {{ ucfirst($user->role ?? 'mahasiswa') }}
                        </span>
                    </div>
                </div>
            </div>

            @if($user->role === 'mahasiswa')
                <div class="flex items-center gap-3 bg-[#fdf2f2] px-4 py-2.5 rounded-xl border border-[#fecaca]">
                    <div class="text-right text-xs">
                        <div class="font-bold text-[#1f2937]">Kelengkapan Profil</div>
                        <div class="text-[#da2424] font-semibold text-[11px]">{{ $filledCount }} dari {{ $totalFields }} Terisi ({{ $percentage }}%)</div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $isComplete ? 'bg-[#eaf8f2] text-[#258260]' : 'bg-amber-100 text-amber-800' }}">
                        {{ $isComplete ? 'Lengkap ✓' : 'Belum Lengkap' }}
                    </span>
                </div>
            @endif
        </div>

        {{-- 1. MODE TAMPILAN PROFIL (VIEW MODE) --}}
        <div x-show="!isEditing" class="space-y-6">
            
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#1f2937]">
                    Informasi Akun & Pengguna
                </h3>
            </div>

            @if($user->role === 'mahasiswa')
                {{-- GRID 8 DATA MAHASISWA (3 KOLOM PROPORSIONAL) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                    {{-- 1. Nama Lengkap --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">1. Nama Lengkap</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-words">
                            @if(!empty($mahasiswa->nama) || !empty($user->name))
                                {{ $mahasiswa->nama ?? $user->name }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    {{-- 2. Email Akun --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">2. Alamat Email</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-all">
                            @if(!empty($user->email))
                                {{ $user->email }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    {{-- 3. NIM --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">3. NIM (Nomor Induk Mahasiswa)</div>
                        <div class="font-mono font-bold text-[#da2424] text-xs mt-1.5 break-words">
                            @if(!empty($mahasiswa->nim))
                                {{ $mahasiswa->nim }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    {{-- 4. Program Studi --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">4. Program Studi</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-words">
                            @if(!empty($mahasiswa->prodi))
                                {{ $mahasiswa->prodi }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    {{-- 5. Fakultas --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">5. Fakultas</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-words">
                            @if(!empty($mahasiswa->fakultas))
                                {{ $mahasiswa->fakultas }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    {{-- 6. No. HP / WhatsApp --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">6. No. HP / WhatsApp</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-words">
                            @if(!empty($mahasiswa->no_hp))
                                {{ $mahasiswa->no_hp }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    {{-- 7. Angkatan --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">7. Angkatan (Tahun)</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-words">
                            @if(!empty($mahasiswa->angkatan))
                                {{ $mahasiswa->angkatan }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    {{-- 8. Jenis Kelamin --}}
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] flex flex-col justify-between hover:border-[#f8c4c4] transition">
                        <div class="text-[11px] font-semibold text-[#6b7280]">8. Jenis Kelamin</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-words">
                            @if($jkText !== 'Belum diisi')
                                {{ $jkText }}
                            @else
                                <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded text-[11px] font-normal inline-block">Belum diisi</span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                {{-- PROFIL ADMIN --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                        <div class="text-[11px] font-semibold text-[#6b7280]">Nama Lengkap</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-words">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                        <div class="text-[11px] font-semibold text-[#6b7280]">Alamat Email</div>
                        <div class="font-bold text-[#1f2937] text-xs mt-1.5 break-all">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                        <div class="text-[11px] font-semibold text-[#6b7280]">Role Akses Sistem</div>
                        <div class="font-bold text-[#da2424] text-xs mt-1.5">
                            Administrator Sistem
                        </div>
                    </div>
                </div>
            @endif

            {{-- TOMBOL EDIT PROFIL DI BAGIAN BAWAH CARD UTAMA --}}
            <div class="pt-6 border-t border-[#fecaca] flex items-center justify-end">
                <button type="button"
                        @click="isEditing = true"
                        class="px-6 py-2.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-sm transition flex items-center gap-2">
                    <span>✏️ Edit Profil</span>
                </button>
            </div>
        </div>

        {{-- 2. MODE EDIT PROFIL (FORM DENGAN DISUAP DI DALAM CARD UTAMA) --}}
        <div x-show="isEditing" x-cloak class="space-y-6">
            <div class="flex items-center justify-between border-b border-[#fecaca] pb-4">
                <div>
                    <h3 class="text-sm font-bold text-[#1f2937] flex items-center gap-2">
                        <span>✏️ Form Perbarui Data Profil</span>
                    </h3>
                    <p class="text-xs text-[#6b7280] mt-0.5">
                        Perbarui informasi data diri Anda di bawah ini lalu klik simpan perubahan.
                    </p>
                </div>

                <button type="button"
                        @click="isEditing = false"
                        class="text-xs font-semibold text-[#6b7280] hover:text-[#1f2937] px-3 py-1.5 rounded-lg border border-[#fecaca] hover:bg-gray-50 transition">
                    ✕ Batal
                </button>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('patch')

                <input type="hidden" name="name" value="{{ old('nama', $mahasiswa->nama ?? $user->name) }}">

                @if($user->role === 'mahasiswa')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        {{-- 1. NAMA LENGKAP --}}
                        <div class="space-y-1.5">
                            <label for="nama" class="block text-xs font-semibold text-[#4a4568]">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input id="nama"
                                   name="nama"
                                   type="text"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('nama', $mahasiswa->nama ?? $user->name) }}"
                                   required
                                   autocomplete="name" />
                        </div>

                        {{-- 2. NIM --}}
                        <div class="space-y-1.5">
                            <label for="nim" class="block text-xs font-semibold text-[#4a4568]">
                                NIM (Nomor Induk Mahasiswa) <span class="text-red-500">*</span>
                            </label>
                            <input id="nim"
                                   name="nim"
                                   type="text"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm font-mono text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('nim', $mahasiswa->nim ?? '') }}"
                                   placeholder="Contoh: 22000001"
                                   required />
                        </div>

                        {{-- 3. EMAIL AKUN --}}
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-semibold text-[#4a4568]">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   autocomplete="username" />
                        </div>

                        {{-- 4. NOMOR HP / WHATSAPP --}}
                        <div class="space-y-1.5">
                            <label for="no_hp" class="block text-xs font-semibold text-[#4a4568]">
                                Nomor HP / WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input id="no_hp"
                                   name="no_hp"
                                   type="text"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('no_hp', $mahasiswa->no_hp ?? '') }}"
                                   placeholder="Contoh: 081234567890"
                                   required />
                        </div>

                        {{-- 5. PROGRAM STUDI --}}
                        <div class="space-y-1.5">
                            <label for="prodi" class="block text-xs font-semibold text-[#4a4568]">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <input id="prodi"
                                   name="prodi"
                                   type="text"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('prodi', $mahasiswa->prodi ?? '') }}"
                                   placeholder="Contoh: Teknik Informatika"
                                   required />
                        </div>

                        {{-- 6. FAKULTAS --}}
                        <div class="space-y-1.5">
                            <label for="fakultas" class="block text-xs font-semibold text-[#4a4568]">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <input id="fakultas"
                                   name="fakultas"
                                   type="text"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('fakultas', $mahasiswa->fakultas ?? '') }}"
                                   placeholder="Contoh: Teknik / FKIP / Ekonomi"
                                   required />
                        </div>

                        {{-- 7. ANGKATAN --}}
                        <div class="space-y-1.5">
                            <label for="angkatan" class="block text-xs font-semibold text-[#4a4568]">
                                Angkatan (Tahun) <span class="text-red-500">*</span>
                            </label>
                            <input id="angkatan"
                                   name="angkatan"
                                   type="number"
                                   min="2000"
                                   max="2100"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('angkatan', $mahasiswa->angkatan ?? '') }}"
                                   placeholder="Contoh: 2024"
                                   required />
                        </div>

                        {{-- 8. JENIS KELAMIN --}}
                        <div class="space-y-1.5">
                            <label for="jenis_kelamin" class="block text-xs font-semibold text-[#4a4568]">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            @php
                                $currentJk = old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '');
                            @endphp
                            <select id="jenis_kelamin"
                                    name="jenis_kelamin"
                                    required
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ ($currentJk === 'L' || $currentJk === 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ ($currentJk === 'P' || $currentJk === 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                @else
                    {{-- FORM EDIT ADMIN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="space-y-1.5">
                            <label for="nama" class="block text-xs font-semibold text-[#4a4568]">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input id="nama"
                                   name="nama"
                                   type="text"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('nama', $user->name) }}"
                                   required />
                        </div>

                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-semibold text-[#4a4568]">
                                Alamat Email <span class="text-red-500">*</span>
                            </label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] bg-white text-sm text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15 focus:border-[#da2424] transition shadow-sm"
                                   value="{{ old('email', $user->email) }}"
                                   required />
                        </div>
                    </div>
                @endif

                <div class="pt-6 border-t border-[#fecaca] flex items-center justify-end gap-3">
                    <button type="button"
                            @click="isEditing = false"
                            class="px-5 py-2.5 rounded-xl border border-[#fecaca] text-[#6b7280] hover:text-[#1f2937] text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

@endsection
