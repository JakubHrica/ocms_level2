<?php namespace AppChat\Conversation\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AppChat\Conversation\Models\Conversation;
use AppUser\User\Models\User;
use Exception;

class ConversationController extends Controller
{
    // Function to search for a user by email
    public function searchUsers(Request $request, $email)
    {
        try {
            $authUser = $request->user; // Authenticated user

            if (!$authUser) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
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

            if (!$user) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'User not found or is already in conversation with you'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User fetched successfully',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to fetch user');
        }
    }

    // Function to start a new conversation
    public function startConversation(Request $request, $user_id)
    {
        try {
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
                return response()->json([
                    'status' => 'exists',
                    'message' => 'Conversation already exists',
                    'id' => $existing->id
                ]);
            }

            // Create a new conversation
            $conversation = new Conversation();
            $conversation->user_one_id = $authUser->id;
            $conversation->user_two_id = $otherUserId;
            $conversation->name = "Conversation"; // Default conversation name
            $conversation->save();

            $otherUser = User::find($otherUserId); // Retrieve the data of the other user

            // Return the data of the new conversation
            return response()->json([
                'status' => 'success',
                'message' => 'Conversation started successfully',
                'conversation_id' => $conversation->id,
                'user' => [
                    'id' => $otherUser->id,
                    'email' => $otherUser->email
                ],
            ]);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    public function changeConversationName(Request $request, $conversation_id)
    {
        try {
            $authUser = $request->user;
            $newName = $request->input('name');

            if (!$newName || trim($newName) === '') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Conversation name is required'
                ], 400);
            }

            $conversation = Conversation::find($conversation_id);

            if (!$conversation) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Conversation not found'
                ], 404);
            }

            // Check if the authenticated user is part of the conversation
            if ($conversation->user_one_id != $authUser->id && $conversation->user_two_id != $authUser->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not a participant in this conversation'
                ], 403);
            }

            $conversation->name = $newName;
            $conversation->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Conversation name updated successfully',
                'conversation' => [
                    'id' => $conversation->id,
                    'name' => $conversation->name
                ]
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to update conversation name');
        }
    }

    // Function for error handling
    private function handleException(Exception $e, $message = 'An error occurred')
    {
        return response()->json([
            'error' => $message,
            'message' => $e->getMessage()
        ], 500);
    }
}
