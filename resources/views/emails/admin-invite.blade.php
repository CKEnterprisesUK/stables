@extends('emails.layout')

@section('subject', "You've been invited to " . ($centreName ?? 'Margaret Haes Riding Centre'))

@section('from_name', $centreName ?? 'Margaret Haes Riding Centre')

@section('content')
    <h2>Welcome to the team!</h2>

    <p>Hello {{ $userName }},</p>

    <p>You've been added as an administrator at <strong>{{ $centreName ?? 'Margaret Haes Riding Centre' }}</strong>. To get started, please set your password by clicking the button below:</p>

    <p style="text-align: center; margin: 28px 0;">
        <a href="{{ $setPasswordUrl }}" class="btn">Set Your Password</a>
    </p>

    <p>This link will expire in 60 minutes. If you did not expect this invitation, you can safely ignore this email.</p>

    <p style="font-size: 13px; color: #6b7280; margin-top: 28px;">
        Kind regards,<br>
        <strong>{{ $centreName ?? 'Margaret Haes Riding Centre' }}</strong>
    </p>
@endsection
