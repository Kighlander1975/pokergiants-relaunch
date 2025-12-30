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
            <label for="country_flag" class="block text-sm font-medium text-gray-700">Ländercode</label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <input type="text" name="country_flag" id="country_flag"
                    value="{{ old('country_flag', $user->userDetail->country_flag ?? 'de_DE') }}"
                    class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="de_DE, gb_SCT, us_US, etc.">
                <button type="button" id="openFlagModal"
                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-50 rounded-r-md text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                    </svg>
                    Flagge wählen
                </button>
            </div>
            <p class="mt-1 text-sm text-gray-500">Wählen Sie Ihre Flagge aus oder geben Sie den Code manuell ein (z.B. de_DE für Deutschland, gb_SCT für Schottland)</p>
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

<!-- Flag Selection Modal -->
<div id="flagModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Flagge auswählen</h3>
                <button type="button" id="closeFlagModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <input type="text" id="flagSearch" placeholder="Nach Land suchen..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div id="flagGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-2 max-h-96 overflow-y-auto">
                @php
                $flags = getAvailableFlags();
                $currentFlag = old('country_flag', $user->userDetail->country_flag ?? 'de_DE');
                @endphp
                @foreach($flags as $code => $name)
                <button type="button" class="flag-option w-36 h-30 flex flex-col items-center justify-center p-2 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition duration-200 {{ $code === $currentFlag ? 'bg-indigo-100' : '' }}"
                    data-code="{{ $code }}" data-name="{{ $name }}">
                    <span class="fi fi-{{ getFlagCode($code) }} text-2xl mb-0.5"></span>
                    <span class="text-xs text-center text-gray-700 leading-tight">{{ $name }}</span>
                </button>
                @endforeach
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" id="cancelFlagModal"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 mr-2">
                    Abbrechen
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('flagModal');
        const openBtn = document.getElementById('openFlagModal');
        const closeBtn = document.getElementById('closeFlagModal');
        const cancelBtn = document.getElementById('cancelFlagModal');
        const flagInput = document.getElementById('country_flag');
        const searchInput = document.getElementById('flagSearch');
        const flagGrid = document.getElementById('flagGrid');
        const flagOptions = document.querySelectorAll('.flag-option');

        // Modal öffnen
        openBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
            searchInput.focus();
            highlightCurrentFlag();
        });

        // Modal schließen
        function closeModal() {
            modal.classList.add('hidden');
            searchInput.value = '';
            filterFlags('');
        }

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Schließen bei Klick außerhalb des Modals
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Flagge auswählen
        flagOptions.forEach(option => {
            option.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                flagInput.value = code;
                updateActiveFlag(this);
                closeModal();
            });
        });

        // Suchfunktion
        searchInput.addEventListener('input', function() {
            filterFlags(this.value);
        });

        function filterFlags(searchTerm) {
            const term = searchTerm.toLowerCase();
            flagOptions.forEach(option => {
                const name = option.getAttribute('data-name').toLowerCase();
                const code = option.getAttribute('data-code').toLowerCase();
                if (name.includes(term) || code.includes(term)) {
                    option.style.display = 'flex';
                } else {
                    option.style.display = 'none';
                }
            });
        }

        function highlightCurrentFlag() {
            const currentCode = flagInput.value;
            flagOptions.forEach(option => {
                if (option.getAttribute('data-code') === currentCode) {
                    option.classList.add('bg-indigo-100');
                } else {
                    option.classList.remove('bg-indigo-100');
                }
            });
        }

        function updateActiveFlag(selectedOption) {
            flagOptions.forEach(option => {
                option.classList.remove('bg-indigo-100');
            });
            selectedOption.classList.add('bg-indigo-100');
        }
    });
</script>

@endsection