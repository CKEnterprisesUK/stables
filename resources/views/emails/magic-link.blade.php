<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magic Login Link</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #4f46e5;">Magic Login Link</h2>

    <p>You requested a magic link to log in to your account. Click the button below to sign in:</p>

    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" style="background-color: #4f46e5; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold;">
            Log In Now
        </a>
    </p>

    <p style="font-size: 14px; color: #666;">
        This link will expire at {{ $expiresAt->format('g:i A') }} ({{ $expiresAt->diffForHumans() }}).
        If you did not request this link, you can safely ignore this email.
    </p>

    <p style="font-size: 12px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
        If the button above doesn't work, copy and paste this URL into your browser:<br>
        <a href="{{ $url }}" style="color: #4f46e5; word-break: break-all;">{{ $url }}</a>
    </p>
</body>
</html>
