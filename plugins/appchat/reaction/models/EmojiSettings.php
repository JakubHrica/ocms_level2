<?php namespace AppChat\Reaction\Models;

use Model;

class EmojiSettings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'appchat_emojisetting_settings';
    public $settingsFields = 'fields.yaml';
}