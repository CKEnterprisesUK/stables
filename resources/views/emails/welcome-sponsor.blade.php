@extends('emails.layout')

@section('subject', "Thank you from {$horseName}!")

@section('from_name', $horseName)

@section('header_subtitle', 'via ' . ($centreName ?? 'Margaret Haes Riding Centre'))

@if($horsePhoto)
@section('horse_photo')
    <img src="{{ $horsePhoto }}" alt="{{ $horseName }}" class="horse-avatar">
@endsection
@endif

@section('content')
    <h2>Thank you for sponsoring me!</h2>

    <p>Hello {{ $sponsorName }},</p>

    @if($childName)
        <p>I'm so excited that <strong>{{ $childName }}</strong> is now my sponsor! Please pass on a big thank you from me.</p>
    @else
        <p>I'm so excited that you've chosen to sponsor me! It means the world to have your support.</p>
    @endif

    <p>Your monthly sponsorship of <strong>&pound;{{ number_format($monthlyAmount / 100, 2) }}</strong> helps keep me happy, healthy and well cared for at {{ $centreName ?? 'Margaret Haes Riding Centre' }}.</p>

    <p>As a sponsor, you'll receive regular updates about how I'm doing, and you can visit your sponsor portal any time to see my latest news.</p>

    <p>Your sponsorship certificate is attached to this email.</p>

    <p style="text-align: center; margin: 28px 0;">
        <a href="{{ $portalUrl }}" class="btn">Visit the Updates Portal for {{ $horseName }}</a>
    </p>

    <p style="font-size: 13px; color: #6b7280; margin-top: 28px;">
        With love,<br>
        <strong>{{ $horseName }}</strong> 🐴
    </p>
@endsection
