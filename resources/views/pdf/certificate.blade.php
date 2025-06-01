<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Kelulusan - {{ $student->nama }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
            line-height: 1.4;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            position: relative;
        }

        .government-header {
            display: table;
            width: 100%;
        }

        .school-header {
            display: table;
            width: 100%;
        }

        .logo-left {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: left;
        }

        .logo-right {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: right;
        }

        .logo {
            width: 70px;
            height: 70px;
        }

        .logo-placeholder {
            width: 70px;
            height: 70px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            font-size: 8px;
            text-align: center;
            color: #666;
        }

        .government-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 20px;
        }

        .government-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: uppercase;
            color: #000;
        }

        .department-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: uppercase;
            color: #000;
        }

        .school-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 20px;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: uppercase;
            color: #000;
        }

        .school-address {
            font-size: 11px;
            margin-bottom: 3px;
            color: #333;
        }

        .school-contact {
            font-size: 10px;
            color: #666;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0;
            text-align: center;
            letter-spacing: 1px;
        }

        .document-number {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
            font-weight: bold;
        }

        .content {
            margin: 15px 0;
            text-align: justify;
            font-size: 12px;
        }

        .student-info {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #000;
            background-color: #f9f9f9;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info td {
            padding: 4px 6px;
            border-bottom: 1px dotted #999;
            font-size: 11px;
        }

        .student-info td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .graduation-statement {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 12px 0;
            padding: 10px;
            border: 1px solid #000;
            background-color: #e8f5e8;
        }

        .certificate-note {
            font-style: italic;
            margin: 10px 0;
            color: #555;
            font-size: 11px;
        }

        .compact-text {
            font-size: 11px;
            margin: 8px 0;
        }

        .signature-section {
            margin-top: 25px;
            display: table;
            width: 100%;
        }

        .photo-box {
            display: table-cell;
            width: 35%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .photo-frame {
            width: 90px;
            height: 120px;
            border: 2px solid #000;
            margin: 0 auto 8px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #666;
            text-align: center;
        }

        .signature-box {
            display: table-cell;
            width: 65%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 40px auto 8px;
            height: 1px;
            width: 180px;
        }

        .footer {
            margin-top: 15px;
            text-align: left;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .qr-footer {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .qr-left {
            display: table-cell;
            width: 70%;
            vertical-align: top;
            text-align: left;
        }

        .qr-right {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            text-align: center;
        }

        .qr-code {
            width: 60px;
            height: 60px;
            margin: 0 auto 5px;
        }

        .qr-code img {
            width: 100%;
            height: auto;
        }

        .qr-text {
            font-size: 7px;
            color: #666;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0, 0, 0, 0.02);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark">{{ strtoupper($schoolData['school_name']) }}</div>

    <!-- Header -->
    <div class="header">
        <!-- Government Header -->
        <div class="government-header">
            <div class="logo-left">
                @if ($schoolData['government_logo'] && file_exists(storage_path('app/public/' . $schoolData['government_logo'])))
                    @php
                        $logoPath = storage_path('app/public/' . $schoolData['government_logo']);
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoMime = mime_content_type($logoPath);
                    @endphp
                    <img src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Logo Pemerintah" class="logo">
                @else
                    <div class="logo-placeholder">
                        LOGO<br>PEMERINTAH
                    </div>
                @endif
            </div>

            <div class="government-info">
                <div class="government-name">{{ $schoolData['government_name'] }}</div>
                <div class="department-name">{{ $schoolData['department_name'] }}</div>
            </div>

            <div class="logo-right">
                @if ($schoolData['school_logo'] && file_exists(storage_path('app/public/' . $schoolData['school_logo'])))
                    @php
                        $logoPath = storage_path('app/public/' . $schoolData['school_logo']);
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoMime = mime_content_type($logoPath);
                    @endphp
                    <img src="data:{{ $logoMime }};base64,{{ $logoData }}" alt="Logo Sekolah" class="logo">
                @else
                    <div class="logo-placeholder">
                        LOGO<br>SEKOLAH
                    </div>
                @endif
            </div>
        </div>

        <!-- School Header -->
        <div class="school-header">
            <div style="display: table-cell; width: 100%; text-align: center; padding: 0 80px;">
                <div class="school-name">{{ $schoolData['school_name'] }}</div>
                <div class="school-address">{{ $schoolData['school_address'] }}</div>
                <div class="school-contact">
                    @if ($schoolData['school_phone'])
                        Telp: {{ $schoolData['school_phone'] }}
                    @endif
                    @if ($schoolData['school_email'])
                        @if ($schoolData['school_phone'])
                            |
                        @endif
                        Email: {{ $schoolData['school_email'] }}
                    @endif
                    @if ($schoolData['school_website'])
                        <br>Website: {{ $schoolData['school_website'] }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Document Title -->
    <div class="document-title">SURAT KETERANGAN KELULUSAN</div>

    <!-- Document Number -->
    <div class="document-number">
        Nomor:
        {{ $student->no_surat ?? 'SK/' . str_pad($student->id, 3, '0', STR_PAD_LEFT) . '/' . $schoolData['graduation_year'] }}
    </div>

    <!-- Content -->
    <div class="content">
        <p class="compact-text">Yang bertanda tangan di bawah ini, Kepala {{ $schoolData['school_name'] }}, dengan ini
            menerangkan bahwa:</p>

        <!-- Student Information -->
        <div class="student-info">
            <table>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>: {{ $student->nama }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>: {{ $student->nisn }}</td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>: {{ $student->nis }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>: Jakarta, {{ $student->tanggal_lahir->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>: {{ $student->kelas }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>: {{ $student->program_studi }}</td>
                </tr>
            </table>
        </div>

        <!-- Graduation Statement -->
        <div class="graduation-statement">
            Telah <strong>LULUS</strong> dari {{ $schoolData['school_name'] }}<br>
            pada Tahun Pelajaran {{ $schoolData['graduation_year'] - 1 }}/{{ $schoolData['graduation_year'] }}
        </div>

        @if ($student->pesan_khusus)
            <div class="certificate-note">
                <strong>Catatan:</strong> {{ $student->pesan_khusus }}
            </div>
        @endif

        <p class="compact-text">Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="photo-box">
            <div class="photo-frame">
                FOTO<br>
                3 x 4<br>
            </div>
        </div>

        <div class="signature-box">
            <p style="font-size: 11px; margin-bottom: 5px;">Jakarta, {{ now()->format('d F Y') }}<br>Kepala Sekolah</p>
            <div class="signature-line"></div>
            <p style="font-size: 11px; margin: 0;"><strong>{{ $schoolData['principal_name'] }}</strong><br>
                @if ($schoolData['principal_nip'])
                    NIP. {{ $schoolData['principal_nip'] }}
                @endif
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis pada {{ now()->format('d F Y, H:i:s') }}</p>

        <!-- QR Code Footer -->
        <div class="qr-footer">
            <div class="qr-left">
                <p style="font-size: 8px; margin: 0;">Untuk verifikasi keaslian dokumen, silakan scan QR Code di samping
                    atau hubungi {{ $schoolData['school_name'] }}</p>
            </div>
            <div class="qr-right">
                <div class="qr-code">
                    @if($qrCode)
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" style="width: 60px; height: 60px;">
                    @else
                        <div style="width: 60px; height: 60px; border: 2px solid #000; text-align: center; font-size: 7px; color: #000; background: #f8f9fa; padding: 8px;">
                            <div style="font-weight: bold; margin-bottom: 3px;">VERIFIKASI</div>
                            <div style="font-size: 6px; margin-bottom: 2px;">
                                ID: {{ strtoupper(substr(md5($verificationUrl), 0, 8)) }}
                            </div>
                            <div style="font-size: 5px; line-height: 1.2;">
                                Kunjungi website<br>sekolah untuk<br>verifikasi
                            </div>
                        </div>
                    @endif
                </div>
                <div class="qr-text">Scan untuk verifikasi</div>
            </div>
        </div>
    </div>
</body>

</html>