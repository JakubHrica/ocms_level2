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
}
