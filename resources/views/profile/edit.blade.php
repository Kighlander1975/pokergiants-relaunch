@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<h1 class="text-3xl font-bold text-center mb-8">Profil bearbeiten</h1>
@endsection

@section('content-body')
<div class="max-w-4xl mx-auto">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Nickname (from users table) -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nickname</label>
                <input type="text" name="name" id="name"
                    value="{{ old('name', $user->name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-Mail-Adresse</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', $user->email) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <p class="mt-1 text-sm text-gray-600">
                    Ihre E-Mail-Adresse ist nicht verifiziert.
                    <a href="{{ route('verification.send') }}" class="text-indigo-600 hover:text-indigo-900">Klicken Sie hier, um eine neue Verifizierungs-E-Mail zu senden.</a>
                </p>
                @endif
            </div>

            <!-- Firstname -->
            <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700">Vorname</label>
                <input type="text" name="firstname" id="firstname"
                    value="{{ old('firstname', $user->userDetail->firstname ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('firstname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lastname -->
            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700">Nachname</label>
                <input type="text" name="lastname" id="lastname"
                    value="{{ old('lastname', $user->userDetail->lastname ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('lastname')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Street and Number -->
            <div>
                <label for="street_number" class="block text-sm font-medium text-gray-700">Straße und Hausnummer</label>
                <input type="text" name="street_number" id="street_number"
                    value="{{ old('street_number', $user->userDetail->street_number ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('street_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- ZIP -->
            <div>
                <label for="zip" class="block text-sm font-medium text-gray-700">PLZ</label>
                <input type="text" name="zip" id="zip"
                    value="{{ old('zip', $user->userDetail->zip ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('zip')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- City -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">Stadt</label>
                <input type="text" name="city" id="city"
                    value="{{ old('city', $user->userDetail->city ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('city')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Country -->
            <div>
                <label for="country" class="block text-sm font-medium text-gray-700">Land</label>
                <input type="text" name="country" id="country"
                    value="{{ old('country', $user->userDetail->country ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('country')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date of Birth -->
            <div>
                <label for="dob" class="block text-sm font-medium text-gray-700">Geburtsdatum</label>
                <input type="date" name="dob" id="dob"
                    value="{{ old('dob', $user->userDetail->dob ? $user->userDetail->dob->format('Y-m-d') : '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('dob')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $user->userDetail->bio ?? '') }}</textarea>
                @error('bio')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('profile.show') }}"
                    class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                    Zurück
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Profil speichern
                </button>
            </div>
        </form>
    </div>
</div>
@endsection