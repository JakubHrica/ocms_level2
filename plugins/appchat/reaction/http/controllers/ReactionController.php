<?php namespace AppChat\Reaction\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use AppChat\Reaction\Models\Reaction;
use AppChat\Reaction\Models\EmojiSettings;
use Exception;

class ReactionController extends Controller
{
    public function reactToMessage(Request $request, $message_id)
    {
        // Get the authenticated user from the request
        $authUser = $request->user;

        // Get the emoji from the POST data
        $emoji = post('emoji');

        // Fetch the available emojis from settings
        $settings = EmojiSettings::instance();

        // Check if the provided emoji is allowed
        $allowed = collect($settings->available_emojis)->pluck('emoji')->contains($emoji);

        if (!$allowed) {
            throw new Exception('Not allowed reaction', 403);
        }   
        
        // Add or update the reaction for the message and user
        $reaction = Reaction::updateOrCreate(
            ['message_id' => $message_id, 'user_id' => $authUser->id],
            ['emoji' => $emoji]
        );

        // Return a success response with the reaction data
        return[
            'reaction' => $reaction
        ];
    }

    public function getAvailableEmojis()
    {
        // Get the singleton instance of EmojiSettings
        $settings = EmojiSettings::instance();

        // Get the available_emojis property, or an empty array if not set
        $emojis = $settings->available_emojis ?? [];

        // Use Laravel's collection helpers to process the emojis
        $emojis = collect($settings->available_emojis ?? [])
            ->pluck('emoji') // Extract only the 'emoji' field from each item
            ->filter() // Remove any null or empty values
            ->values() // Re-index the array
            ->all(); // Convert the collection back to a plain array

        // Return the processed emojis as a JSON response
        return[
            'emojis' => $emojis
        ];
    }
}
