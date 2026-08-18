<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Sponsor {{ $horse->name }}</h2>
        @if($horse->photos->isNotEmpty())
            <img src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                 alt="{{ $horse->name }}"
                 class="mt-4 mx-auto w-32 h-32 object-cover rounded-full">
        @endif
    </div>

    <form id="signup-form" method="POST" action="{{ route('signup.store', $horse) }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                          :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Monthly Amount (fixed by admin) -->
        <div class="mt-4">
            <x-input-label :value="__('Monthly Sponsorship Amount')" />
            <div class="mt-1 flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-md">
                <span class="text-lg font-semibold text-gray-900">&pound;{{ number_format($monthlyAmount, 2) }}</span>
                <span class="text-sm text-gray-500">per month</span>
            </div>
        </div>

        <!-- Child Name (Optional) -->
        <div class="mt-4">
            <x-input-label for="child_name" :value="__('Child\'s Name (optional)')" />
            <p class="text-xs text-gray-500 mt-1">If this sponsorship is a gift for a child, enter their name. It will appear on the certificate.</p>
            <x-text-input id="child_name" class="block mt-1 w-full" type="text" name="child_name"
                          :value="old('child_name')" autocomplete="off" />
            <x-input-error :messages="$errors->get('child_name')" class="mt-2" />
        </div>

        <!-- Stripe Payment Element -->
        <div class="mt-4">
            <x-input-label :value="__('Payment Details')" />
            <div id="payment-element" class="mt-1 p-3 border border-gray-300 rounded-md bg-white">
                <!-- Stripe Payment Element will be mounted here -->
            </div>
            <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
            <div id="payment-errors" class="mt-2 text-sm text-red-600" role="alert"></div>
        </div>

        <!-- Hidden payment method field -->
        <input type="hidden" id="payment_method" name="payment_method" value="">

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>

            <x-primary-button id="submit-button" class="ms-4">
                {{ __('Start Sponsorship') }}
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ $stripeKey }}');
            const elements = stripe.elements();

            // Create and mount the Card Element
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#374151',
                        '::placeholder': {
                            color: '#9CA3AF',
                        },
                    },
                    invalid: {
                        color: '#DC2626',
                    },
                },
            });
            cardElement.mount('#payment-element');

            // Handle real-time validation errors from the card Element
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('payment-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission
            const form = document.getElementById('signup-form');
            const submitButton = document.getElementById('submit-button');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';

                const { paymentMethod, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                    },
                });

                if (error) {
                    const displayError = document.getElementById('payment-errors');
                    displayError.textContent = error.message;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Start Sponsorship';
                } else {
                    // Set the payment method ID in the hidden field
                    document.getElementById('payment_method').value = paymentMethod.id;
                    form.submit();
                }
            });
        });
    </script>
    @endpush
</x-guest-layout>
