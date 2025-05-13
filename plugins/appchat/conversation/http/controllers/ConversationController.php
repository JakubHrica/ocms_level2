<?php namespace AppChat\Conversation\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AppChat\Conversation\Models\Conversation;
use AppUser\User\Models\User;
use Exception;

class ConversationController extends Controller
{
    // Function to search for users
    public function searchUsers(Request $request)
    {
        try {
            $authUser = $request->user; // Authenticated user
            $data = $request->post(); // Retrieve data from the request

            // Get the IDs of users with whom the authenticated user has conversations
            $conversationUserIds = Conversation::where('user_one_id', $authUser->id)
                ->orWhere('user_two_id', $authUser->id)
                ->get()
                ->flatMap(function ($conversation) use ($authUser) {
                    return [$conversation->user_one_id, $conversation->user_two_id];
                })
                ->unique()
                ->filter(fn($id) => $id != $authUser->id) // Remove the ID of the logged-in user
                ->values()
                ->toArray();

            // Search for users who are not in conversations and match the search term
            $users = User::where('id', '!=', $authUser->id)
                ->whereNotIn('id', $conversationUserIds)
                ->when($data['search'], function ($query) use ($data) {
                    $query->where('email', 'LIKE', '%' . $data['search'] . '%');
                })
                ->select('id', 'email')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Users fetched successfully',
                'data' => $users // Return the found users
            ]);
        } catch (Exception $e) {
            return $this->handleException($e, 'Failed to fetch available users');
        }
    }

    // Function to start a new conversation
    public function startConversation(Request $request)
    {
        try {
            $authUser = $request->user; // Logged-in user
            $data = $request->post(); // Retrieve the ID of the other user from POST data

            // Validate the users
            if (!$authUser || !$data['user_id'] || $authUser->id == $data['user_id']) {
                return response()->json(['error' => 'Invalid users'], 400);
            }

            // Check if a conversation already exists between these users
            $otherUserId = $data['user_id'];

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

    // Function for error handling
    private function handleException(Exception $e, $message = 'An error occurred')
    {
        return response()->json([
            'error' => $message,
            'message' => $e->getMessage()
        ], 500);
    }
}
