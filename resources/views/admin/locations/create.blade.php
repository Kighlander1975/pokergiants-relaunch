@extends('layouts.backend.main-layout-container.app')

@section('title', 'Neue Spielstätte')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Spielstätte hinzufügen</h3>
                    <a href="{{ route('admin.locations.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Zur Liste</a>
                </div>

                <form method="POST" action="{{ route('admin.locations.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input required type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="street" class="block text-sm font-medium text-gray-700">Straße / Hausnummer</label>
                        <input required type="text" name="street" id="street" value="{{ old('street') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('street') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="postal_city" class="block text-sm font-medium text-gray-700">PLZ / Ort</label>
                        <input required type="text" name="postal_city" id="postal_city" value="{{ old('postal_city') }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('postal_city') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Besondere Angaben</label>
                        <textarea required name="notes" id="notes" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-gray-700">Aktive Spielstätte</label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.locations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Abbrechen</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection