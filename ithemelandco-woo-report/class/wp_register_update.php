<?php

class ITWR_WP_Register_Update
{
    private static $instance;

    private $plugin_url;
    private $update_data;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        $this->plugin_url = "https://ithemelandco.com";
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'set_plugins_api'), 10, 3);
    }

    public function check_update($transient)
    {
        if (!isset($transient->response)) {
            return $transient;
        }

        $validation = $this->license_validation();
        $update_data = $this->get_update_data();
        if (!empty($validation) && $validation == 'valid') {
            if (!empty($update_data) && is_array($update_data)) {
                $remote_version = $this->get_plugin_update_data('version', $update_data);
                if ($remote_version) {
                    $plugin_basename = 'it_report_wcreport_textdomain' . '/' . 'it_report_wcreport_textdomain' . '.php';
                    if (version_compare(ITWR_VERSION, $remote_version->new_version, '<')) {
                        $obj = new \stdClass();
                        $obj->id = $remote_version->slug;
                        $obj->slug = $remote_version->slug;
                        $obj->new_version = $remote_version->new_version;
                        $obj->url = $remote_version->url;
                        $obj->plugin = $plugin_basename;
                        if (!empty($remote_version->plugin_icon)) {
                            $obj->icons = ['default' => $remote_version->plugin_icon];
                        }
                        $obj->package = $remote_version->package;
                        $obj->tested = $remote_version->tested;
                        $transient->response[$plugin_basename] = $obj;
                    }
                }
            }
        }

        return $transient;
    }

    private function license_validation()
    {
        $license_service = ITWR_License_Service::get_instance();
        return $license_service->license_validation();
    }

    public function set_plugins_api($obj, $action, $arg)
    {
        $update_data = (!empty($this->update_data)) ? $this->update_data : $this->get_update_data();
        if (!empty($update_data) && is_array($update_data)) {
            if (($action == 'query_plugins' || $action == 'plugin_information') && isset($arg->slug) && $arg->slug === 'it_report_wcreport_textdomain') {
                $remote_data = $this->get_plugin_update_data('info', $update_data);
                if ($remote_data) {
                    return $remote_data;
                }
            }
        }

        return $obj;
    }

    private function get_plugin_update_data($action = null, $update_data = [])
    {
        $license_repository = new ITWR_License_Repository();
        $license_data = $license_repository->get();
        if (empty($license_data) || !isset($license_data['license_key']) || !isset($license_data['product_id']) || !isset($license_data['email']) || !isset($license_data['domain'])) {
            return false;
        }

        $hash = ITWR_Generator::license_hash($license_data);
        $data = new stdClass();
        $data->slug = 'iThemelandCo-Woo-Report-Lite';
        $data->name = 'iThemelandCo-Woo-Report-Lite';
        $data->plugin_name = "main.php";
        $data->new_version = isset($update_data['new_version']) ? $update_data['new_version'] : '';
        $data->url = $this->plugin_url;
        $data->tested = (!empty($update_data['wp_tested_version'])) ? $update_data['wp_tested_version'] : '';
        $data->plugin_icon = (!empty($update_data['plugin_icon'])) ? $update_data['plugin_icon'] : '';
        $data->package = $update_data['download_link'] . "&hash={$hash}";
        if ($action == 'info') {
            $data->requires = (!empty($update_item['wp_require_version'])) ? $update_item['wp_require_version'] : '';
            $data->downloaded = 25184;
            $data->last_updated = gmdate('Y-m-d', $update_data['date']);
            $data->sections = (isset($update_data['sections'])) ? $update_data['sections'] : [];
            $data->download_link = $data->package;
        }

        return $data;
    }

    private function get_update_data()
    {
        // insert data to db from remote
        ITWR_Update_Service::init();

        // get update data
        $update_data_repository = ITWR_Singleton_Update_Data::get_instance();
        $this->update_data = $update_data_repository->get();
        return $this->update_data;
    }

    public function __clone()
    {
    }

    public function __wakeup()
    {
    }
}
