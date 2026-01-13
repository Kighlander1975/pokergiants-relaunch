@extends('layouts.backend.main-layout-container.app')

@section('title', 'Dashboard')

@section('content-title')
@endsection

@section('content-body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <x-statistic-card bgColor="bg-blue-500" icon="users" label="Gesamt Benutzer" :value="$totalUsers" />

            <x-statistic-card bgColor="bg-green-500" icon="user-shield" label="Aktive Administratoren" :value="$adminUsersCount" />

            <x-statistic-card bgColor="bg-yellow-500" icon="chart-line" label="Neue Registrierungen" :value="$recentUsers->count()" />

            <x-statistic-card bgColor="bg-green-500" icon="bolt" label="Aktive Registrierungen (24h)" :value="$activeUsers24h" />

            <x-statistic-card bgColor="bg-gray-500" icon="shield-alt" label="Inaktive Benutzer (30+ Tage)" :value="$inactiveUsers" />

            <x-statistic-card bgColor="bg-purple-500" icon="user-circle" label="Benutzer mit Avatar" :value="$usersWithAvatars" />

            <x-statistic-card bgColor="bg-orange-500" icon="user" label="Benutzer ohne Avatar" :value="$usersWithoutAvatars" />

            <x-statistic-card bgColor="bg-blue-500" icon="envelope" label="Verifizierte E-Mails" :value="$verifiedEmails" />

            <x-statistic-card bgColor="bg-red-500" icon="envelope-open" label="Nicht verifizierte E-Mails" :value="$unverifiedEmails" />

            <x-statistic-card bgColor="bg-indigo-500" icon="file-alt" label="Benutzer mit Profil" :value="$usersWithProfile" />

            <x-statistic-card bgColor="bg-teal-500" icon="file" label="Benutzer ohne Profil" :value="$usersWithoutProfile" />

            <x-statistic-card bgColor="bg-lime-500" icon="map-marked-alt" label="Anzahl Spielstätten" :value="$totalLocations" />

            <x-statistic-card bgColor="bg-emerald-500" icon="map-pin" label="Aktive Spielstätten" :value="$activeLocations" />

            <x-statistic-card bgColor="bg-cyan-500" icon="trophy" label="Alle Turniere" :value="$totalTournaments" />
            <x-statistic-card bgColor="bg-indigo-500" icon="calendar-plus" label="Kommende Turniere" :value="$upcomingTournaments" />
            <x-statistic-card bgColor="bg-purple-500" icon="calendar-check" label="Gespielte Turniere" :value="$playedTournaments" />

        </div>

        <!-- Recent Users -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Neue Benutzer</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <label for="per_page" class="text-sm text-gray-600">Anzeigen:</label>
                            <select id="per_page" name="per_page" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page', 5) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 5) == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <a href="{{ route('admin.users') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Alle Benutzer anzeigen</a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spitzname</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-Mail</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rolle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registriert</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentUsers as $user)
                            <tr class="hover:bg-indigo-50 cursor-pointer transition-colors duration-150" data-href="{{ route('admin.users.show', $user) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <!-- E-Mail Verifizierung -->
                                        @if($user->email_verified_at)
                                        <x-icon name="envelope" class="w-5 h-5 text-blue-500" />
                                        @else
                                        <x-icon name="envelope-open" class="w-5 h-5 text-gray-400" />
                                        @endif

                                        <!-- Avatar Status -->
                                        @if($user->userDetail->getFirstMedia('avatar'))
                                        <x-icon name="user-circle" class="w-5 h-5 text-purple-500" />
                                        @else
                                        <x-icon name="user-circle" type="far" class="w-5 h-5 text-purple-500" />
                                        @endif

                                        <!-- Aktivität Status (30 Tage) -->
                                        @if($user->updated_at >= now()->subDays(30))
                                        <x-icon name="shield-alt" class="w-5 h-5 text-green-500" />
                                        @else
                                        <x-icon name="shield-alt" class="w-5 h-5 text-gray-400" />
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->nickname }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->userDetail->role ?? 'player' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d.m.Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Status Legend -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Status-Symbole Legende</h4>
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-2 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-icon name="envelope" class="w-5 h-5 text-blue-500" />
                            <span class="text-gray-700">E-Mail verifiziert</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-icon name="envelope-open" class="w-5 h-5 text-gray-400" />
                            <span class="text-gray-700">E-Mail nicht verifiziert</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-icon name="user-circle" class="w-5 h-5 text-purple-500" />
                            <span class="text-gray-700">Avatar vorhanden</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-icon name="user-circle" type="far" class="w-5 h-5 text-purple-500" />
                            <span class="text-gray-700">Kein Avatar</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-icon name="shield-alt" class="w-5 h-5 text-green-500" />
                            <span class="text-gray-700">Aktiv (letzte 30 Tage)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-icon name="shield-alt" class="w-5 h-5 text-gray-400" />
                            <span class="text-gray-700">Inaktiv (über 30 Tage)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Tournaments -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Kommende Turniere</h3>
                    <a href="{{ route('admin.tournaments.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Alle Turniere</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingTournamentList as $upcoming)
                    <div class="border border-gray-200 rounded-lg p-4 space-y-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ $upcoming->name }}</p>
                            <span class="text-xs text-gray-500">{{ $upcoming->starts_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $upcoming->location->name ?? 'Unbekannte Spielstätte' }}</p>
                        <p class="text-xs text-gray-500">
                            Anmeldung: <span class="font-semibold">{{ $upcoming->is_registration_open ? 'freigegeben' : 'geschlossen' }}</span>
                        </p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Keine kommenden Turniere verfügbar.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('tr[data-href]').forEach((row) => {
        row.addEventListener('click', () => {
            window.location.href = row.dataset.href;
        });
    });

    const perPageSelect = document.getElementById('per_page');
    perPageSelect?.addEventListener('change', function() {
        const perPage = this.value;
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        window.location.href = url.toString();
    });
</script>
@endsection