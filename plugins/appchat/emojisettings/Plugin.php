<?php namespace AppChat\EmojiSettings;

use Backend;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'EmojiSettings',
            'description' => 'Plugin for managing emojis',
            'author' => 'AppChat',
            'icon' => 'icon-emoji-happy',
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'emojisettings' => [
                'label' => 'EmojiSettings',
                'url' => Backend::url('appchat/emojisettings/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['appchat.emojisettings.*'],
                'order' => 500,
            ],
        ];
    }
}
