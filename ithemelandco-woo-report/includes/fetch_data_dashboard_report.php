<?php
	if($file_used=="sql_table")
	{
	}
	elseif($file_used=="data_table"){


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

		}

		$date_format		= get_option( 'date_format' );
		$it_total_shop_day 		= $this->it_get_dashboard_tsd();
		$datetime= date_i18n("Y-m-d H:i:s");

		//echo $it_total_shop_day;
		$it_hide_os=explode(',',$it_hide_os);
		if(strlen($it_shop_order_status)>0 and $it_shop_order_status != "-1")
			$it_shop_order_status = explode(",",$it_shop_order_status);
		else $it_shop_order_status = array();

		//die($it_shop_order_status.' - '.$it_hide_os.' - '.$it_from_date.' - '.$it_to_date);

		$total_part_refund_amt	= $this->dashboard_it_get_por_amount('total',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
//echo $total_part_refund_amt;
		$_total_orders 			= $this->it_get_dashboard_totals_orders('total',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);

		$total_orders 			= $this->it_get_dashboard_value($_total_orders,'total_count',0);
		$total_sales 			= $this->it_get_dashboard_value($_total_orders,'total_amount',0);

		$total_sales			= $total_sales - $total_part_refund_amt;

		////ADDED IN VER4.0
        /// COST OF GOOD
		$_total_cog 			= $this->it_get_dashboard_totals_cogs('cog',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$total_cog 			= $this->it_get_dashboard_value($_total_cog,'cog',0);


		//type, color,icon, title, amount, count, progress_amount_1, progress_amount_1
		//

		$total_sales_avg		= $this->it_get_dashboard_avarages($total_sales,$total_orders);
		$total_sales_avg_per_day= $this->it_get_dashboard_avarages($total_sales,$it_total_shop_day);

		$_todays_orders 		= $this->it_get_dashboard_totals_orders('today',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$total_today_order 		= $this->it_get_dashboard_value($_todays_orders,'total_count',0);
		$total_today_sales 		= $this->it_get_dashboard_value($_todays_orders,'total_amount',0);

		$total_today_avg		= $this->it_get_dashboard_avarages($total_today_sales,$total_today_order);

		$total_categories  		= $this->it_get_dashboard_tcc();
		$total_products  		= $this->it_get_dashboard_tpc();
		$total_orders_shipping	= $this->it_get_dashboard_toss('total',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);


		$total_refund 			= $this->it_get_dashboard_tbs("total","refunded",$it_hide_os,$it_from_date,$it_to_date);
		$today_refund 			= $this->it_get_dashboard_tbs("today","refunded",$it_hide_os,$it_from_date,$it_to_date);
//echo $today_refund;
		$today_part_refund_amt	= $this->dashboard_it_get_por_amount('today',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);

		$total_refund_amount 	= $this->it_get_dashboard_value($total_refund,'total_amount',0);
		$total_refund_count 	= $this->it_get_dashboard_value($total_refund,'total_count',0);

		$total_refund_amount	= $total_refund_amount + $total_part_refund_amt;

		$todays_refund_amount 	= $this->it_get_dashboard_value($today_refund,'total_amount',0);
		$todays_refund_count 	= $this->it_get_dashboard_value($today_refund,'total_count',0);

		$todays_refund_amount	= $todays_refund_amount + $today_part_refund_amt;

		$today_coupon 			= $this->it_get_dashboard_totals_coupons("today",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$total_coupon 			= $this->it_get_dashboard_totals_coupons("total",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);

		$today_coupon_amount 	= $this->it_get_dashboard_value($today_coupon,'total_amount',0);
		$today_coupon_count 	= $this->it_get_dashboard_value($today_coupon,'total_count',0);

		$total_coupon_amount 	= $this->it_get_dashboard_value($total_coupon,'total_amount',0);
		$total_coupon_count 	= $this->it_get_dashboard_value($total_coupon,'total_count',0);

		$today_order_tax 		= $this->it_get_dashborad_totals_orders("today","_order_tax","tax",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$total_order_tax 		= $this->it_get_dashborad_totals_orders("total","_order_tax","tax",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);

		$today_ord_tax_amount	= $this->it_get_dashboard_value($today_order_tax,'total_amount',0);
		$today_ord_tax_count 	= $this->it_get_dashboard_value($today_order_tax,'total_count',0);

		$total_ord_tax_amount	= $this->it_get_dashboard_value($total_order_tax,'total_amount',0);
		$total_ord_tax_count 	= $this->it_get_dashboard_value($total_order_tax,'total_count',0);

		$today_ord_shipping_tax	= $this->it_get_dashborad_totals_orders("today","_order_shipping_tax","tax",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$total_ord_shipping_tax	= $this->it_get_dashborad_totals_orders("total","_order_shipping_tax","tax",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);

		//echo $it_shop_order_status.' - '.$it_hide_os.' - '.$it_from_date.' - '.$it_to_date;

		$today_ordshp_tax_amount= $this->it_get_dashboard_value($today_ord_shipping_tax,'total_amount',0);
		$today_ordshp_tax_count = $this->it_get_dashboard_value($today_ord_shipping_tax,'total_count',0);

		$total_ordshp_tax_amount= $this->it_get_dashboard_value($total_ord_shipping_tax,'total_amount',0);
		$total_ordshp_tax_count = $this->it_get_dashboard_value($total_ord_shipping_tax,'total_count',0);

		$ytday_order_tax		= $this->it_get_dashborad_totals_orders("yesterday","_order_tax","tax",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$ytday_ord_shipping_tax	= $this->it_get_dashborad_totals_orders("yesterday","_order_shipping_tax","tax",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);

		$ytday_tax_amount		= $this->it_get_dashboard_value($ytday_order_tax,'total_amount',0);
		$ytday_ordshp_tax_amount= $this->it_get_dashboard_value($ytday_ord_shipping_tax,'total_amount',0);
		$ytday_total_tax_amount = $ytday_tax_amount + $ytday_ordshp_tax_amount;

		$today_tax_amount		= $today_ordshp_tax_amount + $today_ord_tax_amount;
		$today_tax_count 		= '';

		$total_tax_amount		= $total_ordshp_tax_amount + $total_ord_tax_amount;
		$total_tax_count 		= '';

		$last_order_details 	= $this->it_get_dashboard_lod($it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);

		$last_order_date 		= $this->it_get_dashboard_value($last_order_details,'last_order_date','');
		$last_order_time		= strtotime($last_order_date);

		$short_date_format		= str_replace("F","M",$date_format);//Modified 20150209

		$current_time 			= strtotime($datetime);
		$last_order_time_diff	= $this->it_get_dashboard_time($last_order_time, $current_time ,' ago');

		$users_of_blog 			= count_users();
		$total_customer 		= isset($users_of_blog['avail_roles']['customer']) ? $users_of_blog['avail_roles']['customer'] : 0;

		$total_reg_customer 	= $this->it_get_dashboard_ttoc('total',false);
		$total_guest_customer 	= $this->it_get_dashboard_ttoc('total',true);

		$today_reg_customer 	= $this->it_get_dashboard_ttoc('today',false);
		$today_guest_customer 	= $this->it_get_dashboard_ttoc('today',true);

		$yesterday_reg_customer	= $this->it_get_dashboard_ttoc('yesterday',false);
		$yesterday_guest_customer= $this->it_get_dashboard_ttoc('yesterday',true);

		$yesterday_orders 			= $this->it_get_dashboard_totals_orders('yesterday',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$total_yesterday_order 		= $this->it_get_dashboard_value($yesterday_orders,'total_count',0);
		$total_yesterday_sales 		= $this->it_get_dashboard_value($yesterday_orders,'total_amount',0);

		$total_yesterday_avg		= $this->it_get_dashboard_avarages($total_yesterday_sales,$total_yesterday_order);

		$yesterday_part_refund_amt	= $this->dashboard_it_get_por_amount('yesterday',$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$yesterday_refund 			= $this->it_get_dashboard_tbs("yesterday","refunded",$it_hide_os,$it_from_date,$it_to_date);


		$yesterday_refund_amount 	= $this->it_get_dashboard_value($yesterday_refund,'total_amount',0);
		$yesterday_refund_amount 	= $yesterday_refund_amount + $yesterday_part_refund_amt;

		$yesterday_coupon 			= $this->it_get_dashboard_totals_coupons("yesterday",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$yesterday_coupon_amount 	= $this->it_get_dashboard_value($yesterday_coupon,'total_amount',0);

		$yesterday_tax 				= $this->it_get_dashborad_totals_orders("yesterday","_order_tax","tax",$it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date);
		$yesterday_tax_amount 		= $this->it_get_dashboard_value($yesterday_tax,'total_amount',0);

		$days_in_this_month 		= gmdate('t', mktime(0, 0, 0, gmdate('m', $current_time), 1, gmdate('Y', $current_time)));



		$it_cur_projected_sales_year=substr($it_from_date,0,4);
		//$it_cur_projected_sales_year	= $this->it_get_dashboard_numbers('cur_projected_sales_year',gmdate('Y',$current_time));
		$projected_it_from_date		= $it_cur_projected_sales_year."-01-01";
		$projected_it_to_date			= $it_cur_projected_sales_year."-12-31";

		$projected_total_orders		= $this->it_get_dashboard_totals_orders('total',$it_shop_order_status,$it_hide_os,$projected_it_from_date,$projected_it_to_date);
		$projected_order_amount 	= $this->it_get_dashboard_value($projected_total_orders,'total_amount',0);
		$projected_order_count 		= $this->it_get_dashboard_value($projected_total_orders,'total_count',0);

		$total_projected_amount		= $this->it_get_dashboard_numbers('total_projected_amount',0);

		//////////////////////////////////
		$months = array("January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December");
  		$projected_amounts 			=array();
		foreach($months as $month){
			$key= $month;
			$value=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'monthes_'.$it_cur_projected_sales_year.'_'.$month);
			$total_projected_amount+=$value;
		}
		/////////////////////////////////

		$projected_percentage		= $this->it_get_number_percentage($projected_order_amount,$total_projected_amount);

		$projected_it_from_date_cm	= gmdate($it_cur_projected_sales_year.'-m-01',$current_time);
		$projected_it_to_date_cm		= gmdate($it_cur_projected_sales_year.'-m-t',$current_time);
		$projected_sales_month		= gmdate('F',$current_time);
		$projected_sales_month_shrt	= gmdate('M',$current_time);

		$projected_total_orders_cm	= $this->it_get_dashboard_totals_orders('total',$it_shop_order_status,$it_hide_os,$projected_it_from_date_cm,$projected_it_to_date_cm);
		$projected_order_amount_cm 	= $this->it_get_dashboard_value($projected_total_orders_cm,'total_amount',0);
		$projected_order_count_cm 	= $this->it_get_dashboard_value($projected_total_orders_cm,'total_count',0);


		$months = array("January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December");
  		$projected_amounts 			=array();
		foreach($months as $month){
			$key= $month;
			$value=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'monthes_'.$it_cur_projected_sales_year.'_'.$month);
			if($value=='')
				$value=$month;
			$projected_amounts[$key]=$value;
		}


		$total_projected_amount_cm	= isset($projected_amounts[$projected_sales_month]) && is_numeric($projected_amounts[$projected_sales_month]) ? $projected_amounts[$projected_sales_month] : 0;


		$projected_percentage_cm	= $this->it_get_number_percentage($projected_order_amount_cm,$total_projected_amount_cm);

		$this_month_date			= gmdate('d',$current_time);

		$per_day_sales_amount		= $this->it_get_dashboard_avarages($projected_order_amount_cm,$this_month_date);

		$per_day_sales_amount		= round(($per_day_sales_amount),2);
		$sales_forcasted 			= $per_day_sales_amount * $days_in_this_month;

		$current_total_sales_apd	= $this->it_get_dashboard_avarages($projected_order_amount_cm,$this_month_date);


		//echo '<div class="clearboth"></div><div class="awr-box-title awr-box-title-nomargin">'.esc_html__('Total Summary','it_report_wcreport_textdomain').'</div><div class="clearboth"></div>';

		$type='simple';
		$total_summary ='';

		$total_summary .= $this->it_get_dashboard_boxes_generator($type, 'red-1', 'fa-usd', esc_html__('Total Sales','it_report_wcreport_textdomain'), $total_sales, 'price', $total_orders, 'number');

		$total_summary .= $this->it_get_dashboard_boxes_generator($type, 'blue-1', 'fa-reply-all', esc_html__('Total Refund','it_report_wcreport_textdomain'), $total_refund_amount, 'price', $total_refund_count, 'number');

		$total_summary .= $this->it_get_dashboard_boxes_generator($type, 'blue-2', 'fa-percent', esc_html__('Total Tax','it_report_wcreport_textdomain'), $total_tax_amount, 'price', $total_tax_count, 'precent');

		$total_summary .= $this->it_get_dashboard_boxes_generator($type, 'brown-1', 'fa-ticket', esc_html__('Total Coupons','it_report_wcreport_textdomain'), $total_coupon_amount, 'price', $total_coupon_count, 'number');

		$total_summary .= $this->it_get_dashboard_boxes_generator($type, 'red-2', 'fa-user-plus', esc_html__('Total Registered','it_report_wcreport_textdomain'), "#".$total_customer, 'other', '', 'number');

		$total_summary .= $this->it_get_dashboard_boxes_generator($type, 'green-1', 'fa-user-o', esc_html__('Total Guest Customers','it_report_wcreport_textdomain'), "#".$total_guest_customer, 'other', '', 'number');

		////ADDE IN VER4.0
		/// COST OF GOOD
		if(__IT_COG__!='') {
		    $total_summary .= $this->it_get_dashboard_boxes_generator($type, 'orange-2', 'fa-money', esc_html__('Cost of Good','it_report_wcreport_textdomain'), $total_cog, 'price', '', 'number');

			$total_summary .= $this->it_get_dashboard_boxes_generator($type, 'brown-2', 'fa-diamond', esc_html__('Total Profit','it_report_wcreport_textdomain'), ($total_sales-$total_cog), 'price', '', 'number');
		}



		//echo '<div class="clearboth"></div><div class="awr-box-title">'.esc_html__('Other Summary','it_report_wcreport_textdomain').'</div><div class="clearboth"></div>';

		$other_summary='';

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'orange-2', 'fa-area-chart', esc_html__('Cur. Yr Proj. Sales','it_report_wcreport_textdomain').'('.$it_cur_projected_sales_year.')', $total_projected_amount, 'price', $projected_percentage, 'precent');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'blue-2', 'piechart', esc_html__('Current Year Sales','it_report_wcreport_textdomain').'('.$it_cur_projected_sales_year.')', $projected_order_amount, 'price', $projected_order_count, 'number');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'green-1', 'fa-line-chart', esc_html__('Average Sales Per Order','it_report_wcreport_textdomain'), $total_sales_avg, 'price', $total_orders, 'number');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'pink-2', 'filter', esc_html__('Average Sales Per Day','it_report_wcreport_textdomain'), $total_sales_avg_per_day, 'price', $total_orders, 'number');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'pink-2', 'like', esc_html__('Current Month Sales','it_report_wcreport_textdomain').'('.$projected_sales_month_shrt.' '.$it_cur_projected_sales_year.')', $projected_order_amount_cm, 'price', $projected_percentage_cm, 'number');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'blue-2', 'fa-bar-chart', esc_html__('Cur. Month Proj. Sales','it_report_wcreport_textdomain').'('.$projected_sales_month_shrt.' '.$it_cur_projected_sales_year.')', $total_projected_amount_cm, 'price', $projected_order_count_cm, 'number');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'blue-2', 'fa-pie-chart', '('.$projected_sales_month_shrt.' '.$it_cur_projected_sales_year.')'. esc_html__('Average Sales/Day','it_report_wcreport_textdomain'), $current_total_sales_apd, 'price', '', 'number');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'brown-2', 'basket', '('.$projected_sales_month_shrt.' '.$it_cur_projected_sales_year.')'. esc_html__('Forecasted Sales','it_report_wcreport_textdomain'), $sales_forcasted, 'price', '', 'number');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'orange-1', 'fa-percent', esc_html__('Order Tax','it_report_wcreport_textdomain'), $total_ord_tax_amount, 'price', $total_ord_tax_count, 'precent');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'green-2', 'fa-truck', esc_html__('Order Shipping Tax','it_report_wcreport_textdomain'), $total_ordshp_tax_amount, 'price', $total_ordshp_tax_count, 'precent');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'red-2', 'filter', esc_html__('Order Shipping Total','it_report_wcreport_textdomain'), $total_orders_shipping, 'price', $total_orders, 'number');

		$amount='';
		$count='';
		if ( $last_order_date) $amount= gmdate($short_date_format,$last_order_time); 	  else $amount=esc_html__( '0', 'it_report_wcreport_textdomain');

	   if ( $last_order_time_diff) $count= $last_order_time_diff; else $count=esc_html__( '0', 'it_report_wcreport_textdomain');

		$other_summary .= $this->it_get_dashboard_boxes_generator($type, 'green-2', 'fa-calendar', esc_html__('Last Order Date','it_report_wcreport_textdomain'), $amount, 'other', $count, 'other');


		//echo '<div class="clearboth"></div><div class="awr-box-title">'.esc_html__('Todays Summary','it_report_wcreport_textdomain').'</div><div class="clearboth"></div>';
		$today_summary='';
		$type='progress';

		$progress_html= $this->it_get_dashboard_progress_contents($total_today_sales,$total_yesterday_sales);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'red-1', 'fa-usd', esc_html__('Todays Total Sales','it_report_wcreport_textdomain'), $total_today_sales, 'price', $total_today_order, 'number',$progress_html);

		$progress_html= $this->it_get_dashboard_progress_contents($total_today_avg,$total_yesterday_avg);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'orange-1', 'chart', esc_html__('Todays Average Sales','it_report_wcreport_textdomain'), $total_today_avg, 'price','', 'number',$progress_html);


		$progress_html= $this->it_get_dashboard_progress_contents($todays_refund_amount,$yesterday_refund_amount);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'pink-1', 'fa-reply-all', esc_html__('Todays Total Refund','it_report_wcreport_textdomain'), $todays_refund_amount, 'price', $todays_refund_count, 'number',$progress_html);


		$progress_html= $this->it_get_dashboard_progress_contents($today_coupon_amount,$yesterday_coupon_amount);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'brown-1', 'fa-ticket', esc_html__('Todays Total Coupons','it_report_wcreport_textdomain'), $today_coupon_amount, 'price', $today_coupon_count, 'number',$progress_html);

		$progress_html= $this->it_get_dashboard_progress_contents($today_tax_amount,$ytday_tax_amount);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'blue-2', 'fa-percent', esc_html__('Todays Order Tax','it_report_wcreport_textdomain'), $today_ord_tax_amount, 'price', $today_ord_tax_count, 'number',$progress_html);

		$progress_html= $this->it_get_dashboard_progress_contents($today_tax_amount,$ytday_ordshp_tax_amount);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'red-2', 'fa-truck', esc_html__('Todays Shipping Tax','it_report_wcreport_textdomain'), $today_ordshp_tax_amount, 'price', $today_ordshp_tax_count, 'number',$progress_html);

		$progress_html= $this->it_get_dashboard_progress_contents($today_tax_amount,$ytday_total_tax_amount);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'green-2', 'category', esc_html__('Todays Total Tax','it_report_wcreport_textdomain'), $today_tax_amount, 'price', $today_tax_count, 'number',$progress_html);

		$progress_html= $this->it_get_dashboard_progress_contents($today_reg_customer,$yesterday_reg_customer);

		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'pink-2', 'fa-user-plus', esc_html__('Todays Registered Customers','it_report_wcreport_textdomain'), "#".$today_reg_customer, 'other', '', 'number',$progress_html);


		$progress_html= $this->it_get_dashboard_progress_contents($today_guest_customer,$yesterday_guest_customer);
		$today_summary.= $this->it_get_dashboard_boxes_generator($type, 'orange-2', 'fa-user-o', esc_html__('Todays Guest Customers','it_report_wcreport_textdomain'),  "#".$today_guest_customer, 'other', '', 'number',$progress_html);


		//echo '<div class="clearboth"></div><div class="awr-box-title">'.esc_html__('Other Summary','it_report_wcreport_textdomain').'</div><div class="clearboth"></div>';

	if($this->get_dashboard_capability('summary_boxes')){

		$htmls='
		<div class="tabs tabsB tabs-style-underline">
			<nav>
				<ul class="tab-uls">';

					if($this->get_dashboard_capability('total_summary')){
						$htmls.='
					<li><a href="#section-bar-1" > <span>'.esc_html__('Total Summary','it_report_wcreport_textdomain').'</span></a></li>';
					}

					if($this->get_dashboard_capability('other_summary_box')){
						$htmls.='
					<li><a href="#section-bar-2" > <span>'.esc_html__('Other summary','it_report_wcreport_textdomain').'</span></a></li>';
					}

					if($this->get_dashboard_capability('today_summary')){
						$htmls.='
					<li><a href="#section-bar-3" > <span>'.esc_html__('Today summary','it_report_wcreport_textdomain').'</span></a></li>';
					}
					$htmls.='
				</ul>
			</nav>
			<div class="content-wrap">';

				if($this->get_dashboard_capability('total_summary')){
						$htmls.='
				<section id="section-bar-1">
					'.$total_summary.'
				</section>';
				}

			  	if($this->get_dashboard_capability('other_summary_box')){
						$htmls.='
				<section id="section-bar-2">
					'.$other_summary.'
				</section>';
				}

				if($this->get_dashboard_capability('today_summary')){
						$htmls.='
				<section id="section-bar-3">
					'.$today_summary.'
				</section>';
				}
				$htmls.='
			</div>
		</div>
		';

		echo wp_kses(
			$htmls,
			$this->allowedposttags()
		);
			// echo $htmls;
		}

	}
	elseif($file_used=="search_form"){
		$it_from_date=$this->it_from_date_dashboard;
		$it_to_date=$this->it_to_date_dashboard;

		if(isset($_POST['it_from_date']))
		{
			$this->search_form_fields=$_POST;
			$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
			$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		}

	?>
		<form class='alldetails search_form_reports' action='' method='post' id="dashboard_form">
            <input type='hidden' name='action' value='submit-form' />
            <input type='hidden' name="it_from_date" id="pwr_from_date_dashboard" value="<?php echo esc_html($it_from_date);?>"/>
            <input type='hidden' name="it_to_date" id="pwr_to_date_dashboard"  value="<?php echo esc_html($it_to_date);?>"/>

			<div class="page-toolbar">

				<button type="submit" value="Search" class="button-primary"><i class="fa fa-search"></i> <span><?php echo esc_html__('Search','it_report_wcreport_textdomain'); ?></span></button>
				<div id="dashboard-report-range" class="pull-right tooltips  btn-fit-height grey-salt" data-placement="top" data-original-title="Change dashboard date range">
					<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
						<i class="fa fa-calendar"></i>&nbsp;
						<span></span> <b class="caret"></b>
					</div>
				</div>

				<?php
					$it_hide_os=$this->otder_status_hide;
					$it_publish_order='no';

					$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
				?>
				<input type="hidden" name="list_parent_category" value="">
				<input type="hidden" name="group_by_parent_cat" value="0">

				<input type="hidden" name="it_hide_os" id="it_hide_os" value="<?php echo esc_html($it_hide_os);?>" />
				<input type="hidden" name="publish_order" id="publish_order" value="<?php echo esc_html($it_publish_order);?>" />

				<input type="hidden" name="date_format" id="date_format" value="<?php echo esc_html($data_format);?>" />

				<input type="hidden" name="table_names" value="<?php echo esc_html($table_name);?>"/>
				<div class="fetch_form_loading dashbord-loading"></div>


			</div>






        </form>
    <?php
	}

?>
