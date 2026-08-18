<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('sponsor.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Updates for :name', ['name' => $horse->name]) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Horse Header --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 flex flex-col sm:flex-row gap-6 items-start">
                    @if($horse->photos->isNotEmpty())
                        <img
                            src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                            alt="{{ $horse->name }}"
                            class="w-24 h-24 object-cover rounded-lg flex-shrink-0"
                        >
                    @else
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-400 text-xs">No photo</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $horse->name }}</h3>
                        @if($horse->facts)
                            <p class="mt-1 text-sm text-gray-600">{{ Str::limit($horse->facts, 200) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Updates List --}}
            @if($updates->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6V7.5z"/>
                        </svg>
                        <p class="mt-4 text-lg">No updates yet for {{ $horse->name }}.</p>
                        <p class="mt-1 text-sm">Check back soon for the latest news!</p>
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($updates as $update)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $update->title }}</h4>
                                    <span class="text-sm text-gray-500 flex-shrink-0 ml-4">{{ $update->created_at->format('j F Y') }}</span>
                                </div>

                                @if($update->body)
                                    <div class="mt-3 text-gray-700 prose prose-sm max-w-none">
                                        {!! nl2br(e($update->body)) !!}
                                    </div>
                                @endif

                                @if($update->photos->isNotEmpty())
                                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        @foreach($update->photos as $photo)
                                            <img
                                                src="{{ asset('storage/' . $photo->path) }}"
                                                alt="Update photo"
                                                class="w-full aspect-[4/3] object-cover rounded-lg"
                                            >
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $updates->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
