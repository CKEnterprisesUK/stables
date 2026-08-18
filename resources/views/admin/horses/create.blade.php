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
                <label for="facts" class="block text-sm font-medium text-stable-700">About / Quick Facts</label>
                <textarea name="facts" id="facts" rows="3"
                          class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                          placeholder="A short summary shown on listing cards">{{ old('facts') }}</textarea>
                @error('facts')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Profile Details --}}
            <fieldset class="border border-stable-200 rounded-lg p-4 space-y-4">
                <legend class="text-sm font-semibold text-stable-700 px-2">Profile Details</legend>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-stable-700">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="breed" class="block text-sm font-medium text-stable-700">Breed</label>
                        <input type="text" name="breed" id="breed" value="{{ old('breed') }}"
                               class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                               placeholder="e.g. Welsh Cob, Thoroughbred">
                        @error('breed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="colour" class="block text-sm font-medium text-stable-700">Colour</label>
                        <input type="text" name="colour" id="colour" value="{{ old('colour') }}"
                               class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                               placeholder="e.g. Bay, Chestnut, Grey">
                        @error('colour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-stable-700">Gender</label>
                        <select name="gender" id="gender"
                                class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                            <option value="">— Select —</option>
                            <option value="Mare" {{ old('gender') === 'Mare' ? 'selected' : '' }}>Mare</option>
                            <option value="Gelding" {{ old('gender') === 'Gelding' ? 'selected' : '' }}>Gelding</option>
                            <option value="Stallion" {{ old('gender') === 'Stallion' ? 'selected' : '' }}>Stallion</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="height_hands" class="block text-sm font-medium text-stable-700">Height (hands)</label>
                        <input type="number" step="0.1" name="height_hands" id="height_hands" value="{{ old('height_hands') }}"
                               class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                               placeholder="e.g. 14.2">
                        @error('height_hands')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="arrival_date" class="block text-sm font-medium text-stable-700">Arrival Date</label>
                        <input type="date" name="arrival_date" id="arrival_date" value="{{ old('arrival_date') }}"
                               class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                        @error('arrival_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="favourite_treats" class="block text-sm font-medium text-stable-700">Favourite Treats</label>
                    <input type="text" name="favourite_treats" id="favourite_treats" value="{{ old('favourite_treats') }}"
                           class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                           placeholder="e.g. Polo mints, carrots, apples">
                    @error('favourite_treats')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="personality" class="block text-sm font-medium text-stable-700">Personality</label>
                    <textarea name="personality" id="personality" rows="3"
                              class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                              placeholder="What's their character like? e.g. Gentle, loves cuddles, cheeky at feed time">{{ old('personality') }}</textarea>
                    @error('personality')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="backstory" class="block text-sm font-medium text-stable-700">Backstory</label>
                    <textarea name="backstory" id="backstory" rows="4"
                              class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                              placeholder="How did they come to be at the stables? Their history and journey.">{{ old('backstory') }}</textarea>
                    @error('backstory')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

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
