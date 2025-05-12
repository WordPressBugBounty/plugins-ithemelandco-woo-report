<?php

defined('ABSPATH') || exit();

class ITWRL_AnalyticsTracker
{
    private static $instance;

    public static function register()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    public function __construct()
    {
        add_action('admin_init', [$this, 'analytics_check_field']);
        add_action('init', [$this, 'schedule_weekly_analytics']);
    }

    public function schedule_weekly_analytics()
    {
        if (!ITWRL_Onboarding::usage_track_is_allowed()) {
            return false;
        }

        $transient_name = 'ithemeland_itwrl_analytics_send';
        if (false === get_transient($transient_name)) {
            $analytics_service = ITWRL_AnalyticsService::get_instance();
            $analytics_service->send();
            set_transient($transient_name, 'sent', WEEK_IN_SECONDS);
        }
    }

    public function analytics_check_field()
    {
        register_setting(
            'general',
            'itwrl_usage_track',
            array(
                'type' => 'boolean',
                'sanitize_callback' => [$this, 'sanitize_checkbox'],
                'default' => 1
            )
        );

        add_settings_field(
            'itwrl_usage_track',
            esc_html__('Enable Usage Tracking', 'advanced-product-table-for-woocommerce'),
            [$this, 'usage_tracking_checkbox'],
            'general',
            'default',
            array(
                'label_for' => 'itwrl_usage_track_general',
                'description' => esc_html__('Allow anonymous usage data tracking to help improve our plugin.', 'advanced-product-table-for-woocommerce')
            )
        );
    }

    public function sanitize_checkbox($input)
    {
        return (isset($input) && $input == 'yes') ? 'yes' : '';
    }

    public function usage_tracking_checkbox($args)
    {
        $option = ITWRL_Onboarding::usage_track_is_allowed();
        $description = $args['description'] ?? '';

        include ITWRL_FW_DIR . 'analytics/views/usage_track_checkbox.php';
    }
}
