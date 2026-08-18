<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsor {{ $horseName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
            color: #333333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .poster {
            width: 100%;
            height: 100%;
            padding: 30px 40px 25px;
            text-align: center;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .border-outer {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 3px solid #2c5f2d;
            pointer-events: none;
        }

        .header {
            margin-bottom: 8px;
        }

        .stable-logo {
            max-width: 80px;
            max-height: 60px;
            margin-bottom: 4px;
        }

        .stable-name {
            font-size: 10px;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .title {
            font-size: 32px;
            color: #2c5f2d;
            margin: 8px 0 4px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 13px;
            color: #666666;
            margin-bottom: 8px;
        }

        .decorative-line {
            width: 180px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
            margin: 8px 0;
        }

        .horse-photo {
            width: 220px;
            height: 220px;
            border-radius: 10px;
            border: 3px solid #2c5f2d;
            margin: 10px 0;
            object-fit: cover;
        }

        .horse-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5f2d;
            margin: 6px 0;
        }

        .facts-section {
            margin: 6px 0;
            max-width: 380px;
            text-align: left;
        }

        .fact-item {
            font-size: 11px;
            color: #444444;
            padding: 2px 0 2px 16px;
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
            margin-top: auto;
            padding: 14px 20px;
            background: #f8faf8;
            border: 1px solid #e0e8e0;
            border-radius: 6px;
            width: 100%;
            max-width: 400px;
        }

        .sponsor-cta {
            font-size: 16px;
            font-weight: bold;
            color: #2c5f2d;
            margin-bottom: 4px;
        }

        .sponsor-text {
            font-size: 10px;
            color: #555555;
            margin-bottom: 8px;
        }

        .qr-code {
            margin: 4px auto;
        }

        .qr-code img {
            width: 120px;
            height: 120px;
        }

        .sponsor-url {
            font-size: 9px;
            color: #666666;
            margin-top: 4px;
            word-break: break-all;
        }

        .footer {
            margin-top: 10px;
            font-size: 8px;
            color: #aaaaaa;
        }
    </style>
</head>
<body>
    <div class="poster">
        <div class="border-outer"></div>

        <div class="header">
            @if($stableLogo)
                <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo"><br>
            @endif
            <span class="stable-name">{{ $stableName }}</span>
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
