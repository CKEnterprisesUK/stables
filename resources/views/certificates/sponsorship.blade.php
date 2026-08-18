<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsorship Certificate</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333333;
            width: 297mm;
            height: 210mm;
        }

        .certificate {
            width: 297mm;
            height: 210mm;
            border: 3px solid #2c5f2d;
            padding: 12mm 20mm 10mm;
            text-align: center;
            position: relative;
        }

        .inner-border {
            position: absolute;
            top: 4mm;
            left: 4mm;
            right: 4mm;
            bottom: 4mm;
            border: 1px solid #4a7c4f;
        }

        .stable-logo {
            max-width: 22mm;
            max-height: 22mm;
        }

        .stable-name {
            font-size: 9pt;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        .certificate-title {
            font-size: 22pt;
            color: #2c5f2d;
            font-weight: bold;
            margin-top: 3mm;
        }

        .certificate-subtitle {
            font-size: 9pt;
            color: #777777;
            margin-top: 1mm;
        }

        .decorative-line {
            width: 40mm;
            height: 1px;
            background-color: #2c5f2d;
            margin: 3mm auto;
        }

        .display-name {
            font-size: 18pt;
            font-weight: bold;
            color: #2c5f2d;
        }

        .certificate-text {
            font-size: 9pt;
            color: #444444;
            margin-top: 2mm;
        }

        .horse-name {
            font-size: 15pt;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
            margin-top: 2mm;
        }

        .horse-photo {
            width: 18mm;
            height: 18mm;
            border-radius: 50%;
            border: 2px solid #2c5f2d;
            margin: 3mm auto;
        }

        .certificate-date {
            font-size: 8pt;
            color: #666666;
            margin-top: 2mm;
        }

        .signature-section {
            margin-top: 3mm;
        }

        .hoof-img {
            width: 8mm;
            height: 8mm;
        }

        .signature-name {
            font-size: 10pt;
            font-style: italic;
            color: #4a7c4f;
            margin-top: 1mm;
        }

        .signature-label {
            font-size: 7pt;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .certificate-footer {
            font-size: 7pt;
            color: #bbbbbb;
            margin-top: 3mm;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="inner-border"></div>

        @if($stableLogo)
            <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo"><br>
        @endif
        <span class="stable-name">{{ $stableName }}</span>

        <h1 class="certificate-title">Certificate of Sponsorship</h1>
        <div class="certificate-subtitle">This certificate is proudly presented to</div>

        <div class="decorative-line"></div>

        <div class="display-name">{{ $displayName }}</div>

        <div class="certificate-text">In recognition of their generous sponsorship of</div>

        <div class="horse-name">{{ $horseName }}</div>

        @if($horsePhoto)
            <img src="{{ public_path('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo"><br>
        @endif

        <div class="decorative-line"></div>

        <div class="certificate-date">Sponsorship commenced on {{ $startDate }}</div>

        <div class="signature-section">
            <img src="{{ public_path('images/hoof-print.png') }}" alt="Hoof print" class="hoof-img"><br>
            <span class="signature-name">{{ $horseName }}</span><br>
            <span class="signature-label">With love and gratitude</span>
        </div>

        <div class="certificate-footer">Issued by {{ $stableName }}</div>
    </div>
</body>
</html>
