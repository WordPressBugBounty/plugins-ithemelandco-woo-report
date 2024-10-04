<?php
if($file_used=="sql_table")
{
	
	//show_seleted_order_status
	global $wpdb;
	
	$it_from_date=$this->it_from_date_dashboard;
	$it_to_date=$this->it_to_date_dashboard;
	$date_format = $this->it_date_format($it_from_date);
	$it_hide_os=$this->otder_status_hide;
	$it_shop_order_status=$this->it_shop_status;
	
	if(isset($_POST['it_from_date']))
	{
		//parse_str($_REQUEST, $my_array_of_vars);
		$this->search_form_fields=$_POST;

		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$it_hide_os	= $this->it_get_woo_requests('it_hide_os',$it_hide_os,true);
		$it_shop_order_status	= $this->it_get_woo_requests('shop_order_status',$it_shop_order_status,true);

	}
	
	$it_create_date =  gmdate("Y-m-d");
	$it_url_shop_order_status	= "";
	$it_in_shop_os	= "";
	$it_in_post_os	= "";
	
	
//	$it_hide_os=$this->otder_status_hide;
//	$it_hide_os	= $this->it_get_woo_requests('it_hide_os',$it_hide_os,true);
	$it_hide_os=explode(',',$it_hide_os);		
	//$it_shop_order_status="wc-completed,wc-on-hold,wc-processing";
	//$it_shop_order_status	= $this->it_get_woo_requests('shop_order_status',$it_shop_order_status,true);
	if(strlen($it_shop_order_status)>0 and $it_shop_order_status != "-1") 
		$it_shop_order_status = explode(",",$it_shop_order_status); 
	else $it_shop_order_status = array();
	
	if(count($it_shop_order_status)>0){
		$it_in_post_os	= implode("', '",$it_shop_order_status);	
	}
	
	$in_it_hide_os = "";
	if(count($it_hide_os)>0){
		$in_it_hide_os		= implode("', '",$it_hide_os);				
	}
	
	$per_page=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_category_post_per_page',5);
	
	$it_shop_order_status_condition ='';
	$it_from_date_condition ='';
	$it_hide_os_condition ='';
	
	$sql_columns = " SUM(it_woocommerce_order_itemmeta_product_qty.meta_value) AS quantity 
	,SUM(it_woocommerce_order_itemmeta_product_line_total.meta_value) AS total_amount
	,it_terms_product_id.term_id AS category_id 
	,it_terms_product_id.name AS category_name 
	,it_term_taxonomy_product_id.parent AS parent_category_id
	,it_terms_parent_product_id.name AS parent_category_name";
	
	$sql_joins = " {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_id ON it_woocommerce_order_itemmeta_product_id.order_item_id=it_woocommerce_order_items.order_item_id
	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_qty ON it_woocommerce_order_itemmeta_product_qty.order_item_id=it_woocommerce_order_items.order_item_id
	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_line_total ON it_woocommerce_order_itemmeta_product_line_total.order_item_id=it_woocommerce_order_items.order_item_id
	LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_product_id 	ON it_term_relationships_product_id.object_id		=	it_woocommerce_order_itemmeta_product_id.meta_value 
	LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy_product_id 		ON it_term_taxonomy_product_id.term_taxonomy_id	=	it_term_relationships_product_id.term_taxonomy_id
	LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_product_id 				ON it_terms_product_id.term_id						=	it_term_taxonomy_product_id.term_id
	LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_parent_product_id 				ON it_terms_parent_product_id.term_id						=	it_term_taxonomy_product_id.parent
	LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.id=it_woocommerce_order_items.order_id";
		
	$sql_condition = " 1*1 
	AND it_woocommerce_order_items.order_item_type = 'line_item'
	AND it_woocommerce_order_itemmeta_product_id.meta_key 	= '_product_id'
	AND it_woocommerce_order_itemmeta_product_qty.meta_key = '_qty'
	AND it_woocommerce_order_itemmeta_product_line_total.meta_key 	= '_line_total'
	AND it_term_taxonomy_product_id.taxonomy = 'product_cat'
	AND it_posts.post_type = 'shop_order'";				
	
	if(count($it_shop_order_status)>0){
		$it_in_shop_os		= implode("', '",$it_shop_order_status);
		$it_shop_order_status_condition = " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
	}
	
	if ($it_from_date != NULL &&  $it_to_date !=NULL){
		$it_from_date_condition = " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
	}
	
	if(count($it_hide_os)>0){
		$in_it_hide_os		= implode("', '",$it_hide_os);
		$it_hide_os_condition = " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
	}
	
	$sql_group_by = " GROUP BY category_id";
	$sql_order_by = " Order By total_amount DESC";
	$sql_limit = " LIMIT {$per_page}";
	
	$sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition
			$it_shop_order_status_condition $it_from_date_condition $it_hide_os_condition 
			$sql_group_by $sql_order_by $sql_limit";
	
	//echo $sql;
	
}elseif($file_used=="data_table"){
	
	foreach($this->results as $items){		    $index_cols=0;
	//for($i=1; $i<=20 ; $i++){
						
		$datatable_value.=("<tr>");
								
			//Status
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $items->category_name;
			$datatable_value.=("</td>");
			
			//Target Sales
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $items->quantity;
			$datatable_value.=("</td>");
			
			//Actual Sales
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price($items->total_amount);
			$datatable_value.=("</td>");
			
		$datatable_value.=("</tr>");
	}
}elseif($file_used=="search_form"){}

?>