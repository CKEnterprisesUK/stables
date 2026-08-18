<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Redeem Gift Sponsorship</h2>
        <p class="text-sm text-gray-600 mt-1">Enter your gift code and create your account to start your sponsorship</p>
    </div>

    @if($error)
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-700">{{ $error }}</p>
        </div>
    @endif

    @if($gift && $gift->isAvailable())
        <!-- Gift details preview -->
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
            @if($gift->horse->photos->isNotEmpty())
                <img src="{{ asset('storage/' . $gift->horse->photos->first()->path) }}"
                     alt="{{ $gift->horse->name }}"
                     class="mx-auto w-20 h-20 object-cover rounded-full">
            @endif
            <p class="mt-2 text-sm text-green-800">
                <strong>{{ $gift->months }}-month sponsorship</strong> of <strong>{{ $gift->horse->name }}</strong>
            </p>
            @if($gift->recipient_name)
                <p class="mt-1 text-xs text-green-700">For {{ $gift->recipient_name }}</p>
            @endif
            @if($gift->recipient_message)
                <p class="mt-1 text-xs text-green-600 italic">"{{ $gift->recipient_message }}"</p>
            @endif
            <p class="mt-1 text-xs text-green-600">From {{ $gift->purchaser_name }}</p>
        </div>

        <form method="POST" action="{{ route('gift.redeem.store') }}">
            @csrf

            <input type="hidden" name="code" value="{{ $gift->code }}">

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Your Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                              :value="old('name', $gift->recipient_name)" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                              :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password (Optional - can use magic link to sign in) -->
            <div class="mt-4" x-data="{ showPassword: false }">
                <div class="flex items-center gap-2">
                    <input id="set_password" type="checkbox" x-model="showPassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="set_password" class="text-sm text-gray-600">{{ __('Set a password (optional)') }}</label>
                </div>
                <p class="text-xs text-gray-500 mt-1">You can always sign in with a magic link sent to your email.</p>

                <div x-show="showPassword" x-transition class="mt-3 space-y-4">
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                      autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                      name="password_confirmation" autocomplete="new-password" />
                    </div>
                </div>
            </div>

            <!-- Child Name (Optional) -->
            <div class="mt-4">
                <x-input-label for="child_name" :value="__('Child\'s Name (optional)')" />
                <p class="text-xs text-gray-500 mt-1">If this sponsorship is for a child, enter their name for the certificate.</p>
                <x-text-input id="child_name" class="block mt-1 w-full" type="text" name="child_name"
                              :value="old('child_name')" autocomplete="off" />
                <x-input-error :messages="$errors->get('child_name')" class="mt-2" />
            </div>

            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                <p class="text-xs text-blue-700">
                    <strong>No credit card required.</strong> Your sponsorship is pre-paid for {{ $gift->months }} months.
                </p>
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button>
                    {{ __('Redeem & Start Sponsorship') }}
                </x-primary-button>
            </div>
        </form>
    @elseif(!$code)
        <!-- No code provided - show code entry form -->
        <form method="GET" action="{{ route('gift.redeem.create') }}">
            <div>
                <x-input-label for="code" :value="__('Gift Code')" />
                <x-text-input id="code" class="block mt-1 w-full font-mono text-center tracking-wider" type="text" name="code"
                              :value="old('code')" required autofocus placeholder="XXXX-XXXX-XXXX-XXXX" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button>
                    {{ __('Look Up Code') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <div class="mt-6 text-center">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('gallery') }}">
            &larr; Back to Gallery
        </a>
    </div>
</x-guest-layout>
