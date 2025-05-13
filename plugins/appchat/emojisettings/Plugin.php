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
            'description' => 'No description provided yet...',
            'author' => 'AppChat',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        //
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'AppChat\EmojiSettings\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'appchat.emojisettings.some_permission' => [
                'tab' => 'EmojiSettings',
                'label' => 'Some permission'
            ],
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
