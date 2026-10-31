@extends('layouts.app')

@section('title', 'Pendaftaran UKM Saya')
@section('page-title', 'Pendaftaran UKM Saya')

@section('content')

<div class="max-w-6xl mx-auto space-y-6" x-data="{ activeModal: null }">

    {{-- HELPER FORMATTER --}}
    @php
        $fmtTopsisPercent = function ($val) {
            if ($val === null || $val === '') return '-';
            $percent = (float)$val * 100;
            $formatted = number_format($percent, 2, ',', '.');
            if (str_ends_with($formatted, ',00')) {
                $formatted = substr($formatted, 0, -3);
            }
            return $formatted . '%';
        };
    @endphp

    {{-- 1. HEADER BANNER --}}
    <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-0.5 rounded-full bg-white/10 text-xs font-semibold text-[#fecaca]">
                📋 Riwayat Pendaftaran
            </div>
            <h1 class="text-xl md:text-2xl font-extrabold tracking-tight">
                Pendaftaran UKM Saya
            </h1>
            <p class="text-xs text-[#fecaca] max-w-xl">
                Pantau riwayat pendaftaran UKM, status verifikasi admin, dan detail berkas secara ringkas.
            </p>
        </div>

        <div class="flex-shrink-0">
            <a href="{{ route('mahasiswa.rekomendasi.index') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white text-[#da2424] hover:bg-gray-100 text-xs font-extrabold transition shadow-sm">
                + Daftar UKM Lainnya
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI SUCCESS / ERROR --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-xl px-4 py-3 text-xs font-medium">
            <span class="font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-xl px-4 py-3 text-xs font-medium">
            <span class="font-bold">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- LIST PENDAFTARAN MAHASISWA (COMPACT LIST VIEW) --}}
    @if($pendaftaranList->isNotEmpty())
        <div class="space-y-3">
            @foreach($pendaftaranList as $p)
                @php
                    $logs = $historyLogs->get($p->id) ?? collect();

                    $statusBadgeMap = [
                        'menunggu_verifikasi' => [
                            'label' => '⏳ Menunggu Verifikasi',
                            'cls'   => 'bg-[#fff5e5] text-[#b76e00] border-[#ffe2b3]'
                        ],
                        'terverifikasi' => [
                            'label' => '✓ Terverifikasi',
                            'cls'   => 'bg-[#eaf8f2] text-[#258260] border-[#c9eadc]'
                        ],
                        'diterima' => [
                            'label' => '🎉 Diterima',
                            'cls'   => 'bg-[#eaf8f2] text-[#258260] border-[#c9eadc]'
                        ],
                        'perlu_perbaikan' => [
                            'label' => '⚠️ Perlu Perbaikan',
                            'cls'   => 'bg-amber-50 text-amber-700 border-amber-200'
                        ],
                        'ditolak' => [
                            'label' => '✕ Ditolak',
                            'cls'   => 'bg-red-50 text-red-600 border-red-200'
                        ],
                        'selesai' => [
                            'label' => '✓ Selesai',
                            'cls'   => 'bg-blue-50 text-blue-700 border-blue-200'
                        ],
                    ];

                    $badge = $statusBadgeMap[$p->status] ?? [
                        'label' => ucfirst(str_replace('_', ' ', $p->status)),
                        'cls'   => 'bg-gray-100 text-gray-700 border-gray-200'
                    ];
                @endphp

                {{-- ITEM LIST COMPACT --}}
                <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-[#da2424] transition">
                    
                    {{-- KIRI: ICON / LOGO UKM & INFO UTAMA --}}
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-[#fef2f2] text-[#da2424] font-extrabold text-lg flex items-center justify-center flex-shrink-0 border border-[#fecaca]">
                            🏆
                        </div>

                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-bold text-[#1f2937]">
                                    {{ $p->nama_ukm }}
                                </h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-[#fef2f2] text-[#da2424]">
                                    {{ $p->nomor_pendaftaran }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 text-xs text-[#6b7280]">
                                <span>Tgl: {{ \Carbon\Carbon::parse($p->tanggal_daftar)->format('d M Y, H:i') }} WIB</span>
                                <span>•</span>
                                @if($p->topsis_ranking)
                                    <span class="inline-flex items-center gap-1 text-[#da2424] font-bold">
                                        <span>Rank #{{ $p->topsis_ranking }}</span>
                                        <span class="font-normal text-[#6b7280]">({{ $fmtTopsisPercent($p->topsis_score) }})</span>
                                    </span>
                                @else
                                    <span class="italic text-[#a59fbe]">Pendaftaran Langsung</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: BADGE STATUS & ACTION BUTTONS --}}
                    <div class="flex flex-wrap items-center justify-between sm:justify-end gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-[#fecaca]">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $badge['cls'] }}">
                            {{ $badge['label'] }}
                        </span>

                        <div class="flex items-center gap-2">
                            @if($p->status === 'perlu_perbaikan')
                                <a href="{{ route('mahasiswa.pendaftaran.edit', $p->id) }}"
                                   class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition shadow-sm">
                                    ✍ Perbaiki
                                </a>
                            @endif

                            <button type="button"
                                    @click="activeModal = {{ $p->id }}"
                                    class="px-4 py-1.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                                <span>👁️ Lihat Detail</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL PENDAFTARAN (LENGKAP) --}}
                <div x-show="activeModal === {{ $p->id }}"
                     x-cloak
                     @keydown.escape.window="activeModal = null"
                     class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">

                    <div @click.away="activeModal = null"
                         class="bg-white rounded-3xl border border-[#fecaca] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6 sm:p-8 space-y-6 text-left relative">

                        {{-- MODAL HEADER --}}
                        <div class="flex items-center justify-between pb-4 border-b border-[#fecaca]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#fef2f2] text-[#da2424] font-extrabold text-base flex items-center justify-center border border-[#fecaca]">
                                    🏆
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-[#1f2937]">
                                        Detail Pendaftaran {{ $p->nama_ukm }}
                                    </h2>
                                    <p class="text-xs text-[#6b7280]">
                                        {{ $p->nomor_pendaftaran }} • Diajukan: {{ \Carbon\Carbon::parse($p->tanggal_daftar)->format('d M Y, H:i') }} WIB
                                    </p>
                                </div>
                            </div>

                            <button type="button"
                                    @click="activeModal = null"
                                    class="w-8 h-8 rounded-full bg-[#fdf2f2] text-[#6b7280] hover:text-[#1f2937] hover:bg-gray-100 flex items-center justify-center transition font-bold text-sm">
                                ✕
                            </button>
                        </div>

                        {{-- MODAL BODY --}}
                        <div class="space-y-6">

                            {{-- STATUS BADGE & DOWNLOAD PDF ACTION --}}
                            <div class="flex flex-wrap items-center justify-between gap-3 bg-[#fdf2f2] p-4 rounded-2xl border border-[#fecaca]">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-[#6b7280] font-semibold">Status Pendaftaran:</span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $badge['cls'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </div>

                                <a href="{{ route('mahasiswa.pendaftaran.pdf', $p->id) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#fef2f2] text-[#da2424] hover:bg-[#da2424] hover:text-white text-xs font-extrabold border border-[#fecaca] transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Download PDF Bukti</span>
                                </a>
                            </div>

                            {{-- 1. IDENTITAS PEMOHON --}}
                            <div class="space-y-2">
                                <h3 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">
                                    1. Identitas Pemohon (Data Diri)
                                </h3>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-[#fdf2f2] p-4 rounded-2xl border border-[#fecaca] text-xs">
                                    <div>
                                        <span class="text-[#6b7280]">Nama Lengkap:</span>
                                        <div class="font-bold text-[#1f2937] mt-0.5">{{ $mahasiswa->nama }}</div>
                                    </div>
                                    <div>
                                        <span class="text-[#6b7280]">NIM:</span>
                                        <div class="font-bold font-mono text-[#da2424] mt-0.5">{{ $mahasiswa->nim }}</div>
                                    </div>
                                    <div>
                                        <span class="text-[#6b7280]">Prodi & Angkatan:</span>
                                        <div class="font-medium text-[#1f2937] mt-0.5">{{ $mahasiswa->prodi }} ({{ $mahasiswa->angkatan }})</div>
                                    </div>
                                    <div>
                                        <span class="text-[#6b7280]">No. HP / WA:</span>
                                        <div class="font-medium text-[#1f2937] mt-0.5">{{ $mahasiswa->no_hp ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. ALASAN, MOTIVASI & PORTFOLIO --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">
                                    2. Motivasi & Latar Belakang
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                    <div class="bg-[#fdf2f2] rounded-2xl p-4 border border-[#fecaca] space-y-1">
                                        <div class="font-semibold text-[#6b7280]">Alasan Memilih UKM:</div>
                                        <p class="text-[#1f2937] leading-relaxed font-medium">
                                            "{{ $p->alasan_mendaftar }}"
                                        </p>
                                    </div>

                                    <div class="bg-[#fdf2f2] rounded-2xl p-4 border border-[#fecaca] space-y-1">
                                        <div class="font-semibold text-[#6b7280]">Motivasi Bergabung:</div>
                                        <p class="text-[#1f2937] leading-relaxed font-medium">
                                            "{{ $p->ketertarikan ?? '-' }}"
                                        </p>
                                    </div>
                                </div>

                                @if($p->detail_pengalaman)
                                    <div class="bg-[#fdf2f2] rounded-2xl p-4 border border-[#fecaca] text-xs space-y-1">
                                        <div class="font-semibold text-[#6b7280]">Pengalaman / Portofolio Terkait:</div>
                                        <p class="text-[#1f2937] leading-relaxed font-medium">
                                            "{{ $p->detail_pengalaman }}"
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- 3. TOPSIS SCORE & JAWABAN KESESUAIAN --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">
                                    3. Hasil SPK TOPSIS & Jawaban Formulir
                                </h3>

                                <div class="bg-[#fef2f2] rounded-2xl p-4 border border-[#da2424]/30 flex items-center justify-between">
                                    <div>
                                        <div class="text-xs font-bold text-[#6b7280] uppercase">Hasil Rekomendasi SPK TOPSIS:</div>
                                        @if($p->topsis_ranking)
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-[#da2424] text-white">
                                                    Rank #{{ $p->topsis_ranking }}
                                                </span>
                                                <span class="text-xs font-mono font-bold text-[#da2424]">
                                                    Nilai Preferensi: {{ $fmtTopsisPercent($p->topsis_score) }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="text-xs text-[#6b7280] italic mt-0.5">Pendaftaran Mandiri / Langsung</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-[#fdf2f2] rounded-2xl p-4 border border-[#fecaca] space-y-2 text-xs">
                                    <div class="font-bold text-[#6b7280] uppercase mb-2">Jawaban Pertanyaan Kesesuaian:</div>

                                    <div class="flex justify-between border-b border-[#fecaca] pb-1.5">
                                        <span class="text-[#6b7280]">Pengalaman Kegiatan:</span>
                                        <span class="font-bold text-[#1f2937]">{{ $p->pengalaman ?? '-' }}</span>
                                    </div>

                                    <div class="flex justify-between border-b border-[#fecaca] pb-1.5">
                                        <span class="text-[#6b7280]">Kesiapan Latihan Rutin:</span>
                                        <span class="font-bold text-[#1f2937]">{{ $p->kesediaan_mengikuti_kegiatan ?? '-' }}</span>
                                    </div>

                                    <div class="flex justify-between border-b border-[#fecaca] pb-1.5">
                                        <span class="text-[#6b7280]">Bersedia Taati Aturan:</span>
                                        <span class="font-bold text-[#258260]">{{ $p->kesediaan_menaati_aturan ?? 'Ya' }}</span>
                                    </div>

                                    <div class="flex justify-between border-b border-[#fecaca] pb-1.5">
                                        <span class="text-[#6b7280]">Tujuan Utama Bergabung:</span>
                                        <span class="font-bold text-[#da2424]">{{ $p->komitmen_aktif ?? '-' }}</span>
                                    </div>

                                    @if($p->keterampilan)
                                        <div class="pt-1">
                                            <span class="text-[#6b7280]">Keahlian / Minat:</span>
                                            <div class="font-medium text-[#1f2937] mt-0.5">{{ $p->keterampilan }}</div>
                                        </div>
                                    @endif

                                    @if($p->kontribusi)
                                        <div class="pt-1">
                                            <span class="text-[#6b7280]">Target Kontribusi:</span>
                                            <div class="font-medium text-[#1f2937] mt-0.5">{{ $p->kontribusi }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 4. TIMELINE LOG RIWAYAT VERIFIKASI ADMIN --}}
                            <div class="space-y-2">
                                <h3 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider">
                                    4. Riwayat / Timeline Verifikasi Admin
                                </h3>

                                @if($logs->isNotEmpty())
                                    <div class="space-y-2 border-l-2 border-[#da2424]/30 pl-4 ml-1 text-xs">
                                        @foreach($logs as $log)
                                            <div class="relative space-y-0.5">
                                                <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full bg-[#da2424]"></div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-[#1f2937]">
                                                        Status: <span class="capitalize text-[#da2424]">{{ str_replace('_', ' ', $log->status) }}</span>
                                                    </span>
                                                    <span class="text-[11px] text-[#6b7280]">
                                                        ({{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }} WIB)
                                                    </span>
                                                </div>

                                                @if($log->catatan)
                                                    <div class="p-2.5 rounded-xl bg-[#fdf2f2] border border-[#fecaca] text-[#1f2937] mt-1 font-medium">
                                                        💬 <strong>Catatan Admin ({{ $log->admin_name ?? 'Admin' }}):</strong> {{ $log->catatan }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-3.5 rounded-2xl bg-[#fdf2f2] border border-[#fecaca] text-xs text-[#6b7280]">
                                        ⏳ Belum ada catatan perubahan status dari Admin. Pendaftaran Anda sedang dalam antrean verifikasi.
                                    </div>
                                @endif
                            </div>

                        </div>

                        {{-- MODAL FOOTER --}}
                        <div class="pt-4 border-t border-[#fecaca] flex items-center justify-end">
                            <button type="button"
                                    @click="activeModal = null"
                                    class="px-5 py-2 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-sm transition">
                                Tutup Detail
                            </button>
                        </div>

                    </div>
                </div>

            @endforeach
        </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-12 text-center space-y-4">
            <div class="w-16 h-16 rounded-full bg-[#fee2e2] text-[#da2424] flex items-center justify-center mx-auto text-3xl font-bold">
                📋
            </div>
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Belum Ada Pendaftaran UKM</h2>
                <p class="text-xs text-[#6b7280] max-w-md mx-auto mt-1">
                    Anda belum mendaftar ke UKM manapun. Silakan lihat hasil rekomendasi UKM dan ajukan pendaftaran online.
                </p>
            </div>
            <a href="{{ route('mahasiswa.rekomendasi.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#da2424] text-white text-xs font-bold hover:bg-[#b91c1c] shadow-md transition">
                Lihat Rekomendasi & Daftar UKM →
            </a>
        </div>
    @endif

</div>

@endsection
