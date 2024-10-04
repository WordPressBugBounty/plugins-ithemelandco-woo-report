<?php

class Ithemeland_Update
{
    public function get_plugin_data()
    {
        try {
            $data = [
                'service' => 'update_data',
                'product_id' => 'itwr'
            ];
            $response = wp_remote_post("https://www.ithemelandco.com/services/license-update/index.php", [
                'sslverify' => false,
                'body' => $data
            ]);
            return (!is_object($response) && !empty($response['body'])) ? json_decode($response['body'], true) : null;
        } catch (Exception $e) {
            return false;
        }
    }
}
