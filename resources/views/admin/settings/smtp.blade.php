@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">SMTP Email Settings</h1>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-700">{{ session('status') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.smtp.update') }}" class="bg-white shadow rounded-md p-6 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
            <input type="text" name="host" id="host"
                value="{{ old('host', $settings?->host) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
            @error('host')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="port" class="block text-sm font-medium text-gray-700">Port</label>
            <input type="number" name="port" id="port"
                value="{{ old('port', $settings?->port) }}"
                min="1" max="65535"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
            @error('port')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username"
                value="{{ old('username', $settings?->username) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
            @error('username')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password"
                placeholder="{{ $settings ? '••••••••' : '' }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                {{ $settings ? '' : 'required' }}>
            @if ($settings)
                <p class="mt-1 text-xs text-gray-500">Leave blank to keep the current password.</p>
            @endif
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="encryption" class="block text-sm font-medium text-gray-700">Encryption</label>
            <select name="encryption" id="encryption"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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
            <label for="from_address" class="block text-sm font-medium text-gray-700">From Address</label>
            <input type="email" name="from_address" id="from_address"
                value="{{ old('from_address', $settings?->from_address) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
            @error('from_address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="from_name" class="block text-sm font-medium text-gray-700">From Name</label>
            <input type="text" name="from_name" id="from_name"
                value="{{ old('from_name', $settings?->from_name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                required>
            @error('from_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Save Settings
            </button>
        </div>
    </form>

    @if ($settings)
    <div class="mt-6 bg-white shadow rounded-md p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Test Configuration</h2>
        <p class="text-sm text-gray-600 mb-4">Send a test email to your registered email address to verify the SMTP configuration.</p>
        <form method="POST" action="{{ route('admin.settings.smtp.test') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Send Test Email
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
