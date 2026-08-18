@extends('emails.layout')

@section('subject', 'Your Sponsorship Has Been Cancelled')

@section('from_name', $centreName ?? 'Margaret Haes Riding Centre')

@section('content')
    <h2>Sponsorship Cancelled</h2>

    <p>Hello {{ $sponsorName }},</p>

    <p>We're writing to let you know that your sponsorship of <strong>{{ $horseName }}</strong> has been cancelled by our team.</p>

    <p>If you believe this was done in error or have any questions, please contact us and we'll be happy to help.</p>

    <p>We truly appreciate the support you've given {{ $horseName }} during your time as a sponsor. Thank you for making a difference.</p>

    <p style="font-size: 13px; color: #6b7280; margin-top: 24px;">
        With thanks,<br>
        <strong>{{ $centreName ?? 'Margaret Haes Riding Centre' }}</strong>
    </p>
@endsection
