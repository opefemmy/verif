<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Pinyon+Script&display=swap');

        @page {
            size: 210mm 297mm;
            margin: 0;
        }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            -webkit-print-color-adjust: exact;
        }
        .page-break {
            page-break-after: always;
        }
        /* MASTER LOCK: Fixed A4 dimensions */
        .cert-page {
            width: 210mm;
            height: 297mm;
            position: relative;
            overflow: hidden;
        }
        /* GROUPED BORDERS */
        .border-outer {
            position: absolute;
            top: 10mm;
            left: 10mm;
            width: 190mm;
            height: 277mm;
            border: 8px solid #0f172a;
            background-color: #fff;
            box-sizing: border-box;
            text-align: center;
        }
        .border-middle {
            margin: 4mm auto;
            width: 182mm;
            height: 267mm;
            border: 2px solid #bfa100;
            background-color: #fff;
            box-sizing: border-box;
            text-align: center;
        }
        .border-inner {
            margin: 4mm auto;
            width: 174mm;
            height: 257mm;
            border: 6px double #0f172a;
            background-color: #fff;
            box-sizing: border-box;
            text-align: center;
            position: relative;
        }
        .corner {
            position: absolute;
            width: 12mm;
            height: 12mm;
            border: 4px solid #bfa100;
            z-index: 10;
        }
        .c-tl { top: -4px; left: -4px; border-right: none; border-bottom: none; }
        .c-tr { top: -4px; right: -4px; border-left: none; border-bottom: none; }
        .c-bl { bottom: -4px; left: -4px; border-right: none; border-top: none; }
        .c-br { bottom: -4px; right: -4px; border-left: none; border-top: none; }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
        }

        .logo { width: 80px; margin-bottom: 8px; }
        .inst-name {
            font-size: 18pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 1px;
        }
        .inst-loc {
            font-size: 12pt;
            color: #0f172a;
            margin: 2px 0;
            font-weight: bold;
        }
        .inst-motto {
            font-size: 11pt;
            font-style: italic;
            color: #64748b;
            margin: 3px 0 12px 0;
        }
        .gold-divider {
            border-top: 2px solid #bfa100;
            width: 50%;
            margin: 12px auto;
        }
        .title-main {
            font-size: 30pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            margin: 15px 0 0 0;
        }
        .title-sub {
            font-size: 15pt;
            color: #bfa100;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 15px 0;
        }
        .certify {
            font-size: 13pt;
            color: #0f172a;
            margin: 15px 0 8px 0;
            text-transform: uppercase;
            font-style: italic;
        }
        .name {
            font-size: 48pt;
            font-family: 'Pinyon Script', cursive;
            color: #0f172a;
            margin: 15px 0;
            display: inline-block;
            border-bottom: 2px solid #bfa100;
            padding-bottom: 2px;
            min-width: 60%;
        }
        .details {
            font-size: 13pt;
            color: #334155;
            line-height: 1.4;
            margin: 15px 0;
        }
        .highlight {
            font-weight: bold;
            color: #0f172a;
        }
        .date-line {
            font-size: 12pt;
            margin: 15px 0;
            color: #0f172a;
        }
        .blank {
            border-bottom: 1px solid #0f172a;
            display: inline-block;
            min-width: 35px;
            text-align: center;
            padding: 0 5px;
        }
        .footer-table {
            width: 100%;
            margin-top: 25px;
            border: none;
            border-collapse: collapse;
        }
        .sig-box {
            width: 35%;
            text-align: center;
        }
        .sig-line {
            border-top: 2px solid #0f172a;
            width: 80%;
            margin: 0 auto 5px auto;
        }
        .sig-label {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .pdf-seal {
            width: 30mm;
            height: 30mm;
            border-radius: 50%;
            object-fit: cover;
            vertical-align: middle;
        }
        .passport-container {
            position: absolute;
            width: 30mm;
            height: 35mm;
            border: 2px solid #bfa100;
            background-color: #fff;
            overflow: hidden;
            box-sizing: border-box;
            z-index: 10;
        }
        .passport-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .bottom-meta-table {
            width: 100%;
            margin-top: 15px;
            border: none;
            border-collapse: collapse;
        }
        .cert-no-cell {
            width: 50%;
            text-align: left;
            font-size: 10pt;
            color: #0f172a;
            font-weight: bold;
        }
        .qr-cell {
            width: 50%;
            text-align: right;
        }
        .qr-img {
            width: 70px;
            height: 70px;
        }
    </style>
</head>
<body>
    @foreach($certificates as $certificate)
    <div class="page-break">
        <div class="cert-page">
            <div class="border-outer">
                <div class="corner c-tl"></div>
                <div class="corner c-tr"></div>
                <div class="corner c-bl"></div>
                <div class="corner c-br"></div>

                <div class="border-middle">
                    <div class="border-inner">
                    @if($settings && $settings->logo)
                        <img src="{{ public_path('storage/' . $settings->logo) }}" class="watermark">
                    @endif

                        @if($certificate->passport_photo)
                            <div class="passport-container" style="top: 45mm; right: {{ $settings->passport_x ?? 10 }}mm;">
                                <img src="{{ public_path('storage/' . $certificate->passport_photo) }}" class="passport-photo">
                            </div>
                        @endif

                        <div style="text-align: center;">
                            @if($settings && $settings->logo)
                                <img src="{{ public_path('storage/' . $settings->logo) }}" class="logo">
                            @endif
                            <div class="inst-name">{{ $settings->institution_name ?? 'Institution Name' }},</div>
                            <div class="inst-loc" style="font-size: 12pt; color: #0f172a; margin: 2px 0; font-weight: bold;">
                                {{ $settings->city ? ($settings->city . ' - ' . $settings->state) : ($settings->location ?? 'Location') }}
                            </div>
                            <div class="inst-motto">{{ $settings->motto ?? 'Motto' }}</div>

                            <div class="gold-divider"></div>

                            <div class="title-main">Certificate</div>
                            <div class="title-sub">Of Graduation</div>

                            <div class="certify">This is to certify that</div>
                            <div class="name">{{ $certificate->full_name }}</div>

                            <div class="details">
                                has successfully completed the requirements for the award of <br>
                                <span class="highlight">{{ $certificate->programme }}</span> <br>
                                in the Department of <span class="highlight">{{ $certificate->department ?? $certificate->faculty ?? 'N/A' }}</span> <br>
                                at <span class="highlight">{{ $settings->institution_name ?? 'Our Institution' }}</span>.
                            </div>

                            <div class="details" style="font-style: italic; font-size: 12pt;">
                                and is hereby awarded this Certificate of Graduation.
                            </div>

                            <div class="date-line">
                                Given this <span class="blank">{{ \Carbon\Carbon::parse($certificate->graduation_date)->format('d') }}</span>
                                day of <span class="blank">{{ \Carbon\Carbon::parse($certificate->graduation_date)->format('F') }}</span>,
                                20<span class="blank">{{ \Carbon\Carbon::parse($certificate->graduation_date)->format('y') }}</span>.
                            </div>

                            <table class="footer-table">
                                <tr style="vertical-align: middle;">
                                    <td class="sig-box">
                                        <div style="height: 20mm; position: relative; text-align: center;">
                                            @if($settings && $settings->registrar_signature)
                                                <img src="{{ public_path('storage/' . $settings->registrar_signature) }}" style="max-height: 20mm; max-width: 40mm; position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%);">
                                            @endif
                                        </div>
                                        <div class="sig-line"></div>
                                        <div class="sig-label">Registrar</div>
                                    </td>
                                    <td style="width: 30%; text-align: center; vertical-align: middle;">
                                        @if($settings && $settings->seal)
                                            @php
                                                $sealPath = public_path('storage/' . $settings->seal);
                                            @endphp
                                            <div style="width: 30mm; height: 30mm; border-radius: 50%; overflow: hidden; margin: 0 auto; display: block;">
                                                <img src="{{ $sealPath }}" class="pdf-seal">
                                            </div>
                                        @else
                                            <div style="font-size: 8pt; color: #ccc;">No Seal</div>
                                        @endif
                                    </td>
                                    <td class="sig-box">
                                        <div style="height: 20mm; position: relative; text-align: center;">
                                            @if($settings && $settings->rector_signature)
                                                <img src="{{ public_path('storage/' . $settings->rector_signature) }}" style="max-height: 20mm; max-width: 40mm; position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%);">
                                            @endif
                                        </div>
                                        <div class="sig-line"></div>
                                        <div class="sig-label">Rector</div>
                                    </td>
                                </tr>
                            </table>

                            <table class="bottom-meta-table">
                                <tr>
                                    <td class="cert-no-cell">
                                        Certificate No: {{ $certificate->certificate_number }}
                                    </td>
                                    <td class="qr-cell">
                                        @php
                                            $sanitized = preg_replace('/[^A-Za-z0-9]/', '_', $certificate->matric_number);
                                            $qrPath = public_path('storage/qr-codes/' . $sanitized . '.png');
                                        @endphp
                                        @if(file_exists($qrPath))
                                            <img src="{{ $qrPath }}" class="qr-img">
                                        @else
                                            <img src="{{ route('admin.certificates.download-qr', $certificate->id) }}" class="qr-img">
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>