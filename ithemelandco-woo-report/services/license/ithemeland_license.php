<?php

class Ithemeland_License
{
    public function verify($data)
    {
        $data['service'] = 'license_activation';
        $response = wp_remote_post("https://www.ithemelandco.com/services/license-update/index.php", [
            'sslverify' => false,
            'body' => $data
        ]);
        if (!is_object($response) && !empty($response['body'])) {
            if (!empty($response['response']['code']) && $response['response']['code'] != 500) {
                $data =  json_decode($response['body'], true);
                return (!is_null($data)) ? $data : $response['body'];
            } else {
                return "System Error!";
            }
        }
        return null;
    }

    public function deactivate()
    {
        $license_repository = new ITWR_License_Repository();
        $el_data = $license_repository->get();
        if (empty($el_data)) {
            return false;
        }

        $response = wp_remote_post("https://www.ithemelandco.com/services/license-update/index.php", [
            'sslverify' => false,
            'body' => [
                'service' => 'license_deactivation',
                'license_data' => $el_data
            ]
        ]);

        if (!is_object($response) && !empty($response['body'])) {
            $data =  json_decode($response['body'], true);
            return (isset($data['success']) && $data['success'] === true);
        }

        return false;
    }
}
