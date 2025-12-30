<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\EmailChangeVerification;
use Illuminate\Support\Facades\Auth;

class CredentialsController extends Controller
{
    public function edit()
    {
        return view('credentials.edit');
    }

    public function updateEmail(UpdateEmailRequest $request)
    {
        $user = $request->user();
        $newEmail = $request->email;

        // Token generieren
        $token = Str::random(64);
        $expiresAt = Carbon::now()->addMinutes(10);

        // In UserDetail speichern
        $user->userDetail->update([
            'pending_email' => $newEmail,
            'email_change_token' => $token,
            'email_change_expires_at' => $expiresAt,
            'email_change_requested_at' => Carbon::now(),
            'password_change_blocked' => true,
        ]);

        // Verifizierungs-Email senden
        Mail::to($newEmail)->send(new EmailChangeVerification($user, $token));

        return back()->with('success', 'Eine Verifizierungs-E-Mail wurde an die neue Adresse gesendet. Sie haben 10 Minuten Zeit, um den Link zu bestätigen.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        // Passwort ändern
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Ihr Passwort wurde erfolgreich geändert.');
    }

    public function verifyEmailChange(Request $request, $token)
    {
        $userDetail = \App\Models\UserDetail::where('email_change_token', $token)->first();

        if (!$userDetail) {
            return redirect('/profile/show')->with('error', 'Ungültiger oder abgelaufener Verifizierungslink.');
        }

        if (Carbon::now()->isAfter($userDetail->email_change_expires_at)) {
            // Token abgelaufen - zurücksetzen
            $userDetail->update([
                'pending_email' => null,
                'email_change_token' => null,
                'email_change_expires_at' => null,
                'email_change_requested_at' => null,
                'password_change_blocked' => false,
            ]);
            return redirect('/profile/show')->with('error', 'Der Verifizierungslink ist abgelaufen. Die E-Mail-Änderung wurde zurückgesetzt.');
        }

        // Email ändern
        $oldEmail = $userDetail->user->email;
        $userDetail->user->update(['email' => $userDetail->pending_email]);

        // Cleanup
        $userDetail->update([
            'pending_email' => null,
            'email_change_token' => null,
            'email_change_expires_at' => null,
            'email_change_requested_at' => null,
            'password_change_blocked' => false,
        ]);

        // User ausloggen
        Auth::logout();

        return redirect('/login')->with('success', 'Ihre E-Mail-Adresse wurde erfolgreich geändert. Bitte melden Sie sich mit der neuen E-Mail-Adresse an. Falls Sie keinen Zugriff auf die neue Adresse haben, können Sie sich weiterhin mit der alten Adresse (' . $oldEmail . ') anmelden.');
    }
}
