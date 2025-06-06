<?php
if ($file_used == "sql_table") {
    $request = array();
    $start   = 0;

    $it_from_date = $this->it_get_woo_requests('it_from_date', null, true);
    $it_to_date   = $this->it_get_woo_requests('it_to_date', null, true);
    $date_format  = $this->it_date_format($it_from_date);

    $it_id_order_status = $this->it_get_woo_requests('it_id_order_status', null, true);
    $it_paid_customer   = $this->it_get_woo_requests('it_customers_paid', null, true);
    $txtProduct         = $this->it_get_woo_requests('txtProduct', null, true);
    $it_product_id      = $this->it_get_woo_requests('it_product_id', "-1", true);
    $category_id        = $this->it_get_woo_requests('it_category_id', '-1', true);

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    $brand_id = $this->it_get_woo_requests('it_brand_id', '-1', true);

    $limit = $this->it_get_woo_requests('limit', 15, true);
    $p     = $this->it_get_woo_requests('p', 1, true);

    $page         = $this->it_get_woo_requests('page', null, true);
    $order_id     = $this->it_get_woo_requests('it_id_order', null, true);
    $it_from_date = $this->it_get_woo_requests('it_from_date', null, true);
    $it_to_date   = $this->it_get_woo_requests('it_to_date', null, true);

    $it_txt_email = $this->it_get_woo_requests('it_email_text', null, true);

    $it_txt_first_name = $this->it_get_woo_requests('it_first_name_text', null, true);

    $it_detail_view     = $this->it_get_woo_requests('it_view_details', "no", true);
    $it_country_code    = $this->it_get_woo_requests('it_countries_code', null, true);
    $state_code         = $this->it_get_woo_requests('it_states_code', '-1', true);
    $it_payment_method  = $this->it_get_woo_requests('payment_method', null, true);
    $it_order_item_name = $this->it_get_woo_requests('order_item_name', null, true);//for coupon
    $it_coupon_code     = $this->it_get_woo_requests('coupon_code', null, true);//for coupon

    $it_publish_order  = $this->it_get_woo_requests('publish_order', 'no',
        true);//if publish display publish order only, no or null display all order
    $it_coupon_used    = $this->it_get_woo_requests('it_use_coupon', 'no', true);
    $it_order_meta_key = $this->it_get_woo_requests('order_meta_key', '-1', true);
    $it_order_status   = $this->it_get_woo_requests('it_orders_status', '-1', true);
    //$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

    $it_paid_customer = str_replace(",", "','", $it_paid_customer);
    //$it_country_code		= str_replace(",","','",$it_country_code);
    //$state_code		= str_replace(",","','",$state_code);
    //$it_country_code		= str_replace(",","','",$it_country_code);

    $it_coupon_code  = $this->it_get_woo_requests('coupon_code', '-1', true);
    $it_coupon_codes = $this->it_get_woo_requests('it_codes_of_coupon', '-1', true);

    $it_coupon_code  = '';
    $it_coupon_codes = '';
    ////////////////////CUSTOM WORK/////////////////////
    $it_coupon_code  = $this->it_get_woo_requests('coupon_code', '-1', true);
    $it_coupon_codes = $this->it_get_woo_requests('it_codes_of_coupon', '-1', true);
    //echo $it_coupon_codes;
    if ($it_coupon_codes != "-1") {
        $it_coupon_codes = "'" . str_replace(",", "','", $it_coupon_codes) . "'";
    }

    //echo $it_coupon_codes;
    $coupon_discount_types = $this->it_get_woo_requests('it_coupon_discount_types', '-1', true);
    if ($coupon_discount_types != "-1") {
        $coupon_discount_types = "'" . str_replace(",", "','", $coupon_discount_types) . "'";
    }


    $it_max_amount = $this->it_get_woo_requests('max_amount', '-1', true);
    $it_min_amount = $this->it_get_woo_requests('min_amount', '-1', true);

    $it_billing_post_code = $this->it_get_woo_requests('it_bill_post_code', '-1', true);

    ////ADDED IN V4.0
    $it_variation_id   = $this->it_get_woo_requests('it_variation_id', '-1', true);
    $it_variation_only = $this->it_get_woo_requests('it_variation_only', '-1', true);
    $it_variations     = $it_item_meta_key = '';
    if ($it_variation_id != '-1' and strlen($it_variation_id) > 0) {

        $it_variations = explode(",", $it_variation_id);
        //$this->print_array($it_variations);
        $var      = array();
        $item_att = array();
        foreach ($it_variations as $key => $value):
            $var[]      .= "attribute_pa_" . $value;
            $var[]      .= "attribute_" . $value;
            $item_att[] .= "pa_" . $value;
            $item_att[] .= $value;
        endforeach;
        $it_variations    = implode("', '", $var);
        $it_item_meta_key = implode("', '", $item_att);
    }
    $it_variation_attributes    = $it_variations;
    $it_variation_item_meta_key = $it_item_meta_key;

    $it_hide_os = $this->it_get_woo_requests('it_hide_os', '"trash"', true);

    $it_show_cog = $this->it_get_woo_requests('it_show_cog', 'no', true);

    ///////////HIDDEN FIELDS////////////
    $it_hide_os         = $this->otder_status_hide;
    $it_publish_order   = 'no';
    $it_order_item_name = '';

    $it_payment_method = '';

    $it_order_meta_key = '';

    $data_format = $this->it_get_woo_requests('date_format', get_option('date_format'), true);

    $amont_zero = '';
    //////////////////////

    /////////////////////////
    //APPLY PERMISSION TERMS
    $key = 'all_orders';

    $category_id = $this->it_get_form_element_permission('it_category_id', $category_id, $key);

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    $brand_id = $this->it_get_form_element_permission('it_brand_id', $brand_id, $key);

    $it_product_id = $this->it_get_form_element_permission('it_product_id', $it_product_id, $key);

    $it_country_code = $this->it_get_form_element_permission('it_countries_code', $it_country_code, $key);

    if ($it_country_code != null && $it_country_code != '-1') {
        $it_country_code = "'" . str_replace(",", "','", $it_country_code) . "'";
    }

    $state_code = $this->it_get_form_element_permission('it_states_code', $state_code, $key);

    if ($state_code != null && $state_code != '-1') {
        $state_code = "'" . str_replace(",", "','", $state_code) . "'";
    }

    $it_order_status = $this->it_get_form_element_permission('it_orders_status', $it_order_status, $key);

    if ($it_order_status != null && $it_order_status != '-1') {
        $it_order_status = "'" . str_replace(",", "','", $it_order_status) . "'";
    }
    ///////////////////////////


    $it_variations_formated = '';

    if (strlen($it_max_amount) <= 0) {
        $_REQUEST['max_amount'] = $it_max_amount = '-1';
    }
    if (strlen($it_min_amount) <= 0) {
        $_REQUEST['min_amount'] = $it_min_amount = '-1';
    }

    if ($it_max_amount != '-1' || $it_min_amount != '-1') {
        if ($it_order_meta_key == '-1') {
            $_REQUEST['order_meta_key'] = "_order_total";
        }
    }

    $last_days_orders = "0";
    if (is_array($it_id_order_status)) {
        $it_id_order_status = implode(",", $it_id_order_status);
    }
    if (is_array($category_id)) {
        $category_id = implode(",", $category_id);
    }

    /////ADDED IN VER4.0
    //BRANDS ADDONS
    if (is_array($brand_id)) {
        $brand_id = implode(",", $brand_id);
    }

    if ( ! $it_from_date) {
        $it_from_date = date_i18n('Y-m-d');
    }
    if ( ! $it_to_date) {
        $last_days_orders = apply_filters($page . '_back_day', $last_days_orders);//-1,-2,-3,-4,-5
        $it_to_date       = gmdate('Y-m-d', strtotime($last_days_orders . ' day', strtotime(date_i18n("Y-m-d"))));
    }

    $it_sort_by  = $this->it_get_woo_requests('sort_by', 'order_id', true);
    $it_order_by = $this->it_get_woo_requests('order_by', 'DESC', true);
    ///

    if ($p > 1) {
        $start = ($p - 1) * $limit;
    }

    if ($it_detail_view == "yes") {
        $it_variations_value    = $this->it_get_woo_requests('variations_value', "-1", true);
        $it_variations_formated = '-1';
        if ($it_variations_value != "-1" and strlen($it_variations_value) > 0) {
            $it_variations_value = explode(",", $it_variations_value);
            $var                 = array();
            foreach ($it_variations_value as $key => $value):
                $var[] .= $value;
            endforeach;
            $result = array_unique($var);
            //$this->print_array($var);
            $it_variations_formated = implode("', '", $result);
        }
        $_REQUEST['variations_formated'] = $it_variations_formated;
    }


    //it_first_name_text
    $it_txt_first_name_cols        = '';
    $it_txt_first_name_join        = '';
    $it_txt_first_name_condition_1 = '';
    $it_txt_first_name_condition_2 = '';

    //it_email_text
    $it_txt_email_cols        = '';
    $it_txt_email_join        = '';
    $it_txt_email_condition_1 = '';
    $it_txt_email_condition_2 = '';

    //SORT BY
    $it_sort_by_cols = '';

    //CATEGORY
    $category_id_join      = '';
    $category_id_condition = '';

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    $brand_id_join      = '';
    $brand_id_condition = '';

    //ORDER ID
    $it_id_order_status_join      = '';
    $it_id_order_status_condition = '';

    //COUNTRY
    $it_country_code_join        = '';
    $it_country_code_condition_1 = '';
    $it_country_code_condition_2 = '';

    //STATE
    $state_code_join        = '';
    $state_code_condition_1 = '';
    $state_code_condition_2 = '';

    //PAYMENT METHOD
    $it_payment_method_join        = '';
    $it_payment_method_condition_1 = '';
    $it_payment_method_condition_2 = '';

    //POSTCODE
    $it_billing_post_code_join      = '';
    $it_billing_post_code_condition = '';

    //COUPON USED
    $it_coupon_used_join      = '';
    $it_coupon_used_condition = '';

    //VARIATION ID
    $it_variation_id_join      = '';
    $it_variation_id_condition = '';

    ////ADDED IN V4.0
    //VARIATION
    $it_variation_item_meta_key_join      = '';
    $sql_variation_join                   = '';
    $it_show_variation_join               = '';
    $it_variation_item_meta_key_condition = '';
    $sql_variation_condition              = '';

    //VARIATION ONLY
    $it_variation_only_join      = '';
    $it_variation_only_condition = '';

    //VARIATION FORMAT
    $it_variations_formated_join      = '';
    $it_variations_formated_condition = '';

    //ORDER META KEY
    $it_order_meta_key_join      = '';
    $it_order_meta_key_condition = '';

    //COUPON CODES
    $it_coupon_codes_join      = '';
    $it_coupon_codes_condition = '';

    //COUPON CODE
    $it_coupon_code_condition = '';

    //DATA CONDITION
    $date_condition = '';

    //ORDER ID
    $order_id_condition = '';

    //PAID CUSTOMER
    $it_paid_customer_condition = '';

    //PUBLISH ORDER
    $it_publish_order_condition_1 = '';
    $it_publish_order_condition_2 = '';

    //ORDER ITEM NAME
    $it_order_item_name_condition = '';

    //txt PRODUCT
    $txtProduct_condition = '';

    //PRODUCT ID
    $it_product_id_condition = '';

    //CATEGORY ID
    $category_id_condition = '';

    //ORDER STATUS ID
    $it_id_order_status_condition = '';

    //ORDER STATUS
    $it_order_status_condition = '';

    //HIDE ORDER STATUS
    $it_hide_os_condition = '';

    ////ADDED IN VER4.0
    /// COST OF GOOD
    $it_show_cog_cols      = '';
    $it_show_cog_join      = '';
    $it_show_cog_condition = '';


    if (($it_txt_first_name and $it_txt_first_name != '-1') || $it_sort_by == "billing_name") {
        $it_txt_first_name_cols = " CONCAT(it_postmeta1.meta_value, ' ', it_postmeta2.meta_value) AS billing_name,";
    }
    if ($it_txt_email || ($it_paid_customer && $it_paid_customer != '-1' and $it_paid_customer != "'-1'") || $it_sort_by == "billing_email") {
        $it_txt_email_cols = " postmeta.meta_value AS billing_email,";
    }

    if ($it_sort_by == "status") {
        $it_sort_by_cols = " terms2.name as status, ";
    }
    $sql_columns = " $it_txt_first_name_cols $it_txt_email_cols $it_sort_by_cols";
    $sql_columns .= "
		IF ( (woocommerce_order_itemmeta.meta_key = '_fee_amount'), 1, 0) AS fee,

        billing_country.meta_value as billing_country,
        DATE_FORMAT(it_posts.post_date,'%M %e, %Y %l:%i') 													AS order_date,

        DATE_FORMAT(it_posts.post_modified,'%M %e, %Y %l:%i'	) 													AS order_date_modify,

		it_woocommerce_order_items.order_id 															AS order_id,
		it_woocommerce_order_items.order_item_name 													AS product_name,
		it_woocommerce_order_items.order_item_id														AS order_item_id,
		woocommerce_order_itemmeta.meta_value 														AS woocommerce_order_itemmeta_meta_value,
		(it_woocommerce_order_itemmeta2.meta_value/it_woocommerce_order_itemmeta3.meta_value) 			AS sold_rate,
		IF ( (woocommerce_order_itemmeta.meta_key = '_fee_amount'), woocommerce_order_itemmeta.meta_value , (it_woocommerce_order_itemmeta4.meta_value/it_woocommerce_order_itemmeta3.meta_value))AS product_rate,

		IF ( (woocommerce_order_itemmeta.meta_key = '_fee_amount'), woocommerce_order_itemmeta.meta_value , (it_woocommerce_order_itemmeta4.meta_value))AS item_amount,
		(it_woocommerce_order_itemmeta2.meta_value) 													AS item_net_amount,
		(it_woocommerce_order_itemmeta4.meta_value - it_woocommerce_order_itemmeta2.meta_value) 			AS item_discount,
		it_woocommerce_order_itemmeta2.meta_value 														AS total_price,
		count(it_woocommerce_order_items.order_item_id) 												AS product_quentity,
		woocommerce_order_itemmeta.meta_value 														AS product_id

		,it_woocommerce_order_itemmeta3.meta_value 													AS 'product_quantity'
		,it_posts.post_status 																			AS post_status
		,it_posts.post_status 																			AS order_status

		";

//		if($it_variation_id  && $it_variation_id != "-1") {
//			$sql_columns .= " ,woocommerce_order_itemmeta22.meta_value AS variation_id ";
//		}

    ////ADDED IN V4.0
    if (($it_variation_item_meta_key != "-1" and strlen($it_variation_item_meta_key) > 1)) {
        $sql_columns .= " , it_woocommerce_order_itemmeta_variation.meta_key AS variation_key";
        $sql_columns .= " , it_woocommerce_order_itemmeta_variation.meta_value AS variation_value";
    }

    ////ADDED IN VER4.0
    /// COST OF GOOD
    if ($it_show_cog == "yes") {
        $sql_columns .= " ,woo_itemmeta_cog.meta_value as cog ";
    }


    $sql_joins = "{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items

		LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=it_woocommerce_order_items.order_id

		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as woocommerce_order_itemmeta 	ON woocommerce_order_itemmeta.order_item_id		=	it_woocommerce_order_items.order_item_id";

    ////ADDED IN V4.0
    if (($it_variation_item_meta_key != "-1" and strlen($it_variation_item_meta_key) > 1)) {
        $it_variation_item_meta_key_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_variation ON it_woocommerce_order_itemmeta_variation.order_item_id= it_woocommerce_order_items.order_item_id";
    }


    $sql_joins .= "
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta2 	ON it_woocommerce_order_itemmeta2.order_item_id	=	it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta3 	ON it_woocommerce_order_itemmeta3.order_item_id	=	it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta4 	ON it_woocommerce_order_itemmeta4.order_item_id	=	it_woocommerce_order_items.order_item_id AND it_woocommerce_order_itemmeta4.meta_key='_line_subtotal'
        LEFT JOIN  {$wpdb->prefix}postmeta as billing_country ON billing_country.post_id = it_posts.ID
        ";


    if ($category_id && $category_id != "-1") {
        $category_id_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 			ON it_term_relationships.object_id		=	woocommerce_order_itemmeta.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 				ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
        //LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 						ON it_terms.term_id					=	term_taxonomy.term_id";
    }

    /////ADDED IN VER4.0
    //BRANDS ADDONS
    if ($brand_id && $brand_id != "-1") {
        $brand_id_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_brand 			ON it_term_relationships_brand.object_id		=	woocommerce_order_itemmeta.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_brand				ON term_taxonomy_brand.term_taxonomy_id	=	it_term_relationships_brand.term_taxonomy_id";
        //LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_brand 						ON it_terms_brand.term_id					=	term_taxonomy_brand.term_id";
    }

    if (($it_id_order_status && $it_id_order_status != '-1') || $it_sort_by == "status") {
        $it_id_order_status_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships2			ON it_term_relationships2.object_id	= it_woocommerce_order_items.order_id
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy2				ON it_term_taxonomy2.term_taxonomy_id	= it_term_relationships2.term_taxonomy_id";
        if ($it_sort_by == "status") {
            $it_id_order_status_join .= " LEFT JOIN  {$wpdb->prefix}terms 	as terms2 						ON terms2.term_id					=	it_term_taxonomy2.term_id";
        }
    }

    if ($it_txt_email || ($it_paid_customer && $it_paid_customer != '-1' and $it_paid_customer != "'-1'") || $it_sort_by == "billing_email") {
        $it_txt_email_join = "
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id=it_woocommerce_order_items.order_id";
    }
    if (($it_txt_first_name and $it_txt_first_name != '-1') || $it_sort_by == "billing_name") {
        $it_txt_first_name_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_woocommerce_order_items.order_id
			LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_woocommerce_order_items.order_id";
    }

    if ($it_country_code and $it_country_code != '-1') {
        $it_country_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta4 ON it_postmeta4.post_id=it_woocommerce_order_items.order_id";
    }

    if ($state_code && $state_code != '-1') {
        $state_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_state ON it_postmeta_billing_state.post_id=it_posts.ID";
    }

    if ($it_payment_method) {
        $it_payment_method_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta5 ON it_postmeta5.post_id=it_woocommerce_order_items.order_id";
    }

    if ($it_billing_post_code and $it_billing_post_code != '-1') {
        $it_billing_post_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_postcode ON it_postmeta_billing_postcode.post_id	=	it_posts.ID";
    }

    if ($it_coupon_used == "yes") {
        $it_coupon_used_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta6 ON it_postmeta6.post_id=it_woocommerce_order_items.order_id";
    }

    if ($it_coupon_used == "yes") {
        $it_coupon_used_join .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta7 ON it_postmeta7.post_id=it_posts.ID";
    }

//		if($it_variation_id  && $it_variation_id != "-1") {
//			$it_variation_id_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_variation			ON it_woocommerce_order_itemmeta_variation.order_item_id 		= 	it_woocommerce_order_items.order_item_id";
//		}

    if ($it_variation_only && $it_variation_only != "-1" && $it_variation_only == "yes") {
        $it_variation_only_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_variation_o			ON it_woocommerce_order_itemmeta_variation_o.order_item_id 		= 	it_woocommerce_order_items.order_item_id";
    }

    if ($it_variations_formated != "-1" and $it_variations_formated != null) {
        $it_variations_formated_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta8 ON it_woocommerce_order_itemmeta8.order_item_id = it_woocommerce_order_items.order_item_id";
        $it_variations_formated_join .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_variation ON it_postmeta_variation.post_id = it_woocommerce_order_itemmeta8.meta_value";
    }

    if ($it_order_meta_key and $it_order_meta_key != '-1') {
        $it_order_meta_key_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_order_meta_key ON it_order_meta_key.post_id=it_posts.ID";
    }

    if (($it_coupon_codes != '' && $it_coupon_codes != "-1") or ($it_coupon_code && $it_coupon_code != "-1")) {
        $it_coupon_codes_join = " LEFT JOIN {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_coupon_item ON it_woocommerce_order_coupon_item.order_id = it_posts.ID AND it_woocommerce_order_coupon_item.order_item_type = 'coupon'";
    }


    ////ADDED IN VER4.0
    /// COST OF GOOD
    if ($it_show_cog == "yes") {
        $it_show_cog_join = " LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woo_itemmeta_cog ON woocommerce_order_itemmeta.order_item_id	=	woo_itemmeta_cog.order_item_id  ";
    }


    $post_type_condition = "it_posts.post_type = 'shop_order' AND billing_country.meta_key	= '_billing_country'";


    if ($it_txt_email || ($it_paid_customer && $it_paid_customer != '-1' and $it_paid_customer != "'-1'") || $it_sort_by == "billing_email") {
        $it_txt_email_condition_1 = "
				AND postmeta.meta_key='_billing_email'";
    }

    if (($it_txt_first_name and $it_txt_first_name != '-1') || $it_sort_by == "billing_name") {
        $it_txt_first_name_condition_1 = "
				AND it_postmeta1.meta_key='_billing_first_name'
				AND it_postmeta2.meta_key='_billing_last_name'";
    }

    $other_condition_1 = "
		AND ((woocommerce_order_itemmeta.meta_key = '_product_id'  AND it_woocommerce_order_itemmeta3.meta_key='_qty') OR (woocommerce_order_itemmeta.meta_key = '_fee_amount'))

		AND it_woocommerce_order_itemmeta2.meta_key='_line_total'
		";

    //AND woocommerce_order_itemmeta.meta_key = '_product_id'
    //AND it_woocommerce_order_itemmeta3.meta_key='_qty'
//
//		if($it_variation_id  && $it_variation_id != "-1") {
//			$other_condition_1 .= " AND woocommerce_order_itemmeta22.meta_key = '_variation_id' ";
//		}


    if ($it_country_code and $it_country_code != '-1') {
        $it_country_code_condition_1 = " AND it_postmeta4.meta_key='_billing_country'";
    }

    if ($state_code && $state_code != '-1') {
        $state_code_condition_1 = " AND it_postmeta_billing_state.meta_key='_billing_state'";
    }

    if ($it_billing_post_code and $it_billing_post_code != '-1') {
        $it_billing_post_code_condition = " AND it_postmeta_billing_postcode.meta_key='_billing_postcode' AND it_postmeta_billing_postcode.meta_value LIKE '%{$it_billing_post_code}%' ";
    }

    if ($it_payment_method) {
        $it_payment_method_condition_1 = " AND it_postmeta5.meta_key='_payment_method_title'";
    }

    if ($it_from_date != null && $it_to_date != null) {
        $date_condition = " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
    }

    if ($order_id) {
        $order_id_condition = " AND it_woocommerce_order_items.order_id = " . $order_id;
    }

    if ($it_txt_email) {
        $it_txt_email_condition_2 = " AND postmeta.meta_value LIKE '%" . $it_txt_email . "%'";
    }

    if ($it_paid_customer && $it_paid_customer != '-1' and $it_paid_customer != "'-1'") {
        $it_paid_customer_condition = " AND postmeta.meta_value IN ('" . $it_paid_customer . "')";
    }

    //if($it_txt_first_name and $it_txt_first_name != '-1') $sql .= " AND (it_postmeta1.meta_value LIKE '%".$it_txt_first_name."%' OR it_postmeta2.meta_value LIKE '%".$it_txt_first_name."%')";
    if ($it_txt_first_name and $it_txt_first_name != '-1') {
        $it_txt_first_name_condition_2 = " AND (lower(concat_ws(' ', it_postmeta1.meta_value, it_postmeta2.meta_value)) like lower('%" . $it_txt_first_name . "%') OR lower(concat_ws(' ', it_postmeta2.meta_value, it_postmeta1.meta_value)) like lower('%" . $it_txt_first_name . "%'))";
    }

    //if($it_id_order_status  && $it_id_order_status != "-1") $sql .= " AND terms2.term_id IN (".$it_id_order_status .")";

    if ($it_publish_order == 'yes') {
        $it_publish_order_condition_1 = " AND it_posts.post_status = 'publish'";
    }

    if ($it_publish_order == 'publish' || $it_publish_order == 'trash') {
        $it_publish_order_condition_2 = " AND it_posts.post_status = '" . $it_publish_order . "'";
    }

    //if($it_country_code and $it_country_code != '-1')	$sql .= " AND it_postmeta4.meta_value LIKE '%".$it_country_code."%'";

    //if($state_code and $state_code != '-1')	$sql .= " AND it_postmeta_billing_state.meta_value LIKE '%".$state_code."%'";

    if ($it_country_code and $it_country_code != '-1') {
        $it_country_code_condition_2 = " AND it_postmeta4.meta_value IN (" . $it_country_code . ")";
    }

    if ($state_code && $state_code != '-1') {
        $state_code_condition_2 = " AND it_postmeta_billing_state.meta_value IN (" . $state_code . ")";
    }

    if ($it_payment_method) {
        $it_payment_method_condition_2 = " AND it_postmeta5.meta_value LIKE '%" . $it_payment_method . "%'";
    }

    if ($it_order_meta_key and $it_order_meta_key != '-1') {
        $it_order_meta_key_condition = " AND it_order_meta_key.meta_key='{$it_order_meta_key}' AND it_order_meta_key.meta_value > 0";
    }

    if ($it_order_item_name) {
        $it_order_item_name_condition = " AND it_woocommerce_order_items.order_item_name LIKE '%" . $it_order_item_name . "%'";
    }

    if ($txtProduct && $txtProduct != '-1') {
        $txtProduct_condition = " AND it_woocommerce_order_items.order_item_name LIKE '%" . $txtProduct . "%'";
    }

    if ($it_product_id && $it_product_id != "-1") {
        $it_product_id_condition = " AND woocommerce_order_itemmeta.meta_value IN (" . $it_product_id . ")";
    }

    //if($category_id  && $category_id != "-1") $sql .= " AND it_terms.name NOT IN('simple','variable','grouped','external') AND term_taxonomy.taxonomy LIKE('product_cat') AND term_taxonomy.term_id IN (".$category_id .")";
    if ($category_id && $category_id != "-1") {
        $category_id_condition = " AND term_taxonomy.taxonomy LIKE('product_cat') AND term_taxonomy.term_id IN (" . $category_id . ")";
    }

    ////ADDED IN VER4.0
    //BRANDS ADDONS
    if ($brand_id && $brand_id != "-1") {
        $brand_id_condition = " AND term_taxonomy_brand.taxonomy LIKE('" . __IT_BRAND_SLUG__ . "') AND term_taxonomy_brand.term_id IN (" . $brand_id . ")";
    }


    if ($it_id_order_status && $it_id_order_status != "-1") {
        $it_id_order_status_condition = " AND it_term_taxonomy2.taxonomy LIKE('shop_order_status') AND it_term_taxonomy2.term_id IN (" . $it_id_order_status . ")";
    }

    if ($it_coupon_used == "yes") {
        $it_coupon_used_condition = " AND( (it_postmeta6.meta_key='_order_discount' AND it_postmeta6.meta_value > 0) ||  (it_postmeta7.meta_key='_cart_discount' AND it_postmeta7.meta_value > 0))";
    }


    if ($it_coupon_code != '' && $it_coupon_code != "-1") {
        $it_coupon_code_condition = " AND (it_woocommerce_order_coupon_item.order_item_name IN ('{$it_coupon_code}') OR it_woocommerce_order_coupon_item.order_item_name LIKE '%{$it_coupon_code}%')";
    }
    //echo $it_coupon_codes.'---';

    if ($it_coupon_codes != '' && $it_coupon_codes != "-1") {
        $it_coupon_codes_condition = " AND it_woocommerce_order_coupon_item.order_item_name IN ({$it_coupon_codes})";
    }

//		if($it_variation_id  && $it_variation_id != "-1") {
//			$it_variation_id_condition = " AND it_woocommerce_order_itemmeta_variation.meta_key = '_variation_id' AND it_woocommerce_order_itemmeta_variation.meta_value IN (".$it_variation_id .")";
//		}

    if ($it_variation_only && $it_variation_only != "-1" && $it_variation_only == "yes") {
        $it_variation_only_condition = " AND it_woocommerce_order_itemmeta_variation_o.meta_key 	= '_variation_id'
					 AND (it_woocommerce_order_itemmeta_variation_o.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta_variation_o.meta_value > 0)";
    }

    ////ADDED IN V4.0
    if (($it_variation_item_meta_key != "-1" and strlen($it_variation_item_meta_key) > 1)) {
        $it_variation_item_meta_key_condition = " AND it_woocommerce_order_itemmeta_variation.meta_key IN ('{$it_variation_item_meta_key}')";
    }

    if ($it_variations_formated != "-1" and $it_variations_formated != null) {
        $it_variations_formated_condition = "
			AND it_woocommerce_order_itemmeta8.meta_key = '_variation_id' AND (it_woocommerce_order_itemmeta8.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta8.meta_value > 0)";
        $it_variations_formated_condition .= "
			AND it_postmeta_variation.meta_value IN ('{$it_variations_formated}')";
    }

    ////ADDED IN VER4.0
    /// COST OF GOOD
    if ($it_show_cog == "yes") {
        $it_show_cog_condition = " AND woo_itemmeta_cog.meta_key='" . __IT_COG_TOTAL__ . "' ";
    }


    if ($it_order_status && $it_order_status != '-1' and $it_order_status != "'-1'") {
        $it_order_status_condition = " AND it_posts.post_status IN (" . $it_order_status . ")";
    }

    if ($it_hide_os && $it_hide_os != '-1' and $it_hide_os != "'-1'") {
        $it_hide_os_condition = " AND it_posts.post_status NOT IN ('" . $it_hide_os . "')";
    }


    $sql = "SELECT $sql_columns FROM $sql_joins";

    $sql .= "$category_id_join $brand_id_join $it_id_order_status_join $it_txt_email_join $it_txt_first_name_join
				$it_country_code_join $state_code_join $it_payment_method_join $it_billing_post_code_join
				$it_coupon_used_join $it_variation_id_join $it_variation_only_join $it_variations_formated_join
				$it_order_meta_key_join $it_coupon_codes_join $it_variation_item_meta_key_join $it_show_cog_join ";

    $sql .= " Where $post_type_condition $it_txt_email_condition_1 $it_txt_first_name_condition_1
						$other_condition_1 $it_country_code_condition_1 $state_code_condition_1
						$it_billing_post_code_condition $it_payment_method_condition_1 $date_condition
						$order_id_condition $it_txt_email_condition_2 $it_paid_customer_condition
						$it_txt_first_name_condition_2 $it_publish_order_condition_1 $it_publish_order_condition_2
						$it_country_code_condition_2 $state_code_condition_2 $it_payment_method_condition_2
						$it_order_meta_key_condition $it_order_item_name_condition $txtProduct_condition
						$it_product_id_condition $category_id_condition $brand_id_condition $it_id_order_status_condition
						$it_coupon_used_condition $it_coupon_code_condition $it_coupon_codes_condition $it_variation_item_meta_key_condition
						$it_variation_id_condition $it_variation_only_condition $it_variations_formated_condition $it_show_cog_condition
						$it_order_status_condition $it_hide_os_condition ";

    $sql_group_by = " GROUP BY it_woocommerce_order_items.order_item_id ";
    $sql_order_by = " ORDER BY {$it_sort_by} {$it_order_by}";

    $sql .= $sql_group_by . $sql_order_by;

    //print_r($search_fields);


} elseif ($file_used == "data_table") {

    $first_order_id = '';


    $order_items = $this->results;
    $categories  = array();
    $order_meta  = array();
    if (count($order_items) > 0) {
        foreach ($order_items as $key => $order_item) {

            $order_id                              = $order_item->order_id;
            $order_items[$key]->billing_first_name = '';//Default, some time it missing
            $order_items[$key]->billing_last_name  = '';//Default, some time it missing
            $order_items[$key]->billing_email      = '';//Default, some time it missing

            if ( ! isset($order_meta[$order_id])) {
                $order_meta[$order_id] = $this->it_get_full_post_meta($order_id);
            }

            //die(print_r($order_meta[$order_id]));

            foreach ($order_meta[$order_id] as $k => $v) {
                $order_items[$key]->$k = $v;
            }


            $order_items[$key]->order_total    = isset($order_item->order_total) ? $order_item->order_total : 0;
            $order_items[$key]->order_shipping = isset($order_item->order_shipping) ? $order_item->order_shipping : 0;


            $order_items[$key]->cart_discount  = isset($order_item->cart_discount) ? $order_item->cart_discount : 0;
            $order_items[$key]->order_discount = isset($order_item->order_discount) ? $order_item->order_discount : 0;
            $order_items[$key]->total_discount = isset($order_item->total_discount) ? $order_item->total_discount : ($order_items[$key]->cart_discount + $order_items[$key]->order_discount);


            $order_items[$key]->order_tax          = isset($order_item->order_tax) ? $order_item->order_tax : 0;
            $order_items[$key]->order_shipping_tax = isset($order_item->order_shipping_tax) ? $order_item->order_shipping_tax : 0;
            $order_items[$key]->total_tax          = isset($order_item->total_tax) ? $order_item->total_tax : ($order_items[$key]->order_tax + $order_items[$key]->order_shipping_tax);

            $transaction_id                    = "ransaction ID";
            $order_items[$key]->transaction_id = isset($order_item->$transaction_id) ? $order_item->$transaction_id : (isset($order_item->transaction_id) ? $order_item->transaction_id : '');
            $order_items[$key]->gross_amount   = ($order_items[$key]->order_total + $order_items[$key]->total_discount) - ($order_items[$key]->order_shipping + $order_items[$key]->order_shipping_tax + $order_items[$key]->order_tax);


            $order_items[$key]->billing_first_name = isset($order_item->billing_first_name) ? $order_item->billing_first_name : '';
            $order_items[$key]->billing_last_name  = isset($order_item->billing_last_name) ? $order_item->billing_last_name : '';
            $order_items[$key]->billing_name       = $order_items[$key]->billing_first_name . ' ' . $order_items[$key]->billing_last_name;


        }
    }


    //print_r($order_items);

    $this->results = $order_items;


    //print_r($this->results);

    $items_render = array();


    ////ADDE IN VER4.0
    /// TOTAL ROWS VARIABLES
    $gross_amnt = $discount_amnt = $shipping_amnt = $shipping_tax_amnt = $cog_amnt = $profit_amnt =
    $order_tax_amnt = $total_tax_amnt = $part_refund_amnt = $order_count =
    $product_count = $product_qty = $total_rate = $product_amnt = $product_discount = $net_amnt = 0;

    foreach ($this->results as $items) {
        $index_cols = 0;
        //for($i=1; $i<=20 ; $i++){


        ////ADDE IN VER4.0
        /// TOTAL ROWS
        $product_count++;

        $order_id         = $items->order_id;
        $fetch_other_data = '';

        if ( ! isset($this->order_meta[$order_id])) {
            $fetch_other_data = $this->it_get_full_post_meta($order_id);
        }

        $it_detail_view == "no";

        if (in_array($items->order_id, $items_render)) {
            continue;
        } else {
            $items_render[] = $items->order_id;
        }

        ////ADDE IN VER4.0
        /// TOTAL ROWS
        $order_count++;

        $datatable_value .= ("<tr>");

        //order ID
        $order_id = $items->order_id;

        //CUSTOM WORK - 15862
        if (is_array(__CUSTOMWORK_ID__) && in_array('15862', __CUSTOMWORK_ID__)) {
            $order_id = get_post_meta($order_id, '_order_number_formatted', true);
        }

        $display_class = '';
        if ($this->table_cols[$index_cols++]['status'] == 'hide') {
            $display_class = 'display:none';
        }
        $datatable_value .= ("<td style='" . $display_class . "'>");
        $datatable_value .= $order_id;
        $datatable_value .= ("</td>");

        //Date
        $date_format   = get_option('date_format');
        $display_class = '';
        if ($this->table_cols[$index_cols++]['status'] == 'hide') {
            $display_class = 'display:none';
        }
        $datatable_value .= ("<td style='" . $display_class . "'>");
        $datatable_value .= $items->order_date;
        $datatable_value .= ("</td>");

        //Order Total
        $display_class  = '';
        $it_table_value = isset($items->order_total) ? ($items->order_total) - $part_refund : 0;
        $it_table_value = $it_table_value == 0 ? $it_null_val : $it_table_value;
        if ($this->table_cols[$index_cols++]['status'] == 'hide') {
            $display_class = 'display:none';
        }
        $datatable_value .= ("<td style='" . $display_class . "'>");
        $datatable_value .= $this->price($it_table_value,
            array("currency" => $fetch_other_data['order_currency'], "order_id" => $items->order_id), 'multi_currency');

        ////ADDE IN VER4.0
        /// TOTAL ROWS
        $net_amnt        += $it_table_value;
        $datatable_value .= ("</td>");

        //Date Modify
        $date_format   = get_option('date_format');
        $display_class = '';
        if ($this->table_cols[$index_cols++]['status'] == 'hide') {
            $display_class = 'display:none';
        }
        $datatable_value .= ("<td style='" . $display_class . "'>");
        $datatable_value .= $items->order_date_modify;
        $datatable_value .= ("</td>");

        //Status
        $it_table_value = isset($items->order_status) ? $items->order_status : '';

        if ($it_table_value == 'wc-completed') {
            $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                    'ithemelandco-woo-report')) . '</span>';
        } elseif ($it_table_value == 'wc-refunded') {
            $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                    'ithemelandco-woo-report')) . '</span>';
        } else {
            $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                    'ithemelandco-woo-report')) . '</span>';
        }

        $display_class = '';
        if ($this->table_cols[$index_cols++]['status'] == 'hide') {
            $display_class = 'display:none';
        }
        $datatable_value .= ("<td style='" . $display_class . "'>");
        $datatable_value .= str_replace("Wc-", "", $it_table_value);
        $datatable_value .= ("</td>");


        $datatable_value .= ("</tr>");

    }

    ////ADDED IN VER4.0
    /// TOTAL ROW
    $datatable_value_total = '';
    $it_detail_view        = $this->it_get_woo_requests('it_view_details', "no", true);
    $it_show_cog           = $this->it_get_woo_requests('it_show_cog', 'no', true);
    $table_name_total      = "order_status_change";
    $table_name_total      = $table_name . "_no_items";

    $this->table_cols_total = $this->table_columns_total($table_name_total);


    $datatable_value_total .= ("<tr>");
    $datatable_value_total .= "<td>$order_count</td>";
    $datatable_value_total .= "<td>" . (($net_amnt) == 0 ? $this->price(0) : $this->price($net_amnt)) . "</td>";


    $datatable_value_total .= ("</tr>");


} elseif ($file_used == "search_form") {
    ?>
    <form class='alldetails search_form_report' action='' method='post'>
        <input type='hidden' name='action' value='submit-form'/>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php esc_html_e('Date From', 'ithemelandco-woo-report'); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
            <input name="it_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick"/>
        </div>

        <div class="col-md-6">
            <div class="awr-form-title">
                <?php esc_html_e('Date To', 'ithemelandco-woo-report'); ?>
            </div>
            <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
            <input name="it_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"/>
        </div>


        <?php
        $col_style        = '';
        $permission_value = $this->get_form_element_value_permission('it_orders_status');
        if ($this->get_form_element_permission('it_orders_status') || $permission_value != '') {

            if ( ! $this->get_form_element_permission('it_orders_status') && $permission_value != '') {
                $col_style = 'display:none';
            }
            ?>

            <div class="col-md-6" style=" <?php echo esc_attr($col_style); ?>">
                <div class="awr-form-title">
                    <?php esc_html_e('Status', 'ithemelandco-woo-report'); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-check"></i></span>
                <?php
                $it_order_status = $this->it_get_woo_orders_statuses();

                ////ADDED IN VER4.0
                $shop_status_selected = '';
                /// APPLY DEFAULT STATUS AT FIRST
                if ($this->it_shop_status) {
                    $shop_status_selected = explode(",", $this->it_shop_status);
                }

                $option = '';
                foreach ($it_order_status as $key => $value) {

                    $selected = "";
                    if (is_array($permission_value) && ! in_array($key, $permission_value)) {
                        continue;
                    }

                    ////ADDED IN VER4.0
                    /// APPLY DEFAULT STATUS AT FIRST
                    if (is_array($shop_status_selected) && in_array($key, $shop_status_selected)) {
                        $selected = "selected";
                    }

                    $option .= "<option value='" . $key . "' $selected >" . $value . "</option>";
                }
                ?>

                <select name="it_orders_status[]" multiple="multiple" size="5" data-size="5"
                        class="chosen-select-search">
                    <?php
                    if ($this->get_form_element_permission('it_orders_status') && (( ! is_array($permission_value)) || (is_array($permission_value) && in_array('all',
                                    $permission_value)))) {
                        ?>
                        <option value="-1"><?php esc_html_e('Select All', 'ithemelandco-woo-report'); ?></option>
                        <?php
                    }
                    ?>
                    <?php
                    echo wp_kses(
    $option,
    array(
        'form'  => array(),
        'div'   => array(),
        'label' => array(),
		'option' => array(),
        'input' => array(
            'type',
            'name',
            'value',
        ),
    )
);
                    ?>
                </select>
                <input type="hidden" name="it_id_order_status[]" id="it_id_order_status" value="-1">
            </div>
            <?php
        }
        ?>

        <div class="col-md-12">
            <?php
            $it_hide_os         = $this->otder_status_hide;
            $it_publish_order   = 'no';
            $it_order_item_name = '';
            $it_coupon_code     = '';
            $it_coupon_codes    = '';
            $it_payment_method  = '';

            $it_variation_only = $this->it_get_woo_requests_links('it_variation_only', '-1', true);
            $it_order_meta_key = '';

            $data_format = $this->it_get_woo_requests_links('date_format', get_option('date_format'), true);


            $amont_zero = '';

            ?>

            <input type="hidden" name="it_hide_os" value="<?php echo esc_html($it_hide_os); ?>"/>
            <input type="hidden" name="publish_order" value="<?php echo esc_html($it_publish_order); ?>"/>
            <input type="hidden" name="order_item_name" value="<?php echo esc_attr($it_order_item_name); ?>"/>
            <input type="hidden" name="coupon_code" value="<?php echo esc_attr($it_coupon_code); ?>"/>
            <input type="hidden" name="payment_method" value="<?php echo esc_attr($it_payment_method); ?>"/>


            <input type="hidden" name="date_format" value="<?php echo esc_html($data_format); ?>"/>

            <input type="hidden" name="table_names" value="<?php echo esc_html($table_name); ?>"/>
            <div class="fetch_form_loading search-form-loading"></div>
            <button type="submit" value="Search" class="button-primary"><i class="fa fa-search"></i>
                <span><?php echo esc_html__('Search', 'ithemelandco-woo-report'); ?></span></button>
            <button type="button" value="Reset" class="button-secondary form_reset_btn"><i
                        class="fa fa-reply"></i><span><?php echo esc_html__('Reset Form',
                        'ithemelandco-woo-report'); ?></span></button>
        </div>

    </form>
    <?php
}

?>
