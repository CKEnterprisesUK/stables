<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sponsor Another Horse') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash messages --}}
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('sponsor.dashboard') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-3">
                    <div class="shrink-0 h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Monthly sponsorship: <strong>&pound;{{ number_format($monthlyAmount, 2) }}</strong>/month per horse</p>
                        <p class="text-xs text-gray-500">Each sponsorship is billed separately.</p>
                    </div>
                </div>
            </div>

            @if($horses->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-lg">You're sponsoring all available horses!</p>
                        <p class="mt-1 text-sm">There are no more horses available to sponsor at this time.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($horses as $horse)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ showForm: false }">
                            {{-- Horse Photo --}}
                            <div class="aspect-[4/3] overflow-hidden">
                                @if($horse->photos->isNotEmpty())
                                    <img src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                                         alt="{{ $horse->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">No photo</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $horse->name }}</h3>
                                @if($horse->breed)
                                    <p class="text-sm text-gray-500">{{ $horse->breed }}</p>
                                @endif

                                {{-- Sponsor button --}}
                                <button @click="showForm = !showForm"
                                        x-text="showForm ? 'Cancel' : 'Sponsor {{ $horse->name }}'"
                                        class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md transition-colors"
                                        :class="showForm ? 'text-gray-700 bg-gray-100 hover:bg-gray-200' : 'text-white bg-indigo-600 hover:bg-indigo-700'">
                                </button>

                                {{-- Sponsorship form (hidden until clicked) --}}
                                <div x-show="showForm" x-cloak class="mt-4 pt-4 border-t border-gray-200">
                                    <form id="sponsor-form-{{ $horse->id }}" method="POST" action="{{ route('sponsor.sponsorship.store', $horse) }}">
                                        @csrf

                                        <!-- Child Name (Optional) -->
                                        <div>
                                            <label for="child_name_{{ $horse->id }}" class="block text-xs font-medium text-gray-700">Child's Name (optional)</label>
                                            <input type="text" name="child_name" id="child_name_{{ $horse->id }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                   placeholder="If sponsoring for a child">
                                        </div>

                                        <!-- Stripe Card Element -->
                                        <div class="mt-3">
                                            <label class="block text-xs font-medium text-gray-700">Payment Details</label>
                                            <div id="card-element-{{ $horse->id }}" class="mt-1 p-2.5 border border-gray-300 rounded-md bg-white"></div>
                                            <div id="card-errors-{{ $horse->id }}" class="mt-1 text-xs text-red-600"></div>
                                        </div>

                                        <input type="hidden" name="payment_method" id="payment_method_{{ $horse->id }}" value="">

                                        <button type="submit" id="submit-btn-{{ $horse->id }}"
                                                class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Confirm Sponsorship
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stripe = Stripe('{{ $stripeKey }}', { locale: 'en-GB' });
            const elements = stripe.elements();
            const cardElements = {};

            // Initialize Stripe card elements for each horse form
            @foreach($horses as $horse)
            (function() {
                const horseId = '{{ $horse->id }}';
                const cardElement = elements.create('card', {
                    style: {
                        base: { fontSize: '14px', color: '#374151', '::placeholder': { color: '#9CA3AF' } },
                        invalid: { color: '#DC2626' }
                    }
                });
                cardElement.mount('#card-element-' + horseId);
                cardElements[horseId] = cardElement;

                cardElement.on('change', function(event) {
                    const errEl = document.getElementById('card-errors-' + horseId);
                    errEl.textContent = event.error ? event.error.message : '';
                });

                const form = document.getElementById('sponsor-form-' + horseId);
                form.addEventListener('submit', async function(event) {
                    event.preventDefault();
                    const btn = document.getElementById('submit-btn-' + horseId);
                    btn.disabled = true;
                    btn.textContent = 'Processing...';

                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElements[horseId],
                        billing_details: { name: '{{ auth()->user()->name }}', email: '{{ auth()->user()->email }}' }
                    });

                    if (error) {
                        document.getElementById('card-errors-' + horseId).textContent = error.message;
                        btn.disabled = false;
                        btn.textContent = 'Confirm Sponsorship';
                    } else {
                        document.getElementById('payment_method_' + horseId).value = paymentMethod.id;
                        form.submit();
                    }
                });
            })();
            @endforeach
        });
    </script>
    @endpush
</x-app-layout>
