<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sponsor {{ $horseName }} - Poster</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f3f4f6;
            color: #333333;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }

        .print-btn {
            padding: 10px 20px;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .print-btn:hover {
            background: #1e4620;
        }

        .back-btn {
            padding: 10px 20px;
            background: white;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #f9f9f9;
        }

        .poster {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 30px 40px 25px;
            text-align: center;
            position: relative;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
            margin: 10px 0 4px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 13px;
            color: #666666;
            margin-bottom: 10px;
        }

        .decorative-line {
            width: 180px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
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
            margin-top: 16px;
            padding: 16px 20px;
            background: #f8faf8;
            border: 1px solid #e0e8e0;
            border-radius: 6px;
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
            margin-bottom: 8px;
        }

        .qr-code {
            margin: 6px auto;
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
        }

        @media print {
            body {
                background: white;
            }

            .print-controls {
                display: none !important;
            }

            .poster {
                margin: 0;
                box-shadow: none;
                width: 100%;
                min-height: 100%;
            }
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="print-controls">
        <a href="{{ url()->previous() }}" class="back-btn">Back</a>
        <button onclick="window.print()" class="print-btn">Print / Save as PDF</button>
    </div>

    <div class="poster">
        <div class="border-outer"></div>

        <div class="header">
            @if($stableLogo)
                <img src="{{ asset('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo"><br>
            @endif
            <span class="stable-name">{{ $stableName }}</span>
        </div>

        <h1 class="title">Sponsor Me!</h1>
        <div class="subtitle">Give {{ $horseName }} the love and care they deserve</div>

        <div class="decorative-line"></div>

        @if($horsePhoto)
            <img src="{{ asset('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo">
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
