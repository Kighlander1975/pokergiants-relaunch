@extends('layouts.frontend.main-layout-container.app')

@section('content-title')
<div class="home container glass-card">
    @if(session('completion_required'))
    <h1 class="text-center home-size"><x-suit type="heart" /> Profil vervollständigen<x-suit type="spade" /></h1>
    @else
    <h1 class="text-center home-size"><x-suit type="heart" /> Profil bearbeiten<x-suit type="spade" /></h1>
    @endif
</div>
@endsection

@section('content-body')
<div class="glass-card one-card one-card-50">
    @if(session('completion_required'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
        <p class="font-bold">Achtung!</p>
        <p>Bitte vervollständigen Sie Ihr Profil, um fortzufahren.</p>
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <!-- Nickname (from users table) -->
        <div class="my-auth-forms">
            <label for="nickname" class="block text-sm font-medium text-gray-700">Nickname</label>
            <input type="text" name="nickname" id="nickname"
                value="{{ old('nickname', $user->nickname) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required>
            @error('nickname')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Firstname -->
        <div class="my-auth-forms">
            <label for="firstname" class="block text-sm font-medium text-gray-700">Vorname</label>
            <input type="text" name="firstname" id="firstname"
                value="{{ old('firstname', $user->userDetail->firstname ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                @if(session('completion_required')) required @endif>
            @error('firstname')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lastname -->
        <div class="my-auth-forms">
            <label for="lastname" class="block text-sm font-medium text-gray-700">Nachname</label>
            <input type="text" name="lastname" id="lastname"
                value="{{ old('lastname', $user->userDetail->lastname ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                @if(session('completion_required')) required @endif>
            @error('lastname')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Street and Number -->
        <div class="my-auth-forms">
            <label for="street_number" class="block text-sm font-medium text-gray-700">Straße und Hausnummer</label>
            <input type="text" name="street_number" id="street_number"
                value="{{ old('street_number', $user->userDetail->street_number ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                @if(session('completion_required')) required @endif>
            @error('street_number')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- ZIP -->
        <div class="my-auth-forms">
            <label for="zip" class="block text-sm font-medium text-gray-700">PLZ</label>
            <input type="text" name="zip" id="zip"
                value="{{ old('zip', $user->userDetail->zip ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                @if(session('completion_required')) required @endif>
            @error('zip')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- City -->
        <div class="my-auth-forms">
            <label for="city" class="block text-sm font-medium text-gray-700">Stadt</label>
            <input type="text" name="city" id="city"
                value="{{ old('city', $user->userDetail->city ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                @if(session('completion_required')) required @endif>
            @error('city')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Country -->
        <div class="my-auth-forms">
            <label for="country" class="block text-sm font-medium text-gray-700">Land</label>
            <select name="country" id="country"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="DE" {{ old('country', $user->userDetail->country ?? 'DE') == 'DE' ? 'selected' : '' }}>Deutschland</option>
                <option value="AT" {{ old('country', $user->userDetail->country ?? 'DE') == 'AT' ? 'selected' : '' }}>Österreich</option>
                <option value="CH" {{ old('country', $user->userDetail->country ?? 'DE') == 'CH' ? 'selected' : '' }}>Schweiz</option>
                <option value="Other" {{ old('country', $user->userDetail->country ?? 'DE') == 'Other' ? 'selected' : '' }}>Andere</option>
            </select>
            @error('country')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Country Flag -->
        <div class="my-auth-forms">
            <label for="country_flag" class="block text-sm font-medium text-gray-700">Ländercode (z.B. de_DE, gb_SCT)</label>
            <input type="text" name="country_flag" id="country_flag"
                value="{{ old('country_flag', $user->userDetail->country_flag ?? 'de_DE') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="de_DE">
            @error('country_flag')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date of Birth -->
        <div class="my-auth-forms">
            <label for="dob" class="block text-sm font-medium text-gray-700">Geburtsdatum</label>
            <input type="date" name="dob" id="dob"
                value="{{ old('dob', $user->userDetail->dob ? $user->userDetail->dob->format('Y-m-d') : '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                @if(session('completion_required')) required @endif>
            @error('dob')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bio -->
        <div class="my-auth-forms">
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
@endsection