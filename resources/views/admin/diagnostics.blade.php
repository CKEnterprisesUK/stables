<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Email & Queue Diagnostics
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Queue Status --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Queue Status</h3>
                <dl class="grid grid-cols-1 gap-3">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Queue Connection</dt>
                        <dd class="font-mono">{{ $data['queue_connection'] }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Pending Jobs</dt>
                        <dd class="font-mono {{ $data['pending_jobs'] > 0 ? 'text-amber-600 font-bold' : 'text-green-600' }}">
                            {{ $data['pending_jobs'] }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Failed Jobs</dt>
                        <dd class="font-mono {{ $data['failed_jobs'] > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                            {{ $data['failed_jobs'] }}
                        </dd>
                    </div>
                </dl>

                @if ($data['queue_connection'] === 'sync')
                    <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded text-sm">
                        Queue is set to <strong>sync</strong> — jobs run immediately (not queued). If emails aren't arriving, the problem is SMTP config, not the queue.
                    </div>
                @endif
            </div>

            {{-- Mail Config --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mail Configuration</h3>
                <dl class="grid grid-cols-1 gap-3">
                    @foreach ($data['mail'] as $key => $value)
                        <div class="flex justify-between">
                            <dt class="text-gray-600">{{ str_replace('_', ' ', ucfirst($key)) }}</dt>
                            <dd class="font-mono text-sm">{{ $value ?: '(empty)' }}</dd>
                        </div>
                    @endforeach
                </dl>

                @if (empty($data['mail']['from_address']))
                    <div class="mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded text-sm">
                        <strong>Problem:</strong> From address is empty. Emails will fail. Check your SMTP settings.
                    </div>
                @endif
            </div>

            {{-- Failed Jobs --}}
            @if (count($data['recent_failures']) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Failures</h3>
                    <div class="space-y-4">
                        @foreach ($data['recent_failures'] as $failure)
                            <div class="bg-red-50 border border-red-200 rounded p-4">
                                <p class="text-sm text-gray-600 mb-2">Failed at: {{ $failure['failed_at'] }}</p>
                                <pre class="text-xs text-red-800 whitespace-pre-wrap break-words">{{ $failure['exception'] }}</pre>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Send Test Email --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Send Test Email</h3>
                <p class="text-sm text-gray-600 mb-4">
                    This sends a plain text email directly (bypassing the queue) to verify SMTP is working.
                </p>
                <form method="POST" action="{{ route('admin.diagnostics.send-test') }}" class="flex gap-3">
                    @csrf
                    <input
                        type="email"
                        name="email"
                        placeholder="your@email.com"
                        required
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                        Send Test
                    </button>
                </form>
            </div>

            @isset($data['db_error'])
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded text-sm">
                    <strong>Database error:</strong> {{ $data['db_error'] }}
                </div>
            @endisset

        </div>
    </div>
</x-app-layout>
