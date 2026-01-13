@extends('layouts.backend.main-layout-container.app')

@section('title', 'Edit User: ' . $user->nickname)

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form id="admin-user-form" method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')

                    @if(session('success'))
                    <div class="mb-6 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Benutzerinformationen</h3>

                            <div class="mb-4">
                                <label for="nickname" class="block text-sm font-medium text-gray-700">Spitzname</label>
                                <input type="text" name="nickname" id="nickname" value="{{ old('nickname', $user->nickname) }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('nickname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="role" class="block text-sm font-medium text-gray-700">Rolle</label>
                                <select name="role" id="role"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="player" {{ old('role', $user->userDetail->role ?? 'player') === 'player' ? 'selected' : '' }}>Spieler</option>
                                    <option value="floorman" {{ old('role', $user->userDetail->role ?? 'player') === 'floorman' ? 'selected' : '' }}>Floorman</option>
                                    <option value="admin" {{ old('role', $user->userDetail->role ?? 'player') === 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                                @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Abbrechen</a>
                        <button id="update-user-button" type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed" disabled>Benutzer aktualisieren</button>
                    </div>
                </form>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Neues Passwort vergeben</h3>
                    <p class="text-sm text-gray-600 mb-4">Der Benutzer erhält das neue Passwort per E-Mail und wird automatisch ausgeloggt (sofern er gerade eingeloggt ist).</p>
                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                        @csrf
                        <button type="submit" class="bg-yellow-500 text-gray-900 px-4 py-2 rounded-md hover:bg-yellow-400">Neues Passwort vergeben</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('admin-user-form');
        const submitButton = document.getElementById('update-user-button');

        if (!form || !submitButton) {
            return;
        }

        const fieldNames = ['nickname', 'email', 'role'];
        const initialValues = {};
        fieldNames.forEach((name) => {
            const field = form.querySelector(`[name="${name}"]`);
            initialValues[name] = field ? field.value : '';
        });

        const checkDirty = () => {
            const isDirty = fieldNames.some((name) => {
                const field = form.querySelector(`[name="${name}"]`);
                if (!field) {
                    return false;
                }
                return field.value !== initialValues[name];
            });

            submitButton.disabled = !isDirty;
        };

        fieldNames.forEach((name) => {
            ['input', 'change'].forEach((eventName) => {
                const field = form.querySelector(`[name="${name}"]`);
                if (field) {
                    field.addEventListener(eventName, checkDirty);
                }
            });
        });

        checkDirty();
    });
</script>
@endpush