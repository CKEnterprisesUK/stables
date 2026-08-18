<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsor {{ $horseName }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
            border: 3px solid #2c5f2d;
            padding: 5mm;
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
            text-align: center;
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
            font-size: 30px;
            color: #2c5f2d;
            margin: 10px 0 4px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 12px;
            color: #666666;
            margin-bottom: 10px;
        }

        .decorative-line {
            width: 180px;
            height: 2px;
            background-color: #2c5f2d;
            margin: 10px auto;
        }

        .horse-photo {
            width: 220px;
            height: 220px;
            border-radius: 10px;
            border: 3px solid #2c5f2d;
            margin: 10px auto;
            object-fit: cover;
            display: block;
        }

        .horse-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5f2d;
            margin: 8px 0 6px;
        }

        .facts-section {
            margin: 8px auto;
            max-width: 380px;
            text-align: left;
        }

        .fact-item {
            font-size: 10px;
            color: #444444;
            padding: 2px 0;
            padding-left: 14px;
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
            margin-top: 12px;
            padding: 15px;
            background: #f8faf8;
            border: 1px solid #e0e8e0;
        }

        .sponsor-cta {
            font-size: 16px;
            font-weight: bold;
            color: #2c5f2d;
            margin-bottom: 6px;
        }

        .sponsor-text {
            font-size: 10px;
            color: #555555;
            margin-bottom: 10px;
        }

        .qr-code {
            margin: 6px auto;
            width: 130px;
            height: 130px;
        }

        .qr-code img {
            width: 130px;
            height: 130px;
        }

        .sponsor-url {
            font-size: 9px;
            color: #666666;
            margin-top: 6px;
            word-break: break-all;
        }

        .footer {
            margin-top: 12px;
            font-size: 8px;
            color: #aaaaaa;
            text-align: center;
        }
    </style>
</head>
<body>
    @if($stableLogo)
        <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo"><br>
    @endif
    <div class="stable-name">{{ $stableName }}</div>

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
</body>
</html>
