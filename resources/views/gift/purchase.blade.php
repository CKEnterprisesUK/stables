<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Gift a Sponsorship</h2>
        <p class="text-sm text-gray-600 mt-1">Buy a pre-paid sponsorship as a gift for someone special</p>
        @if($horse->photos->isNotEmpty())
            <img src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                 alt="{{ $horse->name }}"
                 class="mt-4 mx-auto w-32 h-32 object-cover rounded-full">
        @endif
        <p class="mt-2 text-lg font-semibold text-gray-700">{{ $horse->name }}</p>
    </div>

    <form id="gift-form" method="POST" action="{{ route('gift.store', $horse) }}">
        @csrf

        <!-- Purchaser Name -->
        <div>
            <x-input-label for="purchaser_name" :value="__('Your Name')" />
            <x-text-input id="purchaser_name" class="block mt-1 w-full" type="text" name="purchaser_name"
                          :value="old('purchaser_name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('purchaser_name')" class="mt-2" />
        </div>

        <!-- Purchaser Email -->
        <div class="mt-4">
            <x-input-label for="purchaser_email" :value="__('Your Email')" />
            <x-text-input id="purchaser_email" class="block mt-1 w-full" type="email" name="purchaser_email"
                          :value="old('purchaser_email')" required autocomplete="email" />
            <p class="text-xs text-gray-500 mt-1">We'll send the gift card to this email for you to pass on.</p>
            <x-input-error :messages="$errors->get('purchaser_email')" class="mt-2" />
        </div>

        <!-- Recipient Name (Optional) -->
        <div class="mt-4">
            <x-input-label for="recipient_name" :value="__('Recipient\'s Name (optional)')" />
            <x-text-input id="recipient_name" class="block mt-1 w-full" type="text" name="recipient_name"
                          :value="old('recipient_name')" autocomplete="off" />
            <p class="text-xs text-gray-500 mt-1">This will appear on the gift card.</p>
            <x-input-error :messages="$errors->get('recipient_name')" class="mt-2" />
        </div>

        <!-- Gift Message (Optional) -->
        <div class="mt-4">
            <x-input-label for="recipient_message" :value="__('Gift Message (optional)')" />
            <textarea id="recipient_message" name="recipient_message" rows="2"
                      class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                      maxlength="500">{{ old('recipient_message') }}</textarea>
            <x-input-error :messages="$errors->get('recipient_message')" class="mt-2" />
        </div>

        <!-- Duration Selection -->
        <div class="mt-4" x-data="{ months: '{{ old('months', '12') }}' }">
            <x-input-label :value="__('Sponsorship Duration')" />
            <div class="mt-2 grid grid-cols-3 gap-3">
                <label class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none"
                       :class="months === '3' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300'">
                    <input type="radio" name="months" value="3" x-model="months" class="sr-only">
                    <span class="flex flex-col text-center w-full">
                        <span class="text-lg font-semibold" :class="months === '3' ? 'text-indigo-900' : 'text-gray-900'">3</span>
                        <span class="text-xs" :class="months === '3' ? 'text-indigo-700' : 'text-gray-500'">months</span>
                        <span class="mt-1 text-sm font-medium" :class="months === '3' ? 'text-indigo-900' : 'text-gray-700'">&pound;{{ number_format($monthlyAmount * 3, 2) }}</span>
                    </span>
                </label>
                <label class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none"
                       :class="months === '6' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300'">
                    <input type="radio" name="months" value="6" x-model="months" class="sr-only">
                    <span class="flex flex-col text-center w-full">
                        <span class="text-lg font-semibold" :class="months === '6' ? 'text-indigo-900' : 'text-gray-900'">6</span>
                        <span class="text-xs" :class="months === '6' ? 'text-indigo-700' : 'text-gray-500'">months</span>
                        <span class="mt-1 text-sm font-medium" :class="months === '6' ? 'text-indigo-900' : 'text-gray-700'">&pound;{{ number_format($monthlyAmount * 6, 2) }}</span>
                    </span>
                </label>
                <label class="relative flex cursor-pointer rounded-lg border p-3 focus:outline-none"
                       :class="months === '12' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600' : 'border-gray-300'">
                    <input type="radio" name="months" value="12" x-model="months" class="sr-only">
                    <span class="flex flex-col text-center w-full">
                        <span class="text-lg font-semibold" :class="months === '12' ? 'text-indigo-900' : 'text-gray-900'">12</span>
                        <span class="text-xs" :class="months === '12' ? 'text-indigo-700' : 'text-gray-500'">months</span>
                        <span class="mt-1 text-sm font-medium" :class="months === '12' ? 'text-indigo-900' : 'text-gray-700'">&pound;{{ number_format($monthlyAmount * 12, 2) }}</span>
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('months')" class="mt-2" />
        </div>

        <!-- Total -->
        <div class="mt-4" x-data="{ months: '{{ old('months', '12') }}' }">
            <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border border-gray-200 rounded-md">
                <span class="text-sm text-gray-600">One-time payment:</span>
                <span class="text-lg font-semibold text-gray-900" x-text="'£' + ({{ $monthlyAmount }} * months).toFixed(2)"></span>
            </div>
        </div>

        <!-- Stripe Payment Element -->
        <div class="mt-4">
            <x-input-label :value="__('Payment Details')" />
            <div id="payment-element" class="mt-1 p-3 border border-gray-300 rounded-md bg-white">
                <!-- Stripe Card Element will be mounted here -->
            </div>
            <div id="payment-errors" class="mt-2 text-sm text-red-600" role="alert"></div>
        </div>

        <!-- Hidden fields -->
        <input type="hidden" id="payment_intent_id" name="payment_intent_id" value="">

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('gallery.show', $horse) }}">
                {{ __('Back to horse') }}
            </a>

            <x-primary-button id="submit-button" class="ms-4">
                {{ __('Buy Gift Sponsorship') }}
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ $stripeKey }}', { locale: 'en-GB' });
            const elements = stripe.elements();

            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#374151',
                        '::placeholder': { color: '#9CA3AF' },
                    },
                    invalid: { color: '#DC2626' },
                },
            });
            cardElement.mount('#payment-element');

            cardElement.on('change', function(event) {
                const displayError = document.getElementById('payment-errors');
                displayError.textContent = event.error ? event.error.message : '';
            });

            const form = document.getElementById('gift-form');
            const submitButton = document.getElementById('submit-button');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';

                // Get selected months
                const monthsInput = document.querySelector('input[name="months"]:checked');
                const months = monthsInput ? monthsInput.value : '12';

                try {
                    // 1. Create payment intent on server
                    const response = await fetch('{{ route("gift.payment-intent", $horse) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ months: parseInt(months) }),
                    });

                    const { clientSecret, amount } = await response.json();

                    // 2. Confirm card payment with Stripe
                    const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: document.getElementById('purchaser_name').value,
                                email: document.getElementById('purchaser_email').value,
                            },
                        },
                    });

                    if (error) {
                        document.getElementById('payment-errors').textContent = error.message;
                        submitButton.disabled = false;
                        submitButton.textContent = 'Buy Gift Sponsorship';
                    } else if (paymentIntent.status === 'succeeded') {
                        // 3. Set the payment intent ID and submit form
                        document.getElementById('payment_intent_id').value = paymentIntent.id;
                        form.submit();
                    }
                } catch (err) {
                    document.getElementById('payment-errors').textContent = 'An error occurred. Please try again.';
                    submitButton.disabled = false;
                    submitButton.textContent = 'Buy Gift Sponsorship';
                }
            });
        });
    </script>
    @endpush
</x-guest-layout>
