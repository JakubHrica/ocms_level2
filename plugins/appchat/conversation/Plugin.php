<?php namespace AppChat\Conversation;

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
            'name' => 'Conversation',
            'description' => 'Plugin for managing conversations',
            'author' => 'AppChat',
            'icon' => 'icon-comments'
        ];
    }

    /**
     * registerNavigation registers the navigation items for the plugin.
     */
    public function registerNavigation()
    {
        return [
            'conversation' => [
                'label' => 'Conversations',
                'url' => \Backend::url('appchat/conversation/conversations'),
                'icon' => 'icon-comments',
                'permissions' => ['appchat.conversation.*'],
                'order' => 1002,
            ],
        ];
    }
}
