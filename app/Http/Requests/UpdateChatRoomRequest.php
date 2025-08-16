<?php

namespace App\Http\Requests;

use App\Models\ChatRoom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChatRoomRequest extends FormRequest
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
        $room = $this->route('room');
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('chat_rooms', 'name')->ignore($room->id),
            ],
            'description' => 'nullable|string|max:500',
            'type' => 'sometimes|in:public,private',
        ];
    }
}
