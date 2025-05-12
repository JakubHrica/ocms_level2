<?php namespace AppChat\Conversation\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use AppChat\Conversation\Models\Conversation;
use AppUser\User\Models\User;
use Exception;

class ConversationController extends Controller
{
    public function searchUsers(Request $request)
    {
        try {
            $authUser = $request->user;
            $search = $request->input('search');

            $conversationUserIds = Conversation::where('user_one_id', $authUser->id)
                ->orWhere('user_two_id', $authUser->id)
                ->get()
                ->flatMap(function ($conversation) use ($authUser) {
                    return [$conversation->user_one_id, $conversation->user_two_id];
                })
                ->unique()
                ->filter(fn($id) => $id != $authUser->id)
                ->values()
                ->toArray();

            $users = User::where('id', '!=', $authUser->id)
                ->whereNotIn('id', $conversationUserIds)
                ->when($search, function ($query) use ($search) {
                    $query->where('email', 'LIKE', "%$search%");
                })
                ->select('id', 'email')
                ->get();

            return response()->json($users);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch available users', 'message' => $e->getMessage()], 500);
        }
    }

    public function startConversation(Request $request)
    {
        try {
            $authUser = $request->user;
            $otherUserId = post('user_id');

            if (!$authUser || !$otherUserId || $authUser->id == $otherUserId) {
                return response()->json(['error' => 'Invalid users'], 400);
            }

            $existing = Conversation::where(function($q) use ($authUser, $otherUserId) {
                $q->where('user_one_id', $authUser->id)
                ->where('user_two_id', $otherUserId);
            })->orWhere(function($q) use ($authUser, $otherUserId) {
                $q->where('user_one_id', $otherUserId)
                ->where('user_two_id', $authUser->id);
            })->first();

            if ($existing) {
                return response()->json([
                    'message' => 'Conversation already exists',
                    'id' => $existing->id
                ]);
            }

            $conversation = new Conversation();
            $conversation->user_one_id = $authUser->id;
            $conversation->user_two_id = $otherUserId;
            $conversation->name = "Konverzácia";
            $conversation->save();

            $otherUser = User::find($otherUserId);

            return response()->json([
                'conversation_id' => $conversation->id,
                'user' => [
                    'id' => $otherUser->id,
                    'email' => $otherUser->email
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}