<?php namespace AppChat\Conversation\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AppChat\Conversation\Models\Conversation;
use AppUser\User\Models\User;
use Exception;

class ConversationController extends Controller
{
    public function searchUsers(Request $request, $email)
    {
        $authUser = $request->user; // Authenticated user

        if (!$authUser) {
            throw new Exception('User not authenticated', 401);
        }

        // Get the IDs of users with whom the authenticated user has conversations
        $conversationUserIds = Conversation::where('user_one_id', $authUser->id)
            ->orWhere('user_two_id', $authUser->id)
            ->get()
            ->flatMap(function ($conversation) {
                return [$conversation->user_one_id, $conversation->user_two_id];
            })
            ->unique()
            ->filter(fn($id) => $id != $authUser->id) // Remove the ID of the logged-in user
            ->values()
            ->toArray();

        // Search for a user by email who is not in conversation with the authenticated user
        $user = User::where('id', '!=', $authUser->id)
            ->whereNotIn('id', $conversationUserIds)
            ->where('email', $email)
            ->select('id', 'email')
            ->first();

        // If no user is found, return an error response
        if (!$user) {
            throw new Exception('User not found or is already in conversation with you', 404);
        }

        return[
            'data' => $user
        ];
    }

    // Function to start a new conversation
    public function startConversation(Request $request, $user_id)
    {
        $authUser = $request->user; // Authenticated user

        // Check if a conversation already exists between these users
        $otherUserId = $user_id;

        $existing = Conversation::where(function($q) use ($authUser, $otherUserId) {
            $q->where('user_one_id', $authUser->id)
            ->where('user_two_id', $otherUserId);
        })->orWhere(function($q) use ($authUser, $otherUserId) {
            $q->where('user_one_id', $otherUserId)
            ->where('user_two_id', $authUser->id);
        })->first();

        if ($existing) {
            // If the conversation already exists, return its ID
            throw new Exception('Conversation already exists', 403);
        }

        // Create a new conversation
        $conversation = new Conversation();
        $conversation->user_one_id = $authUser->id;
        $conversation->user_two_id = $otherUserId;
        $conversation->name = "Conversation"; // Default conversation name
        $conversation->save();

        $otherUser = User::find($otherUserId); // Retrieve the data of the other user

        // Return the data of the new conversation
        return[
            'conversation_id' => $conversation->id,
            'user' => [
                'id' => $otherUser->id,
                'email' => $otherUser->email
            ],
        ];
    }

    public function changeConversationName(Request $request, $conversation_id)
    {
        $authUser = $request->user; // Authenticated user
        $newName = $request->input('name'); // New conversation name

        // Validate the new name
        if (!$newName || trim($newName) === '') {
            throw new Exception('User not authenticated', 400);
        }

        $conversation = Conversation::find($conversation_id); // Find the conversation by ID

        // Check if the conversation exists
        if (!$conversation) {
            throw new Exception('Conversation name is required', 404);
        }

        // Check if the authenticated user is part of the conversation
        if ($conversation->user_one_id != $authUser->id && $conversation->user_two_id != $authUser->id) {
            throw new Exception('You are not participant in this conversation', 403);
        }

        $conversation->name = $newName; // Update the conversation name
        $conversation->save(); // Save the changes

        return[
                'conversation' => [
                    'id' => $conversation->id,
                    'name' => $conversation->name
                ]
        ];
    }
}
