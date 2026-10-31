@extends('layouts.app')

@section('title', $showResults ? 'Hasil Rekomendasi UKM' : 'Kuesioner Preferensi UKM')
@section('page-title', $showResults ? 'Hasil Rekomendasi UKM' : 'Kuesioner Preferensi UKM')

@section('content')

<div class="max-w-5xl mx-auto space-y-8">

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

        $top1 = $showResults ? $latestResults->firstWhere('ranking', 1) : null;
        $top3 = $showResults ? $latestResults->whereIn('ranking', [1, 2, 3])->sortBy('ranking') : collect();
    @endphp

    {{-- NOTIFIKASI SUCCESS / ERROR --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-[#eaf8f2] border border-[#c9eadc] text-[#258260] rounded-2xl px-5 py-4 text-sm font-semibold shadow-sm">
            <span class="font-bold">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-2xl px-5 py-4 text-sm font-semibold shadow-sm">
            <span class="font-bold">✕</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="bg-[#fff0f1] border border-[#ffd6da] text-[#d93848] rounded-2xl p-5 text-sm space-y-2 shadow-sm">
            <div class="font-bold flex items-center gap-2">
                <span>⚠️ Mohon lengkapi seluruh pertanyaan kuesioner:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs pl-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($showResults)

        {{-- =========================================================
             TAMPILAN HASIL REKOMENDASI TERBARU (FORM TERSEMBUNYI)
        ========================================================= --}}

        {{-- HEADER BANNER HASIL --}}
        <div class="bg-gradient-to-r from-[#991b1b] via-[#b91c1c] to-[#da2424] rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-[#fecaca] border border-white/10">
                        🏆 Hasil Rekomendasi TOPSIS Terbaru
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                        Hasil Rekomendasi UKM Anda
                    </h1>
                    <p class="text-xs md:text-sm text-[#fecaca] max-w-2xl leading-relaxed">
                        Berikut adalah hasil rekomendasi UKM paling sesuai untuk Anda berdasarkan jawaban kuesioner preferensi terakhir.
                    </p>
                </div>

                {{-- WAKTU PENGISIAN TERAKHIR & AKSI --}}
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-xs space-y-2 flex-shrink-0 min-w-[220px] text-center">
                    <div class="text-[#fecaca] font-medium">Tanggal Pengisian</div>
                    <div class="font-bold text-sm text-white">
                        {{ $latestRun ? \Carbon\Carbon::parse($latestRun->created_at)->translatedFormat('d F Y, H:i') : '-' }}
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-green-500/20 text-green-300 text-[11px] font-semibold border border-green-400/30">
                        ● Hasil Terbaru
                    </div>
                </div>
            </div>
        </div>

        {{-- REKOMENDASI UTAMA HERO CARD (RANK #1) --}}
        @if($top1)
            @php $top1Reg = $registeredUkms[$top1->ukm_id] ?? null; @endphp
            <div class="bg-gradient-to-br from-[#fef2f2] via-white to-[#fef2f2] rounded-3xl border-2 border-[#da2424] shadow-md p-6 md:p-8 relative overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

                    <div class="space-y-4 flex-1">
                        <div class="flex items-center gap-3">
                            <span class="px-4 py-1.5 rounded-full text-xs font-black bg-[#da2424] text-white shadow-sm flex items-center gap-1.5">
                                🏆 REKOMENDASI UTAMA (#1)
                            </span>
                            <span class="text-xs font-mono font-bold text-[#da2424]">
                                Nilai Preferensi: <strong class="text-xl text-[#da2424] ml-1">{{ $fmtPercent($top1->nilai_preferensi) }}</strong>
                            </span>
                        </div>

                        <div>
                            <h2 class="text-2xl md:text-3xl font-extrabold text-[#1f2937]">
                                {{ $top1->nama_ukm }}
                            </h2>
                            <p class="text-xs md:text-sm text-[#6b7280] mt-2 leading-relaxed max-w-3xl">
                                {{ $top1->deskripsi ?? 'Unit Kegiatan Mahasiswa Universitas Muhammadiyah Cirebon.' }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 text-xs text-[#6b7280] pt-2 border-t border-[#fecaca]">
                            <span>Status Rekomendasi: <strong class="font-bold text-[#258260]">Prioritas Utama (Paling Sesuai)</strong></span>
                        </div>
                    </div>

                    {{-- AKSI REKOMENDASI UTAMA --}}
                    <div class="flex-shrink-0 flex flex-col sm:flex-row items-center gap-3 self-start md:self-center">
                        <a href="{{ route('mahasiswa.ukm.show', $top1->ukm_id) }}"
                           class="w-full sm:w-auto px-5 py-3.5 rounded-2xl bg-[#fee2e2] text-[#8B0000] hover:bg-[#da2424] hover:text-white text-xs font-bold transition text-center shadow-sm">
                            Lihat Detail UKM
                        </a>

                        @if($top1Reg)
                            <div class="px-5 py-3 rounded-2xl bg-[#eaf8f2] text-[#258260] border border-[#c9eadc] text-xs font-bold text-center space-y-1 w-full sm:w-auto">
                                <div>✓ Sudah Mendaftar</div>
                                <div class="text-[11px] font-normal opacity-90">Status: {{ ucfirst(str_replace('_', ' ', $top1Reg)) }}</div>
                            </div>
                        @else
                            <a href="{{ route('mahasiswa.pendaftaran.create', ['ukm' => $top1->ukm_id, 'topsis_result_id' => $top1->id]) }}"
                               class="w-full px-6 py-3.5 rounded-2xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-extrabold transition shadow-md flex items-center justify-center gap-2">
                                Isi Form Pendaftaran {{ $top1->nama_ukm }} →
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        @endif

        {{-- TOP 3 HIGHLIGHT CARD --}}
        <div class="space-y-4">
            <h2 class="text-lg font-bold text-[#1f2937]">
                Peringkat 3 UKM Teratas (TOP 3)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($top3 as $rec)
                    @php $isReg = $registeredUkms[$rec->ukm_id] ?? null; @endphp
                    <div class="bg-white rounded-2xl border {{ $rec->ranking === 1 ? 'border-[#da2424] bg-[#fef2f2]' : 'border-[#fecaca]' }} shadow-sm p-6 flex flex-col justify-between space-y-5 hover:border-[#da2424] transition">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                @if($rec->ranking === 1)
                                    <span class="px-3 py-1 rounded-full text-xs font-black bg-[#da2424] text-white">
                                        Peringkat #1
                                    </span>
                                @elseif($rec->ranking === 2)
                                    <span class="px-3 py-1 rounded-full text-xs font-black bg-[#8B0000] text-white">
                                        Peringkat #2
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-black bg-[#a80000] text-white">
                                        Peringkat #3
                                    </span>
                                @endif

                                <span class="text-xs font-mono font-extrabold text-[#da2424] text-base">
                                    {{ $fmtPercent($rec->nilai_preferensi) }}
                                </span>
                            </div>

                            <div>
                                <h3 class="font-bold text-[#1f2937] text-base mb-1">
                                    {{ $rec->nama_ukm }}
                                </h3>
                                <p class="text-xs text-[#6b7280] line-clamp-3 leading-relaxed">
                                    {{ $rec->deskripsi ?? 'Unit Kegiatan Mahasiswa Universitas Muhammadiyah Cirebon.' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#fecaca] space-y-2">
                            <a href="{{ route('mahasiswa.ukm.show', $rec->ukm_id) }}"
                               class="block w-full py-2 rounded-xl bg-[#fdf2f2] hover:bg-[#fee2e2] text-[#da2424] text-xs font-bold text-center border border-[#fecaca] transition">
                                Detail UKM
                            </a>

                            @if($isReg)
                                <div class="py-2.5 px-3 rounded-xl bg-[#eaf8f2] text-[#258260] text-xs font-bold text-center">
                                    ✓ {{ ucfirst(str_replace('_', ' ', $isReg)) }}
                                </div>
                            @else
                                <a href="{{ route('mahasiswa.pendaftaran.create', ['ukm' => $rec->ukm_id, 'topsis_result_id' => $rec->id]) }}"
                                   class="block w-full py-2.5 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold text-center transition shadow-sm">
                                    Daftar UKM Ini →
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- TABEL HASIL TOPSIS 7 UKM --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-[#fecaca] flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-[#1f2937]">
                        Hasil Perangkingan TOPSIS Seluruh 7 UKM
                    </h2>
                    <p class="text-xs text-[#6b7280] mt-0.5">
                        Daftar lengkap 7 UKM diurutkan berdasarkan skor rekomendasi TOPSIS
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#fdf2f2] text-[#4b5563] text-xs uppercase tracking-wider font-semibold border-b border-[#fecaca]">
                        <tr>
                            <th class="px-6 py-4 text-center">Peringkat</th>
                            <th class="px-6 py-4">Nama UKM</th>
                            <th class="px-6 py-4 text-center">Nilai Preferensi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#fecaca] text-sm text-[#1f2937]">
                        @foreach($latestResults as $res)
                            @php $registeredStatus = $registeredUkms[$res->ukm_id] ?? null; @endphp
                            <tr class="hover:bg-[#fdf2f2]/70 transition {{ $res->ranking === 1 ? 'bg-[#fef2f2]/40 font-semibold' : '' }}">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $res->ranking === 1 ? 'bg-[#8B0000] text-white' : ($res->ranking <= 3 ? 'bg-[#a80000] text-white' : 'bg-[#fee2e2] text-[#8B0000]') }} font-bold text-xs">
                                        #{{ $res->ranking }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-[#1f2937]">
                                    {{ $res->nama_ukm }}
                                </td>
                                <td class="px-6 py-4 text-center font-mono font-bold text-[#da2424] text-base">
                                    {{ $fmtPercent($res->nilai_preferensi) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center justify-center gap-2">
                                        <a href="{{ route('mahasiswa.ukm.show', $res->ukm_id) }}"
                                           class="px-3 py-1.5 rounded-xl border border-[#fecaca] text-[#da2424] hover:bg-[#fef2f2] text-xs font-bold transition">
                                            Detail
                                        </a>

                                        @if($registeredStatus)
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-[#eaf8f2] text-[#258260]">
                                                ✓ {{ ucfirst(str_replace('_', ' ', $registeredStatus)) }}
                                            </span>
                                        @else
                                            <a href="{{ route('mahasiswa.pendaftaran.create', ['ukm' => $res->ukm_id, 'topsis_result_id' => $res->id]) }}"
                                               class="px-4 py-1.5 rounded-xl bg-[#da2424] text-white text-xs font-bold hover:bg-[#b91c1c] shadow-sm transition">
                                                Daftar UKM
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TOMBOL AKSI UBAH KUESIONER & LAPORAN LENGKAP --}}
        <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-[#6b7280]">
                Ingin menyesuaikan kembali preferensi Anda? Pilihan lama Anda akan tetap tersimpan sebagai riwayat.
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                @if($latestRun)
                    <a href="{{ route('mahasiswa.rekomendasi.pdf', $latestRun->id) }}"
                       class="w-full sm:w-auto text-center px-5 py-3 rounded-xl bg-[#fee2e2] hover:bg-[#da2424] text-[#8B0000] hover:text-white text-xs font-bold transition flex items-center justify-center gap-2 border border-[#fecaca]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Download PDF
                    </a>
                @endif

                <a href="{{ route('mahasiswa.rekomendasi.index') }}"
                   class="w-full sm:w-auto text-center px-5 py-3 rounded-xl border border-[#fecaca] text-[#da2424] hover:bg-[#fef2f2] text-xs font-bold transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    Lihat Laporan Lengkap
                </a>

                <a href="{{ route('mahasiswa.kuesioner.index', ['edit' => 1]) }}"
                   class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Ubah Input Kuesioner
                </a>
            </div>
        </div>

    @else

        {{-- =========================================================
             FORM KUESIONER PREFERENSI MAHASISWA
        ========================================================= --}}

        {{-- HEADER BANNER INSTRUKSI --}}
        <div class="bg-gradient-to-r from-[#5F0000] via-[#770000] to-[#8B0000] rounded-3xl p-6 md:p-8 text-white shadow-md relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-semibold text-[#fecaca] border border-white/10">
                        📝 Form Kuesioner Preferensi SPK
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                        Kuesioner Preferensi UKM
                    </h1>
                    <p class="text-xs md:text-sm text-[#fecaca] max-w-2xl leading-relaxed">
                        Lengkapi 5 kriteria di bawah ini sesuai dengan kondisi dan minat Anda. Sistem SPK akan menghitung kecocokan Anda terhadap 7 UKM menggunakan algoritma TOPSIS secara dinamis.
                    </p>
                </div>

                {{-- BADGE STATUS PENGISIAN --}}
                <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-4 text-xs space-y-1 flex-shrink-0 min-w-[200px] text-center">
                    <div class="text-[#fecaca] font-medium">Status Pengisian</div>
                    @if($isEdit)
                        <div class="font-bold text-sm text-amber-300">🔄 Mode Ubah Jawaban</div>
                        <div class="text-white/80 text-[11px]">Riwayat lama tersimpan</div>
                    @else
                        <div class="font-bold text-sm text-amber-300">⏳ Belum Mengisi</div>
                        <div class="text-white/80 text-[11px]">Pengisian Baru</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- BANNER INFORMASI MODUL UBAH JAWABAN --}}
        @if($isEdit)
            <div class="bg-[#fef2f2] border-2 border-[#da2424]/30 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#fee2e2] text-[#da2424] font-bold text-lg flex items-center justify-center flex-shrink-0">
                    ℹ️
                </div>
                <div class="text-xs text-[#1f2937] space-y-1">
                    <div class="font-bold text-sm text-[#da2424]">Anda sedang mengubah jawaban kuesioner.</div>
                    <p class="text-[#55506f] leading-relaxed">
                        Jawaban dari pengisian terakhir Anda telah otomatis terisi di bawah ini. Setelah Anda mengirimkan jawaban baru, sistem akan menghitung ulang rekomendasi TOPSIS baru dan menyimpan hasil sebelumnya sebagai riwayat.
                    </p>
                </div>
            </div>
        @endif

        {{-- FORM KUESIONER --}}
        <form action="{{ route('mahasiswa.kuesioner.store') }}" method="POST" class="space-y-8">
            @csrf

            @foreach($criteria as $index => $c)
                @php
                    $subKritList = $subKriteriaAll->get($c->id) ?? collect();
                    $savedVal = $savedAnswers[$c->id] ?? null;
                    $selectedSubId = old("answers.{$c->id}", $savedVal);
                @endphp

                <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 space-y-5 hover:border-[#da2424] transition">

                    {{-- CRITERIA HEADER --}}
                    <div class="flex items-start justify-between border-b border-[#fecaca] pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#fee2e2] text-[#da2424] font-black text-sm flex items-center justify-center shadow-sm">
                                {{ $c->kode }}
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-[#1f2937]">
                                    Kriteria {{ $index + 1 }}: {{ $c->nama_kriteria }}
                                </h2>
                                <p class="text-xs text-[#6b7280] mt-0.5">
                                    Kategori Penilaian: <span class="capitalize font-semibold text-[#da2424]">{{ $c->tipe }}</span>
                                </p>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-mono font-bold bg-[#fdf2f2] text-[#6b7280] border border-[#fecaca]">
                            Pertanyaan {{ $index + 1 }} dari 5
                        </span>
                    </div>

                    {{-- QUESTION INSTRUCTION --}}
                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] uppercase tracking-wider mb-3">
                            @if($c->kode === 'C1')
                                1. Pilih bidang kegiatan UKM yang paling Anda minati:
                            @elseif($c->kode === 'C2')
                                2. Pilih bakat atau keahlian utama yang Anda miliki / ingin kembangkan:
                            @elseif($c->kode === 'C3')
                                3. Pilih tingkat ketersediaan waktu luang Anda untuk kegiatan UKM:
                            @elseif($c->kode === 'C4')
                                4. Pilih target pencapaian / prestasi yang Anda harapkan di UKM:
                            @elseif($c->kode === 'C5')
                                5. Pilih kisaran anggaran biaya per semester yang dapat Anda alokasikan:
                            @else
                                Pilih salah satu opsi untuk {{ $c->nama_kriteria }}:
                            @endif
                        </label>

                        {{-- SUB-KRITERIA OPTIONS GRID --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($subKritList as $sub)
                                @php $isSelected = ((string)$selectedSubId === (string)$sub->id); @endphp

                                <label class="relative flex items-start p-4 rounded-xl border {{ $isSelected ? 'border-[#da2424] bg-[#fef2f2] ring-2 ring-[#da2424]/20' : 'border-[#fecaca] bg-white hover:border-[#da2424] hover:bg-[#fdf2f2]' }} cursor-pointer transition">
                                    <div class="flex items-center h-5 mt-0.5">
                                        <input type="radio"
                                               name="answers[{{ $c->id }}]"
                                               value="{{ $sub->id }}"
                                               class="w-4 h-4 text-[#da2424] border-[#fecaca] focus:ring-[#da2424]"
                                               {{ $isSelected ? 'checked' : '' }}
                                               required>
                                    </div>
                                    <div class="ml-3 text-xs leading-relaxed">
                                        <span class="font-bold text-[#1f2937] block text-sm">
                                            {{ $sub->nama_subkriteria }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>
            @endforeach

            {{-- ACTION BUTTONS --}}
            <div class="bg-white rounded-2xl border border-[#fecaca] shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-[#6b7280]">
                    Pastikan kelima kriteria di atas telah terisi dengan benar sebelum menyimpan.
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @if($latestRun)
                        <a href="{{ route('mahasiswa.kuesioner.index') }}"
                           class="w-full sm:w-auto text-center px-5 py-3 rounded-xl border border-[#fecaca] text-[#da2424] text-xs font-bold hover:bg-[#fef2f2] transition">
                            Batal
                        </a>
                    @else
                        <a href="{{ route('mahasiswa.dashboard') }}"
                           class="w-full sm:w-auto text-center px-5 py-3 rounded-xl border border-[#fecaca] text-[#da2424] text-xs font-bold hover:bg-[#fef2f2] transition">
                            Batal
                        </a>
                    @endif

                    <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menyimpan jawaban kuesioner dan menghitung hasil TOPSIS baru?')"
                            class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan & Lihat Hasil
                    </button>
                </div>
            </div>

        </form>

    @endif

</div>

@endsection
