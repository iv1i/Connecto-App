<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'name_color' => $this->name_color,
            'link_name' => $this->link_name,
            'email_verified_at' => $this->email_verified_at,
            'is_blocked' => $this->is_blocked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pivot' => $this->when($this->pivot, function () {
                return [
                    'user_id' => $this->pivot->user_id,
                    'friend_id' => $this->pivot->friend_id,
                    'status' => $this->pivot->status,
                ];
            }),
        ];
    }
}
