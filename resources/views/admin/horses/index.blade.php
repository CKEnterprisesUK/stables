@extends('layouts.admin')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-stable-900">Horses</h1>
            <p class="mt-1 text-sm text-stable-500">Manage your stable's horses and their profiles.</p>
        </div>
        <a href="{{ route('admin.horses.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Horse
        </a>
    </div>

    <!-- Desktop table view -->
    <div class="hidden sm:block bg-white shadow-sm border border-stable-200 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-stable-200">
            <thead class="bg-stable-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Horse</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-stable-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stable-100">
                @forelse ($horses as $horse)
                    <tr class="hover:bg-stable-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if ($horse->photos->first())
                                    <img src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                                         alt="{{ $horse->name }}"
                                         class="h-12 w-12 rounded-lg object-cover ring-1 ring-stable-200">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-saddle-50 flex items-center justify-center ring-1 ring-saddle-200">
                                        <svg class="h-6 w-6 text-saddle-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
                                        </svg>
                                    </div>
                                @endif
                                <a href="{{ route('admin.horses.show', $horse) }}" class="text-sm font-medium text-stable-900 hover:text-brand-700 transition-colors">
                                    {{ $horse->name }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.horses.edit', $horse) }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition-colors">Edit</a>
                                <form action="{{ route('admin.horses.destroy', $horse) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this horse?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-stable-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
                            </svg>
                            <p class="mt-4 text-sm text-stable-500">No horses yet.</p>
                            <a href="{{ route('admin.horses.create') }}" class="mt-2 inline-block text-sm font-medium text-brand-600 hover:text-brand-800">Add your first horse</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile card view -->
    <div class="sm:hidden space-y-3">
        @forelse ($horses as $horse)
            <div class="bg-white border border-stable-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    @if ($horse->photos->first())
                        <img src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                             alt="{{ $horse->name }}"
                             class="h-12 w-12 rounded-lg object-cover ring-1 ring-stable-200">
                    @else
                        <div class="h-12 w-12 rounded-lg bg-saddle-50 flex items-center justify-center ring-1 ring-saddle-200">
                            <svg class="h-6 w-6 text-saddle-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6.75v12A2.25 2.25 0 003.75 21z" />
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('admin.horses.show', $horse) }}" class="text-sm font-semibold text-stable-900 hover:text-brand-700">
                            {{ $horse->name }}
                        </a>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-4 pt-3 border-t border-stable-100">
                    <a href="{{ route('admin.horses.edit', $horse) }}" class="text-sm font-medium text-brand-600 hover:text-brand-800">Edit</a>
                    <a href="{{ route('admin.horses.show', $horse) }}" class="text-sm font-medium text-stable-600 hover:text-stable-800">View</a>
                    <form action="{{ route('admin.horses.destroy', $horse) }}" method="POST" class="inline ml-auto" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white border border-stable-200 rounded-xl p-8 text-center shadow-sm">
                <p class="text-sm text-stable-500">No horses yet.</p>
                <a href="{{ route('admin.horses.create') }}" class="mt-2 inline-block text-sm font-medium text-brand-600 hover:text-brand-800">Add your first horse</a>
            </div>
        @endforelse
    </div>
@endsection
