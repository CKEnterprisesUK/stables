<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsorship Certificate</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 8mm;
            border: 3px solid #2c5f2d;
            padding: 4mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333333;
            text-align: center;
        }

        .stable-logo {
            max-width: 28mm;
            max-height: 28mm;
            margin-top: 2mm;
        }

        .stable-name {
            font-size: 9pt;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        .certificate-title {
            font-size: 24pt;
            color: #2c5f2d;
            font-weight: bold;
            margin-top: 4mm;
        }

        .certificate-subtitle {
            font-size: 10pt;
            color: #777777;
            margin-top: 2mm;
        }

        .decorative-line {
            width: 50mm;
            height: 2px;
            background-color: #2c5f2d;
            margin: 3mm auto;
        }

        .display-name {
            font-size: 20pt;
            font-weight: bold;
            color: #2c5f2d;
        }

        .certificate-text {
            font-size: 10pt;
            color: #444444;
            margin-top: 2mm;
        }

        .horse-name {
            font-size: 16pt;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
            margin-top: 2mm;
        }

        .horse-photo {
            width: 22mm;
            height: 22mm;
            border-radius: 50%;
            border: 2px solid #2c5f2d;
            margin: 3mm auto;
        }

        .certificate-date {
            font-size: 9pt;
            color: #666666;
            margin-top: 3mm;
        }

        .signature-section {
            margin-top: 4mm;
        }

        .hoof-img {
            width: 10mm;
            height: 10mm;
        }

        .signature-name {
            font-size: 11pt;
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
            margin-top: 4mm;
        }
    </style>
</head>
<body>
    @if($stableLogo)
        <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo"><br>
    @endif
    <div class="stable-name">{{ $stableName }}</div>

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
</body>
</html>
