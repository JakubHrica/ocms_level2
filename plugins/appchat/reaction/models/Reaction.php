<?php namespace AppChat\Reaction\Models;

use Model;
use AppChat\Message\Models\Message;
use AppUser\User\Models\User;

/**
 * Reaction Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Reaction extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'appchat_reaction_reactions';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'message_id',
        'user_id',
        'emoji'
    ];

    /**
     * @var array belongsTo relationships
     */
    public $belongsTo = [
        'message' => Message::class,
        'user' => User::class
    ];
}
