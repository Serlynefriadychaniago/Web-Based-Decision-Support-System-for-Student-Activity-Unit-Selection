<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Riwayat Rekomendasi UKM - {{ $mahasiswa->nama }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 8.5pt;
            line-height: 1.25;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* HEADER & KOP UNIVERSITAS */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            border-bottom: 1.5px solid #da2424;
            padding-bottom: 6px;
        }

        .kop-logo {
            width: 55px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            max-width: 50px;
            max-height: 50px;
        }

        .kop-text {
            vertical-align: middle;
            padding-left: 10px;
        }

        .univ-name {
            font-size: 11.5pt;
            font-weight: bold;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .doc-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #da2424;
            margin: 2px 0 0 0;
        }

        .doc-subtitle {
            font-size: 8pt;
            color: #666666;
            margin: 2px 0 0 0;
        }

        /* BOX GENERAL & CARD */
        .card-box {
            background-color: #fdf2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 7px 10px;
            margin-bottom: 8px;
        }

        .card-header-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #da2424;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            border-bottom: 1px solid #fecaca;
            padding-bottom: 3px;
        }

        /* GRID / TABLE UTILITY */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            font-size: 8.5pt;
            padding: 2px 3px;
            vertical-align: top;
        }

        .info-label {
            color: #666666;
            width: 32%;
        }

        .info-colon {
            width: 3%;
            color: #666666;
        }

        .info-value {
            font-weight: bold;
            color: #1f2937;
            width: 65%;
        }

        /* HERO CARD REKOMENDASI UTAMA */
        .hero-card {
            background-color: #fef2f2;
            border: 1.5px solid #da2424;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 8px;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background-color: #da2424;
            color: #ffffff;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 2px 10px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hero-ukm-name {
            font-size: 13.5pt;
            font-weight: bold;
            color: #1f2937;
            margin: 3px 0 1px 0;
        }

        .hero-score-box {
            margin-top: 2px;
        }

        .hero-score-val {
            font-size: 18pt;
            font-weight: bold;
            color: #da2424;
            font-family: 'Courier New', Courier, monospace;
        }

        .hero-score-label {
            font-size: 8pt;
            color: #555555;
            margin-top: 1px;
        }

        .hero-desc {
            font-size: 8pt;
            color: #666666;
            margin-top: 3px;
            line-height: 1.25;
        }

        /* TABLES */
        .section-title {
            font-size: 9pt;
            font-weight: bold;
            color: #1f2937;
            margin: 8px 0 4px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .data-table th {
            background-color: #da2424;
            color: #ffffff;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 6px;
            text-align: left;
            border: 1px solid #da2424;
        }

        .data-table td {
            font-size: 8.5pt;
            padding: 3.5px 6px;
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

        /* BADGE RANKING */
        .rank-badge {
            display: inline-block;
            width: 17px;
            height: 17px;
            line-height: 17px;
            border-radius: 50%;
            background-color: #fee2e2;
            color: #8B0000;
            font-weight: bold;
            font-size: 8pt;
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

        /* CATATAN & FOOTER */
        .catatan-box {
            background-color: #fef2f2;
            border-left: 3px solid #da2424;
            padding: 6px 10px;
            font-size: 7.5pt;
            color: #770000;
            margin-top: 8px;
            margin-bottom: 8px;
            border-radius: 0 4px 4px 0;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            border-top: 1px solid #fecaca;
            padding-top: 4px;
        }

        .footer-table td {
            font-size: 7.5pt;
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
                <div class="doc-title">DETAIL RIWAYAT REKOMENDASI UKM</div>
                <div class="doc-subtitle">Sistem Pendukung Keputusan Pemilihan UKM Sesuai Minat Mahasiswa (Metode AHP + TOPSIS)</div>
            </td>
        </tr>
    </table>

    {{-- GRID IDENTITAS & INFORMASI PROSES --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 4px;">
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
                    </table>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 4px;">
                <div class="card-box" style="margin-bottom: 0;">
                    <div class="card-header-title">Informasi Pengisian &amp; SPK</div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">ID Riwayat</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">#{{ $selectedRun->id }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Tgl. Pengisian</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ \Carbon\Carbon::parse($selectedRun->created_at)->translatedFormat('d F Y, H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <td class="info-label">Metode SPK</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">AHP + TOPSIS</td>
                        </tr>
                        <tr>
                            <td class="info-label">Konfigurasi</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">5 Kriteria, 7 Alternatif UKM</td>
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
            <div class="hero-score-box">
                <span class="hero-score-val">{{ $fmtPercent($top1->nilai_preferensi) }}</span>
            </div>
            <div class="hero-score-label">Tingkat Prioritas Kesesuaian Preferensi</div>
            @if(!empty($top1->deskripsi))
                <div class="hero-desc">{{ Str::limit($top1->deskripsi, 160) }}</div>
            @endif
        </div>
    @endif

    {{-- TABEL HASIL PERANGKINGAN TOPSIS 7 UKM --}}
    <div class="section-title">Hasil Perangkingan TOPSIS Seluruh UKM</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">Peringkat</th>
                <th style="width: 60%;">Nama Unit Kegiatan Mahasiswa (UKM)</th>
                <th class="text-center" style="width: 30%;">Nilai Preferensi</th>
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
                        @if($res->ranking === 1)
                            <small style="color: #da2424; font-weight: normal; margin-left: 4px;">(Prioritas Utama)</small>
                        @endif
                    </td>
                    <td class="text-center font-bold text-red-primary" style="font-family: 'Courier New', Courier, monospace; font-size: 9pt;">
                        {{ $fmtPercent($res->nilai_preferensi) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- GRID BOBOT AHP & JAWABAN KUESIONER --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <td style="width: 44%; vertical-align: top; padding-right: 4px;">
                <div class="section-title" style="margin-top: 0;">Bobot Kriteria AHP</div>
                <table class="data-table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Kode</th>
                            <th style="width: 70%;">Nama Kriteria</th>
                            <th class="text-center" style="width: 30%;">Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ahpWeights as $w)
                            <tr>
                                <td class="font-bold text-red-primary">{{ $w->kode }}</td>
                                <td>{{ $w->nama_kriteria }}</td>
                                <td class="text-center font-bold">{{ number_format($w->bobot * 100, 2, ',', '.') }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td style="width: 56%; vertical-align: top; padding-left: 4px;">
                <div class="section-title" style="margin-top: 0;">Jawaban Kuesioner Mahasiswa</div>
                <table class="data-table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th style="width: 28%;">Kriteria</th>
                            <th style="width: 72%;">Pilihan Jawaban Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentAnswers as $ans)
                            <tr>
                                <td class="font-bold text-red-primary">{{ $ans->kode }} ({{ $ans->nama_kriteria }})</td>
                                <td>{{ $ans->nama_subkriteria }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    {{-- CATATAN AKADEMIK --}}
    <div class="catatan-box">
        <strong>Catatan Sistem:</strong> Hasil rekomendasi merupakan hasil pengolahan preferensi mahasiswa menggunakan metode AHP dan TOPSIS pada saat kuesioner diisi. Dokumen ini sah dan dihasilkan secara otomatis oleh Sistem Pendukung Keputusan Pemilihan UKM Universitas Muhammadiyah Cirebon.
    </div>

    {{-- FOOTER HALAMAN --}}
    <table class="footer-table">
        <tr>
            <td>SPK Pemilihan UKM &bull; Universitas Muhammadiyah Cirebon</td>
            <td class="text-right">Halaman 1 dari 1 &bull; Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

</body>
</html>
