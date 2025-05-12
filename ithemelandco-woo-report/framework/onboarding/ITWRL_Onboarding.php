<?php

defined('ABSPATH') || exit();

class ITWRL_Onboarding
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
        add_action('wp_ajax_itwrl_ithemeland_onboarding_plugin', [$this, 'onboarding_action']);
    }

    public function onboarding_action()
    {
        // Verify nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'ithemeland_onboarding_action')) {
            wp_send_json_error([
                'message' => esc_html__('Security verification failed', 'advanced-product-table-for-woocommerce')
            ], 403);
            exit;
        }

        // Check activation type
        if (!isset($_POST['activation_type'])) {
            wp_send_json_error([
                'message' => esc_html__('Invalid request', 'advanced-product-table-for-woocommerce')
            ], 400);
            exit;
        }

        $activation_type = sanitize_text_field(wp_unslash($_POST['activation_type']));
        $message = esc_html__('Error! Please try again.', 'advanced-product-table-for-woocommerce');

        if ($activation_type === 'skip') {
            self::update_opt_in('no');
            self::update_usage_track('no');
            self::onboarding_complete('skipped');
            wp_send_json_success([
                'redirect' => ITWRL_DASHBOARD_PAGE,
                'message' => esc_html__('Activation skipped', 'advanced-product-table-for-woocommerce')
            ]);
            exit;
        }

        if ($activation_type === 'allow') {
            $opt_in = !empty($_POST['ithemeland_opt_in']) ? 'yes' : 'no';
            $usage_tracking = !empty($_POST['ithemeland_usage_track']) ? 'yes' : 'no';

            self::update_opt_in($opt_in);
            self::update_usage_track($usage_tracking);

            if ($opt_in == 'yes' && class_exists('ITWRL_EmailSubscription')) {
                ITWRL_ActivePlugins::update('itwrl', 'report:free');
                $email_subscription_service = new ITWRL_EmailSubscription();
                $admin_email = get_option('admin_email');
                $info = $email_subscription_service->add_subscription([
                    'email' => sanitize_email($admin_email),
                    'domain' => (!empty($_SERVER['SERVER_NAME'])) ? sanitize_text_field(wp_unslash($_SERVER['SERVER_NAME'])) : '',
                    'product_id' => 'itwrl',
                    'product_name' => ITWRL_LABEL
                ]);

                if (is_array($info)) {
                    if (!empty($info['success']) && $info['success'] === true) {
                        update_option('ithemeland_activation_email', $admin_email);
                        $message = esc_html__('Plugin activated successfully!', 'advanced-product-table-for-woocommerce');
                    } else {
                        $message = $info['message'] ?? esc_html__('Activation failed', 'advanced-product-table-for-woocommerce');
                        wp_send_json_error(['message' => $message], 400);
                        exit;
                    }
                }
            }

            self::onboarding_complete('allowed');

            if ($usage_tracking == 'yes') {
                $analytics_service = ITWRL_AnalyticsService::get_instance();
                $analytics_service->send();
            }

            wp_send_json_success([
                'message' => $message,
                'redirect' => ITWRL_DASHBOARD_PAGE
            ]);
            exit;
        }

        wp_send_json_error(['message' => $message], 400);
        exit;
    }

    public static function is_completed()
    {
        return (get_option('itwrl_onboarding', 'no') != 'no');
    }

    public static function update_opt_in($data)
    {
        update_option('itwrl_opt_in', sanitize_text_field($data));
    }

    public static function update_usage_track($data)
    {
        update_option('itwrl_usage_track', sanitize_text_field($data));
    }

    public static function onboarding_complete($data)
    {
        update_option('itwrl_onboarding', sanitize_text_field($data));
    }

    public static function opt_in_is_allowed()
    {
        return get_option('itwrl_opt_in', 'no') == 'yes';
    }

    public static function usage_track_is_allowed()
    {
        return get_option('itwrl_usage_track', 'no') == 'yes';
    }
}
