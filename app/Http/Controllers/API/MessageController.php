<?php

namespace App\Http\Controllers\API;

use App\Events\MessageDellEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessagesResource;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private MessageService $messageService)
    {
    }

    public function index(ChatRoom $room): JsonResponse|AnonymousResourceCollection
    {
        $messages = $this->messageService->getRoomMessages($room);

        if ($messages['error']) {
            return response()->json($messages);
        }
        //return response()->json($messages);

        return MessagesResource::collection($messages);
    }

    public function store(MessageRequest $request): JsonResponse|MessagesResource
    {
        $message = $this->messageService->sendMessage($request, Auth::user());

        if ($message['error']) {
            return response()->json($message);
        }

        return new MessagesResource($message);
    }

    public function destroy(Message $message): JsonResponse
    {
        $resp = $this->messageService->deleteMessage($message);

        return response()->json($resp);
    }

    public function addReaction(Message $message, string $reaction): JsonResponse
    {
        $message = $this->messageService->addReaction($message, $reaction, Auth::user());

        return response()->json($message);
    }

    public function delReaction(Message $message, string $reaction): JsonResponse
    {
        $message = $this->messageService->deleteReaction($message, $reaction, Auth::user());

        return response()->json($message);
    }
}
