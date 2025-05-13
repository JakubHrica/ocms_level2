<?php namespace AppChat\EmojiSettings\Models;

use Model;

/**
 * Emoji Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Emoji extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'appchat_emojisettings_emoji';

    protected $fillable = ['name', 'unicode'];

    /**
     * @var array rules for validation
     */
    public $rules = [
        'name' => 'required',
        'unicode' => 'required'
    ];
}
