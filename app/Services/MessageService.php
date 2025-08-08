<?php

namespace App\Services;

use App\Events\MessageDellEvent;
use App\Events\MessageSentEvent;
use App\Events\ReactionEvent;
use App\Http\Requests\MessageRequest;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageService
{
    public function sendMessage(MessageRequest $request, User $user): Message
    {
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $message = Message::create($data);
        $msg = $message->load('user');
        MessageSentEvent::dispatch($msg);
        return $msg;
    }

    public function deleteMessage(Message $message): void
    {
        $msg = [
            'id' => $message->id,
            'chat_room_id' => $message->chat_room_id,
            'user' => [
                'id' => $message->user_id,
            ]
        ];
        MessageDellEvent::dispatch($msg);
        $message->delete();
    }

    public function getRoomMessages(ChatRoom $room): LengthAwarePaginator
    {
        return $room->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function addReaction(Message $message, string $reaction, User $user): array
    {
        $reactions = $message->reactions ?? [];
        $reactions[$reaction] = ($reactions[$reaction] ?? 0) + 1;
        $message->reactions = $reactions;
        $message->save();
        $message->load('user');
        $message['reaction'] = $reaction;
        ReactionEvent::dispatch($message);
        return [
            'success' => true,
            'reactions' => $reactions,
        ];
    }
}
