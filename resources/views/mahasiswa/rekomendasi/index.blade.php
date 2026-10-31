@extends('layouts.app')

@section('title', 'Hasil Rekomendasi UKM')
@section('page-title', 'Hasil Rekomendasi UKM')

@section('content')

<div class="max-w-6xl mx-auto space-y-6" x-data="{ activeModal: null }">

    {{-- HELPER FORMATTER PERSENTASE TOPSIS --}}
    @php
        $fmtPercent = function ($val) {
            if ($val === null || $val === '') return '-';
            $percent = (float)$val * 100;
            $formatted = number_format($percent, 2, ',', '.');
            if (str_ends_with($formatted, ',00')) {
                $formatted = substr($formatted, 0, -3);
            }
            return $formatted . '%';
        };

        $fmtNum = function ($val) {
            if ($val === null || $val === '') return '-';
            return number_format((float)$val, 4, ',', '.');
        };

        $latestRun = $allRuns->first();
    @endphp

    {{-- 1. HEADER BANNER DENGAN PROMINENT CTA "ISI KUESIONER" --}}
    <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1.5 max-w-xl">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-white/10 text-xs font-semibold text-[#fecaca]">
                🏆 Hasil Rekomendasi UKM
            </div>
            <h1 class="text-xl md:text-2xl font-extrabold tracking-tight">
                Hasil Perangkingan TOPSIS UKM
            </h1>
            <p class="text-xs text-[#fecaca] leading-relaxed">
                Berikut adalah urutan rekomendasi UKM terbaik berdasarkan preferensi dan jawaban kuesioner Anda.
            </p>
        </div>

        {{-- PROMINENT CTA BUTTON: ISI / UPDATE KUESIONER --}}
        <div class="flex-shrink-0">
            <a href="{{ route('mahasiswa.kuesioner.index', ['edit' => 1]) }}"
               class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl bg-white text-[#da2424] hover:bg-gray-100 font-extrabold text-sm transition shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <span>📝</span>
                <span>{{ $allRuns->isNotEmpty() ? 'Isi Ulang Kuesioner' : 'Isi Kuesioner Sekarang' }}</span>
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

    @if($allRuns->isNotEmpty() && $selectedRun)
        @php
            $selectedRunNumber = $allRuns->count() - $allRuns->search(fn($r) => $r->id === $selectedRun->id);
            $isSelectedLatest = $latestRun && ($selectedRun->id === $latestRun->id);
        @endphp

        {{-- BARIS OPERASIONAL: RIWAYAT DROPDOWN & INFO WAKTU TERAKHIR --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm text-xs">
            <div class="flex items-center gap-2 text-[#6b7280]">
                <span>Terakhir diperbarui:</span>
                <strong class="text-[#1f2937] font-bold">
                    {{ \Carbon\Carbon::parse($selectedRun->created_at)->translatedFormat('d F Y, H:i') }} WIB
                </strong>
                @if($isSelectedLatest)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-green-100 text-green-700 border border-green-200">
                        Hasil Terbaru
                    </span>
                @endif
            </div>

            @if($allRuns->count() > 1)
                <div class="flex items-center gap-2">
                    <span class="text-[#6b7280] font-medium">Riwayat Pengisian:</span>
                    <form method="GET" action="{{ route('mahasiswa.rekomendasi.index') }}">
                        <select name="run_id" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-[#fecaca] bg-[#fdf2f2] text-xs font-bold text-[#1f2937] focus:outline-none focus:ring-2 focus:ring-[#da2424]/15">
                            @foreach($allRuns as $idx => $run)
                                <option value="{{ $run->id }}" {{ $run->id === $selectedRun->id ? 'selected' : '' }}>
                                    Pengisian #{{ $allRuns->count() - $idx }} ({{ \Carbon\Carbon::parse($run->created_at)->format('d M Y, H:i') }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endif
        </div>

        {{-- DAFTAR RANKING 7 UKM (COMPACT LIST VIEW) --}}
        <div class="space-y-3">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-base font-bold text-[#1f2937]">
                    Daftar Ranking UKM Teratas (TOPSIS)
                </h2>
                <span class="text-xs text-[#6b7280]">Total 7 UKM Direkomendasikan</span>
            </div>

            @foreach($selectedResults as $res)
                @php
                    $regStat = $registeredUkms[$res->ukm_id] ?? null;
                    $isTop1 = ($res->ranking === 1);
                @endphp

                {{-- ITEM LIST RANKING COMPACT --}}
                <div class="bg-white rounded-2xl border {{ $isTop1 ? 'border-2 border-[#da2424] bg-[#fef2f2]/40 shadow-md' : 'border-[#fecaca] shadow-sm' }} p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-[#da2424] transition">
                    
                    {{-- KIRI: MEDAL / RANKING BADGE & INFO UKM --}}
                    <div class="flex items-center gap-3.5">
                        {{-- MEDAL / RANK BADGE --}}
                        <div class="flex-shrink-0">
                            @if($res->ranking === 1)
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#da2424] to-[#b91c1c] text-white font-black text-xl flex items-center justify-center shadow-md">
                                    🥇
                                </div>
                            @elseif($res->ranking === 2)
                                <div class="w-11 h-11 rounded-2xl bg-[#fee2e2] text-[#da2424] font-extrabold text-lg flex items-center justify-center border border-[#fecaca]">
                                    🥈
                                </div>
                            @elseif($res->ranking === 3)
                                <div class="w-11 h-11 rounded-2xl bg-[#fee2e2] text-[#da2424] font-extrabold text-lg flex items-center justify-center border border-[#fecaca]">
                                    🥉
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-2xl bg-[#fdf2f2] text-[#da2424] font-extrabold text-sm flex items-center justify-center border border-[#fecaca]">
                                    #{{ $res->ranking }}
                                </div>
                            @endif
                        </div>

                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-bold text-[#1f2937]">
                                    {{ $res->nama_ukm }}
                                </h3>

                                @if($isTop1)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#da2424] text-white shadow-xs">
                                        REKOMENDASI UTAMA
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="text-[#6b7280]">Nilai Preferensi:</span>
                                <span class="font-mono font-bold text-[#da2424] text-sm">
                                    {{ $fmtPercent($res->nilai_preferensi) }}
                                </span>
                                <span class="text-[#a59fbe]">•</span>
                                <span class="text-[#6b7280]">Rank #{{ $res->ranking }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: ACTION BUTTONS --}}
                    <div class="flex flex-wrap items-center justify-between sm:justify-end gap-2.5 pt-3 sm:pt-0 border-t sm:border-t-0 border-[#fecaca]">
                        @if($regStat)
                            <span class="px-3 py-1.5 rounded-xl font-bold text-xs bg-[#eaf8f2] text-[#258260] border border-[#c9eadc]">
                                ✓ {{ ucfirst(str_replace('_', ' ', $regStat)) }}
                            </span>
                        @else
                            <a href="{{ route('mahasiswa.pendaftaran.create', ['ukm' => $res->ukm_id, 'topsis_result_id' => $res->id]) }}"
                               class="px-4 py-2 rounded-xl {{ $isTop1 ? 'bg-[#da2424] hover:bg-[#b91c1c] text-white shadow-md' : 'bg-[#da2424] hover:bg-[#b91c1c] text-white' }} text-xs font-bold transition">
                                Daftar UKM Ini →
                            </a>
                        @endif

                        <button type="button"
                                @click="activeModal = {{ $res->id }}"
                                class="px-3.5 py-2 rounded-xl bg-[#fdf2f2] hover:bg-[#fee2e2] text-[#da2424] text-xs font-bold border border-[#fecaca] transition flex items-center gap-1">
                            <span>👁️ Lihat Detail</span>
                        </button>
                    </div>
                </div>

                {{-- MODAL DETAIL UKM & HASIL TOPSIS AKADEMIK --}}
                <div x-show="activeModal === {{ $res->id }}"
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
                         class="bg-white rounded-3xl border border-[#fecaca] shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 sm:p-8 space-y-6 text-left relative">

                        {{-- MODAL HEADER --}}
                        <div class="flex items-center justify-between pb-4 border-b border-[#fecaca]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#fef2f2] text-[#da2424] font-extrabold text-base flex items-center justify-center border border-[#fecaca]">
                                    #{{ $res->ranking }}
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-[#1f2937]">
                                        {{ $res->nama_ukm }}
                                    </h2>
                                    <p class="text-xs text-[#6b7280]">
                                        Peringkat #{{ $res->ranking }} • Nilai Preferensi: <strong class="text-[#da2424] font-mono">{{ $fmtPercent($res->nilai_preferensi) }}</strong>
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
                        <div class="space-y-5 text-xs">
                            {{-- PROFIL / DESKRIPSI UKM --}}
                            <div class="space-y-1.5 bg-[#fdf2f2] p-4 rounded-2xl border border-[#fecaca]">
                                <div class="font-bold text-[#6b7280] uppercase tracking-wider">Deskripsi & Profil UKM:</div>
                                <p class="text-[#1f2937] leading-relaxed font-medium">
                                    {{ $res->deskripsi ?? 'Unit Kegiatan Mahasiswa Universitas Muhammadiyah Cirebon.' }}
                                </p>
                            </div>

                            {{-- DETAIL PERHITUNGAN TOPSIS (JARAK S+ DAN S-) --}}
                            <div class="space-y-1.5 bg-[#fef2f2] p-4 rounded-2xl border border-[#da2424]/20">
                                <div class="font-bold text-[#da2424] uppercase tracking-wider">Detail Kalkulasi TOPSIS:</div>
                                <div class="grid grid-cols-3 gap-3 pt-1 text-center">
                                    <div class="p-2.5 rounded-xl bg-white border border-[#fecaca]">
                                        <span class="text-[#6b7280] block text-[11px]">Nilai Preferensi (V):</span>
                                        <span class="font-bold font-mono text-[#da2424] text-sm">{{ $fmtPercent($res->nilai_preferensi) }}</span>
                                    </div>
                                    <div class="p-2.5 rounded-xl bg-white border border-[#fecaca]">
                                        <span class="text-[#6b7280] block text-[11px]">Jarak Positif (S+):</span>
                                        <span class="font-bold font-mono text-[#1f2937] text-sm">{{ $fmtNum($res->s_plus) }}</span>
                                    </div>
                                    <div class="p-2.5 rounded-xl bg-white border border-[#fecaca]">
                                        <span class="text-[#6b7280] block text-[11px]">Jarak Negatif (S-):</span>
                                        <span class="font-bold font-mono text-[#1f2937] text-sm">{{ $fmtNum($res->s_minus) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL FOOTER --}}
                        <div class="pt-4 border-t border-[#fecaca] flex items-center justify-between">
                            <a href="{{ route('mahasiswa.ukm.show', $res->ukm_id) }}"
                               class="px-4 py-2 rounded-xl border border-[#fecaca] text-[#da2424] hover:bg-[#fee2e2] text-xs font-bold transition">
                                Profil Lengkap UKM →
                            </a>

                            @if($regStat)
                                <span class="px-4 py-2 rounded-xl bg-[#eaf8f2] text-[#258260] text-xs font-bold border border-[#c9eadc]">
                                    ✓ {{ ucfirst(str_replace('_', ' ', $regStat)) }}
                                </span>
                            @else
                                <a href="{{ route('mahasiswa.pendaftaran.create', ['ukm' => $res->ukm_id, 'topsis_result_id' => $res->id]) }}"
                                   class="px-5 py-2.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-extrabold shadow-sm transition">
                                    Daftar UKM Ini →
                                </a>
                            @endif
                        </div>

                    </div>
                </div>

            @endforeach
        </div>

    @else

        {{-- EMPTY STATE APABILA BELUM MENGISI KUESIONER --}}
        <div class="bg-white rounded-3xl border border-[#fecaca] shadow-sm p-10 sm:p-14 text-center space-y-5 max-w-2xl mx-auto">
            <div class="w-20 h-20 rounded-3xl bg-[#fee2e2] text-[#da2424] flex items-center justify-center mx-auto text-4xl font-extrabold shadow-sm">
                📝
            </div>

            <div class="space-y-2">
                <h2 class="text-xl font-extrabold text-[#1f2937]">Belum Ada Hasil Rekomendasi</h2>
                <p class="text-xs text-[#6b7280] max-w-md mx-auto leading-relaxed">
                    Silakan isi kuesioner terlebih dahulu untuk mendapatkan rekomendasi UKM yang sesuai dengan minat dan kriteria Anda.
                </p>
            </div>

            {{-- PROMINENT CTA BUTTON IN EMPTY STATE --}}
            <div class="pt-2">
                <a href="{{ route('mahasiswa.kuesioner.index') }}"
                   class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-2xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-sm font-extrabold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition">
                    <span>📝</span>
                    <span>Isi Kuesioner Sekarang</span>
                </a>
            </div>
        </div>

    @endif

</div>

@endsection
