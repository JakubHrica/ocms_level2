<?php namespace AppUser\User;

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
            'name' => 'User',
            'description' => 'Plugin for managing users',
            'author' => 'AppUser',
            'icon' => 'icon-user-circle'
        ];
    }

    /**
     * registerNavigation registers the navigation items for the plugin.
     */
    public function registerNavigation()
    {
        return [
            'user' => [
                'label' => 'Users',
                'url' => Backend::url('appuser/user/users'),
                'icon' => 'icon-user-circle',
                'permissions' => ['appuser.user.*'],
                'order' => 1001,
            ],
        ];
    }
}
