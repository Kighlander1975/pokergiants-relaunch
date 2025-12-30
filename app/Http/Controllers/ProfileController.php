<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile overview.
     */
    public function show(Request $request): View
    {
        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()->load('userDetail'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user()->load('userDetail');

        // Update user table fields (only nickname, since email is handled elsewhere)
        $user->fill($request->only(['nickname']));
        $user->save();

        // Update user_details table fields
        if ($user->userDetail) {
            $user->userDetail->update($request->only([
                'firstname',
                'lastname',
                'street_number',
                'zip',
                'city',
                'country',
                'country_flag',
                'dob',
                'bio'
            ]));
        } else {
            // Create userDetail if it doesn't exist
            $user->userDetail()->create($request->only([
                'firstname',
                'lastname',
                'street_number',
                'zip',
                'city',
                'country',
                'country_flag',
                'dob',
                'bio'
            ]));
        }

        // Check if this was a profile completion
        if (session('completion_required')) {
            // Clear the cache so it gets rechecked
            session()->forget('user_details_complete_' . $user->id);
            return Redirect::route('dashboard')->with('success', 'Profil erfolgreich vervollständigt!');
        }

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
