@extends('layouts.backend.main-layout-container.app')

@section('title', 'Benutzer Details')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Benutzer Details</h3>
                    <div class="space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Bearbeiten</a>
                        <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Zurück zur Liste</a>
                        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Zurück zur Übersicht</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Benutzerinformationen -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900 border-b pb-2">Benutzerinformationen</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Spitzname</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->nickname }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-Mail</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-Mail verifiziert</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Ja - {{ $user->email_verified_at->format('d.m.Y H:i') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Nein
                                    </span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Registriert am</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Zuletzt aktualisiert</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Benutzerdetails -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900 border-b pb-2">Benutzerdetails</h4>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rolle</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->userDetail->role === 'admin') bg-red-100 text-red-800
                                    @elseif($user->userDetail->role === 'floorman') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->userDetail->role === 'admin' ? 'Administrator' : ($user->userDetail->role === 'floorman' ? 'Floorman' : 'Spieler') }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Vorname</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->firstname ?: '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nachname</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->lastname ?: '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stadt</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->city ?: '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Land</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->country ?: '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Biografie</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->bio ?: '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Giants Card</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $user->userDetail->giants_card ?: '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Avatar -->
                @if($user->userDetail->getFirstMedia('avatar'))
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2 mb-4">Avatar</h4>
                    <div class="flex items-center space-x-4">
                        <img src="{{ $user->userDetail->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover">
                        <div>
                            <p class="text-sm text-gray-600">Avatar vorhanden</p>
                            <p class="text-xs text-gray-500">Hochgeladen am {{ $user->userDetail->getFirstMedia('avatar')->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2 mb-4">Avatar</h4>
                    <p class="text-sm text-gray-600">Kein Avatar vorhanden</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection