<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsorship Certificate</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #333333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .certificate {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 40px;
            position: relative;
        }

        .border-outer {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 4px solid #2c5f2d;
            pointer-events: none;
        }

        .border-inner {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            bottom: 18px;
            border: 1px solid #4a7c4f;
            pointer-events: none;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .stable-logo {
            max-width: 100px;
            max-height: 100px;
            margin-bottom: 6px;
        }

        .stable-name {
            font-size: 11px;
            color: #555555;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-family: 'Arial', sans-serif;
        }

        .certificate-title {
            font-size: 36px;
            color: #2c5f2d;
            font-weight: bold;
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .certificate-subtitle {
            font-size: 13px;
            color: #777777;
            margin-top: 4px;
        }

        .decorative-line {
            width: 160px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
            margin: 12px auto;
        }

        .display-name {
            font-size: 30px;
            font-weight: bold;
            color: #2c5f2d;
        }

        .certificate-text {
            font-size: 14px;
            color: #444444;
            margin-top: 8px;
        }

        .horse-name {
            font-size: 24px;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
            margin-top: 8px;
        }

        .horse-photo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid #2c5f2d;
            margin-top: 10px;
            object-fit: cover;
        }

        .certificate-date {
            font-size: 11px;
            color: #666666;
            margin-top: 10px;
        }

        .signature-section {
            margin-top: 14px;
            text-align: center;
        }

        .hoof-img {
            width: 36px;
            height: 36px;
        }

        .signature-name {
            font-size: 14px;
            font-style: italic;
            color: #4a7c4f;
            margin-top: 4px;
        }

        .signature-label {
            font-size: 9px;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 2px;
            font-family: 'Arial', sans-serif;
        }

        .certificate-footer {
            font-size: 9px;
            color: #bbbbbb;
            margin-top: 14px;
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer"></div>
        <div class="border-inner"></div>

        <div class="certificate-header">
            @if($stableLogo)
                <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo"><br>
            @endif
            <span class="stable-name">{{ $stableName }}</span>
        </div>

        <div class="certificate-title">Certificate of Sponsorship</div>
        <div class="certificate-subtitle">This certificate is proudly presented to</div>

        <div class="decorative-line"></div>

        <div class="display-name">{{ $displayName }}</div>

        <div class="certificate-text">In recognition of their generous sponsorship of</div>

        <div class="horse-name">{{ $horseName }}</div>

        @if($horsePhoto)
            <img src="{{ public_path('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo">
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
