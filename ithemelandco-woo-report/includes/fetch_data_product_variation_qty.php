<?php

if($file_used=="sql_table")
{

	//GET POSTED PARAMETERS
	$it_sort_by 			= $this->it_get_woo_requests('sort_by','product_name',true);
	$it_order_by 			= $this->it_get_woo_requests('order_by','DESC',true);
	$group_by 			= $this->it_get_woo_requests('it_groupby','variation_id',true);


	$it_order_ids		= $this->it_get_woo_requests('it_order_ids',"-1",true);
	if($it_order_ids != NULL  && $it_order_ids != '-1')
	{
		$it_order_ids = "'".str_replace(",", "','",$it_order_ids)."'";
	}


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

	$it_show_variation='-1';

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



	$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
	//////////////////////


	//CATEGORY
	$category_id_join='';
	$category_id_condition='';
	$it_cat_prod_id_string_condition='';

	//ORDER ID
	$order_id_condition='';

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

	$sql_columns .= ", it_woocommerce_order_itemmeta8.meta_value AS 'variation_id'";

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


	$sql_joins.=$category_id_join.$brand_id_join.$it_id_order_status_join;
	$sql_joins .= "
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta8 ON it_woocommerce_order_itemmeta8.order_item_id = it_woocommerce_order_items.order_item_id
					";
	if($it_show_variation == 'variable'){

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

	$sql_joins.=$it_variation_item_meta_key_join.$sql_variation_join;

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
	$sql_condition.= "
					AND it_woocommerce_order_itemmeta8.meta_key = '_variation_id'
					AND (it_woocommerce_order_itemmeta8.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta8.meta_value > 0)
					";
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

	$sql_condition.=$it_variation_item_meta_key_condition.$sql_variation_condition;

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
					AND (DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
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
		$it_order_status_condition= " AND it_posts.post_status IN (".$it_order_status.")";

	if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
		$it_hide_os_condition= " AND it_posts.post_status NOT IN ('".$it_hide_os."')";


	if($it_order_ids  && $it_order_ids != "-1")
		$order_id_condition= "
					AND it_posts.ID IN (".$it_order_ids .")";

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
	$array_index=3;
	$brands_cols=array();
	if(__IT_BRAND_SLUG__){
		$brands_cols[]=array('lable'=>__IT_BRAND_LABEL__,'status'=>'show');
		array_splice($this->table_cols,$array_index,0,$brands_cols);
		$array_index++;
	}


	$sql="SELECT  (DATE_FORMAT(it_posts.post_date,'%m/%d/%Y')) AS order_date, (it_woocommerce_order_items.order_id) AS order_id,	(it_woocommerce_order_items.order_item_name) AS product_name,	(it_woocommerce_order_items.order_item_id)	AS order_item_id ,(count(it_woocommerce_order_items.order_item_id)) AS product_quentity, (woocommerce_order_itemmeta.meta_value) AS product_id ,(it_woocommerce_order_itemmeta4.meta_value) as variation_id ,(it_woocommerce_order_itemmeta3.meta_value) AS product_quantity	,(it_posts.post_status) AS order_status FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items LEFT JOIN {$wpdb->prefix}posts as it_posts ON it_posts.ID=it_woocommerce_order_items.order_id	LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id	=	it_woocommerce_order_items.order_item_id  LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta3 ON it_woocommerce_order_itemmeta3.order_item_id	=	it_woocommerce_order_items.order_item_id LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta4 ON it_woocommerce_order_itemmeta4.order_item_id	=	it_woocommerce_order_items.order_item_id Where (it_posts.post_type = 'shop_order' OR it_posts.post_type='shop_order_refund')   AND it_woocommerce_order_itemmeta4.meta_key = '_variation_id' AND ((woocommerce_order_itemmeta.meta_key = '_product_id' AND it_woocommerce_order_itemmeta3.meta_key='_qty') OR (woocommerce_order_itemmeta.meta_key = '_fee_amount')) $it_from_date_condition $it_order_status_condition $it_hide_os_condition $category_id_condition $order_id_condition AND it_posts.post_status IN ('wc-processing','wc-on-hold','wc-completed') AND it_posts.post_status NOT IN ('trash') GROUP BY it_woocommerce_order_items.order_item_id ORDER BY variation_id ASC";


	//echo $sql;

}elseif($file_used=="data_table"){

		$it_show_variation		= $this->it_get_woo_requests('it_show_adr_variaton','variable',true);


	    $i=$j=0;

       // var_dump($this->sql2);

	$array_product=array();
        foreach($this->results as $items){
//            $array_variation[$items->variation_id]['name']=$items->product_name;
//            $array_variation[$items->variation_id]['qty']=$items->quantity;


	        $it_table_value= $this->it_get_woo_variation($items->order_item_id);
	        $order_item_id			= ($items->order_item_id);
	        $attributes 							= $this->it_get_variaiton_attributes('order_item_id','',$order_item_id);
	        $varation_string 						= isset($attributes['item_varation_string']) ? $attributes['item_varation_string'] : array();
	        $it_table_value			= $varation_string[$order_item_id]['varation_string'];

            $product_id=$items->product_id;
	        $variation_id=$items->variation_id;


	        if($variation_id=='0' || $variation_id==''){

	            $row=$i;
	            $qty=$items->product_quantity;
	            foreach($array_product as $key=>$val){
	               // echo $key;
	                if($val[0]==$product_id){
		                $array_product[$key][2]+=$qty;
	                    $row=$key;
	                    break;
                    }
                }

		        if($row!=$i) continue;

	            $array_product[$row][0]=$product_id;
	            $array_product[$row][1]=$items->product_name;



	            $array_product[$row][2]=$qty;

	            $array_product[$row][3]='*';

	            if(!isset($array_product[$row][4])) $array_product[$row][4]='';
	            if(!isset($array_product[$row][5])) $array_product[$row][5]='';
	            if(!isset($array_product[$row][6])) $array_product[$row][6]='';
	            if(!isset($array_product[$row][7])) $array_product[$row][7]='';

	            $i++;
            }

	        if($variation_id!='0' && $variation_id!=''){

		        $row=$j;
		        $qty=$items->product_quantity;
		        foreach($array_product as $key=>$val){
			        if($val[4]==$variation_id){
				        $array_product[$key][7]+=$qty;
				        $row=$key;
				        break;
			        }
		        }

                if($row!=$j) continue;

		        if(!isset($array_product[$row][0])) $array_product[$row][0]='';
		        if(!isset($array_product[$row][1])) $array_product[$row][1]='';
		        if(!isset($array_product[$row][2])) $array_product[$row][2]='';

		        $array_product[$row][3]='*';

		        $array_product[$row][4]=($variation_id);
		        $array_product[$row][5]=($items->product_name);
		        $array_product[$row][6]=$it_table_value;
		        $array_product[$row][7]=$qty;
		        $j++;
	        }

	        //$i++;
        }
//	    print_r($array_product);
//	    print_r($array_variation);
//var_dump($array_product);


        foreach($array_product as $key=>$value){
	        $datatable_value.=("<tr>");
            foreach($value as $keys=>$items) {

                if($keys==0 || $keys==4) continue;

	            $display_class = '';

	            if($items=='*') {
		            $display_class .= 'background-color: #b0b9bf;';
		            $items=' ';
	            }

	            $datatable_value .= ( "<td style='" . $display_class . "'>" );
	            $datatable_value .= $items;
	            $datatable_value .= ( "</td>" );
            }
	        $datatable_value.=("</tr>");
        }


	////ADDE IN VER4.0
		/// TOTAL ROWS VARIABLES
		$result_count=$sale_qty=$total_amnt=$cog_amnt=$profit_amnt=0;



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


                <div class="col-md-6">
                    <div class="awr-form-title">
			            <?php esc_html_e('Order ID(s)','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_order_ids" id="it_order_ids" type="text" placeholder="<?php esc_html_e('Separate ids with coma(,)','it_report_wcreport_textdomain');?>"/>
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
							/*if(!$this->get_form_element_permission('it_orders_status') &&  $permission_value!='')
								$selected="selected";*/

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
