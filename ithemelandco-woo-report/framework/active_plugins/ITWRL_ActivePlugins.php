<?php

defined('ABSPATH') || exit();

class ITWRL_ActivePlugins
{
    private static $option_name = 'ithemeland_active_plugins';

    public static function update($name, $label)
    {
        if (empty($name) || empty($label)) {
            return false;
        }

        $plugins = self::get();
        $plugins[sanitize_text_field($name)] = sanitize_text_field($label);
        return update_option(self::$option_name, $plugins);
    }

    public static function get()
    {
        return get_option(self::$option_name, []);
    }
}
