<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', 'Message')</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
        }
        .wrapper {
            width: 100%;
            padding: 40px 20px;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .header {
            padding: 32px 40px 24px;
            text-align: center;
            border-bottom: 1px solid #f3f4f6;
        }
        .header-logo {
            max-height: 60px;
            max-width: 200px;
            margin-bottom: 12px;
        }
        .header-from {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        .header-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin: 4px 0 0;
        }
        .content {
            padding: 32px 40px;
        }
        .content h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 16px;
        }
        .content p {
            font-size: 15px;
            color: #4b5563;
            margin: 0 0 16px;
            line-height: 1.7;
        }
        .btn {
            display: inline-block;
            padding: 12px 28px;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin: 8px 0 16px;
        }
        .btn:hover {
            background-color: #4338ca;
        }
        .footer {
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #f3f4f6;
            background-color: #fafafa;
        }
        .footer p {
            font-size: 12px;
            color: #9ca3af;
            margin: 0 0 4px;
        }
        .footer a {
            color: #6b7280;
            text-decoration: underline;
        }
        .horse-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
            border: 3px solid #e5e7eb;
        }
        @media only screen and (max-width: 600px) {
            .content, .header, .footer {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                @hasSection('horse_photo')
                    @yield('horse_photo')
                @endif

                <p class="header-from">@yield('from_name', $centreName ?? 'Margaret Haes Riding Centre')</p>

                @hasSection('header_subtitle')
                    <p class="header-subtitle">@yield('header_subtitle')</p>
                @endif
            </div>

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ $centreName ?? 'Margaret Haes Riding Centre' }}. All rights reserved.</p>
                <p>This is an automated message from {{ $centreName ?? 'Margaret Haes Riding Centre' }}.</p>
            </div>
        </div>
    </div>
</body>
</html>
