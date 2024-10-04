<?php

	////ADDED IN VER4.0
	/// INTELLIGENCE REPORTS
	add_action('wp_ajax_it_chosen_ajax', 'it_chosen_ajax');
	add_action('wp_ajax_nopriv_it_chosen_ajax', 'it_chosen_ajax');
	function it_chosen_ajax() {
		global $wpdb,$it_rpt_main_class;

		parse_str( $_REQUEST['postdata'], $my_array_of_vars );

		global $wpdb;

		parse_str($_REQUEST['postdata'], $my_array_of_vars);

		$nonce = $_REQUEST['nonce'];

		if(!wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) )
		{
			$arr = array(
				'success'=>'no-nonce',
				'products' => array()
			);
			print_r($arr);
			die();
		}

		//die(print_r($my_array_of_vars));

		global $it_rpt_main_class;
		$data='';
		switch ($my_array_of_vars['target_type']){
			case 'simple_products':
				$data=$it_rpt_main_class->it_get_product_woo_data_chosen('simple',false,$my_array_of_vars['q']);
			break;

			case 'variable_products':
				$data=$it_rpt_main_class->it_get_product_woo_data_chosen('1',false,$my_array_of_vars['q']);
				break;

			case 'all_products':
				$data=$it_rpt_main_class->it_get_product_woo_data_chosen('0',false,$my_array_of_vars['q']);
				break;

			case 'customer';
				$data=$it_rpt_main_class->it_get_woo_customers_orders('shop_order','no',$my_array_of_vars['q']);
			break;
		}




		echo wp_json_encode($data);

		die(0);


	}


	//FETCH REPORT DATAGRID
	add_action('wp_ajax_it_rpt_fetch_data', 'it_rpt_fetch_data');
	add_action('wp_ajax_nopriv_it_rpt_fetch_data', 'it_rpt_fetch_data');
	function it_rpt_fetch_data() {
		global $wpdb;

		parse_str($_REQUEST['postdata'], $my_array_of_vars);

		$nonce = $_POST['nonce'];

		if(!wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) )
		{
			$arr = array(
			  'success'=>'no-nonce',
			  'products' => array()
			);
			print_r($arr);
			die();
		}

		//print_r($my_array_of_vars);

		//echo $sql;

		//$products = $wpdb->get_results($sql);

		global $it_rpt_main_class;

		//$table_name=$my_array_of_vars['table_name'];
		$table_name=$my_array_of_vars['table_names'];

		if($table_name=='int_reports_sales' || $table_name=='int_reports_products' || $table_name=='int_reports_customers' || $table_name=='int_reports_home' || $table_name=='int_reports_transactions')
		{
			$it_rpt_main_class->table_html_intelligence($table_name,$my_array_of_vars);
		}else{
            $it_rpt_main_class->table_html($table_name,$my_array_of_vars);
		}

		die();
	}


	////ADDED IN VER4.0
	/// INTELLIGENCE REPORTS
	add_action('wp_ajax_it_rpt_int_customer_details', 'it_rpt_int_customer_details');
	add_action('wp_ajax_nopriv_it_rpt_int_customer_details', 'it_rpt_int_customer_details');
	function it_rpt_int_customer_details() {
		global $wpdb,$it_rpt_main_class;

		parse_str( $_REQUEST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		global $it_rpt_main_class;
		//print_r( $my_array_of_vars );
		$order_id=$my_array_of_vars['row_id'];
		include("Intelligence/fetch_data_int_reports_orders_details.php");

		die(0);
	}


	add_action('wp_ajax_it_rpt_int_add_note', 'it_rpt_int_add_note');
	add_action('wp_ajax_nopriv_it_rpt_int_add_note', 'it_rpt_int_add_note');
	function it_rpt_int_add_note() {
		global $wpdb,$it_rpt_main_class;

		parse_str( $_REQUEST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		global $it_rpt_main_class;
		$id=$my_array_of_vars['id'];
		$target=$my_array_of_vars['target'];
		$note_text=$my_array_of_vars['note_text'];
		if($target=='order'){
			$order = new WC_Order($id);
			$order->add_order_note($note_text,0,true);
		}elseif ($target=='product'){
			update_post_meta($id,"_purchase_note",$note_text);
		}

		echo esc_html__('New note has been saved.','it_report_wcreport_textdomain');

		die(0);
	}

	add_action('wp_ajax_it_rpt_int_change_order_staus', 'it_rpt_int_change_order_staus');
	add_action('wp_ajax_nopriv_it_rpt_int_change_order_staus', 'it_rpt_int_change_order_staus');
	function it_rpt_int_change_order_staus() {
		global $wpdb, $it_rpt_main_class;

		parse_str( $_REQUEST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		//print_r($my_array_of_vars);

		$order_id=$my_array_of_vars['order_id'];
		$status=$my_array_of_vars['status'];
		$order = new WC_Order($order_id);
		$order->update_status($status, 'order_note');

		die(0);
	}


	add_action('wp_ajax_it_rpt_fetch_single_customer', 'it_rpt_fetch_single_customer');
	add_action('wp_ajax_nopriv_it_rpt_fetch_single_customer', 'it_rpt_fetch_single_customer');
	function it_rpt_fetch_single_customer() {
		global $wpdb, $it_rpt_main_class;

		parse_str( $_REQUEST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		//print_r($my_array_of_vars);
		$page_id=$my_array_of_vars['page_id'];
		$customer_id=$my_array_of_vars['customer_id'];
		$customer_email=$my_array_of_vars['customer_email'];
		$customer_segment=$my_array_of_vars['customer_segment'];
		include("Intelligence/fetch_data_int_reports_customers_single.php");

		die(0);
	}


	add_action('wp_ajax_it_rpt_fetch_single_product', 'it_rpt_fetch_single_product');
	add_action('wp_ajax_nopriv_it_rpt_fetch_single_product', 'it_rpt_fetch_single_product');
	function it_rpt_fetch_single_product() {
		global $wpdb, $it_rpt_main_class;

		parse_str( $_REQUEST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		//print_r($my_array_of_vars);
		$product_id=$my_array_of_vars['product_id'];
		$product_rank_type=$my_array_of_vars['product_rank_type'];
		$product_rank_val=$my_array_of_vars['product_rank_val'];
		$product_rank_title=$my_array_of_vars['product_rank_title'];

		$it_from_date		  = $my_array_of_vars['it_from_date'];
		$it_to_date			= $my_array_of_vars['it_to_date'];
		$total_products_amnt			= $my_array_of_vars['total_products_amnt'];
		$total_products_refund_amnt			= $my_array_of_vars['total_products_refund_amnt'];
//		$order_products			= $my_array_of_vars['orders_products_arr'];
//		$order_products=json_decode(stripslashes($order_products));
//		//print_r($order_products);

		include("Intelligence/fetch_data_int_reports_products_single.php");

		die(0);
	}

	////ADDED IN VER4.0
	/// PDF GENERATOR
	add_action('wp_ajax_it_rpt_add_fav_menu', 'it_rpt_add_fav_menu');
	add_action('wp_ajax_nopriv_it_rpt_add_fav_menu', 'it_rpt_add_fav_menu');
	function it_rpt_add_fav_menu() {
		global $wpdb;

		parse_str( $_REQUEST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		global $it_rpt_main_class;

		$current_user = wp_get_current_user();
		$user_info = $current_user->user_login;

		$smenu = $my_array_of_vars['smenu'];
		$current_fav_menus=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__."fav_menus_".$user_info);
		if(is_array($current_fav_menus) && in_array($smenu,$current_fav_menus)){

			if(($key = array_search($smenu, $current_fav_menus)) !== false) {
				unset($current_fav_menus[$key]);
			}

			update_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__."fav_menus_".$user_info,$current_fav_menus);
		}elseif(is_array($current_fav_menus)){
			array_push($current_fav_menus,$smenu);
			update_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__."fav_menus_".$user_info,$current_fav_menus);
		}else{
			update_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__."fav_menus_".$user_info,array($smenu));
		}
		echo esc_html($smenu);
		die(0);
	}

	function toAscii($str) {
		$clean = preg_replace("/[^a-zA-Z0-9/_|+ -]/", '', $str);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[/_|+ -]+/", '-', $clean);

		return $clean;
	}
	////ADDED IN VER4.0
	/// PDF GENERATOR
	add_action('wp_ajax_it_rpt_pdf_generator', 'it_rpt_pdf_generator');
	add_action('wp_ajax_nopriv_it_rpt_pdf_generator', 'it_rpt_pdf_generator');
	function it_rpt_pdf_generator() {
		
	}



	//FETCH CUSTOM FIELD IN SETTINGS
	function get_operation($fields){
		$operators=array(
			"Numeric" 	=> array(
							"eq"=>esc_html__('EQUALS','it_report_wcreport_textdomain'),
							"neq"=>esc_html__('NOT EQUALS','it_report_wcreport_textdomain'),
							"lt"=>esc_html__('LESS THEN','it_report_wcreport_textdomain'),
							"gt"=>esc_html__('MORE THEN','it_report_wcreport_textdomain'),
							"meq"=>esc_html__('EQUAL AND MORE','it_report_wcreport_textdomain'),
							"leq"=>esc_html__('LESS AND EQUAL','it_report_wcreport_textdomain'),
						),
			"String"	=>  array(
							"elike"=>esc_html__('EXACTLY LIKE','it_report_wcreport_textdomain'),
							"like"=>esc_html__('LIKE','it_report_wcreport_textdomain'),
						),
		);
		$operators_options='';
		foreach($operators as $key=>$value){
			$operators_options.='<optgroup label="'.$key.' operators">';
			foreach($value as $k=>$v){

				$selected="";
				if($fields==$k){
					$selected="SELECTED";
				}
				$operators_options.='<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
			}
			$operators_options.='</optgroup>';
		}
		return $operators_options;
	}

	add_action('wp_ajax_it_rpt_fetch_custom_fields', 'it_rpt_fetch_custom_fields');
	add_action('wp_ajax_nopriv_it_rpt_fetch_custom_fields', 'it_rpt_fetch_custom_fields');
	function it_rpt_fetch_custom_fields(){
		//print_r($_POST);
		$html='';
		parse_str($_REQUEST['postdata'], $my_array_of_vars);
		$field_id=$my_array_of_vars['field'];
		if(isset($my_array_of_vars[$field_id]))
		{
			$custom_fiels = $my_array_of_vars[$field_id];

			foreach($custom_fiels as $fields){
				$meta_column=isset($my_array_of_vars[$fields.'_column']) ? $my_array_of_vars[$fields.'_column'] : "";

				$meta_translate=isset($my_array_of_vars[$fields.'_translate']) ? $my_array_of_vars[$fields.'_translate'] : "";
				$meta_operator=isset($my_array_of_vars[$fields.'_operator']) ? $my_array_of_vars[$fields.'_operator'] : "";

				$label=str_replace("@"," ",$fields);


				$html.='
				<div class="col-xs-12 pw-translate">
					<input type="hidden" name="'.$fields.'_column" placeholder="Label for '.$fields.'" value="off">
					<input type="checkbox" name="'.$fields.'_column" placeholder="Label for '.$fields.'" "'.checked("on",$meta_column,0).'"> '.esc_html__("Display in Grid",'it_report_wcreport_textdomain').'
					<br />
					<input type="text" name="'.$fields.'_translate" placeholder="Label for '.$label.'" value="'.$meta_translate.'">
					<select name="'.$fields.'_operator">
						'.get_operation($meta_operator).'
					</select>
				</div>
				<br />';
			}
		}else{
			$html=esc_html__('Please add custom field to left site','it_report_wcreport_textdomain');
		}
		//echo $html;

		echo wp_kses(
			$html,
			$it_rpt_main_class->allowedposttags()
		);

		die();
	}

	//CUSTOM WORK - 12300
	add_action('wp_ajax_it_rpt_fetch_custom_fields_tickera', 'it_rpt_fetch_custom_fields_tickera');
	add_action('wp_ajax_nopriv_it_rpt_fetch_custom_fields_tickera', 'it_rpt_fetch_custom_fields_tickera');
	function it_rpt_fetch_custom_fields_tickera(){
		//print_r($_POST);
		$html='';
		parse_str($_REQUEST['postdata'], $my_array_of_vars);
		$field_id=$my_array_of_vars['field'];
		$id=$my_array_of_vars['id'];

		if(isset($my_array_of_vars[$field_id]))
		{
			$custom_fiels = $my_array_of_vars[$field_id];
			//print_r($custom_fiels);
			foreach($custom_fiels as $fieldss){

				$fieldss_e=explode("_",$fieldss);
				$fieldss=$fieldss_e[0];

				global $wpdb;
				$other_fields = $wpdb->get_results($wpdb->prepare("SELECT posts.post_title as Field_Name,fmeta.meta_value as Field_Html_Name from {$wpdb->prefix}posts as posts LEFT JOIN {$wpdb->prefix}postmeta as fmeta ON posts.ID=fmeta.post_id WHERE posts.post_type='tc_form_fields' AND fmeta.meta_key='name' AND posts.ID=%s",array($fieldss)), ARRAY_A);


//print_r($other_fields);
				//foreach ($fieldss as $fields){


					$label=$other_fields[0]['Field_Name'];
					$input_name=$other_fields[0]['Field_Html_Name'];

					$meta_column=isset($my_array_of_vars[$input_name.'_column']) ? $my_array_of_vars[$input_name.'_column'] : "";
					$meta_filter=isset($my_array_of_vars[$input_name.'_filter']) ? $my_array_of_vars[$input_name.'_filter'] : "";

					$meta_translate=isset($my_array_of_vars[$input_name.'_translate']) ? $my_array_of_vars[$input_name.'_translate'] : "";
					$meta_operator=isset($my_array_of_vars[$input_name.'_operator']) ? $my_array_of_vars[$input_name.'_operator'] : "";



					$html.='
					<div class="col-xs-12 pw-translate">
						<input type="hidden" name="'.$input_name.'_column" placeholder="Label for '.$label.'" value="off">
						<input type="checkbox" name="'.$input_name.'_column" placeholder="Label for '.$label.'" "'.checked("on",$meta_column,0).'">'.esc_html__("Display in Grid",'it_report_wcreport_textdomain');
						$html.='
						<input type="hidden" name="'.$input_name.'_filter" placeholder="Label for '.$label.'" value="off">
						<input type="checkbox" name="'.$input_name.'_filter" placeholder="Label for '.$label.'" "'.checked("on",$meta_filter,0).'">'.esc_html__("Display in Filter",'it_report_wcreport_textdomain');
					$html.='
						<br />
						<input type="text" name="'.$input_name.'_translate" placeholder="Label for '.$label.'" value="'.$meta_translate.'">

					</div>
					<br />';
				//}
			}
		}else{
			$html=esc_html__('Please add custom field to left site','it_report_wcreport_textdomain');
		}
		// echo $html;
		echo wp_kses(
			$html,
			$it_rpt_main_class->allowedposttags()
		);

		die();
	}


	////ADDED IN VER4.0
	/// PRODUCT OPTIONS CUSTOM FIELDS
	add_action('wp_ajax_it_rpt_fetch_custom_fields_po', 'it_rpt_fetch_custom_fields_po');
	add_action('wp_ajax_nopriv_it_rpt_fetch_custom_fields_po', 'it_rpt_fetch_custom_fields_po');
	function it_rpt_fetch_custom_fields_po(){
		//print_r($_POST);
		$html='';
		parse_str($_REQUEST['postdata'], $my_array_of_vars);
		$field_id=$my_array_of_vars['field'];
		$id=$my_array_of_vars['id'];

		if(isset($my_array_of_vars[$field_id]))
		{
			$custom_fiels = $my_array_of_vars[$field_id];

			foreach($custom_fiels as $fieldss){

				foreach ($fieldss as $fields){

					$label=str_replace("@"," ",$fields);
					if($id=='po_checkout_global_fields_select'){
						$exp= explode('@',$fields);
						$fields=$exp[0];
						$label=$exp[1];
						//str_replace("@"," ",$fields);
					}
					$input_name=str_replace(" ","_",$fields);

					$meta_column=isset($my_array_of_vars[$input_name.'_column']) ? $my_array_of_vars[$input_name.'_column'] : "";
					$meta_filter=isset($my_array_of_vars[$input_name.'_filter']) ? $my_array_of_vars[$input_name.'_filter'] : "";

					$meta_translate=isset($my_array_of_vars[$input_name.'_translate']) ? $my_array_of_vars[$input_name.'_translate'] : "";
					$meta_operator=isset($my_array_of_vars[$input_name.'_operator']) ? $my_array_of_vars[$input_name.'_operator'] : "";



					$html.='
					<div class="col-xs-12 pw-translate">
						<input type="hidden" name="'.$input_name.'_column" placeholder="Label for '.$fields.'" value="off">
						<input type="checkbox" name="'.$input_name.'_column" placeholder="Label for '.$fields.'" "'.checked("on",$meta_column,0).'">'.esc_html__("Display in Grid",'it_report_wcreport_textdomain');
					if($id=='po_global_fields_select' || $id=='po_checkout_global_fields_select'){
						$html.='
						<input type="hidden" name="'.$input_name.'_filter" placeholder="Label for '.$fields.'" value="off">
						<input type="checkbox" name="'.$input_name.'_filter" placeholder="Label for '.$fields.'" "'.checked("on",$meta_filter,0).'">'.esc_html__("Display in Filter",'it_report_wcreport_textdomain');
					}
					$html.='
						<br />
						<input type="text" name="'.$input_name.'_translate" placeholder="Label for '.$label.'" value="'.$meta_translate.'">

					</div>
					<br />';
				}
			}
		}else{
			$html=esc_html__('Please add custom field to left site','it_report_wcreport_textdomain');
		}
		// echo $html;
		echo wp_kses(
			$html,
			$it_rpt_main_class->allowedposttags()
		);

		die();
	}

	//FETCH REPORT DATAGRID
	add_action('wp_ajax_it_rpt_fetch_data_dashborad', 'it_rpt_fetch_data_dashborad');
	add_action('wp_ajax_nopriv_it_rpt_fetch_data_dashborad', 'it_rpt_fetch_data_dashborad');
	function it_rpt_fetch_data_dashborad() {
		global $wpdb;

		parse_str($_REQUEST['postdata'], $my_array_of_vars);

		$nonce = $_POST['nonce'];

		if(!wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) )
		{
			$arr = array(
			  'success'=>'no-nonce',
			  'products' => array()
			);
			print_r($arr);
			die();
		}

		//print_r($my_array_of_vars);

		//echo $sql;

		//$products = $wpdb->get_results($sql);

		global $it_rpt_main_class;

		echo '
		<div class="awr-box">
			<div class="awr-title">
				<h3>
					<i class="fa fa-filter"></i>

				</h3>
			</div><!--awr-title -->
			<div class="awr-box-content">
				<div class="col-xs-12">
					<div class="awr-box">
						<div class="awr-box-content">
							<div id="target">'.
							wp_kses(
								$it_rpt_main_class->table_html("dashboard_report",$my_array_of_vars),
								$it_rpt_main_class->allowedposttags()
							)
							.'
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="col-md-12">'.
		wp_kses(
			$it_rpt_main_class->table_html("monthly_summary",$my_array_of_vars),
			$it_rpt_main_class->allowedposttags()
		)
           .'
        </div>
		';

		die();
	}


	//FETCH CHART DATA
	add_action('wp_ajax_it_rpt_fetch_chart', 'it_rpt_fetch_chart');
	add_action('wp_ajax_nopriv_it_rpt_fetch_chart', 'it_rpt_fetch_chart');
	function it_rpt_fetch_chart() {

		global $wpdb;
		global $it_rpt_main_class;

		parse_str($_POST['postdata'], $my_array_of_vars);

		$nonce = $_POST['nonce'];

		$type = $_POST['type'];

		if(!wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) )
		{
			$arr = array(
			  'success'=>'no-nonce',
			  'products' => array()
			);
			print_r($arr);
			die();
		}

		$it_from_date=$my_array_of_vars['it_from_date'];
		$it_to_date=$my_array_of_vars['it_to_date'];
		$cur_year=substr($it_from_date,0,4);

		$it_hide_os=array('trash');
		$it_shop_order_status=$it_rpt_main_class->it_shop_status;
		if(strlen($it_shop_order_status)>0 and $it_shop_order_status != "-1")
			$it_shop_order_status = explode(",",$it_shop_order_status);
		else $it_shop_order_status = array();

		/////////////////////////////
		//TOP PRODUCTS PIE CHART
		////////////////////////////
		$order_items_top_product=$it_rpt_main_class->it_get_dashboard_top_products_chart_pie($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date);

		/////////////////////////////
		//SALE BY MONTHS
		////////////////////////////

		$order_items_months_multiple=$it_rpt_main_class->it_get_dashboard_sale_months_multiple_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date);

		$order_items_months=$it_rpt_main_class->it_get_dashboard_sale_months_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date);

		$order_items_days=$it_rpt_main_class->it_get_dashboard_sale_days_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date);

		$order_items_3d_months=$it_rpt_main_class->it_get_dashboard_sale_months_3d_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date);

		//die($order_items_days);

		$order_items_week=$it_rpt_main_class->it_get_dashboard_sale_weeks_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date);

		$final_json=array();

		$currency_decimal=get_option('woocommerce_price_decimal_sep','.');
		$currency_thousand=get_option('woocommerce_price_thousand_sep',',');
		$currency_thousand=',';
		/////////////////////
		//SALE BY MONTH MULTIPLE CHART
		////////////////////

		$it_fetchs_data=array();
		$i=0;
		foreach ($order_items_months_multiple as $key => $order_item) {
			$value  =  (is_numeric($order_item->TotalAmount) ?  number_format($order_item->TotalAmount,2):0);

			$it_fetchs_data[$i]["date"]=substr($order_item->Month,0,10);

			//$value=str_replace($currency_decimal,"",$value);
			$value=str_replace($currency_thousand,"",$value);

			$it_fetchs_data[$i]["value"] = $it_rpt_main_class->price_value($value);
			$it_fetchs_data[$i]["volume"] = $it_rpt_main_class->price_value($value);

			$i++;

		}
		//$final_json[]=($it_fetchs_data);


		///////////////////////
		//MONTH FOR CHART
		////////////////////////
		$it_fetchs_data=array();
		$i=0;
		foreach ($order_items_3d_months as $key => $order_item) {

			$value            =  (is_numeric($order_item->TotalAmount) ?  number_format($order_item->TotalAmount,2):0) ;

							$month=esc_html(sanitize_text_field($order_item->Month),'it_report_wcreport_textdomain');

			$it_fetchs_data[$i]["date"]=$month.' '.$order_item->Year;

			//$value=str_replace($currency_decimal,"",$value);
			$value=str_replace($currency_thousand,"",$value);

			$it_fetchs_data[$i]["value"] = $it_rpt_main_class->price_value($value);
			$it_fetchs_data[$i]["volume"] = $it_rpt_main_class->price_value($value);

			$i++;
		}
		$final_json[]=($it_fetchs_data);

		//////////////////
		//SALE BY DAYS
		//////////////////
		$item_dates = array();
		$item_data  = array();
		$it_fetchs_data =array();
		$i=0;
		foreach ($order_items_days as $item) {
			$item_dates[]           = trim($item->Date);
			$item_data[$item->Date] = $item->TotalAmount;

			$value=  (is_numeric($item->TotalAmount) ?  number_format($item->TotalAmount,2):0);
			$it_fetchs_data[$i]["date"] = trim($item->Date);

			//$value=str_replace($currency_decimal,"",$value);
			$value=str_replace($currency_thousand,"",$value);

			$it_fetchs_data[$i]["value"] = $it_rpt_main_class->price_value($value);
			$it_fetchs_data[$i]["volume"] = $it_rpt_main_class->price_value($value);
			$i++;
		}
		$final_json[]=$it_fetchs_data;

		////////////////////////////
		//SALE BY WEEK
		/////////////////////////////
		$item_dates = array();
		$item_data  = array();

		$weekarray = array();
		$timestamp = time();
		for ($i = 0; $i < 7; $i++) {
			$weekarray[] = gmdate('Y-m-d', $timestamp);
			$timestamp -= 24 * 3600;
		}

		foreach ($order_items_week as $item) {
			$item_dates[]           = trim($item->Date);
			$item_data[$item->Date] = (is_numeric($item->TotalAmount) ?  number_format($item->TotalAmount,2):0);
		}

		$new_data = array();
		foreach ($weekarray as $date) {
			if (in_array($date, $item_dates)) {

				$new_data[$date] = $item_data[$date];
			} else {
				$new_data[$date] = 0;
			}
		}

		$it_fetchs_data = array();
		$i         = 0;
		foreach ($new_data as $key => $value) {
			$it_fetchs_data[$i]["date"] = $key;

			//$value=explode($currency_decimal,$value);
			//$value=$value[0];
			//$value=str_replace($currency_decimal,"",$value);
			$value=str_replace($currency_thousand,"",$value);

			$it_fetchs_data[$i]["value"] = (is_numeric($value) ? number_format($value,2):0) ;
			$it_fetchs_data[$i]["volume"] =  (is_numeric($value) ? number_format($value,2):0) ;
			$i++;
		}
		$final_json[]=array_reverse($it_fetchs_data);

		///////////////////////
		//MONTH FOR CHART
		////////////////////////
		$it_fetchs_data=array();
		$i=0;
		foreach ($order_items_months as $key => $order_item) {

			$value            =  (is_numeric($order_item->TotalAmount) ?  number_format($order_item->TotalAmount,2):0) ;

							$month=esc_html(sanitize_text_field($order_item->Month),'it_report_wcreport_textdomain');

			$it_fetchs_data[$i]["date"]=$month;

			//$value=str_replace($currency_decimal,"",$value);
			$value=str_replace($currency_thousand,"",$value);
			//$value=$value[0];

			$it_fetchs_data[$i]["value"] = $it_rpt_main_class->price_value($value);
			$it_fetchs_data[$i]["volume"] = $it_rpt_main_class->price_value($value);

			$i++;
		}
		$final_json[]=($it_fetchs_data);
		//die(print_r($it_fetchs_data));

		///////////////////////////
		//	PIE CHART TOP PRODUCTS
		//////////////////////////
		$it_fetchs_data=array();
		$i=0;
		foreach ($order_items_top_product as $items) {
			$it_fetchs_data[$i]['label']=$items->Label;

			$value=(is_numeric($items->Value) ?  number_format($items->Value,2):0);
			$value=explode($currency_decimal,$value);
			$value=$value[0];
			$value=str_replace($currency_thousand,"",$value);
			$it_fetchs_data[$i]['value']= $it_rpt_main_class->price_value($value);

			$i++;
		}
		$final_json[]=($it_fetchs_data);

		//print_r($final_json);

		echo wp_json_encode($final_json);
		die();

	}

	//ADDE IN 4.9
	//FETCH PRODUCT CHART DATA
	add_action('wp_ajax_it_rpt_fetch_product_chart', 'it_rpt_fetch_product_chart');
	add_action('wp_ajax_nopriv_it_rpt_fetch_product_chart', 'it_rpt_fetch_product_chart');
	function it_rpt_fetch_product_chart() {

		global $wpdb;
		global $it_rpt_main_class;

		parse_str($_POST['postdata'], $my_array_of_vars);

		$nonce = $_POST['nonce'];

		$type = $_POST['type'];

		if(!wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) )
		{
			$arr = array(
				'success'=>'no-nonce',
				'products' => array()
			);
			print_r($arr);
			die();
		}

