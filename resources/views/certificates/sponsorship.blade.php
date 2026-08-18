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

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #333333;
        }

        .certificate {
            width: 100%;
            padding: 60px 50px;
            text-align: center;
            border: 8px double #2c5f2d;
            min-height: 700px;
            position: relative;
        }

        .certificate-header {
            margin-bottom: 40px;
        }

        .stable-logo {
            max-width: 120px;
            max-height: 120px;
            margin-bottom: 15px;
        }

        .stable-name {
            font-size: 16px;
            color: #555555;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }

        .certificate-title {
            font-size: 32px;
            color: #2c5f2d;
            margin-bottom: 10px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .certificate-subtitle {
            font-size: 14px;
            color: #777777;
            margin-bottom: 40px;
        }

        .certificate-body {
            margin: 30px 0;
        }

        .certificate-text {
            font-size: 16px;
            line-height: 1.8;
            color: #444444;
        }

        .display-name {
            font-size: 26px;
            font-weight: bold;
            color: #2c5f2d;
            margin: 20px 0;
        }

        .horse-name {
            font-size: 22px;
            font-weight: bold;
            color: #4a7c4f;
            font-style: italic;
            margin: 15px 0;
        }

        .certificate-date {
            font-size: 14px;
            color: #666666;
            margin-top: 40px;
        }

        .certificate-footer {
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
            border-top: 1px solid #cccccc;
            padding-top: 15px;
            font-size: 11px;
            color: #999999;
        }

        .horse-photo {
            max-width: 180px;
            max-height: 180px;
            border-radius: 50%;
            border: 3px solid #2c5f2d;
            margin: 20px auto;
            object-fit: cover;
        }

        .decorative-line {
            width: 200px;
            height: 2px;
            background: #2c5f2d;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="certificate">
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

            <div class="horse-name">{{ $horseName }}</div>

            @if($horsePhoto)
                <img src="{{ public_path('storage/' . $horsePhoto) }}" alt="{{ $horseName }}" class="horse-photo">
            @endif

            <div class="decorative-line"></div>

            <div class="certificate-date">
                Sponsorship commenced on {{ $startDate }}
            </div>
        </div>

        <div class="certificate-footer">
            Issued by {{ $stableName }}
        </div>
    </div>
</body>
</html>
