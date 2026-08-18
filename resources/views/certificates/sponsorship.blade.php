<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsorship Certificate</title>
    <style>
        @page {
            margin: 0;
            size: 297mm 210mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #333333;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            padding: 20mm 25mm 15mm;
            text-align: center;
            position: relative;
        }

        .border-outer {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 2px solid #2c5f2d;
        }

        .border-inner {
            position: absolute;
            top: 11mm;
            left: 11mm;
            right: 11mm;
            bottom: 11mm;
            border: 1px solid #4a7c4f;
        }

        .certificate-header {
            margin-bottom: 4mm;
        }

        .stable-logo {
            max-width: 30mm;
            max-height: 30mm;
            margin-bottom: 2mm;
        }

        .stable-name {
            font-size: 10pt;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .certificate-title {
            font-size: 24pt;
            color: #2c5f2d;
            margin: 3mm 0 1mm;
            font-weight: bold;
        }

        .certificate-subtitle {
            font-size: 10pt;
            color: #777777;
            margin-bottom: 3mm;
        }

        .decorative-line {
            width: 50mm;
            height: 1px;
            background-color: #2c5f2d;
            margin: 3mm auto;
        }

        .display-name {
            font-size: 20pt;
            font-weight: bold;
            color: #2c5f2d;
            margin: 3mm 0;
        }

        .certificate-text {
            font-size: 10pt;
            color: #444444;
        }

        .horse-name {
            font-size: 16pt;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
            margin: 2mm 0;
        }

        .horse-photo {
            width: 22mm;
            height: 22mm;
            border-radius: 50%;
            border: 2px solid #2c5f2d;
            margin: 2mm auto;
            object-fit: cover;
            display: block;
        }

        .certificate-date {
            font-size: 9pt;
            color: #666666;
            margin-top: 3mm;
        }

        .signature-section {
            margin-top: 4mm;
        }

        .signature-name {
            font-size: 11pt;
            font-style: italic;
            color: #4a7c4f;
        }

        .signature-label {
            font-size: 7pt;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1mm;
        }

        .certificate-footer {
            position: absolute;
            bottom: 14mm;
            left: 25mm;
            right: 25mm;
            font-size: 7pt;
            color: #bbbbbb;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer"></div>
        <div class="border-inner"></div>

        <div class="certificate-header">
            @if($stableLogo)
                <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo">
            @endif
            <div class="stable-name">{{ $stableName }}</div>
        </div>

        <h1 class="certificate-title">Certificate of Sponsorship</h1>
        <div class="certificate-subtitle">This certificate is proudly presented to</div>

        <div class="decorative-line"></div>

        <div class="display-name">{{ $displayName }}</div>

        <p class="certificate-text">In recognition of their generous sponsorship of</p>

        <div class="horse-name">{{ $horseName }}</div>

        @if($horsePhoto)
            <img src="{{ public_path('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo">
        @endif

        <div class="decorative-line"></div>

        <div class="certificate-date">
            Sponsorship commenced on {{ $startDate }}
        </div>

        <div class="signature-section">
            <img src="{{ public_path('images/hoof-print.png') }}" alt="Hoof print" style="width: 14mm; height: auto; margin-bottom: 1mm;">
            <div class="signature-name">{{ $horseName }}</div>
            <div class="signature-label">With love and gratitude</div>
        </div>

        <div class="certificate-footer">
            Issued by {{ $stableName }}
        </div>
    </div>
</body>
</html>
