@extends('layouts.backend.main-layout-container.app')

@section('title', 'User Management')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Alle Benutzer</h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Zurück zur Übersicht</a>
                        <a href="{{ route('admin.users.create') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-150 {{ $canCreateUsers ? '' : 'opacity-50 cursor-not-allowed pointer-events-none' }}"
                            aria-disabled="{{ $canCreateUsers ? 'false' : 'true' }}"
                            {{ $canCreateUsers ? '' : 'tabindex="-1"' }}>
                            Neuen Benutzer anlegen
                        </a>
                    </div>
                </div>

                @php
                $roleFilters = $requestedRoles ?? [];
                $statusFilters = $requestedStatuses ?? [];
                $filterButtonBase = 'px-4 py-2 text-sm font-semibold rounded-md border transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2';
                $activeFilterClass = 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500';
                $inactiveFilterClass = 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-gray-500';
                @endphp

                @php
                $hasFilters = !empty($roleFilters) || !empty($statusFilters);
                @endphp

                <div class="flex flex-wrap gap-3 mb-4 items-center">
                    <button type="button" data-reset-filters class="{{ $filterButtonBase }} {{ $hasFilters ? $inactiveFilterClass : $activeFilterClass }}">
                        Alle Benutzer
                    </button>
                    <div class="flex flex-wrap gap-3 items-center">
                        <label class="inline-flex items-center gap-1 text-sm text-gray-700">
                            <input type="checkbox" data-filter-type="role" value="player" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array('player', $roleFilters) ? 'checked' : '' }}>
                            <span>Alle Spieler</span>
                        </label>
                        <label class="inline-flex items-center gap-1 text-sm text-gray-700">
                            <input type="checkbox" data-filter-type="role" value="floorman" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array('floorman', $roleFilters) ? 'checked' : '' }}>
                            <span>Alle Floormans</span>
                        </label>
                        <label class="inline-flex items-center gap-1 text-sm text-gray-700">
                            <input type="checkbox" data-filter-type="role" value="admin" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array('admin', $roleFilters) ? 'checked' : '' }}>
                            <span>Alle Admins</span>
                        </label>
                        <label class="inline-flex items-center gap-1 text-sm text-gray-700">
                            <input type="checkbox" data-filter-type="status" value="avatar" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array('avatar', $statusFilters) ? 'checked' : '' }}>
                            <span>Mit Avatar</span>
                        </label>
                        <label class="inline-flex items-center gap-1 text-sm text-gray-700">
                            <input type="checkbox" data-filter-type="status" value="active" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array('active', $statusFilters) ? 'checked' : '' }}>
                            <span>Aktiv (30 Tage)</span>
                        </label>
                        <label class="inline-flex items-center gap-1 text-sm text-gray-700">
                            <input type="checkbox" data-filter-type="status" value="verified" class="form-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" {{ in_array('verified', $statusFilters) ? 'checked' : '' }}>
                            <span>Verifizierte Nutzer</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end mb-4 items-center">
                    <label for="users-per-page" class="text-sm text-gray-600 mr-2">Anzeigen:</label>
                    <select id="users-per-page" class="border border-gray-300 rounded-md px-4 py-1 pr-10 text-sm bg-white appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spitzname</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-Mail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rolle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registriert</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    @if($user->email_verified_at)
                                    <x-icon name="envelope" class="w-5 h-5 text-blue-500" />
                                    @else
                                    <x-icon name="envelope-open" class="w-5 h-5 text-gray-400" />
                                    @endif

                                    @if($user->userDetail->getFirstMedia('avatar'))
                                    <x-icon name="user-circle" class="w-5 h-5 text-purple-500" />
                                    @else
                                    <x-icon name="user-circle" type="far" class="w-5 h-5 text-purple-500" />
                                    @endif

                                    @if($user->updated_at >= now()->subDays(30))
                                    <x-icon name="shield-alt" class="w-5 h-5 text-green-500" />
                                    @else
                                    <x-icon name="shield-alt" class="w-5 h-5 text-gray-400" />
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->nickname }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($user->userDetail->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->userDetail->role === 'floorman') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                    {{ $user->userDetail->role === 'admin' ? 'Administrator' : ($user->userDetail->role === 'floorman' ? 'Floorman' : 'Spieler') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">Bearbeiten</a>
                                <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="inline" onsubmit="return confirm('Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Löschen</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Status-Symbole Legende</h4>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-2 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <x-icon name="envelope" class="w-5 h-5 text-blue-500" />
                        <span>E-Mail verifiziert</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="envelope-open" class="w-5 h-5 text-gray-400" />
                        <span>E-Mail nicht verifiziert</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="user-circle" class="w-5 h-5 text-purple-500" />
                        <span>Avatar vorhanden</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="user-circle" type="far" class="w-5 h-5 text-purple-500" />
                        <span>Kein Avatar</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="shield-alt" class="w-5 h-5 text-green-500" />
                        <span>Aktiv (letzte 30 Tage)</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-icon name="shield-alt" class="w-5 h-5 text-gray-400" />
                        <span>Inaktiv (über 30 Tage)</span>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const perPageSelect = document.getElementById('users-per-page');
        const filterInputs = document.querySelectorAll('[data-filter-type]');
        const resetFiltersButton = document.querySelector('[data-reset-filters]');

        function buildUrl(roles, statuses, perPage) {
            const params = new URLSearchParams();
            if (perPage) {
                params.set('per_page', perPage);
            }
            roles.forEach((role) => params.append('roles[]', role));
            statuses.forEach((status) => params.append('statuses[]', status));

            const queryString = params.toString();
            return window.location.pathname + (queryString ? `?${queryString}` : '');
        }

        function gatherFilters() {
            const roles = new Set();
            const statuses = new Set();

            filterInputs.forEach((input) => {
                if (!input.checked) {
                    return;
                }

                if (input.dataset.filterType === 'role') {
                    roles.add(input.value);
                } else if (input.dataset.filterType === 'status') {
                    statuses.add(input.value);
                }
            });

            return {
                roles,
                statuses
            };
        }

        function applyFilters({
            roles,
            statuses,
            perPage
        }) {
            window.location.href = buildUrl(roles, statuses, perPage);
        }

        function shouldShowInactiveReset(roles, statuses) {
            return roles.size > 0 || statuses.size > 0;
        }

        function updateResetButtonState(roles, statuses) {
            if (!resetFiltersButton) {
                return;
            }

            const activeClasses = ['bg-indigo-600', 'text-white', 'border-indigo-600', 'hover:bg-indigo-700', 'focus:ring-indigo-500'];
            const inactiveClasses = ['bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-50', 'focus:ring-gray-500'];
            const isActive = !shouldShowInactiveReset(roles, statuses);

            activeClasses.forEach((className) => resetFiltersButton.classList.toggle(className, isActive));
            inactiveClasses.forEach((className) => resetFiltersButton.classList.toggle(className, !isActive));
        }

        filterInputs.forEach((input) => {
            input.addEventListener('change', () => {
                const filters = gatherFilters();
                const perPage = perPageSelect?.value || '';
                updateResetButtonState(filters.roles, filters.statuses);
                applyFilters({
                    roles: filters.roles,
                    statuses: filters.statuses,
                    perPage
                });
            });
        });

        resetFiltersButton?.addEventListener('click', () => {
            filterInputs.forEach((input) => {
                input.checked = false;
            });

            const values = gatherFilters();
            const perPage = perPageSelect?.value || '';
            updateResetButtonState(values.roles, values.statuses);
            applyFilters({
                roles: values.roles,
                statuses: values.statuses,
                perPage
            });
        });

        perPageSelect?.addEventListener('change', () => {
            const filters = gatherFilters();
            const perPage = perPageSelect.value;
            applyFilters({
                roles: filters.roles,
                statuses: filters.statuses,
                perPage
            });
        });
    });
</script>
@endsection