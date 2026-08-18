@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Horses</h1>
        <a href="{{ route('admin.horses.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
            Add Horse
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($horses as $horse)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($horse->photos->first())
                                <img src="{{ asset('storage/' . $horse->photos->first()->path) }}"
                                     alt="{{ $horse->name }}"
                                     class="h-12 w-12 rounded-md object-cover">
                            @else
                                <div class="h-12 w-12 rounded-md bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No photo</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.horses.show', $horse) }}" class="text-gray-900 font-medium hover:text-indigo-600">
                                {{ $horse->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('admin.horses.edit', $horse) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                            <form action="{{ route('admin.horses.destroy', $horse) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this horse?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            No horses found. <a href="{{ route('admin.horses.create') }}" class="text-indigo-600 hover:text-indigo-900">Add one now.</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
