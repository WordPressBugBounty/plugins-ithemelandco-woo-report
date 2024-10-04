<?php

class ITWR_Generator
{
    public static function license_hash($license_data)
    {
        return (empty($license_data) || !isset($license_data['license_key']) || !isset($license_data['email']))
            ? md5(wp_rand(100000, 999999))
            : md5($license_data['license_key'] . 'itwr' . $license_data['email'] . $_SERVER['SERVER_NAME']);
    }
}
