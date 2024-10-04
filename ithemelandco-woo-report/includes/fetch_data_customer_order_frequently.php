<?php

	if($file_used=="sql_table")
	{
		//GET POSTED PARAMETERS
		$request 			= array();
		$start				= 0;
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


		//ORDER SATTUS
		$it_id_order_status_join='';
		$it_order_status_condition='';

		//ORDER STATUS
		$it_id_order_status_condition='';

		//DATE
		$it_from_date_condition='';

		//PUBLISH ORDER
		$it_publish_order_condition='';

		//HIDE ORDER STATUS
		$it_hide_os_condition ='';

		$sql_columns= "
            COUNT(DISTINCT posts.ID)									AS order_count,
            SUM(order_total.meta_value) 								AS total_amount,
            COUNT(DISTINCT postmeta_billing_email.meta_value)		AS customer_count,
            DATE_FORMAT(posts.post_date,'month_%Y%m') 				AS month_key,
            DATE_FORMAT(posts.post_date,'%Y-%m-01')					AS min_date,
            DATE_FORMAT(LAST_DAY(posts.post_date),'%Y-%m-%d')		AS max_date,
            postmeta_billing_email.meta_value						AS billing_email,
            CONCAT(DATE_FORMAT(posts.post_date,'month_%Y%m'),'-',postmeta_billing_email.meta_value)		AS group_column";


		$sql_joins = "
            {$wpdb->posts} AS posts
            LEFT JOIN {$wpdb->postmeta} AS order_total ON order_total.post_id = posts.ID AND order_total.meta_key = '_order_total'
            LEFT JOIN {$wpdb->postmeta} AS postmeta_billing_email ON postmeta_billing_email.post_id = posts.ID AND postmeta_billing_email.meta_key = '_billing_email'";

		$sql_condition = " posts.post_type = 'shop_order' ";

		if ($it_from_date != NULL &&  $it_to_date !=NULL){
			$sql_condition.= " AND DATE(posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
		}

		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition= " AND posts.post_status IN (".$it_order_status.")";

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition= " AND posts.post_status NOT IN ('".$it_hide_os."')";


		$sql_group_by= " GROUP BY group_column";

		$sql_order_by= " ORDER BY posts.post_date DESC";

		$sql = "SELECT $sql_columns FROM $sql_joins WHERE $sql_condition $it_order_status_condition $it_hide_os_condition
				$sql_group_by $sql_order_by	";

		//echo $sql;

	}elseif($file_used=="data_table"){

		foreach($this->results as $items) {
			$index_cols=0;

			$datatable_value.=("<tr>");

				//Months
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->month_name;
				$datatable_value.=("</td>");

				//Total Sale Amnt
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->total_sales_amount == 0 ? $this->price(0) : $this->price($items->total_sales_amount);
				$datatable_value.=("</td>");

				//Total Order Count
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->total_order_count;
				$datatable_value.=("</td>");

                //New Customer Count
                $display_class='';
               	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= $items->repeat_customer_count;
                $datatable_value.=("</td>");

                //Repeat Customer Count
                $display_class='';
               	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= $items->new_customer_count;
                $datatable_value.=("</td>");

                //New Customer Total
                $display_class='';
               	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= $items->new_customer_sales_amount== 0 ? $this->price(0) : $this->price($items->new_customer_sales_amount);
                $datatable_value.=("</td>");

                //Repeat Customer Total
                $display_class='';
               	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= $items->repeat_customer_sales_amount== 0 ? $this->price(0) : $this->price($items->repeat_customer_sales_amount);
                $datatable_value.=("</td>");


			$datatable_value.=("</tr>");
		}
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

                <div class="col-md-6">
                    <div class="awr-form-title">
			            <?php esc_html_e('Min Order Count','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa fa-battery-4"></i></span>
                    <input name="it_min_order_count" id="it_min_order_count" type="text"/>
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
