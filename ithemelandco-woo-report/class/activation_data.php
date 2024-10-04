<?php

class ITWR_Activation_Data
{
    private static $option_name = "itwrl_is_active";

    public static function get_data()
    {
        return get_option(self::$option_name);
    }

    public static function is_active()
    {
        if (ITWR_Path_Helper::isAllowedDomain()) {
            return true;
        }

        $is_active = get_option(self::$option_name, 'no');
        return ($is_active == 'yes' || $is_active == 'skipped');
    }

    public static function skipped()
    {
        $skipped = get_option(self::$option_name, 'no');
        return $skipped == 'skipped';
    }
}
