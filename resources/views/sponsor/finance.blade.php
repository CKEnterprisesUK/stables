<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash messages --}}
            @if(session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('sponsor.dashboard') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Dashboard
                </a>

                @if(auth()->user()->hasStripeId())
                    <a href="{{ route('sponsor.billing') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                        Manage Payment Method
                    </a>
                @endif
            </div>

            @if($invoices->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <p class="mt-4 text-lg">No invoices yet.</p>
                        <p class="mt-1 text-sm">Your payment history will appear here once your first payment is processed.</p>
                    </div>
                </div>
            @else
                {{-- Desktop table --}}
                <div class="hidden md:block bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Horse</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $invoice->invoice_date->format('j M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $invoice->sponsorship?->horse?->name ?? 'General' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $invoice->formatted_amount }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($invoice->status === 'paid')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-green-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                Paid
                                            </span>
                                        @elseif($invoice->status === 'open')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200">
                                                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-50 text-gray-600 ring-1 ring-gray-200">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        @if($invoice->hosted_invoice_url)
                                            <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" rel="noopener"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                                View
                                            </a>
                                        @endif
                                        @if($invoice->pdf_url)
                                            <a href="{{ $invoice->pdf_url }}" target="_blank" rel="noopener"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                                PDF
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile card view --}}
                <div class="md:hidden space-y-3">
                    @foreach($invoices as $invoice)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $invoice->formatted_amount }}</p>
                                    <p class="text-xs text-gray-500">{{ $invoice->invoice_date->format('j M Y') }}</p>
                                </div>
                                @if($invoice->status === 'paid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        Paid
                                    </span>
                                @elseif($invoice->status === 'open')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-2 text-sm text-gray-600">{{ $invoice->sponsorship?->horse?->name ?? 'General' }}</p>
                            <div class="mt-3 flex gap-3">
                                @if($invoice->hosted_invoice_url)
                                    <a href="{{ $invoice->hosted_invoice_url }}" target="_blank" rel="noopener"
                                       class="text-xs font-medium text-indigo-600 hover:text-indigo-800">View Invoice</a>
                                @endif
                                @if($invoice->pdf_url)
                                    <a href="{{ $invoice->pdf_url }}" target="_blank" rel="noopener"
                                       class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Download PDF</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
