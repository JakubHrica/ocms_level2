<?php namespace AppChat\Conversation\Models;

use AppChat\Message\Models\Message;
use Model;
use AppUser\User\Models\User;

/**
 * Conversation Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Conversation extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'appchat_conversation_conversations';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array belongsTo relationships
     */
    public $belongsTo = [
        'user_one' => [User::class, 'key' => 'user_one_id'],
        'user_two' => [User::class, 'key' => 'user_two_id'],
    ];

    /**
     * @var array hasMany relationships
     */
    public $hasMany = [
        'messages' => Message::class,
    ];
}
