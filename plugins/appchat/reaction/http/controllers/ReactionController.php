<?php namespace AppChat\Reaction\Http\Controllers;

use Illuminate\Http\Request;
use AppChat\Reaction\Models\Reaction;
use Illuminate\Routing\Controller;

class ReactionController extends Controller
{
    public function reactToMessage(Request $request)
    {
        try {
            $user = $request->user;
            $messageId = $request->input('message_id');
            $emoji = $request->input('emoji');

            $reaction = Reaction::updateOrCreate(
                ['message_id' => $messageId, 'user_id' => $user->id],
                ['emoji' => $emoji]
            );

            return response()->json(['message' => 'Reaction added', 'reaction' => $reaction]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add reaction', 'details' => $e->getMessage()], 500);
        }
    }
}