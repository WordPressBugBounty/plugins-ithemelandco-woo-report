<?php

class ITWR_Update_Helper
{
    public static function has_force_update($plugin_key, $current_version)
    {
        $plugins_update_data = ITWR_Singleton_Update_Data::get_instance();
        $plugin_update_data = $plugins_update_data->get($plugin_key);
        return (isset($plugin_update_data['force_update_from']) && version_compare($current_version, $plugin_update_data['force_update_from'], '<='));
    }

    public static function has_available_update($current_version, $plugin_data)
    {
        $new_version = (isset($plugin_data['new_version'])) ? $plugin_data['new_version'] : '';
        return ((version_compare($current_version, $new_version) === -1));
    }
}
