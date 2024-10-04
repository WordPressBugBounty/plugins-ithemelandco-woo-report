<?php

	if($file_used=="sql_table")
	{


		//GET POSTED PARAMETERS
		$request 			= array();
		$start				= 0;


		$it_order_status		= $this->it_get_woo_requests('it_orders_status',"-1",true);
		$category_id		= $this->it_get_woo_requests('it_category_id','-1',true);
		$it_product_id			= $this->it_get_woo_requests('it_product_id','-1',true);
		$it_id_order_status	= $this->it_get_woo_requests('it_id_order_status','-1',true);
		$it_country_code		= $this->it_get_woo_requests('it_countries_code','-1',true);
		$state_code 		= $this->it_get_woo_requests('it_states_code','-1',true);
		$it_sort_by 			= $this->it_get_woo_requests('sort_by','-1',true);
		$it_order_by 			= $this->it_get_woo_requests('order_by','DESC',true);

		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$date_format = $this->it_date_format($it_from_date);

		$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		///////////HIDDEN FIELDS////////////
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_publish_order='no';

		$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
		//////////////////////

		//DATE
		$it_from_date_condition='';

		//ORDER SATTUS
		$it_id_order_status_join='';
		$it_order_status_condition='';

		//COUTNRY
		$it_country_code_join='';
		$it_country_code_condition_1='';
		$it_country_code_condition_2='';

		//STATE
		$state_code_join='';
		$state_code_condition_1='';
		$state_code_condition_2='';

		//ORDER STATUS
		$it_id_order_status_condition='';

		//DATE
		$it_from_date_condition='';

		//PUBLISH ORDER
		$it_publish_order_condition='';

		//HIDE ORDER STATUS
		$it_hide_os_condition ='';

		$sql_columns = "
		it_posts.ID,
		SUM(it_woocommerce_order_itemmeta_tax_amount.meta_value)  AS _order_tax,
		SUM(it_woocommerce_order_itemmeta_shipping_tax_amount.meta_value)  AS _shipping_tax_amount,

		SUM(it_postmeta1.meta_value)  AS _order_shipping_amount,
		SUM(it_postmeta2.meta_value)  AS _order_total_amount,
		COUNT(it_posts.ID)  AS _order_count,

		it_woocommerce_order_items.order_item_name as tax_rate_code,
		it_woocommerce_tax_rates.tax_rate_name as tax_rate_name,
		it_woocommerce_tax_rates.tax_rate as order_tax_rate,

		it_woocommerce_order_itemmeta_tax_amount.meta_value AS order_tax,
		it_woocommerce_order_itemmeta_shipping_tax_amount.meta_value AS shipping_tax_amount,
		it_postmeta1.meta_value as order_shipping_amount,
		it_postmeta2.meta_value as order_total_amount,
		it_postmeta3.meta_value 		as billing_state,
		it_postmeta4.meta_value 		as billing_country
		";

		$sql_columns .= ", CONCAT(it_woocommerce_order_items.order_item_name,'-',it_woocommerce_tax_rates.tax_rate_name,'-',it_woocommerce_tax_rates.tax_rate,'-',it_postmeta4.meta_value,'',it_postmeta3.meta_value) as group_column";


		$sql_joins = "{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items";

		if(($it_id_order_status  && $it_id_order_status != '-1') || $it_sort_by == "status"){
			$it_id_order_status_join = "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";

			if($it_sort_by == "status"){
				$it_id_order_status_join .= " LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 				ON it_terms.term_id					=	term_taxonomy.term_id";
			}
		}

		$sql_joins .= "$it_id_order_status_join LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_tax ON it_woocommerce_order_itemmeta_tax.order_item_id=it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_tax_amount ON it_woocommerce_order_itemmeta_tax_amount.order_item_id=it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_shipping_tax_amount ON it_woocommerce_order_itemmeta_shipping_tax_amount.order_item_id=it_woocommerce_order_items.order_item_id
		LEFT JOIN  {$wpdb->prefix}woocommerce_tax_rates as it_woocommerce_tax_rates ON it_woocommerce_tax_rates.tax_rate_id=it_woocommerce_order_itemmeta_tax.meta_value
		LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=	it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta4 ON it_postmeta4.post_id=it_woocommerce_order_items.order_id";

		if($it_country_code and $it_country_code != '-1')
			$it_country_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta5 ON it_postmeta5.post_id=it_posts.ID";

		if($state_code and $state_code != '-1')
			$state_code_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_state ON it_postmeta_billing_state.post_id=it_posts.ID";

		$sql_joins.="$it_country_code_join $state_code_join";

		$sql_condition = " it_postmeta1.meta_key = '_order_shipping' AND it_woocommerce_order_items.order_item_type = 'tax'
		AND it_posts.post_type='shop_order'
		AND it_postmeta2.meta_key='_order_total'
		AND it_woocommerce_order_itemmeta_tax.meta_key='rate_id'
		AND it_woocommerce_order_itemmeta_tax_amount.meta_key='tax_amount'
		AND it_woocommerce_order_itemmeta_shipping_tax_amount.meta_key='shipping_tax_amount'
		AND it_postmeta3.meta_key='_billing_state'
		AND it_postmeta4.meta_key='_billing_country'";

		if($it_id_order_status  && $it_id_order_status != '-1')
			$it_id_order_status_condition = " AND term_taxonomy.term_id IN (".$it_id_order_status .")";

		if($it_country_code and $it_country_code != '-1')
			$it_country_code_condition_1 = " AND it_postmeta5.meta_key='_billing_country'";

		if($state_code and $state_code != '-1')
			$state_code_condition_1 = " AND it_postmeta_billing_state.meta_key='_billing_state'";

		if($it_country_code and $it_country_code != '-1')
			$it_country_code_condition_2 = " AND it_postmeta5.meta_value IN (".$it_country_code.")";

		if($state_code and $state_code != '-1')
			$state_code_condition_2 = " AND it_postmeta_billing_state.meta_value IN (".$state_code.")";

		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition = " AND it_posts.post_status IN (".$it_order_status.")";

		if ($it_from_date != NULL &&  $it_to_date !=NULL){
			$it_from_date_condition = " AND (DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
		}

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition = " AND it_posts.post_status NOT IN ('".$it_hide_os."')";

		$sql_group_by = "  group by group_column";

		$sql_order_by = "  ORDER BY (it_woocommerce_tax_rates.tax_rate + 0)  ASC";

		$sql = "SELECT $sql_columns
				FROM $sql_joins $it_id_order_status_join
				WHERE $sql_condition
				$it_id_order_status_condition $it_country_code_condition_1 $state_code_condition_1
				$it_country_code_condition_2 $state_code_condition_2
				$it_from_date_condition $it_publish_order_condition
				$it_order_status_condition $it_hide_os_condition  $it_from_date_condition
				$sql_group_by $sql_order_by";

		//echo $sql;

	}
	elseif($file_used=="data_table"){

		////ADDE IN VER4.0
		/// TOTAL ROWS VARIABLES
		$tax_count=$order_count=$shipping_amnt=$gross_amnt=$net_amnt=$shipping_tax=$order_tax=$total_tax=0;
		$net_amnt_arr=array();
		foreach($this->results as $items){
		    $index_cols=0;
		//for($i=1; $i<=20 ; $i++){

			////ADDE IN VER4.0
			/// TOTAL ROWS
            $tax_count++;

			$datatable_value.=("<tr>");
				//Tax Name
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->tax_rate_name;
				$datatable_value.=("</td>");

				//Tax Rate
				$it_table_value=$items->order_tax_rate;

				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $it_table_value = sprintf("%.2f%%",$it_table_value);
				$datatable_value.=("</td>");

				//Order Count
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->_order_count;

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $order_count+= $items->_order_count;
				$datatable_value.=("</td>");

				//Shipping Amt.
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($items->_order_shipping_amount);

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $shipping_amnt+= $items->_order_shipping_amount;
				$datatable_value.=("</td>");

				//Gross Amt.
//				$display_class='';
//				$gross_value=$this->it_get_number_percentage($items->_order_tax,$items->order_tax_rate);
//				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
//				$datatable_value.=("<td style='".$display_class."'>");
//					$datatable_value.= $this->price($gross_value);
//
//                    ////ADDE IN VER4.0
//                    /// TOTAL ROWS
//                    $gross_amnt+= $gross_value;
//				$datatable_value.=("</td>");

				//Net Amt.
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($items->order_total_amount);

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS

	                    $net_amnt += $items->order_total_amount;
                    if(!isset($net_amnt_arr[$items->ID])) {
	                    $net_amnt_arr[$items->ID]='';
                    }

				$datatable_value.=("</td>");

				//Shipping Tax
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($items->_shipping_tax_amount);

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $shipping_tax+= $items->_shipping_tax_amount;
				$datatable_value.=("</td>");

				//Order Tax
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($items->_order_tax);

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $order_tax+= $items->_order_tax;
				$datatable_value.=("</td>");

				//Total Tax
				$display_class='';
				$total_tax_value=$items->_shipping_tax_amount + $items->_order_tax;
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($total_tax_value);

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $total_tax+= $total_tax_value;
				$datatable_value.=("</td>");

			$datatable_value.=("</tr>");
		}
		//print_r($net_amnt_arr);

		////ADDE IN VER4.0
		/// TOTAL ROWS
		$table_name_total= $table_name;
		$this->table_cols_total = $this->table_columns_total( $table_name_total );
		$datatable_value_total='';

		$datatable_value_total.=("<tr>");
		$datatable_value_total.="<td>$tax_count</td>";
		$datatable_value_total.="<td>$order_count</td>";
		$datatable_value_total.="<td>".(($shipping_amnt) == 0 ? $this->price(0) : $this->price($shipping_amnt))."</td>";
		//$datatable_value_total.="<td>".(($gross_amnt) == 0 ? $this->price(0) : $this->price($gross_amnt))."</td>";
		$datatable_value_total.="<td>".(($net_amnt) == 0 ? $this->price(0) : $this->price($net_amnt))."</td>";
		$datatable_value_total.="<td>".(($shipping_tax) == 0 ? $this->price(0) : $this->price($shipping_tax))."</td>";
		$datatable_value_total.="<td>".(($order_tax) == 0 ? $this->price(0) : $this->price($order_tax))."</td>";
		$datatable_value_total.="<td>".(($total_tax) == 0 ? $this->price(0) : $this->price($total_tax))."</td>";
		$datatable_value_total.=("</tr>");

		//print_r($net_amnt_arr);

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

                    <input type="hidden" name="it_id_order_status[]" id="it_id_order_status" value="-1">
                    <input type="hidden" name="it_orders_status[]" id="order_status" value="<?php echo esc_attr($this->it_shop_status); ?>">
                </div>

            </div>

            <div class="col-md-12 awr-save-form">
                    <?php
                    	$it_hide_os=$this->otder_status_hide;
						$it_publish_order='no';

						$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
					?>
                    <input type="hidden" name="list_parent_category" value="">
                    <input type="hidden" name="it_category_id" value="-1">

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
