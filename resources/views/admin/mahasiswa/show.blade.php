@extends('layouts.app')

@section('title', 'Detail Mahasiswa')
@section('page-title', 'Detail Data & Riwayat Mahasiswa')

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
                <h1 class="text-3xl font-bold text-[#1f2937]">{{ $mahasiswa->nama }}</h1>
                <span class="px-3 py-1 bg-[#fef2f2] text-[#da2424] font-mono text-xs font-bold rounded-full">
                    NIM: {{ $mahasiswa->nim }}
                </span>
            </div>
            <p class="text-sm text-[#6b7280] mt-1">
                {{ $mahasiswa->prodi }} • {{ $mahasiswa->fakultas ?? 'Fakultas Teknik' }} • Angkatan {{ $mahasiswa->angkatan ?? date('Y') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}"
               class="px-4 py-2.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 text-sm font-semibold hover:bg-amber-100 transition">
                Edit Mahasiswa
            </a>
            <a href="{{ route('admin.mahasiswa.index') }}"
               class="px-4 py-2.5 rounded-xl border border-[#fecaca] text-[#da2424] font-semibold text-sm hover:bg-[#fef2f2] transition">
                ← Kembali ke Daftar
            </a>
        </div>
    </div>

    {{-- PROFIL & AKUN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-6 space-y-4">
            <h3 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider border-b border-[#fecaca] pb-2">Informasi Akun</h3>
            <div>
                <div class="text-xs text-[#6b7280]">Email</div>
                <div class="text-sm font-semibold text-[#da2424] font-mono mt-0.5">{{ $mahasiswa->email }}</div>
            </div>
            <div>
                <div class="text-xs text-[#6b7280]">Username</div>
                <div class="text-sm font-semibold text-[#1f2937] mt-0.5">{{ $mahasiswa->username }}</div>
            </div>
            <div>
                <div class="text-xs text-[#6b7280]">Role Sistem</div>
                <div class="text-sm font-semibold text-[#1f2937] capitalize mt-0.5">{{ $mahasiswa->role }}</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-6 space-y-4">
            <h3 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider border-b border-[#fecaca] pb-2">Detail Identitas</h3>
            <div>
                <div class="text-xs text-[#6b7280]">Jenis Kelamin</div>
                <div class="text-sm font-semibold text-[#1f2937] mt-0.5">
                    {{ $mahasiswa->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}
                </div>
            </div>
            <div>
                <div class="text-xs text-[#6b7280]">Nomor HP / WhatsApp</div>
                <div class="text-sm font-semibold text-[#1f2937] mt-0.5">{{ $mahasiswa->no_hp ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-[#6b7280]">Terdaftar Sejak</div>
                <div class="text-sm font-semibold text-[#1f2937] mt-0.5">
                    {{ \Carbon\Carbon::parse($mahasiswa->created_at)->format('d M Y H:i') }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm p-6 space-y-4">
            <h3 class="text-xs font-bold text-[#6b7280] uppercase tracking-wider border-b border-[#fecaca] pb-2">Status SPK & Pendaftaran</h3>
            <div>
                <div class="text-xs text-[#6b7280]">Status Kuesioner</div>
                <div class="mt-1">
                    @if($latestSession && $latestSession->status === 'completed')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf8f2] text-[#258260]">
                            ✓ Selesai Diisi ({{ \Carbon\Carbon::parse($latestSession->completed_at)->format('d M Y') }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#fff5e5] text-[#b76e00]">
                            Belum Mengisi Kuesioner
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <div class="text-xs text-[#6b7280]">Total Pendaftaran UKM</div>
                <div class="text-lg font-bold text-[#da2424] mt-0.5">{{ count($pendaftaranList) }} UKM</div>
            </div>
        </div>
    </div>

    {{-- HASIL KUESIONER --}}
    <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#fecaca] flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Jawaban Kuesioner Terakhir</h2>
                <p class="text-xs text-[#6b7280] mt-0.5">Preferensi 5 Kriteria yang diinputkan mahasiswa.</p>
            </div>
        </div>

        <div class="p-6">
            @if(count($kuesionerAnswers) > 0)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($kuesionerAnswers as $ans)
                        <div class="p-4 rounded-xl bg-[#fdf2f2] border border-[#fecaca]">
                            <div class="text-xs font-bold text-[#da2424]">{{ $ans->kriteria_kode }} — {{ $ans->nama_kriteria }}</div>
                            <div class="text-sm font-semibold text-[#1f2937] mt-2">{{ $ans->nama_sub_kriteria }}</div>
                            <div class="text-xs text-[#6b7280] mt-1">Skala Nilai: {{ $fmt($ans->nilai) }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-[#6b7280] text-sm">
                    Mahasiswa ini belum mengisikan jawaban kuesioner SPK.
                </div>
            @endif
        </div>
    </div>

    {{-- HASIL REKOMENDASI TOPSIS --}}
    <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#fecaca] flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Hasil Rekomendasi TOPSIS Mahasiswa</h2>
                <p class="text-xs text-[#6b7280] mt-0.5">Perangkingan UKM berdasarkan skor preferensi mahasiswa.</p>
            </div>
            @if($latestTopsisRun)
                <div class="flex flex-wrap items-center gap-3">
                    @if(isset($allTopsisRuns) && $allTopsisRuns->count() > 1)
                        <div class="relative inline-block text-left" x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#fdf2f2] border border-[#fecaca] text-xs font-semibold text-[#da2424] hover:bg-[#fef2f2] transition">
                                <span>Pilih Sesi (Total: {{ $allTopsisRuns->count() }})</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-64 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20 divide-y divide-[#fecaca] p-1" style="display: none;">
                                @foreach($allTopsisRuns as $run)
                                    <a href="{{ route('admin.mahasiswa.rekomendasi.pdf', ['id' => $mahasiswa->id, 'run_id' => $run->id]) }}" target="_blank" class="group flex items-center justify-between px-3 py-2 text-xs text-[#1f2937] hover:bg-[#fef2f2] rounded-lg transition">
                                        <span>Run #{{ $run->id }} ({{ \Carbon\Carbon::parse($run->created_at)->format('d M Y, H:i') }})</span>
                                        <span class="text-[10px] font-bold text-[#da2424] group-hover:underline">PDF →</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('admin.mahasiswa.rekomendasi.pdf', ['id' => $mahasiswa->id, 'run_id' => $latestTopsisRun->id]) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#da2424] hover:bg-[#b91c1c] text-white text-xs font-bold shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Download PDF Laporan</span>
                    </a>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            @if(count($topsisResults) > 0)
                <table class="w-full text-left">
                    <thead class="bg-[#fdf2f2] text-[#4b5563] text-xs uppercase tracking-wider font-semibold border-b border-[#fecaca]">
                        <tr>
                            <th class="px-6 py-3 text-center">Rank</th>
                            <th class="px-6 py-3">Nama UKM</th>
                            <th class="px-6 py-3 text-center">Preferensi (Ci)</th>
                            <th class="px-6 py-3 text-center">Separasi (S⁺)</th>
                            <th class="px-6 py-3 text-center">Separasi (S⁻)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#fecaca] text-sm text-[#1f2937]">
                        @foreach($topsisResults as $res)
                            <tr class="{{ $res->ranking === 1 ? 'bg-[#fef2f2]/50 font-semibold' : '' }}">
                                <td class="px-6 py-3.5 text-center">
                                    @if($res->ranking === 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#da2424] text-white text-xs font-bold">1</span>
                                    @else
                                        <span class="text-xs font-bold text-[#6b7280]">{{ $res->ranking }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-[#1f2937]">{{ $res->nama_ukm }}</div>
                                    @if($res->ranking === 1)
                                        <span class="text-[10px] text-amber-700 font-bold bg-amber-100 px-2 py-0.5 rounded-full">🏆 Rekomendasi Utama</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-center font-mono font-bold text-[#da2424]">
                                    {{ $fmt($res->nilai_preferensi) }}
                                </td>
                                <td class="px-6 py-3.5 text-center font-mono text-xs text-[#6b7280]">
                                    {{ $fmt($res->s_plus) }}
                                </td>
                                <td class="px-6 py-3.5 text-center font-mono text-xs text-[#6b7280]">
                                    {{ $fmt($res->s_minus) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-8 text-center text-[#6b7280] text-sm">
                    Belum ada riwayat rekomendasi TOPSIS untuk mahasiswa ini.
                </div>
            @endif
        </div>
    </div>

    {{-- RIWAYAT PENDAFTARAN UKM --}}
    <div class="bg-white rounded-2xl border border-[#f8c4c4] shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-[#fecaca]">
            <h2 class="text-lg font-bold text-[#1f2937]">Riwayat Pendaftaran UKM</h2>
            <p class="text-xs text-[#6b7280] mt-0.5">Daftar UKM yang diajukan oleh mahasiswa ini.</p>
        </div>

        <div class="overflow-x-auto">
            @if(count($pendaftaranList) > 0)
                <table class="w-full text-left">
                    <thead class="bg-[#fdf2f2] text-[#4b5563] text-xs uppercase tracking-wider font-semibold border-b border-[#fecaca]">
                        <tr>
                            <th class="px-6 py-3">No Pendaftaran</th>
                            <th class="px-6 py-3">UKM Tujuan</th>
                            <th class="px-6 py-3 text-center">Rank TOPSIS</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3">Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#fecaca] text-sm text-[#1f2937]">
                        @foreach($pendaftaranList as $reg)
                            <tr>
                                <td class="px-6 py-3.5 font-mono text-xs font-bold text-[#da2424]">
                                    {{ $reg->nomor_pendaftaran }}
                                </td>
                                <td class="px-6 py-3.5 font-bold text-[#1f2937]">
                                    {{ $reg->nama_ukm }}
                                </td>
                                <td class="px-6 py-3.5 text-center font-mono text-xs">
                                    {{ $reg->topsis_ranking ? 'Rank #' . $reg->topsis_ranking : '-' }}
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    @if($reg->status === 'diterima')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#eaf8f2] text-[#258260]">Diterima</span>
                                    @elseif($reg->status === 'ditolak')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">Ditolak</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#fff5e5] text-[#b76e00]">Menunggu Verifikasi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-xs text-[#6b7280]">
                                    {{ \Carbon\Carbon::parse($reg->tanggal_daftar)->format('d M Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-8 text-center text-[#6b7280] text-sm">
                    Mahasiswa ini belum mendaftar di UKM manapun.
                </div>
            @endif
        </div>
    </div>

</div>

@endsection
