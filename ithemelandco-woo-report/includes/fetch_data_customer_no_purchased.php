<?php

	if($file_used=="sql_table")
	{
		//GET POSTED PARAMETERS
		$request 			= array();
		$start				= 0;
		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$it_billing_name			= $this->it_get_woo_requests('it_billing_name',NULL,true);
		$it_billing_email			= $this->it_get_woo_requests('it_billing_email',NULL,true);
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

		//EMAILS
		$customer_emails_condition='';

		//BILLING NAME
		$it_billing_name_condition='';

		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		$params=array(
			"it_from_date"=>$it_from_date,
			"it_to_date"=>$it_to_date,
			"order_status"=>$it_order_status,
			"it_hide_os"=>'"trash"'
		);

		$customers 				= $this->it_fetch_emails_of_purchased_customer($params);

		$customer_ids = array();
		$customer_emails = array();
		foreach($customers as $key => $values){
			$customer_ids[] = $values->customer_id;
			$customer_emails[] = $values->billing_email;
		}

		$sql_columns= "
		SUM(it_postmeta1.meta_value) 		AS 'total_amount' ,
		it_postmeta2.meta_value 			AS 'billing_email' ,
		it_postmeta3.meta_value 			AS 'billing_first_name',
		COUNT(it_postmeta2.meta_value) 		AS 'order_count',
		it_postmeta4.meta_value 			AS  customer_id,
		it_postmeta5.meta_value 			AS  billing_last_name,
		MAX(it_posts.post_date)				AS  order_date,
		CONCAT(it_postmeta3.meta_value, ' ',it_postmeta5.meta_value) AS billing_name ";




		$sql_joins = "
		{$wpdb->prefix}posts as it_posts
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_posts.ID
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta4 ON it_postmeta4.post_id=it_posts.ID
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta5 ON it_postmeta5.post_id=it_posts.ID";

		if(strlen($it_id_order_status)>0 && $it_id_order_status != "-1" && $it_id_order_status != "no" && $it_id_order_status != "all"){
				$it_id_order_status_join= "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy 		ON it_term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
		}

		$sql_condition = "
		it_posts.post_type		= 'shop_order'
		AND it_postmeta1.meta_key	= '_order_total'
		AND it_postmeta2.meta_key	= '_billing_email'
		AND it_postmeta3.meta_key	= '_billing_first_name'
		AND it_postmeta4.meta_key	= '_customer_user'
		AND it_postmeta5.meta_key	= '_billing_last_name'";



		if(isset($customer_emails[0])){
			$in_customer_emails		= implode("','",$customer_emails);
			$customer_emails_condition = " AND  it_postmeta2.meta_value NOT IN ('{$in_customer_emails}')";
		}

		if(isset($it_billing_email[0])){
			$it_billing_name_condition = " AND  it_postmeta2.meta_value LIKE '%{$it_billing_email}%'";
		}

		if($it_billing_name and $it_billing_name != '-1'){
			$sql_condition .= " AND (lower(concat_ws(' ', it_postmeta3.meta_value, it_postmeta5.meta_value)) like lower('%".$it_billing_name."%') OR lower(concat_ws(' ', it_postmeta5.meta_value, it_postmeta3.meta_value)) like lower('%".$it_billing_name."%'))";
		}

		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
		    $it_order_status_condition= " AND it_posts.post_status IN (".$it_order_status.")";

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition= " AND it_posts.post_status NOT IN ('".$it_hide_os."')";

		$sql_group_by= " GROUP BY  it_postmeta2.meta_value ";
		$sql_order_by="  Order By billing_first_name ASC, billing_last_name ASC ";

		$sql = "SELECT $sql_columns FROM $sql_joins  WHERE $sql_condition $customer_emails_condition
                $it_billing_name_condition $it_order_status_condition $it_hide_os_condition
				$sql_group_by $sql_order_by
				";

		//echo $sql;

	}elseif($file_used=="data_table"){

		////ADDE IN VER4.0
		/// TOTAL ROWS VARIABLES
		$customer_count=$order_count=$total_amnt=0;

		foreach($this->results as $items){
		    $index_cols=0;
		//for($i=1; $i<=20 ; $i++){

			////ADDE IN VER4.0
			/// TOTAL ROWS
			$customer_count++;

			$datatable_value.=("<tr>");

				//Billing First Name
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->billing_first_name;
				$datatable_value.=("</td>");

				//Billing Last Name
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->billing_last_name;
				$datatable_value.=("</td>");

				//Order Count
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->order_count;

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $order_count+= $items->order_count;

				$datatable_value.=("</td>");

                //Billing Email
                $display_class='';
               	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= $this->it_email_link_format($items->billing_email,false);
                $datatable_value.=("</td>");

				//Amount
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
			        $total_amnt+= $items->total_amount;

				$datatable_value.=("</td>");

                //Date
			    $date_format		= get_option( 'date_format' );
                $display_class='';
               	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                $datatable_value.=("<td style='".$display_class."'>");
                $datatable_value.= gmdate($date_format,strtotime($items->order_date));
                $datatable_value.=("</td>");

                //Wake Up
//                $display_class='';
//               	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
//                $datatable_value.=("<td style='".$display_class."'>");
//                $datatable_value.= "Send Email";
//                $datatable_value.=("</td>");


			$datatable_value.=("</tr>");
		}

		////ADDE IN VER4.0
		/// TOTAL ROWS
		$table_name_total= $table_name;
		$this->table_cols_total = $this->table_columns_total( $table_name_total );
		$datatable_value_total='';

		$datatable_value_total.=("<tr>");
		$datatable_value_total.="<td>$customer_count</td>";
		$datatable_value_total.="<td>$order_count</td>";
		$datatable_value_total.="<td>".(($total_amnt) == 0 ? $this->price(0) : $this->price($total_amnt))."</td>";
		$datatable_value_total.=("</tr>");

	}elseif($file_used=="search_form"){
	?>
		<form class='alldetails search_form_report' action='' method='post'>
            <input type='hidden' name='action' value='submit-form' />
            <div class="row">

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Avg. Calc From Date','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
			            <?php esc_html_e('Avg. Calc To Date','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"/>

                    <input type="hidden" name="it_id_order_status[]" id="it_id_order_status" value="-1">
                    <input type="hidden" name="it_orders_status[]" id="order_status" value="<?php echo esc_attr($this->it_shop_status); ?>">
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
			            <?php esc_html_e('Billing Name','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-tag"></i></span>
                    <input name="it_billing_name" id="it_billing_name" type="text"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
			            <?php esc_html_e('Billing Email','it_report_wcreport_textdomain');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-envelope-o"></i></span>
                    <input name="it_billing_email" id="it_billing_email" type="text"/>
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
