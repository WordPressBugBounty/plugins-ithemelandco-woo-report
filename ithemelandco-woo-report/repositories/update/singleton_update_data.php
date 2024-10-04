<?php

class ITWR_Singleton_Update_Data
{
    private static $instance;
    private $plugin_data;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $update_alert_repository = new ITWR_Update_Data();
        $this->plugin_data = $update_alert_repository->get();
    }

    public function get()
    {
        return $this->plugin_data;
    }

    public function __wakeup()
    {
    }

    public function __clone()
    {
    }
}
