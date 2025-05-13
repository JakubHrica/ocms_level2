<?php namespace AppChat\Message\Models;

use AppChat\Conversation\Models\Conversation;
use AppUser\User\Models\User;
use Model;
use October\Rain\Database\Attach\File;
use AppChat\Reaction\Models\Reaction;

/**
 * Message Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'appchat_message_messages';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'conversation_id' => 'required|exists:appchat_conversation_conversations,id',
        'content' => 'nullable|string',
        'reply_to_id' => 'nullable|exists:appchat_message_messages,id',
        'attachment' => 'nullable|file|max:5120'
    ];

    /**
     * @var array belongsTo relationships
     */
    public $belongsTo = [
        'conversation' => Conversation::class,
        'user' => User::class,
        'reply_to' => [Message::class, 'key' => 'reply_to_id'],
        'user' => User::class,
        'reply_to' => [Message::class, 'key' => 'reply_to_id'],
        'conversation' => Conversation::class,
    ];

    /**
     * @var array attachOne relationships
     */
    public $attachOne = [
        'attachment' => File::class,
    ];

    /**
     * @var array hasOne relationships
     */
    public $hasOne = [
        'reaction' => Reaction::class
    ];

    /**
     * @var array hasMany relationships
     */
    public $hasMany = [
        'reactions' => Reaction::class
    ];
}
