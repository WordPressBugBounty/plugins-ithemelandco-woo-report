<?php
if($file_used=="sql_table")
{

	//show_seleted_order_status
	global $wpdb;

	$it_create_date =  gmdate("Y-m-d");
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

	$per_page=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'recent_post_per_page',5);

	$it_shop_order_status_condition='';
	$it_hide_os_condition ='';

	$sql_columns = " it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status";
	$sql_joins = "{$wpdb->prefix}posts as it_posts";

	$sql_condition= " it_posts.post_type='shop_order' ";

	if ($it_from_date != NULL &&  $it_to_date !=NULL){
		$sql_condition.= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
	}

	if(count($it_shop_order_status)>0){
		$it_in_shop_os		= implode("', '",$it_shop_order_status);
		$it_shop_order_status_condition = " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
	}

	if(count($it_hide_os)>0){
		$in_it_hide_os		= implode("', '",$it_hide_os);
		$it_hide_os_condition = " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
	}


	$sql_group_by= " GROUP BY it_posts.ID";

	$sql_order_by= " Order By it_posts.post_date DESC ";
	$sql_limit = " LIMIT {$per_page}";

	$sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition
			$it_shop_order_status_condition $it_hide_os_condition
			$sql_group_by $sql_order_by $sql_limit";

	//echo $sql;

}elseif($file_used=="data_table"){

	foreach($this->results as $items){
		$index_cols=0;
		//for($i=1; $i<=20 ; $i++){

		$order_id= $items->order_id;
		$fetch_other_data='';

		if(!isset($this->order_meta[$order_id])){
			$fetch_other_data= $this->it_get_full_post_meta($order_id);
		}

		//print_r($fetch_other_data);

		$total_amount=0;
		$datatable_value.=("<tr>");

			$it_order_total = isset($fetch_other_data['order_total'])		? $fetch_other_data['order_total'] 		: 0;

			$order_shipping= isset($fetch_other_data['order_shipping'])	? $fetch_other_data['order_shipping']	: 0;

			$it_cart_discount= isset($fetch_other_data['cart_discount'])		? $fetch_other_data['cart_discount'] 	: 0;

			$it_order_discount= isset($fetch_other_data['order_discount'])	? $fetch_other_data['order_discount'] 	: 0;

			$total_discount = isset($fetch_other_data['total_discount'])	? $fetch_other_data['total_discount'] 	: ($it_cart_discount + $it_order_discount);


			$total_amount+=$it_order_total;
			//order ID
			$order_id = $items->order_id;

			//CUSTOM WORK - 15862
			if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
				$order_id = get_post_meta( $order_id, '_order_number_formatted', true );
			}

			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $order_id;
			$datatable_value.=("</td>");

			//Name
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= (isset($fetch_other_data['billing_first_name']) ? $fetch_other_data['billing_first_name'] : "").' '.(isset($fetch_other_data['billing_last_name']) ? $fetch_other_data['billing_last_name']:"");
			$datatable_value.=("</td>");

			//Email
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= isset($fetch_other_data['billing_email']) ? $fetch_other_data['billing_email'] : "";
			$datatable_value.=("</td>");

			//Date
			$date_format		= get_option( 'date_format' );
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= gmdate($date_format,strtotime($items->order_date));
			$datatable_value.=("</td>");

			//Status
			$it_table_value=$items->order_status;
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

			//Items
			$display_class='';
			$order_items_cnt=$this->it_get_oi_count($items->order_id,'line_item');
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.=isset($order_items_cnt[$items->order_id]) ? $order_items_cnt[$items->order_id]:0;
			$datatable_value.=("</td>");

			//Payment Method
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
			$datatable_value.=isset($fetch_other_data['payment_method_title']) ? $fetch_other_data['payment_method_title'] : "";
			$datatable_value.=("</td>");

			//Shipping Method
			$shipping_method=$this->it_oin_list($items->order_id,'shipping');
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
			$datatable_value.=isset($shipping_method[$items->order_id]) ? $shipping_method[$items->order_id] : "";
			$datatable_value.=("</td>");

			//Order Currency
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
			$datatable_value.=isset($fetch_other_data['order_currency']) ? $fetch_other_data['order_currency'] : "";
			$datatable_value.=("</td>");

			//Gross Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price(($it_order_total + $total_discount) - ($fetch_other_data['order_shipping'] +  $fetch_other_data['order_shipping_tax'] + $fetch_other_data['order_tax'] ),array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Order Discount Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price($it_order_discount,array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Cart Discount Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price(isset($fetch_other_data['cart_discount'])		? $fetch_other_data['cart_discount'] 	: 0 ,array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Total Discount Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price($total_discount ,array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Shipping Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price($fetch_other_data['order_shipping'] ,array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Shipping Tax Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price(isset($fetch_other_data['order_shipping_tax'])? $fetch_other_data['order_shipping_tax'] : 0 ,array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Order Tax Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price(isset($fetch_other_data['order_tax'])? $fetch_other_data['order_tax'] : 0 ,array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Total Tax Amt.
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price(isset($fetch_other_data['total_tax'])? $fetch_other_data['total_tax'] 	: ($fetch_other_data['order_tax'] + $fetch_other_data['order_shipping_tax']),array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

			//Part Refund Amt.
			$display_class='';
			$order_refund_amnt=$this->it_get_por_amount($items->order_id);
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= (isset($order_refund_amnt[$items->order_id])? $this->price($order_refund_amnt[$items->order_id],array("currency" => $fetch_other_data['order_currency'])):$this->price(0,array("currency" => $fetch_other_data['order_currency'])));
			$datatable_value.=("</td>");
			$part_refund=(isset($order_refund_amnt[$items->order_id])? $order_refund_amnt[$items->order_id]:0);

			//Net Amt.
			$display_class='';
			$total_amount=$total_amount-$part_refund;
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.= $this->price($total_amount,array("currency" => $fetch_other_data['order_currency']));
			$datatable_value.=("</td>");

		$datatable_value.=("</tr>");
	}
}elseif($file_used=="search_form"){}

?>
