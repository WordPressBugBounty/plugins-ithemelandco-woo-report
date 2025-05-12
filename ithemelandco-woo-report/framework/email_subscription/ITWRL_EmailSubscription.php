<?php

defined('ABSPATH') || exit();

class ITWRL_EmailSubscription
{
    private $service_url;

    public function __construct()
    {
        $this->service_url = "http://usage-tracking.ithemelandco.com/index.php";
    }

    public function add_subscription($data)
    {
        $data['service'] = 'email_subscription';
        $data['active_plugins'] = ITWRL_ActivePlugins::get();
        $response = wp_remote_post($this->service_url, [
            'sslverify' => false,
            'method' => 'POST',
            'timeout' => 45,
            'httpversion' => '1.0',
            'body' => $data
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => $response->get_error_message()
            ];
        }

        if (wp_remote_retrieve_response_code($response) != 200) {
            return [
                'success' => false,
                'message' => 'Server error: ' . wp_remote_retrieve_response_message($response)
            ];
        }

        $body = wp_remote_retrieve_body($response);
        $response_data = json_decode($body, true);

        self::sent();

        return is_array($response_data) ? $response_data : [
            'success' => false,
            'message' => 'Invalid server response'
        ];
    }

    public static function is_sent()
    {
        return get_option('ithemeland_itwrl_email_subscription_sent', 'no') == 'yes';
    }

    private static function sent()
    {
        return update_option('ithemeland_itwrl_email_subscription_sent', 'yes');
    }
}
