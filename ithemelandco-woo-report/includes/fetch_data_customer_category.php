<?php

	if($file_used=="sql_table")
	{
		$limit 				= $this->it_get_woo_requests('limit',3,true);
		$p 					= $this->it_get_woo_requests('p',1,true);
		$page				= $this->it_get_woo_requests('page',NULL);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status',"-1",true);
		$category_id		= $this->it_get_woo_requests('it_category_id','-1',true);
		$it_product_id			= $this->it_get_woo_requests('it_product_id','-1',true);
		$it_id_order_status	= $this->it_get_woo_requests('it_id_order_status','-1',true);

		$it_paid_customer		= $this->it_get_woo_requests('it_customers_paid','-1',true);

		$it_sort_by 			= $this->it_get_woo_requests('sort_by','-1',true);
		$it_order_by 			= $this->it_get_woo_requests('order_by','DESC',true);

		$it_paid_customer		= $this->it_get_woo_sm_requests('it_customers_paid',$it_paid_customer, "-1");
		$it_order_status		= $this->it_get_woo_sm_requests('it_orders_status',$it_order_status, "-1");
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_cat_prod_id_string = $this->it_get_woo_pli_category($category_id,$it_product_id);
		$category_id 				= "-1";

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

		//PAID CUSTOMERS
		$it_paid_customer_condition='';

		//PRODUCT ID
		$it_product_id_condition='';
		$it_cat_prod_id_string_condition='';

		//ORDER SATTUS
		$it_id_order_status_join='';
		$it_order_status_condition='';


		//CATEGORY ID
		$category_id_condition='';
		$category_id_join='';

		//ORDER STATUS
		$it_id_order_status_condition='';

		//DATE
		$it_from_date_condition='';

		//PUBLISH ORDER
		$it_publish_order_condition='';

		//HIDE ORDER STATUS
		$it_hide_os_condition ='';


		$sql_columns = "it_woocommerce_order_items.order_item_name				AS 'product_name'
					,it_woocommerce_order_items.order_item_id				AS order_item_id
					,SUM(woocommerce_order_itemmeta.meta_value)			AS 'quantity'
					,SUM(it_woocommerce_order_itemmeta6.meta_value)		AS 'total_amount'
					,it_woocommerce_order_itemmeta7.meta_value				AS product_id
					,it_postmeta_customer_user.meta_value					AS customer_id
					,DATE(shop_order.post_date) 						AS post_date
					,it_postmeta_billing_billing_email.meta_value			AS billing_email
					,it_postmeta_billing_billing_phone.meta_value			AS billing_phone
					,CONCAT(it_postmeta_billing_billing_email.meta_value,' ',it_woocommerce_order_itemmeta7.meta_value,' ',it_postmeta_customer_user.meta_value)			AS group_column
					,CONCAT(it_postmeta_billing_first_name.meta_value,' ',postmeta_billing_last_name.meta_value)		AS billing_name
					";

		$sql_joins = "{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id=it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta6 ON it_woocommerce_order_itemmeta6.order_item_id=it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta7 ON it_woocommerce_order_itemmeta7.order_item_id=it_woocommerce_order_items.order_item_id
					";

		if($category_id  && $category_id != "-1") {
				$category_id_join = "
					LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_woocommerce_order_itemmeta7.meta_value
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 				ON it_terms.term_id					=	term_taxonomy.term_id";
		}

		if($it_id_order_status  && $it_id_order_status != "-1") {
				$it_id_order_status_join = "
					LEFT JOIN  {$wpdb->prefix}term_relationships	as it_term_relationships2 	ON it_term_relationships2.object_id	=	it_woocommerce_order_items.order_id
					LEFT JOIN  {$wpdb->prefix}term_taxonomy			as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms					as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
		}


		$sql_joins.="$category_id_join $it_id_order_status_join ";

		$sql_joins .= "
		LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.id=it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_first_name ON it_postmeta_billing_first_name.post_id		=	it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as postmeta_billing_last_name ON postmeta_billing_last_name.post_id			=	it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_billing_email ON it_postmeta_billing_billing_email.post_id	=	it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_billing_billing_phone ON it_postmeta_billing_billing_phone.post_id	=	it_woocommerce_order_items.order_id
		LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_customer_user ON it_postmeta_customer_user.post_id	=	it_woocommerce_order_items.order_id";

		$sql_condition = "
					woocommerce_order_itemmeta.meta_key	= '_qty'
					AND it_woocommerce_order_itemmeta6.meta_key	= '_line_total'
					AND it_woocommerce_order_itemmeta7.meta_key 	= '_product_id'
					AND it_woocommerce_order_itemmeta7.meta_key 	= '_product_id'
					AND it_postmeta_billing_first_name.meta_key	= '_billing_first_name'
					AND postmeta_billing_last_name.meta_key		= '_billing_last_name'
					AND it_postmeta_billing_billing_email.meta_key	= '_billing_email'
					AND it_postmeta_billing_billing_phone.meta_key	= '_billing_phone'
					AND it_postmeta_customer_user.meta_key			= '_customer_user'
					";



		if ($it_from_date != NULL &&  $it_to_date !=NULL){
			$it_from_date_condition = "
					AND (DATE(shop_order.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
		}

		if($it_product_id  && $it_product_id != "-1")
			$it_product_id_condition = "
					AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_product_id .")";

		if($category_id  && $category_id != "-1")
			$category_id_condition = "
					AND it_terms.term_id IN (".$category_id .")";


		if($it_cat_prod_id_string  && $it_cat_prod_id_string != "-1")
			$it_cat_prod_id_string_condition = " AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_cat_prod_id_string .")";

		if($it_id_order_status  && $it_id_order_status != "-1")
			$it_id_order_status_condition = "
					AND terms2.term_id IN (".$it_id_order_status .")";


		if(strlen($it_publish_order)>0 && $it_publish_order != "-1" && $it_publish_order != "no" && $it_publish_order != "all"){
			$in_post_status		= str_replace(",","','",$it_publish_order);
			$it_publish_order_condition = " AND  shop_order.post_status IN ('{$in_post_status}')";
		}

		//echo $it_order_status;
		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition = " AND shop_order.post_status IN (".$it_order_status.")";

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition = " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

		if($it_paid_customer  && $it_paid_customer != '-1' and $it_paid_customer != "'-1'")
			$it_paid_customer_condition = " AND it_postmeta_billing_billing_email.meta_value IN (".$it_paid_customer.")";

		$sql_group_by = " GROUP BY  group_column";

		$sql_order_by = " ORDER BY billing_name ASC, product_name ASC, total_amount DESC";

		$sql = "SELECT $sql_columns
				FROM $sql_joins
				WHERE $sql_condition $it_from_date_condition $it_product_id_condition $category_id_condition
				$it_cat_prod_id_string_condition $it_id_order_status_condition
				$it_publish_order_condition $it_order_status_condition $it_hide_os_condition
				$it_paid_customer_condition $sql_group_by $sql_order_by";

		//echo $sql;


	}
	elseif($file_used=="data_table"){

		////ADDE IN VER4.0
		/// TOTAL ROWS VARIABLES
		$sales_qty=$total_amnt=0;


		$customer_category_array = array();

		foreach($this->results as $items){

		    $index_cols=0;
				//Product Name
            $categorys = $this->it_get_cn_product_id($items->product_id,"product_cat");
            $category = explode(",", $categorys);
            $amnt = $items->total_amount == 0 ? (0) : ($items->total_amount);

            foreach ($category as $cat){
                if(isset($customer_category_array[$items->billing_email][$cat])){
                    $customer_category_array[ $items->billing_email ][ $cat ]['cat_name'] = $cat;
                    $customer_category_array[ $items->billing_email ][ $cat ]['name'] = $items->billing_name;
                    $customer_category_array[ $items->billing_email ][ $cat ]['qty']  += $items->quantity;
                    $customer_category_array[ $items->billing_email ][ $cat ]['amnt'] += $amnt;
                }else {
                    $customer_category_array[ $items->billing_email ][ $cat ]['cat_name'] = $cat;
                    $customer_category_array[ $items->billing_email ][ $cat ]['name'] = $items->billing_name;
                    $customer_category_array[ $items->billing_email ][ $cat ]['qty']  = $items->quantity;
                    $customer_category_array[ $items->billing_email ][ $cat ]['amnt'] = $amnt;
                }
				$total_amnt+=  floatval($amnt);
				$sales_qty+= floatval($items->quantity);
            }

		}

		foreach ($customer_category_array as $customer=>$fields){
		    foreach ($fields as $data) {
		    $datatable_value.=("<tr>");
			    $display_class = '';
			    if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				    $display_class = 'display:none';
			    }
			    $datatable_value .= ( "<td style='" . $display_class . "'>" );
			    $datatable_value .= $customer;
			    $datatable_value .= ( "</td>" );

			    $display_class = '';
			    if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				    $display_class = 'display:none';
			    }
			    $datatable_value .= ( "<td style='" . $display_class . "'>" );
			    $datatable_value .= $data['name'];
			    $datatable_value .= ( "</td>" );

			    $display_class = '';
			    if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				    $display_class = 'display:none';
			    }
			    $datatable_value .= ( "<td style='" . $display_class . "'>" );
			    $datatable_value .= $data['cat_name'];
			    $datatable_value .= ( "</td>" );

			    $display_class = '';
			    if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				    $display_class = 'display:none';
			    }
			    $datatable_value .= ( "<td style='" . $display_class . "'>" );
			    $datatable_value .= $data['qty'];
			    $datatable_value .= ( "</td>" );

			    $display_class = '';
			    if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
				    $display_class = 'display:none';
			    }
			    $datatable_value .= ( "<td style='" . $display_class . "'>" );
			    $datatable_value .= $this->price($data['amnt']);
			    $datatable_value .= ( "</td>" );
		    $datatable_value.=("</tr>");
		    }
        }

		//print_r($customer_category_array);

		////ADDE IN VER4.0
		/// TOTAL ROWS
		$table_name_total= $table_name;
		$this->table_cols_total = $this->table_columns_total( $table_name_total );
		$datatable_value_total='';

		$datatable_value_total.=("<tr>");
		$datatable_value_total.="<td>$sales_qty</td>";
		$datatable_value_total.="<td>".(($total_amnt) == 0 ? $this->price(0) : $this->price($total_amnt))."</td>";
		$datatable_value_total.=("</tr>");

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
