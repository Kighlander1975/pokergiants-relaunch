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
            :image-url="$user->hasAvatar() ? $user->getAvatarUrl('large') : null"
            :firstname="$user->userDetail->firstname ?? null"
            :lastname="$user->userDetail->lastname ?? null"
            :nickname="$user->nickname"
            :display-mode="$user->userDetail->getAvatarDisplayMode()"
            size="80" />

        @php
        $countryFlag = $user->userDetail->country_flag ?? 'de_DE';
        $flagCode = getFlagCode($countryFlag);
        @endphp
        <span class="fi fi-{{ $flagCode }} text-4xl"></span>

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
    <p class="text-gray-600 mb-4">Profilbild hochladen und bearbeiten</p>
    <div class="flex flex-row space-x-2 justify-center">
        <a href="{{ route('avatar.edit') }}"
            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200 inline-block text-center flex-1">
            Avatar ändern
        </a>
        <button @if(!$user->hasAvatar()) disabled @endif
            @if($user->hasAvatar()) onclick="deleteAvatar()" @endif
            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200 inline-block text-center flex-1">
            Avatar löschen
        </button>
    </div>
</div>

<!-- Avatar Display Mode -->
<div class="glass-card">
    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <span class="text-2xl text-yellow-600">🎭</span>
    </div>
    <h3 class="text-xl font-semibold mb-2">Avatar-Anzeige</h3>
    <p class="text-gray-600 mb-4">
        @if($user->hasAvatar())
        Wähle wie dein Avatar-Text angezeigt wird (nur verfügbar wenn kein Bild-Avatar gesetzt ist)
        @else
        Wähle wie dein Avatar-Text angezeigt wird
        @endif
    </p>
    <div class="flex flex-col space-y-2">
        <label class="flex items-center {{ $user->hasAvatar() ? 'opacity-50 cursor-not-allowed' : '' }}">
            <input type="radio" name="avatar_display_mode" value="nickname"
                {{ ($user->userDetail->getAvatarDisplayMode() ?? 'nickname') === 'nickname' ? 'checked' : '' }}
                {{ $user->hasAvatar() ? 'disabled' : '' }}
                class="mr-2">
            <span>Nickname verwenden ({{ Str::limit($user->nickname, 2, '') }})</span>
        </label>
        <label class="flex items-center {{ $user->hasAvatar() ? 'opacity-50 cursor-not-allowed' : '' }}">
            <input type="radio" name="avatar_display_mode" value="initials"
                {{ ($user->userDetail->getAvatarDisplayMode() ?? 'nickname') === 'initials' ? 'checked' : '' }}
                {{ $user->hasAvatar() ? 'disabled' : '' }}
                class="mr-2">
            <span>Initialen verwenden ({{ $user->userDetail->firstname && $user->userDetail->lastname ? Str::substr($user->userDetail->firstname, 0, 1) . Str::substr($user->userDetail->lastname, 0, 1) : '??' }})</span>
        </label>
    </div>
    <button onclick="updateAvatarDisplayMode()"
        {{ $user->hasAvatar() ? 'disabled' : '' }}
        class="bg-yellow-600 text-white px-6 py-2 rounded-md hover:bg-yellow-700 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200 mt-4">
        Avatar-Anzeige aktualisieren
    </button>
</div>

<!-- Edit Credentials -->
<div class="glass-card">
    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <span class="text-2xl text-purple-600">🔐</span>
    </div>
    <h3 class="text-xl font-semibold mb-2">Zugangsdaten</h3>
    <p class="text-gray-600 mb-4">E-Mail und Passwort ändern</p>
    <a href="{{ route('credentials.edit') }}"
        class="bg-purple-600 text-white px-6 py-2 rounded-md hover:bg-purple-700 transition duration-200">
        Zugangsdaten ändern
    </a>
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

@push('scripts')
<script>
    function deleteAvatar() {
        if (confirm('Bist du sicher, dass du deinen Avatar löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.')) {
            // Loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></svg>Lösche...';
            button.disabled = true;

            fetch('/avatar', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Erfolg - Seite neu laden um Änderungen zu zeigen
                        location.reload();
                    } else {
                        alert('Fehler beim Löschen: ' + (data.message || 'Unbekannter Fehler'));
                        // Button zurücksetzen
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    alert('Fehler beim Löschen des Avatars.');
                    // Button zurücksetzen
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }
    }
</script>
@endpush

@endsection