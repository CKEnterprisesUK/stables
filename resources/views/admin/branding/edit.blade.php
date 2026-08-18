@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Stable Branding</h1>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-700">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data" class="bg-white shadow rounded-md p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Stable Name</label>
            <input type="text" name="name" id="name"
                value="{{ old('name', $branding?->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        @if ($branding?->logo_path)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                <img src="{{ asset('storage/' . $branding->logo_path) }}"
                     alt="{{ $branding->name }} logo"
                     class="h-24 w-auto rounded-md border border-gray-200">
            </div>
        @endif

        <div>
            <label for="logo" class="block text-sm font-medium text-gray-700">{{ $branding?->logo_path ? 'Replace Logo' : 'Upload Logo' }}</label>
            <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/svg+xml"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p class="mt-1 text-xs text-gray-500">JPG, PNG, or SVG. Max 2MB.</p>
            @error('logo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Save Branding
            </button>
        </div>
    </form>
</div>
@endsection
