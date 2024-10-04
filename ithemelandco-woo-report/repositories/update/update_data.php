<?php

class ITWR_Update_Data
{
    private $option_name;

    public function __construct()
    {
        $this->option_name = 'itwr_update_data';
    }

    public function update($plugin_data)
    {
        if (!is_array($plugin_data)) {
            return false;
        }
        return update_option($this->option_name, esc_sql($plugin_data));
    }

    public function get()
    {
        return get_option($this->option_name);
    }

    public function clear()
    {
        return update_option($this->option_name, '');
    }
}
