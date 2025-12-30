<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => ['required', 'string', 'max:255'],
            'firstname' => ['required_if:completion_required,true', 'string', 'max:255'],
            'lastname' => ['required_if:completion_required,true', 'string', 'max:255'],
            'street_number' => ['required_if:completion_required,true', 'string', 'max:255'],
            'zip' => ['required_if:completion_required,true', 'string', 'max:10'],
            'city' => ['required_if:completion_required,true', 'string', 'max:255'],
            'country' => ['string', 'in:DE,AT,CH,Other'],
            'country_flag' => ['string', 'max:5', 'regex:/^[a-z]{2}_[A-Z]{2}$/'],
            'dob' => ['required_if:completion_required,true', 'date', 'before:today'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
