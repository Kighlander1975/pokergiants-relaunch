@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" /> Mein Profil<x-suit type="spade" /></h1>
</div>
@endsection

@section('content-body')

<!-- User Info Card -->
<div class="glass-card one-card one-card-100">
    <div class="flex items-center justify-start space-x-8">
        {{-- Debug: {{ $user->nickname }} --}}
        <x-avatar
            :image-filename="$user->userDetail->avatar_image_filename ?? null"
            :firstname="$user->userDetail->firstname ?? null"
            :lastname="$user->userDetail->lastname ?? null"
            :nickname="$user->nickname"
            size="80" />

        @php
        $countryFlag = $user->userDetail->country_flag ?? 'de_DE';
        $parts = explode('_', $countryFlag);
        $countryCode = strtolower($parts[0]);
        $regionCode = isset($parts[1]) ? '-' . strtolower($parts[1]) : '';
        $flagCode = $countryCode . $regionCode;
        @endphp
        <span class="fi fi-{{ $flagCode }} text-6xl"></span>

        <div>
            <h2 class="text-2xl font-bold">{{ $user->nickname }}</h2>
            <p class="text-gray-600">{{ $user->email }}</p>
            <p class="text-sm text-gray-500">Rolle: {{ $user->userDetail->role ?? 'player' }}</p>
        </div>
    </div>
</div>

<!-- Profile Actions -->

<!-- Edit Profile -->
<div class="glass-card">
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
<div class="glass-card">
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
<div class="glass-card">
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


<!-- User Details (if available) -->
@if($user->userDetail)
<div class="glass-card one-card one-card-100">
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
            <p class="mt-1 text-gray-900">
                @php
                $countryMap = [
                'DE' => 'Deutschland',
                'AT' => 'Österreich',
                'CH' => 'Schweiz',
                'Other' => 'Kein DACH-Land'
                ];
                $countryCode = $user->userDetail->country ?? 'DE';
                $countryName = $countryMap[$countryCode] ?? $countryCode;
                @endphp
                {{ $countryName }}
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Geburtsdatum</label>
            <p class="mt-1 text-gray-900">
                {{ $user->userDetail->dob ? $user->userDetail->dob->format('d.m.Y') : 'Nicht angegeben' }}
            </p>
        </div>
    </div>
</div>
<div class="glass-card one-card one-card-100">
    <h3 class="text-xl font-semibold mb-1">Über mich</h3>
    <div>
        <p class="mt-1 text-gray-900 whitespace-pre-line">
            {{ $user->userDetail->bio ?? 'Keine Biografie angegeben.' }}
        </p>
    </div>
</div>
@endif
@endsection