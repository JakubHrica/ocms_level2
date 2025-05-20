<?php namespace AppChat\Reaction;

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
            'name' => 'Reaction',
            'description' => 'Plugin for managing reactions',
            'author' => 'AppChat',
            'icon' => 'icon-leaf'
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'Emoji Settings',
                'description' => 'Manage emoji based settings.',
                'category' => 'AppChat',
                'icon' => 'icon-smile-o',
                'class' => \AppChat\Reaction\Models\EmojiSettings::class,
                'order' => 1001,
                'keywords' => 'emoji, settings',
                'permissions' => ['acme.users.access_settings']
            ]
        ];
    }

    public function registerNavigation()
    {
        return [
            'reaction' => [
                'label' => 'Reactions',
                'url' => \Backend::url('appchat/reaction/reactions'),
                'icon' => 'icon-smile-o',
                'permissions' => ['appchat.reaction.*'],
                'order' => 1004,
            ],
        ];
    }
}
