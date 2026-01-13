@extends('layouts.backend.main-layout-container.app')

@section('title', 'Neuen Benutzer erstellen')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Neuen Benutzer anlegen</h3>
                    <a href="{{ route('admin.users') }}" class="text-sm text-gray-500 hover:text-gray-700">Zurück zur Liste</a>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nickname" class="block text-sm font-medium text-gray-700">Spitzname</label>
                            <input type="text" name="nickname" id="nickname" value="{{ old('nickname') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('nickname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">E-Mail</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Rolle</label>
                        <select name="role" id="role"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="player" {{ old('role', 'player') === 'player' ? 'selected' : '' }}>Spieler</option>
                            <option value="floorman" {{ old('role') === 'floorman' ? 'selected' : '' }}>Floorman</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 text-sm text-yellow-900">
                        Das Passwort wird automatisch generiert und fliesst einmalig per E-Mail an den Nutzer. Der Empfänger kann das Passwort jederzeit im Profil ändern.
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Abbrechen</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Benutzer erstellen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection