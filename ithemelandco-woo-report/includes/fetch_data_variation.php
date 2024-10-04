<?php
	if($file_used=="sql_table")
	{

		//GET POSTED PARAMETERS
		$it_sort_by 			= $this->it_get_woo_requests('sort_by','product_name',true);
		$it_order_by 			= $this->it_get_woo_requests('order_by','DESC',true);
		$group_by 			= $this->it_get_woo_requests('it_groupby','variation_id',true);

		$it_paid_customer		= $this->it_get_woo_requests('it_customers_paid',"-1",true);

		if($it_paid_customer != NULL  && $it_paid_customer != '-1')
		{
			$it_paid_customer = "'".str_replace(",", "','",$it_paid_customer)."'";
		}

		$it_billing_post_code	= $this->it_get_woo_requests('it_bill_post_code',"-1",true);

		$it_product_sku 		= $this->it_get_woo_requests('it_sku_products','-1',true);
		if($it_product_sku != NULL  && $it_product_sku != '-1'){
			$it_product_sku  		= "'".str_replace(",","','",$it_product_sku)."'";
		}

		$it_variation_sku 		= $this->it_get_woo_requests('it_sku_variations','-1',true);
		if($it_variation_sku != NULL  && $it_variation_sku != '-1'){
			$it_variation_sku  		= "'".str_replace(",","','",$it_variation_sku)."'";
		}

		$page				= $this->it_get_woo_requests('page',NULL);
		$it_show_variation 	= get_option($page.'_show_variation','variable');
		$report_name 		= apply_filters($page.'_default_report_name', 'product_page');

		$report_name 		= $this->it_get_woo_requests('report_name',$report_name,true);
		$admin_page			= $this->it_get_woo_requests('admin_page',$page,true);

		$it_EndDate				= $this->it_get_woo_requests('it_to_date',false);
		$it_StareDate			= $this->it_get_woo_requests('it_from_date',false);
		$category_id		= $this->it_get_woo_requests('it_category_id','-1',true);
		$tag_id		= $this->it_get_woo_requests('it_tag_id','-1',true);

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id		= $this->it_get_woo_requests('it_brand_id','-1',true);

		$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		//$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_product_id			= $this->it_get_woo_requests('it_product_id','-1',true);
		$it_variations			= $this->it_get_woo_requests('it_variations','-1',true);
		$it_variation_column	= $this->it_get_woo_requests('it_variation_cols','1',true);
		$it_show_variation		= $this->it_get_woo_requests('it_show_adr_variaton',$it_show_variation,true);
		$count_generated	= $this->it_get_woo_requests('count_generated',0,true);



		$item_att = array();
		$it_item_meta_key =  '-1';
		if($it_show_variation=='variable' && $it_variations != '-1' and strlen($it_variations) > 0){

				$it_variations = explode(",",$it_variations);
				//$this->print_array($it_variations);
				$var = array();
				foreach($it_variations as $key => $value):
					$var[] .=  "attribute_pa_".$value;
					$var[] .=  "attribute_".$value;
					$item_att[] .=  "pa_".$value;
					$item_att[] .=  $value;
				endforeach;
				$it_variations =  implode("', '",$var);
				$it_item_meta_key =  implode("', '",$item_att);
		}
		$it_variation_attributes= $it_variations;
		$it_variation_item_meta_key= $it_item_meta_key;



		//GET POSTED PARAMETERS
		$start				= 0;
		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$date_format = $this->it_date_format($it_from_date);

		$it_product_id			= $this->it_get_woo_requests('it_product_id',"-1",true);
		$category_id 		= $this->it_get_woo_requests('it_category_id','-1',true);
		$it_cat_prod_id_string = $this->it_get_woo_pli_category($category_id,$it_product_id);

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id 		= $this->it_get_woo_requests('it_brand_id','-1',true);
		$it_brand_prod_id_string = $this->it_get_woo_pli_category($brand_id,$it_product_id);

		$it_sort_by 			= $this->it_get_woo_requests('sort_by','-1',true);
		$it_order_by 			= $this->it_get_woo_requests('order_by','ASC',true);

		$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		//$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		$it_show_cog		= $this->it_get_woo_requests('it_show_cog','no',true);

		///////////HIDDEN FIELDS////////////
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_publish_order='no';


		/////////////////////////
		//APPLY PERMISSION TERMS
		$key=$this->it_get_woo_requests('table_names','',true);

		$category_id=$this->it_get_form_element_permission('it_category_id',$category_id,$key);

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id=$this->it_get_form_element_permission('it_brand_id',$brand_id,$key);

		$it_order_status=$this->it_get_form_element_permission('it_orders_status',$it_order_status,$key);

		if($it_order_status != NULL  && $it_order_status != '-1')
			$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";
		///////////////////////////

		//CUSTOM WORK - 15862
		$it_product_custom_sku		= $this->it_get_woo_requests('it_product_custom_sku','-1',true);
		$it_variation_custom_sku		= $this->it_get_woo_requests('it_variation_custom_sku','-1',true);


		$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
		//////////////////////

		//CUSTOM WORK - 15862
		$it_product_custom_sku_join='';
		$it_product_custom_sku_condition='';

		$it_variation_custom_sku_join='';
		$it_variation_custom_sku_condition='';


		//CATEGORY
		$category_id_join='';
		$category_id_condition='';
		$it_cat_prod_id_string_condition='';
		//TAG
		$tag_id_join='';
		$tag_id_condition='';
		$it_tag_prod_id_string_condition='';

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id_join='';
		$brand_id_condition='';
		$it_brand_prod_id_string_condition='';

		//DATE
		$it_from_date_condition='';

		//PRODUCT ID
		$it_product_id_condition='';

		//ORDER
		$it_id_order_status_join='';

		//VARIATION
		$it_variation_item_meta_key_join='';
		$sql_variation_join='';
		$it_show_variation_join='';
		$it_variation_item_meta_key_condition='';
		$sql_variation_condition='';

		//SKU
		$product_variation_sku_condition='';
		$it_variation_sku_condition='';
		$it_product_sku_condition='';

		//PAID CUSTOMER
		$it_paid_customer_join='';
		$it_paid_customer_condition='';

		//BILLING CODE
		$it_billing_post_code_join='';
		$it_billing_post_code_condition='';

		//ORDER STATUS
		$it_id_order_status_condition='';
		$it_order_status_condition='';

		//HIDE ORDER
		$it_hide_os_condition='';


		$sql_columns = "
		it_woocommerce_order_items.order_item_name			AS 'product_name'
		,SUM(woocommerce_order_itemmeta.meta_value)		AS 'quantity'
		,SUM(it_woocommerce_order_itemmeta6.meta_value)	AS 'amount'";

		//COST OF GOOD
		if($it_show_cog=='yes'){
			$sql_columns .= " ,SUM(woocommerce_order_itemmeta.meta_value * it_woocommerce_order_itemmeta22.meta_value) AS 'total_cost'";
		}

		$sql_columns .= "
		,DATE(shop_order.post_date)						AS post_date
		,it_woocommerce_order_itemmeta7.meta_value			AS product_id
		,it_woocommerce_order_items.order_item_id 			AS order_item_id";

		if($it_show_variation == 'variable') {

			$sql_columns .= ", it_woocommerce_order_itemmeta8.meta_value AS 'variation_id'";

			if($it_sort_by == "sku")
				$sql_columns .= ", IF(it_postmeta_sku.meta_value IS NULL or it_postmeta_sku.meta_value = '', IF(it_postmeta_product_sku.meta_value IS NULL or it_postmeta_product_sku.meta_value = '', '', it_postmeta_product_sku.meta_value), it_postmeta_sku.meta_value) as it_sku ";

			}else{
			if($it_sort_by == "sku")
				$sql_columns .= ", IF(it_postmeta_product_sku.meta_value IS NULL or it_postmeta_product_sku.meta_value = '', '', it_postmeta_product_sku.meta_value) as it_sku";

		}


		if(($it_variation_item_meta_key != "-1" and strlen($it_variation_item_meta_key)>1)){
			$sql_columns .= " , it_woocommerce_order_itemmeta_variation.meta_key AS variation_key";
			$sql_columns .= " , it_woocommerce_order_itemmeta_variation.meta_value AS variation_value";
		}


		$sql_joins =  "
			{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
			LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id	= it_woocommerce_order_items.order_item_id
			LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta6 ON it_woocommerce_order_itemmeta6.order_item_id= it_woocommerce_order_items.order_item_id";

			//COST OF GOOD
			if($it_show_cog=='yes'){
				$sql_joins .=	"
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta22 ON it_woocommerce_order_itemmeta22.order_item_id=it_woocommerce_order_items.order_item_id ";
			}

			$sql_joins .=	"
			LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta7 ON it_woocommerce_order_itemmeta7.order_item_id= it_woocommerce_order_items.order_item_id";



		if($category_id  && $category_id != "-1") {
			$category_id_join= "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_woocommerce_order_itemmeta7.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id
				LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 				ON it_terms.term_id					=	term_taxonomy.term_id";
		}

		if($tag_id  && $tag_id != "-1") {
			$tag_id_join= "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_tag 	ON it_term_relationships_tag.object_id		=	it_woocommerce_order_itemmeta7.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_tag 		ON term_taxonomy_tag.term_taxonomy_id	=	it_term_relationships_tag.term_taxonomy_id
				LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_tag 				ON it_terms_tag.term_id					=	term_taxonomy_tag.term_id";
		}
		////ADDED IN VER4.0
		//BRANDS ADDONS
		if($brand_id  && $brand_id != "-1") {
			$brand_id_join= "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_brand 	ON it_term_relationships_brand.object_id		=	it_woocommerce_order_itemmeta7.meta_value
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_brand 		ON term_taxonomy_brand.term_taxonomy_id	=	it_term_relationships_brand.term_taxonomy_id
				LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_brand 				ON it_terms_brand.term_id					=	term_taxonomy_brand.term_id";
		}

		if($it_id_order_status  && $it_id_order_status != "-1") {
			$it_id_order_status_join= "
				LEFT JOIN  {$wpdb->prefix}term_relationships	as it_term_relationships2 	ON it_term_relationships2.object_id	=	it_woocommerce_order_items.order_id
				LEFT JOIN  {$wpdb->prefix}term_taxonomy			as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
				LEFT JOIN  {$wpdb->prefix}terms					as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
		}


		$sql_joins.=$category_id_join.$tag_id_join.$brand_id_join.$it_id_order_status_join;

		if($it_show_variation == 'variable'){
			$sql_joins .= "
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta8 ON it_woocommerce_order_itemmeta8.order_item_id = it_woocommerce_order_items.order_item_id
					";
			if(($it_sort_by == "sku") || ($it_product_sku and $it_product_sku != '-1') || $it_variation_sku != '-1')
				$sql_joins .= "	LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_sku 		ON it_postmeta_sku.post_id		= it_woocommerce_order_itemmeta8.meta_value";

			if(($it_variation_item_meta_key != "-1" and strlen($it_variation_item_meta_key)>1)){
				$it_variation_item_meta_key_join= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_variation ON it_woocommerce_order_itemmeta_variation.order_item_id= it_woocommerce_order_items.order_item_id";
			}

			$sql_variation_join='';
			if(isset($this->search_form_fields['it_new_value_variations']) and count($this->search_form_fields['it_new_value_variations'])>0){
				foreach($this->search_form_fields['it_new_value_variations'] as $key => $value){
					$new_v_key = "wcvf_".$this->it_woo_filter_chars($key);
					$sql_variation_join= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta_{$new_v_key} ON woocommerce_order_itemmeta_{$new_v_key}.order_item_id = it_woocommerce_order_items.order_item_id";
				}
			}

		}

		//CUSTOM WORK - 15862
		if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){

			if($it_product_custom_sku  && $it_product_custom_sku != "-1") {
				$it_product_custom_sku_join = " LEFT JOIN {$wpdb->prefix}postmeta as it_custom_sku ON it_custom_sku.post_id = it_woocommerce_order_itemmeta7.meta_value ";
			}
			if($it_variation_custom_sku  && $it_variation_custom_sku != "-1") {
				$it_variation_custom_sku_join = " LEFT JOIN {$wpdb->prefix}postmeta as it_v_custom_sku ON it_v_custom_sku.post_id = it_woocommerce_order_itemmeta8.meta_value ";
			}
		}


		$sql_joins.=$it_variation_custom_sku_join.$it_product_custom_sku_join.$it_variation_item_meta_key_join.$sql_variation_join;

		if(($it_sort_by == "sku") || ($it_product_sku and $it_product_sku != '-1'))
			$sql_joins .= "	LEFT JOIN  {$wpdb->prefix}postmeta		 as it_postmeta_product_sku 		ON it_postmeta_product_sku.post_id 			= it_woocommerce_order_itemmeta7.meta_value	";

		$sql_joins .= " LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.id=it_woocommerce_order_items.order_id";//For shop_order

		if($it_show_variation == 2 || ($it_show_variation == 'grouped' || $it_show_variation == 'external' || $it_show_variation == 'simple' || $it_show_variation == 'variable_')){
			$it_show_variation_join= "
					LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_product_type 	ON it_term_relationships_product_type.object_id		=	it_woocommerce_order_itemmeta7.meta_value
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy_product_type 		ON it_term_taxonomy_product_type.term_taxonomy_id		=	it_term_relationships_product_type.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_product_type 				ON it_terms_product_type.term_id						=	it_term_taxonomy_product_type.term_id";
		}

		if($it_paid_customer  && $it_paid_customer != '-1' and $it_paid_customer != "'-1'"){
			$it_paid_customer_join= "
				LEFT JOIN  {$wpdb->prefix}postmeta 			as it_postmeta_billing_email				ON it_postmeta_billing_email.post_id=it_woocommerce_order_items.order_id";
		}

		if($it_billing_post_code and $it_billing_post_code != '-1'){
			$it_billing_post_code_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_postcode ON it_postmeta_billing_postcode.post_id	=	it_woocommerce_order_items.order_id";
		}

		$sql_joins.=$it_show_variation_join.$it_paid_customer_join.$it_billing_post_code_join;

		$sql_condition= "
			woocommerce_order_itemmeta.meta_key	= '_qty'
			AND it_woocommerce_order_itemmeta6.meta_key	= '_line_total' ";

			//COST OF GOOD
			if($it_show_cog=='yes'){
				$sql_condition .="
				AND it_woocommerce_order_itemmeta22.meta_key	= '".__IT_COG_TOTAL__."' ";
			}

			$sql_condition .="
			AND it_woocommerce_order_itemmeta7.meta_key 	= '_product_id'
			AND shop_order.post_type					= 'shop_order'
			";

		//CUSTOM WORK - 15862
		if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
			if($it_product_custom_sku  && $it_product_custom_sku != "-1") {
				$it_product_custom_sku_condition.= " AND it_custom_sku.meta_key = 'jk_sku' AND it_custom_sku.meta_value LIKE '%$it_product_custom_sku%'";
			}
			if($it_variation_custom_sku  && $it_variation_custom_sku != "-1") {
				$it_variation_custom_sku_condition.= " AND it_v_custom_sku.meta_key = 'custom_field' AND it_v_custom_sku.meta_value LIKE '%$it_variation_custom_sku%'";
			}
		}


		if($it_show_variation == 'variable'){
			$sql_condition.= "
					AND it_woocommerce_order_itemmeta8.meta_key = '_variation_id'
					AND (it_woocommerce_order_itemmeta8.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta8.meta_value > 0)
					";

			if(($it_sort_by == "sku") || ($it_variation_sku and $it_variation_sku != '-1'))
				$sql_condition .=	" AND it_postmeta_sku.meta_key	= '_sku'";



			if(($it_variation_item_meta_key != "-1" and strlen($it_variation_item_meta_key)>1)){
				$it_variation_item_meta_key_condition= " AND it_woocommerce_order_itemmeta_variation.meta_key IN ('{$it_variation_item_meta_key}')";
			}

			$sql_variation_condition='';
			if(isset($this->search_form_fields['it_new_value_variations']) and count($this->search_form_fields['it_new_value_variations'])>0){
				foreach($this->search_form_fields['it_new_value_variations'] as $key => $value){
					$new_v_key = "wcvf_".$this->it_woo_filter_chars($key);
					$key = str_replace("'","",$key);
					$sql .= " AND woocommerce_order_itemmeta_{$new_v_key}.meta_key = '{$key}'";
					$vv = is_array($value) ? implode(",",$value) : $value;
					//$vv = str_replace("','",",",$vv);
					$vv = str_replace(",","','",$vv);
					$sql_variation_condition= " AND woocommerce_order_itemmeta_{$new_v_key}.meta_value IN ('{$vv}') ";
				}
			}
		}

		$sql_condition.=$it_variation_custom_sku_condition.$it_product_custom_sku_condition.$it_variation_item_meta_key_condition.$sql_variation_condition;

		if(($it_sort_by == "sku") || ($it_product_sku and $it_product_sku != '-1'))
			$sql_condition .= " AND it_postmeta_product_sku.meta_key			= '_sku'";

		if($it_show_variation == 'variable'){

			if(($it_product_sku and $it_product_sku != '-1') and ($it_variation_sku and $it_variation_sku != '-1')){
				$product_variation_sku_condition= " AND (it_postmeta_product_sku.meta_value IN (".$it_product_sku.") AND it_postmeta_sku.meta_value IN (".$it_variation_sku."))";
			}else if ($it_variation_sku and $it_variation_sku != '-1'){
				$it_variation_sku_condition= " AND it_postmeta_sku.meta_value IN (".$it_variation_sku.")";
			}else{
				if($it_product_sku and $it_product_sku != '-1')
					$it_product_sku_condition= " AND it_postmeta_product_sku.meta_value IN (".$it_product_sku.")";
			}

		}else{

			if($it_product_sku and $it_product_sku != '-1')
				$it_product_sku_condition= " AND it_postmeta_product_sku.meta_value IN (".$it_product_sku.")";

		}

		if ($it_from_date != NULL &&  $it_to_date !=NULL){
			$it_from_date_condition= "
					AND (DATE(shop_order.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
		}

		if($it_product_id  && $it_product_id != "-1")
			$it_product_id_condition= "
					AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_product_id .")";

		if($category_id  && $category_id != "-1")
			$category_id_condition= "
					AND it_terms.term_id IN (".$category_id .")";

		////ADDED IN VER4.0
		//BRANDS ADDONS
		if($brand_id  && $brand_id != "-1")
			$brand_id_condition= "
                AND term_taxonomy_brand.taxonomy LIKE('".__IT_BRAND_SLUG__."')
                AND it_terms_brand.term_id IN (".$brand_id .")";

		if($it_cat_prod_id_string  && $it_cat_prod_id_string != "-1")
			$it_cat_prod_id_string_condition= " AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_cat_prod_id_string .")";

		if($it_tag_prod_id_string  && $it_tag_prod_id_string != "-1")
			$it_tag_prod_id_string_condition= " AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_tag_prod_id_string .")";

		////ADDED IN VER4.0
		//BRANDS ADDONS
		if($it_brand_prod_id_string  && $it_brand_prod_id_string != "-1")
			$it_brand_prod_id_string_condition= " AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_brand_prod_id_string .")";

		if($it_id_order_status  && $it_id_order_status != "-1")
			$it_id_order_status_condition= "
					AND terms2.term_id IN (".$it_id_order_status .")";


		$sql_condition.=$product_variation_sku_condition.$it_variation_sku_condition.$it_product_sku_condition.$it_from_date_condition.$it_product_id_condition.$category_id_condition.$brand_id_condition.$it_cat_prod_id_string_condition.$it_brand_prod_id_string_condition.$it_id_order_status_condition;


		if($it_show_variation == 'grouped' || $it_show_variation == 'external' || $it_show_variation == 'simple' || $it_show_variation == 'variable_'){
			$sql_condition .= " AND it_terms_product_type.name IN ('{$it_show_variation}')";
		}



		if($it_show_variation == 2){
			$sql_condition .= " AND it_terms_product_type.name IN ('simple')";
		}

		if($it_paid_customer  && $it_paid_customer != '-1' and $it_paid_customer != "'-1'"){
			$it_paid_customer_condition= " AND it_postmeta_billing_email.meta_key='_billing_email'";
			$it_paid_customer_condition .= " AND it_postmeta_billing_email.meta_value IN (".$it_paid_customer.")";
		}

		if($it_billing_post_code and $it_billing_post_code != '-1'){
			$it_billing_post_code_condition= " AND it_postmeta_billing_postcode.meta_key='_billing_postcode' AND it_postmeta_billing_postcode.meta_value IN ({$it_billing_post_code}) ";
		}



		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition= " AND shop_order.post_status IN (".$it_order_status.")";

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

		$sql_condition.=$it_paid_customer_condition.$it_billing_post_code_condition.$it_order_status_condition.$it_hide_os_condition;

		$sql_group_by='';
		if($it_show_variation == 'variable'){
			switch ($group_by) {
				case "variation_id":
					$sql_group_by= " GROUP BY it_woocommerce_order_itemmeta8.meta_value ";
					break;
				case "order_item_id":
					$sql_group_by= " GROUP BY it_woocommerce_order_items.order_item_id ";
					break;
				default:
					$sql_group_by= " GROUP BY it_woocommerce_order_itemmeta8.meta_value ";
					break;

			}
			//$sql .= " GROUP BY it_woocommerce_order_itemmeta8.meta_value ";
		}else{
			$sql_group_by= "
					GROUP BY  it_woocommerce_order_itemmeta7.meta_value";
		}

		$sql_order_by='';
		switch ($it_sort_by) {
			case "sku":
				$sql_order_by= " ORDER BY sku " .$it_order_by;
				break;
			case "product_name":
				$sql_order_by= " ORDER BY product_name " .$it_order_by;
				break;
			case "ProductID":
				$sql_order_by= " ORDER BY CAST(product_id AS DECIMAL(10,2)) " .$it_order_by;
				break;
			case "amount":
				$sql_order_by= " ORDER BY amount " .$it_order_by;
				break;
			case "variation_id":
				if($it_show_variation == 'variable'){
					$sql_order_by= " ORDER BY CAST(variation_id AS DECIMAL(10,2)) " .$it_order_by;
				}
				break;
			default:
				$sql_order_by= " ORDER BY amount DESC";
				break;
		}

		$sql="SELECT $sql_columns FROM $sql_joins WHERE $sql_condition $sql_group_by $sql_order_by";

		//echo $sql;


        $this->table_cols =$this->table_columns($table_name);
		///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
		$array_index=5;
		$brands_cols=array();
		if(__IT_BRAND_SLUG__){
			$brands_cols[]=array('lable'=>__IT_BRAND_LABEL__,'status'=>'show');
			array_splice($this->table_cols,$array_index,0,$brands_cols);
			$array_index++;
		}

		///////////////////
		//VARIATIONS COLUMNS

		if($it_show_variation=='variable')
		{
			//$array_index=3;
			$data_variation=array();
			$variation_cols_arr=array();
			$attributes_available=$this->it_get_woo_pv_atts('yes');
			$it_variation_attributes 	= $this->it_get_woo_requests('it_variations',NULL,true);

			$it_variation_column	= $this->it_get_woo_requests('it_variation_cols','1',true);
			if($it_variation_column=='1')
			{
				$variation_sel_arr	= '';
				if($it_variation_attributes != NULL  && $it_variation_attributes != '-1')
				{
					$it_variation_attributes = str_replace("','", ",",$it_variation_attributes);
					$variation_sel_arr = explode(",",$it_variation_attributes);
				}

				foreach($attributes_available as $key => $value){
					$new_key = str_replace("wcv_","",$key);
					if($it_variation_attributes=='-1' || is_array($variation_sel_arr) && in_array($new_key,$variation_sel_arr))
					{
						$data_variation[]=$new_key;
						$variation_cols_arr[] = array('lable'=>$value,'status'=>'show');
					}
				}
			}else
			{
				$variation_cols_arr[]=array('lable'=>'variation','status'=>'show');
			}


			$head = array_slice($this->table_cols, 0, $array_index);

			$tail = array_slice($this->table_cols, $array_index);

			$this->table_cols = array_merge($head, $variation_cols_arr, $tail);

			$this->data_variation=$data_variation;
		}


		//CUSTOM WORK - 15862
		if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
			$custom_sku_cols[]=array('lable'=>'Custom SKU','status'=>'show');
			$custom_sku_cols[]=array('lable'=>'Variation Custom SKU','status'=>'show');
			array_splice($this->table_cols,2,0,$custom_sku_cols);
		}


		//CHECK IF COST OF GOOD IS ENABLE
		if($it_show_cog!='yes'){
			unset($this->table_cols[count($this->table_cols)-1]);
			unset($this->table_cols[count($this->table_cols)-1]);
		}

	//	echo $sql;

	}elseif($file_used=="data_table"){

		$it_show_variation		= $this->it_get_woo_requests('it_show_adr_variaton','variable',true);


		////ADDE IN VER4.0
		/// TOTAL ROWS VARIABLES
		$result_count=$sale_qty=$total_amnt=$cog_amnt=$profit_amnt=0;

		foreach($this->results as $items){
		    $index_cols=0;
		//for($i=1; $i<=20 ; $i++){

			////ADDE IN VER4.0
			/// TOTAL ROWS
			$result_count++;

			$datatable_value.=("<tr>");


				//Product ID
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->product_id;
				$datatable_value.=("</td>");

				//Product SKU
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->it_get_prod_sku($items->order_item_id, $items->product_id/*,"wcx_wcreport_plugin_variation",$it_show_variation*/);
				$datatable_value.=("</td>");

			    //CUSTOM WORK - 15862
                if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
                    //Custom SKU
                    $display_class = '';
                    $custom_sku    = get_post_meta( $items->product_id, 'jk_sku', true );
                    if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ( "<td style='" . $display_class . "' data-product-id='" . $items->product_id . "'>" );
                    $datatable_value .= $custom_sku;
                    $datatable_value .= ( "</td>" );
                }

                //CUSTOM WORK - 15862
                if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
                    //Custom SKU
                    $display_class = '';
                    $custom_sku    = get_post_meta( $items->variation_id, 'custom_field', true );
                    if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ( "<td style='" . $display_class . "' data-product-id='" . $items->variation_id . "'>" );
                    $datatable_value .= $custom_sku;
                    $datatable_value .= ( "</td>" );
                }


				//Product Name
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->product_name;
				$datatable_value.=("</td>");
                //Categories
                $display_class='';
                if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= $this->it_get_cn_product_id($items->product_id,"product_cat");
                $datatable_value.=("</td>");


                //Tags
                $display_class='';
                if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= $this->it_get_cn_product_id($items->product_id,"product_tag");
                $datatable_value.=("</td>");

                ///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
                if(__IT_BRAND_SLUG__){
                    $display_class='';
                    if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    $datatable_value.=("<td style='".$display_class."'>");
                    $datatable_value.= $this->it_get_cn_product_id($items->product_id,__IT_BRAND_SLUG__);
                    $datatable_value.=("</td>");
                }

				$j=3;
				//VARIATION COLUMNS
				$it_show_variation	= $this->it_get_woo_requests('it_show_adr_variaton','simple',true);
				if($it_show_variation=='variable')
				{

					$it_variation_column	= $this->it_get_woo_requests('it_variation_cols','1',true);
					if($it_variation_column=='1')
					{
						$variation_arr=array();
						$variation = $this->it_get_product_var_col_separated($items->order_item_id);
						foreach($variation as $key => $value){
							$variation_arr[$key]=$value;
						}


						foreach($this->data_variation as $variation_name){
							$it_table_value=(!empty($variation_arr[$variation_name]) ? $variation_arr[$variation_name] : "-");
							$display_class='';
							if($this->table_cols[$j++]['status']=='hide') $display_class='display:none';
							$datatable_value.=("<td style='".$display_class."'>");
								$datatable_value.= $it_table_value; //$this->it_get_woo_variation($items->order_item_id);
							$datatable_value.=("</td>");
						}
					}
					else{
						$variation_arr=array();
						$variation = $this->it_get_product_var_col_separated($items->order_item_id);
						foreach($variation as $key => $value){
							$variation_arr[]=$value;
						}

						$display_class='';
						if($this->table_cols[$j++]['status']=='hide') $display_class='display:none';
						$datatable_value.=("<td style='".$display_class."'>");
							$datatable_value.=implode(',',$variation_arr); //$this->it_get_woo_variation($items->order_item_id);
						$datatable_value.=("</td>");
					}
				}

				//Sales Qty.
				$display_class='';
				if($this->table_cols[$j++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->quantity;

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $sale_qty+= $items->quantity;

				$datatable_value.=("</td>");

				//Current Stock
				$display_class='';
				if($this->table_cols[$j++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->it_get_prod_stock_($items->order_item_id, $items->product_id);
				$datatable_value.=("</td>");

				//Amount
				$display_class='';
				if($this->table_cols[$j++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($items->amount);

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $total_amnt+= $items->amount;

				$datatable_value.=("</td>");

				//COST OF GOOD
				$it_show_cog= $this->it_get_woo_requests('it_show_cog',"no",true);
				if($it_show_cog=='yes'){
					$display_class='';
					/*$cog=get_post_meta($items->product_id,__IT_COG__,true);
					$cog*=$items->quantity;*/
					if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
					$datatable_value.=("<td style='".$display_class."'>");
						//$datatable_value.= $cog == 0 ? $this->price(0) : $this->price($cog);
						$datatable_value.= $items->total_cost == 0 ? $this->price(0) : $this->price($items->total_cost);

                        ////ADDED IN VER4.0
                        /// TOTAL ROWS
                        $cog_amnt+=$items->total_cost;

					$datatable_value.=("</td>");

					if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
					$datatable_value.=("<td style='".$display_class."'>");
						//$datatable_value.= $cog == 0 ? $this->price(0) : $this->price($cog);
						$datatable_value.= ($items->amount-$items->total_cost) == 0 ? $this->price(0) : $this->price($items->amount-$items->total_cost);

                        ////ADDED IN VER4.0
                        /// TOTAL ROWS
                        $profit_amnt+=($items->amount-$items->total_cost);

					$datatable_value.=("</td>");
				}



			$datatable_value.=("</tr>");
		}

		////ADDE IN VER4.0
		/// TOTAL ROWS
		$table_name_total= $table_name;
		$datatable_value_total='';
		$it_show_cog		= $this->it_get_woo_requests('it_show_cog','no',true);
		$this->table_cols_total = $this->table_columns_total( $table_name_total );
		if($it_show_cog!='yes'){
			////ADDE IN VER4.0
			/// COST OF GOOD
			unset($this->table_cols_total[count($this->table_cols_total)-1]);
			unset($this->table_cols_total[count($this->table_cols_total)-1]);
		}

		$datatable_value_total.=("<tr>");
		$datatable_value_total.="<td>$result_count</td>";
		$datatable_value_total.="<td>$sale_qty</td>";
		$datatable_value_total.="<td>".(($total_amnt) == 0 ? $this->price(0) : $this->price($total_amnt))."</td>";
		if($it_show_cog=='yes'){
			$datatable_value_total.="<td>".(($cog_amnt) == 0 ? $this->price(0) : $this->price($cog_amnt))."</td>";
			$datatable_value_total.="<td>".(($profit_amnt) == 0 ? $this->price(0) : $this->price($profit_amnt))."</td>";
		}
		$datatable_value_total.=("</tr>");

	}elseif($file_used=="search_form"){
	?>
		<form class='alldetails search_form_report' action='' method='post'>
            <input type='hidden' name='action' value='submit-form' />
            <div class="row">

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('From Date','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('To Date','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"/>
                </div>

                <?php
                	$col_style='';
					$permission_value=$this->get_form_element_value_permission('it_category_id');
					if($this->get_form_element_permission('it_category_id') ||  $permission_value!=''){

						if(!$this->get_form_element_permission('it_category_id') &&  $permission_value!='')
							$col_style='display:none';
				?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
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
					$col_style='';
					$permission_value=$this->get_form_element_value_permission('it_tag_id');
					if($this->get_form_element_permission('it_tag_id') ||  $permission_value!=''){

						if(!$this->get_form_element_permission('it_tag_id') &&  $permission_value!='')
							$col_style='display:none';
				?>

                    <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                        <div class="awr-form-title">
                            <?php esc_html_e('Tag','it_report_wcreport_textdomain');?>
                        </div>
                        <span class="awr-form-icon"><i class="fa fa-tags"></i></span>
                        <?php
                        $args = array(
							'taxonomy' => 'product_tag',
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
                        $current_category=$this->it_get_woo_requests_links('it_tag_id','',true);

                        $categories = get_terms($args);
                        $option='';
                        foreach ($categories as $category) {
                            $selected='';
                            //CHECK IF IS IN PERMISSION
                            if(is_array($permission_value) && !in_array($category->term_id,$permission_value))
                                continue;


                            $option .= '<option value="'.$category->term_id.'" '.$selected.'>';
                            $option .= $category->name;
                            $option .= ' ('.$category->count.')';
                            $option .= '</option>';
                        }
                        ?>
                        <select name="it_tag_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                            <?php
                            if($this->get_form_element_permission('it_tag_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
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

				?>


                <div class="col-md-6">
                    <div class="awr-form-title">
			            <?php esc_html_e('Product','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-cog"></i></span>

                    <select id="it_adr_product" name="it_product_id[]" multiple="multiple" size="5"  data-size="5" class="player-select form-control achosen-select-search" data-element-type="simple_products">
                    </select>

                </div>


                <div class="col-md-6">
                    <div class="awr-form-title">
			            <?php esc_html_e('Customer','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-cog"></i></span>

                    <select name="it_customers_paid[]" multiple="multiple" size="5"  data-size="5" class="player-select form-control achosen-select-search" data-element-type="customer">
                    </select>

                </div>

                <div class="col-md-6" >
                    <div class="awr-form-title">
                        <?php esc_html_e('Postcode(zip)','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-map-marker"></i></span>
                    <input name="it_bill_post_code" type="text" class="postcode"/>
                </div>

                <?php
                	$col_style='';
					$permission_value=$this->get_form_element_value_permission('it_orders_status');
					if($this->get_form_element_permission('it_orders_status')||  $permission_value!=''){

						if(!$this->get_form_element_permission('it_orders_status') &&  $permission_value!='')
							$col_style='display:none';
				?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('Status','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-map"></i></span>
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
							//CHECK IF IS IN PERMISSION
							if(is_array($permission_value) && !in_array($key,$permission_value))
								continue;


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
				?>

                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Variations','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-bolt"></i></span>
					<?php
                        $option='';
                        $it_variations=$this->it_get_woo_pv_atts('yes');


                        foreach($it_variations as $key=>$value){
							$selected='';
                            $new_key = str_replace("wcv_","",$key);
                            $option.="<option value='".$new_key."' >".$value." </option>";
                        }
                    ?>

                    <select name="it_variations[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search variation_elements">
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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
                	//echo $this->it_get_woo_var_dropdowns();
				echo wp_kses(
					$this->it_get_woo_var_dropdowns,
					$this->allowedposttags()
				);
				?>

                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Show ','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-eye"></i></span>
					<?php

                        $products=array(
                            "all" => json_decode($this->it_get_product_woo_data('0',true)),
                            "simple" => json_decode($this->it_get_product_woo_data('simple',true)),
                            "variable" => json_decode($this->it_get_product_woo_data('1',true)),
                        );

                        $json_products = wp_json_encode($products);
                        //print_r($json_products);
                    ?>
                    <select id="it_adr_show_variations" name="it_show_adr_variaton">
                        <option value="variable" selected><?php esc_html_e('Variation Products ','it_report_wcreport_textdomain');?></option>
                        <option value="simple" ><?php esc_html_e('Simple Products ','it_report_wcreport_textdomain');?></option>
                        <option value="-1"><?php esc_html_e('All Products ','it_report_wcreport_textdomain');?></option>
                    </select>

                    <script type="text/javascript">
						"use strict";
						jQuery( document ).ready(function( $ ) {

							var products='';
							products=<?php echo esc_js($json_products)?>;

                            $("#it_adr_show_variations").change(function(){
                                var show_val=$(this).val();
                                //confirm(show_val);
                                var product_type='';
                                if(show_val=='simple') {
                                    $('#it_adr_product').attr("data-element-type", "simple_products");
                                    product_type="simple_products";
                                }else if(show_val=='variable') {
                                    $('#it_adr_product').attr("data-element-type", "variable_products");
                                    product_type="variable_products";
                                }else if(show_val=='-1') {
                                    $('#it_adr_product').attr("data-element-type", "all_products");
                                    product_type="all_products";
                                }
                                $('#it_adr_product').val(null).trigger('change');

                                $('#it_adr_product').select2({
                                    ajax: {
                                        // url: "https://raw.githubusercontent.com/kshkrao3/JsonFileSample/master/select2resp.json",
                                        url : params.ajaxurl,
                                        // dataType: 'html',
                                        dataType: 'json',
                                        type: "GET",
                                        //delay: 250,
                                        //data:  pdata,
                                        data: function(it_params) {
                                            return {
                                                action : "it_chosen_ajax",
                                                // postdata : "q="+it_params.term+"&target_type="+ (product_type=='' ? :product_type),
                                                postdata : "q="+it_params.term+"&target_type="+ product_type,
                                                nonce : params.nonce,
                                            };
                                        },
                                        processResults: function(data, it_params) {
                                            //confirm(data);
                                            // confirm(it_params);
                                            // parse the results into the format expected by Select2
                                            // since we are using custom formatting functions we do not need to
                                            // alter the remote JSON data, except to indicate that infinite
                                            // scrolling can be used
                                            var resData = [];
                                            data.forEach(function(value) {
                                                //if (value.LastName.indexOf(it_params.term) != -1)
                                                resData.push(value)
                                            })
                                            return {
                                                results: $.map(resData, function(item) {
                                                    return {
                                                        text: item.label,
                                                        id: item.id
                                                    }
                                                })
                                            };
                                        },
                                        cache: true
                                    },
                                    minimumInputLength: 1,
                                    //multiple : true,
                                    //allowClear: true,
                                    //minimumResultsForSearch: -1,
                                    placeholder: 'past your placeholder'
                                });

                            });



							$("#it_adr_show_variations").trigger("change");

						});

					</script>

                </div>


                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Style','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-paint-brush"></i></span>
                    <select name="it_variation_cols[]" id="it_variation_cols" class="it_variation_cols variation_elements" disabled>
                        <option value="1" selected="selected"><?php esc_html_e('Columner','it_report_wcreport_textdomain');?></option>
                        <option value="0"><?php esc_html_e('Comma Separated','it_report_wcreport_textdomain');?></option>
                    </select>
                </div>

                <?php
                	$it_product_sku_data = $this->it_get_woo_prod_sku();
					if($it_product_sku_data){
				?>

                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Product SKU','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-cog"></i></span>
					<?php
                        $option='';
                        foreach($it_product_sku_data as $sku){
                            $option.="<option value='".$sku->id."' >".$sku->label."</option>";
                        }
                    ?>

                    <select name="it_sku_products[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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
					$it_variation_sku_data = $this->it_get_woo_var_sku();
					if($it_variation_sku_data){
				?>

                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Variation SKU','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-bolt"></i></span>
					<?php
                        $option='';
                        foreach($it_variation_sku_data as $sku){
                            $option.="<option value='".$sku->id."' >".$sku->label."</option>";
                        }
                    ?>
                    <select name="it_sku_variations[]" class="variation_elements chosen-select-search" multiple="multiple" size="5"  data-size="5">
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
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


	            <?php
	            //CUSTOM WORK - 15862
	            if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
		            ?>
                    <div class="col-md-6">
                        <div class="awr-form-title">
				            <?php esc_html_e('Product Custom SKU','it_report_wcreport_textdomain');?>
                        </div>
                        <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                        <input name="it_product_custom_sku" type="text" />
                    </div>
		            <?php
	            }
	            ?>

	            <?php
	            //CUSTOM WORK - 15862
	            if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
		            ?>
                    <div class="col-md-6">
                        <div class="awr-form-title">
				            <?php esc_html_e('Variation Custom SKU','it_report_wcreport_textdomain');?>
                        </div>
                        <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                        <input name="it_variation_custom_sku" type="text" />
                    </div>
		            <?php
	            }
	            ?>


                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Order By','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-sort-alpha-asc"></i></span>
                    <div class="row">
						<div class="col-md-5">
							<select name="sort_by" id="sort_by" class="sort_by"><option value="product_name">Product Name</option><option value="ProductID">Product ID</option><option value="variation_id">Variation ID</option><option value="amount">Amount</option></select>
						</div>
						<div class="col-md-7 ">
							<select name="order_by" id="order_by" class="order_by">
								<option value="ASC"><?php esc_html_e('Ascending','it_report_wcreport_textdomain');?></option>
								<option value="DESC" selected="selected"><?php esc_html_e('Descending','it_report_wcreport_textdomain');?></option>
							</select>
						</div>
					</div>
                </div>

                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Group By','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-suitcase"></i></span>
                    <select name="it_groupby" id="variation_group_by" class="group_by variation_elements" disabled>
                        <option value="variation_id"><?php esc_html_e('Variation ID','it_report_wcreport_textdomain');?></option>
                        <option value="order_item_id"><?php esc_html_e('Order Item ID','it_report_wcreport_textdomain');?></option>
                    </select>
                </div>

                <?php
            	if(__IT_COG__!=''){
				?>

					<div class="col-md-6">
						<div class="awr-form-title">
							<?php esc_html_e('SHOW JUST INCLUDE C.O.G & PROFIT','it_report_wcreport_textdomain');?>
                            <br />
                            <span class="description"><?php esc_html_e('Include just products with current Profit(Cost of good) plugin(Selected in Setting -> Add-on Settings -> Cost of Good).','it_report_wcreport_textdomain');?></span>
						</div>

						<input name="it_show_cog" type="checkbox" value="yes"/>

					</div>
				<?php
					}
				?>

            </div>

            <div class="col-md-12 awr-save-form">
                    <?php
                    	$it_hide_os=$this->otder_status_hide;
						$it_publish_order='no';

						$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
					?>
                    <input type="hidden" name="list_parent_category" value="">
                    <input type="hidden" name="group_by_parent_cat" value="0">

                	<input type="hidden" name="it_hide_os" id="it_hide_os" value="<?php echo esc_html($it_hide_os);?>" />

                    <input type="hidden" name="date_format" id="date_format" value="<?php echo esc_html($data_format);?>" />

                	<input type="hidden" name="table_names" value="<?php echo esc_html($table_name);?>"/>
                    <div class="fetch_form_loading search-form-loading"></div>
                    <button type="submit" value="Search" class="button-primary"><i class="fa fa-search"></i> <span><?php echo esc_html__('Search','it_report_wcreport_textdomain'); ?></span></button>
					<button type="button" value="Reset" class="button-secondary form_reset_btn"><i class="fa fa-reply"></i><span><?php echo esc_html__('Reset Form','it_report_wcreport_textdomain'); ?></span></button>
            </div>

        </form>
    <?php
	}

?>
