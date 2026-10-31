@extends('layouts.app')

@section('title', 'Perbaikan Data Pendaftaran UKM')
@section('page-title', 'Perbaikan Data Pendaftaran UKM')

@section('content')

<div class="max-w-4xl mx-auto space-y-8">

    {{-- HEADER BANNER --}}
    <div class="bg-gradient-to-r from-[#8f2121] via-[#c94a20] to-[#d96236] rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-amber-200 border border-white/10">
                ⚠️ Instruksi Perbaikan Data Pendaftaran
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                Perbaiki Pendaftaran {{ $ukm->nama_ukm }}
            </h1>
            <p class="text-xs md:text-sm text-red-100 max-w-2xl leading-relaxed">
                Nomor Pendaftaran: <strong>{{ $pendaftaran->nomor_pendaftaran }}</strong>. Perbarui informasi sesuai catatan yang diberikan Admin di bawah ini.
            </p>
        </div>
    </div>

    {{-- CATATAN ADMIN / ALASAN PERBAIKAN --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 space-y-2 shadow-sm">
        <div class="flex items-center gap-2 text-xs font-bold text-amber-800 uppercase tracking-wider">
            <span>💬 Catatan Catatan Verifikasi Admin:</span>
        </div>
        <p class="text-sm text-amber-950 font-medium leading-relaxed bg-white/60 p-4 rounded-xl border border-amber-200/60">
            "{{ $latestLog->catatan ?? 'Silakan lengkapi atau perbaiki detail data pendaftaran Anda.' }}"
        </p>
        <div class="text-[11px] text-amber-700">
            Oleh: <strong>{{ $latestLog->admin_name ?? 'Admin UKM' }}</strong> pada {{ \Carbon\Carbon::parse($latestLog->created_at ?? now())->format('d M Y, H:i') }} WIB
        </div>
    </div>

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-2xl px-5 py-4 text-sm font-medium">
            <span class="font-bold">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-2xl p-5 text-xs space-y-2">
            <div class="font-bold text-sm">Terdapat kesalahan dalam pengisian formulir:</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mahasiswa.pendaftaran.update', $pendaftaran->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- A. DATA DIRI MAHASISWA --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 md:p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-[#fecaca] pb-4">
                <div>
                    <h2 class="text-lg font-extrabold text-[#1f2937] flex items-center gap-2">
                        <span>👤</span> A. Data Diri Mahasiswa
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Nama Lengkap</div>
                    <div class="text-sm font-bold text-[#1f2937] mt-1">{{ $mahasiswa->nama ?? $user->name }}</div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">NIM</div>
                    <div class="text-sm font-mono font-bold text-[#da2424] mt-1">{{ $mahasiswa->nim ?? '-' }}</div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Program Studi</div>
                    <div class="text-sm font-medium text-[#1f2937] mt-1">{{ $mahasiswa->prodi ?? '-' }}</div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Kontak</div>
                    <div class="text-sm font-medium text-[#1f2937] mt-1">{{ $user->email }} / {{ $mahasiswa->no_hp ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- B. DATA PENDAFTARAN --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 md:p-8 space-y-6">
            <div class="border-b border-[#fecaca] pb-4">
                <h2 class="text-lg font-extrabold text-[#1f2937] flex items-center gap-2">
                    <span>🏢</span> B. Data Pendaftaran & Informasi Pendukung
                </h2>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        1. Alasan Memilih {{ $ukm->nama_ukm }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_mendaftar" rows="3" required
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('alasan_mendaftar', $pendaftaran->alasan_mendaftar) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        2. Motivasi Utama Bergabung <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ketertarikan" rows="3" required
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('ketertarikan', $pendaftaran->ketertarikan) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        3. Rincian Pengalaman / Portofolio Terkait
                    </label>
                    <textarea name="detail_pengalaman" rows="3"
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('detail_pengalaman', $pendaftaran->detail_pengalaman) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        4. Keahlian & Keterampilan yang Dimiliki <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterampilan" rows="3" required
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('keterampilan', $pendaftaran->keterampilan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- C. PERTANYAAN KESESUAIAN --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 md:p-8 space-y-6">
            <div class="border-b border-[#fecaca] pb-4">
                <h2 class="text-lg font-extrabold text-[#1f2937] flex items-center gap-2">
                    <span>📋</span> C. Pertanyaan Kesesuaian Keanggotaan
                </h2>
            </div>

            <div class="space-y-6">

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        1. Apakah Anda memiliki pengalaman yang berkaitan dengan kegiatan UKM ini? <span class="text-red-500">*</span>
                    </label>
                    <select name="pengalaman" required
                            class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">
                        <option value="Belum pernah" {{ old('pengalaman', $pendaftaran->pengalaman) == 'Belum pernah' ? 'selected' : '' }}>Belum pernah</option>
                        <option value="Pernah sedikit" {{ old('pengalaman', $pendaftaran->pengalaman) == 'Pernah sedikit' ? 'selected' : '' }}>Pernah sedikit</option>
                        <option value="Cukup berpengalaman" {{ old('pengalaman', $pendaftaran->pengalaman) == 'Cukup berpengalaman' ? 'selected' : '' }}>Cukup berpengalaman</option>
                        <option value="Sangat berpengalaman" {{ old('pengalaman', $pendaftaran->pengalaman) == 'Sangat berpengalaman' ? 'selected' : '' }}>Sangat berpengalaman</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        2. Seberapa siap Anda mengikuti kegiatan dan latihan rutin UKM? <span class="text-red-500">*</span>
                    </label>
                    <select name="kesediaan_mengikuti_kegiatan" required
                            class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">
                        <option value="Tidak siap" {{ old('kesediaan_mengikuti_kegiatan', $pendaftaran->kesediaan_mengikuti_kegiatan) == 'Tidak siap' ? 'selected' : '' }}>Tidak siap</option>
                        <option value="Kurang siap" {{ old('kesediaan_mengikuti_kegiatan', $pendaftaran->kesediaan_mengikuti_kegiatan) == 'Kurang siap' ? 'selected' : '' }}>Kurang siap</option>
                        <option value="Cukup siap" {{ old('kesediaan_mengikuti_kegiatan', $pendaftaran->kesediaan_mengikuti_kegiatan) == 'Cukup siap' ? 'selected' : '' }}>Cukup siap</option>
                        <option value="Siap" {{ old('kesediaan_mengikuti_kegiatan', $pendaftaran->kesediaan_mengikuti_kegiatan) == 'Siap' ? 'selected' : '' }}>Siap</option>
                        <option value="Sangat siap" {{ old('kesediaan_mengikuti_kegiatan', $pendaftaran->kesediaan_mengikuti_kegiatan) == 'Sangat siap' ? 'selected' : '' }}>Sangat siap</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        3. Apa kemampuan atau potensi yang dapat Anda kontribusikan kepada UKM ini? <span class="text-red-500">*</span>
                    </label>
                    <textarea name="kontribusi" rows="3" required
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('kontribusi', $pendaftaran->kontribusi) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        4. Apakah Anda bersedia mengikuti aturan, jadwal kegiatan, dan program kerja UKM? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-6 pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-[#1f2937]">
                            <input type="radio" name="kesediaan_menaati_aturan" value="Ya" {{ old('kesediaan_menaati_aturan', $pendaftaran->kesediaan_menaati_aturan) == 'Ya' ? 'checked' : '' }} class="w-4 h-4 text-[#da2424]">
                            <span>Ya, Saya Bersedia</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-[#1f2937]">
                            <input type="radio" name="kesediaan_menaati_aturan" value="Tidak" {{ old('kesediaan_menaati_aturan', $pendaftaran->kesediaan_menaati_aturan) == 'Tidak' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        5. Apa tujuan utama Anda bergabung dengan UKM ini? <span class="text-red-500">*</span>
                    </label>
                    <select name="komitmen_aktif" required
                            class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">
                        <option value="Mengembangkan minat dan bakat" {{ old('komitmen_aktif', $pendaftaran->komitmen_aktif) == 'Mengembangkan minat dan bakat' ? 'selected' : '' }}>Mengembangkan minat dan bakat</option>
                        <option value="Menambah pengalaman" {{ old('komitmen_aktif', $pendaftaran->komitmen_aktif) == 'Menambah pengalaman' ? 'selected' : '' }}>Menambah pengalaman</option>
                        <option value="Mengembangkan prestasi" {{ old('komitmen_aktif', $pendaftaran->komitmen_aktif) == 'Mengembangkan prestasi' ? 'selected' : '' }}>Mengembangkan prestasi</option>
                        <option value="Menambah relasi" {{ old('komitmen_aktif', $pendaftaran->komitmen_aktif) == 'Menambah relasi' ? 'selected' : '' }}>Menambah relasi</option>
                        <option value="Berkontribusi dalam kegiatan kampus" {{ old('komitmen_aktif', $pendaftaran->komitmen_aktif) == 'Berkontribusi dalam kegiatan kampus' ? 'selected' : '' }}>Berkontribusi dalam kegiatan kampus</option>
                        <option value="Lainnya" {{ old('komitmen_aktif', $pendaftaran->komitmen_aktif) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- TOMBOL SUBMIT PERBAIKAN --}}
        <div class="flex items-center justify-end gap-4 pt-4">
            <a href="{{ route('mahasiswa.pendaftaran.index') }}"
               class="px-6 py-3.5 rounded-xl border border-[#fecaca] text-[#6b7280] hover:bg-gray-100 text-xs font-bold transition">
                Batal
            </a>
            <button type="submit"
                    onclick="return confirm('Kirim ulang perbaikan data pendaftaran Anda?')"
                    class="px-8 py-3.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-extrabold shadow-md transition flex items-center gap-2">
                ✓ Kirim Perbaikan Pendaftaran
            </button>
        </div>

    </form>

</div>

@endsection
