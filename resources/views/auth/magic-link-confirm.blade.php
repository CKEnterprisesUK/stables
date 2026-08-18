<x-guest-layout>
    <div class="text-center">
        <div class="mb-4">
            <svg class="mx-auto h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-gray-900 mb-2">
            {{ __('Confirm Sign In') }}
        </h2>

        <p class="text-sm text-gray-600 mb-6">
            {{ __('Click the button below to sign in to your account.') }}
        </p>

        <form method="POST" action="{{ route('magic-link.login', $token) }}">
            @csrf
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Sign In') }}
            </button>
        </form>

        <div class="border-t pt-4 mt-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                {{ __('Back to login') }}
            </a>
        </div>
    </div>
</x-guest-layout>
