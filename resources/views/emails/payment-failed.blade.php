@extends('emails.layout')

@section('subject', 'Payment Failed for Your Sponsorship')

@section('from_name', $centreName ?? 'Margaret Haes Riding Centre')

@section('content')
    <h2>Payment Issue</h2>

    <p>Hello {{ $sponsorName }},</p>

    <p>We were unable to process your monthly payment for your sponsorship of <strong>{{ $horseName }}</strong>.</p>

    <p>Please update your payment method to ensure your sponsorship remains active. If your payment continues to fail, your sponsorship may be cancelled automatically.</p>

    <p style="text-align: center; margin-top: 24px;">
        <a href="{{ $portalUrl }}" class="btn">Update Payment Method</a>
    </p>

    <p>If you need any assistance, please don't hesitate to get in touch with us.</p>

    <p style="font-size: 13px; color: #6b7280; margin-top: 24px;">
        Kind regards,<br>
        <strong>{{ $centreName ?? 'Margaret Haes Riding Centre' }}</strong>
    </p>
@endsection
