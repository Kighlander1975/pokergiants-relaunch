@extends('layouts.backend.main-layout-container.app')

@section('title', 'Spielstätten')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Spielstätten verwalten</h3>
                    <a href="{{ route('admin.locations.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Neue Spielstätte</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($locations as $location)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $location->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <p>{{ $location->street }}</p>
                                    <p>{{ $location->postal_city }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($location->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-green-100 text-green-800 text-xs font-medium">Aktiv</span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-800 text-xs font-medium">Inaktiv</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="text-indigo-600 hover:text-indigo-900">Bearbeiten</a>
                                    <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" class="inline" onsubmit="return confirm('Spielstätte wirklich löschen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Archivieren</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $locations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection