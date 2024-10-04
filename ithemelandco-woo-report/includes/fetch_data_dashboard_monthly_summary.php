<?php

if($file_used=="sql_table")
{


}elseif($file_used=="data_table"){

	$it_from_date=$this->it_from_date_dashboard;
	$it_to_date=$this->it_to_date_dashboard;

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
		$it_create_date =  $it_from_date;
	}

	//$it_hide_os=$this->otder_status_hide;
	//$it_hide_os	= $this->it_get_woo_requests('it_hide_os',$it_hide_os,true);
	$it_hide_os=explode(',',$it_hide_os);
	//$it_shop_order_status="wc-completed,wc-on-hold,wc-processing";
	//$it_shop_order_status	= $this->it_get_woo_requests('shop_order_status',$it_shop_order_status,true);
	$it_shop_order_status=explode(',',$it_shop_order_status);
	$it_cur_projected_sales_year=substr($it_from_date,0,4);

	$it_cur_projected_sales_year= $this->it_get_woo_requests('it_proj_sale_year',$it_cur_projected_sales_year,true);

	$it_from_date			= $it_cur_projected_sales_year."-01-01";
	$start_month_time	= strtotime($it_from_date);
	$month_count		= 12;
	$end_month_time		= strtotime("+{$month_count} month", $start_month_time);
	$it_to_date			= gmdate("Y-m-d",$end_month_time);

	$parameters = array('shop_order_status'=>$it_shop_order_status,'it_hide_os'=>$it_hide_os,'it_from_date'=>$it_from_date,'it_to_date'=>$it_to_date);

	$it_shop_order_status	= isset($parameters['shop_order_status']) ? $parameters['shop_order_status']	: array();
	$it_hide_os	= isset($parameters['it_hide_os']) ? $parameters['it_hide_os']	: array();
	$it_from_date			= isset($parameters['it_from_date'])		? $parameters['it_from_date']			: NULL;
	$it_to_date			= isset($parameters['it_to_date'])			? $parameters['it_to_date']				: NULL;


	$it_from_date			= $it_cur_projected_sales_year."-01-01";
	$start_month_time	= strtotime($it_from_date);
	$month_count		= 12;
	$end_month_time		= strtotime("+{$month_count} month", $start_month_time);
	$it_to_date			= gmdate("Y-m-d",$end_month_time);



	$it_null_val				= $this->price(0);

	//$it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date, $month_count = 12, $it_cur_projected_sales_year = 2010

	$it_refunded_id 		= $this->it_get_woo_old_orders_status(array('refunded'), array('wc-refunded'));
	$it_order_total		= $this->it_get_woo_ts_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date,"_order_total");

	//print_r($it_order_total);

	$it_order_discount		= $this->it_get_woo_ts_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date,"_order_discount");
	$it_cart_discount		= $this->it_get_woo_ts_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date,"_cart_discount");
	$it_refund_order_total = $this->it_get_woo_tss_months($it_refunded_id, $it_hide_os, $it_from_date, $it_to_date, $month_count,"_order_total");

	$order_shipping_tax	= $this->it_get_woo_ts_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date,"_order_shipping_tax");
	$order_tax			= $this->it_get_woo_ts_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date,"_order_tax");

	$part_refund		= $this->it_get_woo_pora_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date, $month_count);
	//,"_cart_discount"
	//$this->print_array($it_order_discount);
	//$this->print_array($it_cart_discount);

	$start_month		= $it_from_date;
	$start_month_time	= strtotime($start_month);
	$month_list			= array();
	$it_fetchs_data			= array();
	$total_projected	= 0;
	$i         			= 0;
	$m         			= 0;

	for ($m=0; $m < ($month_count); $m++){
		$c					= 	strtotime("+$m month", $start_month_time);
		$key				= 	gmdate('F-Y', $c);
		$value				= 	gmdate('F', 	$c);
		$month_list[$key]	=	$value;
	}

	//print_r($month_list);

	$months_translate = array("January"=>"jan", "February"=>"feb", "March"=>"mar", "April"=>"apr", "May"=>"may", "June"=>"jun",
	                          "July"=>"jul", "August"=>"aug", "September"=>"sep", "October"=>"oct", "November"=>"nov", "December"=>"dec");

	$months = array("January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December");
	$projected_amounts 			=array();
	foreach($months as $month){
		$key= $month;
		$value=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'monthes_'.$it_cur_projected_sales_year.'_'.$month);
		if($value=='')
			$value=$month;

		//$number = str_replace(',','.',$value);
		$projected_amounts[$key]=$value;
	}


	foreach ($month_list as $key => $value) {

//		$projected_sales_month					=	$value;
//		$projected_sales_month_amount			=	isset($projected_amounts[$projected_sales_month]) ? $projected_amounts[$projected_sales_month] : 100;
//		$it_fetchs_data[$i]["projected"] 			=	$projected_sales_month_amount;

		$projected_sales_month					=	$value;
		$projected_sales_month_amount			=	isset($projected_amounts[$projected_sales_month]) ? $projected_amounts[$projected_sales_month] : 100;
		$number = str_replace(',','.',$projected_sales_month_amount);
		//$it_fetchs_data[$i]["projected"] 			=	is_numeric($projected_sales_month_amount) ? $projected_sales_month_amount : 0;

		$it_fetchs_data[$i]["projected"] 			=	$projected_sales_month_amount;

		////////TRANSLATE MONTHS////////
		$m_name=explode("-",$key);
		$translate_month=$months_translate[$m_name[0]];
		$keys				= 	$this->it_translate_function(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.$translate_month.'_translate',$m_name[0]).'-'.$m_name[1];
		////////////////////

		$it_fetchs_data[$i]["month_name"] 			=	$keys;


		$it_fetchs_data[$i]["monthname"] 			=	$value;
		$it_fetchs_data[$i]["part_order_refund_amount"] 	=	isset($part_refund[$key]) 		? $part_refund[$key] 			: 0;

		$this_order_total 						=	isset($it_order_total[$key]) 			? $it_order_total[$key] 			: 0;
		$this_order_total						=	strlen($this_order_total)>0			? $this_order_total 			: 0;
		$this_order_total						=	$this_order_total - $it_fetchs_data[$i]["part_order_refund_amount"];
		$it_fetchs_data[$i]["order_total"] 			=	$this_order_total;

		$it_fetchs_data[$i]["refund_order_total"]	=	isset($it_refund_order_total[$key]) 	? $it_refund_order_total[$key]		: 0;

		$it_fetchs_data[$i]["actual_min_porjected"]	=	floatval($it_fetchs_data[$i]["order_total"]) - floatval($projected_sales_month_amount);
		$it_fetchs_data[$i]["order_discount"] 		=	isset($it_order_discount[$key]) 		? $it_order_discount[$key] 		: 0;
		$it_fetchs_data[$i]["cart_discount"] 		=	isset($it_cart_discount[$key]) 		? $it_cart_discount[$key] 		: 0;
		$it_fetchs_data[$i]["total_discount"] 		=	$it_fetchs_data[$i]["order_discount"] + $it_fetchs_data[$i]["cart_discount"];


		$total_projected						=	floatval($total_projected) + floatval($it_fetchs_data[$i]["projected"]);
		$i++;
	}

	foreach ($it_fetchs_data as $key => $value) {
		$it_order_total							=	isset($value["order_total"]) 	? trim($value["order_total"])	: 0;
		$it_order_total							=	strlen(($it_order_total)) > 0 		? $it_order_total	: 0;

		$it_fetchs_data[$key]["totalsalse"]			=	$this->it_get_number_percentage($it_order_total,$total_projected);
		$it_fetchs_data[$key]["actual_porjected_per"]=	$this->it_get_number_percentage($it_order_total,$value["projected"]);
	}

	$it_order_total 				=	$this->it_get_woo_total($it_fetchs_data,'order_total');
	$actual_min_porjected 		=	$this->it_get_woo_total($it_fetchs_data,'actual_min_porjected');
	$it_order_discount 			=	$this->it_get_woo_total($it_fetchs_data,'order_discount');
	$it_cart_discount 				=	$this->it_get_woo_total($it_fetchs_data,'cart_discount');
	$total_discount 			=	$this->it_get_woo_total($it_fetchs_data,'total_discount');

	$it_refund_order_total 		=	$this->it_get_woo_total($it_fetchs_data,'refund_order_total');
	$part_order_refund_amount	=	$this->it_get_woo_total($it_fetchs_data,'part_order_refund_amount');

	$it_order_total				= trim($it_order_total);
	$it_order_total				= strlen($it_order_total) > 0 ? $it_order_total : 0;

	$it_fetchs_data[$i]["month_name"] 				=	"Total";
	$it_fetchs_data[$i]["order_total"] 				=	$it_order_total;
	$it_fetchs_data[$i]["projected"] 				=	$total_projected;
	$it_fetchs_data[$i]["totalsalse"] 				=	$this->it_get_number_percentage($it_order_total,$total_projected);
	$it_fetchs_data[$i]["refund_order_total"] 		=	$it_refund_order_total;
	$it_fetchs_data[$i]["order_discount"] 			=	$it_order_discount;
	$it_fetchs_data[$i]["cart_discount"] 			=	$it_cart_discount;
	$it_fetchs_data[$i]["total_discount"] 			=	$total_discount;

	$it_fetchs_data[$i]["actual_min_porjected"]		=	$actual_min_porjected;
	$it_fetchs_data[$i]["actual_porjected_per"]		=	$this->it_get_number_percentage($it_order_total,$total_projected);

	$it_fetchs_data[$i]["part_order_refund_amount"]	=	$part_order_refund_amount;

	//print_r($it_fetchs_data);

	//return $it_fetchs_data;




	foreach($it_fetchs_data as $items){
		$index_cols=0;
		//for($i=1; $i<=20 ; $i++){
		if($items['month_name']=='Total') continue;

		$datatable_value.=("<tr>");

		//Month
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $items['month_name'];
		$datatable_value.=("</td>");

		//Target Sales
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->price($items['projected']);
		$datatable_value.=("</td>");

		//Actual Sales
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->price($items['order_total']);
		$datatable_value.=("</td>");

		//Difference
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		//$datatable_value.= $this->price($items['actual_min_porjected']);
		$datatable_value.= $this->price(floatval($items['order_total'])-floatval($items['projected']));
		$datatable_value.=("</td>");

		//%
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= round($items['actual_porjected_per'],2)."%";
		$datatable_value.=("</td>");

		//Total % To Sale
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= round($items['totalsalse'],2)."%";
		$datatable_value.=("</td>");

		//Refund Amt.
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->price($items['refund_order_total']);
		$datatable_value.=("</td>");


		//Part Refund Amount
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->price($items['part_order_refund_amount']);
		$datatable_value.=("</td>");

		//Total Discount Amt.
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->price($items['total_discount']);
		$datatable_value.=("</td>");

//		//Tax Amt.
//		$display_class='';
//		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
//		$datatable_value.=("<td style='".$display_class."'>");
//		$datatable_value.= $this->price($items['order_tax']);
//		$datatable_value.=("</td>");
//
//		//Shipping Order Tax
//		$display_class='';
//		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
//		$datatable_value.=("<td style='".$display_class."'>");
//		$datatable_value.= $this->price($items['order_shipping_tax']);
//		$datatable_value.=("</td>");
//
//		//Total Shipping Tax
//		$display_class='';
//		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
//		$datatable_value.=("<td style='".$display_class."'>");
//		$datatable_value.= $this->price($items['total_shipping_tax']);
//		$datatable_value.=("</td>");

		$datatable_value.=("</tr>");
	}
}elseif($file_used=="search_form"){

	//SET YEARS
	global $wpdb;

	$order_date='';
	$results= $wpdb->get_results($wpdb->prepare("SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN (%s) GROUP BY it_posts.ID Order By it_posts.post_date ASC LIMIT 1",'trash'));

	$first_date='';
	if(isset($results[0]))
		$first_date=$results[0]->order_date;

	if($first_date==''){
		$first_date= gmdate("Y-m-d");
		$first_date=substr($first_date,0,4);
	}else{
		$first_date=substr($first_date,0,4);
	}

	$order_date='';
	$results= $wpdb->get_results($wpdb->prepare("SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN (%s) GROUP BY it_posts.ID Order By it_posts.post_date DESC LIMIT 1",'trash'));

	$it_to_date='';
	if(isset($results[0]))
		$it_to_date=$results[0]->order_date;

	if($it_to_date==''){
		$it_to_date= gmdate("Y-m-d");
		$it_to_date=substr($it_to_date,0,4);
	}else{
		$it_to_date=substr($it_to_date,0,4);
	}

	$cur_year=gmdate("Y-m-d");
	$cur_year=substr($cur_year,0,4);

	$option="";
	for($year=($first_date-5);$year<($it_to_date+5);$year++)
	{
		$option.='<option value="'.$year.'" '.selected($cur_year,$year,0).'>'.$year.'</option>';
	}

	?>
	<form class='alldetails search_form_report' action='' method='post'>
		<input type='hidden' name='action' value='submit-form' />
		<div class="row">

			<div class="col-md-6">
				<div class="awr-form-title">
					<?php esc_html_e('Select Year','it_report_wcreport_textdomain');?>
				</div>
				<span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
				<select name="it_proj_sale_year" id="it_proj_sale_year" class="it_proj_sale_year">
					<?php echo wp_kses(
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
);?>
				</select>
			</div>

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

			<input type="hidden" name="shop_order_status" id="shop_order_status" value="<?php echo esc_attr($this->it_shop_status); ?>">

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
