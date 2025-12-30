<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    /**
     * Show the profile completion form.
     */
    public function show(): View
    {
        $user = Auth::user();
        return view('profile.complete', compact('user'));
    }

    /**
     * Update the user's profile details.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'street_number' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
        ]);

        $user = Auth::user();
        $user->userDetail->update($request->only([
            'firstname',
            'lastname',
            'street_number',
            'zip',
            'city',
            'dob',
        ]));

        // Clear the cache so it gets rechecked
        session()->forget('user_details_complete_' . $user->id);

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Profil erfolgreich vervollständigt!');
    }
}
