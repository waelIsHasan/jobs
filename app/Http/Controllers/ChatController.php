<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Events\NewMessageEvent;
use Illuminate\Support\Str;
use App\Traits\ResponseTrait;


class ChatController extends Controller
{

    use ResponseTrait;

    public function sendMessage(Request $request ,$receiverId , $receiverType){

        $validate = $request->validate([
            'message' => 'required'
        ]);
        $sender = auth()->user();
        $senderId = $sender->id;
        $senderType =class_basename(get_class($sender));
        $receiverType = Str::title($receiverType);

        $chatId = $this->getChatId($senderId , $senderType , $receiverId , $receiverType);

        $message = Message::create([
            'chat_id' => $chatId,
            'senderable_id' => $senderId,
            'senderable_type' =>$senderType,
            'message' => $validate['message'],
        ]);
        broadcast(new NewMessageEvent($message, $chatId))->toOthers();

        return $this->successResponse('has sent successfully',null);

    }

    public function getMessages($receiverId , $receiverType){

        $sender = auth()->user();
        $senderId = $sender->id;
        $senderType =class_basename(get_class($sender));
        $receiverType = Str::title($receiverType);
        $chatId = $this->getChatId($senderId , $senderType , $receiverId , $receiverType);
        $chat = Chat::find($chatId);
        $messages = $chat->messages;
        return $this->successResponse('chat'.''.$chatId , $messages);
    }


    private function getChatId($senderId, $senderType, $receiverId, $receiverType)
{
    $chat = Chat::where(function ($query) use ($senderId, $senderType, $receiverId, $receiverType) {
        $query->where('senderable_id', $senderId)
              ->where('senderable_type', $senderType)
              ->where('receiverable_id', $receiverId)
              ->where('receiverable_type', $receiverType);
    })->orWhere(function ($query) use ($senderId, $senderType, $receiverId, $receiverType) {
        $query->where('senderable_id', $receiverId)
              ->where('senderable_type', $receiverType)
              ->where('receiverable_id', $senderId)
              ->where('receiverable_type', $senderType);
    })->first();

    if ($chat) {
            return $chat->id;
    } else {
        // Create a new conversation
        $chat = Chat::create([
            'senderable_id' => $senderId,
            'senderable_type' => $senderType,
            'receiverable_id' => $receiverId,
            'receiverable_type' => $receiverType,
        ]);
        return $chat->id;
    }
}
}
