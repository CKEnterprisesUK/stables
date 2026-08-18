<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div x-data="{ showPassword: false }">
        {{-- Magic Link Form (Default) --}}
        <div x-show="!showPassword" x-transition>
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Enter your email address and we\'ll send you a magic link to log in instantly — no password needed.') }}
            </div>

            <form method="POST" action="{{ route('magic-link.request') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-primary-button class="w-full justify-center">
                        {{ __('Send Magic Link') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <button @click="showPassword = true" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    {{ __('Sign in with password instead') }}
                </button>
            </div>
        </div>

        {{-- Password Form (Optional) --}}
        <div x-show="showPassword" x-transition x-cloak>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="password_email" :value="__('Email')" />
                    <x-text-input id="password_email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button>
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <button @click="showPassword = false" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    {{ __('Use magic link instead') }}
                </button>
            </div>
        </div>
    </div>
</x-guest-layout>
