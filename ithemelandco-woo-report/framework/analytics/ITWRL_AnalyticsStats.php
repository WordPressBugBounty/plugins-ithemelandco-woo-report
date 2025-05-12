<?php

defined('ABSPATH') || exit();

class ITWRL_AnalyticsStats
{
    private static $instance = null;

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_stats()
    {
        return $this->get_default_stats();
    }

    private function get_default_stats()
    {
        return [
            'plugin' => 'itwrl',
            'type' => 'lite',
            'site_id' => md5(get_site_url() . 'itwrl'),
            'plugin_version' => ITWRL_VERSION,
            'analytics_items' => [],
            'domain' => get_site_url(),
            'email' => get_option('admin_email'),
            'timezone' => get_option('timezone_string'),
            'php_version' => $this->get_php_version(),
            'php_max_input_vars' => ini_get('max_input_vars'),
            'php_post_max_size' => ini_get('post_max_size'),
            'php_max_execution_time' => ini_get('max_execution_time'),
            'php_memory_limit' => ini_get('memory_limit'),
            'zip_installed' => extension_loaded('zip'),
            'imagick_available' => extension_loaded('imagick'),
            'xmlreader_exists' => class_exists('XMLReader'),
            'gd_available' => extension_loaded('gd'),
            'curl_version' => $this->get_curl_version(),
            'curl_ssl_version' => $this->get_curl_ssl_version(),
            'is_writable' => $this->is_content_writable(),
            'user_count' => $this->get_user_count(),
            'posts_count' => wp_count_posts()->publish,
            'page_count' => wp_count_posts('page')->publish,
            'site_languages' => get_locale(),
            'is_ssl' => is_ssl(),
            'network_url' => network_site_url(),
            'external_object_cache' => (bool) wp_using_ext_object_cache(),
            'wp_debug' => WP_DEBUG,
            'wp_debug_display' => WP_DEBUG_DISPLAY,
            'script_debug' => SCRIPT_DEBUG,
            'active_theme' => get_stylesheet(),
            'active_plugins' => $this->get_active_plugins_list(),
            'wp_version' => get_bloginfo('version'),
            'is_multisite' => is_multisite(),
            'woocommerce_version' => defined('WC_VERSION') ? WC_VERSION : 'N/A',
            'product_count' => $this->get_product_count(),
            'settings' => $this->get_plugin_settings(),
            'server_info' => [
                'server_software' => (!empty($_SERVER['SERVER_SOFTWARE'])) ? sanitize_text_field(wp_unslash($_SERVER['SERVER_SOFTWARE'])) : '', //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                'php_memory_limit' => ini_get('memory_limit')
            ]
        ];
    }

    private function get_php_version()
    {
        if (defined('PHP_MAJOR_VERSION') && defined('PHP_MINOR_VERSION') && defined('PHP_RELEASE_VERSION')) {
            return PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '.' . PHP_RELEASE_VERSION;
        }
        return phpversion();
    }

    private function get_product_count()
    {
        if (!function_exists('wc_get_products')) {
            return 0;
        }
        $query = new \WC_Product_Query([
            'limit' => -1,
            'return' => 'ids',
            'status' => 'publish'
        ]);
        return count($query->get_products());
    }

    private function get_curl_version()
    {
        if (function_exists('curl_version')) {
            $curl = curl_version();
        }

        return isset($curl['version']) ? $curl['version'] : false;
    }

    private function get_curl_ssl_version()
    {
        $curl = array();
        if (function_exists('curl_version')) {
            $curl = curl_version();
        }

        return isset($curl['ssl_version']) ? $curl['ssl_version'] : false;
    }

    private function is_content_writable()
    {
        $upload_dir = wp_upload_dir();
        return wp_is_writable($upload_dir['basedir']);
    }

    private function get_user_count()
    {
        if (is_multisite()) {
            $user_count = get_user_count();
        } else {
            $count      = count_users();
            $user_count = $count['total_users'];
        }

        return $user_count;
    }

    private function get_active_plugins_list()
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $active_plugins = get_option('active_plugins');
        $plugins = [];

        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $plugins[] = [
                'name' => $plugin_data['Name'],
                'version' => $plugin_data['Version']
            ];
        }

        return $plugins;
    }

    private function get_plugin_settings()
    {

        return [
            'some_setting' => get_option('itwrl_some_setting', 'default')
        ];
    }
}
