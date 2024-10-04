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
	
	
	//$it_hide_os=$this->otder_status_hide;
	//$it_hide_os	= $this->it_get_woo_requests('it_hide_os',$it_hide_os,true);
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
	
	$per_page=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_state_post_per_page',5);
	
	$it_shop_order_status_condition ='';
	$it_from_date_condition ='';
	$it_hide_os_condition ='';

	$sql_columns = " SUM(it_postmeta1.meta_value) AS 'Total' 
	,it_postmeta2.meta_value AS 'billing_state'
	,it_postmeta3.meta_value AS 'billing_country'
	,Count(*) AS 'OrderCount'";
	
	$sql_joins = " {$wpdb->prefix}posts as it_posts
	LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID
	LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID
	LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_posts.ID";
	
	$sql_condition = " it_posts.post_type 	=	'shop_order'  
	AND it_postmeta1.meta_key	=	'_order_total' 
	AND it_postmeta2.meta_key	=	'_billing_state'
	AND it_postmeta3.meta_key	=	'_billing_country'";
	
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
	
	$sql_group_by = " GROUP BY  it_postmeta2.meta_value";
	$sql_order_by = " Order By Total DESC";
	$sql_limit =" LIMIT {$per_page}";
	
	$sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition
			$it_shop_order_status_condition $it_from_date_condition $it_hide_os_condition 
			$sql_group_by $sql_order_by $sql_limit";
	
	//echo $sql;
	
}elseif($file_used=="data_table"){
	
	foreach($this->results as $items){		    $index_cols=0;
	//for($i=1; $i<=20 ; $i++){
						
		$datatable_value.=("<tr>");
			
			//STATE	
			$it_table_value=$this->it_get_woo_bsn($items->billing_country,$items->billing_state);				
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $it_table_value;
			$datatable_value.=("</td>");
			
			//Target Sales
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $items->OrderCount;
			$datatable_value.=("</td>");
			
			//Actual Sales
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price($items->Total);
			$datatable_value.=("</td>");
			
		$datatable_value.=("</tr>");
	}
}elseif($file_used=="search_form"){}

?>