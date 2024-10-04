<?php

class ITWR_Update_Service
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        $plugin_data = $this->get_plugin_data();
        if (!empty($plugin_data) && isset($plugin_data['key'])) {
            $this->save($plugin_data);
        }
        $this->set_log();
    }

    private function set_log()
    {
        return update_option('itwr_last_check_for_update', gmdate('Y-m-d H:i:s', time()));
    }

    private function get_plugin_data()
    {
        return (new Ithemeland_Update())->get_plugin_data();
    }

    private function save($plugin_data)
    {
        try {
            return (new ITWR_Update_Data())->update($plugin_data);
        } catch (\Exception $e) {
            return false;
        }
    }
}
