<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekomendasi UKM - {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 10mm 8mm 10mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 8pt;
            line-height: 1.25;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* HEADER & KOP UNIVERSITAS */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            border-bottom: 1.5px solid #da2424;
            padding-bottom: 5px;
        }

        .kop-logo {
            width: 50px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            max-width: 45px;
            max-height: 45px;
        }

        .kop-text {
            vertical-align: middle;
            padding-left: 8px;
        }

        .univ-name {
            font-size: 11pt;
            font-weight: bold;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .doc-title {
            font-size: 10pt;
            font-weight: bold;
            color: #da2424;
            margin: 1px 0 0 0;
            text-transform: uppercase;
        }

        .doc-subtitle {
            font-size: 7.5pt;
            color: #666666;
            margin: 1px 0 0 0;
        }

        /* CARD & BOX UTILITY */
        .card-box {
            background-color: #fdf2f2;
            border: 1px solid #fecaca;
            border-radius: 5px;
            padding: 6px 8px;
            margin-bottom: 6px;
        }

        .card-header-title {
            font-size: 8pt;
            font-weight: bold;
            color: #da2424;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            border-bottom: 1px solid #fecaca;
            padding-bottom: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            font-size: 8pt;
            padding: 1.5px 2px;
            vertical-align: top;
        }

        .info-label {
            color: #666666;
            width: 34%;
        }

        .info-colon {
            width: 3%;
            color: #666666;
        }

        .info-value {
            font-weight: bold;
            color: #1f2937;
            width: 63%;
        }

        /* HERO REKOMENDASI UTAMA */
        .hero-card {
            background-color: #fef2f2;
            border: 1.5px solid #da2424;
            border-radius: 6px;
            padding: 7px 10px;
            margin-bottom: 6px;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background-color: #da2424;
            color: #ffffff;
            font-size: 7pt;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-ukm-name {
            font-size: 13pt;
            font-weight: bold;
            color: #1f2937;
            margin: 2px 0 1px 0;
        }

        .hero-score-val {
            font-size: 16pt;
            font-weight: bold;
            color: #da2424;
            font-family: 'Courier New', Courier, monospace;
        }

        .hero-score-label {
            font-size: 7.5pt;
            color: #555555;
        }

        /* TABLES */
        .section-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1f2937;
            margin: 6px 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .data-table th {
            background-color: #da2424;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 3.5px 5px;
            text-align: left;
            border: 1px solid #da2424;
        }

        .data-table td {
            font-size: 8pt;
            padding: 3px 5px;
            border: 1px solid #fecaca;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) {
            background-color: #fdf2f2;
        }

        .data-table tr.rank-1-row {
            background-color: #fef2f2;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-red-primary { color: #8B0000; }

        /* RANK BADGES */
        .rank-badge {
            display: inline-block;
            width: 16px;
            height: 16px;
            line-height: 16px;
            border-radius: 50%;
            background-color: #fee2e2;
            color: #8B0000;
            font-weight: bold;
            font-size: 7.5pt;
            text-align: center;
        }

        .rank-badge.rank-1 {
            background-color: #da2424;
            color: #ffffff;
        }

        .rank-badge.rank-top3 {
            background-color: #8B0000;
            color: #ffffff;
        }

        /* RINGKASAN BOX */
        .summary-box {
            background-color: #fdf2f2;
            border: 1px solid #fecaca;
            border-radius: 5px;
            padding: 5px 8px;
            margin-bottom: 6px;
        }

        /* SIGNATURE & FOOTER */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .signature-table td {
            font-size: 7.5pt;
            vertical-align: top;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            border-top: 1px solid #fecaca;
            padding-top: 3px;
        }

        .footer-table td {
            font-size: 6.5pt;
            color: #888888;
        }
    </style>
</head>
<body>

    @php
        // Embedding logo image in base64 if file exists
        $logoPath = public_path('images/logo-umc.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('images/landing/logo.png');
        }
        if (!file_exists($logoPath)) {
            $logoPath = public_path('images/logo.png');
        }

        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }

        $fmtPercent = function ($val) {
            if ($val === null || $val === '') return '-';
            $percent = (float)$val * 100;
            $formatted = number_format($percent, 2, ',', '.');
            if (str_ends_with($formatted, ',00')) {
                $formatted = substr($formatted, 0, -3);
            }
            return $formatted . '%';
        };

        $top1 = $selectedResults->firstWhere('ranking', 1);
    @endphp

    {{-- KOP HEADER --}}
    <table class="kop-table">
        <tr>
            @if($logoBase64)
                <td class="kop-logo">
                    <img src="{{ $logoBase64 }}" alt="Logo UMC">
                </td>
            @endif
            <td class="kop-text">
                <div class="univ-name">Universitas Muhammadiyah Cirebon</div>
                <div class="doc-title">LAPORAN REKOMENDASI MAHASISWA</div>
                <div class="doc-subtitle">Sistem Pendukung Keputusan Pemilihan UKM Sesuai Minat Mahasiswa (Metode AHP + TOPSIS)</div>
            </td>
        </tr>
    </table>

    {{-- GRID IDENTITAS & INFORMASI KUESIONER --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 3px;">
                <div class="card-box" style="margin-bottom: 0;">
                    <div class="card-header-title">Identitas Mahasiswa</div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">Nama Lengkap</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->nama }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">NIM</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->nim }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Program Studi</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->prodi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Fakultas / Angk.</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->fakultas ?: '-' }} / {{ $mahasiswa->angkatan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Email / Kontak</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->email }} / {{ $mahasiswa->no_hp ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 3px;">
                <div class="card-box" style="margin-bottom: 0;">
                    <div class="card-header-title">Informasi Pengisian Kuesioner</div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">ID Run SPK</td>
                            <td class="info-colon">:</td>
                            <td class="info-value text-red-primary" style="font-family: 'Courier New', Courier, monospace;">#{{ $selectedRun->id }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">ID Sesi Kuesioner</td>
                            <td class="info-colon">:</td>
                            <td class="info-value" style="font-family: 'Courier New', Courier, monospace;">#{{ $selectedRun->questionnaire_session_id }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Tgl. Pengisian</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ \Carbon\Carbon::parse($selectedRun->created_at)->translatedFormat('d F Y, H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <td class="info-label">Metode Evaluasi</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">AHP + TOPSIS</td>
                        </tr>
                        <tr>
                            <td class="info-label">Status Sesi</td>
                            <td class="info-colon">:</td>
                            <td class="info-value" style="color: #258260;">Selesai (Completed)</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- HERO CARD REKOMENDASI UTAMA --}}
    @if($top1)
        <div class="hero-card">
            <span class="hero-badge">REKOMENDASI UTAMA (#1)</span>
            <div class="hero-ukm-name">{{ $top1->nama_ukm }}</div>
            <div>
                <span class="hero-score-val">{{ $fmtPercent($top1->nilai_preferensi) }}</span>
            </div>
            <div class="hero-score-label">Tingkat Prioritas Kesesuaian Preferensi Mahasiswa</div>
        </div>
    @endif

    {{-- TABEL HASIL PERANGKINGAN TOPSIS 7 UKM --}}
    <div class="section-title">Hasil Perangkingan TOPSIS Seluruh Alternatif UKM</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">Rank</th>
                <th style="width: 50%;">Nama Unit Kegiatan Mahasiswa (UKM)</th>
                <th class="text-center" style="width: 20%;">Nilai Preferensi</th>
                <th class="text-center" style="width: 20%;">Kategori Prioritas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($selectedResults as $res)
                <tr class="{{ $res->ranking === 1 ? 'rank-1-row' : '' }}">
                    <td class="text-center">
                        <span class="rank-badge {{ $res->ranking === 1 ? 'rank-1' : ($res->ranking <= 3 ? 'rank-top3' : '') }}">
                            {{ $res->ranking }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ $res->ranking === 1 ? 'text-red-primary font-bold' : '' }}">
                            {{ $res->nama_ukm }}
                        </span>
                    </td>
                    <td class="text-center font-bold text-red-primary" style="font-family: 'Courier New', Courier, monospace; font-size: 8.5pt;">
                        {{ $fmtPercent($res->nilai_preferensi) }}
                    </td>
                    <td class="text-center font-bold" style="font-size: 7.5pt;">
                        @if($res->ranking === 1)
                            <span style="color: #da2424;">Prioritas Utama</span>
                        @elseif($res->ranking <= 3)
                            <span style="color: #8B0000;">Prioritas Tinggi</span>
                        @else
                            <span style="color: #666666;">Alternatif</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- RINGKASAN REKOMENDASI --}}
    <div class="section-title">Ringkasan Laporan Rekomendasi</div>
    <div class="summary-box">
        <table class="info-table">
            <tr>
                <td style="width: 25%; color: #666666;">Jumlah Alternatif UKM</td>
                <td style="width: 2%;">:</td>
                <td style="width: 73%; font-weight: bold; color: #1f2937;">{{ $selectedResults->count() }} UKM</td>
            </tr>
            <tr>
                <td style="color: #666666;">UKM Rekomendasi Tertinggi</td>
                <td>:</td>
                <td style="font-weight: bold; color: #da2424;">
                    {{ $top1 ? $top1->nama_ukm : '-' }} (Peringkat #1)
                </td>
            </tr>
            <tr>
                <td style="color: #666666;">Nilai Preferensi Tertinggi</td>
                <td>:</td>
                <td style="font-weight: bold; color: #da2424; font-family: 'Courier New', Courier, monospace;">
                    {{ $top1 ? $fmtPercent($top1->nilai_preferensi) : '-' }}
                </td>
            </tr>
            <tr>
                <td style="color: #666666;">Tanggal Kuesioner Selesai</td>
                <td>:</td>
                <td style="font-weight: bold; color: #1f2937;">
                    {{ \Carbon\Carbon::parse($selectedRun->created_at)->translatedFormat('d F Y, H:i') }} WIB
                </td>
            </tr>
        </table>
    </div>

    {{-- AREA VERIFIKASI & TANDA TANGAN ADMIN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 60%;">
                <div style="background-color: #fef2f2; border-left: 3px solid #da2424; padding: 4px 6px; font-size: 7.5pt; color: #770000; border-radius: 0 4px 4px 0;">
                    <strong>Catatan Sistem:</strong> Laporan rekomendasi ini diterbitkan oleh Administrator SPK UKM Universitas Muhammadiyah Cirebon berdasarkan data hasil evaluasi preferensi mahasiswa menggunakan metode AHP dan TOPSIS.
                </div>
            </td>
            <td style="width: 40%; text-align: center; padding-left: 15px;">
                <div style="color: #555555;">
                    Cirebon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Administrator SPK UKM,
                </div>
                <div style="height: 30px;"></div>
                <div style="font-weight: bold; color: #1f2937; text-decoration: underline;">
                    {{ Auth::user()->name ?? 'Administrator' }}
                </div>
                <div style="font-size: 7pt; color: #666666;">
                    Pengelola Sistem Informasi UKM UMC
                </div>
            </td>
        </tr>
    </table>

    {{-- FOOTER HALAMAN --}}
    <table class="footer-table">
        <tr>
            <td>Sistem Pendukung Keputusan Pemilihan UKM &bull; Universitas Muhammadiyah Cirebon</td>
            <td class="text-right">
                Halaman 1 dari 1 &bull; Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
            </td>
        </tr>
    </table>

</body>
</html>
