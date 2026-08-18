<x-guest-layout>
    <div class="text-center">
        <div class="mb-4">
            <svg class="mx-auto h-12 w-12 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-gray-900 mb-2">
            {{ __('Check your email') }}
        </h2>

        <p class="text-sm text-gray-600 mb-4">
            {{ __('We\'ve sent a magic link to your email address. Click the link in the email to log in.') }}
        </p>

        <p class="text-sm text-gray-500 mb-6">
            {{ __('You can close this tab.') }}
        </p>

        <div class="border-t pt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                {{ __('Back to login') }}
            </a>
        </div>
    </div>
</x-guest-layout>
