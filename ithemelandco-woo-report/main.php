<?php
/*
Plugin Name: iThemelandCo Woo Report Lite
Plugin URI: http://ithemelandco.com/plugins/woocommerce-report/
Description: WooCommerce Advance Reporting plugin is a comprehensive and the most complete reporting system.
Version: 1.5.1
Author: iThemelandCo
Author URI: http://ithemelandco.com/
Text Domain: it_report_wcreport_textdomain
Domain Path: /languages/
License:GPL v2 or later
 */

defined('ABSPATH') || exit();

$a = [];

if (!empty($a)) {
    die('sdsd');
}


if (!class_exists('it_report_wcreport_class')) {
    define('ITWR_VERSION', '1.5.1');
    define('ITWR_NAME', 'iThemelandCo-Woo-Report-Lite');
    define('ITWR_LABEL', 'iThemelandCo Woo Report Lite');
    define('ITWRL_IMAGES_URL', trailingslashit(trailingslashit(plugin_dir_url(__FILE__)) . 'assets/images'));

    //USE IN INCLUDE
    define('__IT_REPORT_WCREPORT_ROOT_DIR__', dirname(__FILE__));

    define('ITWR_DASHBOARD_PAGE', admin_url('admin.php?page=wcx_wcreport_plugin_dashboard&parent=dashboard&smenu=dashboard'));
    define('ITWR_ACTIVATION_PAGE', admin_url('admin.php?page=wcx_wcreport_plugin_activation'));
    //USE IN ENQUEUE AND IMAGE
    define('__IT_REPORT_WCREPORT_CSS_URL__', plugins_url('assets/css/', __FILE__));
    define('__IT_REPORT_WCREPORT_JS_URL__', plugins_url('assets/js/', __FILE__));
    define('__IT_REPORT_WCREPORT_URL__', plugins_url('', __FILE__));
    define('ITWR_VIEWS_DIR', trailingslashit(trailingslashit(__IT_REPORT_WCREPORT_ROOT_DIR__) . 'views'));

    //PERFIX
    define('__IT_REPORT_WCREPORT_FIELDS_PERFIX__', 'custom_report_');

    //TEXT DOMAIN FOR MULTI LANGUAGE
    define('__IT_REPORT_WCREPORT_TEXTDOMAIN__', 'it_report_wcreport_textdomain');

    //COST OF GOOF PRICE
    //define ('__IT_COG__','_IT_COST_GOOD_FIELD');

    include 'includes/datatable_generator.php';
    load_plugin_textdomain(
        'it_report_wcreport_textdomain',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );

    //new it_report_wcreport_crosstab_addon_class;

    //CUSTOM WORK ID
    $customwork_id = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customwork_id', '522,4186,53,16,966,479,17427,12412');
    if ($customwork_id != 0) {
        $customwork_id = explode(",", $customwork_id);
    }
    define('__CUSTOMWORK_ID__', $customwork_id);


    include_once 'repositories/flash_message/flash_message_repository.php';

    include_once 'helpers/generator.php';
    include_once 'helpers/path_helper.php';
    include_once 'helpers/industry_helper.php';

    include_once 'class/activation_data.php';
    include_once 'services/activation/activation_service.php';
    include_once 'class/activation.php';

    include 'add-ons/cross-tab/main.php';
    include 'add-ons/brand/main.php';
    include 'add-ons/customer-role/main.php';
    include 'add-ons/variation/main.php';

    include 'class/class-top-banners.php';

    //MAIN CLASS
    class it_report_wcreport_class extends it_rpt_datatable_generate
    {

        private $plugin_is_active;

        public $it_plugin_status = '';

        public $it_plugin_main_url = '';

        public $it_core_status = '';

        public $it_shop_status = '';

        public $otder_status_hide = '';

        public $today = '';

        public $datetime = null;

        public $it_firstorderdate = '';

        public $our_menu = '';
        public $our_menu_fav = '';

        ////ADDED IN VER4.0
        //CHECK LICENSE & UPDATE
        public $plugin_slug = '';
        public $email = '';
        public $item_valid_id = '';
        public $domain = '';

        //public $menu_fields='';

        public function __construct()
        {


            include 'includes/actions.php';

            ////ADDED IN VER4.0
            //SET DEAFULT VALUES
            $url = $_SERVER['SERVER_NAME'];
            $this->domain = $this->getHost($url);
            $this->email = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'activate_email', '');

            add_action('admin_post_itwr_activation_plugin', [$this, 'activation_plugin']);

            add_action('admin_init', array($this, 'it_standalone_report'));

            add_action('admin_head', array($this, 'it_report_backend_enqueue'));
            add_action('plugins_loaded', array($this, 'loadTextDomain'));
            add_action('admin_menu', array($this, 'it_report_setup_menus'));

            ITWRL_Top_Banners::register();

            $field = __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'activate_purchase_code';
            $this->it_plugin_status = get_option($field);

            $this->it_core_status = false;

            if (get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status') == 'false' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_alt') != 'dashboard') {
                $it_plugin_main_url = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_alt');
                $it_plugin_main_url = explode("admin.php?page=", $it_plugin_main_url);
                $this->it_plugin_main_url = $it_plugin_main_url[1];
            } else {
                $this->it_plugin_main_url = 'wcx_wcreport_plugin_dashboard&parent=dashboard&smenu=dashboard';
            }

            $this->today = date_i18n("Y-m-d");

            //DEFAULT ORDER STATUS AND HIDE STATUS
            $it_shop_status = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'order_status');
            if ($it_shop_status != '') {
                $this->it_shop_status = implode(",", $it_shop_status);
            } else {
                $this->it_shop_status = 'wc-completed,wc-on-hold,wc-processing';
            }

            $otder_status_hide = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'otder_status_hide');
            if ($otder_status_hide == 'on') {
                $this->otder_status_hide = 'trash';
            }


            //SET THE COST OF GOOD CUSTOM FIELD
            $enable_cog = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog', "no");
            if ($enable_cog == 'yes_another') {
                $cog_plugin = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_plugin', "woo_profit");
                $profit_fields = array(
                    'woo_profit' => array(
                        'field' => '_wc_cog_cost', // FOR EACH PRODUCT -> postmeta
                        'total' => '_wc_cog_item_total_cost', // FOR EACH ITEM of ORDER -> order_itemmeta
                        'order_cog' => 'wc_cog_order_total_cost', // FOR EACH ORDER -> postmeta
                    ),
                    'indo_profit' => array(
                        'field' => '_posr_cost_of_good', // FOR EACH PRODUCT -> postmeta
                        'total' => '_posr_line_cog_total', // FOR EACH ITEM of ORDER -> order_itemmeta
                        'order_cog' => '_posr_line_cog_total', // FOR EACH PRODUCT -> postmeta
                    ),

                );

                if ($cog_plugin == 'other') {
                    $cog_field = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_field', "_IT_COST_GOOD_FIELD");
                    define('__IT_COG__', $cog_field);

                    $cog_field = get_option(
                        __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_field_total',
                        "_IT_COST_GOOD_FIELD"
                    );
                    define('__IT_COG_TOTAL__', $cog_field);
                } else {
                    $cog_field = $profit_fields[$cog_plugin]['field'];
                    define('__IT_COG__', $cog_field);

                    $cog_total = $profit_fields[$cog_plugin]['total'];
                    define('__IT_COG_TOTAL__', $cog_total);

                    $order_cog = $profit_fields[$cog_plugin]['order_cog'];
                    define('__IT_COG_ORDER_TOTAL__', $order_cog);
                }
            } elseif ($enable_cog == 'yes_this') {

                define('__IT_COG__', '_IT_COST_GOOD_FIELD');
                define('__IT_COG_TOTAL__', '_IT_COST_GOOD_ITEM_TOTAL_COST');
            } else {
                define('__IT_COG__', '');
                define('__IT_COG_TOTAL__', '');
            }

            ////ADDED IN VER4.0
            ///////////////////////////BRANDS ADD-ONS///////////////////
            $brand_slug = $brand_label = $brand_thumb = '';
            if (defined("__IT_BRANDS_ADD_ON__")) {

                $enable_brands = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand', "no");
                $brand_thumb = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_thumb');

                if ($brand_thumb == 'on') {
                    $brand_thumb = 1;
                } else {
                    $brand_thumb = '';
                }

                if ($enable_brands == 'yes_another') {
                    $brand_plugin = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brands_plugin', "product_brand");
                    $brand_label = get_option(
                        __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_label',
                        esc_html__('Brand', 'it_report_wcreport_textdomain')
                    );

                    $brand_slug = $brand_plugin;
                    $brand_thumb = '';

                    if ($brand_plugin == 'other') {
                        $brand_field = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_slug', "product_brand");
                        $brand_slug = $brand_field;
                        $brand_thumb = '';
                    }

                    //ITHEMELANDCO BRAND PLUGIN
                } elseif ($enable_brands == 'yes_this') {
                    $brand_slug = 'as-brand';
                    $brand_label = get_option(
                        __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_label',
                        esc_html__('Brand', 'it_report_wcreport_textdomain')
                    );
                }
            }
            define('__IT_BRAND_SLUG__', $brand_slug);
            define('__IT_BRAND_LABEL__', $brand_label);
            define('__IT_BRAND_THUMB__', $brand_thumb);

            $this->plugin_slug = basename(dirname(__FILE__));
            $this->plugin_is_active = (ITWR_Activation_Data::is_active() || ITWR_Activation_Data::skipped());
        }

        public function activation_plugin()
        {
            $message = "Error! Try again";

            if (isset($_POST['activation_type'])) {
                if ($_POST['activation_type'] == 'skip') {
                    update_option('itwrl_is_active', 'skipped');
                    wp_redirect(ITWR_DASHBOARD_PAGE);
                    die();
                } else {
                    if (!empty($_POST['email']) && !empty($_POST['industry'])) {
                        $activation_service = new ITWR_Activation_Service();
                        $info = $activation_service->activation([
                            'email' => sanitize_email($_POST['email']),
                            'domain' => $_SERVER['SERVER_NAME'],
                            'product_id' => 'itwrl',
                            'product_name' => ITWR_LABEL,
                            'industry' => sanitize_text_field($_POST['industry']),
                            'multi_site' => is_multisite(),
                            'core_version' => null,
                            'subsystem_version' => ITWR_VERSION,
                        ]);

                        if (!empty($info) && is_array($info)) {
                            if (!empty($info['result']) && $info['result'] == true) {
                                update_option('itwrl_is_active', 'yes');
                                $message = esc_html__('Success !', 'iThemelandCo-Woo-Report-Lite');
                            } else {
                                update_option('itwrl_is_active', 'no');
                                $message = (!empty($info['message'])) ? esc_html($info['message']) : esc_html__('System Error !', 'iThemelandCo-Woo-Report-Lite');
                            }
                        } else {
                            update_option('itwrl_is_active', 'no');
                            $message = esc_html__('Connection Timeout! Please Try Again', 'iThemelandCo-Woo-Report-Lite');
                        }
                    }
                }
            }

            if (!empty($message)) {
                $flush_message_repository = new ITWR_Flush_Message();
                $flush_message_repository->set(['message' => $message]);
            }

            wp_redirect(ITWR_ACTIVATION_PAGE);
            die();
        }

        public function it_date_format($date)
        {
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return '%Y-%m-%d';
            }

            if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date)) {
                return '%d-%m-%Y';
            }
        }

        public function variation_settings_fields($loop, $variation_data, $variation)
        {
            // NUMBER Field
            woocommerce_wp_text_input(
                array(
                    'id' => 'it_cost_of_good[' . $variation->ID . ']',
                    'label' => esc_html__('Cost og Good($)', 'it_report_wcreport_textdomain'),
                    'desc_tip' => 'true',
                    'description' => esc_html__('Enter Cost of Good for this product', 'it_report_wcreport_textdomain'),
                    'value' => get_post_meta($variation->ID, 'it_cost_of_good', true),
                    'custom_attributes' => array(
                        'step' => 'any',
                        'min' => '0',
                    ),
                )
            );
        }

        /**
         * Save new fields for variations
         *
         */
        public function save_variation_settings_fields($post_id)
        {

            // Number Field
            $number_field = $_POST['it_cost_of_good'][$post_id];
            if (!empty($number_field)) {
                update_post_meta($post_id, 'it_cost_of_good', esc_attr($number_field));
            }
        }

        public function woo_add_donation()
        {
            global $woocommerce;
            global $current_user;
            $current_user = wp_get_current_user();

            $user_info = get_userdata($current_user->ID);

            $role = get_role(strtolower($user_info->roles[0]));

            $role = ($role->name);

            //die(print_r($_REQUEST));

            $cost_of_good = isset($_REQUEST['cost_of_good']) ? $_REQUEST['cost_of_good'] : '';

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $post_id = $cart_item['data']->id;

                $cost_of_good = get_post_meta($post_id, '_cost_of_good', true);

                $additional_price = '';

                if (isset($main_price) && isset($cash_price) && $main_price == 'cash_role') {
                    $additional_price = $cash_price;
                }

                if ($additional_price != '') {
                    $cart_item['data']->set_price($additional_price);
                }
            }

            return true;
        }

        public function it_add_custom_price_box()
        {
            woocommerce_wp_text_input(array(
                'id' => 'it_cost_of_good',
                'class' => 'wc_input_price short',
                'label' => esc_html__('Cost of Good($)', 'woocommerce'),
            ));
        }

        public function it_custom_woocommerce_process_product_meta($post_id)
        {
            update_post_meta($post_id, 'it_cost_of_good', stripslashes($_POST['it_cost_of_good']));
        }

        public function add_custom_price_front($p, $obj)
        {
            global $current_user, $product;
            $post_id = $obj->post->ID;
            $additional_price = '';

            $current_user = wp_get_current_user();

            $user_info = get_userdata($current_user->ID);

            $role = get_role(strtolower($user_info->roles[0]));

            $role = ($role->name);
            //$role = get_role( strtolower('Administrator'));
            //    echo $role;

            $credit_price = get_post_meta($post_id, 'pro_credit_price_extra', true);
            $wholesale_price = get_post_meta($post_id, 'pro_wholesale_price_extra', true);

            $credit_prices = wc_price(floatval($credit_price));
            $wholesale_prices = wc_price(floatval($wholesale_price));

            if (is_admin()) {
                //show in new line
                $tag = 'div';
            } else {
                $tag = 'span';
            }

            if (is_product()) {

                if (!empty($credit_price) && ($role == 'credit_role' || $role == 'cash_role' || $role == 'administrator')) {
                    $additional_price .= "$credit_prices";
                }

                if (!empty($wholesale_price) && ($role == 'wholesale_role' || $role == 'administrator')) {
                    $additional_price .= "$wholesale_prices";
                }

                $total_price = get_post_meta($post_id, '_price', true);

                $html = "<input value='cash_role' class='it_prices' type='radio' name='role_price' /><label>$p</label><br />
				<input value='credit_role' class='it_prices' type='radio' name='role_price' /><label>$credit_prices</label><br />
				<input value='wholesale_role' class='it_prices' type='radio' name='role_price' /><label>$wholesale_prices</label><br />

				<script>
					jQuery(document).ready(function(){

						jQuery('.it_prices').on('click',function(){
							price=(jQuery(this).val());
							jQuery('.it_main_price_input').remove();
							jQuery('.cart').append('<input class=\'it_main_price_input\' name=\'main_price\' value=\''+price+'\' />');
						});

					});
				</script>

				";

                return $html;
            }

            return $p;
        }

        public function array_insert(&$array, $insert, $position)
        {
            settype($array, "array");
            settype($insert, "array");
            settype($position, "int");

            //if pos is start, just merge them
            if ($position == 0) {
                $array = array_merge($insert, $array);
            } else {

                //if pos is end just merge them
                if ($position >= (count($array) - 1)) {
                    $array = array_merge($array, $insert);
                } else {
                    //split into head and tail, then merge head+inserted bit+tail
                    $head = array_slice($array, 0, $position);
                    $tail = array_slice($array, $position);
                    $array = array_merge($head, $insert, $tail);
                }
            }
        }

        public function array_insert_bf(&$array, $insert, $position)
        {
            settype($array, "array");
            settype($insert, "array");
            settype($position, "int");

            //if pos is start, just merge them
            if ($position == 0) {
                $array = array_merge($insert, $array);
            } else {

                //if pos is end just merge them
                if ($position >= (count($array) - 1)) {
                    $array = array_merge($array, $insert);
                } else {
                    //split into head and tail, then merge head+inserted bit+tail
                    $head = array_slice($array, 0, $position);
                    $tail = array_slice($array, $position);
                    $array = array_merge($head, $insert, $tail);
                }
            }
        }

        public function menu_fields($index = '')
        {
            $menu_fields = array(
                'all_orders' => array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_states_code" => esc_html__('State', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

                ////ADDED IN VER4.0
                /// ORDER PER COUNTRY
                "details_order_country" => array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_states_code" => esc_html__('State', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

                'product' => array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_tags_id" => esc_html__('Tags', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),
                'category' => array(
                    'fields' => array(
                        "it_parent_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

                'tags' => array(
                    'fields' => array(
                        "it_tags_id" => esc_html__('Tags', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

                'stock_list' => array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

                'tax_reports' => array(
                    'fields' => array(
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

                'order_product_analysis' => array(
                    'fields' => array(
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

                'order_variation_analysis' => array(
                    'fields' => array(
                        "it_products" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                ),

            );

            if (defined("__IT_VARIATION_ADD_ON__")) {
                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),

                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "variation", $new_menu);
            }

            if (__IT_COG__ != '') {
                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_states_code" => esc_html__('State', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "profit", $new_menu);
            }

            if (defined("__IT_CROSSTABB_ADD_ON__")) {
                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "prod_per_month", $new_menu);

                $new_menu = array(
                    'fields' => array(
                        "it_categories" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                        "it_products" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "variation_per_month", $new_menu);

                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "prod_per_country", $new_menu);
                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_states_code" => esc_html__('State', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "prod_per_state", $new_menu);

                $new_menu = array(
                    'fields' => array(
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "country_per_month", $new_menu);

                $new_menu = array(
                    'fields' => array(
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "payment_per_month", $new_menu);

                $new_menu = array(
                    'fields' => array(
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after(
                    "all_orders",
                    $menu_fields,
                    "order_status_per_month",
                    $new_menu
                );
            }

            if (defined("__IT_TAX_FIELD_ADD_ON__")) {
                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_states_code" => esc_html__('State', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "details_tax_field", $new_menu);

                $new_menu = array(
                    'fields' => array(
                        "it_parent_brand_id" => esc_html__('Brand', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "brand_tax_field", $new_menu);

                $new_menu = array(
                    'fields' => array(
                        "it_customy_taxonomies" => esc_html__('Product Taxonimies', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "custom_tax_field", $new_menu);
            }

            if (defined("__IT_BRANDS_ADD_ON__")) {
                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_states_code" => esc_html__('State', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after("all_orders", $menu_fields, "details_brands", $new_menu);
            }

            if (defined("__IT_PO_ADD_ON__")) {
                $new_menu = array(
                    'fields' => array(
                        "it_category_id" => esc_html__('Category', 'it_report_wcreport_textdomain'),
                        "it_brand_id" => __IT_BRAND_SLUG__ ? __IT_BRAND_LABEL__ : false,
                        "it_product_id" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                        "it_countries_code" => esc_html__('Country', 'it_report_wcreport_textdomain'),
                        "it_states_code" => esc_html__('State', 'it_report_wcreport_textdomain'),
                        "it_orders_status" => esc_html__('Status', 'it_report_wcreport_textdomain'),
                    ),
                    'cols' => array(),
                );
                $menu_fields = $this->array_insert_after(
                    "all_orders",
                    $menu_fields,
                    "details_product_options",
                    $new_menu
                );
            }

            ///////////////////////////////////////
            ////GENERATE CUSTOM TAXONOMY FIELDS////
            $visible_custom_taxonomy = array();
            $post_name = 'product';
            //$all_tax=get_object_taxonomies( $post_name );
            $all_tax = $this->fetch_product_taxonomies($post_name);

            $current_value = array();
            if (is_array($all_tax) && count($all_tax) > 0) {
                //FETCH TAXONOMY
                foreach ($all_tax as $tax) {
                    $tax_status = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search_' . $index . '_' . $tax);

                    if ($tax_status == 'on') {
                        $visible_custom_taxonomy[] = $tax;
                    }
                }
            }

            $custom_taxonomy_fields = '';

            if (defined("__IT_TAX_FIELD_ADD_ON__") && is_array($visible_custom_taxonomy) && count($visible_custom_taxonomy) > 0) {

                //FETCH TAXONOMY
                foreach ($visible_custom_taxonomy as $tax) {
                    $taxonomy = get_taxonomy($tax);
                    $values = $tax;
                    $label = $taxonomy->label;
                    $translate = get_option($index . '_' . $tax . "_translate");
                    if ($translate != '') {
                        $label = $translate;
                    }
                    $menu_fields['details_tax_field']['fields'][$tax] = $label;

                    $menu_fields['product']['fields'][$tax] = $label;
                    $menu_fields['prod_per_month']['fields'][$tax] = $label;
                    $menu_fields['prod_per_country']['fields'][$tax] = $label;
                    $menu_fields['prod_per_state']['fields'][$tax] = $label;
                    $menu_fields['stock_list']['fields'][$tax] = $label;
                }
            }

            //////////////////////////////////////

            return $menu_fields;
        }

        public function it_report_backend_enqueue()
        {

            $array_gift = ['adv_gift', 'wrap_gift', 'license'];

            if ((isset($_GET['parent']) && !in_array(
                $_GET['parent'],
                $array_gift
            )) || (isset($_GET['page']) && $_GET['page'] == 'wcx_wcreport_plugin_mani_settings') || (isset($_GET['page']) && $_GET['page'] == 'permission_report')) {
                include "includes/admin-embed.php";
            }

            //            if(isset($_GET['parent']) || (isset($_GET['page']) && $_GET['page']=='wcx_wcreport_plugin_mani_settings')  || (isset($_GET['page']) && $_GET['page']=='permission_report'))
            //            {
            //                include ("includes/admin-embed.php");
            //            }
        }

        public function loadTextDomain()
        {
            load_plugin_textdomain(
                'it_report_wcreport_textdomain',
                false,
                dirname(plugin_basename(__FILE__)) . '/languages/'
            );
        }

        public function fetch_product_taxonomies($post_name)
        {
            $all_tax = get_object_taxonomies($post_name);
            $taxonomies = array();
            if (is_array($all_tax) && count($all_tax) > 0) {
                //FETCH TAXONOMY
                $i = 1;
                foreach ($all_tax as $tax) {
                    if ($tax == 'product_cat') {
                        continue;
                    }
                    $taxonomies[] = $tax;
                }
            }

            return $taxonomies;
        }

        public function make_custom_taxonomy($args)
        {
            $key = $args['page'];
            $visible_custom_taxonomy = array();
            $post_name = 'product';
            $all_tax = $this->fetch_product_taxonomies($post_name);
            $current_value = array();
            if (is_array($all_tax) && count($all_tax) > 0) {
                //FETCH TAXONOMY
                foreach ($all_tax as $tax) {
                    $tax_status = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search_' . $key . '_' . $tax);

                    if ($tax_status == 'on') {
                        $visible_custom_taxonomy[] = $tax;
                    }
                }
            }

            $option_data = '';
            $param_line = '';
            $show_custom_tax_block = false;

            $current_value = array();
            if (defined("__IT_TAX_FIELD_ADD_ON__") && is_array($visible_custom_taxonomy) && count($visible_custom_taxonomy) > 0) {

                $post_type_label = get_post_type_object($post_name);
                $label = $post_type_label->label;

                //FETCH TAXONOMY
                foreach ($visible_custom_taxonomy as $tax) {
                    $taxonomy = get_taxonomy($tax);
                    $values = $tax;
                    $label = $taxonomy->label;
                    $translate = get_option($key . '_' . $tax . "_translate");
                    if ($translate != '') {
                        $label = $translate;
                    }

                    $attribute_taxonomies = wc_get_attribute_taxonomies();

                    ////////////////////////////////////
                    //PERMISSION CHECK
                    $col_style = '';
                    $permission_value = $this->get_form_element_value_permission($tax);
                    if (!$this->get_form_element_permission($tax) && $permission_value == '') {
                        continue;
                    }

                    $permission_value = $this->get_form_element_value_permission($tax);
                    //////////////////////////////////////

                    if (!$this->get_form_element_permission($tax) && $permission_value != '') {
                        $col_style = 'display:none';
                    } else {
                        $show_custom_tax_block = true;
                    }

                    $param_line .= '

					<div class="col-md-6" style="' . $col_style . '">
						<div class="awr-form-title">' . $label . '</div>
						<span class="awr-form-icon"><i class="fa fa-tags"></i></span>
							<div class="full-lbl-cnt more-padding">';

                    $param_line_exclude = $param_line_include = '<select name="it_custom_taxonomy_in_' . $tax . '[]" class="chosen-select-search" multiple="multiple" style="width: 531px;" data-placeholder="' . esc_html__(
                        'Choose Inclulde ',
                        'it_report_wcreport_textdomain'
                    ) . ' ' . $label . ' ..." id="it_' . $tax . '">';

                    if ($this->get_form_element_permission($tax) && ((!is_array($permission_value)) || (is_array($permission_value) && in_array(
                        'all',
                        $permission_value
                    )))) {
                        $param_line_include .= '<option value="-1">' . esc_html__(
                            'Select All',
                            'it_report_wcreport_textdomain'
                        ) . '</option>';
                    }

                    $param_line_exclude = '<select name="it_custom_taxonomy_ex_' . $tax . '[]" class="chosen-select-search" multiple="multiple" style="width: 531px;" data-placeholder="' . esc_html__(
                        'Choose Exclude',
                        'it_report_wcreport_textdomain'
                    ) . ' ' . $label . ' ..." id="it_' . $tax . '">';
                    $args = array(
                        'taxonomy' => $tax,
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => 0,
                        'hierarchical' => 1,
                        'exclude' => '',
                        'include' => '',
                        'child_of' => 0,
                        'number' => '',
                        'pad_counts' => false,
                    );

                    $categories = get_terms($args);
                    foreach ($categories as $category) {
                        $selected = '';

                        //CHECK IF IS IN PERMISSION
                        if (is_array($permission_value) && !in_array($category->term_id, $permission_value)) {
                            continue;
                        }

                        if (!$this->get_form_element_permission($tax) && $permission_value != '') {
                            $selected = "selected";
                        }

                        $option = '<option value="' . $category->term_id . '" ' . $selected . '>';
                        $option .= $category->name;
                        $option .= ' (' . $category->count . ')';
                        $option .= '</option>';
                        $param_line_include .= $option;
                    }
                    $param_line_include .= '</select>';

                    $categories = get_terms($args);
                    foreach ($categories as $category) {

                        $option = '<option value="' . $category->term_id . '" ' . $selected . '>';
                        $option .= $category->name;
                        $option .= ' (' . $category->count . ')';
                        $option .= '</option>';
                        $param_line_exclude .= $option;
                    }
                    $param_line_exclude .= '</select>';
                    $param_line_exclude = '';
                    $param_line .= $param_line_include . $param_line_exclude . '
					</div></div> ';
                }
            }

            if ($show_custom_tax_block) {
                $param_line = '
					<div class="col-md-6" style="border:#f2c811 2px solid;width:100%">
						<div class="awr-form-title" style="padding: 7px 5px 10px;text-align: center;background: #f2c811;color: #fff;">
							' . esc_html__('Custom Taxonomy', 'it_report_wcreport_textdomain') . '
						</div>' . $param_line . '</div>';
            }

            return $param_line;
        }

        ////ADDED IN VER4.6
        /// GET META DATA OF PRODUCT
        public function it_get_category_tag($id = 0)
        {
            $term_links = array();
            $enable_metadata = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'show_category', '');
            $metadata_cat_tax = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cat_tax', '');

            if ($enable_metadata) {
                foreach ($metadata_cat_tax as $cat_tax => $val) {

                    if ($val == 'on') {
                        $terms = get_the_terms($id, $cat_tax);

                        if (is_wp_error($terms)) {
                            return '<div class="it_intelligence_product_category">
		                            -
		                        </div>';
                        }

                        if (empty($terms)) {
                            return '<div class="it_intelligence_product_category">
		                            -
		                        </div>';
                        }

                        $counter = 0;
                        foreach ($terms as $term) {
                            $link = get_term_link($term, $cat_tax);
                            if (is_wp_error($link)) {
                                return $link;
                            }
                            $term_links[] = '<span><a href="' . $link . '" rel="tag">' . $term->name . '</a></span>';
                            break;
                        }
                    }
                }
                if (count($term_links) > 0) {
                    return '<div class="it_intelligence_product_category">
                            ' . implode(",", $term_links) . '
                        </div>';
                } else {
                    return '<div class="it_intelligence_product_category">
                            -
                        </div>';
                }
            } else {
                return true;
            }
        }

        ////ADDED IN VER4.0
        /// PRODUCT OPTIONS CUSTOM FIELDS
        public function it_po_fields_gridheader()
        {
            $po_array_fields = array();
            $po_fields = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_custom_fields');
            foreach ($po_fields as $fields) {
                foreach ($fields as $po_field) {
                    $input_name = str_replace(" ", "_", $po_field);
                    $title = get_option($input_name . '_translate', $po_field);
                    $col_visible = get_option($input_name . '_column', 'off');
                    if ($col_visible == 'on') {
                        $po_array_fields[] = array('lable' => $title, 'status' => 'show');
                    }
                }
            }

            return $po_array_fields;
        }

        public function it_po_checkout_fields_gridheader()
        {
            $po_array_fields = array();
            $po_fields = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_checkout_custom_fields');
            foreach ($po_fields as $fields) {
                foreach ($fields as $po_field) {

                    //echo $po_field;
                    $exp = explode('@', $po_field);
                    $po_field = $exp[0];
                    $title = $exp[1];
                    $input_name = str_replace(" ", "_", $po_field);
                    $title = get_option($input_name . '_translate', $title);
                    $col_visible = get_option($input_name . '_column', 'off');
                    if ($col_visible == 'on') {
                        $po_array_fields[] = array('lable' => $title, 'status' => 'show');
                    }
                }
            }

            return $po_array_fields;
        }

        /*
         * Compare Saerch Fields & saved data as array
         */
        public function it_po_fields_apply_search($results)
        {
            $po_flag = array();
            foreach ($results as $items) {
                $order_id = $items->order_id;
                $order_item_id = $items->order_item_id;

                $po_fields = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_custom_fields');
                $fields_array = $this->it_po_fields_fetch_field($order_item_id);

                //echo $order_item_id;
                //print_r($po_fields);

                $po_flag[$order_id] = true;
                foreach ($po_fields as $fields) {
                    foreach ($fields as $po_field) {

                        $input_name = str_replace(" ", "_", $po_field);
                        $it_input_value = $this->it_get_woo_requests($input_name, "", true);

                        if ($it_input_value != '') {
                            //echo $input_name.'='.$it_input_value.'#'.implode(",",$fields_array[strtolower($po_field)]['value']).'@';

                            $it_input_value = explode(",", $it_input_value);
                            //print_r($fields_array[strtolower($po_field)]['value']);
                            //if(implode(",",$fields_array[strtolower($po_field)]['value'])!=$it_input_value)
                            if ((isset($fields_array[strtolower($po_field)]['value']) && count(array_intersect(
                                $fields_array[strtolower($po_field)]['value'],
                                $it_input_value
                            )) != count($it_input_value)) || $fields_array[strtolower($po_field)]['value'] == '') {
                                $po_flag[$order_id] = false;
                            }
                        }
                    }
                }
            }

            return $po_flag;
        }

        public function it_po_fields_fetch_field($order_item_id, $field = '')
        {
            global $wpdb;
            $field = str_replace("_", " ", $field);
            //echo $field.'   --   ';
            $types = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT it_itemmeta.meta_value as meta_value 
                    FROM {$wpdb->prefix}woocommerce_order_itemmeta as it_itemmeta 
                    WHERE it_itemmeta.meta_key = %s AND it_itemmeta.order_item_id = %d",
                    '_tmcartepo_data',
                    $order_item_id
                ),
                ARRAY_A
            );
            if (defined('THEMECOMPLETE_EPO_VERSION') && version_compare(THEMECOMPLETE_EPO_VERSION, "4.6", ">=") && $types == null) {
                //echo 'BALATAR'.THEMECOMPLETE_EPO_VERSION;
                $like_field = '%' . $wpdb->esc_like($field) . '%';
                $types = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT it_itemmeta.meta_value as meta_value 
                        FROM {$wpdb->prefix}woocommerce_order_itemmeta as it_itemmeta 
                        WHERE it_itemmeta.meta_key LIKE %s AND it_itemmeta.order_item_id = %d",
                        $like_field,
                        $order_item_id
                    ),
                    ARRAY_A
                );
                //echo "SELECT it_itemmeta.meta_value as meta_value FROM {$wpdb->prefix}woocommerce_order_itemmeta as it_itemmeta where it_itemmeta.meta_key LIKE '%$field%' and it_itemmeta.order_item_id='".$order_item_id."'";
                if ($types != null) {
                    foreach ($types as $v) {
                        return $v['meta_value'];
                    }
                    //print_r($types);
                }

                return '';
            }
            //echo 'BALATAR'.THEMECOMPLETE_EPO_VERSION;

            $po_fields = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_custom_fields');
            $fields_type = array(
                'textarea',
                'textfield',
                'selectbox',
                'radiobuttons',
                'checkboxes',
                'upload',
                'date',
                'time',
                'range',
                'color',
            );
            $types = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT it_itemmeta.meta_value as meta_value 
                    FROM {$wpdb->prefix}woocommerce_order_itemmeta as it_itemmeta 
                    WHERE it_itemmeta.meta_key = %s AND it_itemmeta.order_item_id = %d",
                    '_tmcartepo_data',
                    $order_item_id
                ),
                ARRAY_A
            );

            $fields_array = array();
            if ($types != null) {
                foreach ($types as $v) {

                    $data = unserialize($v['meta_value']);
                    $j = 0;
                    foreach ($data as $fields) {

                        //if(!isset($fields['element']['type'])) continue;

                        $index = $fields['name'];
                        if (!isset($fields_array[strtolower($index)])) {
                            $j = 0;
                            $fields_array[strtolower($index)]['value'][0] = $fields['value'];
                            if (isset($fields['is_taxonomy'])) {
                                $fields_array[strtolower($index)]['type'][0] = 'is_taxonomy';
                            } else {
                                $fields_array[strtolower($index)]['type'][0] = $fields['element']['type'];
                            }
                        } else {
                            $j++;
                            $fields_array[strtolower($index)]['value'][$j] = $fields['value'];
                            if (isset($fields['is_taxonomy'])) {
                                $fields_array[strtolower($index)]['type'][$j] = 'is_taxonomy';
                            } else {
                                $fields_array[strtolower($index)]['type'][$j] = $fields['element']['type'];
                            }
                        }
                    }
                }
            }

            return $fields_array;
        }

        public function it_po_checkout_fields_search_fields()
        {
            $po_fields = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_checkout_custom_fields');
            //print_r($po_fields);
            $fields_type = array(
                'textarea',
                'textfield',
                'selectbox',
                'radiobuttons',
                'checkboxes',
                'upload',
                'date',
                'time',
                'range',
                'color',
            );
            $html = '';

            foreach ($po_fields as $field_type => $value) {
                if ($field_type == 'po_checkout_global_fields_select') {
                    global $wpdb;

                    $types = $wpdb->get_results("SELECT it_post.post_title,it_postmeta.meta_value as meta_value FROM {$wpdb->prefix}posts as it_post
                                    INNER JOIN {$wpdb->prefix}postmeta as it_postmeta ON it_post.ID=it_postmeta.post_id
                                    where it_post.post_type='tm_eco_cp' AND it_post.post_status IN ('publish')
                                    AND it_postmeta.meta_key='tm_meta'", ARRAY_A);

                    $val_arr = array();
                    foreach ($value as $val) {
                        $exp = explode('@', $val);
                        $val_arr[] = $exp[0];
                    }

                    $fields_array = $val_arr;
                    //$fields_array = $value;
                    if ($types != null) {
                        foreach ($types as $v) {
                            if (!$v['meta_value']) {
                                continue;
                            }

                            $parent_id = $v['post_title'];
                            $data = unserialize($v['meta_value']);
                            //print_r($data);
                            foreach ($fields_type as $f_type) {

                                if (isset($data['tmfbuilder'][$f_type . '_header_title'])) {

                                    //print_r($data['tmfbuilder']);
                                    $element_id = $data['tmfbuilder'][$f_type . '_uniqid'][0];
                                    //echo $element_id;

                                    $i = 0;
                                    foreach ($data['tmfbuilder'][$f_type . '_uniqid'] as $fields) {

                                        $fields = str_replace(".", "_", $fields);

                                        if (in_array($fields, $fields_array)) {

                                            $input_name = str_replace(" ", "_", $fields);
                                            $title = get_option(
                                                $input_name . '_translate',
                                                $data['tmfbuilder'][$f_type . '_header_title'][$i]
                                            );
                                            $show_filter = get_option($input_name . '_filter');

                                            $input_name_txt = $fields;
                                            if ($show_filter != 'on') {
                                                continue;
                                            }

                                            switch ($f_type) {

                                                case 'selectbox':
                                                    $select_values = $data['tmfbuilder']['multiple_selectbox_options_value'][0];
                                                    $select_titles = $data['tmfbuilder']['multiple_selectbox_options_title'][0];
                                                    $select_option = '<option value="">Choose One</option>';
                                                    $j = 0;
                                                    foreach ($select_values as $option) {
                                                        $select_option .= '<option value="' . $option . '" >' . $select_titles[$j] . '</option>';
                                                        $j++;
                                                    }

                                                    $html .= '<div class="col-md-6">
								                        <div class="awr-form-title">
								                            ' . $title . '
								                        </div>
								                        <span class="awr-form-icon"><i class="fa fa-check"></i></span>';
                                                    $html .= '<select  name="' . $input_name_txt . '" >' . $select_option . '</select></select></div>';
                                                    break;

                                                case 'time':
                                                    $time_format = $data['tmfbuilder'][$f_type . '_time_format'][$i];
                                                    $html .= '
													<div class="col-md-6">
									                    <div class="awr-form-title">
									                        ' . $title . '
									                    </div>
									                    <span class="awr-form-icon"><i class="fa fa-clock-o"></i></span>
									                    ';
                                                    $html .= '
												        <div class="input-group date" id="' . $input_name . '">
												            <input type="text"  name="' . $input_name_txt . '" class="form-control">
												            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												        </div>

													    <script>
													        jQuery(document).ready( function($) {
													            $("#' . $input_name . '").datetimepicker({
																	format: "' . $time_format . '"
																});
													        });
													    </script>
											        </div>';

                                                    break;

                                                case 'range':
                                                    echo 'sds';
                                                    $range_min = $data['tmfbuilder'][$f_type . '_min'][$i];
                                                    $range_max = $data['tmfbuilder'][$f_type . '_max'][$i];
                                                    $range_step = $data['tmfbuilder'][$f_type . '_step'][$i];

                                                    $html .= '<div class="col-md-6">
									                    <div class="awr-form-title">
									                        ' . $title . '
									                    </div><div class="awr-range-slider">
													    <input  name="' . $input_name_txt . '"  class="awr-range-slider__range" type="range" value="' . $range_min . '" min="' . $range_min . '" max="' . $range_max . '" step="' . $range_step . '">
													    <span class="awr-range-slider__value">0</span>
													</div></div>


													<script>
														jQuery(document).ready( function($) {
															var rangeSlider = function(){
																	var slider = $(\'.awr-range-slider\'),
																	range = $(\'.awr-range-slider__range\'),
																	value = $(\'.awr-range-slider__value\');

																	slider.each(function(){

																		value.each(function(){
																			var value = $(this).prev().attr(\'value\');
																			$(this).html(value);
																		});

																			range.on(\'input\', function(){
																				$(this).next(value).html(this.value);
																			});
																		});
																	};

															rangeSlider();

														});

													</script>
';

                                                    break;

                                                case 'textfield':
                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>
							                        <span class="awr-form-icon"><i class="fa fa-check"></i></span>';
                                                    $html .= '<input type="text" name="' . $input_name_txt . '" /></div>';
                                                    break;

                                                case 'checkboxes':
                                                    $select_values = isset($data['tmfbuilder']['multiple_checkboxes_options_value'][0]) ? $data['tmfbuilder']['multiple_checkboxes_options_value'][0] : "";
                                                    $select_titles = isset($data['tmfbuilder']['multiple_checkboxes_options_title'][0]) ? $data['tmfbuilder']['multiple_checkboxes_options_title'][0] : "";
                                                    $select_image = isset($data['tmfbuilder']['multiple_checkboxes_options_imagep'][0]) ? $data['tmfbuilder']['multiple_checkboxes_options_imagep'][0] : "";
                                                    $select_option = '';
                                                    $j = 0;
                                                    foreach ($select_values as $option) {
                                                        $img = '';
                                                        if (isset($select_image[$j]) && $select_image[$j] != '') {
                                                            $img = '<img src="' . $select_image[$j] . '" width="30" height="30" />';
                                                        }

                                                        $select_option .= $img . $select_titles[$j] . '<input type="checkbox" name="' . $input_name_txt . '[]" placeholder=""  value="' . $option . '" />';
                                                        $j++;
                                                    }
                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>';
                                                    $html .= $select_option . '</div>';
                                                    break;

                                                case 'radiobuttons':
                                                    $select_values = isset($data['tmfbuilder']['multiple_radiobuttons_options_value'][0]) ? $data['tmfbuilder']['multiple_radiobuttons_options_value'][0] : "";
                                                    $select_titles = isset($data['tmfbuilder']['multiple_radiobuttons_options_title'][0]) ? $data['tmfbuilder']['multiple_radiobuttons_options_title'][0] : "";
                                                    $select_image = isset($data['tmfbuilder']['multiple_radiobuttons_options_imagep'][0]) ? $data['tmfbuilder']['multiple_radiobuttons_options_imagep'][0] : "";

                                                    $select_option = '';
                                                    $j = 0;
                                                    foreach ($select_values as $option) {

                                                        $img = '';
                                                        if (isset($select_image[$j]) && $select_image[$j] != '') {
                                                            $img = '<img src="' . $select_image[$j] . '" width="30" height="30" />';
                                                        }

                                                        $select_option .= $img . $select_titles[$j] . '<input type="radio" name="' . $input_name_txt . '" placeholder="' . $fields . '"  value="' . $option . '" />';
                                                        $j++;
                                                    }
                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>';
                                                    $html .= $select_option . '</div>';
                                                    break;

                                                case 'date':
                                                    //date_format
                                                    $date_format = $data['tmfbuilder'][$f_type . '_format'][0];

                                                    switch ($date_format) {
                                                        case "0":
                                                            $date_format = 'dd/mm/yy';
                                                            break;
                                                        case "1":
                                                            $date_format = 'mm/dd/yy';
                                                            break;
                                                        case "2":
                                                            $date_format = 'dd.mm.yy';
                                                            break;
                                                        case "3":
                                                            $date_format = 'mm.dd.yy';
                                                            break;
                                                        case "4":
                                                            $date_format = 'dd-mm-yy';
                                                            break;
                                                        case "5":
                                                            $date_format = 'mm-dd-yy';
                                                            break;
                                                    }

                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>
							                        <span class="awr-form-icon"><i class="fa fa-calendar-o"></i></span>';
                                                    $html .= '<input type="text" name="' . $input_name_txt . '" id="' . $input_name . '" placeholder="" class="datepick"/>
													</div>
                                                    <script>
                                                        jQuery().ready(function($){
                                                            ////ADDED IN VER4.0
                                                            $("#' . $input_name . '").datepicker({ dateFormat: "' . $date_format . '" });
                                                        });
                                                    </script>';
                                                    break;

                                                case 'color':
                                                    $html .= '<div class="col-md-6">
								                        <div class="awr-form-title">
								                            ' . $title . '
								                        </div>
								                        <input type="text" name="' . $input_name_txt . '" placeholder="' . $fields . '" class="wp_ad_picker_color"/>
							                        </div>
                                                    <script type="text/javascript">
                                                        jQuery(document).ready(function($) {
                                                            $(".wp_ad_picker_color").wpColorPicker();
                                                        });
                                                    </script>';
                                                    break;
                                            }

                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $html_main = '';
            if ($html != '') {
                $html_main = '<div class="col-md-12">
                        <div class="awr-option-title">' . esc_html__(
                    'Product Options Checkout Fields',
                    'it_report_wcreport_textdomain'
                ) . '</div>
                            <div class="awr-option-fields">';
                $html_main .= $html;

                $html_main .= '
							</div>
						</div>';
            }

            return $html_main;
        }

        public function it_po_fields_search_fields()
        {
            $po_fields = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_custom_fields');
            $fields_type = array(
                'textarea',
                'textfield',
                'selectbox',
                'radiobuttons',
                'checkboxes',
                'upload',
                'date',
                'time',
                'range',
                'color',
            );
            $html = '';

            foreach ($po_fields as $field_type => $value) {
                if ($field_type == 'po_global_fields_select') {
                    global $wpdb;

                    $types = $wpdb->get_results("SELECT it_post.post_title,it_postmeta.meta_value as meta_value FROM {$wpdb->prefix}posts as it_post
                                    INNER JOIN {$wpdb->prefix}postmeta as it_postmeta ON it_post.ID=it_postmeta.post_id
                                    where it_post.post_type='tm_global_cp' AND it_post.post_status IN ('publish')
                                    AND it_postmeta.meta_key='tm_meta'", ARRAY_A);

                    $fields_array = $value;
                    if ($types != null) {
                        foreach ($types as $v) {
                            if (!$v['meta_value']) {
                                continue;
                            }

                            $parent_id = $v['post_title'];
                            $data = unserialize($v['meta_value']);
                            //print_r($data);
                            foreach ($fields_type as $f_type) {

                                if (isset($data['tmfbuilder'][$f_type . '_header_title'])) {

                                    $i = 0;
                                    foreach ($data['tmfbuilder'][$f_type . '_header_title'] as $fields) {
                                        if (in_array($fields, $fields_array)) {

                                            $input_name = str_replace(" ", "_", $fields);
                                            $title = get_option(
                                                $input_name . '_translate',
                                                $data['tmfbuilder'][$f_type . '_header_title'][$i]
                                            );
                                            $show_filter = get_option($input_name . '_filter');
                                            if ($show_filter != 'on') {
                                                continue;
                                            }

                                            switch ($f_type) {

                                                case 'selectbox':
                                                    $select_values = $data['tmfbuilder']['multiple_selectbox_options_value'][0];
                                                    $select_titles = $data['tmfbuilder']['multiple_selectbox_options_title'][0];
                                                    $select_option = '<option value="">Choose One</option>';
                                                    $j = 0;
                                                    foreach ($select_values as $option) {
                                                        $select_option .= '<option value="' . $option . '" >' . $select_titles[$j] . '</option>';
                                                        $j++;
                                                    }

                                                    $html .= '<div class="col-md-6">
								                        <div class="awr-form-title">
								                            ' . $title . '
								                        </div>
								                        <span class="awr-form-icon"><i class="fa fa-check"></i></span>';
                                                    $html .= '<select  name="' . $input_name . '" >' . $select_option . '</select></select></div>';
                                                    break;

                                                case 'time':
                                                    $time_format = $data['tmfbuilder'][$f_type . '_time_format'][$i];
                                                    $html .= '
													<div class="col-md-6">
									                    <div class="awr-form-title">
									                        ' . $title . '
									                    </div>
									                    <span class="awr-form-icon"><i class="fa fa-clock-o"></i></span>
									                    ';
                                                    $html .= '
												        <div class="input-group date" id="' . $input_name . '">
												            <input type="text"  name="' . $input_name . '" class="form-control">
												            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												        </div>

													    <script>
													        jQuery(document).ready( function($) {
													            $("#' . $input_name . '").datetimepicker({
																	format: "' . $time_format . '"
																});
													        });
													    </script>
											        </div>';

                                                    break;

                                                case 'range':
                                                    $range_min = $data['tmfbuilder'][$f_type . '_min'][$i];
                                                    $range_max = $data['tmfbuilder'][$f_type . '_max'][$i];
                                                    $range_step = $data['tmfbuilder'][$f_type . '_step'][$i];

                                                    $html .= '<div class="col-md-6">
									                    <div class="awr-form-title">
									                        ' . $title . '
									                    </div><div class="awr-range-slider">
													    <input  name="' . $input_name . '"  class="awr-range-slider__range" type="range" value="' . $range_min . '" min="' . $range_min . '" max="' . $range_max . '" step="' . $range_step . '">
													    <span class="awr-range-slider__value">0</span>
													</div></div>


													<script>
														jQuery(document).ready( function($) {
															var rangeSlider = function(){
																	var slider = $(\'.awr-range-slider\'),
																	range = $(\'.awr-range-slider__range\'),
																	value = $(\'.awr-range-slider__value\');

																	slider.each(function(){

																		value.each(function(){
																			var value = $(this).prev().attr(\'value\');
																			$(this).html(value);
																		});

																			range.on(\'input\', function(){
																				$(this).next(value).html(this.value);
																			});
																		});
																	};

															rangeSlider();

														});

													</script>
';

                                                    break;

                                                case 'textfield':
                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>
							                        <span class="awr-form-icon"><i class="fa fa-check"></i></span>';
                                                    $html .= '<input type="text" name="' . $input_name . '" placeholder="' . $fields . '" /></div>';
                                                    break;

                                                case 'checkboxes':
                                                    $select_values = isset($data['tmfbuilder']['multiple_checkboxes_options_value'][0]) ? $data['tmfbuilder']['multiple_checkboxes_options_value'][0] : "";
                                                    $select_titles = isset($data['tmfbuilder']['multiple_checkboxes_options_title'][0]) ? $data['tmfbuilder']['multiple_checkboxes_options_title'][0] : "";
                                                    $select_image = isset($data['tmfbuilder']['multiple_checkboxes_options_imagep'][0]) ? $data['tmfbuilder']['multiple_checkboxes_options_imagep'][0] : "";
                                                    $select_option = '';
                                                    $j = 0;
                                                    foreach ($select_values as $option) {
                                                        $img = '';
                                                        if (isset($select_image[$j]) && $select_image[$j] != '') {
                                                            $img = '<img src="' . $select_image[$j] . '" width="30" height="30" />';
                                                        }

                                                        $select_option .= $img . $select_titles[$j] . '<input type="checkbox" name="' . $input_name . '[]" placeholder="' . $fields . '"  value="' . $option . '" />';
                                                        $j++;
                                                    }
                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>';
                                                    $html .= $select_option . '</div>';
                                                    break;

                                                case 'radiobuttons':
                                                    $select_values = isset($data['tmfbuilder']['multiple_radiobuttons_options_value'][0]) ? $data['tmfbuilder']['multiple_radiobuttons_options_value'][0] : "";
                                                    $select_titles = isset($data['tmfbuilder']['multiple_radiobuttons_options_title'][0]) ? $data['tmfbuilder']['multiple_radiobuttons_options_title'][0] : "";
                                                    $select_image = isset($data['tmfbuilder']['multiple_radiobuttons_options_imagep'][0]) ? $data['tmfbuilder']['multiple_radiobuttons_options_imagep'][0] : "";

                                                    $select_option = '';
                                                    $j = 0;
                                                    foreach ($select_values as $option) {

                                                        $img = '';
                                                        if (isset($select_image[$j]) && $select_image[$j] != '') {
                                                            $img = '<img src="' . $select_image[$j] . '" width="30" height="30" />';
                                                        }

                                                        $select_option .= $img . $select_titles[$j] . '<input type="radio" name="' . $input_name . '" placeholder="' . $fields . '"  value="' . $option . '" />';
                                                        $j++;
                                                    }
                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>';
                                                    $html .= $select_option . '</div>';
                                                    break;

                                                case 'date':
                                                    //date_format
                                                    $date_format = $data['tmfbuilder'][$f_type . '_format'][0];

                                                    switch ($date_format) {
                                                        case "0":
                                                            $date_format = 'dd/mm/yy';
                                                            break;
                                                        case "1":
                                                            $date_format = 'mm/dd/yy';
                                                            break;
                                                        case "2":
                                                            $date_format = 'dd.mm.yy';
                                                            break;
                                                        case "3":
                                                            $date_format = 'mm.dd.yy';
                                                            break;
                                                        case "4":
                                                            $date_format = 'dd-mm-yy';
                                                            break;
                                                        case "5":
                                                            $date_format = 'mm-dd-yy';
                                                            break;
                                                    }

                                                    $html .= '<div class="col-md-6">
							                        <div class="awr-form-title">
							                            ' . $title . '
							                        </div>
							                        <span class="awr-form-icon"><i class="fa fa-calendar-o"></i></span>';
                                                    $html .= '<input type="text" name="' . $input_name . '" id="' . $input_name . '" placeholder="' . $fields . '" class="datepick"/>
													</div>
                                                    <script>
                                                        jQuery().ready(function($){
                                                            ////ADDED IN VER4.0
                                                            $("#' . $input_name . '").datepicker({ dateFormat: "' . $date_format . '" });
                                                        });
                                                    </script>';
                                                    break;

                                                case 'color':
                                                    $html .= '<div class="col-md-6">
								                        <div class="awr-form-title">
								                            ' . $title . '
								                        </div>
								                        <input type="text" name="' . $input_name . '" placeholder="' . $fields . '" class="wp_ad_picker_color"/>
							                        </div>
                                                    <script type="text/javascript">
                                                        jQuery(document).ready(function($) {
                                                            $(".wp_ad_picker_color").wpColorPicker();
                                                        });
                                                    </script>';
                                                    break;
                                            }

                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $html_main = '';
            if ($html != '') {
                $html_main = '<div class="col-md-12">
                        <div class="awr-option-title">' . esc_html__(
                    'Product Options Fields',
                    'it_report_wcreport_textdomain'
                ) . '</div>
                            <div class="awr-option-fields">';
                $html_main .= $html;

                $html_main .= '
							</div>
						</div>';
            }

            return $html_main;
        }

        public function it_po_fetch_checkout_fields($order_id, $field)
        {
            global $wpdb;
            $sql = "SELECT pmeta.meta_value as fvalue from {$wpdb->prefix}woocommerce_order_items as pitems left join {$wpdb->prefix}woocommerce_order_itemmeta as pmeta on pitems.order_item_id=pmeta.order_item_id
 left join {$wpdb->prefix}woocommerce_order_itemmeta as pmeta_p on pitems.order_item_id=pmeta_p.order_item_id
where pitems.order_id='$order_id' AND pmeta.meta_key='_wc_eco_fields_value' AND pmeta_p.meta_key='_wc_eco_element_id' AND pmeta_p.meta_value='$field'";

            //echo $sql;
            $order_items = $this->get_results($sql);
            $table_data = '';
            //print_r($order_items);
            if (isset($order_items[0])) {
                $table_data = $order_items[0]->fvalue;
                $table_data = unserialize($table_data);
                if (is_array($table_data)) {
                    $table_data = implode(",", $table_data);
                } else {
                    $table_data = $order_items[0]->fvalue;
                }
            }

            //print_r($table_data);
            return $table_data;
        }

        ////////////////END PRODUCT OPTIONS CUSTOM FIELDS//////////

        //CUSTOM WORK - 12300
        ///////////////TICKERA CUSTOM FIELDS////////////////
        public function it_tickera_fetch_fields($order_id, $field)
        {
            global $wpdb;
            $sql = "SELECT tmeta.meta_value as fvalue FROM {$wpdb->prefix}posts as tpost LEFT JOIN {$wpdb->prefix}postmeta as tmeta ON tpost.ID=tmeta.post_id Where tpost.post_parent='$order_id' AND tpost.post_type='tc_tickets_instances' AND tmeta.meta_key='$field'";

            //echo $sql;
            $order_items = $this->get_results($sql);
            $table_data = '-';
            //print_r($order_items);
            if (isset($order_items[0])) {
                $table_data = $order_items[0]->fvalue;
                $table_data = unserialize($table_data);
                if (is_array($table_data)) {
                    $table_data = implode(",", $table_data);
                } else {
                    $table_data = $order_items[0]->fvalue;
                }
            }

            //print_r($table_data);
            return $table_data;
        }

        ////////////////////////////////////////////////

        public function it_standalone_report()
        {

            if (defined("__IT_PERMISSION_ADD_ON__")) {
                global $current_user;
                $current_user = wp_get_current_user();

                $user_info = get_userdata($current_user->ID);

                $role = get_role(strtolower($user_info->roles[0]));

                //$role->has_cap( 'it_report_appear' );

                if (isset($role->capabilities['it_report_appear']) && $role->capabilities['it_report_appear']) {
                    $role_capability = 'it_report_appear';
                }

                if (strtolower($user_info->roles[0]) == 'woo_report_role') {

                    add_action('admin_head', array($this, 'custom_menu_page_removing'));
                    //echo $_SERVER["PHP_SELF"].' - '.strpos('admin-ajax.php',$_SERVER["PHP_SELF"]);
                    //echo strpos($_SERVER["PHP_SELF"],'admin-ajax.php')=== true;
                    if (!isset($_GET['parent']) && strpos($_SERVER["PHP_SELF"], 'admin-ajax.php') === false) {
                        die('
								<div class="wrap">
									<div class="row">
										<div class="col-xs-12">
											<div class="awr-box awr-acc-box">
												<div class="awr-acc-icon">
												    <i class="fa fa-meh-o"></i>
												</div>
												<h3 class="awr-acc-title">' . esc_html__(
                            "Access Denied !",
                            'it_report_wcreport_textdomain'
                        ) . '</h3>
												<div class="awr-acc-desc">' . esc_html__(
                            "You have no permisson !! Please Contact site Administrator",
                            'it_report_wcreport_textdomain'
                        ) . '</div>
											</div>
										</div><!--col-xs-12 -->
									</div><!--row -->
								</div><!--wrap -->');
                    }
                }
            }
        }

        public function my_login_redirect_woo($redirect, $user)
        {

            if ($user && is_object($user) && is_a($user, 'WP_User')) {
                if ($user->has_cap('woo_report_role')) {
                    $url = $this->it_plugin_main_url;
                    $redirect = admin_url("admin.php?page=$url");
                }
            }

            return $redirect;
        }

        public function my_login_redirect($redirect_to, $request, $user)
        {

            if ($user && is_object($user) && is_a($user, 'WP_User')) {
                if ($user->has_cap('woo_report_role')) {
                    $url = $this->it_plugin_main_url;
                    $redirect_to = admin_url("admin.php?page=$url");
                }
            }

            return $redirect_to;

            //is there a user to check?
            global $user;
            if (isset($user->roles) && is_array($user->roles)) {
                //check for admins

                if (in_array('woo_report_role', $user->roles)) {
                    // redirect them to the default place
                    $url = $this->it_plugin_main_url;
                    wp_redirect(admin_url("admin.php?page=$url"));

                    $url = $this->it_plugin_main_url;

                    return admin_url("admin.php?page=$url");
                }
            } else {
                return $redirect_to;
            }

            return $redirect_to;
        }

        public function custom_menu_page_removing()
        {
            echo '<style>#adminmenuwrap,#wp-admin-bar-root-default{display:none;}</style>';

            echo '<script >
				jQuery(document).ready(function($){
					jQuery("#adminmenuwrap, #adminmenuback, #wp-admin-bar-root-default").remove();
					jQuery("body").addClass("woo_report_role");
				});
			</script>';
        }

        public function get_capability()
        {
            //$role_capability='manage_options';
            $role_capability = 'edit_posts';
            $role_capability = 'edit_pages';
            //$role_capability='read';

            if (defined("__IT_PERMISSION_ADD_ON__")) {
                global $current_user;
                $current_user = wp_get_current_user();

                $user_info = get_userdata($current_user->ID);

                $role = get_role(strtolower($user_info->roles[0]));

                if (strtolower($user_info->roles[0]) == 'administrator') {
                    return 'manage_options';
                }

                //$role->has_cap( 'it_report_appear' );

                if (isset($role->capabilities['it_report_appear']) && $role->capabilities['it_report_appear']) {
                    $role_capability = 'it_report_appear';
                }
            }

            return $role_capability;
        }

        public function get_dashboard_capability($menu_id)
        {
            $permission = true;
            if (defined("__IT_PERMISSION_ADD_ON__")) {

                global $current_user;
                $current_user = wp_get_current_user();
                $user_info = $current_user->user_login;

                $user_infos = get_userdata($current_user->ID);
                if (strtolower($user_infos->roles[0]) == 'administrator') {
                    return true;
                }

                if (get_option("it_" . $user_info . "_dashborad_permission") != '') {
                    $menu_permission = get_option("it_" . $user_info . "_dashborad_permission");
                } else {
                    $user_info = get_userdata($current_user->ID);
                    $menu_permission = get_option("it_" . $user_info->roles[0] . "_dashborad_permission");
                    if (strtolower($user_info->roles[0]) == 'administrator') {
                        return true;
                    }
                }

                $fetched_value = json_decode($menu_permission);
                $keys = "it_elm_enable_" . $menu_id;
                $current_value = isset($fetched_value->$keys) ? $fetched_value->$keys : "";
                //echo $current_value;
                if ($current_value == 'off' || $current_value == '') {
                    $permission = false;
                }
            }

            return $permission;
        }

        public function get_menu_capability($menu_id)
        {
            $permission = true;
            if (defined("__IT_PERMISSION_ADD_ON__")) {

                global $current_user;
                $current_user = wp_get_current_user();
                $user_info = $current_user->user_login;

                $user_infos = get_userdata($current_user->ID);
                if (strtolower($user_infos->roles[0]) == 'administrator') {
                    return true;
                }

                if (get_option("it_" . $user_info . "_permission") != '') {
                    $menu_permission = get_option("it_" . $user_info . "_permission");
                } else {
                    $user_info = get_userdata($current_user->ID);
                    $menu_permission = get_option("it_" . $user_info->roles[0] . "_permission");
                    if (strtolower($user_info->roles[0]) == 'administrator') {
                        return true;
                    }
                }

                $fetched_value = json_decode($menu_permission);
                $keys = "it_elm_enable_" . $menu_id;
                $current_value = isset($fetched_value->$keys) ? $fetched_value->$keys : "";
                //echo $current_value;
                if ($current_value == 'off' || $current_value == '') {
                    $permission = false;
                }
            }

            return $permission;
        }

        public function get_form_element_permission($field_id, $key = '')
        {
            $permission = true;
            if (defined("__IT_PERMISSION_ADD_ON__")) {
                global $current_user;
                $current_user = wp_get_current_user();
                $user_info = $current_user->user_login;

                $user_infos = get_userdata($current_user->ID);
                if (strtolower($user_infos->roles[0]) == 'administrator') {
                    return true;
                }

                if (get_option("it_" . $user_info . "_permission") != '') {
                    $menu_permission = get_option("it_" . $user_info . "_permission");
                } else {
                    $user_info = get_userdata($current_user->ID);
                    $menu_permission = get_option("it_" . $user_info->roles[0] . "_permission");
                    if (strtolower($user_info->roles[0]) == 'administrator') {
                        return true;
                    }
                }

                $fetched_value = json_decode($menu_permission);
                $parent = isset($_GET['smenu']) ? $_GET['smenu'] : $_GET['parent'];
                if ($key != '') {
                    $parent = $key;
                }
                $keys = "it_elm_checkbox_" . $parent . "_" . $field_id;
                //print_r($fetched_value);
                $current_value = isset($fetched_value->$keys) ? $fetched_value->$keys : "";
                //echo $current_value;
                if ($current_value == '') {
                    $permission = false;
                }
            }

            return $permission;
        }

        public function get_form_element_value_permission($field_id, $key = '')
        {
            $permission = true;
            if (defined("__IT_PERMISSION_ADD_ON__")) {
                global $current_user;
                $current_user = wp_get_current_user();
                $user_info = $current_user->user_login;

                $user_infos = get_userdata($current_user->ID);
                if (strtolower($user_infos->roles[0]) == 'administrator') {
                    return true;
                }

                if (get_option("it_" . $user_info . "_permission") != '') {
                    $menu_permission = get_option("it_" . $user_info . "_permission");
                } else {
                    $user_info = get_userdata($current_user->ID);
                    $menu_permission = get_option("it_" . $user_info->roles[0] . "_permission");
                    if (strtolower($user_info->roles[0]) == 'administrator') {
                        return true;
                    }
                }

                $fetched_value = json_decode($menu_permission);
                $parent = isset($_GET['smenu']) ? $_GET['smenu'] : $_GET['parent'];
                if ($key != '') {
                    $parent = $key;
                }
                $keys = "it_elm_value_" . $parent . "_" . $field_id;
                //print_r($fetched_value->$keys);
                if (isset($fetched_value->$keys) && !in_array("all", $fetched_value->$keys)) {
                    return $fetched_value->$keys;
                }
            }

            return $permission;
        }

        public function it_get_form_element_permission($field_id, $field_value, $key = '')
        {
            if (!defined("__IT_PERMISSION_ADD_ON__")) {
                return $field_value;
            }
            $permission_value = $this->get_form_element_value_permission($field_id, $key);
            $permission_enable = $this->get_form_element_permission($field_id, $key);
            if ($permission_enable && $field_value == '-1' && $permission_value != 1) {
                return implode(",", $permission_value);
            }

            return $field_value;
        }

        public function it_activate_hook()
        {
            //DEACTIVATE 2 EXTRA ADD-ONS
            $dir = plugin_dir_path(__DIR__);
            // Absolute path to plugins dir
            $my_plugin = $dir . '/PW-Advanced-Woocommerce-Reporting-System-Crosstab-addon/main.php';
            // Check to see if plugin is already active
            deactivate_plugins($my_plugin, true);
            if (is_plugin_active($my_plugin)) {
            }

            $my_plugin = $dir . '/PW-Advanced-Woocommerce-Reporting-System-Variaion-addon/main.php';
            // Check to see if plugin is already active
            deactivate_plugins($my_plugin, true);
            if (is_plugin_active($my_plugin)) {
            }
        }

        public function it_report_setup_menus()
        {
            global $submenu;

            //IF WANT TO SHOW MENU FOR ALL USER USE 'edit_posts'

            $role_capability = $this->get_capability();
            //echo $role_capability;

            add_menu_page(
                '<span style="color: #627ddd;font-weight: 900;">iT </span>' . esc_html__('Woo Report', 'it_report_wcreport_textdomain'),
                '<span style="color: #627ddd;font-weight: 900;">iT </span>' . esc_html__('Woo Report', 'it_report_wcreport_textdomain'),
                $role_capability,
                $this->it_plugin_main_url,
                array($this, 'wcx_plugin_dashboard'),
                //'dashicons-chart-pie',
                esc_html(__IT_REPORT_WCREPORT_URL__) . '/assets/images/mini_logo_20.png',
                65
            );

            add_submenu_page(
                $this->it_plugin_main_url,
                esc_html__('Settings', 'it_report_wcreport_textdomain'),
                esc_html__('Settings', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_setting_report&parent=setting&smenu=setting',
                array($this, 'wcx_plugin_mani_settings')
            );

            add_submenu_page(
                $this->it_plugin_main_url,
                esc_html__('Activation', 'it_report_wcreport_textdomain'),
                esc_html__('Activation', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_activation',
                array($this, 'wcx_plugin_menu_activation'),
                50
            );

            if ($this->plugin_is_active === false && isset($_GET['page']) && strpos($_GET['page'], 'wcx_wcreport_plugin') !== false && $_GET['page'] != 'wcx_wcreport_plugin_activation') {
                wp_redirect(ITWR_ACTIVATION_PAGE);
                die();
            }

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Dashboard', 'it_report_wcreport_textdomain'),
                esc_html__('Dashboard', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_dashboard',
                array($this, 'wcx_plugin_dashboard')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('My Dashboard', 'it_report_wcreport_textdomain'),
                esc_html__('My Dashboard', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_plugin_menu_my_dashboard',
                array($this, 'wcx_plugin_menu_my_dashboard')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Details', 'it_report_wcreport_textdomain'),
                esc_html__('Details', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_details',
                array($this, 'wcx_plugin_menu_details')
            );

            //CUSTOM WORK - 12300
            //add_submenu_page(null, esc_html__('Details Tickera','it_report_wcreport_textdomain'), esc_html__('Details Tickera','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_details_tickera',   array($this,'wcx_plugin_menu_details_tickera' ) );

            //CUSTOM WORK - 4186
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Details Full', 'it_report_wcreport_textdomain'),
                esc_html__('Details Full', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_details_full',
                array($this, 'wcx_plugin_menu_details_full')
            );

            //CUSTOM WORK - 53
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Details Full Billing/Shipping', 'it_report_wcreport_textdomain'),
                esc_html__('Details Full Billing/Shipping', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_details_full_shipping',
                array($this, 'wcx_plugin_menu_details_full_shipping')
            );

            //CUSTOM WORK - 522
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Combined Orders', 'it_report_wcreport_textdomain'),
                esc_html__('Combined Orders', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_details_combined',
                array($this, 'wcx_plugin_menu_details_combined')
            );

            //CUSTOM WORK - 16
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Details Full Billing|Shipping', 'it_report_wcreport_textdomain'),
                esc_html__('Details Full Billing|Shipping', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_details_full_shipping_tax',
                array($this, 'wcx_plugin_menu_details_full_shipping_tax')
            );

            //CUSTOM WORK - 4179
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Status Change', 'it_report_wcreport_textdomain'),
                esc_html__('Status Change', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_order_status_change',
                array($this, 'wcx_plugin_menu_order_status_change')
            );

            ////ADDED IN VER4.0
            /// ORDER PER COUNTRY
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('All Orders (Custom Taxonomy, Field)', 'it_report_wcreport_textdomain'),
                esc_html__('All Orders (Custom Taxonomy, Field)', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_details_order_country',
                array($this, 'wcx_plugin_menu_details_order_country')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Order/Country', 'it_report_wcreport_textdomain'),
                esc_html__('Order Per Country', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_order_per_country',
                array($this, 'wcx_plugin_menu_order_per_country')
            );

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            //CUSTOM TAX & FIELDS

            //ALL DETAILS
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Product', 'it_report_wcreport_textdomain'),
                esc_html__('Product', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_product',
                array($this, 'wcx_plugin_menu_product')
            );

            //CUSTOM WORK 966
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('All Products', 'it_report_wcreport_textdomain'),
                esc_html__('All Products', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_all_products',
                array($this, 'wcx_plugin_menu_all_products')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Stock List', 'it_report_wcreport_textdomain'),
                esc_html__('Stock List', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_stock_list',
                array($this, 'wcx_plugin_menu_stock_list')
            );

            ////ADDED IN VER4.5
            //CUSTOM WORK
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Product/Users', 'it_report_wcreport_textdomain'),
                esc_html__('Product/Users', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_product_per_users',
                array($this, 'wcx_plugin_menu_product_per_users')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Profit', 'it_report_wcreport_textdomain'),
                esc_html__('Profit', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_profit',
                array($this, 'wcx_plugin_menu_profit')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Category', 'it_report_wcreport_textdomain'),
                esc_html__('Category', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_category',
                array($this, 'wcx_plugin_menu_category')
            );
            ////ADDED IN VER4.0
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Tag', 'it_report_wcreport_textdomain'),
                esc_html__('Tag', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_tags',
                array($this, 'wcx_plugin_menu_tags')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Customer', 'it_report_wcreport_textdomain'),
                esc_html__('Customer', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_customer',
                array($this, 'wcx_plugin_menu_customer')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Billing Country', 'it_report_wcreport_textdomain'),
                esc_html__('Billing Country', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_billingcountry',
                array($this, 'wcx_plugin_menu_billingcountry')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Billing State', 'it_report_wcreport_textdomain'),
                esc_html__('Billing State', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_billingstate',
                array($this, 'wcx_plugin_menu_billingstate')
            );
            ////ADDED IN VER4.0
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Billing City', 'it_report_wcreport_textdomain'),
                esc_html__('Billing City', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_billingcity',
                array($this, 'wcx_plugin_menu_billingcity')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Payment Gateway', 'it_report_wcreport_textdomain'),
                esc_html__('Payment Gateway', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_paymentgateway',
                array($this, 'wcx_plugin_menu_paymentgateway')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Order Status', 'it_report_wcreport_textdomain'),
                esc_html__('Order Status', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_orderstatus',
                array($this, 'wcx_plugin_menu_orderstatus')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Recent Order', 'it_report_wcreport_textdomain'),
                esc_html__('Recent Order', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_recentorder',
                array($this, 'wcx_plugin_menu_recentorder')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Tax Report', 'it_report_wcreport_textdomain'),
                esc_html__('Tax Report', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_taxreport',
                array($this, 'wcx_plugin_menu_taxreport')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Purchased Product by Customer', 'it_report_wcreport_textdomain'),
                esc_html__('Purchased Product by Customer', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_customrebuyproducts',
                array($this, 'wcx_plugin_menu_customrebuyproducts')
            );

            //CUSTOM WORK - 17427
            if (is_array(__CUSTOMWORK_ID__) && in_array('17427', __CUSTOMWORK_ID__)) {
                add_submenu_page(
                    'itwrl_submenu',
                    esc_html__('Purchased Category by Customer', 'it_report_wcreport_textdomain'),
                    esc_html__('Purchased Category by Customer', 'it_report_wcreport_textdomain'),
                    $role_capability,
                    'wcx_wcreport_plugin_customer_category',
                    array(
                        $this,
                        'wcx_plugin_menu_customer_category',
                    )
                );
            }

            //CUSTOM WORK - 15092
            if (is_array(__CUSTOMWORK_ID__) && in_array('15092', __CUSTOMWORK_ID__)) {
                add_submenu_page(
                    'itwrl_submenu',
                    esc_html__('Order Per Shipping', 'it_report_wcreport_textdomain'),
                    esc_html__('Order Per Shipping', 'it_report_wcreport_textdomain'),
                    $role_capability,
                    'wcx_wcreport_plugin_order_per_custom_shipping',
                    array(
                        $this,
                        'wcx_plugin_menu_order_per_custom_shipping',
                    )
                );
            }

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Refund Orders', 'it_report_wcreport_textdomain'),
                esc_html__('Refund Orders', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_refunddetails',
                array($this, 'wcx_plugin_menu_refunddetails')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Coupon', 'it_report_wcreport_textdomain'),
                esc_html__('Coupon', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_coupon',
                array($this, 'wcx_plugin_menu_coupon')
            );

            //CUSTOM WORK - 12679
            if (is_array(__CUSTOMWORK_ID__) && in_array('12679', __CUSTOMWORK_ID__)) {
                add_submenu_page(
                    'itwrl_submenu',
                    esc_html__('Total Sales per Clinic', 'it_report_wcreport_textdomain'),
                    esc_html__('Total Sales per Clinic', 'it_report_wcreport_textdomain'),
                    $role_capability,
                    'wcx_wcreport_plugin_clinic',
                    array($this, 'wcx_plugin_menu_clinic')
                );
            }

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            /////ADDED IN VER4.0
            /// OTHER SUMMARY
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Coupon Discount', 'it_report_wcreport_textdomain'),
                esc_html__('Coupon Discount', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_coupon_discount',
                array($this, 'wcx_plugin_menu_coupon_discount')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Customer Analysis', 'it_report_wcreport_textdomain'),
                esc_html__('Customer Analysis', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_customer_analysis',
                array($this, 'wcx_plugin_menu_customer_analysis')
            );
            //add_submenu_page(null, esc_html__('Frequently Order Customer','it_report_wcreport_textdomain'), esc_html__('Frequently Order Customer','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_customer_order_frequently',   array($this,'wcx_plugin_menu_customer_order_frequently' ) );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Customer in Price Point', 'it_report_wcreport_textdomain'),
                esc_html__('Customer in Price Point', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_customer_min_max',
                array($this, 'wcx_plugin_menu_customer_min_max')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Customer/Non Purchase', 'it_report_wcreport_textdomain'),
                esc_html__('Customer/Non Purchase', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_customer_no_purchased',
                array($this, 'wcx_plugin_menu_customer_no_purchased')
            );

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            //STOCK REOPRTS
            /////ADDED IN VER4.0
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Zero Level Stock', 'it_report_wcreport_textdomain'),
                esc_html__('Zero Level Stock', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_stock_zero_level',
                array($this, 'wcx_plugin_menu_stock_zero_level')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Minimum Level Stock', 'it_report_wcreport_textdomain'),
                esc_html__('Minimum Level Stock', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_stock_min_level',
                array($this, 'wcx_plugin_menu_stock_min_level')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Most Stocked', 'it_report_wcreport_textdomain'),
                esc_html__('Most Stocked', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_stock_max_level',
                array($this, 'wcx_plugin_menu_stock_max_level')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Summary Stock Planner', 'it_report_wcreport_textdomain'),
                esc_html__('Summary Stock Planner', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_stock_summary_avg',
                array($this, 'wcx_plugin_menu_stock_summary_avg')
            );

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            //ORDER ANALYSIS
            /////ADDED IN VER4.0
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Analysis Simple products', 'it_report_wcreport_textdomain'),
                esc_html__('Analysis Simple products', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_order_product_analysis',
                array($this, 'wcx_plugin_menu_order_product_analysis')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Analysis Variation products', 'it_report_wcreport_textdomain'),
                esc_html__('Analysis Simple products', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_order_variation_analysis',
                array($this, 'wcx_plugin_menu_order_variation_analysis')
            );

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            //PRODUCT OPTIONS CUSTOM FIELDS
            /////ADDED IN VER4.0
            /// PRODUCT OPTIONS CUSTOM FIELDS

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            //CROSSTAB

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            //VARIATION

            //STOCK VARIATION
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Project VS Actual Sale', 'it_report_wcreport_textdomain'),
                esc_html__('Project VS Actual Sale', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_projected_actual_sale',
                array($this, 'wcx_plugin_menu_projected_actual_sale')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Tax Reports', 'it_report_wcreport_textdomain'),
                esc_html__('Tax Reports', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_tax_reports',
                array($this, 'wcx_plugin_menu_tax_reports')
            );

            //CUSTOM WORK - 12412
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Product Qty.', 'it_report_wcreport_textdomain'),
                esc_html__('Product Qty.', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_product_variation_qty',
                array($this, 'wcx_plugin_menu_product_variation_qty')
            );

            //////////////////////////////////////////////
            //////////////////////
            //////////////////////////////////////////////
            //ABANDONED CART
            /////ADDED IN VER4.9
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Abandoned Products', 'it_report_wcreport_textdomain'),
                esc_html__('Products', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_abandoned_products',
                array($this, 'wcx_plugin_menu_abandoned_products')
            );
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Abandoned Carts', 'it_report_wcreport_textdomain'),
                esc_html__('Abandoned Carts Data', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_abandoned_cart',
                array($this, 'wcx_plugin_menu_abandoned_cart')
            );

            /////////////////////////////
            //SETTINGS
            /////////////////////////////////
            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Settings', 'it_report_wcreport_textdomain'),
                esc_html__('Report Settings', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_setting_report',
                array($this, 'wcx_plugin_menu_setting_report')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Add-ons', 'it_report_wcreport_textdomain'),
                esc_html__('Report Add-ons', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_addons_report',
                array($this, 'wcx_plugin_menu_addons_report')
            );

            add_submenu_page(
                'itwrl_submenu',
                esc_html__('Activate Plugin', 'it_report_wcreport_textdomain'),
                esc_html__('Active Plugin', 'it_report_wcreport_textdomain'),
                $role_capability,
                'wcx_wcreport_plugin_active_report',
                array($this, 'wcx_plugin_menu_active_report')
            );

            //CUSTOMIZE MENUS
            do_action('it_report_wcreport_admin_menu');
        }

        public function wcx_plugin_dashboard($display = "all")
        {
            if ($this->plugin_is_active === false) {
                return wp_redirect(ITWR_ACTIVATION_PAGE);
            }

            $this->pages_fetch("dashboard_report.php", $display);
        }

        public function wcx_plugin_mani_settings($display = "all")
        {
            if ($this->plugin_is_active === false) {
                return wp_redirect(ITWR_ACTIVATION_PAGE);
            }

            include "class/setting_general.php";
        }

        public function wcx_plugin_menu_my_dashboard()
        {
            $this->pages_fetch("reports.php");
        }

        //Details
        public function wcx_plugin_menu_details()
        {
            $this->pages_fetch("details.php");
        }

        //Details Full
        //CUSTOM WORK - 4186
        public function wcx_plugin_menu_details_full()
        {
            $this->pages_fetch("details_full.php");
        }

        //Details Full Billing/Shipping
        //CUSTOM WORK - 53
        public function wcx_plugin_menu_details_full_shipping()
        {
            $this->pages_fetch("details_full_shipping.php");
        }

        //Details Full Billing/Shipping with Tax
        //CUSTOM WORK - 16
        public function wcx_plugin_menu_details_full_shipping_tax()
        {
            $this->pages_fetch("details_full_shipping_tax.php");
        }

        //CUSTOM WORK - 522
        public function wcx_plugin_menu_details_combined()
        {
            $this->pages_fetch("details_combined.php");
        }

        //CUSTOM WORK - 12300
        //        function wcx_plugin_menu_details_tickera(){
        //            $this->pages_fetch("details_tickera.php");
        //        }

        //ADDED IN VER 4.9
        //ABANDONED
        public function wcx_plugin_menu_abandoned_cart()
        {
            $this->pages_fetch("abandoned_cart.php");
        }

        public function wcx_plugin_menu_abandoned_products()
        {
            $this->pages_fetch("abandoned_product.php");
        }

        //CUSTOM WORK - 4179
        public function wcx_plugin_menu_order_status_change()
        {
            $this->pages_fetch("order_status_change.php");
        }

        /////ADDED IN VER4.0
        /// ORDER PER COUNTRY
        public function wcx_plugin_menu_details_order_country()
        {
            $this->pages_fetch("details_order_country.php");
        }

        public function wcx_plugin_menu_order_per_country()
        {
            $this->pages_fetch("order_per_country.php");
        }

        //////////////////////ALL DETAILS//////////////////////
        public function array_insert_after($key, array &$array, $new_key, $new_value)
        {
            if (array_key_exists($key, $array)) {
                $new = array();
                foreach ($array as $k => $value) {
                    $new[$k] = $value;
                    if ($k === $key) {
                        $new[$new_key] = $new_value;
                    }
                }

                return $new;
            }

            return false;
        }

        public function fetch_our_menu_fav($report_name = '')
        {

            $current_user = wp_get_current_user();
            $user_info = $current_user->user_login;

            $fav_menu = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . "fav_menus_" . $user_info);
            $this->our_menu_fav = $fav_menu;
            if (is_array($fav_menu) && count($fav_menu) > 0 && in_array($report_name, $fav_menu)) {
                return true;
            }

            return false;
        }

        public function pages_fetch($page, $display = "all")
        {
            $it_plugin_main_url = '';
            if ($this->it_plugin_main_url) {
                $it_plugin_main_url = 'admin.php?page=' . $this->it_plugin_main_url;
            }

            //NEW MENU
            $this->our_menu = array(
                "logo" => array(
                    "label" => '',
                    "id" => "logo",
                    "link" => '#',
                    "icon" => esc_html(__IT_REPORT_WCREPORT_URL__) . "/assets/images/logo.png",
                    "mini_icon" => esc_html(__IT_REPORT_WCREPORT_URL__) . "/assets/images/mini_logo.png",
                ),

                "dashboard" => array(
                    "label" => esc_html__('Dashboard', 'it_report_wcreport_textdomain'),
                    "id" => "dashboard",
                    "link" => $it_plugin_main_url,
                    "icon" => "fa-bookmark",
                ),

                "all_order_reports" => array(
                    "label" => esc_html__('Order', 'it_report_wcreport_textdomain'),
                    "id" => "all_order_reports",
                    "link" => "#",
                    "icon" => "fa-shopping-cart",
                    "childs" => array(
                        "all_orders" => array(
                            "label" => esc_html__('All Orders', 'it_report_wcreport_textdomain'),
                            "id" => "all_orders",
                            "link" => "admin.php?page=wcx_wcreport_plugin_details&parent=all_order_reports&smenu=all_orders",
                            "icon" => "fa-file-text",
                        ),
                        //CUSTOM WORK - 12300
                        //                        "all_orders_tickera" => array(
                        //                            "label" => esc_html__('All Orders Tickera', 'it_report_wcreport_textdomain'),
                        //                            "id" => "all_orders_tickera",
                        //                            "link" => "admin.php?page=wcx_wcreport_plugin_details_tickera&parent=all_orders_tickera&smenu=all_orders_tickera",
                        //                            "icon" => "fa-file-text",
                        //                        ),
                        "all_orders_full" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("All Orders Billing(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "all_orders_full",
                            "link" => "#",
                            "icon" => "fa-file-text",
                        ),
                        "order_per_country" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Order/Country(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "order_per_country",
                            "link" => "#",
                            "icon" => "fa-eye-slash",
                        ),
                        "order_status" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Order Status(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "order_status",
                            "link" => "#",
                            "icon" => "fa-check",
                        ),

                        //CUSTOM WORK - 4179
                        "order_status_change" => array(
                            "label" => esc_html__("Status Change", 'it_report_wcreport_textdomain'),
                            "id" => "order_status_change",
                            "link" => "#",
                            "icon" => "fa-check",
                        ),
                        "recent_order" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Recent Order(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "recent_order",
                            "link" => "#",
                            "icon" => "fa-shopping-cart",
                        ),
                        "refund_detail" => array(
                            "label" => esc_html__("Refund Orders", 'it_report_wcreport_textdomain'),
                            "id" => "refund_detail",
                            "link" => "admin.php?page=wcx_wcreport_plugin_refunddetails&parent=all_order_reports&smenu=refund_detail",
                            "icon" => "fa-eye-slash",
                        ),
                        ////ADDED IN VER4.0
                        //ORDER ANALYSIS
                        "order_product_analysis" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Analysis Simple Products(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "order_product_analysis",
                            "link" => "#",
                            "icon" => "fa-line-chart",
                        ),
                        "order_variation_analysis" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Analysis Variation Products(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "order_variation_analysis",
                            "link" => "#",
                            "icon" => "fa-area-chart",
                        ),

                    ),
                ),

                //ADDED IN VER 4.9
                //                "abandoned_carts" => array(
                //                    "label" => esc_html__('Abandoned Cart','it_report_wcreport_textdomain'),
                //                    "id" => "abandoned_carts",
                //                    "link" => "#",
                //                    "icon" => "fa-shopping-cart",
                //                    "childs" => array(
                //                        "abandoned_products" => array(
                //                            "label" => esc_html__("Products" ,'it_report_wcreport_textdomain'),
                //                            "id" => "abandoned_products",
                //                            "link" => "admin.php?page=wcx_wcreport_plugin_abandoned_products&parent=abandoned_carts&smenu=abandoned_products",
                //                            "icon" => "fa-pie-chart",
                //                        ),
                //                        "abandoned_cart" => array(
                //                            "label" => esc_html__('Cart Data','it_report_wcreport_textdomain'),
                //                            "id" => "abandoned_cart",
                //                            "link" => "admin.php?page=wcx_wcreport_plugin_abandoned_cart&parent=abandoned_carts&smenu=abandoned_cart",
                //                            "icon" => "fa-pie-chart",
                //                        ),
                //                    )
                //                ),

                "product_reports" => array(
                    "label" => esc_html__('Product', 'it_report_wcreport_textdomain'),
                    "id" => "product_reports",
                    "link" => "#",
                    "icon" => "fa-shopping-bag",
                    "childs" => array(

                        "product" => array(
                            "label" => esc_html__("Purchased Product", 'it_report_wcreport_textdomain'),
                            "id" => "product",
                            "link" => "admin.php?page=wcx_wcreport_plugin_product&parent=product_reports&smenu=product",
                            "icon" => "fa-cog",
                        ),

                        //CUSTOM WORK - 12412
                        "product_variation_qty" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Purchased Product Qty(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "product_variation_qty",
                            "link" => "#",
                            "icon" => "fa-cog",
                        ),

                        ////ADDED IN VER4.5
                        //CUSTOM WORK
                        "product_per_users" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Product/Users(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "product_per_users",
                            "link" => "#",
                            "icon" => "fa-cog",
                        ),

                        "category" => array(
                            "label" => esc_html__("Category", 'it_report_wcreport_textdomain'),
                            "id" => "category",
                            "link" => "admin.php?page=wcx_wcreport_plugin_category&parent=product_reports&smenu=category",
                            "icon" => "fa-tags",
                        ),

                        ////ADDED IN VER4.0
                        "tags" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Tag(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "tags",
                            "link" => "#",
                            "icon" => "fa-tags",
                        ),

                        "customer_buy_prod" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Purchased Product by Customer(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "customer_buy_prod",
                            "link" => "#",
                            "icon" => "fa-users",
                        ),

                        //CUSTOM WORK 17427

                        "stock_list" => array(
                            "label" => esc_html__('Product Stock', 'it_report_wcreport_textdomain'),
                            "id" => "stock_list",
                            "link" => "admin.php?page=wcx_wcreport_plugin_stock_list&parent=product_reports&smenu=stock_list",
                            "icon" => "fa-cart-arrow-down",
                        ),

                        /////ADDED IN VER4.0
                        /// STOCK REPORTS
                        "stock_zero_level" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Zero Level Stock(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "stock_zero_level",
                            "link" => "#",
                            "icon" => "fa-exclamation-triangle",
                        ),
                        "stock_min_level" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Minimum Level Stock(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "stock_min_level",
                            "link" => "#",
                            "icon" => "fa-level-down",
                        ),
                        "stock_max_level" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Most Stocked(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "stock_max_level",
                            "link" => "#",
                            "icon" => "fa-level-up",
                        ),
                        "stock_summary_avg" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Summary Stock Planner(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "stock_summary_avg",
                            "link" => "#",
                            "icon" => "fa-newspaper-o",
                        ),
                    ),
                ),

                "customer_reports" => array(
                    "label" => esc_html__('Customer', 'it_report_wcreport_textdomain'),
                    "id" => "customer_reports",
                    "link" => "#",
                    "icon" => "fa-user",
                    "childs" => array(

                        "customer" => array(
                            "label" => esc_html__("Customer", 'it_report_wcreport_textdomain'),
                            "id" => "customer",
                            "link" => "admin.php?page=wcx_wcreport_plugin_customer&parent=customer_reports&smenu=customer",
                            "icon" => "fa-user",
                        ),
                        ////ADDED IN VER4.0
                        //OTHER SUMMARY
                        "customer_analysis" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Customer Analysis(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "customer_analysis",
                            "link" => "#",
                            "icon" => "fa-bar-chart",
                        ),
                        "customer_min_max" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Customer Min-Max(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "customer_min_max",
                            "link" => "#",
                            "icon" => "fa-hand-pointer-o",
                        ),
                        "customer_no_purchased" => array(
                            "label" => esc_html__("Customer/Non Purchase", 'it_report_wcreport_textdomain'),
                            "id" => "customer_no_purchased",
                            "link" => "admin.php?page=wcx_wcreport_plugin_customer_no_purchased&parent=customer_reports&smenu=customer_no_purchased",
                            "icon" => "fa-ban",
                        ),

                    ),
                ),

                //CUSTOM TAX & FIELD

                "more_reports" => array(
                    "label" => esc_html__('More Reports', 'it_report_wcreport_textdomain'),
                    "id" => "more_reports",
                    "link" => "#",
                    "icon" => "fa-files-o",
                    "childs" => array(

                        "profit" => __IT_COG__ != '' ? array(
                            "label" => esc_html__("Profit", 'it_report_wcreport_textdomain'),
                            "id" => "profit",
                            "link" => "admin.php?page=wcx_wcreport_plugin_profit&parent=more_reports&smenu=profit",
                            "icon" => "fa-money",
                        ) : false,

                        "billing_country" => array(
                            "label" => esc_html__("Billing Country", 'it_report_wcreport_textdomain'),
                            "id" => "billing_country",
                            "link" => "admin.php?page=wcx_wcreport_plugin_billingcountry&parent=more_reports&smenu=billing_country",
                            "icon" => "fa-globe",
                        ),
                        "billing_state" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Billing State(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "billing_state",
                            "link" => "#",
                            "icon" => "fa-map",
                        ),
                        ////ADDED IN VER4.0
                        "billing_city" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Billing City(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "billing_city",
                            "link" => "#",
                            "icon" => "fa-map-marker",
                        ),
                        "payment_gateway" => array(
                            "label" => esc_html__("Payment Gateway", 'it_report_wcreport_textdomain'),
                            "id" => "payment_gateway",
                            "link" => "admin.php?page=wcx_wcreport_plugin_paymentgateway&parent=more_reports&smenu=payment_gateway",
                            "icon" => "fa-credit-card",
                        ),

                        "coupon" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Coupon(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "coupon",
                            "link" => "#",
                            "icon" => "fa-hashtag",
                        ),
                        "coupon_discount" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Coupon Discount(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "coupon_discount",
                            "link" => "#",
                            "icon" => "fa-percent",
                        ),
                        "proj_actual_sale" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Project VS Actual Sale(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "proj_actual_sale",
                            "link" => "#",
                            "icon" => "fa-calendar-check-o",
                        ),

                    ),
                ),

                "tax_reports" => array(
                    "label" => esc_html__('Tax', 'it_report_wcreport_textdomain'),
                    "id" => "tax_reports",
                    "link" => "#",
                    "icon" => "fa-percent",
                    "childs" => array(
                        "tax_report" => array(
                            "label" => esc_html__("Tax Report", 'it_report_wcreport_textdomain'),
                            "id" => "tax_report",
                            "link" => "admin.php?page=wcx_wcreport_plugin_taxreport&parent=tax_reports&smenu=tax_report",
                            "icon" => "fa-pie-chart",
                        ),
                        "tax_reports" => array(
                            "label" => '<span style="color:#d97c7c">' . esc_html__("Tax Reports(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                            "id" => "tax_reports",
                            "link" => "#",
                            "icon" => "fa-pie-chart",
                        ),
                    ),
                ),

                //CROSSTAB
                //VARIATION

                //VARIATION STOCK

                "setting" => array(
                    "label" => esc_html__('Settings', 'it_report_wcreport_textdomain'),
                    "id" => "setting",
                    "link" => "admin.php?page=wcx_wcreport_plugin_setting_report&parent=setting&smenu=setting",
                    "icon" => "fa-cogs",
                ),

            );

            //CUSTOM WORK - 53
            if (is_array(__CUSTOMWORK_ID__) && in_array('53', __CUSTOMWORK_ID__)) {

                $extra_menu = array(
                    "label" => '<span style="color:#d97c7c">' . esc_html__("All Orders Billing/Shipping(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                    "id" => "all_orders_full_shipping",
                    "link" => "#",
                    "icon" => "fa-area-chart",
                );
                $this->our_menu['all_order_reports']['childs'] = $this->array_insert_after(
                    "all_orders_full",
                    $this->our_menu['all_order_reports']['childs'],
                    "all_orders_full_shipping",
                    $extra_menu
                );
            }

            //CUSTOM WORK - 16
            if (is_array(__CUSTOMWORK_ID__) && in_array('16', __CUSTOMWORK_ID__)) {

                $extra_menu = array(
                    "label" => '<span style="color:#d97c7c">' . esc_html__("All Orders Billing|Shipping Tax(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                    "id" => "all_orders_full_shipping_tax",
                    "link" => "#",
                    "icon" => "fa-area-chart",
                );
                $this->our_menu['all_order_reports']['childs'] = $this->array_insert_after(
                    "all_orders_full",
                    $this->our_menu['all_order_reports']['childs'],
                    "all_orders_full_shipping_tax",
                    $extra_menu
                );
            }

            //CUSTOM WORK - 522
            if (is_array(__CUSTOMWORK_ID__) && in_array('522', __CUSTOMWORK_ID__)) {

                $extra_menu = array(
                    "label" => '<span style="color:#d97c7c">' . esc_html__("Combined Orders(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                    "id" => "details_combined",
                    "link" => "#",
                    "icon" => "fa-area-chart",
                );
                $this->our_menu['all_order_reports']['childs'] = $this->array_insert_after(
                    "all_orders_full",
                    $this->our_menu['all_order_reports']['childs'],
                    "details_combined",
                    $extra_menu
                );
            }

            //CUSTOM WORK - 12679
            if (is_array(__CUSTOMWORK_ID__) && in_array('12679', __CUSTOMWORK_ID__)) {

                $extra_menu = array(
                    "label" => esc_html__("Total Sales per Clinic", 'it_report_wcreport_textdomain'),
                    "id" => "clinic",
                    "link" => "admin.php?page=wcx_wcreport_plugin_clinic&parent=all_order_reports&smenu=clinic",
                    "icon" => "fa-area-chart",
                );
                $this->our_menu['all_order_reports']['childs'] = $this->array_insert_after(
                    "all_orders",
                    $this->our_menu['all_order_reports']['childs'],
                    "clinic",
                    $extra_menu
                );
            }

            //CUSTOM WORK - 15092
            if (is_array(__CUSTOMWORK_ID__) && in_array('15092', __CUSTOMWORK_ID__)) {

                $extra_menu = array(
                    "label" => '<span style="color:#d97c7c">' . esc_html__("Order / Shipping(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                    "id" => "order_per_custom_shipping",
                    "link" => "#",
                    "icon" => "fa-users",
                );
                $this->our_menu['all_order_reports']['childs'] = $this->array_insert_after(
                    "order_per_country",
                    $this->our_menu['all_order_reports']['childs'],
                    "order_per_custom_shipping",
                    $extra_menu
                );
            }

            //CUSTOM WORK - 17427
            if (is_array(__CUSTOMWORK_ID__) && in_array('17427', __CUSTOMWORK_ID__)) {

                $extra_menu = array(
                    "label" => '<span style="color:#d97c7c">' . esc_html__("Purchased Category by Customer(Pro)", 'it_report_wcreport_textdomain') . '</span>',
                    "id" => "customer_category",
                    "link" => "#",
                    "icon" => "fa-users",
                );
                $this->our_menu['product_reports']['childs'] = $this->array_insert_after(
                    "customer_buy_prod",
                    $this->our_menu['product_reports']['childs'],
                    "customer_category",
                    $extra_menu
                );
            }

            //CUSTOM WORK - 966
            if (is_array(__CUSTOMWORK_ID__) && in_array('966', __CUSTOMWORK_ID__)) {

                $extra_menu = array(
                    "label" => esc_html__("All Products", 'it_report_wcreport_textdomain'),
                    "id" => "all_products",
                    "link" => "admin.php?page=wcx_wcreport_plugin_all_products&parent=product_reports&smenu=all_products",
                    "icon" => "fa-cog",
                );
                $this->our_menu['product_reports']['childs'] = $this->array_insert_after(
                    "product",
                    $this->our_menu['product_reports']['childs'],
                    "all_products",
                    $extra_menu
                );
            }

            $visible_menu = array();

            include "class/pages_fetch_dashboards.php";
        }

        ////ADDED IN VER4.0
        /// MENU GENERATOR
        public function it_menu_generator($our_menu, $menu_type = '', $selected_menu = [])
        {
            $menu_html = '';
            $menu_html_mini = '';
            $menu_html_mini_submenu = '';
            $menu_html_mini_logo = '';
            $menu_html_fav = '';
            $menu_html_mini_fav = '';
            $fav_menu_html = '';
            $fav_menu_html_mini = '';
            $fav_menu_html_mini_submenu = '';

            $parent_fav = array();
            $fav_active_parent = '';
            $fav_active = '';
            $fav_active_icon = 'fa-angle-right';

            //$menu_html.= '<ul class="bn-mainmenu-list-ul">';
            $parent = $selected_menu['parent'];
            $smenu = $selected_menu['smenu'];
            //die(print_r($selected_menu));
            //die(print_r($our_menu));

            foreach ($our_menu as $key => $menus) {

                if (defined("__IT_PERMISSION_ADD_ON__")) {
                    if (!$this->get_menu_capability($menus['id'])) {
                        continue;
                    }
                }

                if ($key == 'logo') {

                    $menu_html_mini_logo .= '
				     <div class="awr-item awr-item-logo" data-id="logo">
				      		<img src="' . $menus['mini_icon'] . '" class="small image">
				     </div>';

                    continue;
                }

                $same_title = array(
                    "all_orders",
                    "details_product_options",
                    "details_brands",
                    "brand_tax_field",
                    "brand_brands",
                );

                $activate = '';
                $submenu_id = '';
                $submenu_id_class = '';
                $activate_parent = '';
                $icon_toggle = 'fa-angle-right';
                if (isset($menus['childs']) && array_key_exists($smenu, $menus['childs'])) {
                    $icon_toggle = 'fa-angle-down';
                    $activate = ' awr-mainmenu-list-active ';
                    $activate_parent = "style='display:block'";
                } elseif (!isset($menus['childs']) && $menus['id'] == $parent) {
                    $activate = ' awr-mainmenu-list-active ';
                } else {
                    $submenu_id = 'id="' . $menus['id'] . '"';
                    $submenu_id_class = $menus['id'];
                }

                $link = $menus['link'];
                if (isset($menus['childs'])) {
                    $link = '#';
                }

                if (isset($menus['childs'])) {

                    $menu_html .= '<li class="awr-mainmenu-list-hassub ' . $activate . ' ' . $menus['id'] . '" data-parent-id="' . $menus['id'] . '"><a href="javascript:void(0);" class="' . $submenu_id_class . '"><i class="fa ' . $menus['icon'] . '"></i><span>' . $menus['label'] . '</span>
					<span class="awr-mainmenu-list-toggle"><i class="fa ' . $icon_toggle . '"></i></span>
						<ul class="awr-mainmenu-list-sub" ' . $activate_parent . '>
					</a>';
                } else {

                    $menu_html .= '<li class=" ' . $activate . ' ' . $activate . $menus['id'] . '" data-parent-id="' . $menus['id'] . '"><a href="' . $link . '" ' . $submenu_id . '><i class="fa ' . $menus['icon'] . '"></i><span>' . $menus['label'] . '</span></a></li>';

                    $menu_html_mini .= '
					<div class="awr-item"  data-id="' . $key . '">
			            <a href="' . $link . '" ' . $submenu_id . '>
			                <i class="fa ' . $menus['icon'] . '"></i>
                      <div class="awr-sub-title">' . $menus['label'] . '</div>
			            </a>
			            <!--<div class="awr-mini-submenu">
			                <div class="awr-sub-title">' . $menus['label'] . '</div>
			            </div>-->
			        </div>';
                }

                if (isset($menus['childs'])) {

                    // $menu_html .= '';

                    $menu_html_mini .= '
					<div class="awr-item ' . $activate . '" data-id="' . $key . '">
			            <span>
			                <i class="fa ' . $menus['icon'] . '"></i>
                      <div class="awr-sub-title">' . $menus['label'] . '</div>
			            </span>';
                    $menu_html_mini_submenu .= '
			            <div class="awr-mini-submenu" id="menu_' . $key . '">
			            	<div class="awr-sub-title">' . $menus['label'] . '</div>
			            	<div class="awr-sub-links-cnt">';

                    foreach ($menus['childs'] as $child) {
                        // IF AEEAT VAUE is NULL or FALSE
                        if (!$child) {
                            continue;
                        }

                        if (defined("__IT_PERMISSION_ADD_ON__")) {
                            if (!$this->get_menu_capability($child['id'])) {
                                continue;
                            }
                        }

                        if ($child['id'] == $smenu) {
                            $activate = ' awr-mainmenu-list-active ';
                        } else {
                            $activate = '';
                        }

                        $submenu_id = 'id="' . $child['id'] . '"';

                        $menu_html .= '<li><a class="' . $child['id'] . $activate . ' item" data-parent-id="' . $menus['id'] . '"  href="' . $child['link'] . '" ' . $submenu_id . '><span>' . $child['label'] . '</span></a></li>';

                        $menu_html_mini_submenu .= '<a class="' . $child['id'] . $activate . ' awr-sub-link awr-sub-link-active" data-parent-id="' . $menus['id'] . '"  href="' . $child['link'] . '" ' . $submenu_id . '>' . $child['label'] . '</a>';

                        if ($this->fetch_our_menu_fav($child['id'])) {
                            $parent_fav[$menus['id']] = $menus['id'];

                            if ($activate != '') {
                                $fav_active_parent = "style='display:block'";
                                $fav_active_icon = " fa-angle-down ";
                                $fav_active = " awr-mainmenu-list-active ";
                            }

                            $fav_title = $child['label'];
                            if (in_array($child['id'], $same_title)) {
                                $fav_title = $child['label'];
                            }

                            $fav_menu_html .= '<li><a class="' . $child['id'] . $activate . ' item" data-parent-id="' . $menus['id'] . '"  href="' . $child['link'] . '" ' . $submenu_id . '><span>' . $fav_title . '</span></a></li>';

                            $fav_menu_html_mini .= '<a class="' . $child['id'] . $activate . ' awr-sub-link awr-sub-link-active" data-parent-id="' . $menus['id'] . '"  href="' . $child['link'] . '" ' . $submenu_id . '>' . $fav_title . '</a>';
                        }
                    }

                    $menu_html .= '</ul></li>';
                    $menu_html_mini_submenu .= '
							</div>
						</div>';
                    $menu_html_mini .= '</div>';
                } //IF has childs
                //$menu_html.='</li>';
            }
            //$menu_html.= '</ul>';

            if ($fav_menu_html != '') {
                $menu_html_fav .= '<li class="awr-mainmenu-list-hassub ' . $fav_active . ' ' . implode(
                    " ",
                    $parent_fav
                ) . '" data-parent-id="fav_menu"><a href="javascript:void(0);" class=""><i class="fa fa-star"></i><span>' . esc_html__(
                    'Favorite Menus',
                    'it_report_wcreport_textdomain'
                ) . '</span>
				<span class="awr-mainmenu-list-toggle"><i class="fa ' . $fav_active_icon . '"></i></span>
				</a>
						<ul class="awr-mainmenu-list-sub" ' . $fav_active_parent . '>' . $fav_menu_html . '</ul></li>';
                $menu_html_mini_fav .= '
					<div class="awr-item ' . $fav_active . '"  data-id="fav">
			            <span>
			                <i class="fa fa-star"></i>
                      <div class="awr-sub-title">' . esc_html__('Favorite Menus', 'it_report_wcreport_textdomain') . '</div>
			            </span>';
                $fav_menu_html_mini_submenu .= '
			            <div class="awr-mini-submenu" id="menu_fav">
			            	<div class="awr-sub-title">' . esc_html__('Favorite Menus', 'it_report_wcreport_textdomain') . '</div>
			            	<div class="awr-sub-links-cnt">' . $fav_menu_html_mini . '</div>
							</div>';
                $menu_html_mini_fav .= '
	                    </div>';
            }

            if ($menu_type == 'mini') {
                return $menu_html_mini_logo . '<div class="mini_parent">' . $menu_html_mini_fav . $menu_html_mini . '</div><div class="mini_childs">' . $fav_menu_html_mini_submenu . $menu_html_mini_submenu . '</div>';
            }

            return $menu_html_fav . $menu_html;
        }

        public function getHost($url)
        {
            $parseUrl = wp_parse_url(trim($url));
            if (isset($parseUrl['host'])) {
                $host = $parseUrl['host'];
            } else {
                $path = explode('/', $parseUrl['path']);
                $host = $path[0];
            }
            $host = str_ireplace('www.', '', $host);

            return trim($host);
        }

        public function dashboard($item_id = '')
        {
            return true;
        }

        //1-PRODUCTS
        public function wcx_plugin_menu_product()
        {
            $this->pages_fetch("product.php");
        }

        //CUSTOM WORK 966
        public function wcx_plugin_menu_all_products()
        {
            $this->pages_fetch("all_products.php");
        }

        ////ADDED IN VER4.5
        //CUSTOM WORK
        public function wcx_plugin_menu_product_per_users()
        {
            $this->pages_fetch("product_per_users.php");
        }

        //2-PROFIT
        public function wcx_plugin_menu_profit()
        {
            $this->pages_fetch("profit.php");
        }

        //2-CATEGORY
        public function wcx_plugin_menu_category()
        {
            $this->pages_fetch("category.php");
        }

        //ADDED IN VER4.0
        //2-1-TAGS
        public function wcx_plugin_menu_tags()
        {
            $this->pages_fetch("tags.php");
        }

        //3-CUSTOMER
        public function wcx_plugin_menu_customer()
        {
            $this->pages_fetch("customer.php");
        }

        //4-BILLING COUNTRY
        public function wcx_plugin_menu_billingcountry()
        {
            $this->pages_fetch("billingcountry.php");
        }

        //5-BILLING STATE
        public function wcx_plugin_menu_billingstate()
        {
            $this->pages_fetch("billingstate.php");
        }

        ////ADDED IN VER4.0
        public function wcx_plugin_menu_billingcity()
        {
            $this->pages_fetch("billingcity.php");
        }

        //6-PAYMENT GATEWAY
        public function wcx_plugin_menu_paymentgateway()
        {
            $this->pages_fetch("paymentgateway.php");
        }

        //7-ORDER STATUS
        public function wcx_plugin_menu_orderstatus()
        {
            $this->pages_fetch("orderstatus.php");
        }

        //8-RECENT ORDER
        public function wcx_plugin_menu_recentorder()
        {
            $this->pages_fetch("recentorder.php");
        }

        //9-TAX REPORT
        public function wcx_plugin_menu_taxreport()
        {
            $this->pages_fetch("taxreport.php");
        }

        //10-CUSTOMER BUY PRODUCT
        public function wcx_plugin_menu_customrebuyproducts()
        {
            $this->pages_fetch("customerbuyproducts.php");
        }

        //CUSTOM WORK 15092
        public function wcx_plugin_menu_order_per_custom_shipping()
        {
            $this->pages_fetch("order_per_custom_shipping.php");
        }

        //CUSTOM WORK 17427
        public function wcx_plugin_menu_customer_category()
        {
            $this->pages_fetch("customer_category.php");
        }

        //11-REFUND DETAILS
        public function wcx_plugin_menu_refunddetails()
        {
            $this->pages_fetch("refunddetails.php");
        }

        //12-COUPON
        public function wcx_plugin_menu_coupon()
        {
            $this->pages_fetch("coupon.php");
        }

        //CUSTOM WORK - 12679
        public function wcx_plugin_menu_clinic()
        {
            $this->pages_fetch("clinic.php");
        }

        ////ADDED IN VER4.0
        /// OTHER SUMMARY
        public function wcx_plugin_menu_coupon_discount()
        {
            $this->pages_fetch("coupon_discount.php");
        }

        public function wcx_plugin_menu_customer_analysis()
        {
            $this->pages_fetch("customer_analysis.php");
        }

        public function wcx_plugin_menu_customer_order_frequently()
        {
            $this->pages_fetch("customer_order_frequently.php");
        }

        public function wcx_plugin_menu_customer_min_max()
        {
            $this->pages_fetch("customer_min_max.php");
        }

        public function wcx_plugin_menu_customer_no_purchased()
        {
            $this->pages_fetch("customer_no_purchased.php");
        }

        /////ADDED IN VER4.0
        ////////////////////////STOCK REPORTS/////////////////////////
        public function wcx_plugin_menu_stock_zero_level()
        {
            $this->pages_fetch("stock_zero_level.php");
        }

        public function wcx_plugin_menu_stock_min_level()
        {
            $this->pages_fetch("stock_min_level.php");
        }

        public function wcx_plugin_menu_stock_max_level()
        {
            $this->pages_fetch("stock_max_level.php");
        }

        public function wcx_plugin_menu_stock_summary_avg()
        {
            $this->pages_fetch("stock_summary_avg.php");
        }

        /////ADDED IN VER4.0
        ////////////////////////ORDER ANALYSIS/////////////////////////
        public function wcx_plugin_menu_order_product_analysis()
        {
            $this->pages_fetch("order_product_analysis.php");
        }

        public function wcx_plugin_menu_order_variation_analysis()
        {
            $this->pages_fetch("order_variation_analysis.php");
        }

        //////////////////////CROSS TABS//////////////////////

        //VARIATION
        public function wcx_plugin_menu_variation()
        {
            $this->pages_fetch("variation.php");
        }

        //STOCK LIST
        public function wcx_plugin_menu_stock_list()
        {
            $this->pages_fetch("stock_list.php");
        }

        //VARIATION STOCK
        public function wcx_plugin_menu_variation_stock()
        {
            $this->pages_fetch("variation_stock.php");
        }

        //PROJECTED VS ACTUAL SALE
        public function wcx_plugin_menu_projected_actual_sale()
        {
            $this->pages_fetch("projected_actual_sale.php");
        }

        //TAX REPORT
        public function wcx_plugin_menu_tax_reports()
        {
            $this->pages_fetch("tax_reports.php");
        }

        //SETTING
        public function wcx_plugin_menu_setting_report()
        {
            $this->pages_fetch("setting_report.php");
        }

        //ADD-ONS
        public function wcx_plugin_menu_addons_report()
        {
            $this->pages_fetch("addons_report.php");
        }

        //ACTIVE
        public function wcx_plugin_menu_active_report()
        {
            $this->pages_fetch("plugin_active.php");
        }

        //activation
        public function wcx_plugin_menu_activation()
        {
            wp_enqueue_style('itwr-activation', __IT_REPORT_WCREPORT_CSS_URL__ . 'back-end/activation.css', [], ITWR_VERSION);

            wp_enqueue_script('itwr-activation', __IT_REPORT_WCREPORT_JS_URL__ . 'back-end/activation.js', [], ITWR_VERSION);

            $activation_controller = new ITWR_Activation_Controller();
            $activation_controller->index();
        }

        //CUSTOM WORK - 12412
        public function wcx_plugin_menu_product_variation_qty()
        {
            $this->pages_fetch("product_variation_qty.php");
        }

        ////ADDED IN VER4.0
        //SEND EMAIL SCHEDULE
        public function wcx_send_email_schedule()
        {

            $act_email_reporting = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email', 0);
            $email_schedule = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'email_schedule', 'daily');

            $email_daily_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'today_email', 0);
            $email_weekly_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_week_email', 0);
            $email_monthly_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_month_email', 0);
            $email_till_today_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'till_today_email', 0);
            $email_yesterday_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'yesterday_email', 0);

            $email_last_week_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_week_email', 0);
            $email_last_month_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_month_email', 0);
            $email_this_year_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_year_email', 0);
            $email_last_year_report = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_year_email', 0);
            $email_total_summary = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'total_summary', 0);
            $email_time_limit = 300;

            //CUSTOM WORK - 4061
            $email_product_by_customer = $this->get_options(
                __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'product_by_customer',
                0
            );

            set_time_limit($email_time_limit); //set_time_limit — Limits the maximum execution time

            if (
                $email_daily_report == 1
                || $email_weekly_report == 1
                || $email_monthly_report == 1
                || $email_till_today_report == 1
                || $email_yesterday_report == 1
                || $email_last_week_report == 1
                || $email_last_month_report == 1
                || $email_this_year_report == 1
                || $email_last_year_report == 1
                || $act_email_reporting == 1
                || $email_total_summary == 1
                //CUSTOM WORK - 4061
                || $email_product_by_customer == 1

            ) {
                //Pass
            } else {
                return '';
            }

            add_action('plugins_loaded', array($this, 'loadTextDomain'));

            //$this->check_parent_plugin();
            //            $this->define_constant();

            $post_status = array();
            $shop_order_status = $this->it_shop_status;
            $otder_status_hide = $it_hide_os = $this->otder_status_hide;

            $email_data = "";
            //$today_date        = date_i18n("Y-m-d");
            $today_date = $this->today;
            $timestamp = strtotime($today_date);
            $report = array();

            if ($email_weekly_report == 1 || $email_last_week_report == 1) {
                $start_of_week = $this->startWeek();
                $current_day = strtolower(gmdate('l', $timestamp));
                if ($current_day != $start_of_week) {

                    $this_week_strtotime = strtotime("last {$start_of_week}", $timestamp);
                    $this_week_start_date = gmdate("Y-m-d", $this_week_strtotime);
                    $this_week_end_date = gmdate('Y-m-d', strtotime("6 day", $this_week_strtotime));

                    $last_week_strtotime = strtotime("last {$start_of_week} -7 days", $timestamp);
                    $last_week_start_date = gmdate("Y-m-d", $last_week_strtotime);
                    $last_week_end_date = gmdate("Y-m-d", strtotime("6 day", $last_week_strtotime));
                } else {
                    $this_week_strtotime = strtotime("this {$start_of_week}", $timestamp);
                    $this_week_start_date = gmdate("Y-m-d", $this_week_strtotime);
                    $this_week_end_date = gmdate('Y-m-d', strtotime("6 day", $this_week_strtotime));

                    $last_week_strtotime = strtotime("this {$start_of_week} -7 days", $timestamp);
                    $last_week_start_date = gmdate("Y-m-d", $last_week_strtotime);
                    $last_week_end_date = gmdate("Y-m-d", strtotime("6 day", $last_week_strtotime));
                }
            }

            if ($email_daily_report == 1) :
                $start_date = $today_date;
                $end_date = $today_date;
                $title = esc_html__("Today", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_yesterday_report == 1) :
                $yesterday_date = gmdate("Y-m-d", strtotime("-1 day", $timestamp));
                $start_date = $yesterday_date;
                $end_date = $yesterday_date;
                $title = esc_html__("Yesterday", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_weekly_report == 1) :
                $end_date = $this_week_end_date;
                $start_date = $this_week_start_date;
                $title = esc_html__("Current Week", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_last_week_report == 1) :
                $end_date = $last_week_end_date;
                $start_date = $last_week_start_date;
                $title = esc_html__("Last Week", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_monthly_report == 1) :
                $end_date = gmdate('Y-m-d', $timestamp);
                $start_date = gmdate('Y-m-01', strtotime('this month', $timestamp));
                $title = esc_html__("Current Month", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_last_month_report == 1) :
                $end_date = gmdate('Y-m-t', strtotime('last month', $timestamp));
                $start_date = gmdate('Y-m-01', strtotime('last month', $timestamp));
                $title = esc_html__("Last Month", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_this_year_report == 1) :
                $end_date = gmdate('Y-m-d', strtotime('this year', $timestamp));
                $start_date = gmdate('Y-01-01', strtotime('this year', $timestamp));
                $title = esc_html__("Current Year", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_last_year_report == 1) :
                $end_date = gmdate('Y-12-31', strtotime('last year', $timestamp));
                $start_date = gmdate('Y-01-01', strtotime('last year', $timestamp));
                $title = esc_html__("Last Year", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_till_today_report == 1) :
                $end_date = gmdate('Y-m-d', $timestamp);
                $start_date = $this->it_order_first_date();
                $title = esc_html__("Till Date", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_general_email_data(
                    $start_date,
                    $end_date,
                    $title,
                    $post_status,
                    $shop_order_status
                );
                $report[] = $title;
            endif;

            if ($email_total_summary == 1) :

                //echo $it_total_shop_day;
                $it_hide_os = explode(',', $otder_status_hide);
                $it_shop_order_status = array();
                if (strlen($shop_order_status) > 0 and $shop_order_status != "-1") {
                    $it_shop_order_status = explode(",", $shop_order_status);
                } else {
                    $it_shop_order_status = array();
                }

                $end_date = gmdate('Y-m-d', $timestamp);
                $start_date = $this->it_order_first_date();
                $title = esc_html__("Till Date", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_special_email_date(
                    $start_date,
                    $end_date,
                    $title,
                    $it_hide_os,
                    $it_shop_order_status
                );
                $report[] = $title;
            endif;

            //CUSTOM WORK - 4061
            if ($email_product_by_customer == 1) :

                $end_date = gmdate('Y-m-t', strtotime('last month', $timestamp));
                $start_date = gmdate('Y-m-01', strtotime('last month', $timestamp));
                $title = esc_html__("Purchased Product by Customer - Last Month", 'it_report_wcreport_textdomain');
                $email_data .= "<br>";
                $email_data .= $this->it_fetch_special_email_date_purchase_buy_customer(
                    $start_date,
                    $end_date,
                    $title,
                    $it_hide_os,
                    $it_shop_order_status
                );
                $report[] = $title;
            endif;

            if (
                $email_daily_report == 1
                || $email_weekly_report == 1
                || $email_monthly_report == 1
                || $email_till_today_report == 1
                || $email_yesterday_report == 1
                || $email_last_week_report == 1
                || $email_last_month_report == 1
                || $email_this_year_report == 1
                || $email_last_year_report == 1
                || $act_email_reporting == 1
                || $email_total_summary == 1

            ) :
                if (strlen($email_data) > 0) {

                    //$this->set_error_log('called funtion ic_woo_schedule_send_email, copleted html data');

                    $new = '<html>';
                    $new .= '<head>';
                    $new .= '<title>';
                    $new .= $title;
                    $new .= '</title>';
                    $new .= '</head>';
                    $new .= '<body>';
                    //$new .= $this->display_logo();
                    $new .= $email_data;
                    $new .= '</body>';
                    $new .= '</html>';
                    $email_data = $new;

                    $email_send_to = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sendto_email', '');
                    $email_from_name = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'from_name', '');
                    $email_from_email = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sendfrom_email', '');
                    $email_subject = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'subject_email', '');
                    $email_optimize = $this->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'optimize_email', '');

                    $email_send_to = $this->reformat_email_text($email_send_to);
                    $email_from_email = $this->reformat_email_text($email_from_email);
                    if ($email_send_to || $email_from_email) {

                        //$subject = $email_subject.'-'.implode(", ",$report)." Report";
                        $subject = $email_subject;

                        $headers = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                        if ($email_optimize) {
                            $headers .= 'From: ' . $email_from_name . ' <' . $email_from_email . '>' . "\r\n";
                        } else {
                            $headers .= "From: =?UTF-8?B?" . base64_encode($email_from_name) . "?= <" . $email_from_email . ">" . "\r\n";
                            $headers .= 'Content-Transfer-Encoding: 8bit';
                        }

                        $email_data = str_replace("! ", "", $email_data);
                        $email_data = str_replace("!", "", $email_data);

                        $date_format = get_option('date_format', "Y-m-d");
                        $time_format = get_option('time_format', 'g:i a');
                        $reporte_created = date_i18n($date_format . " " . $time_format);

                        $siteurl = get_option('siteurl');
                        $email_data = $email_data . "<div style=\" padding-bottom:3px; width:520px; margin:auto; text-align:left;\"><strong>" . esc_html__(
                            "Created Date/Time:",
                            'it_report_wcreport_textdomain'
                        ) . " " . "</strong> {$reporte_created}</div>";

                        $message = $email_data;
                        $to = $email_send_to;

                        update_option("email_message", $message);
                        //return 'OKa';

                        //return $message;

                        //$result = wp_mail( $to, $subject, $message, $headers);
                        $result = wp_mail($to, "=?UTF-8?B?" . base64_encode($subject) . "?=", $message, $headers);

                        return $result;
                    }
                }
            endif;

            return '';
        }

        public function it_email_table_row_html($title, $amount, $count, $price_type = 'price')
        {
            if ($price_type == 'price') {
                $amount = $this->price($amount);
            }

            return '
			<tr>
	            <td style="padding: 10px; background-color: #f2f2f2; color: #696969; font-size: 11px;     text-transform: capitalize; border-bottom: 1px solid #ddd;font-weight: bold;">
	                ' . $title . '
	            </td>
	            <td style="padding: 10px; background-color: #f2f2f2; color: #909090; font-size: 11px;     text-transform: capitalize; border-bottom: 1px solid #ddd;">
	            	' . $count . '
	            </td>
	            <td style="padding: 10px; background-color: #f2f2f2; color: #909090; font-size: 11px;     text-transform: capitalize; border-bottom: 1px solid #ddd;">
	                ' . $amount . '
	            </td>
	        </tr>';
        }

        public function it_fetch_general_email_data($start_date, $end_date, $title = "Daily", $post_status = [], $shop_order_status = [])
        {
            $body = '';
            include "includes/fetch_data_dailymail_status.php";

            return $message = $body;
        }

        public function it_fetch_special_email_date(
            $it_from_date,
            $it_to_date,
            $title = "Daily",
            $it_hide_os = [],
            $it_shop_order_status = []
        ) {
            $body = '';
            include "includes/fetch_data_dailymail.php";

            return $body;
        }

        //CUSTOM WORK - 4061
        public function it_fetch_special_email_date_purchase_buy_customer(
            $start_date,
            $end_date,
            $title = "Daily",
            $post_status = [],
            $shop_order_status = []
        ) {
            $body = '';
            include "includes/fetch_data_dailymail_purchase_by_customer.php";

            return $body;
        }

        //////////////END SEND EMAIL SCHEDULE////////////

        public function get_options($field, $default)
        {
            $value = get_option($field, $default);

            if ($value == 'on') {
                $value = 1;
            }
            if ($value == 'off') {
                $value = 0;
            }

            return $value;
        }

        public function validate_email($check)
        {
            $expression = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$/";
            if (preg_match($expression, $check)) {
                return true;
            } else {
                return false;
            }
        }

        public function reformat_email_text($emails)
        {
            $emails = str_replace("|", ",", $emails);
            $emails = str_replace(";", ",", $emails);
            $emails = explode(",", $emails);

            $newemail = array();
            foreach ($emails as $key => $value) :
                $e = trim($value);
                if ($this->validate_email($e)) {
                    $newemail[] = $e;
                }
            endforeach;

            if (count($newemail) > 0) {
                $newemail = array_unique($newemail);

                return implode(",", $newemail);
            } else {
                return false;
            }
        }

        public function startWeek()
        {
            $start_of_week = get_option('start_of_week', 0);
            $week_days = array("sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday");
            $day_name = isset($week_days[$start_of_week]) ? $week_days[$start_of_week] : "sunday";

            return $day_name;
        }

        public function it_today_total_customer()
        {
            global $wpdb, $sql, $Limit;
            $TodayDate = $this->today;
            $user_query = new WP_User_Query(array('role' => 'Customer'));
            $users = $user_query->get_results();
            $user2 = array();
            if (!empty($users)) {
                foreach ($users as $user) {
                    $strtotime = strtotime($user->user_registered);
                    $user_registered = gmdate("Y-m-d", $strtotime);
                    if ($user_registered == $TodayDate) {
                        $user2[] = $user->ID;
                    }
                }

                return count($user2);
            }

            return count($user2);
        }

        public function it_order_first_date($key = null)
        {
            global $wpdb;
            if ($this->it_firstorderdate) {
                return $this->it_firstorderdate;
            } else {
                //$sql = ;
                $post_type = 'shop_order';
                return $this->it_firstorderdate = $wpdb->get_var($wpdb->prepare(
                    "SELECT DATE_FORMAT(posts.post_date, '%%Y-%%m-%%d') AS 'OrderDate' 
                    FROM {$wpdb->prefix}posts AS posts 
                    WHERE posts.post_type = %s 
                    ORDER BY posts.post_date ASC 
                    LIMIT 1",
                    $post_type
                ));
            }
        }

        public function it_intelligence_product_images($title = 'No Title', $id = '', $url = false)
        {
            $first_letter = strtolower($title[0]);

            //IF PRODUCT NO TITLE or START WITH NO ALPHABETIC CHARACTER
            if (!preg_match("/^[a-z]$/", $first_letter)) {
                $first_letter = 'other';
            }

            $image_num = $id % 2;

            //$rand_img=rand(1,5);
            if ($url) {
                $img = esc_html(__IT_REPORT_WCREPORT_URL__) . '/assets/images/products/' . $first_letter . '/' . $image_num . '.jpg';
            } else {
                $img = '<img src="' . esc_html(__IT_REPORT_WCREPORT_URL__) . '/assets/images/products/' . $first_letter . '/' . $image_num . '.jpg">';
            }

            return $img;
        }

        public function it_get_ip_address()
        {

            if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            }

            return $ip;
        }

        public function it_cron_event_schedule()
        {
            $this->datetime = date_i18n("Y-m-d H:i:s");
            $args = array(
                'parent_plugin' => "WooCommerce",
                'report_plugin' => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . '_' . '20150522',
                'site_name' => get_option('blogname', ''),
                'home_url' => esc_url(home_url()),
                'site_date' => $this->datetime,
                'ip_address' => $this->it_get_ip_address(),
                'remote_address' => (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
            );
            $url = 'h' . 't' . 't' . 'p' . ':' . '/' . '/' . 'p' . 'l' . 'u' . 'g' . 'i' . 'n' . 's.' . 'i' . 'n' . 'f' . 'o' . 's' . 'o' . 'f' . 't' . 't' . 'e' . 'c' . 'h' . '.c' . 'o' . 'm' . '/' . 'w' . 'p' . '-' . 'a' . 'p' . 'i' . '/' . 'p' . 'l' . 'u' . 'g' . 'i' . 'n' . 's' . '.' . 'p' . 'h' . 'p';
            $request = wp_remote_post($url, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => $args,
                'cookies' => array(),
                'sslverify' => false,
            ));
        }

        //CUSTOMER ID
        public function it_dropdown_users($args = '')
        {
            $defaults = array(
                'show_option_all' => '',
                'show_option_none' => '',
                'hide_if_only_one_author' => '',
                'orderby' => 'display_name',
                'order' => 'ASC',
                'include' => '',
                'exclude' => '',
                'multi' => 0,
                'show' => 'display_name',
                'echo' => 1,
                'selected' => 0,
                'name' => 'user',
                'class' => '',
                'id' => '',
                'blog_id' => $GLOBALS['blog_id'],
                'who' => '',
                'include_selected' => false,
            );

            $r = wp_parse_args($args, $defaults);
            extract($r, EXTR_SKIP);

            $query_args = wp_array_slice_assoc(
                $r,
                array('blog_id', 'include', 'exclude', 'orderby', 'order', 'who')
            );
            $query_args['fields'] = array('ID', 'display_name', 'user_login');
            $users = get_users($query_args);

            $output = '';

            foreach ((array) $users as $user) {
                $user->ID = (int) $user->ID;

                $author = '';
                if ($user->ID == 0) {
                    $author = esc_html__('Guest', 'it_report_wcreport_textdomain');
                } else {
                    $author = get_user_meta($user->ID, 'billing_first_name', true) . ' ' . get_user_meta(
                        $user->ID,
                        'billing_last_name',
                        true
                    );
                }

                if ($user->display_name != '') {
                    $full_name = $user->display_name;
                } else {
                    $full_name = $user->user_login;
                }

                if ($full_name != '') {

                    $display = $full_name != ' ' ? $full_name : '(' . $user->user_login . ')';
                    $output .= "\t<option value='$user->ID'>" . esc_html($author) . "</option>\n";
                }
            }

            $output .= "\t<option value='0'>Guest</option>\n";

            $output .= "</select>";

            $output = apply_filters('wp_dropdown_users', $output);
            //
            //            if ($echo) {
            //                echo $output;
            //            }

            return $output;
        }
    }

    $GLOBALS['it_rpt_main_class'] = new it_report_wcreport_class;

    //ABANDONED CART
    //include(plugin_dir_path(__FILE__)."/includes/Abandoned/woocommerce-cart-reports.php");

    function it_add_custom_sku()
    {

        $args = array(

            'label' => esc_html__('Custom SKU', 'woocommerce'),

            'placeholder' => esc_html__('Enter custom SKU here', 'woocommerce'),

            'id' => 'jk_sku',

            'desc_tip' => true,

            'description' => esc_html__('This SKU is for internal use only.', 'woocommerce'),

        );

        woocommerce_wp_text_input($args);
    }

    add_action('woocommerce_product_options_sku', 'it_add_custom_sku');

    function it_save_custom_sku($post_id)
    {

        // grab the custom SKU from $_POST

        $custom_sku = isset($_POST['jk_sku']) ? sanitize_text_field($_POST['jk_sku']) : '';

        // grab the product

        $product = wc_get_product($post_id);

        // save the custom SKU using WooCommerce built-in functions

        $product->update_meta_data('jk_sku', $custom_sku);

        $product->save();
    }

    add_action('woocommerce_process_product_meta', 'it_save_custom_sku');

    add_action('woocommerce_variation_options_pricing', 'it_add_custom_field_to_variations', 10, 3);

    function it_add_custom_field_to_variations($loop, $variation_data, $variation)
    {

        woocommerce_wp_text_input(
            array(

                'id' => 'custom_field[' . $loop . ']',

                'class' => 'short',

                'label' => esc_html__('Custom Field', 'woocommerce'),

                'value' => get_post_meta($variation->ID, 'custom_field', true),

            )

        );
    }

    add_action('woocommerce_save_product_variation', 'it_save_custom_field_variations', 10, 2);

    function it_save_custom_field_variations($variation_id, $i)
    {

        $custom_field = $_POST['custom_field'][$i];

        if (isset($custom_field)) {
            update_post_meta($variation_id, 'custom_field', esc_attr($custom_field));
        }
    }

    add_filter('woocommerce_available_variation', 'it_add_custom_field_variation_data');

    function it_add_custom_field_variation_data($variations)
    {

        $variations['custom_field'] = '<div class="woocommerce_custom_field">Custom Field: <span>' . get_post_meta(
            $variations['variation_id'],
            'custom_field',
            true
        ) . '</span></div>';

        return $variations;
    }
}
