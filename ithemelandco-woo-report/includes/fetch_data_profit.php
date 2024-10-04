<?php
    if($file_used=="sql_table")
    {

        $request 			= array();
        $start				= 0;

        $it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
        $it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
	    $date_format = $this->it_date_format($it_from_date);

        $it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
        $it_paid_customer		= $this->it_get_woo_requests('it_customers_paid',NULL,true);
        $txtProduct 		= $this->it_get_woo_requests('txtProduct',NULL,true);
        $it_product_id			= $this->it_get_woo_requests('it_product_id',"-1",true);
        $category_id 		= $this->it_get_woo_requests('it_category_id','-1',true);

        ////ADDED IN VER4.0
        //BRANDS ADDONS
        $brand_id 		= $this->it_get_woo_requests('it_brand_id','-1',true);

        $limit 				= $this->it_get_woo_requests('limit',15,true);
        $p 					= $this->it_get_woo_requests('p',1,true);

        $page 				= $this->it_get_woo_requests('page',NULL,true);
        $order_id 			= $this->it_get_woo_requests('it_id_order',NULL,true);
        $it_from_date 		= $this->it_get_woo_requests('it_from_date',NULL,true);
        $it_to_date 			= $this->it_get_woo_requests('it_to_date',NULL,true);

        $it_txt_email 			= $this->it_get_woo_requests('it_email_text',NULL,true);

        $it_txt_first_name		= $this->it_get_woo_requests('it_first_name_text',NULL,true);

        $it_detail_view		= $this->it_get_woo_requests('it_view_details',"no",true);
        $it_country_code		= $this->it_get_woo_requests('it_countries_code',NULL,true);
        $state_code			= $this->it_get_woo_requests('it_states_code','-1',true);
        $it_payment_method		= $this->it_get_woo_requests('payment_method',NULL,true);
        $it_order_item_name	= $this->it_get_woo_requests('order_item_name',NULL,true);//for coupon
        $it_coupon_code		= $this->it_get_woo_requests('coupon_code',NULL,true);//for coupon
        $it_publish_order		= $this->it_get_woo_requests('publish_order','no',true);//if publish display publish order only, no or null display all order
        $it_coupon_used		= $this->it_get_woo_requests('it_use_coupon','no',true);
        $it_order_meta_key		= $this->it_get_woo_requests('order_meta_key','-1',true);
        $it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
        //$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

        $it_coupon_code		= $this->it_get_woo_requests('coupon_code','-1',true);
        $it_coupon_codes		= $this->it_get_woo_requests('it_codes_of_coupon','-1',true);

        $it_max_amount			= $this->it_get_woo_requests('max_amount','-1',true);
        $it_min_amount			= $this->it_get_woo_requests('min_amount','-1',true);

        $it_billing_post_code		= $this->it_get_woo_requests('it_bill_post_code','-1',true);
        $it_variation_id		= $this->it_get_woo_requests('variation_id','-1',true);
        $it_variation_only		= $this->it_get_woo_requests('variation_only','-1',true);
        $it_hide_os		= $this->it_get_woo_requests('it_hide_os','"trash"',true);

        $it_show_cog		= $this->it_get_woo_requests('it_show_cog','no',true);

        ///////////HIDDEN FIELDS////////////
        $it_hide_os=$this->otder_status_hide;
        $it_publish_order='no';
        $it_order_item_name='';
        $it_coupon_code='';
        $it_coupon_codes='';
        $it_payment_method='';

        $it_variation_only=$this->it_get_woo_requests('variation_only','-1',true);
        $it_order_meta_key='';

        $data_format=$this->it_get_woo_requests('date_format',get_option('date_format'),true);


        $it_variation_id='-1';
        $amont_zero='';
        //////////////////////



        /////////////////CUSTOM FIELDS & TAXONOMY/////////////////

        /////////////////////////////////
	    $key=$this->it_get_woo_requests('table_names','',true);

        $it_variations_formated='';

        if(strlen($it_max_amount)<=0) $_REQUEST['max_amount']	= 	$it_max_amount = '-1';
        if(strlen($it_min_amount)<=0) $_REQUEST['min_amount']	=	$it_min_amount = '-1';

        if($it_max_amount != '-1' || $it_min_amount != '-1'){
            if($it_order_meta_key == '-1'){
                $_REQUEST['order_meta_key']	= "_order_total";
            }
        }

        $last_days_orders 		= "0";
        if(is_array($it_id_order_status)){		$it_id_order_status 	= implode(",", $it_id_order_status);}
        if(is_array($category_id)){ 		$category_id		= implode(",", $category_id);}

	    $category_id=$this->it_get_form_element_permission('it_category_id',$category_id,$key);

	    ////ADDED IN VER4.0
	    //BRANDS ADDONS
	    $brand_id=$this->it_get_form_element_permission('it_brand_id',$brand_id,$key);

	    $it_country_code=$this->it_get_form_element_permission('it_countries_code',$it_country_code,$key);

	    if($it_country_code != NULL  && $it_country_code != '-1')
		    $it_country_code  		= "'".str_replace(",","','",$it_country_code)."'";

	    $state_code=$this->it_get_form_element_permission('it_states_code',$state_code,$key);

	    if($state_code != NULL  && $state_code != '-1')
		    $state_code  		= "'".str_replace(",","','",$state_code)."'";

	    $it_order_status=$this->it_get_form_element_permission('it_orders_status',$it_order_status,$key);

	    if($it_order_status != NULL  && $it_order_status != '-1')
		    $it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

        if(!$it_from_date){	$it_from_date = date_i18n('Y-m-d');}
        if(!$it_to_date){
            $last_days_orders 		= apply_filters($page.'_back_day', $last_days_orders);//-1,-2,-3,-4,-5
            $it_to_date = gmdate('Y-m-d', strtotime($last_days_orders.' day', strtotime(date_i18n("Y-m-d"))));}

        $it_sort_by 			= $this->it_get_woo_requests('sort_by','order_id',true);
        $it_order_by 			= $this->it_get_woo_requests('order_by','DESC',true);
        ///

        if($p > 1){	$start = ($p - 1) * $limit;}

        if($it_detail_view == "yes"){
            $it_variations_value		= $this->it_get_woo_requests('variations_value',"-1",true);
            $it_variations_formated = '-1';
            if($it_variations_value != "-1" and strlen($it_variations_value)>0){
                $it_variations_value = explode(",",$it_variations_value);
                $var = array();
                foreach($it_variations_value as $key => $value):
                    $var[] .=  $value;
                endforeach;
                $result = array_unique ($var);
                //$this->print_array($var);
                $it_variations_formated = implode("', '",$result);
            }
            $_REQUEST['variations_formated'] = $it_variations_formated;
        }


        //it_first_name_text
        $it_txt_first_name_cols='';
        $it_txt_first_name_join = '';
        $it_txt_first_name_condition_1 = '';
        $it_txt_first_name_condition_2 = '';

        //it_email_text
        $it_txt_email_cols ='';
        $it_txt_email_join = '';
        $it_txt_email_condition_1 = '';
        $it_txt_email_condition_2 = '';

        //SORT BY
        $it_sort_by_cols ='';

        //CATEGORY
        $category_id_join ='';
        $category_id_condition = '';

        ////ADDED IN VER4.0
        //BRANDS ADDONS
        $brand_id_join ='';
        $brand_id_condition = '';

        //ORDER ID
        $it_id_order_status_join ='';
        $it_id_order_status_condition = '';

        //COUNTRY
        $it_country_code_join = '';
        $it_country_code_condition_1 = '';
        $it_country_code_condition_2 = '';

        //STATE
        $state_code_join= '';
        $state_code_condition_1 = '';
        $state_code_condition_2 = '';

        //PAYMENT METHOD
        $it_payment_method_join= '';
        $it_payment_method_condition_1 = '';
        $it_payment_method_condition_2 = '';

        //POSTCODE
        $it_billing_post_code_join = '';
        $it_billing_post_code_condition= '';

        //COUPON USED
        $it_coupon_used_join = '';
        $it_coupon_used_condition = '';

        //VARIATION ID
        $it_variation_id_join = '';
        $it_variation_id_condition = '';

        //VARIATION ONLY
        $it_variation_only_join = '';
        $it_variation_only_condition = '';

        //VARIATION FORMAT
        $it_variations_formated_join = '';
        $it_variations_formated_condition = '';

        //ORDER META KEY
        $it_order_meta_key_join = '';
        $it_order_meta_key_condition = '';

        //COUPON CODES
        $it_coupon_codes_join = '';
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
	    $it_show_cog_cols='';
	    $it_show_cog_join='';
	    $it_show_cog_condition='';

        if($it_sort_by == "status"){
            $it_sort_by_cols = " terms2.name as status, ";
        }
        $sql_columns = " $it_txt_first_name_cols $it_txt_email_cols $it_sort_by_cols";
        $sql_columns .= "
            DATE_FORMAT(it_posts.post_date,'%m/%d/%Y') 													AS order_date,
            it_woocommerce_order_items.order_id 															AS order_id,
            it_woocommerce_order_items.order_item_name 													AS product_name,
            it_woocommerce_order_items.order_item_id														AS order_item_id,
            woocommerce_order_itemmeta.meta_value 														AS woocommerce_order_itemmeta_meta_value,
            (it_woocommerce_order_itemmeta2.meta_value/it_woocommerce_order_itemmeta3.meta_value) 			AS sold_rate,
            (it_woocommerce_order_itemmeta4.meta_value/it_woocommerce_order_itemmeta3.meta_value) 			AS product_rate,
            (it_woocommerce_order_itemmeta4.meta_value) 													AS item_amount,
            (it_woocommerce_order_itemmeta2.meta_value) 													AS item_net_amount,
            (it_woocommerce_order_itemmeta4.meta_value - it_woocommerce_order_itemmeta2.meta_value) 			AS item_discount,
            it_woocommerce_order_itemmeta2.meta_value 														AS total_price,
            count(it_woocommerce_order_items.order_item_id) 												AS product_quentity,
            woocommerce_order_itemmeta.meta_value 														AS product_id

            ,woocommerce_order_itemmeta22.meta_value AS variation_id

            ,it_woocommerce_order_itemmeta3.meta_value 													AS 'product_quantity'
            ,it_posts.post_status 																			AS post_status
            ,it_posts.post_status 																			AS order_status
            ,woo_itemmeta_cog.meta_value as cog
            ";


        $sql_joins ="{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items

            LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=it_woocommerce_order_items.order_id

            LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as woocommerce_order_itemmeta 	ON woocommerce_order_itemmeta.order_item_id		=	it_woocommerce_order_items.order_item_id

            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta22 ON woocommerce_order_itemmeta22.order_item_id	=	it_woocommerce_order_items.order_item_id

            LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta2 	ON it_woocommerce_order_itemmeta2.order_item_id	=	it_woocommerce_order_items.order_item_id
            LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta3 	ON it_woocommerce_order_itemmeta3.order_item_id	=	it_woocommerce_order_items.order_item_id
            LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta4 	ON it_woocommerce_order_itemmeta4.order_item_id	=	it_woocommerce_order_items.order_item_id AND it_woocommerce_order_itemmeta4.meta_key='_line_subtotal'
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woo_itemmeta_cog ON woocommerce_order_itemmeta.order_item_id	=	woo_itemmeta_cog.order_item_id
            ";




        if($category_id  && $category_id != "-1") {
            $category_id_join = "
                    LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 			ON it_term_relationships.object_id		=	woocommerce_order_itemmeta.meta_value
                    LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 				ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
            //LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 						ON it_terms.term_id					=	term_taxonomy.term_id";
        }

        ////ADDED IN VER4.0
        //BRANDS ADDONS
        if($brand_id  && $brand_id != "-1") {
            $brand_id_join = "
                    LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_brand 			ON it_term_relationships_brand.object_id		=	woocommerce_order_itemmeta.meta_value
                    LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_brand 				ON term_taxonomy_brand.term_taxonomy_id	=	it_term_relationships_brand.term_taxonomy_id";
            //LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_brand 						ON it_terms_brand.term_id					=	term_taxonomy_brand.term_id";
        }

        if(($it_id_order_status  && $it_id_order_status != '-1') || $it_sort_by == "status"){
            $it_id_order_status_join= "
                    LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships2			ON it_term_relationships2.object_id	= it_woocommerce_order_items.order_id
                    LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy2				ON it_term_taxonomy2.term_taxonomy_id	= it_term_relationships2.term_taxonomy_id";
            if($it_sort_by == "status"){
                $it_id_order_status_join .= " LEFT JOIN  {$wpdb->prefix}terms 	as terms2 						ON terms2.term_id					=	it_term_taxonomy2.term_id";
            }
        }

        if($it_country_code and $it_country_code != '-1')
            $it_country_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta4 ON it_postmeta4.post_id=it_woocommerce_order_items.order_id";

        if($state_code && $state_code != '-1')
            $state_code_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_state ON it_postmeta_billing_state.post_id=it_posts.ID";

        if($it_payment_method)
            $it_payment_method_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta5 ON it_postmeta5.post_id=it_woocommerce_order_items.order_id";


        $post_type_condition="it_posts.post_type = 'shop_order'";

        $other_condition_1 = "
            AND woocommerce_order_itemmeta.meta_key = '_product_id'

            AND woocommerce_order_itemmeta22.meta_key = '_variation_id'

            AND it_woocommerce_order_itemmeta2.meta_key='_line_total'
            AND it_woocommerce_order_itemmeta3.meta_key='_qty'
            AND woo_itemmeta_cog.meta_key='".__IT_COG_TOTAL__."' ";



        if($it_country_code and $it_country_code != '-1')
            $it_country_code_condition_1 = " AND it_postmeta4.meta_key='_billing_country'";

        if($state_code && $state_code != '-1')
            $state_code_condition_1 = " AND it_postmeta_billing_state.meta_key='_billing_state'";

        if ($it_from_date != NULL &&  $it_to_date !=NULL){
            $date_condition = " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
        }

        if($order_id)
            $order_id_condition = " AND it_woocommerce_order_items.order_id = ".$order_id;

        if($it_publish_order == 'yes')
            $it_publish_order_condition_1 = " AND it_posts.post_status = 'publish'";

        if($it_publish_order == 'publish' || $it_publish_order == 'trash')
            $it_publish_order_condition_2 = " AND it_posts.post_status = '".$it_publish_order."'";

        if($it_country_code and $it_country_code != '-1')
            $it_country_code_condition_2 = " AND it_postmeta4.meta_value IN (".$it_country_code.")";

        if($state_code && $state_code != '-1')
            $state_code_condition_2 = " AND it_postmeta_billing_state.meta_value IN (".$state_code.")";

        if($it_product_id  && $it_product_id != "-1")
            $it_product_id_condition = " AND woocommerce_order_itemmeta.meta_value IN (".$it_product_id .")";

        if($category_id  && $category_id != "-1")
            $category_id_condition = " AND term_taxonomy.taxonomy LIKE('product_cat') AND term_taxonomy.term_id IN (".$category_id .")";

        ////ADDED IN VER4.0
        //BRANDS ADDONS
        if($brand_id  && $brand_id != "-1")
            $brand_id_condition = " AND term_taxonomy_brand.taxonomy LIKE('".__IT_BRAND_SLUG__."') AND term_taxonomy_brand.term_id IN (".$brand_id .")";


        if($it_id_order_status  && $it_id_order_status != "-1")
            $it_id_order_status_condition = " AND it_term_taxonomy2.taxonomy LIKE('shop_order_status') AND it_term_taxonomy2.term_id IN (".$it_id_order_status .")";

        if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
            $it_order_status_condition = " AND it_posts.post_status IN (".$it_order_status.")";

        if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
            $it_hide_os_condition = " AND it_posts.post_status NOT IN ('".$it_hide_os."')";



        $sql ="SELECT $sql_columns FROM $sql_joins";

        $sql .="$category_id_join $brand_id_join $it_id_order_status_join $it_txt_email_join $it_txt_first_name_join
                    $it_country_code_join $state_code_join $it_payment_method_join $it_billing_post_code_join
                    $it_coupon_used_join $it_variation_id_join $it_variation_only_join $it_variations_formated_join
                    $it_order_meta_key_join $it_coupon_codes_join";

        $sql .= " Where $post_type_condition $it_txt_email_condition_1 $it_txt_first_name_condition_1
                            $other_condition_1 $it_country_code_condition_1 $state_code_condition_1
                            $it_billing_post_code_condition $it_payment_method_condition_1 $date_condition
                            $order_id_condition $it_txt_email_condition_2 $it_paid_customer_condition
                            $it_txt_first_name_condition_2 $it_publish_order_condition_1 $it_publish_order_condition_2
                            $it_country_code_condition_2 $state_code_condition_2 $it_payment_method_condition_2
                            $it_order_meta_key_condition $it_order_item_name_condition $txtProduct_condition
                            $it_product_id_condition $category_id_condition $brand_id_condition $it_id_order_status_condition
                            $it_coupon_used_condition $it_coupon_code_condition $it_coupon_codes_condition
                            $it_variation_id_condition $it_variation_only_condition $it_variations_formated_condition
                            $it_order_status_condition $it_hide_os_condition ";

        $sql_group_by = " GROUP BY it_woocommerce_order_items.order_item_id ";

        $sql .=$sql_group_by;


        $this->table_cols =$this->table_columns($table_name);


    }
    elseif($file_used=="data_table"){

        $first_order_id='';

        $order_items=$this->results;
        $categories = array();
        $order_meta = array();
        if(count($order_items)>0)

            foreach ( $order_items as $key => $order_item ) {

                $order_id								= $order_item->order_id;
                $order_items[$key]->billing_first_name  = '';//Default, some time it missing
                $order_items[$key]->billing_last_name  	= '';//Default, some time it missing
                $order_items[$key]->billing_email  		= '';//Default, some time it missing

                if(!isset($order_meta[$order_id])){
                    $order_meta[$order_id]					= $this->it_get_full_post_meta($order_id);
                }

                foreach($order_meta[$order_id] as $k => $v){
                    $order_items[$key]->$k			= $v;
                }


                $order_items[$key]->order_total			= isset($order_item->order_total)		? $order_item->order_total 		: 0;
                $order_items[$key]->order_shipping		= isset($order_item->order_shipping)	? $order_item->order_shipping 	: 0;


                $order_items[$key]->cart_discount		= isset($order_item->cart_discount)		? $order_item->cart_discount 	: 0;
                $order_items[$key]->order_discount		= isset($order_item->order_discount)	? $order_item->order_discount 	: 0;
                $order_items[$key]->total_discount 		= isset($order_item->total_discount)	? $order_item->total_discount 	: ($order_items[$key]->cart_discount + $order_items[$key]->order_discount);


                $order_items[$key]->order_tax 			= isset($order_item->order_tax)			? $order_item->order_tax : 0;
                $order_items[$key]->order_shipping_tax 	= isset($order_item->order_shipping_tax)? $order_item->order_shipping_tax : 0;
                $order_items[$key]->total_tax 			= isset($order_item->total_tax)			? $order_item->total_tax 	: ($order_items[$key]->order_tax + $order_items[$key]->order_shipping_tax);

                $transaction_id = "ransaction ID";
                $order_items[$key]->transaction_id		= isset($order_item->$transaction_id) 	? $order_item->$transaction_id		: (isset($order_item->transaction_id) ? $order_item->transaction_id : '');
                $order_items[$key]->gross_amount 		= ($order_items[$key]->order_total + $order_items[$key]->total_discount) - ($order_items[$key]->order_shipping +  $order_items[$key]->order_shipping_tax + $order_items[$key]->order_tax );


                $order_items[$key]->billing_first_name	= isset($order_item->billing_first_name)? $order_item->billing_first_name 	: '';
                $order_items[$key]->billing_last_name	= isset($order_item->billing_last_name)	? $order_item->billing_last_name 	: '';
                $order_items[$key]->billing_name		= $order_items[$key]->billing_first_name.' '.$order_items[$key]->billing_last_name;


            }


        $this->results=$order_items;


        //print_r($this->results);

        $items_render=array();

        ////ADDE IN VER4.0
        /// TOTAL ROWS VARIABLES
        $order_count=$net_amnt=$cog_amnt=$profit_amnt=0;

        foreach($this->results as $items){
            $index_cols=0;
            //for($i=1; $i<=20 ; $i++){

            ////ADDE IN VER4.0
            /// TOTAL ROWS


            $order_id= $items->order_id;
            $fetch_other_data='';

            if(!isset($this->order_meta[$order_id])){
                $fetch_other_data= $this->it_get_full_post_meta($order_id);
            }

            $new_order=false;
            if($first_order_id=='')
            {
                $first_order_id=$items->order_id;
                $new_order=true;
            }else if($first_order_id!=$items->order_id)
            {
                $first_order_id=$items->order_id;
                $new_order=true;
            }


            if(in_array($items->order_id,$items_render))
                continue;
            else
                $items_render[]=$items->order_id;

            $datatable_value.=("<tr>");

            //order ID
            $display_class='';
           	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
            $datatable_value.=("<td style='".$display_class."'>");
            $datatable_value.= $items->order_id;
            $datatable_value.=("</td>");




            //Status
            $it_table_value = isset($items->order_status) ? $items->order_status : '';

            if($it_table_value=='wc-completed')
                $it_table_value = '<span class="awr-order-status awr-order-status-'.sanitize_title($it_table_value).'" >'.ucwords(esc_html(sanitize_text_field($it_table_value), 'it_report_wcreport_textdomain')).'</span>';
            else if($it_table_value=='wc-refunded')
                $it_table_value = '<span class="awr-order-status awr-order-status-'.sanitize_title($it_table_value).'" >'.ucwords(esc_html(sanitize_text_field($it_table_value), 'it_report_wcreport_textdomain')).'</span>';
            else
                $it_table_value = '<span class="awr-order-status awr-order-status-'.sanitize_title($it_table_value).'" >'.ucwords(esc_html(sanitize_text_field($it_table_value), 'it_report_wcreport_textdomain')).'</span>';

            $display_class='';
           	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
            $datatable_value.=("<td style='".$display_class."'>");
            $datatable_value.= str_replace("Wc-","",$it_table_value);
            $datatable_value.=("</td>");

            //Order Total
	        $part_refund=(isset($order_refund_amnt[$items->order_id])? $order_refund_amnt[$items->order_id]:0);
            $display_class='';
            $it_table_value = isset($items -> order_total) ? ($items -> order_total)-$part_refund : 0;
            $it_table_value = $it_table_value == 0 ? $it_null_val : $it_table_value;
           	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
            $datatable_value.=("<td style='".$display_class."'>");
            $datatable_value.= $this->price($it_table_value,array("currency" => $fetch_other_data['order_currency']),'multi_currency');

            ////ADDE IN VER4.0
            /// TOTAL ROWS
            $net_amnt+=$it_table_value;
            $order_count++;

            $datatable_value.=("</td>");

            $display_class='';
            $all_cog='';

	        //IF USE WooCommerce Cost of Goods plugin(wootheme)
	        if(__IT_COG_ORDER_TOTAL__=='wc_cog_order_total_cost'){
		        $all_cog=$items->{__IT_COG_ORDER_TOTAL__};
	        }elseif(__IT_COG_ORDER_TOTAL__=='_posr_line_cog_total'){
		        //IF USE WooCommerce Profit of Sales Report plugin (IndoWebKreasi)
		        $order = new WC_Order( $order_id );
		        $items_order = $order->get_items();

		        foreach ( $items_order as $item ) {
			        $product_qty = $item['qty'];
			        $product_id = $item['product_id'];
			        $product_variation_id = $item['variation_id'];

			        $product_id_cog=$product_id;
			        if($product_variation_id!=''){
				        $product_id_cog=$product_variation_id;
			        }
			        $cog=get_post_meta($product_id_cog,__IT_COG__,true);
			        $cog*=$product_qty;
			        $all_cog+=$cog;
		        }
	        }



           	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
            $datatable_value.=("<td style='".$display_class."'>");
            $datatable_value.= $all_cog == 0 ? $this->price(0) : $this->price($all_cog);

            ////ADDE IN VER4.0
            /// TOTAL ROWS
            $cog_amnt+=$all_cog;

            $datatable_value.=("</td>");
            $profit=$it_table_value-$all_cog;
            $display_class='';
           	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
            $datatable_value.=("<td style='".$display_class."'>");
            $datatable_value.= ($profit) == 0 ? $this->price(0) : $this->price($profit);

            ////ADDE IN VER4.0
            /// TOTAL ROWS
            $profit_amnt+=$profit;

            $datatable_value.=("</td>");


            $datatable_value.=("</tr>");
        }

        ////ADDED IN VER4.0
        /// TOTAL ROW
        $table_name_total= $table_name;
	    $this->table_cols_total = $this->table_columns_total( $table_name_total );
	    $datatable_value_total='';

        $datatable_value_total.=("<tr>");
        $datatable_value_total.="<td>$order_count</td>";
        $datatable_value_total.="<td>".(($net_amnt) == 0 ? $this->price(0) : $this->price($net_amnt))."</td>";
        $datatable_value_total.="<td>".(($cog_amnt) == 0 ? $this->price(0) : $this->price($cog_amnt))."</td>";
        $datatable_value_total.="<td>".(($profit_amnt) == 0 ? $this->price(0) : $this->price($profit_amnt))."</td>";
        $datatable_value_total.=("</tr>");

    }elseif($file_used=="search_form"){
        ?>
        <form class='alldetails search_form_report' action='' method='post'>
            <input type='hidden' name='action' value='submit-form' />

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php esc_html_e('Date From','it_report_wcreport_textdomain');?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="it_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick"/>
            </div>

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php esc_html_e('Date To','it_report_wcreport_textdomain');?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="it_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"/>
            </div>

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php esc_html_e('Order ID','it_report_wcreport_textdomain');?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="it_id_order" type="text"  class=""/>
            </div>


            <?php
            $col_style='';
            $permission_value=$this->get_form_element_value_permission('it_category_id');
            if($this->get_form_element_permission('it_category_id') ||  $permission_value!=''){

                if(!$this->get_form_element_permission('it_category_id') &&  $permission_value!='')
                    $col_style='display:none';
                ?>
                <div class="col-md-6" style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('Category','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-tags"></i></span>
                    <?php
                    $args = array(
                        'taxonomy' => 'product_cat',
                        'orderby'                  => 'name',
                        'order'                    => 'ASC',
                        'hide_empty'               => 1,
                        'hierarchical'             => 0,
                        'exclude'                  => '',
                        'include'                  => '',
                        'child_of'          		 => 0,
                        'number'                   => '',
                        'pad_counts'               => false

                    );

                    //$categories = get_categories($args);
                    $current_category=$this->it_get_woo_requests_links('it_category_id','',true);

                    $categories = get_terms($args);
                    $option='';
                    foreach ($categories as $category) {

                        $selected='';
                        //CHECK IF IS IN PERMISSION
                        if(is_array($permission_value) && !in_array($category->term_id,$permission_value))
                            continue;

                        if(!$this->get_form_element_permission('it_category_id') &&  $permission_value!='')
                            $selected="selected";

                        if($current_category==$category->term_id)
                            $selected="selected";

                        $option .= '<option value="'.$category->term_id.'" '.$selected.'>';
                        $option .= $category->name;
                        $option .= ' ('.$category->count.')';
                        $option .= '</option>';
                    }
                    ?>
                    <select name="it_category_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        if($this->get_form_element_permission('it_category_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
                        {
                            ?>
                            <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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

                </div>
                <?php
            }

            ////ADDED IN VER4.0
            //BRANDS ADDONS
            $col_style='';
            $permission_value=$this->get_form_element_value_permission('it_brand_id');
            if(__IT_BRAND_SLUG__ && ($this->get_form_element_permission('it_brand_id') ||  $permission_value!='')){
                if(!$this->get_form_element_permission('it_brand_id') &&  $permission_value!='')
                    $col_style='display:none';
                ?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php Brand?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-tags"></i></span>
                    <?php
                    $args = array(
                        'taxonomy' => __IT_BRAND_SLUG__,
                        'orderby'                  => 'name',
                        'order'                    => 'ASC',
                        'hide_empty'               => 1,
                        'hierarchical'             => 0,
                        'exclude'                  => '',
                        'include'                  => '',
                        'child_of'          		 => 0,
                        'number'                   => '',
                        'pad_counts'               => false

                    );

                    //$categories = get_categories($args);
                    $current_category=$this->it_get_woo_requests_links('it_brand_id','',true);

                    $categories = get_terms($args);
                    $option='';
                    foreach ($categories as $category) {
                        $selected='';
                        //CHECK IF IS IN PERMISSION
                        if(is_array($permission_value) && !in_array($category->term_id,$permission_value))
                            continue;
                        /*if(!$this->get_form_element_permission('it_category_id') &&  $permission_value!='')
                            $selected="selected";

                        if($current_category==$category->term_id)
                            $selected="selected";*/

                        $option .= '<option value="'.$category->term_id.'" '.$selected.'>';
                        $option .= $category->name;
                        $option .= ' ('.$category->count.')';
                        $option .= '</option>';
                    }
                    ?>
                    <select name="it_brand_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        if($this->get_form_element_permission('it_brand_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
                        {
                            ?>
                            <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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

                </div>

                <?php
            }

            $col_style='';
            $permission_value=$this->get_form_element_value_permission('it_product_id');
            ?>



            <?php
            $col_style='';
            $permission_value=$this->get_form_element_value_permission('it_orders_status');
            if($this->get_form_element_permission('it_orders_status') ||  $permission_value!=''){

                if(!$this->get_form_element_permission('it_orders_status') &&  $permission_value!='')
                    $col_style='display:none';
                ?>

                <div class="col-md-6" style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('Status','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-check"></i></span>
                    <?php
                    $it_order_status=$this->it_get_woo_orders_statuses();

                    ////ADDED IN VER4.0
                    /// APPLY DEFAULT STATUS AT FIRST
                    $shop_status_selected='';
                    if($this->it_shop_status)
	                    $shop_status_selected=explode(",",$this->it_shop_status);

                    $option='';
                    foreach($it_order_status as $key => $value){

                        $selected="";
                        if(is_array($permission_value) && !in_array($key,$permission_value))
                            continue;

                        if(!$this->get_form_element_permission('it_orders_status') &&  $permission_value!='')
                            $selected="selected";

	                    ////ADDED IN VER4.0
	                    /// APPLY DEFAULT STATUS AT FIRST
	                    if(is_array($shop_status_selected) && in_array($key,$shop_status_selected))
		                    $selected="selected";

	                    $option.="<option value='".$key."' $selected >".$value."</option>";
                    }
                    ?>

                    <select name="it_orders_status[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        if($this->get_form_element_permission('it_orders_status') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
                        {
                            ?>
                            <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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
            $col_style='';
            $permission_value=$this->get_form_element_value_permission('it_countries_code');
            if($this->get_form_element_permission('it_countries_code') ||  $permission_value!=''){
                if(!$this->get_form_element_permission('it_countries_code') &&  $permission_value!='')
                    $col_style='display:none'

                ?>
                <div class="col-md-6" style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('Country','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-globe"></i></span>
                    <?php
                    $country_data = $this->it_get_paying_woo_state('billing_country');
                    $country      	= $this->it_get_woo_countries();
                    $option='';
                    foreach($country_data as $countries){
                        $selected='';
                        //CHECK IF IS IN PERMISSION
                        if(is_array($permission_value) && !in_array($countries->id,$permission_value))
                            continue;

                        if(!$this->get_form_element_permission('it_countries_code') &&  $permission_value!='')
                            $selected="selected";

                        $it_table_value = $country->countries[$countries->id];
                        $option.="<option value='".$countries->id."' $selected >".$it_table_value."</option>";
                    }

                    $country_states = $this->it_get_woo_country_of_state();
                    $json_country_states = wp_json_encode($country_states);
                    //print_r($json_country_states);
                    ?>
                    <select id="it_adr_country" name="it_countries_code[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        if($this->get_form_element_permission('it_countries_code') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
                        {
                            ?>
                            <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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

                    <script type="text/javascript">
                        "use strict";
                        jQuery( document ).ready(function( $ ) {

                            var country_state='';
                            country_state=<?php echo esc_js($json_country_states);?>;

                            $("#it_adr_country").change(function(){
                                var country_val=$(this).val();

                                if(country_val==null){
                                    return false;
                                }

                                var option_data = Array();
                                var optionss = '<option value="-1">Select All</option>';
                                var i = 1;
                                $.each(country_state, function(key,val){

                                    if(country_val.indexOf(val.parent_id) >= 0 || country_val=="-1"){
                                        optionss += '<option value="' + val.id + '">' + val.label + '</option>';
                                        option_data[val.id] = val.label;
                                    }
                                    i++;
                                });

                                $('#it_adr_state').empty(); //remove all child nodes
                                $("#it_adr_state").html(optionss);
                                $('#it_adr_state').trigger("chosen:updated");
                            });



                        });

                    </script>

                </div>

                <?php
            }
            $col_style='';
            $permission_value=$this->get_form_element_value_permission('it_states_code');
            if($this->get_form_element_permission('it_states_code') ||  $permission_value!=''){
                if(!$this->get_form_element_permission('it_states_code') &&  $permission_value!='')
                    $col_style='display:none';
                ?>

                <div class="col-md-6" style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('State','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-map"></i></span>
                    <?php
                    //$state_codes = $this->it_get_paying_woo_state('shipping_state','shipping_country');
                    //$this->it_get_woo_country_of_state();
                    //$this->it_get_woo_bsn($items->billing_country,$items->billing_state_code);
                    $state_codes = $this->it_get_paying_woo_state('billing_state','billing_country');
                    $option='';
                    foreach($state_codes as $state){
                        $selected="";
                        //CHECK IF IS IN PERMISSION
                        if(is_array($permission_value) && !in_array($state->id,$permission_value))
                            continue;

                        if(!$this->get_form_element_permission('it_states_code') &&  $permission_value!='')
                            $selected="selected";

                        $it_table_value = $this->it_get_woo_bsn($state->billing_country,$state->id);
                        $option.="<option $selected value='".$state->id."' >".$it_table_value." ($state->billing_country)</option>";
                    }
                    ?>

                    <select id="it_adr_state" name="it_states_code[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        if($this->get_form_element_permission('it_states_code') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
                        {
                            ?>
                            <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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

                </div>
                <?php
            }
            ?>


            <div class="col-md-12 awr-save-form">
                <?php
                $it_hide_os=$this->otder_status_hide;
                $it_publish_order='no';
                $it_order_item_name='';
                $it_coupon_code='';
                $it_coupon_codes='';
                $it_payment_method='';

                $it_variation_only=$this->it_get_woo_requests_links('variation_only','-1',true);
                $it_order_meta_key='';

                $data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);


                $it_variation_id='-1';
                $amont_zero='';

                ?>



                <input type="hidden" name="it_hide_os" value="<?php echo esc_html($it_hide_os);?>" />
                <input type="hidden" name="publish_order" value="<?php echo esc_html($it_publish_order);?>" />
                <input type="hidden" name="order_item_name" value="<?php echo esc_attr($it_order_item_name);?>" />
                <input type="hidden" name="coupon_code" value="<?php echo esc_attr($it_coupon_code);?>" />
                <input type="hidden" name="it_codes_of_coupon" value="<?php echo esc_attr($it_coupon_codes);?>" />
                <input type="hidden" name="payment_method" value="<?php echo esc_attr($it_payment_method);?>" />
                <input type="hidden" name="variation_id" value="<?php echo esc_attr($it_variation_id); ?>" />
                <input type="hidden" name="variation_only" value="<?php echo esc_attr($it_variation_only); ?>" />
                <input type="hidden" name="date_format" value="<?php echo esc_html($data_format); ?>" />

                <input type="hidden" name="table_names" value="<?php echo esc_html($table_name);?>"/>
                <div class="fetch_form_loading search-form-loading"></div>
                <button type="submit" value="Search" class="button-primary"><i class="fa fa-search"></i> <span><?php echo esc_html__('Search','it_report_wcreport_textdomain'); ?></span></button>
                <button type="button" value="Reset" class="button-secondary form_reset_btn"><i class="fa fa-reply"></i><span><?php echo esc_html__('Reset Form','it_report_wcreport_textdomain'); ?></span></button>
            </div>

        </form>
        <?php
    }

?>
