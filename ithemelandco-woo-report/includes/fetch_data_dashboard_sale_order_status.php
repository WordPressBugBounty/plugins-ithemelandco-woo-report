<?php
if($file_used=="sql_table")
{

	//show_seleted_order_status
	global $wpdb;

	$it_from_date=$this->it_from_date_dashboard;
	$it_to_date=$this->it_to_date_dashboard;
	$date_format = $this->it_date_format($it_from_date);

	$it_hide_os='';
	$it_shop_order_status="";

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


	//$it_hide_os='';
	//$it_hide_os	= $this->it_get_woo_requests('it_hide_os',$it_hide_os,true);
	$it_hide_os=explode(',',$it_hide_os);
	//$it_shop_order_status="";
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

	$it_from_date_condition ='';
	$it_hide_os_condition ='';
	$it_shop_order_status_condition ='';

	$sql_columns = "
	COUNT(postmeta.meta_value) AS 'Count'
	,SUM(postmeta.meta_value) AS 'Total'
	,it_posts.post_status As 'Status' ,it_posts.post_status As 'StatusID'";

	$sql_joins = " {$wpdb->prefix}posts as it_posts
	LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id=it_posts.ID";

	$sql_condition = " postmeta.meta_key = '_order_total'  AND it_posts.post_type='shop_order' ";

	if ($it_from_date != NULL &&  $it_to_date !=NULL){
		$it_from_date_condition = " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
	}

	$url_it_hide_os = "";
	if(count($it_hide_os)>0){
		$in_it_hide_os		= implode("', '",$it_hide_os);
		$it_hide_os_condition = " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
	}

	$show_seleted_order_status	= 1;

	if($show_seleted_order_status == 1){
		if(count($it_shop_order_status)>0){
			$it_in_shop_os		= implode("', '",$it_shop_order_status);
			$it_shop_order_status_condition = " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
		}
	}

	$sql_group_by = " Group BY it_posts.post_status";
	$sql_order_by = " ORDER BY Total DESC";

	$sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition
			$it_from_date_condition $it_hide_os_condition $it_shop_order_status_condition
			$sql_group_by $sql_order_by";

	//echo $sql;

}elseif($file_used=="data_table"){

	foreach($this->results as $items){
		$index_cols=0;
	//for($i=1; $i<=20 ; $i++){

		$datatable_value.=("<tr>");

			//Status
			$display_class='';
			$status=ucfirst(str_replace("wc-","",$items->Status));
			$status=esc_html(sanitize_text_field($status),'it_report_wcreport_textdomain');
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.=$status;
			$datatable_value.=("</td>");

			//Target Sales
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $items->Count;
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
