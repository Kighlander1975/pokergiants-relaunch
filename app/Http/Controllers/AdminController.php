<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalUsers = User::count();
        $perPage = $request->get('per_page', 5); // Default 5, kann 5, 10, 25, 50 sein
        $recentUsers = User::with('userDetail')->where('created_at', '>=', now()->subDays(14))->latest()->take($perPage)->get();
        $adminUsers = UserDetail::whereIn('role', ['admin', 'floorman'])->with('user')->get();

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
        $totalPages = \App\Models\Page::count();
        $publishedPages = \App\Models\Page::where('is_published', true)->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'recentUsers',
            'adminUsers',
            'activeUsers24h',
            'inactiveUsers',
            'usersWithAvatars',
            'usersWithoutAvatars',
            'verifiedEmails',
            'unverifiedEmails',
            'usersWithProfile',
            'usersWithoutProfile',
            'totalPages',
            'publishedPages'
        ));
    }

    public function users()
    {
        $users = User::with('userDetail')->paginate(20);
        return view('admin.users.index', compact('users'));
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

    // CMS Methods
    public function pages()
    {
        $pages = Page::paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function createPage()
    {
        return view('admin.pages.create');
    }

    public function storePage(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|unique:pages,slug',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        Page::create($request->all());

        return redirect()->route('admin.pages')->with('success', 'Seite erfolgreich erstellt.');
    }

    public function editPage(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function updatePage(Request $request, Page $page)
    {
        $request->validate([
            'slug' => 'required|string|unique:pages,slug,' . $page->id,
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $page->update($request->all());

        return redirect()->route('admin.pages')->with('success', 'Seite erfolgreich aktualisiert.');
    }

    public function deletePage(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages')->with('success', 'Seite erfolgreich gelöscht.');
    }
}
