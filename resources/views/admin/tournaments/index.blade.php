@extends('layouts.backend.main-layout-container.app')

@section('title', 'Turniere')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Turniere</h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.locations.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Spielstätten</a>
                        <a href="{{ route('admin.tournaments.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Neues Turnier</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tournaments as $tournament)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $tournament->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $tournament->location->name ?? '–' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $tournament->starts_at->format('d.m.Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tournament->is_ranglistenturnier ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $tournament->is_ranglistenturnier ? 'Rangliste' : 'Freies Turnier' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium space-x-2 flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('admin.tournaments.publish', $tournament) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-900 disabled:text-gray-400" {{ $tournament->is_published ? 'disabled' : '' }}>Veröffentlichen</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.tournaments.open-registration', $tournament) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-cyan-600 hover:text-cyan-900 disabled:text-gray-400" {{ $tournament->is_registration_open ? 'disabled' : '' }}>Registrierung öffnen</button>
                                    </form>
                                    <a href="{{ route('admin.tournaments.edit', $tournament) }}" class="text-indigo-600 hover:text-indigo-900">Bearbeiten</a>
                                    <form method="POST" action="{{ route('admin.tournaments.destroy', $tournament) }}" class="inline" onsubmit="return confirm('Turnier wirklich archivieren?');">
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

                <div class="mt-6">{{ $tournaments->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection