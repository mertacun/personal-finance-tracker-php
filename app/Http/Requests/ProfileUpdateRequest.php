<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user()->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}