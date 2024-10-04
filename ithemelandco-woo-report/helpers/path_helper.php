<?php

class ITWR_Path_Helper
{
    public static function isLocalhost()
    {
        return ($_SERVER['SERVER_NAME'] == 'localhost');
    }

    public static function isAllowedDomain()
    {
        return (in_array($_SERVER['SERVER_NAME'], [
            // 'localhost',
            'wordpress.local',
            'ithemelandco.com',
            'yaldayekavir.com',
            'demos.ithemelandco.com'
        ]));
    }
}