//print_r($my_array_of_vars);

		$it_from_date=$my_array_of_vars['it_from_date'];
		$it_to_date=$my_array_of_vars['it_to_date'];
		$date_format = $it_rpt_main_class->it_date_format($it_from_date);

		$it_product_id=$my_array_of_vars['it_product_id'];
		if(count($it_product_id)>1)
			$it_product_id  		= "'".str_replace(",","','",$it_product_id)."'";
		else
			$it_product_id  		='';

		$it_order_status=$my_array_of_vars['it_orders_status'];

		if(is_array($it_order_status)) {
			$it_order_status = implode( ",", $it_order_status );
			$it_order_status = str_replace( ",", "','", $it_order_status ) ;
		}
		else
			$it_order_status  		='';

		if($it_order_status=='OandA'){
			$it_order_status="Open','Abandoned";
		}

		//echo $it_order_status;
		$cur_year=substr($it_from_date,0,4);


		$it_order_status_condition='';
		$it_product_id_condition='';
		$date_condition='';
		$it_order_status_join ='';
		if($it_order_status  && $it_order_status != "-1") {
			$it_order_status_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 			ON it_term_relationships.object_id		=	it_posts.ID
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 				ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id
				LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 						ON it_terms.term_id					=	term_taxonomy.term_id";
		}

		if($it_order_status  && $it_order_status != "-1") {
			$it_order_status_condition = " AND term_taxonomy.taxonomy LIKE('shop_cart_status') AND it_terms.slug IN ('" . $it_order_status . "')";
		}

		if ($it_from_date != NULL &&  $it_to_date !=NULL){
			$date_condition = " AND DATE(it_posts.post_modified) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
		}

		//echo $it_product_id;
		if($it_product_id  && $it_product_id != "-1") {
			$it_product_id=explode(",",$it_product_id);
			$it_product_id_condition.=" AND (";
			$op=array();
			foreach($it_product_id as $pid){
				$op[]=" meta.meta_value LIKE '%\"product_id\";i:$pid%' OR meta.meta_value LIKE '%\"variation_id\";i:$pid%'";
			}

			$it_product_id_condition.=implode(" OR ",$op);

			$it_product_id_condition.=' ) ';
		}

		$sql="Select DATE_FORMAT(it_posts.post_modified,'%M %e, %Y %l:%i'	) as modify,it_posts.id as id,it_posts.post_author as author,meta.meta_value as  it_cartitems from {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as meta ON
