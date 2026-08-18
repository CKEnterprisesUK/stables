@extends('emails.layout')

@section('subject', 'Magic Login Link')

@section('from_name', $centreName ?? 'Margaret Haes Riding Centre')

@section('content')
    <h2>Magic Login Link</h2>

    <p>You requested a magic link to log in to your account. Click the button below to sign in:</p>

    <p style="text-align: center; margin: 28px 0;">
        <a href="{{ $url }}" class="btn">Log In Now</a>
    </p>

    <p>This link will expire at {{ $expiresAt->format('g:i A') }} ({{ $expiresAt->diffForHumans() }}). If you did not request this link, you can safely ignore this email.</p>

    <p style="font-size: 13px; color: #6b7280; margin-top: 28px;">
        If the button above doesn't work, copy and paste this URL into your browser:<br>
        <a href="{{ $url }}" style="color: #4f46e5; word-break: break-all;">{{ $url }}</a>
    </p>
@endsection
