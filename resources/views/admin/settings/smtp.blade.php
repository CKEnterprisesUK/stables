@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-stable-900">Email (SMTP) Settings</h1>
        <p class="mt-1 text-sm text-stable-500">Configure outgoing email for sponsor notifications and updates.</p>
    </div>

    <div class="max-w-2xl space-y-6">
        <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
            <form method="POST" action="{{ route('admin.settings.smtp.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="host" class="block text-sm font-medium text-stable-700">SMTP Host</label>
                        <input type="text" name="host" id="host"
                            value="{{ old('host', $settings?->host) }}"
                            placeholder="smtp.example.com"
                            class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                            required>
                        @error('host')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="port" class="block text-sm font-medium text-stable-700">Port</label>
                        <input type="number" name="port" id="port"
                            value="{{ old('port', $settings?->port) }}"
                            min="1" max="65535" placeholder="587"
                            class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                            required>
                        @error('port')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="encryption" class="block text-sm font-medium text-stable-700">Encryption</label>
                        <select name="encryption" id="encryption"
                            class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                            required>
                            <option value="tls" {{ old('encryption', $settings?->encryption?->value) === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('encryption', $settings?->encryption?->value) === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ old('encryption', $settings?->encryption?->value) === 'none' ? 'selected' : '' }}>None</option>
                        </select>
                        @error('encryption')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-stable-700">Username</label>
                        <input type="text" name="username" id="username"
                            value="{{ old('username', $settings?->username) }}"
                            class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                            required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-stable-700">Password</label>
                        <input type="password" name="password" id="password"
                            placeholder="{{ $settings ? '••••••••' : '' }}"
                            class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                            {{ $settings ? '' : 'required' }}>
                        @if ($settings)
                            <p class="mt-1 text-xs text-stable-500">Leave blank to keep the current password.</p>
                        @endif
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="from_address" class="block text-sm font-medium text-stable-700">From Address</label>
                        <input type="email" name="from_address" id="from_address"
                            value="{{ old('from_address', $settings?->from_address) }}"
                            placeholder="hello@yourstable.com"
                            class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                            required>
                        @error('from_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="from_name" class="block text-sm font-medium text-stable-700">From Name</label>
                        <input type="text" name="from_name" id="from_name"
                            value="{{ old('from_name', $settings?->from_name) }}"
                            placeholder="Happy Hooves Stables"
                            class="mt-1 block w-full rounded-lg border-stable-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                            required>
                        @error('from_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        @if ($settings)
        <div class="bg-white shadow-sm border border-stable-200 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-stable-900 mb-2">Test Configuration</h2>
            <p class="text-sm text-stable-500 mb-4">Send a test email to your registered address to verify settings work correctly.</p>
            <form method="POST" action="{{ route('admin.settings.smtp.test') }}">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-stable-100 text-stable-700 text-sm font-medium rounded-lg hover:bg-stable-200 border border-stable-300 shadow-sm transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    Send Test Email
                </button>
            </form>
        </div>
        @endif
    </div>
@endsection
