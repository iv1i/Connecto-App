<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'actions' => $this->actions,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'invite_code' => $this->when($this->invite_code !== null, $this->invite_code),
            'messages_count' => $this->whenCounted('messages'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pivot' => $this->when($this->pivot, function () {
                return [
                    'user_id' => $this->pivot->user_id,
                    'chat_room_id' => $this->pivot->chat_room_id,
                    'joined_via' => $this->pivot->joined_via,
                ];
            }),
        ];
    }
}
