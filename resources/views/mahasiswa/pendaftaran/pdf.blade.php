<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pendaftaran UKM - {{ $pendaftaran->nomor_pendaftaran }}</title>
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

        /* BOX GENERAL & CARD */
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

        /* GRID / TABLE UTILITY */
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

        /* HERO / SUMMARY SPK & UKM CARD */
        .summary-card {
            background-color: #fef2f2;
            border: 1.2px solid #da2424;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 6px;
        }

        .ukm-title {
            font-size: 11pt;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-menunggu { background-color: #fff5e5; color: #b76e00; border: 1px solid #ffe2b3; }
        .badge-verifikasi { background-color: #eaf8f2; color: #258260; border: 1px solid #c9eadc; }
        .badge-perbaikan { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .badge-ditolak { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .badge-selesai { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

        .topsis-score-box {
            background-color: #ffffff;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 4px 8px;
            text-align: center;
        }

        .topsis-val {
            font-size: 11pt;
            font-weight: bold;
            color: #da2424;
            font-family: 'Courier New', Courier, monospace;
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
            vertical-align: top;
        }

        .data-table tr:nth-child(even) {
            background-color: #fdf2f2;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-red-primary { color: #8B0000; }

        /* CATATAN VERIFIKASI & ADMIN */
        .catatan-box {
            background-color: #fffbeb;
            border-left: 3px solid #d97706;
            padding: 5px 8px;
            font-size: 7.5pt;
            color: #92400e;
            margin-top: 5px;
            margin-bottom: 6px;
            border-radius: 0 4px 4px 0;
        }

        .catatan-box.catatan-info {
            background-color: #fef2f2;
            border-left: 3px solid #da2424;
            color: #770000;
        }

        /* SIGNATURE SECTION */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .signature-table td {
            font-size: 8pt;
            vertical-align: top;
        }

        .signature-title {
            color: #555555;
            margin-bottom: 35px;
        }

        .signature-name {
            font-weight: bold;
            color: #1f2937;
            text-decoration: underline;
        }

        /* FOOTER */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            border-top: 1px solid #fecaca;
            padding-top: 3px;
        }

        .footer-table td {
            font-size: 7pt;
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

        $statusBadgeMap = [
            'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => 'badge-menunggu'],
            'terverifikasi'       => ['label' => 'Terverifikasi',       'class' => 'badge-verifikasi'],
            'diterima'            => ['label' => 'Diterima Sebagai Anggota', 'class' => 'badge-verifikasi'],
            'perlu_perbaikan'     => ['label' => 'Perlu Perbaikan Data', 'class' => 'badge-perbaikan'],
            'ditolak'             => ['label' => 'Ditolak',             'class' => 'badge-ditolak'],
            'selesai'             => ['label' => 'Proses Selesai',      'class' => 'badge-selesai'],
        ];

        $currentStatus = $pendaftaran->status ?? 'menunggu_verifikasi';
        $statusInfo = $statusBadgeMap[$currentStatus] ?? [
            'label' => ucwords(str_replace('_', ' ', $currentStatus)),
            'class' => 'badge-menunggu'
        ];

        // Cari catatan admin terakhir dari riwayat status
        $latestAdminNote = '';
        if (isset($historyLogs) && $historyLogs->isNotEmpty()) {
            foreach ($historyLogs->reverse() as $log) {
                if (!empty($log->catatan) && str_contains(strtolower($log->catatan), 'admin')) {
                    $latestAdminNote = $log->catatan;
                    break;
                }
            }
            if (empty($latestAdminNote)) {
                $lastLog = $historyLogs->last();
                if (!empty($lastLog->catatan)) {
                    $latestAdminNote = $lastLog->catatan;
                }
            }
        }
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
                <div class="doc-title">DETAIL PENDAFTARAN UKM</div>
                <div class="doc-subtitle">Sistem Pendukung Keputusan Pemilihan UKM Sesuai Minat Mahasiswa (Metode AHP + TOPSIS)</div>
            </td>
        </tr>
    </table>

    {{-- GRID IDENTITAS & INFORMASI PENDAFTARAN --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 3px;">
                <div class="card-box" style="margin-bottom: 0;">
                    <div class="card-header-title">Identitas Pendaftar</div>
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
                            <td class="info-label">Email</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $user->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">No. HP / WA</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Program Studi</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->prodi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Fakultas / Angk.</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->fakultas ?? '-' }} / {{ $mahasiswa->angkatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Jenis Kelamin</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $mahasiswa->jenis_kelamin ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 3px;">
                <div class="card-box" style="margin-bottom: 0;">
                    <div class="card-header-title">Informasi Pendaftaran</div>
                    <table class="info-table">
                        <tr>
                            <td class="info-label">No. Pendaftaran</td>
                            <td class="info-colon">:</td>
                            <td class="info-value font-bold text-red-primary" style="font-family: 'Courier New', Courier, monospace;">
                                {{ $pendaftaran->nomor_pendaftaran }}
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Tgl. Pendaftaran</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">
                                {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y, H:i') }} WIB
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Status Saat Ini</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">
                                <span class="badge-status {{ $statusInfo['class'] }}">
                                    {{ $statusInfo['label'] }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">UKM Tujuan</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $pendaftaran->nama_ukm }}</td>
                        </tr>
                        @if(!empty($pendaftaran->ukm_pembina))
                        <tr>
                            <td class="info-label">Pembina UKM</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $pendaftaran->ukm_pembina }}</td>
                        </tr>
                        @endif
                        @if(!empty($pendaftaran->ukm_hari_latihan))
                        <tr>
                            <td class="info-label">Hari Latihan</td>
                            <td class="info-colon">:</td>
                            <td class="info-value">{{ $pendaftaran->ukm_hari_latihan }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- SUMMARY SPK REKOMENDASI CARD (JIKA ADA RELASI TOPSIS) --}}
    <div class="summary-card">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle;">
                    <div style="font-size: 7.5pt; font-weight: bold; color: #da2424; text-transform: uppercase; letter-spacing: 0.5px;">
                        Unit Kegiatan Mahasiswa Yang Dipilih:
                    </div>
                    <div class="ukm-title">{{ $pendaftaran->nama_ukm }}</div>
                    @if(!empty($pendaftaran->ukm_deskripsi))
                        <div style="font-size: 7.5pt; color: #555555; line-height: 1.2;">
                            {{ Str::limit($pendaftaran->ukm_deskripsi, 180) }}
                        </div>
                    @endif
                </td>
                @if($pendaftaran->topsis_ranking)
                    <td style="width: 32%; vertical-align: middle; text-align: center; padding-left: 8px;">
                        <div class="topsis-score-box">
                            <div style="font-size: 7pt; font-weight: bold; color: #666666; text-transform: uppercase;">
                                Rekomendasi TOPSIS
                            </div>
                            <div style="font-size: 9pt; font-weight: bold; color: #1f2937; margin: 1px 0;">
                                Peringkat #{{ $pendaftaran->topsis_ranking }}
                            </div>
                            <div class="topsis-val">
                                {{ $fmtPercent($pendaftaran->topsis_score) }}
                            </div>
                            <div style="font-size: 6.5pt; color: #777777;">Nilai Preferensi</div>
                        </div>
                    </td>
                @else
                    <td style="width: 28%; vertical-align: middle; text-align: center; padding-left: 8px;">
                        <div class="topsis-score-box">
                            <div style="font-size: 7.5pt; font-weight: bold; color: #666666;">
                                Jalur Pendaftaran
                            </div>
                            <div style="font-size: 8pt; font-weight: bold; color: #da2424; margin-top: 2px;">
                                Pendaftaran Langsung
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        </table>
    </div>

    {{-- FORMULIR PENDAFTARAN MAHASISWA --}}
    <div class="section-title">Isian Formulir Pendaftaran UKM</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 32%;">Pertanyaan / Field Formulir</th>
                <th style="width: 68%;">Jawaban / Isian Mahasiswa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold text-red-primary">Alasan Memilih UKM</td>
                <td>{{ $pendaftaran->alasan_mendaftar ?: '-' }}</td>
            </tr>
            <tr>
                <td class="font-bold text-red-primary">Motivasi &amp; Minat Bergabung</td>
                <td>{{ $pendaftaran->ketertarikan ?: '-' }}</td>
            </tr>
            <tr>
                <td class="font-bold text-red-primary">Pengalaman Kegiatan</td>
                <td>{{ $pendaftaran->pengalaman ?: '-' }}</td>
            </tr>
            @if(!empty($pendaftaran->detail_pengalaman))
            <tr>
                <td class="font-bold text-red-primary">Detail Pengalaman / Portofolio</td>
                <td>{{ $pendaftaran->detail_pengalaman }}</td>
            </tr>
            @endif
            <tr>
                <td class="font-bold text-red-primary">Keahlian &amp; Minat Khusus</td>
                <td>{{ $pendaftaran->keterampilan ?: '-' }}</td>
            </tr>
            <tr>
                <td class="font-bold text-red-primary">Kesiapan Latihan Rutin</td>
                <td>{{ $pendaftaran->kesediaan_mengikuti_kegiatan ?: '-' }}</td>
            </tr>
            <tr>
                <td class="font-bold text-red-primary">Kesesuaian Jadwal</td>
                <td>{{ $pendaftaran->kesesuaian_jadwal ?: 'Siap menyesuaikan' }}</td>
            </tr>
            <tr>
                <td class="font-bold text-red-primary">Target Kontribusi</td>
                <td>{{ $pendaftaran->kontribusi ?: '-' }}</td>
            </tr>
            <tr>
                <td class="font-bold text-red-primary">Ketersediaan Menaati Aturan</td>
                <td>{{ $pendaftaran->kesediaan_menaati_aturan ?: 'Ya' }}</td>
            </tr>
            <tr>
                <td class="font-bold text-red-primary">Tujuan Utama / Komitmen</td>
                <td>{{ $pendaftaran->komitmen_aktif ?: '-' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- SECTION CATATAN ADMIN (JIKA PERLU PERBAIKAN ATAU ADA CATATAN VERIFIKASI) --}}
    @if($currentStatus === 'perlu_perbaikan' && !empty($latestAdminNote))
        <div class="catatan-box">
            <strong style="text-transform: uppercase;">Catatan Verifikasi Admin (Perlu Perbaikan):</strong><br>
            {{ $latestAdminNote }}
        </div>
    @elseif(!empty($latestAdminNote))
        <div class="catatan-box catatan-info">
            <strong>Catatan Verifikasi Admin:</strong> {{ $latestAdminNote }}
        </div>
    @endif

    {{-- RIWAYAT STATUS VERIFIKASI ADMIN --}}
    @if(isset($historyLogs) && $historyLogs->isNotEmpty())
        <div class="section-title">Riwayat Verifikasi Pendaftaran</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Waktu Perubahan</th>
                    <th style="width: 25%;">Status</th>
                    <th style="width: 20%;">Pemeriksa</th>
                    <th style="width: 30%;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historyLogs as $log)
                    @php
                        $stLog = $statusBadgeMap[$log->status] ?? [
                            'label' => ucwords(str_replace('_', ' ', $log->status)),
                            'class' => ''
                        ];
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y, H:i') }} WIB</td>
                        <td class="font-bold text-red-primary">{{ $stLog['label'] }}</td>
                        <td>{{ $log->admin_name ?? 'Sistem/Admin' }}</td>
                        <td>{{ $log->catatan ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- AREA TANDA TANGAN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 60%;">
                <div class="catatan-box catatan-info" style="margin: 0; padding: 4px 6px;">
                    <strong>Pernyataan:</strong> Data ini benar-benar diisi oleh mahasiswa bersangkutan dan sah untuk keanggotaan UKM Universitas Muhammadiyah Cirebon.
                </div>
            </td>
            <td style="width: 40%; text-align: center; padding-left: 20px;">
                <div class="signature-title">
                    Cirebon, {{ \Carbon\Carbon::parse($pendaftaran->tanggal_daftar)->translatedFormat('d F Y') }}<br>
                    Mahasiswa Pendaftar,
                </div>
                <div class="signature-name">
                    {{ $mahasiswa->nama }}
                </div>
                <div style="font-size: 7.5pt; color: #666666; margin-top: 1px;">
                    NIM. {{ $mahasiswa->nim }}
                </div>
            </td>
        </tr>
    </table>

    {{-- FOOTER HALAMAN --}}
    <table class="footer-table">
        <tr>
            <td>SPK Pemilihan UKM &bull; Universitas Muhammadiyah Cirebon</td>
            <td class="text-right">Halaman 1 dari 1 &bull; Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

</body>
</html>
