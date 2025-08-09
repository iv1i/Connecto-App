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
        $user = auth()->user();

        return $room->messages()
            ->with(['user', 'userReactions' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($message) use ($user) {
                $message->user_reactions = $message->userReactions->pluck('reaction')->toArray();
                unset($message->userReactions); // Удаляем relations, чтобы не дублировать данные
                return $message;
            });
    }

    public function addReaction(Message $message, string $reaction, User $user): array
    {
        $validReactions = ['like', 'love', 'laugh', 'wow', 'sad', 'angry', 'fire', 'star', 'clap', 'rocket'];
        if (!in_array($reaction, $validReactions)) {
            return [
                'success' => false,
                'message' => 'Invalid reaction type'
            ];
        }

        // Проверяем, есть ли уже такая реакция от этого пользователя
        $existingReaction = $user->reactions()
            ->where('message_id', $message->id)
            ->where('reaction', $reaction)
            ->first();

        $reactions = $message->reactions ?? [];

        if ($existingReaction) {
            // Если реакция уже есть - удаляем ее
            $existingReaction->delete();

            // Уменьшаем счетчик
            if (isset($reactions[$reaction])) {
                $reactions[$reaction]--;
                if ($reactions[$reaction] <= 0) {
                    unset($reactions[$reaction]);
                }
            }
        } else {
            // Если реакции нет - добавляем
            $user->reactions()->create([
                'message_id' => $message->id,
                'reaction' => $reaction
            ]);

            // Увеличиваем счетчик
            $reactions[$reaction] = ($reactions[$reaction] ?? 0) + 1;
        }

        // Обновляем сообщение
        $message->reactions = $reactions;
        $message->save();
        $message->load(['user', 'userReactions']);

        ReactionEvent::dispatch($message);

        return [
            'success' => true,
            'reactions' => $reactions,
            'action' => $existingReaction ? 'removed' : 'added'
        ];
    }

    public function deleteReaction(Message $message, string $reaction, User $user)
    {
        // Удаляем реакцию пользователя
        $deleted = $user->reactions()
            ->where('message_id', $message->id)
            ->where('reaction', $reaction)
            ->delete();

        if ($deleted) {
            $reactions = $message->reactions ?? [];

            if (array_key_exists($reaction, $reactions)) {
                if ($reactions[$reaction] > 1) {
                    $reactions[$reaction]--;
                } else {
                    unset($reactions[$reaction]);
                }

                $message->reactions = $reactions;
                $message->save();
            }
        }

        $message->load(['user', 'userReactions']);
        ReactionEvent::dispatch($message);

        return [
            'success' => (bool)$deleted,
            'message' => $deleted ? 'Reaction removed' : 'Reaction not found',
            'reactions' => $message->reactions ?? [],
        ];
    }
}
