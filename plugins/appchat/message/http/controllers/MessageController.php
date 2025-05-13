<?php namespace AppChat\Message\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AppChat\Message\Models\Message;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $authUser = $request->user;
            $data = $request->only('conversation_id', 'content', 'reply_to_id', 'attachment');

            $message = new Message();
            $message->conversation_id = $data['conversation_id'];
            $message->user_id = $authUser->id;
            $message->content = $data['content'] ?? null;
            $message->reply_to_id = $data['reply_to_id'] !== '' ? $data['reply_to_id'] : null;

            if ($request->hasFile('attachment')) {
                $message->attachment = $request->file('attachment');
            }

            $message->save();

            return response()->json(['message' => 'Message sent successfully', 'data' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send message', 'details' => $e->getMessage()], 500);
        }
    }

}
