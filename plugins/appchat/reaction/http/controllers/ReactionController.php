<?php namespace AppChat\Reaction\Http\Controllers;

use Illuminate\Http\Request;
use AppChat\Reaction\Models\Reaction;
use Illuminate\Routing\Controller;

class ReactionController extends Controller
{
    public function reactToMessage(Request $request)
    {
        try {
            $authUser = $request->user; // Authenticated user
            $data = $request->post(); // Retrieve data from the request

            // Update or create a reaction for the given message and user
            // If a reaction already exists, it updates the emoji; otherwise, it creates a new reaction
            $reaction = Reaction::updateOrCreate(
            ['message_id' => $request['messageId'], 'user_id' => $authUser->id],
            ['emoji' => $request['emoji']]
            );

            return response()->json([
                'message' => 'Reaction added',
                'reaction' => $reaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to add reaction',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
