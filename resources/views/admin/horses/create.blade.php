@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.horses.index') }}" class="inline-flex items-center gap-1 text-sm text-stable-500 hover:text-stable-700 transition-colors mb-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            Back to Horses
        </a>
        <h1 class="text-2xl font-bold text-stable-900">Add Horse</h1>
    </div>

    <div class="max-w-2xl bg-white shadow-sm border border-stable-200 rounded-xl p-6">
        <form action="{{ route('admin.horses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-stable-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="facts" class="block text-sm font-medium text-stable-700">Facts</label>
                <textarea name="facts" id="facts" rows="4"
                          class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                          placeholder="Breed, age, personality, favourite treats...">{{ old('facts') }}</textarea>
                @error('facts')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="photos" class="block text-sm font-medium text-stable-700">Photos</label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/jpeg,image/png,image/webp"
                       class="mt-1 block w-full text-sm text-stable-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                <p class="mt-1 text-xs text-stable-500">JPG, PNG, or WebP. Max 5MB per photo.</p>
                @error('photos.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                    Create Horse
                </button>
                <a href="{{ route('admin.horses.index') }}" class="text-sm font-medium text-stable-600 hover:text-stable-800 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
