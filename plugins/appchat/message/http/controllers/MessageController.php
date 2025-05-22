<?php namespace AppChat\Message\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AppChat\Message\Models\Message;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $authUser = $request->user; // Authenticated user
            $data = $request->post(); // Retrieve data from the request

            // Create a new Message
            $message = new Message();
            $message->conversation_id = $data['conversation_id']; // Set the conversation ID
            $message->user_id = $authUser->id; // Associate the message with the authenticated user
            $message->content = $data['content'] ?? null; // Set the content of the message
            $message->reply_to_id = $data['reply_to_id'] !== '' ? $data['reply_to_id'] : null; // Handle optional reply_to_id

            // Check if an attachment file is included in the request
            if ($request->hasFile('attachment')) {
                $message->attachment = $request->file('attachment'); // Assign the uploaded file to the message
            }

            // Save the message to the database
            $message->save();

            // Return a success response with the saved message data
            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfully',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Failed to send message');
        }
    }

    public function getMessages(Request $request, $conversation_id)
    {
        try {
            // Fetch messages for the given conversation ID
            $messages = Message::with([
            'user:id,email', // Include user details
            'reaction', // Include reactions associated with the message
            'reply_to', // Include the message being replied to, if any
            'attachment' // Include any attachments associated with the message
            ])
            ->where('conversation_id', $conversation_id) // Filter messages by conversation ID
            ->orderBy('created_at', 'asc') // Order messages by creation time in ascending order
            ->get(); // Retrieve the messages

            // Return a success response with the fetched messages
            return response()->json([
                'status' => 'success',
                'message' => 'Messages fetched successfully',
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Failed to fetch messages');
        }
    }

    private function handleException(\Exception $e, $defaultMessage)
    {
        return response()->json([
            'status' => 'error',
            'error' => $defaultMessage,
            'details' => $e->getMessage()
        ], 500);
    }
}
