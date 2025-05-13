<?php namespace AppChat\EmojiSettings\Models;

use Model;

/**
 * Settings Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Settings extends Model
{
    /**
     * @var array list of behaviors implemented by this model
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string table name
     */
    public $table = 'appchat_emojisettings_settings';

    public function getEmojiOptions()
    {
    return Emoji::class::all()->pluck('unicode', 'id')->toArray();
    }
}
