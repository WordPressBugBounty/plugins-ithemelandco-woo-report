<?php

class ITWR_License_Repository
{
    private $option_name;

    public function __construct()
    {
        $this->option_name = 'itwr-el-data';
    }

    public function get()
    {
        return get_option($this->option_name);
    }

    public function matched_license()
    {
        $license_data = $this->get();
        if (!$license_data) {
            return false;
        }

        $license_data['matched'] = 1;
        $license_data['status'] = 1;

        return $this->update($license_data);
    }

    public function update($data)
    {
        return update_option($this->option_name, $data);
    }
}
