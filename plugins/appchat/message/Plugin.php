<?php namespace AppChat\Message;

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
            'name' => 'Message',
            'description' => 'Plugin for managing messages',
            'author' => 'AppChat',
            'icon' => 'icon-comments',
        ];
    }

    /**
     * registerNavigation registers the navigation items for the plugin.
     */
    public function registerNavigation()
    {
        return [
            'message' => [
                'label' => 'Messages',
                'url' => Backend::url('appchat/message/messages'),
                'icon' => 'icon-comments',
                'permissions' => ['appchat.message.*'],
                'order' => 1003,
            ],
        ];
    }

    public $require = [
        'AppUser.User'
    ];
}
