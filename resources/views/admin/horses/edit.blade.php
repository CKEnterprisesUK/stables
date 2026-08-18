@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Horse: {{ $horse->name }}</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.horses.update', $horse) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $horse->name) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="facts" class="block text-sm font-medium text-gray-700">Facts</label>
                <textarea name="facts" id="facts" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('facts', $horse->facts) }}</textarea>
                @error('facts')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if ($horse->photos->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Photos</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach ($horse->photos as $photo)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $photo->path) }}"
                                     alt="{{ $horse->name }}"
                                     class="h-32 w-full rounded-md object-cover">
                                <label class="absolute top-2 right-2 flex items-center gap-1 bg-white/90 rounded px-2 py-1 text-xs cursor-pointer">
                                    <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="text-red-600">Delete</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-6">
                <label for="photos" class="block text-sm font-medium text-gray-700">Add More Photos</label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/jpeg,image/png,image/webp"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG, or WebP. Max 5MB per photo.</p>
                @error('photos.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    Update Horse
                </button>
                <a href="{{ route('admin.horses.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
