<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gift Sponsorship Card</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 8mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333333;
            text-align: center;
            background: #fefefe;
        }

        .stable-logo {
            max-width: 22mm;
            max-height: 22mm;
            margin-top: 4mm;
        }

        .stable-name {
            font-size: 9pt;
            color: #555555;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 2mm;
        }

        .gift-title {
            font-size: 22pt;
            color: #8b4513;
            font-weight: bold;
            margin-top: 6mm;
        }

        .gift-subtitle {
            font-size: 10pt;
            color: #777777;
            margin-top: 2mm;
        }

        .decorative-line {
            width: 50mm;
            height: 1px;
            background-color: #8b4513;
            margin: 4mm auto;
        }

        .recipient-name {
            font-size: 16pt;
            font-weight: bold;
            color: #8b4513;
            margin-top: 2mm;
        }

        .gift-message {
            font-size: 10pt;
            color: #555555;
            font-style: italic;
            margin-top: 3mm;
            max-width: 180mm;
            margin-left: auto;
            margin-right: auto;
        }

        .horse-section {
            margin-top: 5mm;
        }

        .horse-name {
            font-size: 15pt;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
        }

        .horse-photo {
            width: 22mm;
            height: 22mm;
            border-radius: 50%;
            border: 2px solid #4a7c4f;
            margin: 3mm auto;
        }

        .duration-text {
            font-size: 11pt;
            color: #444444;
            margin-top: 3mm;
        }

        .code-section {
            margin-top: 6mm;
            padding: 4mm 8mm;
            display: inline-block;
            border: 2px dashed #8b4513;
            border-radius: 4mm;
        }

        .code-label {
            font-size: 8pt;
            color: #888888;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .code-value {
            font-size: 16pt;
            font-weight: bold;
            color: #8b4513;
            letter-spacing: 3px;
            margin-top: 1mm;
        }

        .redeem-info {
            font-size: 8pt;
            color: #666666;
            margin-top: 4mm;
        }

        .redeem-url {
            font-size: 8pt;
            color: #4a7c4f;
        }

        .expiry-text {
            font-size: 7pt;
            color: #999999;
            margin-top: 3mm;
        }

        .footer {
            font-size: 7pt;
            color: #bbbbbb;
            margin-top: 4mm;
        }

        .from-text {
            font-size: 9pt;
            color: #666666;
            margin-top: 3mm;
        }

        .qr-section {
            margin-top: 4mm;
        }

        .qr-code {
            width: 25mm;
            height: 25mm;
        }

        .qr-label {
            font-size: 7pt;
            color: #888888;
            margin-top: 1mm;
        }
    </style>
</head>
<body>
    @if($stableLogo)
        <img src="{{ public_path('storage/' . $stableLogo) }}" alt="{{ $stableName }}" class="stable-logo"><br>
    @endif
    <div class="stable-name">{{ $stableName }}</div>

    <h1 class="gift-title">Gift Sponsorship</h1>
    <div class="gift-subtitle">A special gift has been purchased</div>

    <div class="decorative-line"></div>

    @if($recipientName)
        <div class="gift-subtitle">For</div>
        <div class="recipient-name">{{ $recipientName }}</div>
    @endif

    @if($recipientMessage)
        <div class="gift-message">"{{ $recipientMessage }}"</div>
    @endif

    <div class="from-text">From {{ $purchaserName }}</div>

    <div class="horse-section">
        <div class="gift-subtitle">Sponsorship of</div>
        <div class="horse-name">{{ $horseName }}</div>

        @if($horsePhoto)
            <img src="{{ public_path('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo"><br>
        @endif
    </div>

    <div class="duration-text">{{ $months }} month{{ $months > 1 ? 's' : '' }} of sponsorship</div>

    <div class="code-section">
        <div class="code-label">Redemption Code</div>
        <div class="code-value">{{ $code }}</div>
    </div>

    <div class="qr-section">
        <img src="{{ $qrCodeDataUri }}" alt="Scan to redeem" class="qr-code">
        <div class="qr-label">Scan to redeem</div>
    </div>

    <div class="redeem-info">
        Or visit:<br>
        <span class="redeem-url">{{ $redeemUrl }}</span>
    </div>

    <div class="expiry-text">This gift code is valid until {{ $expiresAt }}</div>

    <div class="footer">Issued by {{ $stableName }}</div>
</body>
</html>
