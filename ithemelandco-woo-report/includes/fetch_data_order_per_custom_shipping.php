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
	//	$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		$it_paid_customer		= str_replace(",","','",$it_paid_customer);
		//$it_country_code		= str_replace(",","','",$it_country_code);

		$it_coupon_code		= $this->it_get_woo_requests('coupon_code','-1',true);
		$it_coupon_codes		= $this->it_get_woo_requests('it_codes_of_coupon','-1',true);

		$it_max_amount			= $this->it_get_woo_requests('max_amount','-1',true);
		$it_min_amount			= $this->it_get_woo_requests('min_amount','-1',true);

		$it_billing_post_code		= $this->it_get_woo_requests('it_bill_post_code','-1',true);
		$it_variation_id		= $this->it_get_woo_requests('variation_id','-1',true);
		$it_variation_only		= $this->it_get_woo_requests('variation_only','-1',true);
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','"trash"',true);


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



		/////////////////////////
		//APPLY PERMISSION TERMS
		$key=$this->it_get_woo_requests('table_names','',true);

		$category_id=$this->it_get_form_element_permission('it_category_id',$category_id,$key);

		$it_product_id=$this->it_get_form_element_permission('it_product_id',$it_product_id,$key);

		$it_country_code=$this->it_get_form_element_permission('it_countries_code',$it_country_code,$key);

		if($it_country_code != NULL  && $it_country_code != '-1')
			$it_country_code  		= "'".str_replace(",","','",$it_country_code)."'";

		$state_code=$this->it_get_form_element_permission('it_states_code',$state_code,$key);

		if($state_code != NULL  && $state_code != '-1')
			$state_code  		= "'".str_replace(",","','",$state_code)."'";

		$it_order_status=$this->it_get_form_element_permission('it_orders_status',$it_order_status,$key);

		if($it_order_status != NULL  && $it_order_status != '-1')
			$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";
		///////////////////////////





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




		if(($it_txt_first_name and $it_txt_first_name != '-1') || $it_sort_by == "billing_name"){
			$it_txt_first_name_cols = " CONCAT(it_postmeta1.meta_value, ' ', it_postmeta2.meta_value) AS billing_name," ;
		}
		if($it_txt_email || ($it_paid_customer  && $it_paid_customer != '-1' and $it_paid_customer != "'-1'") || $it_sort_by == "billing_email"){
			$it_txt_email_cols = " postmeta.meta_value AS billing_email,";
		}

		if($it_sort_by == "status"){
			$it_sort_by_cols = " terms2.name as status, ";
		}
		$sql_columns = " $it_txt_first_name_cols $it_txt_email_cols $it_sort_by_cols";
		$sql_columns .= "
        billing_country.meta_value as billing_country,
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
		,it_woocommerce_order_itemmeta3.meta_value 													AS 'product_quantity'
		,it_posts.post_status 																			AS post_status
		,it_posts.post_status 																			AS order_status

		";

		$sql_joins ="{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items

		LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=it_woocommerce_order_items.order_id

		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as woocommerce_order_itemmeta 	ON woocommerce_order_itemmeta.order_item_id		=	it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta2 	ON it_woocommerce_order_itemmeta2.order_item_id	=	it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta3 	ON it_woocommerce_order_itemmeta3.order_item_id	=	it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta4 	ON it_woocommerce_order_itemmeta4.order_item_id	=	it_woocommerce_order_items.order_item_id AND it_woocommerce_order_itemmeta4.meta_key='_line_subtotal'
        LEFT JOIN  {$wpdb->prefix}postmeta as billing_country ON billing_country.post_id = it_posts.ID
        ";




		if($category_id  && $category_id != "-1") {
			$category_id_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 			ON it_term_relationships.object_id		=	woocommerce_order_itemmeta.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 				ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
				//LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 						ON it_terms.term_id					=	term_taxonomy.term_id";
		}

		if(($it_id_order_status  && $it_id_order_status != '-1') || $it_sort_by == "status"){
			$it_id_order_status_join= "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships2			ON it_term_relationships2.object_id	= it_woocommerce_order_items.order_id
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy2				ON it_term_taxonomy2.term_taxonomy_id	= it_term_relationships2.term_taxonomy_id";
				if($it_sort_by == "status"){
					$it_id_order_status_join .= " LEFT JOIN  {$wpdb->prefix}terms 	as terms2 						ON terms2.term_id					=	it_term_taxonomy2.term_id";
				}
		}

		if($it_txt_email || ($it_paid_customer  && $it_paid_customer != '-1' and $it_paid_customer != "'-1'") || $it_sort_by == "billing_email"){
			$it_txt_email_join = "
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id=it_woocommerce_order_items.order_id";
		}
		if(($it_txt_first_name and $it_txt_first_name != '-1') || $it_sort_by == "billing_name"){
			$it_txt_first_name_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_woocommerce_order_items.order_id
			LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_woocommerce_order_items.order_id";
		}

		if($it_country_code and $it_country_code != '-1')
			$it_country_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta4 ON it_postmeta4.post_id=it_woocommerce_order_items.order_id";

		if($state_code && $state_code != '-1')
			$state_code_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_state ON it_postmeta_billing_state.post_id=it_posts.ID";

		if($it_payment_method)
			$it_payment_method_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta5 ON it_postmeta5.post_id=it_woocommerce_order_items.order_id";

		if($it_billing_post_code and $it_billing_post_code != '-1')
			$it_billing_post_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_postcode ON it_postmeta_billing_postcode.post_id	=	it_posts.ID";

		if($it_coupon_used == "yes")
			$it_coupon_used_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta6 ON it_postmeta6.post_id=it_woocommerce_order_items.order_id";

		if($it_coupon_used == "yes")
			$it_coupon_used_join .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta7 ON it_postmeta7.post_id=it_posts.ID";

		if($it_variation_id  && $it_variation_id != "-1") {
			$it_variation_id_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_variation			ON it_woocommerce_order_itemmeta_variation.order_item_id 		= 	it_woocommerce_order_items.order_item_id";
		}

		if($it_variation_only  && $it_variation_only != "-1" && $it_variation_only == "1") {
			$it_variation_only_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_variation			ON it_woocommerce_order_itemmeta_variation.order_item_id 		= 	it_woocommerce_order_items.order_item_id";
		}

		if($it_variations_formated  != "-1" and $it_variations_formated  != NULL){
			$it_variations_formated_join = " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta8 ON it_woocommerce_order_itemmeta8.order_item_id = it_woocommerce_order_items.order_item_id";
			$it_variations_formated_join .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_variation ON it_postmeta_variation.post_id = it_woocommerce_order_itemmeta8.meta_value";
		}

		if($it_order_meta_key and $it_order_meta_key != '-1')
			$it_order_meta_key_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_order_meta_key ON it_order_meta_key.post_id=it_posts.ID";

		if(($it_coupon_codes && $it_coupon_codes != "-1") or ($it_coupon_code && $it_coupon_code != "-1")){
			$it_coupon_codes_join = " LEFT JOIN {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_coupon_item ON it_woocommerce_order_coupon_item.order_id = it_posts.ID AND it_woocommerce_order_coupon_item.order_item_type = 'coupon'";
		}





		$post_type_condition="it_posts.post_type = 'shop_order' AND billing_country.meta_key	= '_billing_country' ";



		if($it_txt_email || ($it_paid_customer  && $it_paid_customer != '-1' and $it_paid_customer != "'-1'") || $it_sort_by == "billing_email"){
			$it_txt_email_condition_1 = "
				AND postmeta.meta_key='_billing_email'";
		}

		if(($it_txt_first_name and $it_txt_first_name != '-1') || $it_sort_by == "billing_name"){
			$it_txt_first_name_condition_1 = "
				AND it_postmeta1.meta_key='_billing_first_name'
				AND it_postmeta2.meta_key='_billing_last_name'";
		}

		$other_condition_1 = "
		AND woocommerce_order_itemmeta.meta_key = '_product_id'
		AND it_woocommerce_order_itemmeta2.meta_key='_line_total'
		AND it_woocommerce_order_itemmeta3.meta_key='_qty' ";



		if($it_country_code and $it_country_code != '-1')
			$it_country_code_condition_1 = " AND it_postmeta4.meta_key='_billing_country'";

		if($state_code && $state_code != '-1')
			$state_code_condition_1 = " AND it_postmeta_billing_state.meta_key='_billing_state'";

		if($it_billing_post_code and $it_billing_post_code != '-1')
			$it_billing_post_code_condition= " AND it_postmeta_billing_postcode.meta_key='_billing_postcode' AND it_postmeta_billing_postcode.meta_value LIKE '%{$it_billing_post_code}%' ";

		if($it_payment_method)
			$it_payment_method_condition_1 = " AND it_postmeta5.meta_key='_payment_method_title'";

		if ($it_from_date != NULL &&  $it_to_date !=NULL){
			$date_condition = " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
		}

		if($order_id)
			$order_id_condition = " AND it_woocommerce_order_items.order_id = ".$order_id;

		if($it_txt_email)
			$it_txt_email_condition_2 = " AND postmeta.meta_value LIKE '%".$it_txt_email."%'";

		if($it_paid_customer  && $it_paid_customer != '-1' and $it_paid_customer != "'-1'")
			$it_paid_customer_condition = " AND postmeta.meta_value IN ('".$it_paid_customer."')";

		//if($it_txt_first_name and $it_txt_first_name != '-1') $sql .= " AND (it_postmeta1.meta_value LIKE '%".$it_txt_first_name."%' OR it_postmeta2.meta_value LIKE '%".$it_txt_first_name."%')";
		if($it_txt_first_name and $it_txt_first_name != '-1')
			$it_txt_first_name_condition_2 = " AND (lower(concat_ws(' ', it_postmeta1.meta_value, it_postmeta2.meta_value)) like lower('%".$it_txt_first_name."%') OR lower(concat_ws(' ', it_postmeta2.meta_value, it_postmeta1.meta_value)) like lower('%".$it_txt_first_name."%'))";

		//if($it_id_order_status  && $it_id_order_status != "-1") $sql .= " AND terms2.term_id IN (".$it_id_order_status .")";

		if($it_publish_order == 'yes')
			$it_publish_order_condition_1 = " AND it_posts.post_status = 'publish'";

		if($it_publish_order == 'publish' || $it_publish_order == 'trash')
			$it_publish_order_condition_2 = " AND it_posts.post_status = '".$it_publish_order."'";

		//if($it_country_code and $it_country_code != '-1')	$sql .= " AND it_postmeta4.meta_value LIKE '%".$it_country_code."%'";

		//if($state_code and $state_code != '-1')	$sql .= " AND it_postmeta_billing_state.meta_value LIKE '%".$state_code."%'";

		if($it_country_code and $it_country_code != '-1')
			$it_country_code_condition_2 = " AND it_postmeta4.meta_value IN (".$it_country_code.")";

		if($state_code && $state_code != '-1')
			$state_code_condition_2 = " AND it_postmeta_billing_state.meta_value IN (".$state_code.")";

		if($it_payment_method)
			$it_payment_method_condition_2 = " AND it_postmeta5.meta_value LIKE '%".$it_payment_method."%'";

		if($it_order_meta_key and $it_order_meta_key != '-1')
			$it_order_meta_key_condition = " AND it_order_meta_key.meta_key='{$it_order_meta_key}' AND it_order_meta_key.meta_value > 0";

		if($it_order_item_name)
			$it_order_item_name_condition = " AND it_woocommerce_order_items.order_item_name LIKE '%".esc_attr($it_order_item_name)."%'";

		if($txtProduct  && $txtProduct != '-1')
			$txtProduct_condition = " AND it_woocommerce_order_items.order_item_name LIKE '%".$txtProduct."%'";

		if($it_product_id  && $it_product_id != "-1")
			$it_product_id_condition = " AND woocommerce_order_itemmeta.meta_value IN (".$it_product_id .")";

		//if($category_id  && $category_id != "-1") $sql .= " AND it_terms.name NOT IN('simple','variable','grouped','external') AND term_taxonomy.taxonomy LIKE('product_cat') AND term_taxonomy.term_id IN (".$category_id .")";
		if($category_id  && $category_id != "-1")
			$category_id_condition = " AND term_taxonomy.taxonomy LIKE('product_cat') AND term_taxonomy.term_id IN (".$category_id .")";


		if($it_id_order_status  && $it_id_order_status != "-1")
			$it_id_order_status_condition = " AND it_term_taxonomy2.taxonomy LIKE('shop_order_status') AND it_term_taxonomy2.term_id IN (".$it_id_order_status .")";

		if($it_coupon_used == "yes")
			$it_coupon_used_condition = " AND( (it_postmeta6.meta_key='_order_discount' AND it_postmeta6.meta_value > 0) ||  (it_postmeta7.meta_key='_cart_discount' AND it_postmeta7.meta_value > 0))";


		if($it_coupon_code && $it_coupon_code != "-1"){
			$it_coupon_code_condition = " AND (it_woocommerce_order_coupon_item.order_item_name IN ('{$it_coupon_code}') OR it_woocommerce_order_coupon_item.order_item_name LIKE '%{$it_coupon_code}%')";
		}

		if($it_coupon_codes && $it_coupon_codes != "-1"){
			$it_coupon_codes_condition = " AND it_woocommerce_order_coupon_item.order_item_name IN ({$it_coupon_codes})";
		}

		if($it_variation_id  && $it_variation_id != "-1") {
			$it_variation_id_condition = " AND it_woocommerce_order_itemmeta_variation.meta_key = '_variation_id' AND it_woocommerce_order_itemmeta_variation.meta_value IN (".$it_variation_id .")";
		}

		if($it_variation_only  && $it_variation_only != "-1" && $it_variation_only == "1") {
			$it_variation_only_condition = " AND it_woocommerce_order_itemmeta_variation.meta_key 	= '_variation_id'
					 AND (it_woocommerce_order_itemmeta_variation.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta_variation.meta_value > 0)";
		}


		if($it_variations_formated  != "-1" and $it_variations_formated  != NULL){
			$it_variations_formated_condition = "
			AND it_woocommerce_order_itemmeta8.meta_key = '_variation_id' AND (it_woocommerce_order_itemmeta8.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta8.meta_value > 0)";
			$it_variations_formated_condition .= "
			AND it_postmeta_variation.meta_value IN ('{$it_variations_formated}')";
		}

		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition = " AND it_posts.post_status IN (".$it_order_status.")";

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition = " AND it_posts.post_status NOT IN ('".$it_hide_os."')";



		$sql ="SELECT $sql_columns FROM $sql_joins";

		$sql .="$category_id_join $it_id_order_status_join $it_txt_email_join $it_txt_first_name_join
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
						$it_product_id_condition $category_id_condition $it_id_order_status_condition
						$it_coupon_used_condition $it_coupon_code_condition $it_coupon_codes_condition
						$it_variation_id_condition $it_variation_only_condition $it_variations_formated_condition
						$it_order_status_condition $it_hide_os_condition ";

		$sql_group_by = " GROUP BY it_woocommerce_order_items.order_item_id ";

		$sql_order_by = " ORDER BY {$it_sort_by} {$it_order_by}";

		$sql .=$sql_group_by.$sql_order_by;

		$sql = "SELECT pmeta.meta_value as address, imeta.meta_value as ship, imetap.meta_value as pid, imetav.meta_value as vid, sum(imeta4.meta_value) as qty, sum(imeta2.meta_value+imeta3.meta_value) as total FROM {$wpdb->prefix}posts as posts left join {$wpdb->prefix}woocommerce_order_items as items ON posts.ID=items.order_id LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as imeta ON items.order_item_id=imeta.order_item_id LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as imeta2 ON imeta.order_item_id=imeta2.order_item_id LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as imeta3 ON imeta.order_item_id=imeta3.order_item_id LEFT JOIN {$wpdb->prefix}postmeta as pmeta ON posts.ID=pmeta.post_id LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as imetap ON imeta.order_item_id=imetap.order_item_id LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as imetav ON imeta.order_item_id=imetav.order_item_id LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as imeta4 ON imeta.order_item_id=imeta4.order_item_id WHERE items.order_item_type='line_item' AND imeta.meta_key='_wcms_cart_key' AND imeta2.meta_key='_line_total' AND imeta3.meta_key='_line_tax' AND pmeta.meta_key='_shipping_addresses' AND (imetap.meta_key='_product_id') AND (imetav.meta_key='_variation_id') AND imeta4.meta_key='_qty' GROUP by ship";
	}
	elseif($file_used=="data_table"){


	    $order_count = $total = 0;

		foreach($this->results as $items) {
			$index_cols      = 0;
			$datatable_value .= ( "<tr class='awr-colored-tbl-row'>" );

			//Shiiping Address

            $shipping_name = $this->getShippingName( $items->ship );
			$shipping_name = (unserialize($shipping_name));
			//print_r($shipping_name);
			$ship_address = '';

			$pid = ($items->pid==0 ? $items->vid : $items->pid);

			$mkey = 'shipping_first_name_'.$items->ship.'_'.$pid.'_1';
            foreach( $shipping_name as $key => $ship ){
                if($key == $mkey) {
	                $ship_address = $ship;
	                break;
                }
            }

			$display_class = '';
			if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				$display_class = 'display:none';
			}
			$datatable_value .= ( "<td style='" . $display_class . "'>" );
			$datatable_value .= $ship_address;
			$datatable_value .= ( "</td>" );





			//Count
			$display_class = '';
			if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				$display_class = 'display:none';
			}
			$datatable_value .= ( "<td style='" . $display_class . "'>" );
			$datatable_value .= $items->qty;
			$order_count += $items->qty;
			$datatable_value .= ( "</td>" );

			//Count
			$display_class = '';
			if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				$display_class = 'display:none';
			}
			$datatable_value .= ( "<td style='" . $display_class . "'>" );
			$datatable_value .= $this->price($items->total);
			$total += $items->total;
			$datatable_value .= ( "</td>" );

			$datatable_value .= ( "</tr>" );
		}


		////ADDED IN VER4.0
		/// TOTAL ROW
		$table_name_total= $table_name;
		$this->table_cols_total = $this->table_columns_total( $table_name_total );
		$datatable_value_total='';
		$datatable_value_total.=("<tr>");
		$datatable_value_total.="<td>$order_count</td>";
		$datatable_value_total.="<td>$total</td>";
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
                        <?php esc_html_e('Shipping Address','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-money"></i></span>
                    <select name="it_shipping_address" >
                        <option value="-1"><?php esc_html_e('Select One','it_report_wcreport_textdomain');?></option>
                        <option value="percent"><?php esc_html_e('Percentage Discount','it_report_wcreport_textdomain');?></option>
                        <option value="fixed_cart"><?php esc_html_e('Fixed Cart Discount','it_report_wcreport_textdomain');?></option>
                        <option value="fixed_product"><?php esc_html_e('Fixed Product Discount','it_report_wcreport_textdomain');?></option>
                    </select>
                </div>

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
