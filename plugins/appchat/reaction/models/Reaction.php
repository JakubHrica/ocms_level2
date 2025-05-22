<?php namespace AppChat\Reaction\Models;

use Model;
use AppChat\Message\Models\Message;
use AppUser\User\Models\User;
use AppChat\Reaction\Models\EmojiSettings;

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

    public $settingsFields = 'fields.yaml';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'message_id' => 'required|unique:appchat_reaction_reactions',
        'user_id' => 'required',
        'emoji' => 'required'
    ];

    /**
     * @var array fillable attributes
     */
    protected $fillable = ['message_id', 'user_id', 'emoji'];

    /**
     * @var array belongsTo relationships
     */
    public $belongsTo = [
        'message' => Message::class,
        'user' => User::class
    ];

    public function getEmojiOptions()
    {
        $settings = EmojiSettings::instance();
        return [
            'emojis' => $settings->available_emojis
        ];
    }
}
