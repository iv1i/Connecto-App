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
    public function sendMessage(MessageRequest $request, User $user): Message|array
    {
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $chatRoom = ChatRoom::query()->find($data['chat_room_id']);
        $check = $chatRoom->members()->where('users.id', $user->id)->exists();

        if (!$check) {
            return ['error' => 'user not joined on chat room'];
        }

        $message = Message::query()->create($data);
        $msg = $message->load('user');

        MessageSentEvent::dispatch($msg);

        return $msg;
    }

    public function deleteMessage(Message $message): array
    {
        if ($message->user_id === auth()->user()->id || auth()->user()->isAdmin()) {
            $msg = [
                'id' => $message->id,
                'chat_room_id' => $message->chat_room_id,
                'user' => [
                    'id' => $message->user_id,
                ]
            ];

            MessageDellEvent::dispatch($msg);
            $message->delete();

            return ['message' => 'deleted'];
        }

        return ['message' => 'not access'];
    }

    public function getRoomMessages(ChatRoom $room): LengthAwarePaginator|array
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;

        $check = $room->isMember($authUserId);

        if (!$check) {
            return ['error' => 'user not joined on chat room'];
        }

        return $room->messages()
            ->with(['user', 'userReactions' => function($query) use ($authUser) {
                $query->where('user_id', $authUser->id);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($message) use ($authUser) {
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

        // Проверяем существующую реакцию
        $existingReaction = $user->reactions()
            ->where('message_id', $message->id)
            ->where('reaction', $reaction)
            ->first();

        $reactions = $message->reactions ?? [];

        if ($existingReaction) {
            // Удаляем реакцию
            $existingReaction->delete();

            if (isset($reactions[$reaction])) {
                $reactions[$reaction]--;
                if ($reactions[$reaction] <= 0) {
                    unset($reactions[$reaction]);
                }
            }
        } else {
            // Добавляем реакцию
            $user->reactions()->create([
                'message_id' => $message->id,
                'reaction' => $reaction
            ]);

            $reactions[$reaction] = ($reactions[$reaction] ?? 0) + 1;
        }

        // Обновляем сообщение
        $message->reactions = $reactions;
        $message->save();

        // Загружаем свежие данные с пользовательскими реакциями
        $message->load(['user', 'userReactions' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }]);

        // Добавляем user_reactions в данные сообщения
        $message->user_reactions = $message->userReactions->pluck('reaction')->toArray();

        ReactionEvent::dispatch($message->fresh());

        return [
            'success' => true,
            'reactions' => $reactions,
            'user_reactions' => $message->user_reactions,
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
