@extends('emails.layout')

@section('subject', "Update from {$horseName}")

@section('from_name', $horseName)

@section('header_subtitle', "via {{ $centreName ?? 'Margaret Haes Riding Centre' }}")

@if($horsePhoto)
@section('horse_photo')
    <img src="{{ $horsePhoto }}" alt="{{ $horseName }}" class="horse-avatar">
@endsection
@endif

@section('content')
    <h2>{{ $updateTitle }}</h2>

    <p>Hello {{ $sponsorName }},</p>

    <p>{!! nl2br(e($updateBody)) !!}</p>

    @if(!empty($updatePhotos))
        <div style="margin: 20px 0;">
            @foreach($updatePhotos as $photo)
                <img src="{{ $photo }}" alt="Update photo" style="max-width: 100%; border-radius: 8px; margin-bottom: 12px;">
            @endforeach
        </div>
    @endif

    <p style="text-align: center; margin-top: 24px;">
        <a href="{{ $portalUrl }}" class="btn">View in Sponsor Portal</a>
    </p>

    <p style="font-size: 13px; color: #6b7280; margin-top: 24px;">
        With love,<br>
        <strong>{{ $horseName }}</strong> 🐴
    </p>
@endsection
