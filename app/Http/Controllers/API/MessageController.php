<?php

namespace App\Http\Controllers\API;

use App\Events\MessageDellEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private MessageService $messageService)
    {
    }

    public function index(ChatRoom $room): JsonResponse
    {
        $messages = $this->messageService->getRoomMessages($room);
        return response()->json($messages);
    }

    public function store(MessageRequest $request): JsonResponse
    {
        $message = $this->messageService->sendMessage($request, Auth::user());
        return response()->json($message, 201);
    }

    public function destroy(Message $message): JsonResponse
    {
        $this->messageService->deleteMessage($message);
        return response()->json(null, 204);
    }

    public function addReaction(Message $message, string $reaction): JsonResponse
    {
        $message = $this->messageService->addReaction($message, $reaction, Auth::user());

        return response()->json($message);
    }
}
