<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:chat_rooms,name',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:public,private',
        ];
    }
}
