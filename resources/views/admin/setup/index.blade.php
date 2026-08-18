@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">Setup Checklist</h1>
        <p class="mt-1 text-sm text-stable-500">Complete these steps to get your sponsorship platform ready for sponsors.</p>
    </div>

    <!-- Progress overview -->
    <div class="mb-8 bg-white border border-stable-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-stable-700">
                {{ $completedCount }} of {{ $totalCount }} steps completed
            </p>
            @if($completedCount === $totalCount)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-700 ring-1 ring-brand-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                    All done
                </span>
            @endif
        </div>
        <div class="w-full bg-stable-100 rounded-full h-2.5">
            <div class="bg-brand-500 h-2.5 rounded-full transition-all duration-500"
                 style="width: {{ $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0 }}%"></div>
        </div>
    </div>

    @if($completedCount === $totalCount)
        <div class="mb-8 flex items-start gap-3 p-4 bg-brand-50 border border-brand-200 rounded-xl text-brand-800 text-sm">
            <svg class="h-5 w-5 shrink-0 text-brand-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-medium">Your platform is ready to accept sponsors.</p>
                <p class="mt-1 text-brand-700">Everything is configured. Share your gallery link with potential sponsors to get started.</p>
            </div>
        </div>
    @endif

    <!-- Checklist steps -->
    <div class="space-y-4">
        @foreach($steps as $index => $step)
            <div class="bg-white border border-stable-200 rounded-xl shadow-sm overflow-hidden {{ $step['completed'] ? 'opacity-80' : '' }}">
                <div class="p-5">
                    <div class="flex items-start gap-4">
                        <!-- Step number / completion indicator -->
                        <div class="shrink-0 mt-0.5">
                            @if($step['completed'])
                                <div class="h-8 w-8 rounded-full bg-brand-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            @else
                                <div class="h-8 w-8 rounded-full bg-stable-100 border-2 border-stable-300 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-stable-500">{{ $index + 1 }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3">
                                <h3 class="text-base font-semibold text-stable-900 {{ $step['completed'] ? 'line-through decoration-stable-300' : '' }}">
                                    {{ $step['title'] }}
                                </h3>
                                @if($step['completed'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-50 text-brand-700">
                                        Done
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-stable-600">{{ $step['description'] }}</p>

                            <!-- Why this helps -->
                            <div class="mt-3 flex items-start gap-2 p-3 bg-stable-50 rounded-lg border border-stable-100">
                                <svg class="h-4 w-4 shrink-0 text-stable-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                                </svg>
                                <p class="text-xs text-stable-500"><span class="font-medium text-stable-600">Why this helps:</span> {{ $step['why'] }}</p>
                            </div>

                            <!-- Action button -->
                            @if(!$step['completed'])
                                <div class="mt-4">
                                    <a href="{{ $step['action_url'] }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                                        {{ $step['action_label'] }}
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
