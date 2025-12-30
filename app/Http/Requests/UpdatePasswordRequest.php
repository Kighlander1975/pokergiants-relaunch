<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    // Prüfen ob PW-Änderung blockiert ist wegen pending Email
                    if ($this->user()->userDetail->password_change_blocked) {
                        $fail('Passwort-Änderungen sind derzeit nicht möglich, da eine E-Mail-Änderung aussteht.');
                    }

                    // Prüfen ob neues PW gleich altem ist
                    if (\Hash::check($value, $this->user()->password)) {
                        $fail('Das neue Passwort darf nicht mit dem aktuellen Passwort übereinstimmen.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Das aktuelle Passwort ist erforderlich.',
            'current_password.current_password' => 'Das aktuelle Passwort ist falsch.',
            'password.required' => 'Das neue Passwort ist erforderlich.',
            'password.min' => 'Das neue Passwort muss mindestens 8 Zeichen lang sein.',
            'password.confirmed' => 'Die Passwort-Bestätigung stimmt nicht überein.',
        ];
    }
}
