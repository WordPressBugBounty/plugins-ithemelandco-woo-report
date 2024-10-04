<?php

	if($file_used=="sql_table")
	{
		//GET POSTED PARAMETERS
		$start				= 0;
		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$date_format = $this->it_date_format($it_from_date);

		$it_from_date=substr($it_from_date,0,strlen($it_from_date)-3);
		$it_to_date=substr($it_to_date,0,strlen($it_to_date)-3);

		$it_product_id			= $this->it_get_woo_requests('it_product_id',"-1",true);
		$category_id 		= $this->it_get_woo_requests('it_category_id','-1',true);
		$it_cat_prod_id_string = $this->it_get_woo_pli_category($category_id,$it_product_id);
		$category_id 				= "-1";

		$it_sort_by 			= $this->it_get_woo_requests('sort_by','item_name',true);
		$it_order_by 			= $this->it_get_woo_requests('order_by','ASC',true);

		$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		///////////HIDDEN FIELDS////////////
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_publish_order='no';

		$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
		//////////////////////

		//ORDER Status ID
		$it_id_order_status_condition='';
		$it_id_order_status_join ='';

		//START DATE CONDITION
		$it_from_date_condition='';

		//ORDER STATUS
		$it_order_status_condition='';

		//HIDE ORDER STATUS
		$it_hide_os_condition='';


		$sql_columns = "
		SUM(it_postmeta2.meta_value)						as total
		,COUNT(shop_order.ID) 							as quantity

		,MONTH(shop_order.post_date) 					as month_number
		,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key";

		$sql_joins="
		{$wpdb->prefix}posts as shop_order
		LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta2 on it_postmeta2.post_id = shop_order.ID";

		if($it_id_order_status != NULL  && $it_id_order_status != '-1'){
			$it_id_order_status_join = "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships2 	ON it_term_relationships2.object_id	=	shop_order.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
			LEFT JOIN  {$wpdb->prefix}terms 				as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
		}

		$sql_condition = "shop_order.post_type	= 'shop_order'";

		$sql_condition .= " AND it_postmeta2.meta_value > 0";


		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition = " AND shop_order.post_status IN (".$it_order_status.")";

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

		if ($it_from_date != NULL &&  $it_to_date !=NULL)
			$it_from_date_condition= " AND DATE_FORMAT(shop_order.post_date, '%Y-%m') BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";

		if($it_id_order_status != NULL  && $it_id_order_status != '-1'){
			$it_id_order_status_condition= "
			AND it_term_taxonomy2.taxonomy LIKE('shop_order_status')
			AND terms2.term_id IN (".$it_id_order_status .")";
		}

		$sql_group_by = " group by month_number ";
		$sql_order_by = "ORDER BY month_number";

		$sql = "SELECT $sql_columns FROM $sql_joins $it_id_order_status_join
			WHERE $sql_condition $it_order_status_condition $it_hide_os_condition
			$it_from_date_condition $it_id_order_status_condition
			$sql_group_by $sql_order_by
		";

		//echo $sql;

	}elseif($file_used=="data_table"){

		$reports		= $this->it_get_woo_requests('reports','-1',true);
		$array = array(
			"0"  => array("item_name"=>esc_html__("Order Total",			'it_report_wcreport_textdomain'),"id"=>"_order_total")
			,"1" => array("item_name"=>esc_html__("Order Tax",				'it_report_wcreport_textdomain'),"id"=>"_order_tax")
			,"2" => array("item_name"=>esc_html__("Order Discount",			'it_report_wcreport_textdomain'),"id"=>"_order_discount")
			,"3" => array("item_name"=>esc_html__("Cart Discount",			'it_report_wcreport_textdomain'),"id"=>"_cart_discount")
			,"4" => array("item_name"=>esc_html__("Order Shipping",			'it_report_wcreport_textdomain'),"id"=>"_order_shipping")
			,"5" => array("item_name"=>esc_html__("Order Shipping Tax",		'it_report_wcreport_textdomain'),"id"=>"_order_shipping_tax")
			,"6" => array("item_name"=>esc_html__("Product Sales",			'it_report_wcreport_textdomain'),"id"=>"_by_product")
		);

		if($reports != '-1'){
			$reports = explode(",", $reports);
				$new_array = array();
				foreach($reports as $key => $value)
					$new_array[] = $array[$value];
				$array = $new_array;
		}

		//foreach($this->results as $items){		    $index_cols=0;
		for($i=1; $i<=20 ; $i++){
			$datatable_value.=("<tr>");
				//Reports
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Reports';
				$datatable_value.=("</td>");


				//$items = $this->it_get_woo_items_sale($type,$items_only,$id);

				//Jan
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Jan';
				$datatable_value.=("</td>");

				//Feb
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Feb';
				$datatable_value.=("</td>");

				//Mar
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Mar';
				$datatable_value.=("</td>");

				//Apr
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Apr';
				$datatable_value.=("</td>");

				//May
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'May';
				$datatable_value.=("</td>");

				//Jun
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Jun';
				$datatable_value.=("</td>");

				//Jul
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Jul';
				$datatable_value.=("</td>");

				//Aug
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Aug';
				$datatable_value.=("</td>");

				//Sep
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Sep';
				$datatable_value.=("</td>");

				//Oct
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Oct';
				$datatable_value.=("</td>");

				//Nov
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Nov';
				$datatable_value.=("</td>");

				//Dec
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Dec';
				$datatable_value.=("</td>");

				//Total
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= 'Total';
				$datatable_value.=("</td>");





			$datatable_value.=("</tr>");
		}
	}elseif($file_used=="search_form"){
			$now_date= gmdate("Y-m-d");
			$cur_year=substr($now_date,0,4);
			$it_from_date= $cur_year."-01-01";
			$it_to_date= $cur_year."-12-31";
		?>
		<form class='alldetails search_form_report' action='' method='post'>
			<input type='hidden' name='action' value='submit-form' />
			<div class="row">

				<div class="col-md-6">
					<div>
						<?php esc_html_e('From Date','it_report_wcreport_textdomain');?>
					</div>
					<span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
					<input name="it_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick" value="<?php echo esc_html($it_from_date);?>"/>
				</div>
				<div class="col-md-6">
					<div class="awr-form-title">
						<?php esc_html_e('To Date','it_report_wcreport_textdomain');?>
					</div>
					<span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
					<input name="it_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"  value="<?php echo esc_html($it_to_date);?>"/>
				</div>

             	<div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Reports','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-flag"></i></span>
					<?php
                        $reports = array(
                            "0"=>esc_html__("Order Total",				'it_report_wcreport_textdomain'),
                            "1"=>esc_html__("Order Tax",				'it_report_wcreport_textdomain'),
                            "2"=>esc_html__("Order Discount",			'it_report_wcreport_textdomain'),
                            "3"=>esc_html__("Cart Discount",			'it_report_wcreport_textdomain'),
                            "4"=>esc_html__("Order Shipping",			'it_report_wcreport_textdomain'),
                            "5"=>esc_html__("Order Shipping Tax",		'it_report_wcreport_textdomain'),
                            "6"=>esc_html__("Product Sales",			'it_report_wcreport_textdomain')
                        );

                        $option='';
                        foreach($reports as $key => $value){
                            $option.="<option value='".$key."' >".$value."</option>";
                        }
                    ?>
                    <select name="reports[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
                        <?php
                            echo wp_kses(
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
);
                        ?>
                    </select>

                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Status','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-check"></i></span>
					<?php
                        $it_order_status=$this->it_get_woo_orders_statuses();

                        ////ADDED IN VER4.0
                        /// APPLY DEFAULT STATUS AT FIRST
                        $shop_status_selected='';
                        if($this->it_shop_status)
                            $shop_status_selected=explode(",",$this->it_shop_status);

                        $option='';
                        foreach($it_order_status as $key => $value){

	                        ////ADDED IN VER4.0
	                        /// APPLY DEFAULT STATUS AT FIRST
	                        if(is_array($shop_status_selected) && in_array($key,$shop_status_selected))
		                        $selected="selected";

	                        $option.="<option value='".$key."' $selected >".$value."</option>";

                        }
                    ?>

                    <select name="it_orders_status[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
                        <?php
                            echo wp_kses(
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
);
                        ?>
                    </select>
                    <input type="hidden" name="it_id_order_status[]" id="it_id_order_status" value="-1">
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
