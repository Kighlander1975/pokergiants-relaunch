<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailRequest extends EmailVerificationRequest
{
    private ?\Illuminate\Contracts\Auth\MustVerifyEmail $verifiedUser = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = \App\Models\User::find($this->route('id'));
        if (! $user) {
            return false;
        }

        $this->verifiedUser = $user;

        if (! hash_equals((string) $user->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the user that should be verified.
     */
    public function user($guard = null): \Illuminate\Contracts\Auth\MustVerifyEmail
    {
        return $this->verifiedUser ?: parent::user($guard);
    }
}
