@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
    <h1 class="text-3xl font-bold text-center mb-8">Mein Profil</h1>
@endsection

@section('content-body')
    <div class="max-w-4xl mx-auto">
        <!-- User Info Card -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="text-2xl text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <p class="text-sm text-gray-500">Rolle: {{ $user->userDetail->role ?? 'player' }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Actions -->
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Edit Profile -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl text-blue-600">👤</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Profil bearbeiten</h3>
                <p class="text-gray-600 mb-4">Persönliche Daten und Nickname</p>
                <a href="{{ route('profile.edit') }}"
                   class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                    Bearbeiten
                </a>
            </div>

            <!-- Edit Avatar -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl text-green-600">📸</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Avatar ändern</h3>
                <p class="text-gray-600 mb-4">Profilbild hochladen</p>
                <button class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition duration-200 cursor-not-allowed opacity-50"
                        disabled>
                    Bald verfügbar
                </button>
            </div>

            <!-- Edit Credentials -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl text-purple-600">🔐</span>
                </div>
                <h3 class="text-xl font-semibold mb-2">Zugangsdaten</h3>
                <p class="text-gray-600 mb-4">E-Mail und Passwort ändern</p>
                <button class="bg-purple-600 text-white px-6 py-2 rounded-md hover:bg-purple-700 transition duration-200 cursor-not-allowed opacity-50"
                        disabled>
                    Bald verfügbar
                </button>
            </div>
        </div>

        <!-- User Details (if available) -->
        @if($user->userDetail)
        <div class="bg-white p-6 rounded-lg shadow-md mt-8">
            <h3 class="text-xl font-semibold mb-4">Persönliche Informationen</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Vorname</label>
                    <p class="mt-1 text-gray-900">{{ $user->userDetail->firstname ?? 'Nicht angegeben' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nachname</label>
                    <p class="mt-1 text-gray-900">{{ $user->userDetail->lastname ?? 'Nicht angegeben' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Adresse</label>
                    <p class="mt-1 text-gray-900">{{ $user->userDetail->street_number ?? 'Nicht angegeben' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">PLZ / Ort</label>
                    <p class="mt-1 text-gray-900">
                        {{ $user->userDetail->zip ?? '' }} {{ $user->userDetail->city ?? 'Nicht angegeben' }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Land</label>
                    <p class="mt-1 text-gray-900">{{ $user->userDetail->country ?? 'Nicht angegeben' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Geburtsdatum</label>
                    <p class="mt-1 text-gray-900">
                        {{ $user->userDetail->dob ? $user->userDetail->dob->format('d.m.Y') : 'Nicht angegeben' }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection