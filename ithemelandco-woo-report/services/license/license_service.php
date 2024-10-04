<?php

class ITWR_License_Service
{
    private static $instance;
    private $service_url;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->service_url = "https://www.ithemelandco.com/services/license-update/index.php";
    }

    public function license_validation()
    {
        $license_repository = new ITWR_License_Repository();
        $license_hash = ITWR_Generator::license_hash($license_repository->get());
        if (empty($license_hash)) {
            return false;
        }

        $validation = $this->get_validation_by_hash($license_hash);
        if (!empty($validation)) {
            update_option('_itwr_validation', $validation);
        }

        return $validation;
    }

    public function license_is_valid()
    {
        $validation = get_option('_itwr_validation');
        return (!empty($validation) && $validation == 'valid');
    }

    private function get_validation_by_hash($hash)
    {
        $response = wp_remote_post($this->service_url, [
            'sslverify' => false,
            'body' => [
                'service' => 'license_validation',
                'hash' => $hash
            ]
        ]);

        if (!is_object($response) && !empty($response['body'])) {
            return $response['body'];
        }

        return false;
    }
}
