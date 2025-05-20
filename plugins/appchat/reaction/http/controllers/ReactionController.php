<?php namespace AppChat\Reaction\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AppChat\Reaction\Models\Reaction;
use AppChat\Reaction\Models\EmojiSettings;

class ReactionController extends Controller
{
    public function reactToMessage(Request $request, $message_id)
    {
        try {
            $authUser = $request->user;
            $emoji = $request->post('emoji');

            $settings = EmojiSettings::instance();
            $allowed = collect($settings->available_emojis)->pluck('emoji')->contains($emoji);

            if (!$allowed) {
                return response()->json(['error' => 'Not allowed reaction'], 403);
            }   
            
            $reaction = Reaction::updateOrCreate(
                ['message_id' => $message_id, 'user_id' => $authUser->id],
                ['emoji' => $emoji]
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

    public function getAvailableEmojis()
    {
        try {
            $settings = EmojiSettings::instance();
            $emojis = $settings->available_emojis ?? [];

            $emojis = collect($settings->available_emojis ?? [])
                ->pluck('emoji')
                ->filter()
                ->values()
                ->all();

            return response()->json([
                'emojis' => $emojis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch emojis',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
