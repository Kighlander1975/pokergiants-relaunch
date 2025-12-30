<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailRequest extends FormRequest
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
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $this->user()->id,
                function ($attribute, $value, $fail) {
                    // Prüfen ob bereits eine pending Änderung läuft
                    if ($this->user()->userDetail->pending_email) {
                        $fail('Es läuft bereits eine E-Mail-Änderung. Bitte warten Sie, bis diese bestätigt oder abgelaufen ist.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Die E-Mail-Adresse ist erforderlich.',
            'email.email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein.',
            'email.unique' => 'Diese E-Mail-Adresse wird bereits verwendet.',
        ];
    }
}
