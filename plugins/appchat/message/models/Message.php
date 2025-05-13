<?php namespace AppChat\Message\Models;

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

    public $belongsTo = [
        'conversation' => ['AppChat\Conversation\Models\Conversation'],
        'user'         => ['AppUser\User\Models\User'],
        'reply_to'     => ['AppChat\Message\Models\Message', 'key' => 'reply_to_id'],
        'user' => ['AppUser\User\Models\User'],
        'reply_to' => ['AppChat\Message\Models\Message', 'key' => 'reply_to_id'],
        'conversation' => ['AppChat\Conversation\Models\Conversation']
    ];

    public $attachOne = [
        'attachment' => File::class,
    ];

    public $hasOne = [
        'reaction' => Reaction::class
    ];

    public $hasMany = [
        'reactions' => Reaction::class
    ];
}
