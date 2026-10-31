@extends('layouts.app')

@section('title', 'Formulir Pendaftaran UKM')
@section('page-title', 'Formulir Pendaftaran UKM')

@section('content')

<div class="max-w-4xl mx-auto space-y-8">

    {{-- HEADER BANNER --}}
    <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-[#fecaca] border border-white/10">
                📝 Formulir Pendaftaran Anggota Baru
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                Pendaftaran {{ $ukm->nama_ukm }}
            </h1>
            <p class="text-xs md:text-sm text-[#fecaca] max-w-2xl leading-relaxed">
                Lengkapi informasi pendaftaran Anda secara detail. Data ini akan digunakan oleh pengurus dan Admin UKM untuk memverifikasi kesesuaian calon anggota.
            </p>
        </div>
    </div>

    {{-- NOTIFIKASI ERROR / VALIDASI --}}
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

    <form action="{{ route('mahasiswa.pendaftaran.store') }}" method="POST" class="space-y-8">
        @csrf

        <input type="hidden" name="ukm_id" value="{{ $ukm->id }}">
        @if($topsisResultId)
            <input type="hidden" name="topsis_result_id" value="{{ $topsisResultId }}">
        @endif

        {{-- A. DATA DIRI MAHASISWA (READ-ONLY INFORMATIONAL CARDS) --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 md:p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-[#fecaca] pb-4">
                <div>
                    <h2 class="text-lg font-extrabold text-[#1f2937] flex items-center gap-2">
                        <span>👤</span> A. Data Diri Mahasiswa
                    </h2>
                    <p class="text-xs text-[#6b7280] mt-0.5">
                        Data diambil otomatis dari profil akun Anda.
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-xs text-[#da2424] hover:underline font-semibold">
                    Edit Profil ✎
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Nama Lengkap</div>
                    <div class="text-sm font-bold text-[#1f2937] mt-1">{{ $mahasiswa->nama ?? $user->name }}</div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">NIM (Nomor Induk Mahasiswa)</div>
                    <div class="text-sm font-mono font-bold text-[#da2424] mt-1">{{ $mahasiswa->nim ?? '-' }}</div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Program Studi & Fakultas</div>
                    <div class="text-sm font-medium text-[#1f2937] mt-1">
                        {{ $mahasiswa->prodi ?? '-' }} ({{ $mahasiswa->fakultas ?? 'UMC' }})
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Angkatan</div>
                    <div class="text-sm font-medium text-[#1f2937] mt-1">Tahun {{ $mahasiswa->angkatan ?? date('Y') }}</div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Email Kampus</div>
                    <div class="text-sm font-medium text-[#1f2937] mt-1">{{ $user->email }}</div>
                </div>

                <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                    <div class="text-[11px] font-bold text-[#6b7280] uppercase tracking-wider">Nomor HP / WhatsApp</div>
                    <div class="text-sm font-medium text-[#1f2937] mt-1">{{ $mahasiswa->no_hp ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- B. DATA PENDAFTARAN UKM --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 md:p-8 space-y-6">
            <div class="border-b border-[#fecaca] pb-4">
                <h2 class="text-lg font-extrabold text-[#1f2937] flex items-center gap-2">
                    <span>🏢</span> B. Data Pendaftaran & Informasi Pendukung
                </h2>
                <p class="text-xs text-[#6b7280] mt-0.5">
                    Informasikan alasan, minat, dan potensi yang Anda miliki secara jujur dan rinci.
                </p>
            </div>

            {{-- UKM TERPILIH BADGE --}}
            <div class="p-4 rounded-xl bg-[#fef2f2] border border-[#da2424]/30 flex items-center justify-between">
                <div>
                    <div class="text-xs text-[#6b7280]">UKM yang Didaftari:</div>
                    <div class="text-base font-extrabold text-[#da2424] mt-0.5">{{ $ukm->nama_ukm }}</div>
                </div>
                @if($topsisResult)
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-[#da2424] text-white">
                            Rank SPK #{{ $topsisResult->ranking }}
                        </span>
                        <div class="text-[11px] text-[#da2424] font-mono font-bold mt-1">
                            Kecocokan SPK: {{ number_format((float)$topsisResult->nilai_preferensi * 100, 2, ',', '.') }}%
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-5">
                {{-- ALASAN MEMILIH UKM --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        1. Alasan Memilih {{ $ukm->nama_ukm }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan_mendaftar" rows="3" required
                              placeholder="Mengapa Anda tertarik mendaftar di UKM ini dibanding UKM lainnya?"
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('alasan_mendaftar') }}</textarea>
                </div>

                {{-- MOTIVASI BERGABUNG --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        2. Motivasi Utama Bergabung <span class="text-red-500">*</span>
                    </label>
                    <textarea name="ketertarikan" rows="3" required
                              placeholder="Tuliskan harapan dan dorongan utama Anda bergabung dalam keanggotaan UKM ini..."
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('ketertarikan') }}</textarea>
                </div>

                {{-- PENGALAMAN YANG RELEVAN --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        3. Rincian Pengalaman / Portofolio Terkait (Jika Ada)
                    </label>
                    <textarea name="detail_pengalaman" rows="3"
                              placeholder="Jelaskan secara singkat pengalaman organisasi, divisi, lomba, atau latihan yang pernah diikuti sebelumnya..."
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('detail_pengalaman') }}</textarea>
                </div>

                {{-- KEAHLIAN / MINAT --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                        4. Keahlian & Keterampilan yang Dimiliki <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterampilan" rows="3" required
                              placeholder="Contoh: Desain Grafis, Public Speaking, Fotografi, Bermain Alat Musik, Manajemen Acara, dll."
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('keterampilan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- C. PERTANYAAN KESESUAIAN KEANGGOTAAN --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 md:p-8 space-y-6">
            <div class="border-b border-[#fecaca] pb-4">
                <h2 class="text-lg font-extrabold text-[#1f2937] flex items-center gap-2">
                    <span>📋</span> C. Pertanyaan Kesesuaian Keanggotaan
                </h2>
                <p class="text-xs text-[#6b7280] mt-0.5">
                    Jawab pertanyaan singkat berikut untuk membantu Admin mengukur kesiapan Anda.
                </p>
            </div>

            <div class="space-y-6">

                {{-- PERTANYAAN 1: TINGKAT MINAT (SCALE 1-5) --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-3">
                        1. Seberapa besar minat Anda untuk mengikuti seluruh rangkaian kegiatan UKM ini? <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-5 gap-3">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center justify-center p-3 rounded-xl border border-[#fecaca] cursor-pointer hover:border-[#da2424] hover:bg-[#fef2f2] transition group">
                                <input type="radio" name="minat_scale" value="{{ $i }}" {{ old('minat_scale', '5') == $i ? 'checked' : '' }} class="sr-only peer">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center font-extrabold text-sm border border-[#fecaca] peer-checked:bg-[#da2424] peer-checked:text-white peer-checked:border-[#da2424] transition">
                                    {{ $i }}
                                </span>
                                <span class="text-[10px] text-[#6b7280] mt-1 text-center font-medium">
                                    @if($i == 1) Sangat Rendah @elseif($i == 3) Cukup @elseif($i == 5) Sangat Tinggi @else Level {{ $i }} @endif
                                </span>
                            </label>
                        @endfor
                    </div>
                </div>

                {{-- PERTANYAAN 2: PENGALAMAN BEKERJA/KEGIATAN --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        2. Apakah Anda memiliki pengalaman yang berkaitan dengan bidang kegiatan UKM ini? <span class="text-red-500">*</span>
                    </label>
                    <select name="pengalaman" required
                            class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">
                        <option value="">-- Pilih Tingkat Pengalaman --</option>
                        <option value="Belum pernah" {{ old('pengalaman') == 'Belum pernah' ? 'selected' : '' }}>Belum pernah (Ingin belajar dari awal)</option>
                        <option value="Pernah sedikit" {{ old('pengalaman') == 'Pernah sedikit' ? 'selected' : '' }}>Pernah sedikit (Memahami dasar-dasar)</option>
                        <option value="Cukup berpengalaman" {{ old('pengalaman') == 'Cukup berpengalaman' ? 'selected' : '' }}>Cukup berpengalaman (Pernah berpartisipasi)</option>
                        <option value="Sangat berpengalaman" {{ old('pengalaman') == 'Sangat berpengalaman' ? 'selected' : '' }}>Sangat berpengalaman (Aktif & Berprestasi)</option>
                    </select>
                </div>

                {{-- PERTANYAAN 3: KESIAPAN LATIHAN RUTIN --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        3. Seberapa siap Anda mengikuti kegiatan dan latihan rutin UKM? <span class="text-red-500">*</span>
                    </label>
                    <select name="kesediaan_mengikuti_kegiatan" required
                            class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">
                        <option value="">-- Pilih Tingkat Kesiapan --</option>
                        <option value="Tidak siap" {{ old('kesediaan_mengikuti_kegiatan') == 'Tidak siap' ? 'selected' : '' }}>Tidak siap</option>
                        <option value="Kurang siap" {{ old('kesediaan_mengikuti_kegiatan') == 'Kurang siap' ? 'selected' : '' }}>Kurang siap</option>
                        <option value="Cukup siap" {{ old('kesediaan_mengikuti_kegiatan') == 'Cukup siap' ? 'selected' : '' }}>Cukup siap</option>
                        <option value="Siap" {{ old('kesediaan_mengikuti_kegiatan', 'Siap') == 'Siap' ? 'selected' : '' }}>Siap</option>
                        <option value="Sangat siap" {{ old('kesediaan_mengikuti_kegiatan') == 'Sangat siap' ? 'selected' : '' }}>Sangat siap</option>
                    </select>
                </div>

                {{-- PERTANYAAN 4: KONTRIBUSI / POTENSI --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        4. Apa kemampuan atau potensi yang dapat Anda kontribusikan kepada UKM ini? <span class="text-red-500">*</span>
                    </label>
                    <textarea name="kontribusi" rows="3" required
                              placeholder="Misal: Siap membantu kepengurusan acara, mewakili dalam perlombaan, mengelola media sosial, dll."
                              class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">{{ old('kontribusi') }}</textarea>
                </div>

                {{-- PERTANYAAN 5: KESEDIAAN MENAATI ATURAN --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        5. Apakah Anda bersedia mengikuti aturan, jadwal kegiatan, dan program kerja UKM? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-6 pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-[#1f2937]">
                            <input type="radio" name="kesediaan_menaati_aturan" value="Ya" {{ old('kesediaan_menaati_aturan', 'Ya') == 'Ya' ? 'checked' : '' }} class="w-4 h-4 text-[#da2424] focus:ring-[#da2424]">
                            <span>Ya, Saya Bersedia</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-[#1f2937]">
                            <input type="radio" name="kesediaan_menaati_aturan" value="Tidak" {{ old('kesediaan_menaati_aturan') == 'Tidak' ? 'checked' : '' }} class="w-4 h-4 text-red-600 focus:ring-red-500">
                            <span>Tidak</span>
                        </label>
                    </div>
                </div>

                {{-- PERTANYAAN 6: TUJUAN UTAMA BERGABUNG --}}
                <div>
                    <label class="block text-xs font-bold text-[#1f2937] mb-2">
                        6. Apa tujuan utama Anda bergabung dengan UKM ini? <span class="text-red-500">*</span>
                    </label>
                    <select name="komitmen_aktif" required
                            class="w-full px-4 py-3 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/30 focus:border-[#da2424] transition">
                        <option value="">-- Pilih Tujuan Utama --</option>
                        <option value="Mengembangkan minat dan bakat" {{ old('komitmen_aktif') == 'Mengembangkan minat dan bakat' ? 'selected' : '' }}>Mengembangkan minat dan bakat</option>
                        <option value="Menambah pengalaman" {{ old('komitmen_aktif') == 'Menambah pengalaman' ? 'selected' : '' }}>Menambah pengalaman</option>
                        <option value="Mengembangkan prestasi" {{ old('komitmen_aktif') == 'Mengembangkan prestasi' ? 'selected' : '' }}>Mengembangkan prestasi</option>
                        <option value="Menambah relasi" {{ old('komitmen_aktif') == 'Menambah relasi' ? 'selected' : '' }}>Menambah relasi</option>
                        <option value="Berkontribusi dalam kegiatan kampus" {{ old('komitmen_aktif') == 'Berkontribusi dalam kegiatan kampus' ? 'selected' : '' }}>Berkontribusi dalam kegiatan kampus</option>
                        <option value="Lainnya" {{ old('komitmen_aktif') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- TOMBOL AKSI SUBMIT --}}
        <div class="flex items-center justify-end gap-4 pt-4">
            <a href="{{ route('mahasiswa.rekomendasi.index') }}"
               class="px-6 py-3.5 rounded-xl border border-[#fecaca] text-[#6b7280] hover:bg-gray-100 text-xs font-bold transition">
                Batal
            </a>
            <button type="submit"
                    onclick="return confirm('Apakah Anda yakin data pendaftaran sudah terisi dengan benar?')"
                    class="px-8 py-3.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-extrabold shadow-md transition flex items-center gap-2">
                ✓ Kirim Pendaftaran {{ $ukm->nama_ukm }} Sekarang
            </button>
        </div>

    </form>

</div>

@endsection
