<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Pendaftaran UKM - Universitas Muhammadiyah Cirebon</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 7mm 8mm 7mm 8mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 7.5pt;
            line-height: 1.25;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* HEADER & KOP UNIVERSITAS */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            border-bottom: 1.5px solid #da2424;
            padding-bottom: 5px;
        }

        .kop-logo {
            width: 45px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            max-width: 40px;
            max-height: 40px;
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

        /* SUMMARY CARDS GRID */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .stats-card {
            background-color: #fdf2f2;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 4px 5px;
            text-align: center;
        }

        .stats-label {
            font-size: 6pt;
            font-weight: bold;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stats-val {
            font-size: 9.5pt;
            font-weight: bold;
            color: #da2424;
            margin-top: 1px;
        }

        /* TABEL REKAPITULASI */
        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .rekap-table thead {
            display: table-header-group;
        }

        .rekap-table tr {
            page-break-inside: avoid;
        }

        .rekap-table th {
            background-color: #da2424;
            color: #ffffff;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 4px;
            text-align: left;
            border: 1px solid #da2424;
        }

        .rekap-table td {
            font-size: 7.5pt;
            padding: 3.5px 4px;
            border: 1px solid #fecaca;
            vertical-align: middle;
        }

        .rekap-table tr:nth-child(even) {
            background-color: #fdf2f2;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .text-red-primary { color: #8B0000; }

        .badge-status {
            display: inline-block;
            padding: 1.5px 6px;
            border-radius: 8px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-menunggu { background-color: #fff5e5; color: #b76e00; border: 1px solid #ffe2b3; }
        .badge-verifikasi { background-color: #eaf8f2; color: #258260; border: 1px solid #c9eadc; }
        .badge-perbaikan { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-selesai { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

        .empty-box {
            background-color: #fff5e5;
            border: 1px solid #ffe2b3;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
            color: #b76e00;
            font-size: 8.5pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .catatan-box {
            background-color: #fef2f2;
            border-left: 3px solid #da2424;
            padding: 4px 6px;
            font-size: 7pt;
            color: #770000;
            border-radius: 0 4px 4px 0;
        }

        /* SIGNATURE & FOOTER */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .signature-table td {
            font-size: 7.5pt;
            vertical-align: top;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
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

        $periodeMap = [
            'today'      => 'Hari Ini',
            'this_week'  => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
        ];
        $periodeLabel = $periodeMap[$selectedPeriode] ?? 'Semua Periode';
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
                <div class="doc-title">LAPORAN REKAP PENDAFTARAN UKM</div>
                <div class="doc-subtitle">Rekapitulasi pendaftaran UKM mahasiswa Universitas Muhammadiyah Cirebon</div>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 240px;">
                <div style="font-size: 7.5pt; color: #666666;">
                    <strong>UKM / Filter:</strong> {{ $selectedUkmName }} ({{ $periodeLabel }})<br>
                    <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    {{-- STATISTIK RINGKASAN (6 CARDS DINAMIS) --}}
    <table class="stats-table">
        <tr>
            <td style="width: 16.6%; padding-right: 2px;">
                <div class="stats-card">
                    <div class="stats-label">Total Pendaftar</div>
                    <div class="stats-val">{{ $stats['total_pendaftar'] }}</div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 0 2px;">
                <div class="stats-card">
                    <div class="stats-label">UKM Berpendaftar</div>
                    <div class="stats-val">{{ $stats['total_ukm_pendaftar'] }} UKM</div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 0 2px;">
                <div class="stats-card">
                    <div class="stats-label">Menunggu Verifikasi</div>
                    <div class="stats-val" style="color: #b76e00;">{{ $stats['menunggu_verifikasi'] }}</div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 0 2px;">
                <div class="stats-card">
                    <div class="stats-label">Terverifikasi</div>
                    <div class="stats-val" style="color: #258260;">{{ $stats['terverifikasi'] }}</div>
                </div>
            </td>
            <td style="width: 16.6%; padding: 0 2px;">
                <div class="stats-card">
                    <div class="stats-label">Perlu Perbaikan</div>
                    <div class="stats-val" style="color: #b45309;">{{ $stats['perlu_perbaikan'] }}</div>
                </div>
            </td>
            <td style="width: 16.6%; padding-left: 2px;">
                <div class="stats-card">
                    <div class="stats-label">Selesai</div>
                    <div class="stats-val" style="color: #1d4ed8;">{{ $stats['selesai'] }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABEL REKAPITULASI 10 KOLOM --}}
    @if(count($pendaftaranList) > 0)
        <table class="rekap-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 3.5%;">NO</th>
                    <th class="text-center" style="width: 11%;">NO. PENDAFTARAN</th>
                    <th style="width: 17%;">NAMA MAHASISWA</th>
                    <th class="text-center" style="width: 9.5%;">NIM</th>
                    <th style="width: 13%;">PROGRAM STUDI</th>
                    <th style="width: 15%;">UKM DIPILIH</th>
                    <th class="text-center" style="width: 10%;">TGL DAFTAR</th>
                    <th class="text-center" style="width: 6%;">RANK</th>
                    <th class="text-center" style="width: 6%;">NILAI %</th>
                    <th class="text-center" style="width: 9%;">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendaftaranList as $idx => $reg)
                    @php
                        $stBadgeMap = [
                            'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'badge-menunggu'],
                            'terverifikasi'       => ['label' => 'Terverifikasi',       'class' => 'badge-verifikasi'],
                            'diterima'            => ['label' => 'Terverifikasi',       'class' => 'badge-verifikasi'],
                            'perlu_perbaikan'     => ['label' => 'Perlu Perbaikan',     'class' => 'badge-perbaikan'],
                            'ditolak'             => ['label' => 'Perlu Perbaikan',     'class' => 'badge-perbaikan'],
                            'selesai'             => ['label' => 'Selesai',             'class' => 'badge-selesai'],
                        ];
                        $st = $stBadgeMap[$reg->status] ?? ['label' => ucwords(str_replace('_', ' ', $reg->status)), 'class' => 'badge-menunggu'];
                    @endphp
                    <tr>
                        <td class="text-center font-bold">{{ $idx + 1 }}</td>
                        <td class="text-center font-mono font-bold text-red-primary">{{ $reg->nomor_pendaftaran }}</td>
                        <td class="font-bold" style="color: #1f2937;">{{ $reg->nama_mahasiswa }}</td>
                        <td class="text-center font-mono font-bold text-red-primary">{{ $reg->nim }}</td>
                        <td>{{ $reg->prodi }}</td>
                        <td class="font-bold" style="color: #1f2937;">{{ $reg->nama_ukm }}</td>
                        <td class="text-center" style="font-size: 7pt; color: #555555;">
                            {{ \Carbon\Carbon::parse($reg->tanggal_daftar)->translatedFormat('d-m-Y H:i') }}
                        </td>
                        <td class="text-center font-bold">
                            {{ $reg->topsis_ranking ? '#' . $reg->topsis_ranking : '-' }}
                        </td>
                        <td class="text-center font-bold text-red-primary font-mono" style="font-size: 7.5pt;">
                            {{ $fmtPercent($reg->topsis_score) }}
                        </td>
                        <td class="text-center">
                            <span class="badge-status {{ $st['class'] }}">
                                {{ $st['label'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-box">
            Belum terdapat permohonan pendaftaran UKM yang memenuhi kriteria filter.
        </div>
    @endif

    {{-- AREA VERIFIKASI & TANDA TANGAN ADMIN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 68%;">
                <div class="catatan-box">
                    <strong>Catatan Administrasi:</strong> Laporan rekapitulasi ini memuat seluruh permohonan pendaftaran UKM yang diajukan oleh mahasiswa Universitas Muhammadiyah Cirebon beserta status verifikasi dan skor rekomendasi TOPSIS terkait.
                </div>
            </td>
            <td style="width: 32%; text-align: center; padding-left: 15px;">
                <div style="color: #555555;">
                    Cirebon, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Administrator SPK UKM,
                </div>
                <div style="height: 25px;"></div>
                <div style="font-weight: bold; color: #1f2937; text-decoration: underline;">
                    {{ Auth::user()->name ?? 'Administrator' }}
                </div>
                <div style="font-size: 6.5pt; color: #666666;">
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
                Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
            </td>
        </tr>
    </table>

</body>
</html>
