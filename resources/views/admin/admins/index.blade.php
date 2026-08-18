@extends('layouts.admin')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-stable-900">Admin Users</h1>
            <p class="mt-1 text-sm text-stable-500">Manage who has access to the admin panel and their permission level.</p>
        </div>
        <a href="{{ route('admin.admins.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 shadow-sm transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Admin
        </a>
    </div>

    <div class="bg-white shadow-sm border border-stable-200 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-stable-200">
            <thead class="bg-stable-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-stable-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-stable-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stable-100">
                @forelse($admins as $admin)
                    <tr class="hover:bg-stable-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-brand-100 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-brand-700">{{ substr($admin->name, 0, 1) }}</span>
                                </div>
                                <span class="text-sm font-medium text-stable-900">{{ $admin->name }}</span>
                                @if($admin->id === auth()->id())
                                    <span class="text-xs text-stable-400">(you)</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-stable-600">{{ $admin->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badgeColor = match($admin->role) {
                                    \App\Enums\UserRole::SuperAdmin => 'bg-purple-100 text-purple-700',
                                    \App\Enums\UserRole::SponsorshipAdmin => 'bg-blue-100 text-blue-700',
                                    \App\Enums\UserRole::UpdateAdmin => 'bg-green-100 text-green-700',
                                    default => 'bg-stable-100 text-stable-700',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                {{ $admin->role->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.admins.edit', $admin) }}"
                                   class="text-brand-600 hover:text-brand-800 font-medium transition-colors">Edit</a>
                                @if($admin->id !== auth()->id())
                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to remove this admin?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition-colors">Remove</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-stable-500">No admin users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Role Permissions Reference -->
    <div class="mt-8 bg-white shadow-sm border border-stable-200 rounded-xl p-6">
        <h2 class="text-lg font-semibold text-stable-900 mb-4">Role Permissions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-purple-700 mb-2">Super Admin</h3>
                <ul class="text-sm text-stable-600 space-y-1">
                    <li>All settings &amp; configuration</li>
                    <li>Manage sponsorship pricing</li>
                    <li>Manage admin users</li>
                    <li>Manage sponsorships &amp; finance</li>
                    <li>Manage horses &amp; updates</li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-blue-700 mb-2">Sponsorship Admin</h3>
                <ul class="text-sm text-stable-600 space-y-1">
                    <li>Manage sponsorships</li>
                    <li>View finance records</li>
                    <li>Manage horses &amp; updates</li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-green-700 mb-2">Update Admin</h3>
                <ul class="text-sm text-stable-600 space-y-1">
                    <li>Manage horses</li>
                    <li>Post &amp; send updates</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
