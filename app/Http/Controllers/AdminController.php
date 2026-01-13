<?php

namespace App\Http\Controllers;

use App\Mail\NewUserCredentials;
use App\Models\Location;
use App\Models\Tournament;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $recentUsersPerPage = max(1, min((int) $request->query('per_page', 5), 50));

        $totalUsers = User::count();
        $recentUsers = User::with('userDetail')
            ->where('created_at', '>=', now()->subDays(7))
            ->latest('created_at')
            ->take($recentUsersPerPage)
            ->get();
        $adminUsersCount = UserDetail::where('role', 'admin')->count();
        $activeUsers24h = User::where('updated_at', '>=', now()->subDay())->count();
        $inactiveUsers = User::where('updated_at', '<', now()->subDays(30))->count();
        $usersWithAvatars = UserDetail::whereHas('media', function ($query) {
            $query->where('collection_name', 'avatar');
        })->count();
        $usersWithoutAvatars = max(0, $totalUsers - $usersWithAvatars);
        $verifiedEmails = User::whereNotNull('email_verified_at')->count();
        $unverifiedEmails = max(0, $totalUsers - $verifiedEmails);
        $usersWithProfile = UserDetail::where(function ($query) {
            $query->whereNotNull('firstname')
                ->orWhereNotNull('lastname')
                ->orWhereNotNull('country')
                ->orWhereNotNull('city')
                ->orWhereNotNull('bio');
        })->count();
        $usersWithoutProfile = max(0, $totalUsers - $usersWithProfile);
        $totalTournaments = Tournament::count();
        $upcomingTournaments = Tournament::upcoming()->count();
        $playedTournaments = Tournament::played()->count();
        $totalLocations = Location::count();
        $activeLocations = Location::active()->count();
        $upcomingTournamentList = Tournament::upcoming()
            ->with('location')
            ->orderBy('starts_at')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'recentUsers',
            'adminUsersCount',
            'activeUsers24h',
            'inactiveUsers',
            'usersWithAvatars',
            'usersWithoutAvatars',
            'verifiedEmails',
            'unverifiedEmails',
            'usersWithProfile',
            'usersWithoutProfile',
            'totalTournaments',
            'upcomingTournaments',
            'playedTournaments',
            'totalLocations',
            'activeLocations',
            'upcomingTournamentList'
        ));
    }

    public function users(Request $request)
    {
        $allowedRoles = ['player', 'floorman', 'admin'];
        $allowedStatuses = ['avatar', 'active', 'verified'];
        $perPage = (int) $request->query('per_page', 5);

        $requestedRoles = collect($request->query('roles', []))
            ->map(fn($role) => trim($role))
            ->filter(fn($role) => in_array($role, $allowedRoles, true))
            ->unique()
            ->values()
            ->all();

        $requestedStatuses = collect($request->query('statuses', []))
            ->map(fn($status) => trim($status))
            ->filter(fn($status) => in_array($status, $allowedStatuses, true))
            ->unique()
            ->values()
            ->all();

        $usersQuery = User::with('userDetail')
            ->when(!empty($requestedRoles), function ($query) use ($requestedRoles) {
                $query->whereHas('userDetail', function ($detailQuery) use ($requestedRoles) {
                    $detailQuery->whereIn('role', $requestedRoles);
                });
            })
            ->when(in_array('avatar', $requestedStatuses, true), function ($query) {
                $query->whereHas('userDetail', function ($detailQuery) {
                    $detailQuery->whereHas('media', function ($mediaQuery) {
                        $mediaQuery->where('collection_name', 'avatar');
                    });
                });
            })
            ->when(in_array('active', $requestedStatuses, true), function ($query) {
                $query->where('updated_at', '>=', now()->subDays(30));
            })
            ->when(in_array('verified', $requestedStatuses, true), function ($query) {
                $query->whereNotNull('email_verified_at');
            });

        $users = $usersQuery->paginate(max($perPage, 1))->withQueryString();
        $canCreateUsers = $this->currentUserIsAdmin();

        return view('admin.users.index', compact(
            'users',
            'canCreateUsers',
            'requestedRoles',
            'requestedStatuses',
            'perPage'
        ));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nickname' => 'required|string|max:255|unique:users,nickname,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:player,floorman,admin',
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'nickname' => $request->nickname,
            'email' => $request->email,
        ]);

        $user->userDetail->update([
            'role' => $request->role,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'country' => $request->country,
            'city' => $request->city,
            'bio' => $request->bio,
        ]);

        return redirect()->route('admin.users')->with('success', 'Benutzer erfolgreich aktualisiert.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Benutzer erfolgreich gelöscht.');
    }

    public function createUser()
    {
        $this->ensureAdmin();

        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'nickname' => 'required|string|max:255|unique:users,nickname',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|in:player,floorman,admin',
        ]);

        $password = $this->generateRandomPassword();

        $user = User::create([
            'nickname' => $validated['nickname'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
        ]);

        $user->email_verified_at = now();
        $user->save();

        $user->userDetail()->create([
            'role' => $validated['role'],
        ]);

        Mail::to($user->email)->send(new NewUserCredentials($user, $password));

        return redirect()->route('admin.users')->with('success', 'Benutzer erfolgreich erstellt und Zugangsdaten per E-Mail versendet.');
    }

    private function ensureAdmin(): void
    {
        abort_unless($this->currentUserIsAdmin(), 403);
    }

    private function currentUserIsAdmin(): bool
    {
        return optional(auth()->user()->userDetail)->role === 'admin';
    }

    private function generateRandomPassword(int $length = 10): string
    {
        $allowedChars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
        $maxIndex = strlen($allowedChars) - 1;
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $allowedChars[random_int(0, $maxIndex)];
        }

        return $password;
    }
}