it_posts.ID=meta.post_id $it_order_status_join where meta.meta_key='it_cartitems' AND it_posts.post_type='carts' $date_condition $it_order_status_condition  $it_product_id_condition ";
		//echo $sql;

		if($my_array_of_vars['type']=='line'){

			$it_product_id=$my_array_of_vars['row_id'];
			if($it_product_id  && $it_product_id != "-1") {
				$it_product_id=explode(",",$it_product_id);
				$it_product_id_condition.=" AND (";
				$op=array();
				foreach($it_product_id as $pid){
					$op[]=" meta.meta_value LIKE '%\"product_id\";i:$pid%' OR meta.meta_value LIKE '%\"variation_id\";i:$pid%'";
				}

				$it_product_id_condition.=implode(" OR ",$op);

				$it_product_id_condition.=' ) ';
			}

			$it_order_status_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 			ON it_term_relationships.object_id		=	it_posts.ID
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 				ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id
				LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 						ON it_terms.term_id					=	term_taxonomy.term_id";

			//MAIN
			$it_order_status_condition = " AND term_taxonomy.taxonomy LIKE('shop_cart_status') AND it_terms.slug IN ('open','Abandoned','Converted')";
			$sql_all="Select DATE_FORMAT(it_posts.post_modified,'%M %Y' ) as modify_m,DATE_FORMAT(it_posts.post_modified,'%M %e, %Y %l:%i'	) as modify,it_posts.id as id,it_posts.post_author as author,meta.meta_value as  it_cartitems from {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as meta ON
it_posts.ID=meta.post_id $it_order_status_join where meta.meta_key='it_cartitems' AND it_posts.post_type='carts' $date_condition $it_order_status_condition  $it_product_id_condition ";
			//echo $sql_all;

			//OPRN
			$it_order_status_condition = " AND term_taxonomy.taxonomy LIKE('shop_cart_status') AND it_terms.slug IN ('open')";
			$sql_open="Select DATE_FORMAT(it_posts.post_modified,'%M %Y' ) as modify_m,DATE_FORMAT(it_posts.post_modified,'%M %e, %Y %l:%i'	) as modify,it_posts.id as id,it_posts.post_author as author,meta.meta_value as  it_cartitems from {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as meta ON
it_posts.ID=meta.post_id $it_order_status_join where meta.meta_key='it_cartitems' AND it_posts.post_type='carts' $date_condition $it_order_status_condition  $it_product_id_condition ";


			//ABANDONED STATUS
			$it_order_status_condition = " AND term_taxonomy.taxonomy LIKE('shop_cart_status') AND it_terms.slug IN ('Abandoned')";
			$sql_abdn="Select DATE_FORMAT(it_posts.post_modified,'%M %Y' ) as modify_m,DATE_FORMAT(it_posts.post_modified,'%M %e, %Y %l:%i'	) as modify,it_posts.id as id,it_posts.post_author as author,meta.meta_value as  it_cartitems from {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as meta ON
it_posts.ID=meta.post_id $it_order_status_join where meta.meta_key='it_cartitems' AND it_posts.post_type='carts' $date_condition $it_order_status_condition  $it_product_id_condition ";

			//CONVERTED
			$it_order_status_condition = " AND term_taxonomy.taxonomy LIKE('shop_cart_status') AND it_terms.slug IN ('Converted')";
			$sql_convert="Select DATE_FORMAT(it_posts.post_modified,'%M %Y' ) as modify_m,DATE_FORMAT(it_posts.post_modified,'%M %e, %Y %l:%i'	) as modify,it_posts.id as id,it_posts.post_author as author,meta.meta_value as  it_cartitems from {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as meta ON
it_posts.ID=meta.post_id $it_order_status_join where meta.meta_key='it_cartitems' AND it_posts.post_type='carts' $date_condition $it_order_status_condition  $it_product_id_condition ";
			//echo $sql_convert;
		}

		//echo $sql;


		$final_json=array();

		$currency_decimal=get_option('woocommerce_price_decimal_sep','.');
		$currency_thousand=get_option('woocommerce_price_thousand_sep',',');
		$currency_thousand=',';
		/////////////////////
		//SALE BY MONTH MULTIPLE CHART
		////////////////////

		///////////////////////////
		//	PIE CHART TOP PRODUCTS
		//////////////////////////
		$it_fetchs_data=array();
		$final_array=array();
		$i=0;

		if($my_array_of_vars['type']=='pie') {

			$order_items   = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$product_array = array();
			foreach ( $order_items as $item ) {

				$items = ( unserialize( $item->it_cartitems ) );

				foreach ( $items as $pitem ) {
					$_product = wc_get_product( $pitem['product_id'] );
					$pid      = $pitem['product_id'];
					if($it_product_id  && $it_product_id != "-1") {
						$it_product_id = explode( ",", $it_product_id );
						if(is_array($it_product_id) && !in_array($pid,$it_product_id)){
							continue;
						}
					}

					//echo $pid.'-';
					$sum                            = 0;
					$product_array[ $pid ]['title'] = $_product->get_title();
					$product_array[ $pid ]['sku']   = $_product->get_sku();
					$variation                      = '';
					if ( isset( $pitem['variation'] ) && count( $pitem['variation'] ) > 0 ) {
						$variation_data = wc_get_formatted_variation( $pitem['variation'], true );

						$variation = $variation_data;
					}
					$product_array[ $pid ]['variation'] = $variation;
					if ( isset( $product_array[ $pid ]['qty'] ) ) {
						$product_array[ $pid ]['qty'] += $pitem['quantity'];
					} else {
						$product_array[ $pid ]['qty'] = $pitem['quantity'];
					}

					$product_array[ $pid ]['rate'] = wc_price( $_product->get_price() );
					$sum                           = ( $pitem['quantity'] * $_product->get_price() );
					if ( isset( $product_array[ $pid ]['total'] ) ) {
						$product_array[ $pid ]['total'] += $sum;
					} else {
						$product_array[ $pid ]['total'] = $sum;
					}

				}

				$i = 0;

				foreach ( $product_array as $key => $pitem ) {
					$final_array[ $i ]['label'] = $pitem['title'];

					//$value=(is_numeric($items->Value) ?  number_format($items->Value,2):0);

					$final_array[ $i ]['value'] = $pitem['qty'];

					$i ++;
				}

			}
		}

		if($my_array_of_vars['type']=='line') {
			$it_product_id=$my_array_of_vars['row_id'];
			$order_items_all   = $wpdb->get_results( $sql_all ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$order_items_open   = $wpdb->get_results( $sql_open ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$order_items_abdn   = $wpdb->get_results( $sql_abdn ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$order_items_convert   = $wpdb->get_results( $sql_convert ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			$product_array_open=$product_array_abdn=$product_array_converted=array();

			//ALL
			$product_array = array();
			foreach ( $order_items_all as $item ) {

				$items = ( unserialize( $item->it_cartitems ) );

				foreach ( $items as $pitem ) {
					$_product = wc_get_product( $pitem['product_id'] );
					$pid      = $pitem['product_id'];

					if($it_product_id  && $it_product_id != "-1") {
						if($it_product_id!=$pid){
							continue;
						}
					}
					$sum                            = 0;
					$product_array[ $pid ]['date']   = $item->modify_m;
				}
			}
//print_r($product_array);


			//OPEN
			$product_array = array();
			foreach ( $order_items_open as $item ) {

				$items = ( unserialize( $item->it_cartitems ) );

				foreach ( $items as $pitem ) {
					$_product = wc_get_product( $pitem['product_id'] );
					$pid      = $pitem['product_id'];

					if($it_product_id  && $it_product_id != "-1") {
						if($it_product_id!=$pid){
							continue;
						}
					}

					$product_array[ $pid ]['date']   = $item->modify_m;
					if ( isset( $product_array[ $pid ]['qty'] ) ) {
						$product_array[ $pid ]['qty'] += $pitem['quantity'];
					} else {
						$product_array[ $pid ]['qty'] = $pitem['quantity'];
					}
				}

				$i = 0;

				foreach ( $product_array as $key => $pitem ) {
					if(isset($product_array_open[$pitem['date']]["value"]))
						$product_array_open[$pitem['date']]["value"]+= $pitem['qty'];
					else
						$product_array_open[$pitem['date']]["value"]= $pitem['qty'];
				}

			}

			//print_r($product_array_open);

			//ABANDINE
			$product_array = array();
			foreach ( $order_items_abdn as $item ) {

				$items = ( unserialize( $item->it_cartitems ) );

				foreach ( $items as $pitem ) {
					$_product = wc_get_product( $pitem['product_id'] );
					$pid      = $pitem['product_id'];

					if($it_product_id  && $it_product_id != "-1") {
						if($it_product_id!=$pid){
							continue;
						}
					}

					$product_array[ $pid ]['date']   = $item->modify_m;
					if ( isset( $product_array[ $pid ]['qty'] ) ) {
						$product_array[ $pid ]['qty'] += $pitem['quantity'];
					} else {
						$product_array[ $pid ]['qty'] = $pitem['quantity'];
					}
				}

				$i = 0;

				foreach ( $product_array as $key => $pitem ) {
					if(isset($product_array_abdn[$pitem['date']]["value"]))
						$product_array_abdn[$pitem['date']]["value"]+= $pitem['qty'];
					else
						$product_array_abdn[$pitem['date']]["value"]= $pitem['qty'];
				}

			}
			//print_r($product_array_abdn);

			//CONVERT
			$product_array = array();
			foreach ( $order_items_convert as $item ) {

				$items = ( unserialize( $item->it_cartitems ) );

				foreach ( $items as $pitem ) {
					$_product = wc_get_product( $pitem['product_id'] );
					$pid      = $pitem['product_id'];

					if($it_product_id  && $it_product_id != "-1") {
						if($it_product_id!=$pid){
							continue;
						}
					}

					$product_array[ $pid ]['date']   = $item->modify_m;
					if ( isset( $product_array[ $pid ]['qty'] ) ) {
						$product_array[ $pid ]['qty'] += $pitem['quantity'];
					} else {
						$product_array[ $pid ]['qty'] = $pitem['quantity'];
					}
				}

				$i = 0;

				foreach ( $product_array as $key => $pitem ) {
					if(isset($product_array_converted[$pitem['date']]["value"]))
						$product_array_converted[$pitem['date']]["value"]+= $pitem['qty'];
					else
						$product_array_converted[$pitem['date']]["value"]= $pitem['qty'];
				}

			}
			//print_r($product_array_converted);
		}

		$order_items_all   = $wpdb->get_results( $sql_all ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		//ALL
		$product_array_main = array();
		$i=0;
		foreach ( $order_items_all as $item ) {
			$product_array_main[$item->modify_m]   = 0;
			$i++;
		}

		//print_r($product_array_main);

		$i=0;
		foreach($product_array_main as $key=>$val){
			$keys=explode(" ",$key);
			$final_array[$i]['date']=$key;
			if(isset($product_array_open[$key])){
				$final_array[$i]['open']=$product_array_open[$key]['value'];
			}else{
				$final_array[$i]['open']=0;

			}

			if(isset($product_array_abdn[$key])){
				$final_array[$i]['abandoned']=$product_array_abdn[$key]['value'];
			}else{
				$final_array[$i]['abandoned']=0;

			}

			if(isset($product_array_converted[$key])){
				$final_array[$i]['convert']=$product_array_converted[$key]['value'];
			}else{
				$final_array[$i]['convert']=0;

			}
			$i++;
		}


		$final_json=($final_array);
		//print_r($final_json);
		echo wp_json_encode($final_json);

		die();

	}

	////ADDED IN VER4.0
	/// DAILY EMAIL
	//CHECK MAIL
	add_action('wp_ajax_it_rpt_test_email', 'it_rpt_test_email');
	add_action('wp_ajax_nopriv_it_rpt_test_email', 'it_rpt_test_email');
	function it_rpt_test_email() {

		global $wpdb;
		global $it_rpt_main_class;

		//parse_str( $_POST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];


		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		global $it_rpt_main_class;
		$result=$it_rpt_main_class->wcx_send_email_schedule();

		if($result){
			echo '<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="awr-sum-item" style="background-color: #0DBF44;color: #fff">
							<h2>'.esc_html__('Your Test mail sent successfully','it_report_wcreport_textdomain').'</h2>
						</div><!--awr-sum-item -->
					</div>';
		}else{
			echo '<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="awr-sum-item">
							<h2>'.esc_html__('Error in sending mail!','it_report_wcreport_textdomain').'</h2>
						</div><!--awr-sum-item -->
					</div>';
		}

		die(0);
	}



	function it_fetch_reports_core(){


		global $it_rpt_main_class;


		if(($it_rpt_main_class->email=="" || !filter_var($it_rpt_main_class->email, FILTER_VALIDATE_EMAIL)) && isset($_GET["smenu"]) && $_GET["smenu"]!="wcx_wcreport_plugin_active_report"){
			header("location:".admin_url()."admin.php?page=wcx_wcreport_plugin_active_report&parent=active_plugin");
			return false;
		}

		/////////////
		/// /// CHECK LICENSE PLUGIN
		/////////////
		$request_string = array(
			"body" => array(
				"action" => "insert_licensekey",
				"license-key" => $it_rpt_main_class->license_key,
				"email" => $it_rpt_main_class->email,
				"domain" => $it_rpt_main_class->domain,
				"item-id" => $it_rpt_main_class->item_valid_id,
			)
		);

		if($it_rpt_main_class->license_key!="" && (filter_var($it_rpt_main_class->email, FILTER_VALIDATE_EMAIL))){
			$response = wp_remote_post($it_rpt_main_class->api_url, $request_string);

			if ( is_wp_error( $response ) or ( wp_remote_retrieve_response_code( $response ) != 200 ) ) {
				return false;
			}
			$result = json_decode( wp_remote_retrieve_body( $response ), true );

			//$result=$result[0];
			if(isset($result["verify-purchase"]["status"]) && $result["verify-purchase"]["status"]=="valid"){
				$it_rpt_main_class->it_core_status=true;
				return $result;
			}
			else if(isset($result["verify-purchase"]["status"]) && $result["verify-purchase"]["status"]!="valid"){
				return $result;
			}
			else{
				return false;
			}
		}
	}




	////ADDED IN VER4.0
	/// SEND REQUEST FORM
	add_action('wp_ajax_it_rpt_request_form', 'it_rpt_request_form');
	add_action('wp_ajax_nopriv_it_rpt_request_form', 'it_rpt_request_form');
	function it_rpt_request_form() {

		global $wpdb;
		global $it_rpt_main_class;

		parse_str( $_POST['postdata'], $my_array_of_vars );

		$nonce = $_POST['nonce'];

		$type = $_POST['type'];

		if ( ! wp_verify_nonce( $nonce, 'it_livesearch_nonce' ) ) {
			$arr = array(
				'success'  => 'no-nonce',
				'products' => array()
			);
			print_r( $arr );
			die();
		}

		global $it_rpt_main_class;

		$subject_arr=array("request"=>esc_html__("Send a Request",'it_report_wcreport_textdomain'),"issue"=>esc_html__("Report an issue",'it_report_wcreport_textdomain'));
		$fullname=$my_array_of_vars['awr_fullname'];
		$email=$my_array_of_vars['awr_email'];
		$subject=$my_array_of_vars['awr_subject'];
		$subject=$subject!='' ? $subject_arr[$subject] : esc_html__("Email From Woo Reporting",'it_report_wcreport_textdomain') ;
		$title=$my_array_of_vars['awr_title'];
		$content=$my_array_of_vars['awr_content'];
		$email_optimize 		= $it_rpt_main_class->get_options(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'optimize_email','');

		$email_send_to = $it_rpt_main_class->reformat_email_text('reporting_support@ithemelandco.com');
		$email_from_email = $it_rpt_main_class->reformat_email_text($email);

		$date_format 					= get_option( 'date_format', "Y-m-d" );
		$time_format 					= get_option('time_format','g:i a');
		$reporte_created				= date_i18n($date_format." ".$time_format);

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		if($email_optimize)
			$headers .= 'From: '.$fullname.' <'.$email_from_email.'>'. "\r\n";
		else {

			$headers .= "From: =?UTF-8?B?" . base64_encode( $fullname ) . "?= <" . $email_from_email . ">" . "\r\n";
			$headers .= 'Content-Transfer-Encoding: 8bit';
		}

		$email_data =  $content. "<div style=\" padding-bottom:3px; width:520px; margin:auto; text-align:left;\"><strong>".esc_html__("Created Date/Time:",'it_report_wcreport_textdomain')." "."</strong> {$reporte_created}</div>";

		$message = $email_data;
		$to		 = $email_send_to;

		$result = wp_mail( $to, "=?UTF-8?B?".base64_encode($subject).' - '.base64_encode($title)."?=", $message, $headers);

		if($result){
			echo '<span class="awr-req-result" style="background-color: #0DBF44">'. esc_html__("Your Email has been received. Thanks for contact.",'it_report_wcreport_textdomain') .' </span>';
		}else{
			echo '<span class="awr-req-result">'. esc_html__("Error in sending Email!",'it_report_wcreport_textdomain') .' </span>';
		}

		die(0);
	}


	////ADDED IN VER4.0
	/// INVOICE ACTION
	add_action('wp_ajax_it_rpt_pdf_invoice', 'it_rpt_pdf_invoice');
	add_action('wp_ajax_nopriv_it_rpt_pdf_invoice', 'it_rpt_pdf_invoice');
	function it_rpt_pdf_invoice(){
		
	}
?>
