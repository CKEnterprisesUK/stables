@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">What Your Sponsorship Goes To</h1>
        <p class="mt-1 text-sm text-stable-500">Edit the public page that tells sponsors and potential sponsors what their money goes towards. This content is displayed on the public "What Your Sponsorship Goes To" page.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
            {{ session('status') }}
        </div>
    @endif

    <div class="max-w-3xl bg-white shadow-sm border border-stable-200 rounded-xl p-6">
        <form method="POST" action="{{ route('admin.sponsorship-info.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="sponsorship_info" class="block text-sm font-medium text-stable-700">Page Content</label>
                <p class="mt-1 text-xs text-stable-500">Use line breaks to separate paragraphs. This will be displayed as formatted text on the public page.</p>
                <textarea
                    name="sponsorship_info"
                    id="sponsorship_info"
                    rows="15"
                    class="mt-2 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                    placeholder="Describe what sponsorship money goes towards, e.g. feed, veterinary care, shelter, farrier visits..."
                    required
                >{{ old('sponsorship_info', $branding?->sponsorship_info) }}</textarea>
                @error('sponsorship_info')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                    Save Content
                </button>

                <a href="{{ route('sponsorship-info') }}" target="_blank" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                    View Public Page &rarr;
                </a>
            </div>
        </form>
    </div>
@endsection
