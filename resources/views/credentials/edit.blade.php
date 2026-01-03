@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    <h1 class="text-center home-size"><x-suit type="heart" /> Zugangsdaten ändern<x-suit type="spade" /></h1>
</div>
@endsection

@section('content-body')

<!-- Email Change Form -->
<div class="glass-card card-50">
    <h3 class="text-xl font-semibold mb-4">E-Mail-Adresse ändern</h3>

    @if($user->userDetail->pending_email)
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
        <p><strong>Ausstehende E-Mail-Änderung:</strong></p>
        <p>Neue E-Mail: {{ $user->userDetail->pending_email }}</p>
        <p>Angefordert: {{ $user->userDetail->email_change_requested_at->format('d.m.Y H:i') }}</p>
        <p>Läuft ab: {{ $user->userDetail->email_change_expires_at->format('d.m.Y H:i') }}</p>
        <p class="text-sm mt-2">Passwort-Änderungen sind solange blockiert, bis die E-Mail bestätigt wurde.</p>
    </div>
    @else
    <form action="{{ route('credentials.update.email') }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Neue E-Mail-Adresse</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Wichtiger Hinweis zur E-Mail-Änderung</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Ein Verifizierungslink wird an die neue E-Mail-Adresse gesendet</li>
                            <li>Der Link ist nur 10 Minuten gültig</li>
                            <li>Nach Bestätigung werden Sie automatisch abgemeldet</li>
                            <li>Bei fehlendem Zugriff auf die neue Adresse können Sie sich mit der alten Adresse anmelden</li>
                            <li>Passwort-Änderungen sind während der Verifizierung blockiert</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
            E-Mail-Adresse ändern
        </button>
    </form>
    @endif
</div>

<!-- Password Change Form -->
<div class="glass-card card-50">
    <h3 class="text-xl font-semibold mb-4">Passwort ändern</h3>

    <form action="{{ route('credentials.update.password') }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label for="current_password" class="block text-sm font-medium text-gray-700">Aktuelles Passwort</label>
            <input type="password" name="current_password" id="current_password"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('current_password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Neues Passwort</label>
            <input type="password" name="password" id="password"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Neues Passwort bestätigen</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition duration-200">
            Passwort ändern
        </button>
    </form>
</div>

@endsection