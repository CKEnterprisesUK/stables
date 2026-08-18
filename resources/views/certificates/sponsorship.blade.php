<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsorship Certificate</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
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
            margin: 0;
            padding: 0;
        }

        .certificate {
            width: 100%;
            padding: 30px 45px 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .border-outer {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            bottom: 12px;
            border: 3px solid #2c5f2d;
        }

        .border-inner {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            bottom: 18px;
            border: 1px solid #4a7c4f;
        }

        .certificate-header {
            margin-bottom: 10px;
        }

        .stable-logo {
            max-width: 140px;
            max-height: 140px;
            margin-bottom: 6px;
        }

        .stable-name {
            font-size: 11px;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .certificate-title {
            font-size: 26px;
            color: #2c5f2d;
            margin: 8px 0 3px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .certificate-subtitle {
            font-size: 11px;
            color: #777777;
            margin-bottom: 8px;
        }

        .decorative-line {
            width: 140px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
            margin: 8px auto;
        }

        .certificate-body {
            margin: 5px 0;
        }

        .display-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c5f2d;
            margin: 6px 0;
        }

        .certificate-text {
            font-size: 12px;
            color: #444444;
        }

        .horse-section {
            margin: 6px 0;
        }

        .horse-name {
            font-size: 18px;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
            margin: 5px 0;
        }

        .horse-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #2c5f2d;
            margin: 8px auto;
            object-fit: cover;
            display: block;
        }

        .certificate-date {
            font-size: 10px;
            color: #666666;
            margin-top: 8px;
        }

        .signature-section {
            margin-top: 10px;
        }

        .hoof-print {
            display: inline-block;
            margin: 0 auto 3px;
        }

        .signature-name {
            font-size: 12px;
            font-style: italic;
            color: #4a7c4f;
        }

        .signature-label {
            font-size: 8px;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .certificate-footer {
            position: absolute;
            bottom: 25px;
            left: 45px;
            right: 45px;
            font-size: 8px;
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

        <div class="certificate-body">
            <div class="display-name">{{ $displayName }}</div>

            <p class="certificate-text">
                In recognition of their generous sponsorship of
            </p>

            <div class="horse-section">
                <div class="horse-name">{{ $horseName }}</div>

                @if($horsePhoto)
                    <img src="{{ public_path('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo">
                @endif
            </div>

            <div class="decorative-line"></div>

            <div class="certificate-date">
                Sponsorship commenced on {{ $startDate }}
            </div>
        </div>

        <div class="signature-section">
            <div class="hoof-print">
                <svg width="36" height="44" viewBox="0 0 80 100">
                    <path d="M25 2 C10 2, 2 15, 2 30 C2 45, 10 52, 20 52 C25 52, 30 48, 32 42 C34 36, 36 34, 40 34 C44 34, 46 36, 48 42 C50 48, 55 52, 60 52 C70 52, 78 45, 78 30 C78 15, 70 2, 55 2 C48 2, 44 6, 40 12 C36 6, 32 2, 25 2 Z" fill="#2c5f2d"/>
                    <path d="M30 58 C25 58, 20 62, 20 70 C20 78, 25 85, 32 85 C38 85, 40 80, 40 75 C40 80, 42 85, 48 85 C55 85, 60 78, 60 70 C60 62, 55 58, 50 58 C46 58, 43 60, 40 64 C37 60, 34 58, 30 58 Z" fill="#2c5f2d"/>
                </svg>
            </div>
            <div class="signature-name">{{ $horseName }}</div>
            <div class="signature-label">With love and gratitude</div>
        </div>

        <div class="certificate-footer">
            Issued by {{ $stableName }}
        </div>
    </div>
</body>
</html>
