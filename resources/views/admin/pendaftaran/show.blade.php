@extends('layouts.app')

@section('title', 'Detail Pendaftaran UKM')
@section('page-title', 'Detail & Verifikasi Pendaftaran UKM')

@section('content')

<div class="max-w-6xl mx-auto space-y-8">

    {{-- HELPER FORMATTER --}}
    @php
        $fmt = function ($val, $dec = 4) {
            if ($val === null || $val === '') return '-';
            $f = number_format((float)$val, $dec, ',', '');
            $f = rtrim($f, '0');
            $f = rtrim($f, ',');
            return $f ?: '0';
        };
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-[#1f2937]">
                    Permohonan {{ $pendaftaran->nomor_pendaftaran }}
                </h1>

                @if($pendaftaran->status === 'terverifikasi' || $pendaftaran->status === 'diterima')
                    <span class="px-3.5 py-1 bg-[#eaf8f2] text-[#258260] font-bold text-xs rounded-full">
                        ✓ Terverifikasi / Diterima
                    </span>
                @elseif($pendaftaran->status === 'perlu_perbaikan' || $pendaftaran->status === 'ditolak')
                    <span class="px-3.5 py-1 bg-red-50 text-red-600 font-bold text-xs rounded-full">
                        ✕ Ditolak / Perbaikan
                    </span>
                @elseif($pendaftaran->status === 'selesai')
                    <span class="px-3.5 py-1 bg-blue-50 text-blue-700 font-bold text-xs rounded-full">
                        ✓ Selesai
                    </span>
                @else
                    <span class="px-3.5 py-1 bg-[#fff5e5] text-[#b76e00] font-bold text-xs rounded-full">
                        ⏳ Menunggu Verifikasi
                    </span>
                @endif
            </div>

            <p class="text-sm text-[#6b7280] mt-1">
                Diajukan oleh <strong>{{ $pendaftaran->nama_mahasiswa }}</strong> (NIM: {{ $pendaftaran->nim }}) pada {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d M Y H:i') }}
            </p>
        </div>

        <a href="{{ route('admin.pendaftaran.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-[#fecaca] text-[#da2424] font-semibold text-sm hover:bg-[#fef2f2] transition flex-shrink-0">
            ← Kembali ke Daftar
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-xl px-5 py-4">
            <span class="font-bold">✓</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="flex items-center gap-3 bg-[#eef2ff] border border-[#c7d2fe] text-[#3730a3] rounded-xl px-5 py-4">
            <span class="font-bold">i</span>
            <span class="text-sm font-medium">{{ session('info') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-xl px-5 py-4">
            <span class="font-bold">✕</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI & TENGAH (IDENTITAS & SPK) --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- IDENTITAS MAHASISWA & PENDAFTARAN --}}
            <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-6">
                <h3 class="text-sm font-bold text-[#1f2937] border-b border-[#fecaca] pb-3 mb-4">
                    1. Identitas Pemohon & Detail Pendaftaran
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="text-xs text-[#6b7280]">Nama Mahasiswa</div>
                        <div class="text-base font-bold text-[#1f2937] mt-0.5">{{ $pendaftaran->nama_mahasiswa }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-[#6b7280]">NIM</div>
                        <div class="text-sm font-mono font-semibold text-[#da2424] mt-0.5">{{ $pendaftaran->nim }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-[#6b7280]">Program Studi / Fakultas</div>
                        <div class="text-sm font-medium text-[#1f2937] mt-0.5">
                            {{ $pendaftaran->prodi }} • {{ $pendaftaran->fakultas ?? 'Fakultas Teknik' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-[#6b7280]">Kontak (Email / No. HP)</div>
                        <div class="text-sm font-medium text-[#1f2937] mt-0.5">
                            {{ $pendaftaran->email }} {{ $pendaftaran->no_hp ? '• ' . $pendaftaran->no_hp : '' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-[#6b7280]">UKM Tujuan yang Didaftari</div>
                        <div class="text-lg font-bold text-[#da2424] mt-0.5">{{ $pendaftaran->nama_ukm }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-[#6b7280]">Tanggal Pengajuan</div>
                        <div class="text-sm font-medium text-[#1f2937] mt-0.5">
                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d M Y H:i:s') }}
                        </div>
                    </div>
                </div>

                {{-- ALASAN & DATA PENDAFTARAN LENGKAP --}}
                <div class="mt-6 pt-4 border-t border-[#fecaca] space-y-4">
                    <div>
                        <div class="text-xs text-[#6b7280] font-bold uppercase tracking-wider">Alasan Memilih {{ $pendaftaran->nama_ukm }}</div>
                        <div class="p-3.5 rounded-xl bg-[#fdf2f2] text-xs text-[#1f2937] mt-1 border border-[#fecaca]">
                            "{{ $pendaftaran->alasan_mendaftar ?? '-' }}"
                        </div>
                    </div>

                    @if($pendaftaran->ketertarikan)
                        <div>
                            <div class="text-xs text-[#6b7280] font-bold uppercase tracking-wider">Motivasi Bergabung</div>
                            <div class="p-3.5 rounded-xl bg-[#fdf2f2] text-xs text-[#1f2937] mt-1 border border-[#fecaca]">
                                "{{ $pendaftaran->ketertarikan }}"
                            </div>
                        </div>
                    @endif

                    @if($pendaftaran->detail_pengalaman)
                        <div>
                            <div class="text-xs text-[#6b7280] font-bold uppercase tracking-wider">Rincian Pengalaman / Portofolio</div>
                            <div class="p-3.5 rounded-xl bg-[#fdf2f2] text-xs text-[#1f2937] mt-1 border border-[#fecaca]">
                                "{{ $pendaftaran->detail_pengalaman }}"
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <div class="text-xs text-[#6b7280] font-bold uppercase tracking-wider">Keahlian & Keterampilan</div>
                            <div class="p-3.5 rounded-xl bg-[#fdf2f2] text-xs font-semibold text-[#da2424] mt-1 border border-[#fecaca]">
                                {{ $pendaftaran->keterampilan ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-[#6b7280] font-bold uppercase tracking-wider">Target & Kontribusi</div>
                            <div class="p-3.5 rounded-xl bg-[#fdf2f2] text-xs font-semibold text-[#1f2937] mt-1 border border-[#fecaca]">
                                {{ $pendaftaran->kontribusi ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- PERTANYAAN KESESUAIAN RINGKASAN --}}
                    <div class="pt-3 border-t border-[#fecaca] space-y-2">
                        <div class="text-xs font-bold text-[#1f2937] uppercase tracking-wider">Pertanyaan Kesesuaian Keanggotaan:</div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div class="p-3 rounded-lg bg-[#fdf2f2] border border-[#fecaca]">
                                <span class="text-[#6b7280] block text-[10px]">Pengalaman Bidang:</span>
                                <span class="font-bold text-[#1f2937]">{{ $pendaftaran->pengalaman ?? '-' }}</span>
                            </div>
                            <div class="p-3 rounded-lg bg-[#fdf2f2] border border-[#fecaca]">
                                <span class="text-[#6b7280] block text-[10px]">Kesiapan Latihan:</span>
                                <span class="font-bold text-[#1f2937]">{{ $pendaftaran->kesediaan_mengikuti_kegiatan ?? '-' }}</span>
                            </div>
                            <div class="p-3 rounded-lg bg-[#fdf2f2] border border-[#fecaca]">
                                <span class="text-[#6b7280] block text-[10px]">Kesediaan Aturan:</span>
                                <span class="font-bold text-[#258260]">{{ $pendaftaran->kesediaan_menaati_aturan ?? 'Ya' }}</span>
                            </div>
                            <div class="p-3 rounded-lg bg-[#fdf2f2] border border-[#fecaca]">
                                <span class="text-[#6b7280] block text-[10px]">Tujuan Utama:</span>
                                <span class="font-bold text-[#da2424]">{{ $pendaftaran->komitmen_aktif ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- DATA REKOMENDASI SPK TOPSIS --}}
            <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-6">
                <div class="flex items-center justify-between border-b border-[#fecaca] pb-3 mb-4">
                    <h3 class="text-sm font-bold text-[#1f2937]">
                        2. Hasil Perhitungan SPK TOPSIS Mahasiswa
                    </h3>
                    @if($pendaftaran->topsis_ranking)
                        <span class="px-3 py-1 bg-[#fef2f2] text-[#da2424] text-xs font-mono font-bold rounded-full">
                            Peringkat SPK: Rank #{{ $pendaftaran->topsis_ranking }}
                        </span>
                    @endif
                </div>

                @if($pendaftaran->topsis_score !== null)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca] text-center">
                            <div class="text-xs text-[#6b7280]">Nilai Preferensi TOPSIS ($C_i$)</div>
                            <div class="text-xl font-bold font-mono text-[#da2424] mt-1">
                                {{ $fmt($pendaftaran->topsis_score) }}
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca] text-center">
                            <div class="text-xs text-[#6b7280]">Jarak Ideal Positif ($S^+$)</div>
                            <div class="text-sm font-bold font-mono text-[#1f2937] mt-1">
                                {{ $fmt($pendaftaran->s_plus) }}
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca] text-center">
                            <div class="text-xs text-[#6b7280]">Jarak Ideal Negatif ($S^-$)</div>
                            <div class="text-sm font-bold font-mono text-[#1f2937] mt-1">
                                {{ $fmt($pendaftaran->s_minus) }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-amber-50 text-amber-800 rounded-xl text-xs mb-6">
                        Pendaftaran ini dibuat secara langsung atau tanpa tautan topsis_result_id aktif.
                    </div>
                @endif

                {{-- JAWABAN KUESIONER --}}
                <div>
                    <h4 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider mb-3">Jawaban Kuesioner 5 Kriteria Mahasiswa:</h4>
                    @if(count($kuesionerAnswers) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                            @foreach($kuesionerAnswers as $ans)
                                <div class="p-3 rounded-xl bg-[#fdf2f2] border border-[#fecaca] text-xs">
                                    <div class="font-bold text-[#da2424]">{{ $ans->kriteria_kode }}</div>
                                    <div class="font-medium text-[#1f2937] mt-1 line-clamp-2">{{ $ans->nama_sub_kriteria }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-xs text-[#6b7280] italic">Tidak ada rincian kuesioner tersimpan.</div>
                    @endif
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN (FORM VERIFIKASI & TIMELINE) --}}
        <div class="space-y-8">

            {{-- PANEL ACTION VERIFIKASI --}}
            <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-[#1f2937] border-b border-[#fecaca] pb-3">
                    Aksi Verifikasi Pendaftaran
                </h3>

                <form action="{{ route('admin.pendaftaran.verify', $pendaftaran->id) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-2">
                            Catatan Verifikasi (Opsional)
                        </label>
                        <textarea name="catatan" rows="3" placeholder="Masukkan catatan atau instruksi verifikasi..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-[#fecaca] text-xs focus:outline-none focus:ring-2 focus:ring-[#da2424]/20 focus:border-[#da2424] transition"></textarea>
                    </div>

                    <div class="space-y-2 pt-2">
                        {{-- TOMBOL TERIMA --}}
                        <button type="submit" name="status" value="terverifikasi"
                                onclick="return confirm('Apakah Anda yakin ingin MENERIMA pendaftaran mahasiswa ini di {{ $pendaftaran->nama_ukm }}?')"
                                class="w-full py-3 px-4 rounded-xl bg-[#258260] text-white font-bold text-sm hover:bg-[#1e6b4f] shadow-sm transition flex items-center justify-center gap-2">
                            ✓ Terima / Verifikasi
                        </button>

                        {{-- TOMBOL TOLAK / REVISI --}}
                        <button type="submit" name="status" value="perlu_perbaikan"
                                onclick="return confirm('Apakah Anda yakin ingin MENOLAK / MINTA REVISI pendaftaran mahasiswa ini?')"
                                class="w-full py-3 px-4 rounded-xl bg-red-600 text-white font-bold text-sm hover:bg-red-700 shadow-sm transition flex items-center justify-center gap-2">
                            ✕ Tolak / Perlu Perbaikan
                        </button>
                    </div>
                </form>
            </div>

            {{-- TIMELINE RIWAYAT STATUS --}}
            <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-6">
                <h3 class="text-sm font-bold text-[#1f2937] border-b border-[#fecaca] pb-3 mb-4">
                    Riwayat Status Pendaftaran
                </h3>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#fecaca]">
                    {{-- EVENT INITIAL --}}
                    <div class="relative">
                        <span class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-[#da2424] ring-4 ring-[#fef2f2]"></span>
                        <div class="text-xs font-bold text-[#1f2937]">Pendaftaran Diajukan</div>
                        <div class="text-[11px] text-[#6b7280] mt-0.5">
                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->format('d M Y H:i') }}
                        </div>
                        <div class="text-xs text-[#4b5563] mt-1">
                            Status awal: <span class="font-semibold text-amber-600">Menunggu Verifikasi</span>
                        </div>
                    </div>

                    {{-- HISTORY ENTRIES FROM DB --}}
                    @foreach($riwayatStatus as $log)
                        <div class="relative pt-2 border-t border-[#fecaca]/60">
                            <span class="absolute -left-6 top-3.5 w-2.5 h-2.5 rounded-full {{ ($log->status === 'terverifikasi' || $log->status === 'diterima' || $log->status === 'selesai') ? 'bg-[#258260] ring-4 ring-[#eaf8f2]' : 'bg-red-500 ring-4 ring-red-50' }}"></span>
                            <div class="text-xs font-bold capitalize {{ ($log->status === 'terverifikasi' || $log->status === 'diterima' || $log->status === 'selesai') ? 'text-[#258260]' : 'text-red-600' }}">
                                Status: {{ str_replace('_', ' ', $log->status) }}
                            </div>
                            <div class="text-[11px] text-[#6b7280] mt-0.5">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }} • oleh {{ $log->admin_name ?? 'Admin' }}
                            </div>
                            @if($log->catatan)
                                <div class="text-xs text-[#1f2937] bg-[#fdf2f2] p-2.5 rounded-lg border border-[#fecaca] mt-1.5">
                                    "{{ $log->catatan }}"
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
