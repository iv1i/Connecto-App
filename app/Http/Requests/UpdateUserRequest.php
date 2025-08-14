<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'current_password' => 'required_if:password,!null|string',
            'role' => 'sometimes|in:user',
            'is_blocked' => 'sometimes|boolean',
            'name_color' => 'sometimes|nullable|string|max:7',
        ];
    }
}
