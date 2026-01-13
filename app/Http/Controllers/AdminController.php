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
        $totalUsers = User::count();
        $perPage = $request->get('per_page', 5); // Default 5, kann 5, 10, 25, 50 sein
        $recentUsers = User::with('userDetail')->where('created_at', '>=', now()->subDays(14))->latest()->take($perPage)->get();
        $adminUsersCount = UserDetail::whereIn('role', ['admin', 'floorman'])->count();
        // Neue Statistiken
        $activeUsers24h = User::where('created_at', '>=', now()->subDay())->count();
        $inactiveUsers = User::where('created_at', '<', now()->subDays(30))->count();
        $usersWithAvatars = UserDetail::whereHas('media', function ($query) {
            $query->where('collection_name', 'avatar');
        })->count();
        $usersWithoutAvatars = $totalUsers - $usersWithAvatars;
        $verifiedEmails = User::whereNotNull('email_verified_at')->count();
        $unverifiedEmails = $totalUsers - $verifiedEmails;
        $usersWithProfile = UserDetail::whereNotNull('firstname')->orWhereNotNull('lastname')->orWhereNotNull('city')->count();
        $usersWithoutProfile = $totalUsers - $usersWithProfile;
        $totalTournaments = Tournament::count();
        $upcomingTournaments = Tournament::upcoming()->count();
        $playedTournaments = Tournament::played()->count();
        $totalLocations = Location::count();
        $activeLocations = Location::where('is_active', true)->count();
        $upcomingTournamentList = Tournament::upcoming()->with('location')->orderBy('starts_at')->take(4)->get();

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

    public function users()
    {
        $users = User::with('userDetail')->paginate(20);
        $canCreateUsers = $this->currentUserIsAdmin();

        return view('admin.users.index', compact('users', 'canCreateUsers'));
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
