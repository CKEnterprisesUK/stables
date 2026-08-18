<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsorship Certificate</title>
    <style>
        @page {
            margin: 0;
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
            height: 100%;
            padding: 35px 50px 30px;
            text-align: center;
            position: relative;
        }

        .border-outer {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 3px solid #2c5f2d;
        }

        .border-inner {
            position: absolute;
            top: 22px;
            left: 22px;
            right: 22px;
            bottom: 22px;
            border: 1px solid #4a7c4f;
        }

        .certificate-header {
            margin-bottom: 15px;
        }

        .stable-logo {
            max-width: 80px;
            max-height: 80px;
            margin-bottom: 8px;
        }

        .stable-name {
            font-size: 11px;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .certificate-title {
            font-size: 28px;
            color: #2c5f2d;
            margin: 12px 0 4px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .certificate-subtitle {
            font-size: 12px;
            color: #777777;
            margin-bottom: 12px;
        }

        .decorative-line {
            width: 160px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
            margin: 10px auto;
        }

        .certificate-body {
            margin: 8px 0;
        }

        .display-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5f2d;
            margin: 10px 0;
        }

        .certificate-text {
            font-size: 13px;
            color: #444444;
        }

        .horse-section {
            margin: 10px 0;
        }

        .horse-name {
            font-size: 20px;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
            margin: 8px 0;
        }

        .horse-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid #2c5f2d;
            margin: 10px auto;
            object-fit: cover;
            display: block;
        }

        .certificate-date {
            font-size: 11px;
            color: #666666;
            margin-top: 12px;
        }

        .signature-section {
            margin-top: 15px;
            padding-top: 10px;
        }

        .horseshoe {
            font-size: 36px;
            color: #2c5f2d;
            margin-bottom: 2px;
            line-height: 1;
        }

        .signature-name {
            font-size: 13px;
            font-style: italic;
            color: #4a7c4f;
        }

        .signature-label {
            font-size: 9px;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .certificate-footer {
            position: absolute;
            bottom: 30px;
            left: 50px;
            right: 50px;
            font-size: 9px;
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
            <div class="horseshoe">&#x1F401;</div>
            <svg width="40" height="40" viewBox="0 0 100 100" style="margin: 0 auto; display: block;">
                <path d="M20 90 C20 40, 20 20, 35 15 C40 12, 45 15, 45 25 L45 60 M55 60 L55 25 C55 15, 60 12, 65 15 C80 20, 80 40, 80 90" 
                      fill="none" stroke="#2c5f2d" stroke-width="8" stroke-linecap="round"/>
                <circle cx="30" cy="55" r="5" fill="#2c5f2d"/>
                <circle cx="70" cy="55" r="5" fill="#2c5f2d"/>
                <circle cx="25" cy="38" r="5" fill="#2c5f2d"/>
                <circle cx="75" cy="38" r="5" fill="#2c5f2d"/>
            </svg>
            <div class="signature-name">{{ $horseName }}</div>
            <div class="signature-label">With love and gratitude</div>
        </div>

        <div class="certificate-footer">
            Issued by {{ $stableName }}
        </div>
    </div>
</body>
</html>
