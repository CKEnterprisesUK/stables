<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sponsor a Horse</title>
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
            padding: 50px;
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
            margin-bottom: 30px;
        }

        .stable-logo {
            max-width: 120px;
            max-height: 100px;
            margin-bottom: 10px;
        }

        .stable-name {
            font-size: 14px;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .title {
            font-size: 38px;
            color: #2c5f2d;
            margin: 30px 0 10px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 16px;
            color: #555555;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .decorative-line {
            width: 250px;
            height: 2px;
            background: linear-gradient(to right, transparent, #2c5f2d, transparent);
            margin: 20px auto;
        }

        .horseshoe-icon {
            margin: 20px auto;
        }

        .info-section {
            margin: 25px auto;
            max-width: 420px;
            text-align: center;
        }

        .info-text {
            font-size: 13px;
            color: #444444;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .benefits-list {
            text-align: left;
            margin: 20px auto;
            max-width: 350px;
        }

        .benefit-item {
            font-size: 12px;
            color: #444444;
            padding: 5px 0;
            padding-left: 20px;
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
            margin-top: 30px;
            padding: 25px;
            background: #f8faf8;
            border-radius: 8px;
            border: 1px solid #e0e8e0;
        }

        .sponsor-cta {
            font-size: 20px;
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
            margin: 12px auto;
            width: 180px;
            height: 180px;
        }

        .qr-code img {
            width: 180px;
            height: 180px;
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

        <h1 class="title">Sponsor a Horse</h1>
        <div class="subtitle">Make a difference in the life of one of our horses</div>

        <div class="decorative-line"></div>

        <div class="horseshoe-icon">
            <svg width="60" height="60" viewBox="0 0 100 100" style="margin: 0 auto; display: block;">
                <path d="M20 90 C20 40, 20 20, 35 15 C40 12, 45 15, 45 25 L45 60 M55 60 L55 25 C55 15, 60 12, 65 15 C80 20, 80 40, 80 90"
                      fill="none" stroke="#2c5f2d" stroke-width="8" stroke-linecap="round"/>
                <circle cx="30" cy="55" r="5" fill="#2c5f2d"/>
                <circle cx="70" cy="55" r="5" fill="#2c5f2d"/>
                <circle cx="25" cy="38" r="5" fill="#2c5f2d"/>
                <circle cx="75" cy="38" r="5" fill="#2c5f2d"/>
            </svg>
        </div>

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
