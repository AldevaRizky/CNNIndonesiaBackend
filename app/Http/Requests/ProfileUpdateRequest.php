<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'jabatan' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'alamat' => ['nullable','string','max:512'],
            'bio' => ['nullable','string'],
            'website' => ['nullable','url','max:255'],
            'twitter' => ['nullable','url','max:255'],
            'facebook' => ['nullable','url','max:255'],
            'instagram' => ['nullable','url','max:255'],
            'dob' => ['nullable','date'],
            'gender' => ['nullable','in:male,female,other'],
            'country' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:255'],
            'state' => ['nullable','string','max:255'],
            'zip' => ['nullable','string','max:30'],
            'address_line' => ['nullable','string','max:512'],
            'profile' => ['nullable','image','mimes:jpeg,png,jpg,gif,webp','max:10240'],
            'password' => ['nullable','confirmed', Password::min(8)],
            'current_password' => ['nullable','current_password'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
        ];
    }
}
