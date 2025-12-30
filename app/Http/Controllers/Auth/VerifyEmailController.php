<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyEmailRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(VerifyEmailRequest $request): RedirectResponse|View
    {
        $user = \App\Models\User::find($request->route('id'));
        if (! $user) {
            return view('layouts.frontend.pages.verify-error', ['error' => 'User not found.']);
        }

        try {
            if ($user->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
            }

            $user->markEmailAsVerified();
            event(new Verified($user));

            // Auth::logout(); // Temporarily remove logout

            return view('layouts.frontend.pages.verify-success');
        } catch (\Exception $e) {
            return view('layouts.frontend.pages.verify-error', ['error' => $e->getMessage()]);
        }
    }
}
