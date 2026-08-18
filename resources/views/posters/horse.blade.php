<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsor {{ $horseName }}</title>
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

        .poster {
            width: 100%;
            height: 100%;
            padding: 40px 50px;
            text-align: center;
            position: relative;
        }

        .border-outer {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            bottom: 12px;
            border: 3px solid #2c5f2d;
            border-radius: 8px;
        }

        .border-inner {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            bottom: 18px;
            border: 1px solid #4a7c4f;
            border-radius: 6px;
        }

        .header {
            margin-bottom: 20px;
        }

        .stable-logo {
            max-width: 100px;
            max-height: 80px;
            margin-bottom: 8px;
        }

        .stable-name {
            font-size: 12px;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .title {
            font-size: 32px;
            color: #2c5f2d;
            margin: 15px 0 5px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 14px;
            color: #666666;
            margin-bottom: 20px;
        }

        .decorative-line {
            width: 200px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
            margin: 15px auto;
        }

        .horse-photo {
            width: 280px;
            height: 280px;
            border-radius: 12px;
            border: 4px solid #2c5f2d;
            margin: 15px auto;
            object-fit: cover;
            display: block;
        }

        .horse-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c5f2d;
            margin: 12px 0 8px;
        }

        .facts-section {
            margin: 10px auto;
            max-width: 400px;
            text-align: left;
        }

        .fact-item {
            font-size: 11px;
            color: #444444;
            padding: 3px 0;
            padding-left: 15px;
            position: relative;
        }

        .fact-item::before {
            content: "\2022";
            position: absolute;
            left: 0;
            color: #2c5f2d;
            font-weight: bold;
        }

        .sponsor-section {
            margin-top: 20px;
            padding: 20px;
            background: #f8faf8;
            border-radius: 8px;
            border: 1px solid #e0e8e0;
        }

        .sponsor-cta {
            font-size: 18px;
            font-weight: bold;
            color: #2c5f2d;
            margin-bottom: 12px;
        }

        .sponsor-text {
            font-size: 11px;
            color: #555555;
            margin-bottom: 15px;
        }

        .qr-code {
            margin: 10px auto;
            width: 150px;
            height: 150px;
        }

        .qr-code img {
            width: 150px;
            height: 150px;
        }

        .sponsor-url {
            font-size: 10px;
            color: #666666;
            margin-top: 10px;
            word-break: break-all;
        }

        .footer {
            position: absolute;
            bottom: 28px;
            left: 50px;
            right: 50px;
            font-size: 9px;
            color: #aaaaaa;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="poster">
        <div class="border-outer"></div>
        <div class="border-inner"></div>

        <div class="header">
            @if($stableLogo)
                <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo">
            @endif
            <div class="stable-name">{{ $stableName }}</div>
        </div>

        <h1 class="title">Sponsor Me!</h1>
        <div class="subtitle">Give {{ $horseName }} the love and care they deserve</div>

        <div class="decorative-line"></div>

        @if($horsePhoto)
            <img src="{{ public_path('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo">
        @endif

        <div class="horse-name">{{ $horseName }}</div>

        @if($horseFacts)
            @php
                $factLines = array_slice(array_filter(array_map('trim', explode("\n", $horseFacts))), 0, 4);
            @endphp
            @if(count($factLines) > 0)
                <div class="facts-section">
                    @foreach($factLines as $fact)
                        <div class="fact-item">{{ $fact }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="sponsor-section">
            <div class="sponsor-cta">Scan to Sponsor {{ $horseName }}</div>
            <div class="sponsor-text">Scan the QR code below or visit the link to set up your sponsorship</div>

            <div class="qr-code">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
            </div>

            <div class="sponsor-url">{{ $sponsorUrl }}</div>
        </div>

        <div class="footer">
            {{ $stableName }} &middot; Scan the QR code with your phone camera to sponsor {{ $horseName }}
        </div>
    </div>
</body>
</html>
