<?php

defined('ABSPATH') || exit();

class ITWRL_AnalyticsService
{
    private static $instance;

    private $service_url = "http://usage-tracking.ithemelandco.com/index.php";

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function send()
    {
        $analytics_stats = ITWRL_AnalyticsStats::instance();
        $stats = $analytics_stats->get_stats();

        $stats['service'] = 'analytics';
        $response = wp_remote_post($this->service_url, [
            'sslverify' => false,
            'method' => 'POST',
            'timeout' => '45',
            'httpversion' => '1.0',
            'body' => $stats
        ]);

        return (!is_wp_error($response));
    }
}
