@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">Stable Branding</h1>
        <p class="mt-1 text-sm text-stable-500">Customise your stable's name and logo displayed across the portal.</p>
    </div>

    <div class="max-w-2xl bg-white shadow-sm border border-stable-200 rounded-xl p-6">
        <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-stable-700">Stable Name</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name', $branding?->name) }}"
                    class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if ($branding?->logo_path)
                <div>
                    <label class="block text-sm font-medium text-stable-700 mb-3">Current Logo</label>
                    <div class="inline-block p-4 bg-stable-50 rounded-xl border border-stable-200">
                        <img src="{{ asset('storage/' . $branding->logo_path) }}"
                             alt="{{ $branding->name }} logo"
                             class="h-20 w-auto">
                    </div>
                </div>
            @endif

            <div>
                <label for="logo" class="block text-sm font-medium text-stable-700">{{ $branding?->logo_path ? 'Replace Logo' : 'Upload Logo' }}</label>
                <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/svg+xml"
                    class="mt-1 block w-full text-sm text-stable-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                <p class="mt-1 text-xs text-stable-500">JPG, PNG, or SVG. Max 2MB.</p>
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if ($branding?->favicon_path)
                <div>
                    <label class="block text-sm font-medium text-stable-700 mb-3">Current Favicon</label>
                    <div class="inline-block p-4 bg-stable-50 rounded-xl border border-stable-200">
                        <img src="{{ asset('storage/' . $branding->favicon_path) }}"
                             alt="Favicon"
                             class="h-8 w-8 object-contain">
                    </div>
                </div>
            @endif

            <div>
                <label for="favicon" class="block text-sm font-medium text-stable-700">{{ $branding?->favicon_path ? 'Replace Favicon' : 'Upload Favicon' }}</label>
                <input type="file" name="favicon" id="favicon" accept="image/x-icon,image/png,image/svg+xml"
                    class="mt-1 block w-full text-sm text-stable-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                <p class="mt-1 text-xs text-stable-500">ICO, PNG, or SVG. Max 512KB.</p>
                @error('favicon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                    Save Branding
                </button>
            </div>
        </form>
    </div>
@endsection
