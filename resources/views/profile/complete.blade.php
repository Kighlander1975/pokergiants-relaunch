@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
    <h1 class="text-3xl font-bold text-center mb-8">Profil vervollständigen</h1>
@endsection

@section('content-body')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <p class="text-gray-600 mb-6">
                Bevor Sie die App nutzen können, müssen Sie Ihre Profilinformationen vervollständigen.
                Alle Felder sind erforderlich.
            </p>

            <form method="POST" action="{{ route('profile.complete.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Firstname -->
                <div>
                    <label for="firstname" class="block text-sm font-medium text-gray-700">Vorname</label>
                    <input type="text" name="firstname" id="firstname"
                           value="{{ old('firstname', $user->userDetail->firstname ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('firstname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lastname -->
                <div>
                    <label for="lastname" class="block text-sm font-medium text-gray-700">Nachname</label>
                    <input type="text" name="lastname" id="lastname"
                           value="{{ old('lastname', $user->userDetail->lastname ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('lastname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Street and Number -->
                <div>
                    <label for="street_number" class="block text-sm font-medium text-gray-700">Straße und Hausnummer</label>
                    <input type="text" name="street_number" id="street_number"
                           value="{{ old('street_number', $user->userDetail->street_number ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('street_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ZIP -->
                <div>
                    <label for="zip" class="block text-sm font-medium text-gray-700">PLZ</label>
                    <input type="text" name="zip" id="zip"
                           value="{{ old('zip', $user->userDetail->zip ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('zip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">Stadt</label>
                    <input type="text" name="city" id="city"
                           value="{{ old('city', $user->userDetail->city ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="dob" class="block text-sm font-medium text-gray-700">Geburtsdatum</label>
                    <input type="date" name="dob" id="dob"
                           value="{{ old('dob', $user->userDetail->dob ? $user->userDetail->dob->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           required>
                    @error('dob')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Profil speichern
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection