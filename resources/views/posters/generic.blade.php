<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sponsor a Horse - Poster</title>
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
            padding: 35px 45px 30px;
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
            margin-bottom: 12px;
        }

        .stable-logo {
            max-width: 100px;
            max-height: 70px;
            margin-bottom: 6px;
        }

        .stable-name {
            font-size: 11px;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .title {
            font-size: 38px;
            color: #2c5f2d;
            margin: 20px 0 6px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
            color: #555555;
            margin-bottom: 16px;
        }

        .decorative-line {
            width: 200px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
            margin: 14px auto;
        }

        .info-section {
            margin: 16px auto;
            max-width: 420px;
        }

        .info-text {
            font-size: 13px;
            color: #444444;
            line-height: 1.6;
            margin-bottom: 14px;
        }

        .benefits-list {
            text-align: left;
            margin: 0 auto;
            max-width: 340px;
        }

        .benefit-item {
            font-size: 13px;
            color: #444444;
            padding: 5px 0 5px 24px;
            position: relative;
        }

        .benefit-item::before {
            content: "\2714";
            position: absolute;
            left: 0;
            color: #2c5f2d;
            font-weight: bold;
        }

        .sponsor-section {
            margin-top: 20px;
            padding: 20px 24px;
            background: #f8faf8;
            border: 1px solid #e0e8e0;
            border-radius: 6px;
        }

        .sponsor-cta {
            font-size: 18px;
            font-weight: bold;
            color: #2c5f2d;
            margin-bottom: 6px;
        }

        .sponsor-text {
            font-size: 11px;
            color: #555555;
            margin-bottom: 10px;
        }

        .qr-code {
            margin: 8px auto;
        }

        .qr-code img {
            width: 160px;
            height: 160px;
        }

        .sponsor-url {
            font-size: 10px;
            color: #666666;
            margin-top: 8px;
            word-break: break-all;
        }

        .footer {
            margin-top: 16px;
            font-size: 9px;
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

        <h1 class="title">Sponsor a Horse</h1>
        <div class="subtitle">Make a difference in the life of one of our horses</div>

        <div class="decorative-line"></div>

        <div class="info-section">
            <p class="info-text">
                Your sponsorship helps provide food, shelter, veterinary care, and love for our horses. Every contribution makes a real difference.
            </p>

            <div class="benefits-list">
                <div class="benefit-item">Regular updates on your sponsored horse</div>
                <div class="benefit-item">A personalised sponsorship certificate</div>
                <div class="benefit-item">Know you are making a real difference</div>
                <div class="benefit-item">A wonderful gift for horse lovers</div>
            </div>
        </div>

        <div class="decorative-line"></div>

        <div class="sponsor-section">
            <div class="sponsor-cta">Scan to Find Out More</div>
            <div class="sponsor-text">Scan the QR code below or visit the link to learn about sponsorship</div>

            <div class="qr-code">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
            </div>

            <div class="sponsor-url">{{ $sponsorUrl }}</div>
        </div>

        <div class="footer">
            {{ $stableName }} &middot; Scan the QR code with your phone camera to find out more
        </div>
    </div>
</body>
</html>
