<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Hasil Rekomendasi Mahasiswa - Universitas Muhammadiyah Cirebon</title>
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
            margin-bottom: 6px;
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
            padding: 4px 6px;
            text-align: center;
        }

        .stats-label {
            font-size: 6.5pt;
            font-weight: bold;
            color: #666666;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stats-val {
            font-size: 10pt;
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

        /* EMPTY STATE & CATATAN */
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
                <div class="doc-title">LAPORAN REKAP HASIL REKOMENDASI MAHASISWA</div>
                <div class="doc-subtitle">Rekap hasil pengisian kuesioner dan rekomendasi UKM mahasiswa (Metode AHP + TOPSIS)</div>
            </td>
            <td style="text-align: right; vertical-align: middle; width: 220px;">
                <div style="font-size: 7.5pt; color: #666666;">
                    <strong>Tanggal Cetak:</strong><br>
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    {{-- STATISTIK RINGKASAN --}}
    <table class="stats-table">
        <tr>
            <td style="width: 25%; padding-right: 3px;">
                <div class="stats-card">
                    <div class="stats-label">Total Mahasiswa Mengisi</div>
                    <div class="stats-val">{{ $totalMahasiswaMengisi }} Mahasiswa</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 1.5px;">
                <div class="stats-card">
                    <div class="stats-label">Total Sesi Rekomendasi</div>
                    <div class="stats-val">{{ $totalRekomendasi }} Sesi</div>
                </div>
            </td>
            <td style="width: 30%; padding: 0 1.5px;">
                <div class="stats-card">
                    <div class="stats-label">UKM Top Rekomendasi #1</div>
                    <div class="stats-val" style="font-size: 8.5pt;">{{ $topUkmMode }}</div>
                </div>
            </td>
            <td style="width: 20%; padding-left: 3px;">
                <div class="stats-card">
                    <div class="stats-label">Status Dokumen</div>
                    <div class="stats-val" style="color: #258260; font-size: 8.5pt;">Resmi / Aktif</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABEL UTAMA REKAPITULASI --}}
    @if(!empty($rekapList) && count($rekapList) > 0)
        <table class="rekap-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 3%;">NO</th>
                    <th style="width: 15%;">NAMA MAHASISWA</th>
                    <th class="text-center" style="width: 9%;">NIM</th>
                    <th style="width: 12%;">PROGRAM STUDI</th>
                    <th class="text-center" style="width: 10%;">TGL KUESIONER</th>
                    <th style="width: 13%;">REKOMENDASI #1</th>
                    <th class="text-center" style="width: 6.5%;">NILAI</th>
                    <th style="width: 11.5%;">REKOMENDASI #2</th>
                    <th class="text-center" style="width: 6%;">NILAI</th>
                    <th style="width: 11.5%;">REKOMENDASI #3</th>
                    <th class="text-center" style="width: 6%;">NILAI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapList as $idx => $row)
                    <tr>
                        <td class="text-center font-bold">{{ $idx + 1 }}</td>
                        <td class="font-bold" style="color: #1f2937;">{{ $row->nama }}</td>
                        <td class="text-center font-mono font-bold text-red-primary">{{ $row->nim }}</td>
                        <td>{{ $row->prodi ?: '-' }}</td>
                        <td class="text-center" style="font-size: 7pt; color: #555555;">
                            {{ \Carbon\Carbon::parse($row->tanggal_kuesioner)->translatedFormat('d-m-Y H:i') }}
                        </td>
                        <td class="font-bold text-red-primary">{{ $row->rank1_ukm }}</td>
                        <td class="text-center font-bold text-red-primary font-mono" style="font-size: 7.5pt;">
                            {{ $fmtPercent($row->rank1_score) }}
                        </td>
                        <td>{{ $row->rank2_ukm }}</td>
                        <td class="text-center font-mono" style="font-size: 7pt;">
                            {{ $fmtPercent($row->rank2_score) }}
                        </td>
                        <td>{{ $row->rank3_ukm }}</td>
                        <td class="text-center font-mono" style="font-size: 7pt;">
                            {{ $fmtPercent($row->rank3_score) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-box">
            Belum terdapat mahasiswa yang memiliki hasil rekomendasi kuesioner TOPSIS.
        </div>
    @endif

    {{-- AREA VERIFIKASI & TANDA TANGAN ADMIN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 68%;">
                <div class="catatan-box">
                    <strong>Catatan Administrasi:</strong> Laporan rekapitulasi ini menyajikan hasil rekomendasi TOPSIS aktif (sesi pengisian kuesioner terbaru) bagi setiap mahasiswa yang telah menyelesaikan pengisian kuesioner minat UKM di Universitas Muhammadiyah Cirebon.
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
