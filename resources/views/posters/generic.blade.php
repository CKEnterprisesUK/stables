<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsor a Horse</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
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
            min-height: 100%;
            padding: 40px 45px 35px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .border-outer {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 3px solid #2c5f2d;
        }

        .header {
            margin-bottom: 15px;
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
            font-size: 36px;
            color: #2c5f2d;
            margin: 20px 0 8px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 14px;
            color: #555555;
            margin-bottom: 20px;
        }

        .decorative-line {
            width: 200px;
            height: 2px;
            background-color: #2c5f2d;
            margin: 15px auto;
        }

        .info-section {
            margin: 20px auto;
            max-width: 420px;
            text-align: center;
        }

        .info-text {
            font-size: 13px;
            color: #444444;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .benefits-list {
            text-align: left;
            margin: 15px auto;
            max-width: 340px;
        }

        .benefit-item {
            font-size: 12px;
            color: #444444;
            padding: 4px 0;
            padding-left: 22px;
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
            padding: 20px;
            background: #f8faf8;
            border: 1px solid #e0e8e0;
        }

        .sponsor-cta {
            font-size: 18px;
            font-weight: bold;
            color: #2c5f2d;
            margin-bottom: 8px;
        }

        .sponsor-text {
            font-size: 11px;
            color: #555555;
            margin-bottom: 12px;
        }

        .qr-code {
            margin: 8px auto;
            width: 160px;
            height: 160px;
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
            margin-top: 20px;
            font-size: 9px;
            color: #aaaaaa;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="poster">
        <div class="border-outer"></div>

        <div class="header">
            @if($stableLogo)
                <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo">
            @endif
            <div class="stable-name">{{ $stableName }}</div>
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
