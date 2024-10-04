<?php
	if(!class_exists('it_rpt_datatable_generate'))
	{
		class it_rpt_datatable_generate{

			public $results;
			//GET LAST x DAYS FOR DETECT RANK of ITEMS
			public $sql_int_last_x_days;
			public $sql_int_last_todays;
			public $top_products;
			public $sql_int_refund_products;
			public $search_form_fields='';
			public $table_cols;
			public $table_cols_total;
			public $refund_status='refunddetails_status_refunded_main';
			var $order_meta= array();
			public $month_count=0;
			public $month_start=1;
			public $data_month='';
			public $data_country='';
			public $data_state='';
			public $data_variation='';
			public $it_from_date_dashboard='';
			public $it_to_date_dashboard='';
			public $sql1='';
			public $sql2='';

			public function __construct(){

			}


			//////////////////////////////
			// GENERATE SQL
			//////////////////////////////
			public function fetch_sql($table_name,$search_fields=NULL){
				global $wpdb;

				$file_used="sql_table";
				switch($table_name){

					case 'order_summary' :
						require("fetch_data_dashboard_order_summary.php");
					break;

					case 'sale_order_status':
						require("fetch_data_dashboard_sale_order_status.php");
					break;

					case 'top_5_products':
						require("fetch_data_dashboard_top_5_products.php");
					break;

					case 'top_5_category':
						require("fetch_data_dashboard_top_5_category.php");
					break;

					case 'top_5_country':
						require("fetch_data_dashboard_top_5_country.php");
					break;

					case 'top_5_state':
						require("fetch_data_dashboard_top_5_state.php");
					break;

					case 'top_5_customer':
						require("fetch_data_dashboard_top_5_customer.php");
					break;

					case 'top_5_coupon':
						require("fetch_data_dashboard_top_5_coupon.php");
					break;

					case 'top_5_gateway':
						require("fetch_data_dashboard_top_5_gateway.php");
					break;

					case 'recent_5_order':
						require("fetch_data_dashboard_recent_5_order.php");
					break;

					case 'details' :
						require("fetch_data_details.php");
					break;

                    //CUSTOM WORK - 522
                    case 'details_combined':
                        require("fetch_data_details_combined.php");
                        break;

					//CUSTOM WORK - 12679
					case 'clinic':
						require("fetch_data_clinic.php");
						break;

					//CUSTOM WORK - 12300
					case 'details_tickera' :
						require("fetch_data_details_tickera.php");
						break;

					//CUSTOM WORK - 4186
					case 'details_full' :
						require("fetch_data_details_full.php");
						break;

                    //CUSTOM WORK - 53
                    case 'details_full_shipping' :
                        require("fetch_data_details_full_shipping.php");
                        break;

                    //CUSTOM WORK - 16
                    case 'details_full_shipping_tax' :
                        require("fetch_data_details_full_shipping_tax.php");
                        break;


					//ADDED IN VER 4.9
					case 'abandoned_cart' :
						require("fetch_data_abandoned_cart.php");
					break;
					case 'abandoned_product' :
						require("fetch_data_abandoned_product.php");
					break;

					//CUSTOM WORK - 4179
					case 'order_status_change':
						require("fetch_data_order_status_change.php");
						break;

					////ADDED IN VER4.0
					/// PRODUCT OPTIONS CUSTOM FIELDS
					case 'details_product_options':
						require("fetch_data_details_product_options.php");
					break;

					///////// BRANDS////////////
					case 'details_brands' :
						require("fetch_data_details_brands.php");
						break;

					///////// CUSTOM TAX & FIELDS////////////

					case 'details_tax_field' :
						require("fetch_data_details_tax_field.php");
					break;

					case 'brand' :
						require("fetch_data_brand.php");
					break;

					case 'custom_taxonomy' :
						require("fetch_data_custom_taxonomy.php");
					break;

					/////////////////////////////////////////


                    ///////// ORDER PER COUNTRY ////////////

					case 'details_order_country' :
						require("fetch_data_details_per_country.php");
					break;

					case 'order_per_country' :
						require("fetch_data_custom_order_per_country.php");
					break;

					//CUSTOM WORK 15092
					case 'order_per_custom_shipping' :
						require("fetch_data_order_per_custom_shipping.php");
						break;

					/////////////////////////////////////////

					case 'product' :
						require("fetch_data_product.php");
					break;

					//CUSTOM WORK 966
					case 'all_products' :
						require("fetch_data_all_products.php");
					break;


					////ADDED IN VER4.5
					//CUSTOM WORK
					case 'product_per_users' :
						require("fetch_data_product_per_users.php");
					break;

					case 'profit' :
						require("fetch_data_profit.php");
					break;

					case 'category' :
						require("fetch_data_category.php");
					break;

					////ADDED IN VER4.0
					case 'tags':
						require("fetch_data_tags.php");
					break;

					case 'customer' :
						require("fetch_data_customer.php");
					break;

					////ADDED IN VER4.0
					/// ROLE/GROUP ADDON
					case 'customer_role_total_sale':
						require("fetch_data_customer_role_total_sale.php");
						break;
					case 'customer_role_registered':
						require("fetch_data_customer_role_registered.php");
						break;
					case 'customer_role_top_products':
						require("fetch_data_customer_role_top_products.php");
						break;
					case 'customer_role_bottom_products':
						require("fetch_data_customer_role_bottom_products.php");
						break;

					case 'billingcountry' :
						require("fetch_data_billingcountry.php");
					break;

					case 'billingstate' :
						require("fetch_data_billingstate.php");
					break;

					////ADDED IN VER4.0
					case 'billingcity' :
						require("fetch_data_billingcity.php");
					break;

					case 'paymentgateway' :
						require("fetch_data_paymentgateway.php");
					break;

					case 'orderstatus' :
						require("fetch_data_orderstatus.php");
					break;

					case 'recentorder' :
						require("fetch_data_recentorder.php");
					break;

					case 'taxreport' :
						require("fetch_data_taxreport.php");
					break;


					case 'customerbuyproducts' :
						require("fetch_data_customerbuyproducts.php");
					break;

					//CUSTOM WORK 17427
					case 'customer_category' :
						require("fetch_data_customer_category.php");
						break;


					case 'refunddetails' :
						require("fetch_data_refunddetails.php");
					break;

					case 'coupon' :
						require("fetch_data_coupon.php");
					break;

					////ADDED IN VER4.0
                    /// OTHER SUMMARY
					case 'coupon_discount' :
						require("fetch_data_coupon_discount.php");
					break;
					case 'customer_analysis' :
						require("fetch_data_customer_analysis.php");
					break;
					case 'customer_order_frequently' :
						require("fetch_data_customer_order_frequently.php");
					break;
					case 'customer_min_max' :
						require("fetch_data_customer_min_max.php");
					break;
					case 'customer_no_purchased' :
						require("fetch_data_customer_no_purchased.php");
					break;


					/////ADDED IN VER4.0
                    //STOCK REPORTS
					case 'stock_zero_level' :
						require("fetch_data_stock_zero_level.php");
					break;
					case 'stock_min_level' :
						require("fetch_data_stock_min_level.php");
					break;
					case 'stock_max_level' :
						require("fetch_data_stock_max_level.php");
					break;
					case 'stock_summary_avg' :
						require("fetch_data_stock_summary_avg.php");
					break;
					//////END STOCK REPORTS/////

                    /////ADDED IN VER4.0
					//ORDER ANALYSIS
					case 'order_product_analysis' :
						require("fetch_data_order_product_analysis.php");
					break;
					case 'order_variation_analysis' :
						require("fetch_data_order_variation_analysis.php");
					break;
					//////END ORDER ANALYSIS/////


					//VARIATION
					case 'prod_per_month' :
						require("fetch_data_prod_per_month.php");
					break;

					case 'variation_per_month' :
						require("fetch_data_variation_per_month.php");
					break;

					case 'prod_per_country':
						require("fetch_data_prod_per_country.php");
					break;

					case 'prod_per_state':
						require("fetch_data_prod_per_state.php");
					break;

					case 'country_per_month':
						require("fetch_data_country_per_month.php");
					break;

					case 'payment_per_month':
						require("fetch_data_payment_per_month.php");
					break;

					case 'ord_status_per_month':
						require("fetch_data_ord_status_per_month.php");
					break;

					case 'summary_per_month':
						require("fetch_data_summary_per_month.php");
					break;

					case 'variation':
						require("fetch_data_variation.php");
					break;

					case 'stock_list':
						require("fetch_data_stock_list.php");
					break;

					case 'variation_stock':
						require("fetch_data_variation_stock.php");
					break;

					case 'projected_actual_sale':
						require("fetch_data_projected_actual_sale.php");
					break;

					case 'tax_reports':
						require("fetch_data_tax_reports.php");
					break;



					//CUSTOM WORK - 12412
					case 'product_variation_qty':
						require("fetch_data_product_variation_qty.php");
					break;

				}

				if(isset($sql)) {
					$wpdb->query('SET SQL_BIG_SELECTS = 1');
					//$sql = $wpdb->prepare($sql);
// 					echo $sql;
					//return $this->refactor_sql_queries($sql);
// 					$sql = $this->prepare();
// 					print_r($sql);die;
					//echo $sql;
					return $wpdb->get_results( $sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				}

			}
			
			public function my_safe_query($query, $params) {
				global $wpdb;
				//echo $query;
				// Prepare the query if parameters are provided
				if (!empty($params)) {
					//$params = explode(',',$params);
					
					return $wpdb->get_results($wpdb->prepare($query, array($params)));  // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
					//echo $query;return $params;
				}
				// Execute the query
				return ($query);
			}

			public function refactor_sql_queries($content) {

				// Callback function to process each match
				//$callback = function ($matches) {
					$query = $content;

					// Identify and extract variable values (this part may need customization)
					//preg_match_all('/\'([^\']+)\'/', $query, $valueMatches);
					$valueMatches[0] = array_values(array_diff($valueMatches[0],["'%Y-%m-%d'"]));
					$valueMatches[1] = array_values(array_diff($valueMatches[1],["%Y-%m-%d"]));

// return $valueMatches;				

					// Replace hardcoded values with placeholders
					$placeholders = str_replace($valueMatches[0], '%d', $query);
				//return $placeholders;
					// Construct parameters array
					$params = implode(', ', $valueMatches[0]);
// 				return $params;
			//	return $placeholders;
					return $this->my_safe_query($placeholders ,$params);

				//return $params;
			}
			

			//////////////////////////////
			// GENERATE TABLE COLUMNS
			//////////////////////////////
			public function table_columns($table_name){
				switch($table_name){

					case 'dashboard_report':
						$table_column=array();
					break;

					case 'monthly_summary':
						$table_column=array(
							array('lable'=>esc_html__('Month','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Target Sales','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Actual Sales','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Difference','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('%','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('% of Total YR PROJ','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Part Refund Amount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
//							array('lable'=>esc_html__('Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
//							array('lable'=>esc_html__('Shipping Order Tax','it_report_wcreport_textdomain'),'status'=>'show'),
//							array('lable'=>esc_html__('Total Shipping Tax','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'order_summary':
						$table_column=array(
							array('lable'=>esc_html__('Sales Order','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'sale_order_status':
						$table_column=array(
							array('lable'=>esc_html__('Order Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'top_5_products':
						$table_column=array(
							array('lable'=>esc_html__('Item Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Qty','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'top_5_category':
						$table_column=array(
							array('lable'=>esc_html__('Category Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Qty','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'top_5_country':
						$table_column=array(
							array('lable'=>esc_html__('Billing Country','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'top_5_state':
						$table_column=array(
							array('lable'=>esc_html__('Billing State','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'top_5_customer':
						$table_column=array(
							array('lable'=>esc_html__('Billing Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'top_5_coupon':
						$table_column=array(
							array('lable'=>esc_html__('Coupon Code','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Coupon Used Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'top_5_gateway':
						$table_column=array(
							array('lable'=>esc_html__('Payment Method','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'recent_5_order':
						$table_column=array(
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Items','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Payment Method','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Shipping Method','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Currency','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Cart Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Shipping Amt..','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					break;


					case 'details':
						$table_column=[];
					break;

                    //CUSTOM WORK - 522
                    case 'details_combined':
                        $table_column=[];

                        break;


					//CUSTOM WORK - 12679
					case 'clinic':
					    $table_column=array(
                            array('lable'=>esc_html__('Clinic Abbreviation','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=>esc_html__('Number of Sales','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
                        );
						break;

					//CUSTOM WORK - 12300
					case 'details_tickera':
						$table_column=array(
						);
						break;
						$table_column=array(
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Change Status Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Status.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					//CUSTOM WORK - 4186
					case 'details_full' :
						$table_column=array(
						);
					break;


                    //CUSTOM WORK - 53
                    case 'details_full_shipping' :
                        $table_column=array(
                        );
                        break;

                    //CUSTOM WORK - 16
                    case 'details_full_shipping_tax' :
                        $table_column=array(
                        );
                        break;

					//ADDED IN VER 4.9
					case 'abandoned_cart':
						$table_column=array(
						);
						break;
					case 'abandoned_product':
						$table_column=array(
						);
						break;


					//CUSTOM WORK - 4179
					case 'order_status_change':
						$table_column=array(
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Change Status Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Status.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					break;


					////ADDED IN VER4.0
					/// PRODUCT OPTIONS CUSTOM FIELDS
					case 'details_product_options':
						$table_column=array(
						);
					break;

					/////////////BRANDS////////////////
					case 'details_brands':
						$table_column=array(
						);
						break;

					/////////////CUSTOM TAX & FIELDS////////////////
					case 'details_tax_field':
						$table_column=array(
						);
					break;

					case 'brand':
						$table_column=array(
							array('lable'=>esc_html__('Brand Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Quantity','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					case 'custom_taxonomy':
						$table_column=array(
							array('lable'=>esc_html__('Category Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Quantity','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;
					///////////////////////

                    ///////// ORDER PER COUNTRY ////////////

					case 'details_order_country' :
						$table_column=array(
						);
					break;

					case 'order_per_country' :
						$table_column=array(
							array('lable'=>esc_html__('Country','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Number of Items sold','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Sale','it_report_wcreport_textdomain'),'status'=>'currency'),
                            array('lable'=>esc_html__('Coupon','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Shipping','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Refunds','it_report_wcreport_textdomain'),'status'=>'currency'),
                            array('lable'=>esc_html__('Finaly Sale Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					//CUSTOM WORK 15092
					case 'order_per_custom_shipping' :
						$table_column=array(
							array('lable'=>esc_html__('Shipping Address','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Number of Items sold','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Sale','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					/////////////////////////////////////////



					//ALL DETAIL

					case 'product':
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Categories','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Tags','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Current Stock','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;


					//CUSTOM WORK 966
					case 'all_products':
					$table_column=array(
						array('lable'=>esc_html__('Product ID','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Price','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Tag','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Current Stock','it_report_wcreport_textdomain'),'status'=>'show'),
						array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						array('lable'=>esc_html__('C.O.G','it_report_wcreport_textdomain'),'status'=>'currency'),
						array('lable'=>esc_html__('Profit','it_report_wcreport_textdomain'),'status'=>'currency'),
						//array('lable'=>esc_html__('Edit','it_report_wcreport_textdomain'),'status'=>'show'),
				);
				break;


					////ADDED IN VER4.5
					//CUSTOM WORK
					case 'product_per_users':
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Categories','it_report_wcreport_textdomain'),'status'=>'show'),
							///array('lable'=>esc_html__('Tags','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							//array('lable'=>esc_html__('Current Stock','it_report_wcreport_textdomain'),'status'=>'show'),
							//array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'profit':
						$table_column=array(
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit','it_report_wcreport_textdomain'),'status'=>'currency'),
							//array('lable'=>esc_html__('Avg Profit/Order','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;


					case 'category':
						$table_column=array(
							array('lable'=>esc_html__('Category Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Quantity','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					////ADDED IN VER4.0
					case 'tags':
						$table_column=array(
							array('lable'=>esc_html__('Tag Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Quantity','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					case 'customer':
						$table_column=array(
							array('lable'=>esc_html__('Billing First Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Last Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency')
						);
					break;

					////ADDED IN VER4.0
					/// ROLE/GROUP ADDON
					case 'customer_role_total_sale':
						$table_column=array(
							array('lable'=>esc_html__('Role Name','it_report_wcreport_textdomain'),'status'=>'show'),
//							array('lable'=>esc_html__('User Name','it_report_wcreport_textdomain'),'status'=>'show'),
//							array('lable'=>esc_html__('Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency')
						);
						break;
					case 'customer_role_registered':
						$table_column=array(
							array('lable'=>esc_html__('User Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('User Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Role Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('First Order','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('First Order Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('First Order Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;
					case 'customer_role_top_products':
						$table_column=array(
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Sales Qty','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;
					case 'customer_role_bottom_products':
						$table_column=array(
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Sales Qty','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'billingcountry':
						$table_column=array(
							array('lable'=>esc_html__('Billing Country','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;
					case 'billingstate':
						$table_column=array(
							array('lable'=>esc_html__('Billing State','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Country','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

                    ////ADDED IN VER4.0
					case 'billingcity':
						$table_column=array(
							array('lable'=>esc_html__('Billing City','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing State','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Country','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					case 'paymentgateway':
						$table_column=array(
							array('lable'=>esc_html__('Payment Method','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;
					case 'orderstatus':
						$table_column=array(
							array('lable'=>esc_html__('Order Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					case 'recentorder':
						$table_column=array(
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Items','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Payment Method','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Shipping Method','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Currency','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Order Discount Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Cart Discount Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total Discount Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Shipping Amt..','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),

						);
					break;
					case 'taxreport':
						$table_column=array(
							array('lable'=>esc_html__('Tax Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Tax Rate','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							//array('lable'=>esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Shipping Tax','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Order Tax','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total Tax','it_report_wcreport_textdomain'),'status'=>'currency'),

					);
					break;

					case 'customerbuyproducts':
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Customer Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Customer Email','it_report_wcreport_textdomain'),'status'=>'show'),
							//4103
							array('lable'=>esc_html__('Customer Phone','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Current Stock','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					//CUSTOM WORK 17427
					case 'customer_category':
						$table_column=array(
							array('lable'=>esc_html__('Customer Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Customer Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Category','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_status_refunded_main':
						$table_column=array(
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_status_refunded':
						$table_column=array(
							array('lable'=>esc_html__('Refunded By','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_status_daily':
						$table_column=array(
							array('lable'=>esc_html__('Refund Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_status_monthly':
						$table_column=array(
							array('lable'=>esc_html__('Month','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_status_yearly':
						$table_column=array(
							array('lable'=>esc_html__('Year','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_part_refunded_main':
						$table_column=array(
							array('lable'=>esc_html__('Refund ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund By','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Note','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_part_refunded_order_id':
						$table_column=array(
							array('lable'=>esc_html__('Order ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Status','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_part_refunded':
						$table_column=array(
							array('lable'=>esc_html__('Refunded By','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_part_refunded_daily':
						$table_column=array(
							array('lable'=>esc_html__('Refund Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_part_refunded_monthly':
						$table_column=array(
							array('lable'=>esc_html__('Month','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'refunddetails_part_refunded_yearly':
						$table_column=array(
							array('lable'=>esc_html__('Year','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'coupon':
						$table_column=array(
							array('lable'=>esc_html__('Coupon Code','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Coupon Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Coupon Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					////ADDED IN VER4.0
					/// OTHER SUMMARY
					case 'coupon_discount':
						$table_column=array(
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Coupon Code','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Discount Type','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Qty','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Discount Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;
					case 'customer_analysis':
						$table_column=array(
							array('lable'=>esc_html__('Months','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Sales Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('New Customer Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Repeat Customer Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('New Customer Sales Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Repeat Customer Sales Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;
					case 'customer_order_frequently':
						$table_column=array(
							array('lable'=>esc_html__('Report Item','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					break;
					case 'customer_min_max':
						$table_column=array(
							array('lable'=>esc_html__('Billing First Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Last Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Min Price','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Max Price','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					break;
					case 'customer_no_purchased':
						$table_column=array(
							array('lable'=>esc_html__('Billing First Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Last Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Billing Email','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Last Order Date','it_report_wcreport_textdomain'),'status'=>'show'),
							//array('lable'=>esc_html__('Wake Up','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					break;

					/////ADDED IN VER4.0
					//STOCK REPORTS
					case 'stock_zero_level' :
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Last Sales Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Stock Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Actions','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					case 'stock_min_level' :
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Last Sales Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Stock Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Actions','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					case 'stock_max_level' :
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Last Sales Date','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Stock Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Actions','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;

					case 'stock_summary_avg' :
						$table_column=array();
					break;
					//////END STOCK REPORTS/////

                    /////ADDED IN VER4.0
					//ORDER ANALYSIS
					case 'order_product_analysis' :
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Number of Order.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Price','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;
					case 'order_variation_analysis' :
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Variation','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Number of Order.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Price','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
					break;
					//////END ORDER ANALYSIS/////

					/////////////////CROSTABS///////////////
					case 'prod_per_month':
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Payment Method','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'variation_per_month':
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Payment Method','it_report_wcreport_textdomain'),'status'=>'show'),

					);
					break;
					case 'prod_per_country':
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						//Make columns with QUERY
					break;
					case 'prod_per_state':
						$table_column=array(
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						//Make columns with QUERY
					break;

					case 'country_per_month':
						$table_column=array(
							array('lable'=>esc_html__('Country Name','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;
					case 'payment_per_month':
						$table_column=array(
							array('lable'=>esc_html__('Payment Gateway','it_report_wcreport_textdomain'),'status'=>'show'),

					);
					break;
					case 'ord_status_per_month':
						$table_column=array(
							array('lable'=>esc_html__('Status Name','it_report_wcreport_textdomain'),'status'=>'show'),

					);
					break;
					case 'summary_per_month':
						$table_column=array(
							array('lable'=>esc_html__('Reports','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					break;
					//end of cross tabs

					case 'variation':
						$table_column=array(
							array('lable'=>esc_html__('Product ID','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product SKU','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Tag','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Current Stock','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit','it_report_wcreport_textdomain'),'status'=>'currency'),
							//array('lable'=>esc_html__('Edit','it_report_wcreport_textdomain'),'status'=>'show'),
					);
					break;

					case 'stock_list':
						$table_column=array();
					break;
					case 'variation_stock':
						$table_column=array();
					break;

					case 'projected_actual_sale':
						$table_column=array(
							array('lable'=>esc_html__('Month','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Target Sales','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Actual Sales','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Difference','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('%','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Refund Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Part Refund Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total Discount Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
					);
					break;

					case 'tax_reports':
						$table_column=array();
					break;


					//CUSTOM WORK - 12412
					case 'product_variation_qty':
						$table_column=array(
							array('lable'=>esc_html__('Simple Product','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Qty','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('*','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Variation','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Variation','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Qty','it_report_wcreport_textdomain'),'status'=>'show'),
						);
					break;

				}

				return $table_column;
			}


			////ADDED IN VER4.0
            /// TOTAL ROWS AND OTHER ROWS
			//////////////////////////////
			// GENERATE TABLE TOTAL COLUMNS
			//////////////////////////////
			public function table_columns_total($table_name){
				switch($table_name){

					case 'details_no_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'details_with_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Rate','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Discount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;




					//CUSTOM WORK - 522
                    case 'details_combined':
                        $table_column=array(
                            array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=> esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=> esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=> esc_html__('Rate','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=> esc_html__('Prod. Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=> esc_html__('Prod. Discount','it_report_wcreport_textdomain'),'status'=>'show'),
                            array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
                        );
                        break;



					//CUSTOM WORK - 12300
                    case 'details_tickera_no_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					//CUSTOM WORK - 12679
					case 'clinic':
						$table_column=array(
							array('lable'=>esc_html__('Number of Sales','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

                    //CUSTOM WORK - 12300
					case 'details_tickera_with_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Rate','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Discount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					/////////////BRANDS////////////////
					case 'details_brands_no_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'details_brands_with_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Rate','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Discount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show')
						);
						break;

					/////////////CUSTOM TAX & FIELDS////////////////
					case 'details_tax_field_no_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'details_tax_field_with_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Rate','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Discount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show')
						);
						break;

					case 'brand':
						$table_column=array(
							array('lable'=>esc_html__('Brand Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'custom_taxonomy':
						$table_column=array(
							array('lable'=>esc_html__('Category Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;
					///////////////////////

					///////// ORDER PER COUNTRY ////////////
					case 'details_order_country_no_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'details_order_country_with_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Rate','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Discount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show')
						);
						break;

					case 'order_per_country' :
						$table_column=array(
							array('lable'=>esc_html__('Country Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Number of Items sold','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Sale','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Coupon Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Refunds Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Finaly Sale Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					    //CUSTOM WORK 15092
						case 'order_per_custom_shipping' :
						$table_column=array(
							array('lable'=>esc_html__('Number of Items sold','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Sale','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					/////////////////////////////////////////


					///////// PRODUCT OPTIONS ALL ORDER ////////////
					case 'details_product_options_no_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'details_product_options_with_items':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Rate','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Prod. Discount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;



					//ALL DETAIL

					case 'product':
						$table_column=array(
							array('lable'=>esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

						//CUSTOM WORK 966
						case 'all_products':
						$table_column=array(
							array('lable'=>esc_html__('Variation Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

                    ////ADDED IN VER4.5
					//CUSTOM WORK
					case 'product_per_users':
						$table_column=array(
							array('lable'=>esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							//array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'profit':
						$table_column=array(
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Profit Amt','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'category':
						$table_column=array(
							array('lable'=>esc_html__('Category Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					////ADDED IN VER4.0
					case 'tags':
						$table_column=array(
							array('lable'=>esc_html__('Category Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'customer':
						$table_column=array(
							array('lable'=>esc_html__('Customer Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency')
						);
						break;

					case 'billingcountry':
						$table_column=array(
							array('lable'=>esc_html__('Country Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;
					case 'billingstate':
						$table_column=array(
							array('lable'=>esc_html__('Billing State Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					////ADDED IN VER4.0
					case 'billingcity':
						$table_column=array(
							array('lable'=>esc_html__('Billing City Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'paymentgateway':
						$table_column=array(
							array('lable'=>esc_html__('Payment Method Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'orderstatus':
						$table_column=array(
							array('lable'=>esc_html__('Order Status Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'recentorder':
						$table_column=array(
							array('lable'=> esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Discount Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Shipping Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>	esc_html__('Order Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Total Tax Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Part Refund Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=> esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'taxreport':
						$table_column=array(
							array('lable'=>esc_html__('Tax Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Shipping Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							//array('lable'=>esc_html__('Gross Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Net Amt.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Shipping Tax','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Tax','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Tax','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'customerbuyproducts':
						$table_column=array(
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;


					//CUSTOM WORK 17427
					case 'customer_category':
						$table_column=array(
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'refunddetails':
						$table_column=array(
							array('lable'=>esc_html__('Result Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Order Counts','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Refund Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					case 'coupon':
						$table_column=array(
							array('lable'=>esc_html__('Result Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Coupon Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Coupon Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					////ADDED IN VER4.0
					/// OTHER SUMMARY
					case 'coupon_discount':
						$table_column=array(
							array('lable'=>esc_html__('Result Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Product Qty','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Discount Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;
					case 'customer_analysis':
						$table_column=array(
							array('lable'=>esc_html__('Months Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Sales Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Total Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('New Customer Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Repeat Customer Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('New Customer Sales Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Repeat Customer Sales Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;
					case 'customer_order_frequently':
						$table_column=array(
							array('lable'=>esc_html__('Report Item','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;
					case 'customer_min_max':
						$table_column=array(
							array('lable'=>esc_html__('Customer Count','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;
					case 'customer_no_purchased':
						$table_column=array(
							array('lable'=>esc_html__('Customer Count','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Order Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Amount','it_report_wcreport_textdomain'),'status'=>'show'),
						);
						break;

					/////ADDED IN VER4.0
					//STOCK REPORTS
					case 'stock_zero_level' :
						$table_column=array(
							array('lable'=>esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Stock','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'stock_min_level' :
						$table_column=array(
							array('lable'=>esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Stock','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'stock_max_level' :
						$table_column=array(
							array('lable'=>esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Total Stock','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

					case 'stock_summary_avg' :
						$table_column=array(
							array('lable'=>esc_html__('Product Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Current Stock Qty','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;
					//////END STOCK REPORTS/////


					case 'variation':
						$table_column=array(
							array('lable'=>esc_html__('Variation Count','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Sales Qty.','it_report_wcreport_textdomain'),'status'=>'show'),
							array('lable'=>esc_html__('Amount','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('C.O.G Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
							array('lable'=>esc_html__('Profit Amt.','it_report_wcreport_textdomain'),'status'=>'currency'),
						);
						break;

				}

				return $table_column;
			}

			//////////////////////////////
			// MAIN FUNCTION OF TABLE HTML
			//////////////////////////////
			public function table_html($table_name,$search_fields=NULL){
				$table_name_total='';
				$product_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_product_post_per_page',5);
				$order_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'recent_post_per_page',5);
				$category_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_category_post_per_page',5);
				$country_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_country_post_per_page',5);
				$state_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_state_post_per_page',5);
				$gateway_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_gateway_post_per_page',5);
				$coupon_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_coupon_post_per_page',5);
				$customer_count=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'top_customer_post_per_page',5);


				$page_titles=array(
					'dashboard_report'		=> esc_html__( "Summary",'it_report_wcreport_textdomain'),
					'monthly_summary'		=> esc_html__( "Monthly Summary",'it_report_wcreport_textdomain'),
					'order_summary'			=> esc_html__( "Order Summary",'it_report_wcreport_textdomain'),
					'sale_order_status'		=> esc_html__( "Sales Order Status",'it_report_wcreport_textdomain'),
					'top_5_products'		=> esc_html__( "Top 5 Products",'it_report_wcreport_textdomain'),
					'top_5_country'			=> esc_html__( "Top 5 Billing Country",'it_report_wcreport_textdomain'),
					'top_5_category'		=> esc_html__( "Top 5 Categroy",'it_report_wcreport_textdomain'),
					'top_5_state'			=> esc_html__( "Top 5 Billing State",'it_report_wcreport_textdomain'),
					'top_5_customer'		=> esc_html__( "Top 5 Customers",'it_report_wcreport_textdomain'),
					'top_5_coupon'			=> esc_html__( "Top 5 Coupon",'it_report_wcreport_textdomain'),
					'top_5_gateway'			=> esc_html__( "Top 5 Payment Gateway",'it_report_wcreport_textdomain'),
					"recent_5_order"		=> esc_html__( "Recent 5 Orders",'it_report_wcreport_textdomain'),
					'details'				=> esc_html__( "All Orders",'it_report_wcreport_textdomain'),

                    //CUSTOM WORK - 522
                    'details_combined'				=> esc_html__( "Combined Orders",'it_report_wcreport_textdomain'),

					//CUSTOM WORK - 12679
					'clinic'				=> esc_html__( "Total Sales per Clinic",'it_report_wcreport_textdomain'),

                    //CUSTOM WORK - 12300
					'details_tickera'				=> esc_html__( "All Orders Tickera",'it_report_wcreport_textdomain'),

                    //CUSTOM WORK - 4186
					'details_full'				=> esc_html__( "All Orders Billing",'it_report_wcreport_textdomain'),


                    //CUSTOM WORK - 53
					'details_full_shipping'				=> esc_html__( "All Orders Billing/Shipping",'it_report_wcreport_textdomain'),

                    //CUSTOM WORK - 16
					'details_full_shipping_tax'				=> esc_html__( "All Orders Billing|Shipping Tax",'it_report_wcreport_textdomain'),

					//ADDED IN VER 4.9
					'abandoned_cart'				=> esc_html__( "Abandoned Carts",'it_report_wcreport_textdomain'),
					'abandoned_product'				=> esc_html__( "Abandoned Products",'it_report_wcreport_textdomain'),

					//CUSTOM WORK - 4179
					'order_status_change'				=> esc_html__( "Status Change",'it_report_wcreport_textdomain'),

					////ADDED IN VER4.0
                    /// ORDER PER COUNTRY
                    'details_order_country'	=> esc_html__( "All Orders per Country",'it_report_wcreport_textdomain'),
					'order_per_country'		=> esc_html__( "Order / Country",'it_report_wcreport_textdomain'),

					//CUSTOM WORK 15092
					'order_per_custom_shipping'		=> esc_html__( "Order / Shipping",'it_report_wcreport_textdomain'),
					////ADDED IN VER4.0
                    /// PRODUCT OPTIONS CUSTOM FIELDS

					'product'				 => esc_html__( "Product",'it_report_wcreport_textdomain'),

					//CUSTOM WORK 966
					'all_products'				 => esc_html__( "All Products",'it_report_wcreport_textdomain'),

        	'profit'				 => esc_html__( "Profit",'it_report_wcreport_textdomain'),
					'category'				=> esc_html__( "Category",'it_report_wcreport_textdomain'),
					////ADDED IN VER4.0
					'tags'				=> esc_html__( "Tags",'it_report_wcreport_textdomain'),
					'stock_zero_level'				=> esc_html__( "Zero Level Stock",'it_report_wcreport_textdomain'),
					'stock_min_level'				=> esc_html__( "Minimum Level Stock",'it_report_wcreport_textdomain'),
					'stock_max_level'				=> esc_html__( "Most Stocked",'it_report_wcreport_textdomain'),
					'stock_summary_avg'				=> esc_html__( "Summary Stock Planner Based On Average Sales",'it_report_wcreport_textdomain'),

					'customer'				=> esc_html__( "Customer",'it_report_wcreport_textdomain'),
					'billingcountry'		  => esc_html__( "Billing Country",'it_report_wcreport_textdomain'),
					'billingstate'			=> esc_html__( "Billing State",'it_report_wcreport_textdomain'),
					'billingcity'			=> esc_html__( "Billing City",'it_report_wcreport_textdomain'),
					'paymentgateway'		  => esc_html__( "Payment Gateway",'it_report_wcreport_textdomain'),
					'orderstatus'			 => esc_html__( "Order Status",'it_report_wcreport_textdomain'),
					'recentorder'			 => esc_html__( "Recent Order",'it_report_wcreport_textdomain'),
					'taxreport' 			   => esc_html__( "Tax Report",'it_report_wcreport_textdomain'),
					'customerbuyproducts'	 => esc_html__( "Custom Member Buy Products",'it_report_wcreport_textdomain'),

					//CUSTOM WORK
					'customer_category'	 => esc_html__( "Customer Buy Category",'it_report_wcreport_textdomain'),


					'refunddetails' 		   => esc_html__( "Refund Orders",'it_report_wcreport_textdomain'),
					'coupon' 				  => esc_html__( "Coupon",'it_report_wcreport_textdomain'),
					////ADDED IN VER4.0
                    /// OTHER SUMMARY
                    'coupon_discount' 				  => esc_html__( "Coupon Discount Type Reporting",'it_report_wcreport_textdomain'),
					'customer_analysis' 				  => esc_html__( "New Customer/Repeat Customer Analysis",'it_report_wcreport_textdomain'),
					'customer_order_frequently' 	=> esc_html__( "Top n Customer Report Who Orders Frequently",'it_report_wcreport_textdomain'),
					'customer_min_max' 	=> esc_html__( "Customer Min-Max",'it_report_wcreport_textdomain'),
					'customer_no_purchased' 	=> esc_html__( "Customer Who Has Not Purchased",'it_report_wcreport_textdomain'),

					////ADDED IN VER4.0
					/// ORDER ANALYSIS
					'order_product_analysis' 				  => esc_html__( "Order Qty. Analysis Simple",'it_report_wcreport_textdomain'),
					'order_variation_analysis' 				  => esc_html__( "Order Qty. Analysis Variable",'it_report_wcreport_textdomain'),

					'stock_list'			  => esc_html__( "Stock List",'it_report_wcreport_textdomain'),
					'projected_actual_sale'   => esc_html__( "Target Sale vs Actual Sale",'it_report_wcreport_textdomain'),
					'tax_reports'			 => esc_html__( "Tax Reports",'it_report_wcreport_textdomain'),

					//CUSTOM WORK - 12412
					'product_variation_qty'			 => esc_html__( "Product Qty",'it_report_wcreport_textdomain'),


					'monthly_summary'	     => esc_html__( "Monthly Summary",'it_report_wcreport_textdomain'),
				);
				$page_titles=apply_filters( 'it_report_wcreport_page_titles', $page_titles);

				$except_table=array("monthly_summary","order_summary","sale_order_status","top_5_products","top_5_category","top_5_country","top_5_state","top_5_customer","top_5_coupon","top_5_gateway","recent_5_order");

				$chart_table=array("order_summary","sale_order_status","top_5_products","top_5_category","top_5_country","top_5_state","top_5_customer","top_5_coupon","top_5_gateway");


				if($search_fields!=NULL || in_array($table_name,$except_table))
				{   $this->search_form_fields=$search_fields;
					$this->results =$this->fetch_sql($table_name,$search_fields);
				}

				/**************TABLE COLUMNS & CONTROLS************/


				///////////////////////////////
				// FETCH REPORT COLUMNS :
				// 1- There are two types of columns for refunddetails
				// 2- There are dynamic columns : The columns will be changed in code
				// 3- General mode

				$refund_type='';
				if($table_name=='refunddetails')
				{
					$refund_type=$this->refund_status;
					$this->table_cols =$this->table_columns($refund_type);
				}else if( in_array ( $table_name,array('details','details_combined','details_full','details_full_shipping','details_full_shipping_tax','abandoned_cart','abandoned_product','details_product_options','product','all_products','product_per_users','category','tags','details_order_country','details_brands','details_tax_field','prod_per_month','variation_per_month','prod_per_country','prod_per_state','country_per_month','payment_per_month','ord_status_per_month','summary_per_month','variation','stock_summary_avg','stock_list','variation_stock','tax_reports','customer_role_total_sale','customer_role_top_products','customer_role_bottom_products','details_tickera','stock_zero_level','stock_max_level') ) )
				{
					//$this->table_cols =$this->table_columns($table_name);
				}else{

					$this->table_cols = $this->table_columns($table_name);
				}
				////ADDE IN VER4.0
                /// TOTAL ROW

				//print_r($this->table_cols);

				$cols_controls='';
				$table_cols='';

				$i=0;
				if($search_fields!=NULL  || in_array($table_name,$except_table))
				{
					foreach($this->table_cols as $cols)
					{

						$checked='checked'; $display='';
						$currency_class='';
						if ($cols['status']=='currency'){ $currency_class='currency';}

						if ($cols['status']=='hide'){ $checked='';$display='display:none;';}
						$cols_controls.= '<label><input type="checkbox" '.$checked.'  data-column="'.$i++.'">'.$cols['lable'].'</label>';
						$table_cols.= '<th style="'.$display.'" data-class="'.$currency_class.'" data-id= "'.$cols['lable'].'"><div>'.$cols['lable'].'</div></th>';

					}
				}
				//echo $table_cols;

				/**************TABLE FETCH DATAS OF TABLE************/
				if($search_fields!=NULL  || in_array($table_name,$except_table) || $table_name=='dashboard_report')
				{
					//$it_null_val = $this->price(0);
					$it_null_val = 0;
					$datatable_value='';


					$file_used="data_table";
					switch($table_name){

						case 'dashboard_report':
							require("fetch_data_dashboard_report.php");
						break;

						case 'monthly_summary':
							require("fetch_data_dashboard_monthly_summary.php");
						break;

						case 'order_summary':
							require("fetch_data_dashboard_order_summary.php");
						break;

						case 'sale_order_status':
							require("fetch_data_dashboard_sale_order_status.php");
						break;

						case 'top_5_products':
							require("fetch_data_dashboard_top_5_products.php");
						break;

						case 'top_5_category':
							require("fetch_data_dashboard_top_5_category.php");
						break;

						case 'top_5_country':
							require("fetch_data_dashboard_top_5_country.php");
						break;

						case 'top_5_state':
							require("fetch_data_dashboard_top_5_state.php");
						break;

						case 'top_5_customer':
							require("fetch_data_dashboard_top_5_customer.php");
						break;

						case 'top_5_coupon':
							require("fetch_data_dashboard_top_5_coupon.php");
						break;

						case 'top_5_gateway':
							require("fetch_data_dashboard_top_5_gateway.php");
						break;

						case 'recent_5_order':
							require("fetch_data_dashboard_recent_5_order.php");
						break;

						case 'details':
							require("fetch_data_details.php");
						break;

                        //CUSTOM WORK - 522
                        case 'details_combined':
                            require("fetch_data_details_combined.php");
                            break;


						//CUSTOM WORK - 12679
						case 'clinic':
							require("fetch_data_clinic.php");
						break;

						//CUSTOM WORK - 12300
						case 'details_tickera':
							require("fetch_data_details_tickera.php");
						break;

						//CUSTOM WORK - 4186
						case 'details_full' :
						    require("fetch_data_details_full.php");
						break;

                        //CUSTOM WORK - 53
                        case 'details_full_shipping' :
                            require("fetch_data_details_full_shipping.php");
                            break;

                        //CUSTOM WORK - 16
                        case 'details_full_shipping_tax' :
                            require("fetch_data_details_full_shipping_tax.php");
                            break;

						//ADDED IN VER 4.9
						case 'abandoned_cart' :
							require("fetch_data_abandoned_cart.php");
							break;
						case 'abandoned_product' :
							require("fetch_data_abandoned_product.php");
							break;

						//CUSTOM WORK - 4179
						case 'order_status_change':
							require("fetch_data_order_status_change.php");
						break;


						////ADDED IN VER4.0
                        /// PRODUCT OPTIONS CUSTOM FIELDS
						case 'details_product_options':
							require("fetch_data_details_product_options.php");
						break;

						///////// BRANDS////////////
						case 'details_brands' :
							require("fetch_data_details_brands.php");
						break;

						///////// CUSTOM TAX & FIELDS////////////
						case 'details_tax_field' :
							require("fetch_data_details_tax_field.php");
						break;

						case 'brand' :
							require("fetch_data_brand.php");
						break;

						case 'custom_taxonomy' :
							require("fetch_data_custom_taxonomy.php");
						break;

						/////////////////////////////////////////

                        ///////// ORDER PER COUNTRY ////////////

    					case 'details_order_country' :
    						require("fetch_data_details_per_country.php");
    					break;

    					case 'order_per_country' :
    						require("fetch_data_custom_order_per_country.php");
    					break;

						//CUSTOM WORK 15092
						case 'order_per_custom_shipping' :
							require("fetch_data_order_per_custom_shipping.php");
							break;

    					/////////////////////////////////////////

						//ALL DETAILS
						case 'product':
							require("fetch_data_product.php");
						break;

						//CUSTOM WORK 966
						case 'all_products' :
							require("fetch_data_all_products.php");
						break;

						////ADDED IN VER4.5
						//CUSTOM WORK
						case 'product_per_users' :
							require("fetch_data_product_per_users.php");
						break;

						case 'profit':
							require("fetch_data_profit.php");
						break;
						case 'category':
							require("fetch_data_category.php");
						break;

						////ADDED IN VER4.0
						case 'tags':
							require("fetch_data_tags.php");
						break;

						case 'customer':
							require("fetch_data_customer.php");
						break;

						////ADDED IN VER4.0
						/// ROLE/GROUP ADDON
						case 'customer_role_total_sale':
							require("fetch_data_customer_role_total_sale.php");
							break;
						case 'customer_role_registered':
							require("fetch_data_customer_role_registered.php");
							break;
						case 'customer_role_top_products':
							require("fetch_data_customer_role_top_products.php");
							break;
						case 'customer_role_bottom_products':
							require("fetch_data_customer_role_bottom_products.php");
							break;

						case 'billingcountry':
							require("fetch_data_billingcountry.php");
						break;
						case 'billingstate':
							require("fetch_data_billingstate.php");
						break;
						////ADDED IN VER4.0
						case 'billingcity':
							require("fetch_data_billingcity.php");
						break;
						case 'paymentgateway':
							require("fetch_data_paymentgateway.php");
						break;
						case 'orderstatus':
							require("fetch_data_orderstatus.php");
						break;
						case 'recentorder':
							require("fetch_data_recentorder.php");
						break;
						case 'taxreport':
							require("fetch_data_taxreport.php");
						break;
						case 'tax_reports':
							require("fetch_data_tax_reports.php");
							break;

						case 'customerbuyproducts':
							require("fetch_data_customerbuyproducts.php");
						break;

						//CUSTOM WORK 17427
						case 'customer_category' :
							require("fetch_data_customer_category.php");
							break;

						case 'refunddetails' :
							require("fetch_data_refunddetails.php");
						break;

						case 'coupon' :
							require("fetch_data_coupon.php");
						break;

						////ADDED IN VER4.0
                        /// OTHER SUMMARY
						case 'coupon_discount' :
							require("fetch_data_coupon_discount.php");
						break;
						case 'customer_analysis' :
							require("fetch_data_customer_analysis.php");
						break;
						case 'customer_order_frequently' :
						    require("fetch_data_customer_order_frequently.php");
						break;
						case 'customer_min_max' :
						    require("fetch_data_customer_min_max.php");
						break;
						case 'customer_no_purchased' :
						    require("fetch_data_customer_no_purchased.php");
						break;

						case 'prod_per_month' :
							require("fetch_data_prod_per_month.php");
						break;

						/////ADDED IN VER4.0
						//STOCK REPORTS
						case 'stock_zero_level' :
							require("fetch_data_stock_zero_level.php");
						break;

						case 'stock_min_level' :
							require("fetch_data_stock_min_level.php");
						break;

						case 'stock_max_level' :
							require("fetch_data_stock_max_level.php");
						break;

						case 'stock_summary_avg' :
							require("fetch_data_stock_summary_avg.php");
						break;
                        //////END STOCK REPORTS/////

						/////ADDED IN VER4.0
						//ORDER ANALYSIS
						case 'order_product_analysis' :
							require("fetch_data_order_product_analysis.php");
						break;
						case 'order_variation_analysis' :
							require("fetch_data_order_variation_analysis.php");
						break;
						//////END ORDER ANALYSIS/////

						case 'variation_per_month' :
							require("fetch_data_variation_per_month.php");
						break;

						case 'prod_per_country':
							require("fetch_data_prod_per_country.php");
						break;

						case 'prod_per_state':
							require("fetch_data_prod_per_state.php");
						break;

						case 'country_per_month':
							require("fetch_data_country_per_month.php");
						break;

						case 'payment_per_month':
							require("fetch_data_payment_per_month.php");
						break;

						case 'ord_status_per_month':
							require("fetch_data_ord_status_per_month.php");
						break;

						case 'summary_per_month':
							require("fetch_data_summary_per_month.php");
						break;

						case 'variation':
							require("fetch_data_variation.php");
						break;

						case 'stock_list':
							require("fetch_data_stock_list.php");
						break;

						case 'variation_stock':
							require("fetch_data_variation_stock.php");
						break;

						case 'projected_actual_sale':
							require("fetch_data_projected_actual_sale.php");
						break;




						//CUSTOM WORK - 12412
						case 'product_variation_qty':
							require("fetch_data_product_variation_qty.php");
						break;

					}
				}

				/**************TABLE HTML************/
				if(($search_fields!=NULL && $table_name!='dashboard_report')   || (in_array($table_name,$except_table)))
				{
				?>
                <div class="awr-box">
                    <div class="awr-title">
                        <h3>

                            <?php
                                //esc_html_e('Result','it_report_wcreport_textdomain');
                                echo esc_attr($page_titles[$table_name]);
                            ?>
                        </h3>

                        <?php
							if(in_array($table_name,$chart_table))
							{
						?>
                        <div class="awr-title-icons">
							<div class="awr-title-icon awr-title-icon-active" data-table="<?php echo esc_html($table_name); ?>" data-swap-type="awr-grid-chart"><i class="fa fa-table"></i></div>
							<div class="awr-title-icon" data-table="<?php echo esc_html($table_name); ?>" data-swap-type="awr-pie-chart"><i class="fa fa-pie-chart"></i></div>
							<div class="awr-title-icon" data-table="<?php echo esc_html($table_name); ?>" data-swap-type="awr-bar-chart"><i class="fa fa-bar-chart"></i></div>
						</div>
                        <?php } ?>

                    </div><!--awr-title -->
                    <div class="awr-box-content" id="awr-grid-chart-<?php echo esc_html($table_name); ?>">

                        <?php
							if(!in_array($table_name,$except_table) )
							{
						?>

                            <div class="awr-selcol">
                                    <a class="btn default" href="javascript:;" data-toggle="dropdown">
                                    Select Columns <i class="fa fa-angle-down"></i>
                                    </a>
                                    <div  class="dropdown-menu awr-opened">
                                    <?php
                                        // echo $cols_controls;
										echo wp_kses(
											$cols_controls,
											$this->allowedposttags()
										);
                                    ?>
                                    </div>
                            </div>
                        <?php } ?>


                        <table class="display datatable <?php echo esc_attr($table_name)?>_datatable" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <?php
									echo wp_kses(
										$table_cols,
										$this->allowedposttags()
									);
                                    //echo $table_cols;
                                ?>
                                </tr>
                            </thead>

                            <?php
                            	if(!in_array($table_name,$except_table))
								{
                            ?>
                            <tfoot>
                                <tr>
                                <?php
                                    echo wp_kses(
										$table_cols,
										$this->allowedposttags()
									);
                                ?>
                                </tr>
                            </tfoot>
                            <?php
								}
							?>

                            <tbody>
                                <?php
                              //  echo $datatable_value;
								echo wp_kses(
									$datatable_value,
									$this->allowedposttags()
								);
                                ?>
                            </tbody>

                        </table>


                        </div><!--awr-box-content -->
                	<div class="awr-box-content" id="awr-pie-chart-<?php echo esc_html($table_name); ?>"></div>
                    <div class="awr-box-content" id="awr-bar-chart-<?php echo esc_html($table_name); ?>"></div>





                    <?php
                    ////ADDED IN VER4.0
                    /// TOTAL ROW
                    if($table_name_total) {
	                    //$this->table_cols_total = $this->table_columns_total( $table_name_total );

	                    $table_cols_total = '';
	                    foreach ( $this->table_cols_total as $cols ) {
		                    $currency_class = '';
		                    if ( $cols['status'] == 'currency' ) {
			                    $currency_class = 'currency';
		                    }
		                    $table_cols_total .= '<th data-class="' . $currency_class . '"><div>' . $cols['lable'] . '</div></th>';
	                    }
	                    ?>
                        <div class="awr-box-content" id="awr-total-row-<?php echo esc_attr($table_name_total); ?>">

                            <table class="display awr-total-row-tbl <?php echo esc_attr($table_name_total); ?>_datatable" cellspacing="0"
                                   width="100%" border="1">
                                <thead>
                                <tr style="background-color: #0c91e5">
				                    <?php
				                    //echo $table_cols_total;
									echo wp_kses(
										$table_cols_total,
										$this->allowedposttags()
									);
				                    ?>
                                </tr>
                                </thead>


                                <tbody>
			                    <?php
			                   // echo $datatable_value_total;
								echo wp_kses(
									$datatable_value_total,
									$this->allowedposttags()
								);
			                    ?>
                                </tbody>

                            </table>
                        </div>
	                <?php
                    }
                    ?>

                </div>

                <?php
				}else if(/*$search_fields!=NULL && */$table_name=='dashboard_report'){
				?>

                   <!--HERE IS JUST FOR DASHBOARD SUMMARY BOX-->

                <?php
				}


			}//ENd Function


			//////////////////////////////
			// MAIN FUNCTION OF TABLE HTML FOR PDF
			//////////////////////////////
            public function slug_str($str){

	            $str= sanitize_title($str);
	            $str=str_replace("-","_",$str);
	            return $str;
            }
			public function table_html_pdf($table_name,$search_fields=NULL,$selected_columns=[]){
				$table_name_total='';

				$page_titles=array(
					'dashboard_report'		=> esc_html__( "Summary",'it_report_wcreport_textdomain'),
					'monthly_summary'		=> esc_html__( "Monthly Summary",'it_report_wcreport_textdomain'),
					'order_summary'			=> esc_html__( "Order Summary",'it_report_wcreport_textdomain'),
					'sale_order_status'		=> esc_html__( "Sales Order Status",'it_report_wcreport_textdomain'),
					'top_5_products'		=> esc_html__( "Top 5 Products",'it_report_wcreport_textdomain'),
					'top_5_country'			=> esc_html__( "Top 5 Billing Country",'it_report_wcreport_textdomain'),
					'top_5_category'		=> esc_html__( "Top 5 Categroy",'it_report_wcreport_textdomain'),
					'top_5_state'			=> esc_html__( "Top 5 Billing State",'it_report_wcreport_textdomain'),
					'top_5_customer'		=> esc_html__( "Top 5 Customers",'it_report_wcreport_textdomain'),
					'top_5_coupon'			=> esc_html__( "Top 5 Coupon",'it_report_wcreport_textdomain'),
					'top_5_gateway'			=> esc_html__( "Top 5 Payment Gateway",'it_report_wcreport_textdomain'),
					"recent_5_order"		=> esc_html__( "Recent 5 Orders",'it_report_wcreport_textdomain'),
					'details'				=> esc_html__( "All Orders",'it_report_wcreport_textdomain'),

                    //CUSTOM WORK - 12679
					'clinic'				=> esc_html__( "Total Sales per Clinic",'it_report_wcreport_textdomain'),

                    //CUSTOM WORK - 12300
					'details_tickera'				=> esc_html__( "All Orders Tickera",'it_report_wcreport_textdomain'),



					//CUSTOM WORK - 4186
					'details'				=> esc_html__( "All Orders Billing",'it_report_wcreport_textdomain'),

					//ADDED IN VER 4.9
					'abandoned_cart'				=> esc_html__( "Abandoned Carts",'it_report_wcreport_textdomain'),
					'abandoned_product'				=> esc_html__( "Abandoned Products",'it_report_wcreport_textdomain'),

					//CUSTOM WORK - 4179
					'order_status_change'				=> esc_html__( "Status Change",'it_report_wcreport_textdomain'),

					////ADDED IN VER4.0
					/// ORDER PER COUNTRY
					'details_order_country'	=> esc_html__( "All Orders per Country",'it_report_wcreport_textdomain'),
					'order_per_country'		=> esc_html__( "Order / Country",'it_report_wcreport_textdomain'),

					//CUSTOM WORK 15092
					'order_per_custom_shipping'		=> esc_html__( "Order / Shipping",'it_report_wcreport_textdomain'),
					////ADDED IN VER4.0
					/// PRODUCT OPTIONS CUSTOM FIELDS

					'product'				 => esc_html__( "Product",'it_report_wcreport_textdomain'),

					//CUSTOM WORK 966
					'all_products'				 => esc_html__( "All Products",'it_report_wcreport_textdomain'),

					////ADDED IN VER4.5
					//CUSTOM WORK
					'product_per_users'				 => esc_html__( "Product/Users",'it_report_wcreport_textdomain'),

					'profit'				 => esc_html__( "Profit",'it_report_wcreport_textdomain'),
					'category'				=> esc_html__( "Category",'it_report_wcreport_textdomain'),
					////ADDED IN VER4.0
					'tags'				=> esc_html__( "Tags",'it_report_wcreport_textdomain'),
					'stock_zero_level'				=> esc_html__( "Zero Level Stock",'it_report_wcreport_textdomain'),
					'stock_min_level'				=> esc_html__( "Minimum Level Stock",'it_report_wcreport_textdomain'),
					'stock_max_level'				=> esc_html__( "Most Stocked",'it_report_wcreport_textdomain'),
					'stock_summary_avg'				=> esc_html__( "Summary Stock Planner Based On Average Sales",'it_report_wcreport_textdomain'),

					'customer'				=> esc_html__( "Customer",'it_report_wcreport_textdomain'),
					'billingcountry'		  => esc_html__( "Billing Country",'it_report_wcreport_textdomain'),
					'billingstate'			=> esc_html__( "Billing State",'it_report_wcreport_textdomain'),
					'billingcity'			=> esc_html__( "Billing City",'it_report_wcreport_textdomain'),
					'paymentgateway'		  => esc_html__( "Payment Gateway",'it_report_wcreport_textdomain'),
					'orderstatus'			 => esc_html__( "Order Status",'it_report_wcreport_textdomain'),
					'recentorder'			 => esc_html__( "Recent Order",'it_report_wcreport_textdomain'),
					'taxreport' 			   => esc_html__( "Tax Report",'it_report_wcreport_textdomain'),
					'customerbuyproducts'	 => esc_html__( "Custom Member Buy Products",'it_report_wcreport_textdomain'),

					//CUSTOM WORK
					'customer_category'	 => esc_html__( "Customer Buy Category",'it_report_wcreport_textdomain'),

					'refunddetails' 		   => esc_html__( "Refund Orders",'it_report_wcreport_textdomain'),
					'coupon' 				  => esc_html__( "Coupon",'it_report_wcreport_textdomain'),
					////ADDED IN VER4.0
					/// OTHER SUMMARY
					'coupon_discount' 				  => esc_html__( "Coupon Discount Type Wise Reporting",'it_report_wcreport_textdomain'),
					'customer_analysis' 				  => esc_html__( "New Customer/Repeat Customer Analysis",'it_report_wcreport_textdomain'),
					'customer_order_frequently' 	=> esc_html__( "Top n Customer Report Who Orders Frequently",'it_report_wcreport_textdomain'),
					'customer_min_max' 	=> esc_html__( "Customer Min-Max",'it_report_wcreport_textdomain'),
					'customer_no_purchased' 	=> esc_html__( "Customer Who Has Not Purchased",'it_report_wcreport_textdomain'),

					////ADDED IN VER4.0
					/// ORDER ANALYSIS
					'order_product_analysis' 				  => esc_html__( "Order Qty. Analysis Simple",'it_report_wcreport_textdomain'),
					'order_variation_analysis' 				  => esc_html__( "Order Qty. Analysis Variable",'it_report_wcreport_textdomain'),

					'stock_list'			  => esc_html__( "Stock List",'it_report_wcreport_textdomain'),
					'projected_actual_sale'   => esc_html__( "Target Sale vs Actual Sale",'it_report_wcreport_textdomain'),
					'tax_reports'			 => esc_html__( "Tax Reports",'it_report_wcreport_textdomain'),


                    //CUSTOM WORK - 12412
                    'product_variation_qty'			 => esc_html__( "Product Qty",'it_report_wcreport_textdomain'),



					'monthly_summary'	     => esc_html__( "Monthly Summary",'it_report_wcreport_textdomain'),
				);
				$page_titles=apply_filters( 'it_report_wcreport_page_titles', $page_titles);

				if($search_fields!=NULL)
				{   $this->search_form_fields=$search_fields;
					$this->results =$this->fetch_sql($table_name,$search_fields);
				}

				/**************TABLE COLUMNS & CONTROLS************/


				///////////////////////////////
				// FETCH REPORT COLUMNS :
				// 1- There are two types of columns for refunddetails
				// 2- There are dynamic columns : The columns will be changed in code
				// 3- General mode

				$refund_type='';
				if($table_name=='refunddetails')
				{
					$refund_type=$this->refund_status;
					$this->table_cols =$this->table_columns($refund_type);
				}else if( in_array ( $table_name,array('details','details_full','details_full_shipping','details_full_shipping_tax','abandoned_cart','abandoned_product','details_product_options','product','all_products','product_per_users','category','tags','details_order_country','details_brands','details_tax_field','prod_per_month','variation_per_month','prod_per_country','prod_per_state','country_per_month','payment_per_month','ord_status_per_month','summary_per_month','variation','stock_summary_avg','stock_list','variation_stock','tax_reports','customer_role_total_sale','customer_role_top_products','customer_role_bottom_products','details_tickera','stock_zero_level','stock_max_level') ) )
				{
					//$this->table_cols =$this->table_columns($table_name);
				}else{

					$this->table_cols = $this->table_columns($table_name);
				}


				////COMPARE TABLE COLUMNS WITH SELECTED PDF COLUMNS//////
                $ind=0;
				$final_columns=array();

				foreach($this->table_cols as $all_columns){
                    $fcols=$this->slug_str($all_columns['lable']);

				    if(!in_array($fcols,$selected_columns)){
				        $all_columns['status']='hide';
				        $final_columns[]=$all_columns;
                    }else{
					    $final_columns[]=$all_columns;
                    }
                }
                $this->table_cols=$final_columns;



				////ADDE IN VER4.0
				/// TOTAL ROW

				//print_r($this->table_cols);

				$cols_controls='';
				$table_cols='';

				$i=0;
				if($search_fields!=NULL)
				{
					foreach($this->table_cols as $cols)
					{
					    $display='';
						if ($cols['status']=='hide'){ $display='display:none;';}
						$table_cols.= '<th style="'.$display.'" data-id="'.$cols['lable'].'"><div>'.$cols['lable'].'</div></th>';
					}
				}
				//echo $table_cols;

				/**************TABLE FETCH DATAS OF TABLE************/
				if($search_fields!=NULL)
				{
					//$it_null_val = $this->price(0);
					$it_null_val = 0;
					$datatable_value='';

					$file_used="data_table";
					switch($table_name){

						case 'dashboard_report':
							require("fetch_data_dashboard_report.php");
							break;

						case 'monthly_summary':
							require("fetch_data_dashboard_monthly_summary.php");
							break;

						case 'order_summary':
							require("fetch_data_dashboard_order_summary.php");
							break;

						case 'sale_order_status':
							require("fetch_data_dashboard_sale_order_status.php");
							break;

						case 'top_5_products':
							require("fetch_data_dashboard_top_5_products.php");
							break;

						case 'top_5_category':
							require("fetch_data_dashboard_top_5_category.php");
							break;

						case 'top_5_country':
							require("fetch_data_dashboard_top_5_country.php");
							break;

						case 'top_5_state':
							require("fetch_data_dashboard_top_5_state.php");
							break;

						case 'top_5_customer':
							require("fetch_data_dashboard_top_5_customer.php");
							break;

						case 'top_5_coupon':
							require("fetch_data_dashboard_top_5_coupon.php");
							break;

						case 'top_5_gateway':
							require("fetch_data_dashboard_top_5_gateway.php");
							break;

						case 'recent_5_order':
							require("fetch_data_dashboard_recent_5_order.php");
							break;

						case 'details':
							require("fetch_data_details.php");
							break;

                        //CUSTOM WORK - 522
                        case 'details_combined':
                            require("fetch_data_details_combined.php");
                            break;

						//CUSTOM WORK - 12679
						case 'clinic':
							require("fetch_data_clinic.php");
							break;

						//CUSTOM WORK - 12300
						case 'details_tickera':
							require("fetch_data_details_tickera.php");
						    break;

						//CUSTOM WORK - 4186
						case 'details_full' :
							require("fetch_data_details_full.php");
							break;

                        //CUSTOM WORK - 53
                        case 'details_full_shipping' :
                            require("fetch_data_details_full_shipping.php");
                            break;

                        //CUSTOM WORK - 16
                        case 'details_full_shipping_tax' :
                            require("fetch_data_details_full_shipping_tax.php");
                            break;

						//ADDED IN VER 4.9
						case 'abandoned_cart' :
							require("fetch_data_abandoned_cart.php");
							break;
						case 'abandoned_product' :
							require("fetch_data_abandoned_product.php");
							break;

							//CUSTOM WORK - 4179
						case 'order_status_change':
							require("fetch_data_order_status_change.php");
							break;

						////ADDED IN VER4.0
						/// PRODUCT OPTIONS CUSTOM FIELDS
						case 'details_product_options':
							require("fetch_data_details_product_options.php");
							break;

						///////// BRANDS////////////
						case 'details_brands' :
							require("fetch_data_details_brands.php");
							break;

						///////// CUSTOM TAX & FIELDS////////////
						case 'details_tax_field' :
							require("fetch_data_details_tax_field.php");
							break;

						case 'brand' :
							require("fetch_data_brand.php");
							break;

						case 'custom_taxonomy' :
							require("fetch_data_custom_taxonomy.php");
							break;

						/////////////////////////////////////////

						///////// ORDER PER COUNTRY ////////////

						case 'details_order_country' :
							require("fetch_data_details_per_country.php");
							break;

						case 'order_per_country' :
							require("fetch_data_custom_order_per_country.php");
							break;


						//CUSTOM WORK 15092
						case 'order_per_custom_shipping' :
							require("fetch_data_order_per_custom_shipping.php");
							break;
						/////////////////////////////////////////

						//ALL DETAILS
						case 'product':
							require("fetch_data_product.php");
						break;

						//CUSTOM WORK 966
						case 'all_products' :
							require("fetch_data_all_products.php");
						break;

						////ADDED IN VER4.5
						//CUSTOM WORK
						case 'product_per_users' :
							require("fetch_data_product_per_users.php");
						break;

						case 'profit':
							require("fetch_data_profit.php");
							break;
						case 'category':
							require("fetch_data_category.php");
							break;

						////ADDED IN VER4.0
						case 'tags':
							require("fetch_data_tags.php");
							break;

						case 'customer':
							require("fetch_data_customer.php");
							break;

						////ADDED IN VER4.0
						/// ROLE/GROUP ADDON
						case 'customer_role_total_sale':
							require("fetch_data_customer_role_total_sale.php");
							break;
						case 'customer_role_registered':
							require("fetch_data_customer_role_registered.php");
							break;
						case 'customer_role_top_products':
							require("fetch_data_customer_role_top_products.php");
							break;
						case 'customer_role_bottom_products':
							require("fetch_data_customer_role_bottom_products.php");
							break;

						case 'billingcountry':
							require("fetch_data_billingcountry.php");
							break;
						case 'billingstate':
							require("fetch_data_billingstate.php");
							break;
						////ADDED IN VER4.0
						case 'billingcity':
							require("fetch_data_billingcity.php");
							break;
						case 'paymentgateway':
							require("fetch_data_paymentgateway.php");
							break;
						case 'orderstatus':
							require("fetch_data_orderstatus.php");
							break;
						case 'recentorder':
							require("fetch_data_recentorder.php");
							break;
						case 'taxreport':
							require("fetch_data_taxreport.php");
							break;
						case 'customerbuyproducts':
							require("fetch_data_customerbuyproducts.php");
							break;

						//CUSTOM WORK 17427
						case 'customer_category' :
							require("fetch_data_customer_category.php");
							break;

						case 'refunddetails' :
							require("fetch_data_refunddetails.php");
							break;

						case 'coupon' :
							require("fetch_data_coupon.php");
							break;

						////ADDED IN VER4.0
						/// OTHER SUMMARY
						case 'coupon_discount' :
							require("fetch_data_coupon_discount.php");
							break;
						case 'customer_analysis' :
							require("fetch_data_customer_analysis.php");
							break;
						case 'customer_order_frequently' :
							require("fetch_data_customer_order_frequently.php");
							break;
						case 'customer_min_max' :
							require("fetch_data_customer_min_max.php");
							break;
						case 'customer_no_purchased' :
							require("fetch_data_customer_no_purchased.php");
							break;

						case 'prod_per_month' :
							require("fetch_data_prod_per_month.php");
							break;

						/////ADDED IN VER4.0
						//STOCK REPORTS
						case 'stock_zero_level' :
							require("fetch_data_stock_zero_level.php");
							break;

						case 'stock_min_level' :
							require("fetch_data_stock_min_level.php");
							break;

						case 'stock_max_level' :
							require("fetch_data_stock_max_level.php");
							break;

						case 'stock_summary_avg' :
							require("fetch_data_stock_summary_avg.php");
							break;
						//////END STOCK REPORTS/////

						/////ADDED IN VER4.0
						//ORDER ANALYSIS
						case 'order_product_analysis' :
							require("fetch_data_order_product_analysis.php");
							break;
						case 'order_variation_analysis' :
							require("fetch_data_order_variation_analysis.php");
							break;
						//////END ORDER ANALYSIS/////

						case 'variation_per_month' :
							require("fetch_data_variation_per_month.php");
							break;

						case 'prod_per_country':
							require("fetch_data_prod_per_country.php");
							break;

						case 'prod_per_state':
							require("fetch_data_prod_per_state.php");
							break;

						case 'country_per_month':
							require("fetch_data_country_per_month.php");
							break;

						case 'payment_per_month':
							require("fetch_data_payment_per_month.php");
							break;

						case 'ord_status_per_month':
							require("fetch_data_ord_status_per_month.php");
							break;

						case 'summary_per_month':
							require("fetch_data_summary_per_month.php");
							break;

						case 'variation':
							require("fetch_data_variation.php");
							break;

						case 'stock_list':
							require("fetch_data_stock_list.php");
							break;

						case 'variation_stock':
							require("fetch_data_variation_stock.php");
							break;

						case 'projected_actual_sale':
							require("fetch_data_projected_actual_sale.php");
							break;

						case 'tax_reports':
							require("fetch_data_tax_reports.php");
							break;

						//CUSTOM WORK - 12412
						case 'product_variation_qty':
							require("fetch_data_product_variation_qty.php");
						break;

					}
				}

				/**************TABLE HTML************/
				if($search_fields!=NULL)
				{
					?>
                    <div class="awr-box">
                        <div class="awr-title">
                            <h3>
                                <i class="fa fa-filter"></i>
								<?php
								//esc_html_e('Result','it_report_wcreport_textdomain');
								// echo $page_titles[$table_name];
								echo esc_attr($page_titles[$table_name]);
								?>
                            </h3>

                        </div><!--awr-title -->
                        <div class="awr-box-content" id="awr-grid-chart-<?php echo esc_html($table_name); ?>">


                            <table class="display datatable awr-total-row-tbl <?php echo esc_attr($table_name)?>_datatable" cellspacing="0" width="100%">
                                <thead>
                                <tr>
									<?php
									//echo $table_cols;
									echo wp_kses(
										$table_cols,
										$this->allowedposttags()
									);
									?>
                                </tr>
                                </thead>

                                <tfoot>
                                <tr>
		                            <?php
									echo wp_kses(
										$table_cols,
										$this->allowedposttags()
									);
		                           // echo $table_cols;
		                            ?>
                                </tr>
                                </tfoot>

                                <tbody>
								<?php
								// echo $datatable_value;
								echo wp_kses(
									$datatable_value,
									$this->allowedposttags()
								);
								?>
                                </tbody>

                            </table>


                        </div><!--awr-box-content -->
                    	<?php
						////ADDED IN VER4.0
						/// TOTAL ROW
						if($table_name_total) {
							//$this->table_cols_total = $this->table_columns_total( $table_name_total );

							$table_cols_total = '';
							foreach ( $this->table_cols_total as $cols ) {
								$table_cols_total .= '<th ><div>' . $cols['lable'] . '</div></th>';
							}
							?>
                            <div class="awr-box-content" id="awr-total-row-<?php echo esc_attr($table_name_total); ?>">
                                <table class="display awr-total-row-tbl " cellspacing="0"
                                       width="100%" border="1">
                                    <thead>
                                    <tr style="background-color: #0c91e5">
										<?php
										//echo $table_cols_total;
										echo wp_kses(
											$table_cols_total,
											$this->allowedposttags()
										);
										?>
                                    </tr>
                                    </thead>


                                    <tbody>
									<?php
									// echo $datatable_value_total;
									echo wp_kses(
										$datatable_value_total,
										$this->allowedposttags()
									);
									?>
                                    </tbody>

                                </table>
                            </div>
							<?php
						}
						?>

                    </div>

					<?php
				}else if(/*$search_fields!=NULL && */$table_name=='dashboard_report'){
					?>

                    <!--HERE IS JUST FOR DASHBOARD SUMMARY BOX-->

					<?php
				}


			}//ENd Function


            ////ADDED IN VER4.1
            ///INTELLIGENCE REPORTS
			public function table_html_intelligence($table_name,$search_fields=NULL){

			    global  $wpdb;

				$page_titles=array(
					'int_reports_Home'		=> esc_html__( "Home",'it_report_wcreport_textdomain'),
					'int_reports_sales'		=> esc_html__( "Intelligence Sales Report",'it_report_wcreport_textdomain'),
					'int_reports_products'		=> esc_html__( "Intelligence Products Report",'it_report_wcreport_textdomain'),
					'int_reports_customers'			=> esc_html__( "Intelligence Customers Report",'it_report_wcreport_textdomain'),
					'int_reports_transactions'			=> esc_html__( "Intelligence Transactions Report",'it_report_wcreport_textdomain'),
                );


				$this->search_form_fields=$search_fields;
				$file_used="sql_table";
				switch($table_name){

					case 'int_reports_home':
						require("Intelligence/fetch_data_int_reports_home.php");
						break;

                    case 'int_reports_sales':
						require("Intelligence/fetch_data_int_reports_sales.php");
						break;

					case 'int_reports_products':
						require("Intelligence/fetch_data_int_reports_products.php");
						break;

					case 'int_reports_customers':
						require("Intelligence/fetch_data_int_reports_customers.php");
						break;

                    case 'int_reports_transactions':
						require("Intelligence/fetch_data_int_reports_transactions.php");
						break;
				}
				if(isset($sql))
					$this->results=$wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared


			    $output='';
			    $file_used="data_table";
				switch($table_name){

					case 'int_reports_home':
						require("Intelligence/fetch_data_int_reports_home.php");
						break;

                    case 'int_reports_sales':
						require("Intelligence/fetch_data_int_reports_sales.php");
						break;

					case 'int_reports_products':
						require("Intelligence/fetch_data_int_reports_products.php");
						break;

					case 'int_reports_customers':
						require("Intelligence/fetch_data_int_reports_customers.php");
						break;

                    case 'int_reports_transactions':
						require("Intelligence/fetch_data_int_reports_transactions.php");
						break;
				}



				$html = $output;
                //echo $html;
            }

			//////////////////////////////
			// GENERATE HTML
			//////////////////////////////
			public function it_generate_htmls($params){

				extract($params);

				ob_start();
				?>
				<div class="wrap">
					<div class="awr-box awr-search-from">
						<div class="awr-title">
							<h3>
								<i class="fa fa-filter"></i><?php esc_html_e( 'Search', 'it_report_wcreport_textdomain' ); ?>
							</h3>
							<div class="awr-title-icon awr-toggle-icon"><i class="fa fa-search"></i></div>
							<div class="awr-title-icons">
								<div class="awr-title-icon awr-add-fav-icon" title="Add to Favorites Menus" data-smenu="<?php echo esc_attr($smenu); ?>"><i
											class="fa <?php echo esc_attr($fav_icon); ?>"></i></div>
								
								<div class="awr-title-icon awr-setting-icon"><i class="fa fa-cog"></i></div>
								<div class="awr-title-icon awr-close-icon"><i class="fa fa-times"></i></div>
							</div>
						</div><!--awr-title -->
						<div class="awr-box-content-form">
							<?php
							$this->search_form_html( $table_name );
							?>
						</div>
					</div>


					<div class="row">
						<div class="col-md-12" id="target">
							<?php
							$this->table_html( $table_name );
							?>
						</div>
					</div>
				</div>
				<?php
				$content = ob_get_clean();

				return $content;
			}


			///////////////////////////
			//ALLOWED HTML
			//////////////////////////
			public function allowedposttags(){
				$allowed_atts = array(
					'align'      => array(),
					'class'      => array(),
					'type'       => array(),
					'id'         => array(),
					'dir'        => array(),
					'lang'       => array(),
					'style'      => array(),
					'xml:lang'   => array(),
					'src'        => array(),
					'alt'        => array(),
					'href'       => array(),
					'rel'        => array(),
					'rev'        => array(),
					'target'     => array(),
					'novalidate' => array(),
					'type'       => array(),
					'value'      => array(),
					'name'       => array(),
					'tabindex'   => array(),
					'action'     => array(),
					'method'     => array(),
					'for'        => array(),
					'width'      => array(),
					'height'     => array(),
					'data'       => array(),
					'title'      => array(),
					'multiple'      => array(),
					'data-parent-id'      => array(),
					
				);
				$allowedposttags['form']     = $allowed_atts;
				$allowedposttags['label']    = $allowed_atts;
				$allowedposttags['input']    = $allowed_atts;
				$allowedposttags['textarea'] = $allowed_atts;
				$allowedposttags['iframe']   = $allowed_atts;
				$allowedposttags['script']   = $allowed_atts;
				$allowedposttags['style']    = $allowed_atts;
				$allowedposttags['strong']   = $allowed_atts;
				$allowedposttags['small']    = $allowed_atts;
				$allowedposttags['table']    = $allowed_atts;
				$allowedposttags['span']     = $allowed_atts;
				$allowedposttags['abbr']     = $allowed_atts;
				$allowedposttags['code']     = $allowed_atts;
				$allowedposttags['pre']      = $allowed_atts;
				$allowedposttags['div']      = $allowed_atts;
				$allowedposttags['img']      = $allowed_atts;
				$allowedposttags['h1']       = $allowed_atts;
				$allowedposttags['h2']       = $allowed_atts;
				$allowedposttags['h3']       = $allowed_atts;
				$allowedposttags['h4']       = $allowed_atts;
				$allowedposttags['h5']       = $allowed_atts;
				$allowedposttags['h6']       = $allowed_atts;
				$allowedposttags['ol']       = $allowed_atts;
				$allowedposttags['ul']       = $allowed_atts;
				$allowedposttags['li']       = $allowed_atts;
				$allowedposttags['em']       = $allowed_atts;
				$allowedposttags['hr']       = $allowed_atts;
				$allowedposttags['br']       = $allowed_atts;
				$allowedposttags['tr']       = $allowed_atts;
				$allowedposttags['td']       = $allowed_atts;
				$allowedposttags['p']        = $allowed_atts;
				$allowedposttags['a']        = $allowed_atts;
				$allowedposttags['b']        = $allowed_atts;
				$allowedposttags['i']        = $allowed_atts;
				$allowedposttags['button']        = $allowed_atts;
				$allowedposttags['select']        = $allowed_atts;
				$allowedposttags['option']        = $allowed_atts;
				$allowedposttags['table']        = $allowed_atts;
				$allowedposttags['tr']        = $allowed_atts;
				$allowedposttags['td']        = $allowed_atts;
				$allowedposttags['th']        = $allowed_atts;
				$allowedposttags['nav']        = $allowed_atts;

				return $allowedposttags;
			}

			//////////////////////////////
			// SEARCH FORM HTML
			//////////////////////////////
			public function search_form_html($table_name){
				//$this->results =$this->fetch_sql($table_name);

				$file_used="search_form";
				switch($table_name){

					case 'dashboard_report':
						require("fetch_data_dashboard_report.php");
					break;

					case 'monthly_summary':
						require("fetch_data_dashboard_monthly_summary.php");
					break;

					case 'order_summary':
						require("fetch_data_dashboard_order_summary.php");
					break;

					case 'details':
						require("fetch_data_details.php");
					break;

                    //CUSTOM WORK - 522//Name
                    //            $display_class='';
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    //
                    //            //Email
                    //            $display_class='';
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    //
                    //            //Date
                    //            $display_class='';
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    //
                    //            //COUNTRY
                    //            $display_class='';
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    //
                    //            //STATE
                    //            $display_class='';
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    //
                    //
                    //            //Status
                    //
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    //
                    //
                    //
                    //            //PAYMENT METHOD
                    //            $display_class='';
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    //
                    //
                    //            //COUPON CODE
                    //            $display_class='';
                    //            if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    //            $datatable_value.=("<td style='".$display_class."'>");
                    //            $datatable_value.= '';
                    //            $datatable_value.=("</td>");
                    case 'details_combined':
                        require("fetch_data_details_combined.php");
                        break;

					//CUSTOM WORK - 12679
					case 'clinic':
						require("fetch_data_clinic.php");
						break;

					//CUSTOM WORK - 12300
					case 'details_tickera':
						require("fetch_data_details_tickera.php");
					break;

					//CUSTOM WORK - 4186
					case 'details_full' :
					    require("fetch_data_details_full.php");
					break;

                    //CUSTOM WORK - 53
                    case 'details_full_shipping' :
                        require("fetch_data_details_full_shipping.php");
                        break;

                    //CUSTOM WORK - 16
                    case 'details_full_shipping_tax' :
                        require("fetch_data_details_full_shipping_tax.php");
                        break;

					//ADDED IN VER 4.9
					case 'abandoned_cart' :
						require("fetch_data_abandoned_cart.php");
						break;
					case 'abandoned_product' :
						require("fetch_data_abandoned_product.php");
						break;

					//CUSTOM WORK - 4179
					case 'order_status_change':
						require("fetch_data_order_status_change.php");
						break;

					////ADDED IN VER4.0
					/// PRODUCT OPTIONS CUSTOM FIELDS
					case 'details_product_options':
						require("fetch_data_details_product_options.php");
					break;

					///////// BRANDS////////////
					case 'details_brands' :
						require("fetch_data_details_brands.php");
					break;

					///////// CUSTOM TAX & FIELDS////////////
					case 'details_tax_field' :
						require("fetch_data_details_tax_field.php");
					break;

					case 'brand' :
						require("fetch_data_brand.php");
					break;

					case 'custom_taxonomy' :
						require("fetch_data_custom_taxonomy.php");
					break;

					/////////////////////////////////////////

                    ///////// ORDER PER COUNTRY ////////////
					case 'details_order_country' :
						require("fetch_data_details_per_country.php");
					break;

					case 'order_per_country' :
						require("fetch_data_custom_order_per_country.php");
					break;

					//CUSTOM WORK 15092
					case 'order_per_custom_shipping' :
						require("fetch_data_order_per_custom_shipping.php");
						break;
					/////////////////////////////////////////

                    ////ADDED IN VER4.1
                    ///INTELLIGENCE REPORTS
					case 'int_reports_home' :
						require("Intelligence/fetch_data_int_reports_home.php");
						break;

                    case 'int_reports_sales' :
						require("Intelligence/fetch_data_int_reports_sales.php");
						break;
					case 'int_reports_products':
						require("Intelligence/fetch_data_int_reports_products.php");
						break;

					case 'int_reports_customers':
						require("Intelligence/fetch_data_int_reports_customers.php");
						break;

                    case 'int_reports_transactions':
						require("Intelligence/fetch_data_int_reports_transactions.php");
						break;

					/////////////////////////////////////////

					//ALL DETAILS
					case 'product':
						require("fetch_data_product.php");
					break;

					//CUSTOM WORK 966
						case 'all_products' :
							require("fetch_data_all_products.php");
						break;

					////ADDED IN VER4.5
					//CUSTOM WORK
					case 'product_per_users' :
						require("fetch_data_product_per_users.php");
					break;

					case 'profit':
						require("fetch_data_profit.php");
					break;
					case 'category':
						require("fetch_data_category.php");
					break;

					////ADDED IN VER4.0
					case 'tags':
						require("fetch_data_tags.php");
					break;

					case 'customer':
						require("fetch_data_customer.php");
					break;

					////ADDED IN VER4.0
					/// ROLE/GROUP ADDON
					case 'customer_role_total_sale':
						require("fetch_data_customer_role_total_sale.php");
						break;
					case 'customer_role_registered':
						require("fetch_data_customer_role_registered.php");
						break;
					case 'customer_role_top_products':
						require("fetch_data_customer_role_top_products.php");
						break;
					case 'customer_role_bottom_products':
						require("fetch_data_customer_role_bottom_products.php");
						break;

					case 'billingcountry':
						require("fetch_data_billingcountry.php");
					break;
					case 'billingstate':
						require("fetch_data_billingstate.php");
					break;
					////ADDED IN VER4.0
					case 'billingcity':
						require("fetch_data_billingcity.php");
					break;
					case 'paymentgateway':
						require("fetch_data_paymentgateway.php");
					break;
					case 'orderstatus':
						require("fetch_data_orderstatus.php");
					break;
					case 'recentorder':
						require("fetch_data_recentorder.php");
					break;
					case 'taxreport':
						require("fetch_data_taxreport.php");
					break;
					case 'customerbuyproducts':
						require("fetch_data_customerbuyproducts.php");
					break;


					//CUSTOM WORK 17427
					case 'customer_category' :
						require("fetch_data_customer_category.php");
						break;

					case 'refunddetails':
						require("fetch_data_refunddetails.php");
					break;
					case 'coupon':
						require("fetch_data_coupon.php");
					break;

					////ADDED IN VER4.0
                    ///OTHER SUMMARY
					case 'coupon_discount' :
						require("fetch_data_coupon_discount.php");
					break;
					case 'customer_analysis' :
						require("fetch_data_customer_analysis.php");
					break;
					case 'customer_order_frequently' :
						require("fetch_data_customer_order_frequently.php");
					break;
					case 'customer_min_max' :
						require("fetch_data_customer_min_max.php");
					break;
					case 'customer_no_purchased' :
						require("fetch_data_customer_no_purchased.php");
					break;


					case 'prod_per_month':
						require("fetch_data_prod_per_month.php");
					break;

					/////ADDED IN VER4.0
					//STOCK REPORTS
					case 'stock_zero_level' :
						require("fetch_data_stock_zero_level.php");
					break;

					case 'stock_min_level' :
						require("fetch_data_stock_min_level.php");
					break;

					case 'stock_max_level' :
						require("fetch_data_stock_max_level.php");
					break;

					case 'stock_summary_avg' :
						require("fetch_data_stock_summary_avg.php");
					break;
					//////END STOCK REPORTS/////

					/////ADDED IN VER4.0
					//ORDER ANALYSIS
					case 'order_product_analysis' :
						require("fetch_data_order_product_analysis.php");
					break;
					case 'order_variation_analysis' :
						require("fetch_data_order_variation_analysis.php");
					break;
					//////END ORDER ANALYSIS/////

					case 'variation_per_month':
						require("fetch_data_variation_per_month.php");
					break;
					case 'prod_per_country':
						require("fetch_data_prod_per_country.php");
					break;
					case 'prod_per_state':
						require("fetch_data_prod_per_state.php");
					break;
					case 'country_per_month':
						require("fetch_data_country_per_month.php");
					break;
					case 'payment_per_month':
						require("fetch_data_payment_per_month.php");
					break;
					case 'ord_status_per_month':
						require("fetch_data_ord_status_per_month.php");
					break;
					case 'summary_per_month':
						require("fetch_data_summary_per_month.php");
					break;
					case 'variation':
						require("fetch_data_variation.php");
					break;
					case 'stock_list':
						require("fetch_data_stock_list.php");
					break;
					case 'variation_stock':
						require("fetch_data_variation_stock.php");
					break;
					case 'projected_actual_sale':
						require("fetch_data_projected_actual_sale.php");
					break;
					case 'tax_reports':
						require("fetch_data_tax_reports.php");
					break;

					//CUSTOM WORK - 12412
					case 'product_variation_qty':
					    require("fetch_data_product_variation_qty.php");
					break;
				}
			}

			////ADDED IN VER4.1
            /// GET FROM-TO DATE
            function it_get_date_form_to(){
	            global $wpdb;

	            $order_date="SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN ('trash') GROUP BY it_posts.ID Order By it_posts.post_date ASC LIMIT 5";
	            $results= $wpdb->get_results($order_date); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	            $first_date='';
	            $i=0;
	            while($i<5){

		            if(!$results && $results[$i]->order_date!=0)
		            {
			            if(isset($results[$i]))
				            $first_date=$results[$i]->order_date;
			            break;
		            }
		            $i++;
	            }

	            $order_date="SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN ('trash') GROUP BY it_posts.ID Order By it_posts.post_date DESC LIMIT 1";
	            $results= $wpdb->get_results($order_date); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	            $it_to_date='';
	            if(isset($results[0]))
		            $it_to_date=$results[0]->order_date;

	            if($first_date==''){
		            $first_date= gmdate("Y-m-d");

		            if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
			            $first_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'from_date',$first_date);
		            }

		            $this->it_from_date_dashboard=$first_date;
		            $first_date=substr($first_date,0,4);
	            }else{

		            if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
			            $first_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'from_date',$first_date);
		            }

		            $it_from_date_dashboard=explode(" ",$first_date);
		            $this->it_from_date_dashboard=$it_from_date_dashboard[0];

		            $first_date=substr($first_date,0,4);
	            }

	            if($it_to_date==''){
		            $it_to_date= gmdate("Y-m-d");
		            if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
			            $it_to_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'to_date',$it_to_date);
		            }
		            $this->it_to_date_dashboard=$it_to_date;
		            $it_to_date=substr($it_to_date,0,4);
	            }else{
		            if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
			            $it_to_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'to_date',$it_to_date);
		            }
		            $it_to_date_dashboard=explode(" ",$it_to_date);
		            $this->it_to_date_dashboard=$it_to_date_dashboard[0];

		            $it_to_date=substr($it_to_date,0,4);
	            }
	            return true;
            }

			//PUBLIC FUCTIONS
			function it_get_prod_sku($order_item_id, $it_product_id/*,$current_page='',$product_type='-1'*/){
				$it_table_value = $this->it_get_oiv_sku($order_item_id);
				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : $this->it_get_op_sku($it_product_id);

				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : 'Not Set';
				return $it_table_value;
			}

			function it_get_oiv_customfields($order_item_id = 0){
				global $wpdb;
				$sql = "
				SELECT
				it_postmeta_sku.meta_value as it_variation_sku
				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_sku ON it_postmeta_sku.post_id = woocommerce_order_itemmeta.meta_value
				WHERE it_woocommerce_order_items.order_item_id={$order_item_id}

				AND it_woocommerce_order_items.order_item_type = 'line_item'
				AND woocommerce_order_itemmeta.meta_key = '_variation_id'
				AND it_postmeta_sku.meta_key = 'Serial Number'
				";
				return $orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			function it_get_prod_customfields($order_item_id, $it_product_id/*,$current_page='',$product_type='-1'*/){

                $it_table_value = $this->it_get_oiv_customfields($order_item_id);

				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : 'Not Set';
				return $it_table_value;
			}

            function it_get_product_sku($it_product_id){

				$it_table_value = $this->it_get_op_sku($it_product_id);


				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : 'Not Set';
				return $it_table_value;
			}

			function it_get_prod_stock_($order_item_id, $it_product_id){
				$it_table_value = $this->it_get_oiv_stock($order_item_id);
				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : $this->it_op_stock($it_product_id);
				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : 'Not Set';
				return $it_table_value;
			}

			function it_get_oiv_stock($order_item_id = 0){
				global $wpdb;
				$sql = "
				SELECT
				it_postmeta_sku.meta_value as it_variation_sku
				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_sku ON it_postmeta_sku.post_id = woocommerce_order_itemmeta.meta_value
				WHERE it_woocommerce_order_items.order_item_id={$order_item_id}

				AND it_woocommerce_order_items.order_item_type = 'line_item'
				AND woocommerce_order_itemmeta.meta_key = '_variation_id'
				AND it_postmeta_sku.meta_key = '_stock'
				";
				return $orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			function it_op_stock($it_product_id = 0){
				global $wpdb;
				$sql = "SELECT it_postmeta_stock.meta_value as it_product_sku
				FROM {$wpdb->prefix}postmeta as it_postmeta_stock
				WHERE it_postmeta_stock.meta_key = '_stock'";


				if(strlen($it_product_id) >= 0 and  $it_product_id > 0)
					$sql .= " and it_postmeta_stock.post_id = {$it_product_id}";

				if(strlen($it_product_id) >= 0 and  $it_product_id > 0){
					$orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					if(strlen($wpdb->last_error) > 0){
						//echo $wpdb->last_error;
					}
				}else
					$orderitems = '';


				return $orderitems;

				//return $orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}


			////ADDED IN VER4.0
			/// GET CUSTOM FIELDS
			function it_get_prod_custom_fields($order_item_id, $it_product_id,$field/*,$current_page='',$product_type='-1'*/){

				$it_table_value = $this->it_get_oiv_custom_fields($order_item_id,$field);
				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : $this->it_get_op_custom_fields($it_product_id,$field);

				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : 'Not Set';
				return $it_table_value;
			}

			function it_get_oiv_custom_fields($order_item_id = 0,$field=''){
				global $wpdb;
				$sql = "
				SELECT
				it_postmeta_sku.meta_value as it_variation_sku
				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_sku ON it_postmeta_sku.post_id = woocommerce_order_itemmeta.meta_value
				WHERE it_woocommerce_order_items.order_item_id={$order_item_id}

				AND it_woocommerce_order_items.order_item_type = 'line_item'
				AND woocommerce_order_itemmeta.meta_key = '_variation_id'
				AND it_postmeta_sku.meta_key = '$field'
				";
				return $orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			function it_get_op_custom_fields($it_product_id = 0,$field=''){
				global $wpdb;
				$sql = "SELECT it_postmeta_sku.meta_value as it_product_sku
				FROM {$wpdb->prefix}postmeta as it_postmeta_sku
				WHERE it_postmeta_sku.meta_key = '$field'";


				if(strlen($it_product_id) >= 0 and  $it_product_id > 0)
					$sql .= " and it_postmeta_sku.post_id = {$it_product_id}";

				if(strlen($it_product_id) >= 0 and  $it_product_id > 0){
					$orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					if(strlen($wpdb->last_error) > 0){
						//echo $wpdb->last_error;
					}
				}else
					$orderitems = '';

				return $orderitems;
			}

			////ADDED IN VER4.0
			/// GET CUSTOM FIELDS - TYPE2
			function it_get_prod_custom_fields_2($order_item_id, $it_product_id,$field/*,$current_page='',$product_type='-1'*/){

				$it_table_value = $this->it_get_oiv_custom_fields_2($order_item_id,$field);
				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : $this->it_get_op_custom_fields_2($it_product_id,$field);

				$it_table_value = strlen($it_table_value) > 0 ? $it_table_value : 'Not Set';
				return $it_table_value;
			}

			function it_get_oiv_custom_fields_2($order_item_id = 0,$field=''){
				global $wpdb;
				$sql = "
				SELECT
				woocommerce_order_itemmeta.meta_value as it_variation_sku
				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id
				WHERE it_woocommerce_order_items.order_item_id={$order_item_id}

				AND it_woocommerce_order_items.order_item_type = 'line_item'
				AND woocommerce_order_itemmeta.meta_key = '$field'

				";
				//echo $sql;
				return $orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			function it_get_op_custom_fields_2($it_product_id = 0,$field=''){
				global $wpdb;
				$sql = "SELECT it_postmeta_sku.meta_value as it_product_sku
				FROM {$wpdb->prefix}postmeta as it_postmeta_sku
				WHERE it_postmeta_sku.meta_key = '$field'";


				if(strlen($it_product_id) >= 0 and  $it_product_id > 0)
					$sql .= " and it_postmeta_sku.post_id = {$it_product_id}";
///echo $sql;
				if(strlen($it_product_id) >= 0 and  $it_product_id > 0){
					$orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					if(strlen($wpdb->last_error) > 0){
						//echo $wpdb->last_error;
					}
				}else
					$orderitems = '';

				return $orderitems;
			}


			function it_get_oiv_sku($order_item_id = 0){
				global $wpdb;
				$sql = "
				SELECT
				it_postmeta_sku.meta_value as it_variation_sku
				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_sku ON it_postmeta_sku.post_id = woocommerce_order_itemmeta.meta_value
				WHERE it_woocommerce_order_items.order_item_id={$order_item_id}

				AND it_woocommerce_order_items.order_item_type = 'line_item'
				AND woocommerce_order_itemmeta.meta_key = '_variation_id'
				AND it_postmeta_sku.meta_key = '_sku'
				";
				//echo $sql;
				return $orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			function it_get_op_sku($it_product_id = 0){
				global $wpdb;
				$sql = "SELECT it_postmeta_sku.meta_value as it_product_sku
				FROM {$wpdb->prefix}postmeta as it_postmeta_sku
				WHERE it_postmeta_sku.meta_key = '_sku'";


				if(strlen($it_product_id) >= 0 and  $it_product_id > 0)
					$sql .= " and it_postmeta_sku.post_id = {$it_product_id}";
				//echo $sql;
				if(strlen($it_product_id) >= 0 and  $it_product_id > 0){
					$orderitems = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					if(strlen($wpdb->last_error) > 0){
						//echo $wpdb->last_error;
					}
				}else
					$orderitems = '';

				return $orderitems;
			}


			public function _get_woos_price_calculation(){

				$oldway=false;
				if (class_exists('WOOCS')){
					global $WOOCS;

					$oldway=false;
				}

				return $oldway;

			}

			////ADDE IN VER4.0
            /// USE FOR CHART VALUES
			function price_value($value, $args = array(),$type='general'){
//return $value;
				$currency_decimal=get_option('woocommerce_price_decimal_sep','.');
				$currency_thousand=get_option('woocommerce_price_thousand_sep',',');
				$currency_thousand=',';

				if (class_exists('WOOCS')) {
					global $WOOCS;

					$current_currency=$WOOCS->current_currency;
					$current_currency=$WOOCS->storage->get_val('woocs_current_currency');

//					if(isset($args['currency'])){
//						$current_currency=$args['currency'];
//                    }
					//echo $current_currency;

					$currencies=$WOOCS->get_currencies();
					$symbol=($currencies[$current_currency]['symbol']);
					$position=($currencies[$current_currency]['position']);

					//$value = apply_filters('woocs_exchange_value', $value);
					//$value = $WOOCS->woocs_exchange_value($value);

					//before Currecny Switcher ver 2.0.9
					$value = $value * $currencies[$current_currency]['rate'];
					$value = number_format($value, 2, $WOOCS->decimal_sep, '');


					return $value;


				}


				return $value;
			}

			////ADDE IN VER4.0
            /// CURRENCY SWITCHER
            /// If add a product in admin in $/Pound/Euro (exp: 100$), and then purchase product -> 1- the currency is same as admin => same price (100$) 2- other currecny => exchange price (exp : 55pound / 60 Euro)
			function woocs_price_order($value, $order_id){

			    global $WOOCS;
				$currencies = $WOOCS->get_currencies();
				$currency_decimal=get_option('woocommerce_price_decimal_sep','.');
				$currency_thousand=get_option('woocommerce_price_thousand_sep',',');

				$_order_currency = get_post_meta($order_id, '_order_currency', true);
				$order_rate = get_post_meta($order_id, '_woocs_order_rate', true);

				if (!$order_rate)
				{
					if (isset($currencies[$_order_currency]))
					{
						$order_rate = $currencies[$_order_currency]['rate'];
					} else
					{
						$order_rate =1;
					}
				}

				$symbol=($currencies[$_order_currency]['symbol']);
				$position=($currencies[$_order_currency]['position']);
				if ($_order_currency != $WOOCS->default_currency)
				{
					$value=$WOOCS->back_convert($value, $order_rate, 2);
					$symbol=($currencies[$WOOCS->default_currency]['symbol']);
					$position=($currencies[$WOOCS->default_currency]['position']);
				}else{
					$value = number_format($value, 2, $currency_decimal, $currency_thousand);
                }

				switch($position){
					case "right":
						$v = ($value.$symbol);
						break;

					case "right_space":
						$v = ($value.' '.$symbol);
						break;

					case "left":
						$v = ($symbol.$value);
						break;

					case "left_space":
						$v = ($symbol.' '.$value);
						break;
				}

				return $v;
            }

            //use for stock , variation_stock and every reports not related to order_id
			function woocs_price_single($value){

				global $WOOCS;
				//return $WOOCS->woocs_exchange_value($value);
				$currency_decimal=get_option('woocommerce_price_decimal_sep','.');
				$currency_thousand=get_option('woocommerce_price_thousand_sep',',');

				$current_currency=$WOOCS->current_currency;
				$default_currency=$WOOCS->default_currency;
				$currencies=$WOOCS->get_currencies();
				$symbol=($currencies[$default_currency]['symbol']);
				$position=($currencies[$default_currency]['position']);
				//$value=$WOOCS->back_convert($value, $currencies[$default_currency]['rate'], 2);
				switch($position){
					case "right":
						$v = ($value.$symbol);
						break;

					case "right_space":
						$v = ($value.' '.$symbol);
						break;

					case "left":
						$v = ($symbol.$value);
						break;

					case "left_space":
						$v = ($symbol.' '.$value);
						break;
				}
				return $v;
			}

			function price($value, $args = array(),$type='general'){

				$currency_decimal=get_option('woocommerce_price_decimal_sep','.');
				$currency_thousand=get_option('woocommerce_price_thousand_sep',',');
				$currency_thousand=',';

				if (class_exists('WOOCS')) {
//					global $WOOCS;
//
//					if(isset($args['order_id'])){
//					    $order_id = $args['order_id'];
//					    return $this->woocs_price_order($value,$order_id).'';
//                    }else{
//					    return $this->woocs_price_single($value);
//                    }

				}

				$currency        = isset( $args['currency'] ) ? $args['currency'] : '';

				if (!$currency ) {

					if(!isset($this->constants['woocommerce_currency'])){
						$this->constants['woocommerce_currency'] =  $currency = (function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : "USD");
					}else{
						$currency  = $this->constants['woocommerce_currency'];
					}
				}
				//return $currency;
				$args['currency'] 	= $currency;
				$value 				= trim($value);
				$withoutdecimal 	= str_replace(".","d",$value);

				if(!isset($this->constants['price_format'][$currency][$withoutdecimal])){

					if(!function_exists('woocommerce_price')){
						if(!isset($this->constants['currency_symbol'])){
							$this->constants['currency_symbol'] =  $currency_symbol 	= apply_filters( 'it_woo_symbol_currency', '&#36;', 'USD');
						}else{
							$currency_symbol  = $this->constants['currency_symbol'];
						}
						$value				= strlen(trim($value)) > 0 ? $value : 0;
						$v 					= $currency_symbol."".number_format($value, 2, '.', ' ');
						$v					= "<span class=\"amount\">{$v}</span>";

					}else{

						$v = '';
						global $woocommerce;
						if( version_compare( $woocommerce->version, 3.0, "<" ) ) {
							$v = woocommerce_price($value, $args);
						}else{
							$v = wc_price($value, $args);
						}




					}
					$this->constants['price_format'][$currency][$withoutdecimal] = $v;
				}else{
					$v = $this->constants['price_format'][$currency][$withoutdecimal];
				}


				return $v;
			}

			function woocommerce_currency(){
				if(!isset($this->constants['woocommerce_currency'])){
					$this->constants['woocommerce_currency'] =  $currency = (function_exists('get_woocommerce_currency') ? get_woocommerce_currency() : "USD");
				}else{
					$currency  = $this->constants['woocommerce_currency'];
				}
				return $currency;
			}

			var $terms_by = array();
			function it_get_cn_product_id($id, $taxonomy = 'product_cat', $termkey = 'name'){
				$term_name ="";
				if(!isset($this->terms_by[$taxonomy][$id])){
					$id			= (integer)$id;
					$terms		= get_the_terms($id, $taxonomy);
					$termlist	= array();



					if($terms){

						foreach ( $terms as $term ) {
						    ////ADDE IN VER4.0
                            /// BRAND THUMBNAIL
						    if($taxonomy=='product_brand' && __IT_BRAND_THUMB__==1){
							    $thumbnail = get_term_meta($term->term_id, 'thumbnail_id', true);
							    $current=wp_get_attachment_image_src($thumbnail, 'full');
							    $brand_image_src='';
							    if(is_array($current))
							        $brand_image_src = current($current);
							    $termlist[] ='<img src="'.$brand_image_src.'" width="40" height="40" title="'.$term->$termkey.'"/>';
                            }else{
                                $termlist[] = $term->{$termkey};
                            }

						}
//return $termlist;
						if(!$termlist && __IT_BRAND_THUMB__==1){
							$term_name =  implode( ' ', $termlist );
						}else if(is_array($termlist)){
							$term_name =  implode( ', ', $termlist );
                        }
					}
					$this->terms_by[$taxonomy][$id] = $term_name;
				}else{
					$term_name = $this->terms_by[$taxonomy][$id];
				}
				return $term_name;
			}

			function it_email_link_format($e, $display = true){
				$return = '<a href="mailto:'.$e.'">'.$e.'</a>';
				if($display)
					// echo $return;
					echo wp_kses(
						$return,
						$this->allowedposttags()
					);
				else
					return $return;
			}


			function it_get_woo_countries(){
				return class_exists('WC_Countries') ? (new WC_Countries) : (object) array();
			}

			//GET COUNTRY
			function it_get_paying_woo_country($code = "_billing_country"){
				global $wpdb;

				$country      	= $this->it_get_woo_countries();

				$sql = "SELECT
				postmeta.meta_value AS 'id'
				,postmeta.meta_value AS 'label'

				FROM {$wpdb->prefix}postmeta as postmeta
				WHERE postmeta.meta_key='{$code}'
				GROUP BY postmeta.meta_value
				ORDER BY postmeta.meta_value ASC";
				$results = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

				foreach($results as $key => $value):
						$results[$key]->label = isset($country->countries[$value->label]) ? $country->countries[$value->label]: $value->label;
				endforeach;

				return $results;
			}

			//GET COUNTRY STATES
			function it_get_wc_woo_states($it_country_code){
				global $woocommerce;
				return isset($woocommerce) ? $woocommerce->countries->get_states($it_country_code) : array();
			}

			var $states_name = array();
			var $country_states = array();

			function it_get_woo_bsn($cc = NULL,$st = NULL){
				global $woocommerce;
				$state_code = $st;

				if(!$cc) return $state_code;

				if(isset($this->states_name[$cc][$st])){
					$state_code = $this->states_name[$cc][$st];
				}else{

					if(isset($this->country_states[$cc])){
						$states = $this->country_states[$cc];
					}else{
						$states = $this->it_get_wc_woo_states($cc);
						$this->country_states[$cc] = $states;
					}

					if(is_array($states)){
						$state_code = isset($states[$state_code]) ? $states[$state_code] : $state_code;
					}

					$this->states_name[$cc][$st] = $state_code;
				}
				return $state_code;
			}


			//GET COUPON CODE
			function it_oin_list($order_id_string = array(),$order_item_type = "tax"){
				global $wpdb;
				$item_name = array();
				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				if(strlen($order_id_string) > 0){
					$sql = "SELECT
					it_woocommerce_order_items.order_id as order_id,
					it_woocommerce_order_items.order_item_name AS item_name
					FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
					WHERE order_item_type ='{$order_item_type}' AND order_id IN ({$order_id_string})";
					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared


					if(!empty($order_items)){
						foreach($order_items as $key => $value){
							if(isset($item_name[$value->order_id]))
								$item_name[$value->order_id] = $item_name[$value->order_id].", " . $value->item_name;
							else
								$item_name[$value->order_id] = $value->item_name;
						}
					}
				}

				return $item_name;
			}

			////ADDED IN VER 4.0
			/// TAX REPORTS
			function it_list_items_id($order_items = array(),$field_key = 'order_id', $return_default = '-1' , $return_formate = 'string'){
				$list 	= array();
				$string = $return_default;
				if(!$order_items){
					foreach ($order_items as $key => $order_item) {
						if(isset($order_item->$field_key)){
							if(!empty($order_item->$field_key))
								$list[] = $order_item->$field_key;
						}
					}

					$list = array_unique($list);

					if($return_formate == "string"){
						$string = implode(",",$list);
					}else{
						$string = $list;
					}
				}
				return $string;
			}

			function it_fetch_refund_tax($type = 'limit_row',$args=[]){
				global $wpdb;

				$order_status	= $args['order_status'];
				$it_from_date	= $args['it_from_date'];
				$it_to_date	= $args['it_to_date'];
				$date_format = $this->it_date_format($it_from_date);

				$sql = "  SELECT
                SUM(it_woo_order_itemmeta_tax_amnt.meta_value)  			AS refund_tax_amount,

                SUM(it_woo_order_itemmeta_ship_tax_amnt.meta_value)  	AS refund_shipping_tax_amount,

                it_shop_order_refund.post_parent										AS order_id,

                it_woo_order_items.order_item_id								AS order_item_id,

                it_shop_order_refund.ID												AS refund_order_id,

                it_woo_order_items.order_item_name							AS order_item_name,

                CONCAT(it_shop_order_refund.post_parent,'-',it_woo_order_items.order_item_name)	AS refund_group_column
                ";

				$sql .= " FROM {$wpdb->prefix}woocommerce_order_items as it_woo_order_items";

				$sql .= " LEFT JOIN  {$wpdb->prefix}posts as it_shop_order_refund ON 	it_shop_order_refund.ID=	it_woo_order_items.order_id";

				$sql .= " LEFT JOIN  {$wpdb->prefix}posts as posts ON posts.ID=	it_shop_order_refund.post_parent";

				$sql .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woo_order_itemmeta_tax_amnt ON it_woo_order_itemmeta_tax_amnt.order_item_id=it_woo_order_items.order_item_id";

				$sql .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woo_order_itemmeta_ship_tax_amnt ON it_woo_order_itemmeta_ship_tax_amnt.order_item_id=it_woo_order_items.order_item_id";

				$sql .= " WHERE 1*1";

				$sql .= " AND it_shop_order_refund.post_type='shop_order_refund' ";

				$sql .= " AND it_woo_order_items.order_item_type='tax' ";

				$sql .= " AND it_woo_order_itemmeta_tax_amnt.meta_key='tax_amount' ";

				$sql .= " AND it_woo_order_itemmeta_ship_tax_amnt.meta_key='shipping_tax_amount' ";

				if($order_status  && $order_status != '-1' and $order_status != "'-1'")$sql .= " AND posts.post_status IN (".$order_status.")";

				if ($it_from_date != NULL &&  $it_to_date !=NULL){
					$sql .= " AND (DATE(posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
				}

				$sql .= "  GROUP BY refund_group_column";

				$sql .= "  ORDER BY refund_group_column ASC";

				$wpdb->flush();

				$wpdb->query("SET SQL_BIG_SELECTS=1");

				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				$return = array();

				if($wpdb->last_error){
					//echo $wpdb->last_error;
				}

				foreach($order_items as $key => $order_item){
					$return[$order_item->refund_group_column] = $order_item;
				}

				return $return;
			}

			public static function it_get_post_meta($order_ids = '0', $columns = array(), $extra_meta_keys = array(), $type = 'all'){

				global $wpdb;

				$post_meta_keys = array();

				if(!$columns)
					foreach($columns as $key => $label){
						$post_meta_keys[] = $key;
					}

				foreach($extra_meta_keys as $key => $label){
					$post_meta_keys[] = $label;
				}

				foreach($post_meta_keys as $key => $label){
					$post_meta_keys[] = "_".$label;
				}

				$post_meta_key_string = implode("', '",$post_meta_keys);

				$sql = " SELECT * FROM {$wpdb->postmeta} AS postmeta";

				$sql .= " WHERE 1*1";

				if(strlen($order_ids) >0){
					$sql .= " AND postmeta.post_id IN ($order_ids)";
				}

				if(strlen($post_meta_key_string) >0){
					$sql .= " AND postmeta.meta_key IN ('{$post_meta_key_string}')";
				}

				if($type == 'total'){
					$sql .= " AND (LENGTH(postmeta.meta_value) > 0 AND postmeta.meta_value > 0)";
				}

				$sql .= " ORDER BY postmeta.post_id ASC, postmeta.meta_key ASC";


				$order_meta_data = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				if($wpdb->last_error){
					//echo $wpdb->last_error;
				}else{
					$order_meta_new = array();

					foreach($order_meta_data as $key => $order_meta){

						$meta_value	= $order_meta->meta_value;

						$meta_key	= $order_meta->meta_key;

						$post_id	= $order_meta->post_id;

						$meta_key 	= ltrim($meta_key, "_");

						$order_meta_new[$post_id][$meta_key] = $meta_value;

					}
				}

				return $order_meta_new;

			}
			function it_get_tax_columns($tax_type = 1){

				//refund_order_total


				if($tax_type == 'tax_group_by_city'){
					$columns = array(
						"billing_country"			=>esc_html__("Tax Country",			'it_report_wcreport_textdomain')
					,"billing_state"			=>esc_html__("Tax State",			'it_report_wcreport_textdomain')
					,"tax_city"					=>esc_html__("Tax City",			'it_report_wcreport_textdomain')
					,"tax_rate_name"			=>esc_html__("Tax Name",			'it_report_wcreport_textdomain')
					,"tax_rate_code"			=>esc_html__("Tax Rate Code",		'it_report_wcreport_textdomain')
					,"order_tax_rate"			=>esc_html__("Tax Rate",			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}elseif($tax_type == 'tax_group_by_state'){
					$columns = array(
						"billing_country"			=>esc_html__("Tax Country",			'it_report_wcreport_textdomain')
					,"billing_state"			=>esc_html__("Tax State",			'it_report_wcreport_textdomain')
					,"tax_rate_name"			=>esc_html__("Tax Name",			'it_report_wcreport_textdomain')
					,"tax_rate_code"			=>esc_html__("Tax Rate Code",		'it_report_wcreport_textdomain')
					,"order_tax_rate"			=>esc_html__("Tax Rate",			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')


					);
				}elseif($tax_type == 'tax_group_by_country'){
					$columns = array(
						"billing_country"			=>esc_html__("Tax Country",			'it_report_wcreport_textdomain')
					,"tax_rate_name"			=>esc_html__("Tax Name",			'it_report_wcreport_textdomain')
					,"tax_rate_code"			=>esc_html__("Tax Rate Code",		'it_report_wcreport_textdomain')
					,"order_tax_rate"			=>esc_html__("Tax Rate",			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}elseif($tax_type == 'tax_group_by_zip'){
					$columns = array(
						"billing_country"			=>esc_html__("Tax Country",			'it_report_wcreport_textdomain')
					,"billing_postcode"			=>esc_html__("Tax Zip",				'it_report_wcreport_textdomain')
					,"tax_rate_name"			=>esc_html__("Tax Name",			'it_report_wcreport_textdomain')
					,"tax_rate_code"			=>esc_html__("Tax Rate Code",		'it_report_wcreport_textdomain')
					,"order_tax_rate"			=>esc_html__("Tax Rate",			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}elseif($tax_type == 'tax_group_by_tax_name'){
					$columns = array(
						"tax_rate_name"				=>esc_html__("Tax Name",			'it_report_wcreport_textdomain')
					,"tax_rate_code"			=>esc_html__("Tax Rate Code",		'it_report_wcreport_textdomain')
					,"order_tax_rate"			=>esc_html__("Tax Rate",			'it_report_wcreport_textdomain')
						//,"billing_state"			=>esc_html__("Billing State",		'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}elseif($tax_type == 'tax_group_by_tax_summary'){
					$columns = array(
						"tax_rate_name"				=>esc_html__("Tax Name",			'it_report_wcreport_textdomain')
					,"order_tax_rate"			=>esc_html__("Tax Rate"	,			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}elseif($tax_type == 'tax_group_by_city_summary'){
					$columns = array(
						"billing_country"			=>esc_html__("Tax Country",			'it_report_wcreport_textdomain')
					,"billing_state"			=>esc_html__("Tax State",			'it_report_wcreport_textdomain')
					,"tax_city"					=>esc_html__("Tax City",			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}elseif($tax_type == 'tax_group_by_state_summary'){
					$columns = array(
						"billing_country"			=>esc_html__("Tax Country",			'it_report_wcreport_textdomain')
					,"billing_state"			=>esc_html__("Tax State",			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}elseif($tax_type == 'tax_group_by_country_summary'){
					$columns = array(
						"billing_country"			=>esc_html__("Tax Country",			'it_report_wcreport_textdomain')
					,"order_count"				=>esc_html__("Order Count",			'it_report_wcreport_textdomain')
					,"order_shipping"			=>esc_html__("Shipping Amt.",		'it_report_wcreport_textdomain')
					,"gross_amount"				=>esc_html__("Gross Amt.",			'it_report_wcreport_textdomain')
					,"order_total"				=>esc_html__("Net Amt.",			'it_report_wcreport_textdomain')
					,"refund_order_total"		=>esc_html__("Part Refund.",		'it_report_wcreport_textdomain')
					,"net_order_total"			=>esc_html__("(Net- Refund) Amt.",	'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}else{
					$columns = array(
						"order_tax_rate"			=>esc_html__("Tax Rate",			'it_report_wcreport_textdomain')
					,"shipping_tax_amount"		=>esc_html__("Shipping Tax",		'it_report_wcreport_textdomain')
					,"tax_amount"				=>esc_html__("Order Tax",			'it_report_wcreport_textdomain')
					,"total_tax"				=>esc_html__("Total Tax",			'it_report_wcreport_textdomain')
					);
				}

				$columns['refund_tax_amount'] 			= esc_html__("Refund Tax",				'it_report_wcreport_textdomain');
				$columns['refund_shipping_tax_amount'] 	= esc_html__("Refund Shipping Tax",		'it_report_wcreport_textdomain');
				$columns['total_tax_refund'] 			= esc_html__("Total Tax Refund",		'it_report_wcreport_textdomain');

				$columns['net_total_tax'] 			= esc_html__("Net Total Tax",		'it_report_wcreport_textdomain');

				//tax_group_by_state_summary
				return $columns;
			}
			function it_calculate_order_total($total_row,$order_item){


				$order_item->order_shipping_tax = isset( $order_item->order_shipping_tax) ?  $order_item->order_shipping_tax : 0;

				$total_row['order_count'] = isset($total_row['order_count']) ? ($total_row['order_count'] + $order_item->order_count) : $order_item->order_count;

				$total_row['gross_amount'] = isset($total_row['gross_amount']) ? ($total_row['gross_amount'] + $order_item->gross_amount) : $order_item->gross_amount;
				$total_row['order_total'] = isset($total_row['order_total']) ? ($total_row['order_total'] + $order_item->order_total) : $order_item->order_total;
				$total_row['order_shipping'] = isset($total_row['order_shipping']) ? ($total_row['order_shipping'] + $order_item->order_shipping) : $order_item->order_shipping;
				$total_row['order_shipping_tax'] = isset($total_row['order_shipping_tax']) ? ($total_row['order_shipping_tax'] + $order_item->order_shipping_tax) : $order_item->order_shipping_tax;



				$total_row['tax_amount'] 					= isset($total_row['tax_amount']) ? ($total_row['tax_amount'] + $order_item->tax_amount) : $order_item->tax_amount;
				$total_row['shipping_tax_amount'] 			= isset($total_row['shipping_tax_amount']) ? ($total_row['shipping_tax_amount'] + $order_item->shipping_tax_amount) : $order_item->shipping_tax_amount;
				$total_row['total_tax'] 					= isset($total_row['total_tax']) ? ($total_row['total_tax'] + $order_item->total_tax) : $order_item->total_tax;

				$total_row['refund_tax_amount'] 			= isset($total_row['refund_tax_amount']) ? ($total_row['refund_tax_amount'] + $order_item->refund_tax_amount) : $order_item->refund_tax_amount;
				$total_row['refund_shipping_tax_amount'] 	= isset($total_row['refund_shipping_tax_amount']) ? ($total_row['refund_shipping_tax_amount'] + $order_item->refund_shipping_tax_amount) : $order_item->refund_shipping_tax_amount;
				$total_row['total_tax_refund'] 				= isset($total_row['total_tax_refund']) 	? ($total_row['total_tax_refund'] 	+ $order_item->total_tax_refund) : $order_item->total_tax_refund;
				$total_row['net_total_tax'] 				= isset($total_row['net_total_tax']) 		? ($total_row['net_total_tax'] 		+ ($order_item->net_total_tax)) : $order_item->net_total_tax;


				$total_row['net_order_total'] = isset($total_row['net_order_total']) ? ($total_row['net_order_total'] + $order_item->net_order_total) : $order_item->net_order_total;
				$total_row['refund_order_total'] = isset($total_row['refund_order_total']) ? ($total_row['refund_order_total'] + $order_item->refund_order_total) : $order_item->refund_order_total;





				return $total_row;
			}

			function it_tax_bystate($order_items, $tax_group_by_key = 'billing_state',$tax_group_by=''){
				$last_state 	= "";
				$row_count 		= 0;
				$output 		= '';


				$columns 		= $this->it_get_tax_columns($tax_group_by);

				$total_row = array("_shipping_tax_amount" => 0,"tax_amount" => 0,"total_tax" => 0);

				$country    = $this->it_get_woo_countries();


				$tr_id = 0;

				foreach ( $order_items as $item_key => $order_item ) {

					$order_item->tax_rate_name = isset($order_item->tax_rate_name) ? trim($order_item->tax_rate_name) : '';
					$order_item->tax_rate_name = strlen($order_item->tax_rate_name)<=0 ? $order_item->tax_rate_code : $order_item->tax_rate_name;
					$order_item->billing_state = isset($order_item->billing_state) ? $order_item->billing_state : '';

					if($last_state != $order_item->$tax_group_by_key){

						if($tr_id != 0){

							$alternate = " awr-colored-tbl-total-row ";
							$output .= '<tr class="'.$alternate."row_".$item_key.'">';
							foreach($columns as $key => $value):
								$td_class = $key;
								$td_value = "";
								switch($key):

									case "tax_amount":
									case "shipping_tax_amount":
									case "total_tax":

									case "refund_tax_amount":
									case "refund_shipping_tax_amount":
									case "total_tax_refund":
									case "net_total_tax":

										$td_value = isset($total_row[$key]) ? $total_row[$key] : 0;
										$td_value = $this->price($td_value);
										break;
									default:
										$td_value = '';
										break;
								endswitch;
								$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
								$output .= $td_content;
							endforeach;
							$output .= '</tr>';
							$row_count = 0;
							$total_row = array();
						}
						$alternate = " awr-colored-tbl-row ";
						$output .= '<tr class="'.$alternate."row_".$item_key.'">';
						foreach($columns as $key => $value):
							$td_class = $key;
							$td_value = "";
							switch($key):
								case "billing_state":
									$billing_state = isset($order_item->$key) ? $order_item->$key : '';
									$billing_country = isset($order_item->billing_country) ? $order_item->billing_country : '';
									$td_value = $this->it_get_woo_bsn($billing_country, $billing_state);
									break;
								case "billing_country":
									$billing_country = isset($order_item->$key) ? $order_item->$key : '';
									$billing_country = isset($country->countries[$billing_country]) ? $country->countries[$billing_country]: $billing_country;
									$td_value = $billing_country;
									break;
								case "tax_city":
								case "billing_postcode":
									$td_value = isset($order_item->$key) ? $order_item->$key : '';
									break;
								default:
									$td_value = '';
									break;
							endswitch;
							$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
							$output .= $td_content;
						endforeach;
						$row_count = 0;
						$output .= '</tr>';
					}

					$total_row = $this->it_calculate_order_total($total_row,$order_item);

					if($row_count%2 == 0){$alternate = "alternate ";}else{$alternate = "";};
					$output .= '<tr class="'.$alternate."row_".$item_key.'">';
					foreach($columns as $key => $value):

						$td_class = $key;
						$td_value = "";
						switch($key):
							case "billing_state":
							case "billing_country":
							case "tax_city":
							case "billing_postcode":
								$td_value = '';
								break;
							case "order_count":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								break;
							case "order_tax_rate":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = sprintf("%.2f%%",$td_value);
								break;

							case "order_total_amount":
							case "order_total":
							case "order_shipping":
							case "cart_discount":
							case "order_discount":
							case "total_discount":
							case "order_tax":
							case "order_shipping_tax":
							case "total_tax":
							case "gross_amount":
							case "shipping_tax_amount":
							case "refund_tax_amount":
							case "refund_shipping_tax_amount":
							case "total_tax_refund":

							case "tax_amount":
							case "shipping_tax_amount":
							case "total_tax":

							case "refund_tax_amount":
							case "refund_shipping_tax_amount":
							case "total_tax_refund":

							case "net_order_total":
							case "refund_order_total":
							case "net_total_tax":

								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = $this->price($td_value);
								break;
							default:
								$td_value = isset($order_item->$key) ? $order_item->$key : '';
								break;
						endswitch;

						$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
						$output .= $td_content;
					endforeach;
					$output .= '</tr>';
					$last_state = $order_item->$tax_group_by_key;
					$row_count++;
					$tr_id++;

				}

				$alternate = " awr-colored-tbl-total-row ";
				$output .= '<tr class="'.$alternate."row_".$key.'">';
				foreach($columns as $key => $value):
					$td_class = $key;
					$td_value = "";
					switch($key):
						case "tax_amount":
						case "shipping_tax_amount":
						case "total_tax":

						case "refund_tax_amount":
						case "refund_shipping_tax_amount":
						case "total_tax_refund":
						case "net_total_tax":
							$td_value = isset($total_row[$key]) ? $total_row[$key] : 0;
							$td_value = $this->price($td_value);
							break;
						default:
							$td_value = '';
							break;
					endswitch;
					$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
					$output .= $td_content;
				endforeach;
				$output .= '</tr>';
				$row_count = 0;

				return $output;
			}

			function it_tax_bytaxname($order_items,$tax_group_by){
				$last_state 	= "";
				$row_count 		= 0;
				$output 		= '';
				$tr_id 			= 0;

				$columns = $this->it_get_tax_columns($tax_group_by);

				$total_row = array("shipping_tax_amount" => 0,"tax_amount" => 0,"total_tax" => 0);

				foreach ( $order_items as $key => $order_item ) {

					$order_item->tax_rate_name = isset($order_item->tax_rate_name) ? trim($order_item->tax_rate_name) : '';
					$order_item->tax_rate_name = strlen($order_item->tax_rate_name)<=0 ? $order_item->tax_rate_code : $order_item->tax_rate_name;
					$order_item->billing_state = isset($order_item->billing_state) ? $order_item->billing_state : '';

					if($last_state != $order_item->tax_rate_name){
						if($tr_id != 0){
							$alternate = " awr-colored-tbl-total-row ";
							$output .= '<tr class="'.$alternate."row_".$key.'">';
							foreach($columns as $key => $value):
								$td_class = $key;
								$td_value = "";
								switch($key):
									case "order_total":
									case "order_shipping":
									case "cart_discount":
									case "order_discount":
									case "total_discount":
									case "order_tax":
									case "order_shipping_tax":
									case "total_tax":
									case "gross_amount":

									case "tax_amount":
									case "shipping_tax_amount":
									case "total_tax":

									case "refund_tax_amount":
									case "refund_shipping_tax_amount":
									case "total_tax_refund":

									case "net_order_total":
									case "refund_order_total":
									case "net_total_tax":

										$td_value = isset($total_row[$key]) ? $total_row[$key] : 0;
										$td_value = $this->price($td_value);
										break;
									default:
										$td_value = '';
										break;
								endswitch;
								$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
								$output .= $td_content;
							endforeach;
							$output .= '</tr>';
							$row_count = 0;
							$total_row = array();
						}
						$alternate = " awr-colored-tbl-row ";
						$output .= '<tr class="'.$alternate."row_".$key.'">';
						foreach($columns as $key => $value):
							$td_class = $key;
							$td_value = "";
							switch($key):
								case "tax_rate_name":
									$td_value = isset($order_item->$key) ? $order_item->$key : '';
									break;
								default:
									$td_value = '';
									break;
							endswitch;
							$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
							$output .= $td_content;
						endforeach;
						$row_count = 0;
						$output .= '</tr>';
					}

					$total_row = $this->it_calculate_order_total($total_row,$order_item);

					if($row_count%2 == 0){$alternate = "alternate ";}else{$alternate = "";};
					$output .= '<tr class="'.$alternate."row_".$key.'">';
					foreach($columns as $key => $value):
						$td_class = $key;
						$td_value = "";
						switch($key):
							case "tax_rate_name":
								$td_value = '';
								break;
							case "billing_state":
								$billing_state = isset($order_item->$key) ? $order_item->$key : '';
								$billing_country = isset($order_item->billing_country) ? $order_item->billing_country : '';
								$td_value = $this->it_get_woo_bsn($billing_country, $billing_state);
								break;
							case "_order_count":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								break;
							case "order_tax_rate":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = sprintf("%.2f%%",$td_value);
								break;

							case "order_total":
							case "order_shipping":
							case "cart_discount":
							case "order_discount":
							case "total_discount":
							case "order_tax":
							case "order_shipping_tax":
							case "total_tax":
							case "gross_amount":

							case "tax_amount":
							case "shipping_tax_amount":
							case "total_tax":

							case "refund_tax_amount":
							case "refund_shipping_tax_amount":
							case "total_tax_refund":

							case "net_order_total":
							case "refund_order_total":
							case "net_total_tax":

								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = $this->price($td_value);
								break;
							default:
								$td_value = isset($order_item->$key) ? $order_item->$key : '';
								break;
						endswitch;
						$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
						$output .= $td_content;
					endforeach;
					$output .= '</tr>';
					$last_state = $order_item->tax_rate_name;
					$row_count++;
					$tr_id++;
				}

				$alternate = " awr-colored-tbl-total-row ";
				$output .= '<tr class="'.$alternate."row_".$key.'">';
				foreach($columns as $key => $value):
					$td_class = $key;
					$td_value = "";
					switch($key):
						case "tax_amount":
						case "shipping_tax_amount":
						case "total_tax":

						case "refund_tax_amount":
						case "refund_shipping_tax_amount":
						case "total_tax_refund":
						case "net_total_tax":

							$td_value = isset($total_row[$key]) ? $total_row[$key] : 0;
							$td_value = $this->price($td_value);
							break;
						default:
							$td_value = '';
							break;
					endswitch;
					$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
					$output .= $td_content;
				endforeach;
				$output .= '</tr>';
				$row_count = 0;
				return $output;
			}

			function it_tax_bytaxsummary($order_items,$tax_group_by){
				$last_state 	= "";
				$row_count 		= 0;
				$output 		= '';

				$columns = $this->it_get_tax_columns($tax_group_by);

				$total_row = array("_shipping_tax_amount" => 0,"tax_amount" => 0,"total_tax" => 0);

				foreach ( $order_items as $key => $order_item ) {

					$order_item->tax_rate_name = isset($order_item->tax_rate_name) ? trim($order_item->tax_rate_name) : '';
					$order_item->tax_rate_name = strlen($order_item->tax_rate_name)<=0 ? $order_item->tax_rate_code : $order_item->tax_rate_name;
					$order_item->billing_state = isset($order_item->billing_state) ? $order_item->billing_state : '';


					$total_row = $this->it_calculate_order_total($total_row,$order_item);

					if($row_count%2 == 0){$alternate = "alternate ";}else{$alternate = "";};
					$output .= '<tr class="'.$alternate."row_".$key.'">';
					foreach($columns as $key => $value):
						$td_class = $key;
						$td_value = "";
						switch($key):
							case "billing_state":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								break;
							case "_order_count":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								break;
							case "order_tax_rate":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = sprintf("%.2f%%",$td_value);
								break;
							case "_order_shipping_amount":
							case "_order_amount":
							case "order_total_amount":
							case "_shipping_tax_amount":
							case "_order_tax":
							case "_total_tax":
							case "r_order_tax":
							case "r_total_tax":
							case "order_total":
							case "order_shipping":
							case "cart_discount":
							case "order_discount":
							case "total_discount":
							case "order_tax":
							case "order_shipping_tax":
							case "total_tax":
							case "gross_amount":
							case "tax_amount":
							case "shipping_tax_amount":
							case "total_tax":

							case "refund_tax_amount":
							case "refund_shipping_tax_amount":
							case "total_tax_refund":

							case "net_order_total":
							case "refund_order_total":
							case "net_total_tax":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = $this->price($td_value);
								break;
							default:
								$td_value = isset($order_item->$key) ? $order_item->$key : '';
								break;
						endswitch;
						$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
						$output .= $td_content;
					endforeach;
					$output .= '</tr>';
					$last_state = $order_item->billing_state;
					$row_count++;
				}

				$alternate = " awr-colored-tbl-total-row ";
				$output .= '<tr class="'.$alternate."row_".$key.'">';
				foreach($columns as $key => $value):
					$td_class = $key;
					$td_value = "";
					switch($key):

						case "tax_amount":
						case "shipping_tax_amount":
						case "total_tax":

						case "refund_tax_amount":
						case "refund_shipping_tax_amount":
						case "total_tax_refund":
						case "net_total_tax":
							$td_value = isset($total_row[$key]) ? $total_row[$key] : 0;
							$td_value = $this->price($td_value);
							break;
						default:
							$td_value = '';
							break;
					endswitch;
					$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
					$output .= $td_content;
				endforeach;
				$output .= '</tr>';
				$row_count = 0;
				return $output;
			}

			function it_tax_bysummary_state($order_items, $tax_group_by_key = 'billing_state',$tax_group_by=''){
				$last_state 	= "";
				$row_count 		= 0;
				$output 		= '';

				$columns 		= $this->it_get_tax_columns($tax_group_by);

				$total_row = array("_shipping_tax_amount" => 0,"tax_amount" => 0,"total_tax" => 0);

				$country    = $this->it_get_woo_countries();//Added 20150225



				foreach ( $order_items as $key => $order_item ) {

					$order_item->tax_rate_name = isset($order_item->tax_rate_name) ? trim($order_item->tax_rate_name) : '';
					$order_item->tax_rate_name = strlen($order_item->tax_rate_name)<=0 ? $order_item->tax_rate_code : $order_item->tax_rate_name;
					$order_item->billing_state = isset($order_item->billing_state) ? $order_item->billing_state : '';

					$total_row = $this->it_calculate_order_total($total_row,$order_item);

					if($row_count%2 == 0){$alternate = "alternate ";}else{$alternate = "";};
					$output .= '<tr class="'.$alternate."row_".$key.'">';
					foreach($columns as $key => $value):
						$td_class = $key;
						$td_value = "";
						switch($key):
							case "billing_state":
								$billing_state = isset($order_item->$key) ? $order_item->$key : '';
								$billing_country = isset($order_item->billing_country) ? $order_item->billing_country : '';
								$td_value = $this->it_get_woo_bsn($billing_country, $billing_state);
								break;
							case "billing_country":
								$billing_country = isset($order_item->$key) ? $order_item->$key : '';
								$billing_country = isset($country->countries[$billing_country]) ? $country->countries[$billing_country]: $billing_country;
								$td_value = $billing_country;
								break;
							case "tax_city":
							case "billing_postcode":
								$td_value = isset($order_item->$key) ? $order_item->$key : '';
								break;
							case "_order_count":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								break;
							case "order_tax_rate":
								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = sprintf("%.2f%%",$td_value);
								break;
							case "_order_shipping_amount":
							case "_order_amount":
							case "order_total_amount":
							case "_shipping_tax_amount":
							case "_order_tax":
							case "_total_tax":
							case "r_order_tax":
							case "r_total_tax":
							case "order_total":
							case "order_shipping":
							case "cart_discount":
							case "order_discount":
							case "total_discount":
							case "order_tax":
							case "order_shipping_tax":
							case "total_tax":
							case "gross_amount":

							case "tax_amount":
							case "shipping_tax_amount":
							case "total_tax":

							case "refund_tax_amount":
							case "refund_shipping_tax_amount":
							case "total_tax_refund":
							case "net_total_tax":

							case "refund_order_total":
							case "net_order_total":


								$td_value = isset($order_item->$key) ? $order_item->$key : 0;
								$td_value = $this->price($td_value);
								break;
							default:
								$td_value = isset($order_item->$key) ? $order_item->$key : '';
								break;
						endswitch;
						$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
						$output .= $td_content;
					endforeach;
					$output .= '</tr>';
					$last_state = $order_item->billing_state;
					$row_count++;
				}

				$alternate = " awr-colored-tbl-total-row ";
				$output .= '<tr class="'.$alternate."row_".$key.'">';
				foreach($columns as $key => $value):
					$td_class = $key;
					$td_value = "";
					switch($key):
						case "tax_amount":
						case "shipping_tax_amount":
						case "total_tax":

						case "refund_tax_amount":
						case "refund_shipping_tax_amount":
						case "total_tax_refund":
						case "net_total_tax":


						case "order_total":
						case "order_shipping":
						case "cart_discount":
						case "order_discount":
						case "total_discount":
						case "order_tax":
						case "order_shipping_tax":
						case "total_tax":
						case "gross_amount":
						case "refund_order_total":
						case "net_order_total":
							$td_value = isset($total_row[$key]) ? $total_row[$key] : 0;
							$td_value = $this->price($td_value);
							break;
						case "order_count":
							$td_value = isset($total_row[$key]) ? $total_row[$key] : 0;
							break;
						default:
							$td_value = '';
							break;
					endswitch;
					$td_content = "<td class=\"{$td_class}\">{$td_value}</td>\n";
					$output .= $td_content;
				endforeach;
				$output .= '</tr>';
				$row_count = 0;
				return $output;
			}
			function it_get_maincontent_grid($items, $tax_group_by){

				switch($tax_group_by){
					case "tax_group_by_city":
						$body_grid = $this->it_tax_bystate($items,'tax_city',$tax_group_by);
						break;
					case "tax_group_by_state":
						$body_grid = $this->it_tax_bystate($items,'billing_state',$tax_group_by);
						break;
					case "tax_group_by_country":
						$body_grid = $this->it_tax_bystate($items,'billing_country',$tax_group_by);
						break;
					case "tax_group_by_zip":
						$body_grid = $this->it_tax_bystate($items,'billing_postcode',$tax_group_by);
						break;
					case "tax_group_by_tax_name":
						$body_grid = $this->it_tax_bytaxname($items,$tax_group_by);
						break;
					case "tax_group_by_tax_summary":
						$body_grid = $this->it_tax_bytaxsummary($items,$tax_group_by);
						break;
					case "tax_group_by_city_summary":
						$body_grid = $this->it_tax_bysummary_state($items,'tax_city',$tax_group_by);
						break;
					case "tax_group_by_state_summary":
						$body_grid = $this->it_tax_bysummary_state($items,'billing_state',$tax_group_by);
						break;
					case "tax_group_by_country_summary":
						$body_grid = $this->it_tax_bysummary_state($items,'billing_country',$tax_group_by);
						break;
					default:
						$body_grid = $this->it_tax_bytaxname($items,$tax_group_by);
						break;
				}

				return $body_grid;
			}
			function it_refund_id_of_shop_order($type = 'limit_row',$post_ids = '',$extra_key = array('order_total')){
				global $wpdb;


				$sql = "  SELECT
                    it_shop_order_refund.post_parent AS order_id,
                    it_shop_order_refund.ID AS shop_order_refund_id
                ";

				$sql .= " FROM {$wpdb->prefix}posts as it_shop_order_refund";

				$sql .= " WHERE 1*1" ;

				$sql .= " AND it_shop_order_refund.post_type='shop_order_refund' ";

				$sql .= " AND it_shop_order_refund.post_parent IN ($post_ids) ";

				$wpdb->flush();

				$wpdb->query("SET SQL_BIG_SELECTS=1");

				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				$return = array();

				if($wpdb->last_error){
					//echo $wpdb->last_error;
				}



				$shop_order_refund_ids 			= $this->it_list_items_id($order_items,'shop_order_refund_id');
				$refund_postmeta_datas 			= $this->it_get_post_meta($shop_order_refund_ids, array(),$extra_key,'no');
				$meta_keies						= array();

				foreach ( $order_items as $key => $order_item ) {
					$shop_order_refund_id = $order_item->shop_order_refund_id;
					$postmeta_data 	= isset($refund_postmeta_datas[$shop_order_refund_id]) ? $refund_postmeta_datas[$shop_order_refund_id] : array();
					foreach($postmeta_data as $postmeta_key => $postmeta_value){
						$order_items[$key]->{'refund_'.$postmeta_key}	= $postmeta_value;
						$meta_keies[$postmeta_key] = 'refund_'.$postmeta_key;
					}
				}

				$return = array();
				foreach ( $order_items as $key => $order_item ) {
					$order_id = $order_item->order_id;

					if(isset($return[$order_id])){
						foreach($meta_keies as $meta_key => $refund_meta_key){

							$v1 = isset($return[$order_id]->$refund_meta_key) ? $return[$order_id]->$refund_meta_key : 0;
							$v2 = isset($order_item->$refund_meta_key) ? $order_item->$refund_meta_key : 0;


							$return[$order_id]->$refund_meta_key = $v1 + $v2;

						}
					}else{
						$return[$order_id] = $order_item;
					}
				}

				return $return;
			}
            ///////////////////////////
            /// END TAX REPORTS
            ///////////////////////////



			////ADDED IN VER 4.0
            /// GET TAX RATE, NAME
			function it_get_tax_rate_name($order_id_string = array(),$order_item_type = "tax"){
				global $wpdb;
				$item_name = array();
				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				if(strlen($order_id_string) > 0){
					$sql = "SELECT
					order_items.order_id as order_id,
					order_items.order_item_id AS item_id,
					order_meta.meta_value AS item_name,
					order_meta1.meta_value AS item_rate,
					order_meta2.meta_value AS item_rate_id
					FROM {$wpdb->prefix}woocommerce_order_items as order_items
					INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_meta ON order_items.order_item_id=order_meta.order_item_id
					INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_meta1 ON order_items.order_item_id=order_meta1.order_item_id
					INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_meta2 ON order_items.order_item_id=order_meta2.order_item_id
					WHERE order_item_type ='{$order_item_type}' AND order_id IN ({$order_id_string})
					AND order_meta.meta_key='label' AND order_meta1.meta_key='tax_amount' AND order_meta2.meta_key='rate_id'";
					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 


					if(!$order_items){
						foreach($order_items as $key => $value){
							$item_name[$value->item_id]['name'] = $value->item_name;
							$item_name[$value->item_id]['amount'] = $value->item_rate;
							$rate=new WC_Tax();
							$item_name[$value->item_id]['rate'] = $rate->get_rate_percent($value->item_rate_id);
						}
					}
				}

				return $item_name;
			}


			var $coupons_codes = array();
			function it_get_woo_coupons($order_id = 0, $all = false){
				global $wpdb;

				if($order_id == 0 and $all == false) return '';

				$coupons_code = '';

				if(!isset($this->coupons_codes[$order_id])){
					$sql	= "SELECT * FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items WHERE it_woocommerce_order_items.order_item_type = 'coupon'";

					if($order_id > 0) $sql	.= " AND it_woocommerce_order_items.order_id={$order_id}";

					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					$coupons_code = "";
					if($order_items){
						$coupons = array();
						foreach($order_items as $key => $value){
							$coupons[] = $value->order_item_name;
						}

//print_r($coupons);

						if($coupons){
							$coupons_code = implode(", ",$coupons);
						}
//return $coupons_code;
					}

					$this->coupons_codes[$order_id] = $coupons_code;
				}else{
					$coupons_code = $this->coupons_codes[$order_id];
				}


				return $coupons_code;

			}

			////ADDED IN VER4.0
            /// EMAIL REPORTS
			function it_fetch_emails_of_purchased_customer($params){

				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';



				$sql_columns =" it_postmeta2.meta_value 		AS 	billing_email,
							    it_postmeta4.meta_value 	AS  customer_id";

				$sql_joins= " {$wpdb->prefix}posts as it_posts
			    LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID
			    LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta4 ON it_postmeta4.post_id=it_posts.ID";

				$sql_condition = "
                it_posts.post_type='shop_order'
                AND it_postmeta2.meta_key='_billing_email'
                AND it_postmeta4.meta_key='_customer_user'";

				$it_from_date_condition='';
				if ($it_from_date != NULL &&  $it_to_date !=NULL){
					$it_from_date_condition = " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format') ";
				}

				$it_order_status_condition='';
				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$it_order_status_condition = " AND it_posts.post_status IN (".$it_order_status.")";

				$it_hide_os_condition='';
				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$it_hide_os_condition = " AND it_posts.post_status NOT IN ('".$it_hide_os."')";

				$sql_group_by = "  GROUP BY  it_postmeta2.meta_value Order By billing_email ASC";

				$sql = "SELECT $sql_columns FROM $sql_joins  WHERE $sql_condition $it_from_date_condition
                $it_order_status_condition $it_hide_os_condition
				$sql_group_by";
				//echo $sql;

				$customer = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $customer;
			}

			////ADDED IN VER4.0
            /// EMIAL REPORTS
			function it_fetch_old_emails_of_customer($params){
                global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';


				$sql_columns = "
				it_billing_email.meta_value AS billing_email";

				$sql_joins = " {$wpdb->prefix}posts AS posts
				LEFT JOIN {$wpdb->postmeta} AS it_billing_email ON it_billing_email.post_id = posts.ID";

				$sql_condition = " posts.post_type		= 'shop_order'
				AND it_billing_email.meta_key 	= '_billing_email'";

				$it_from_date_condition='';
				if ($it_from_date != NULL){
					$it_from_date_condition = " AND DATE(posts.post_date) < '".$it_from_date."'";
				}

				$it_hide_os_condition='';
				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$it_hide_os_condition= " AND posts.post_status NOT IN ('".$it_hide_os."')";

				$it_order_status_condition='';
				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$it_order_status_condition= " AND posts.post_status IN (".$it_order_status.")";

				$sql_group_by = " GROUP BY billing_email";

				$sql_order_by = " ORDER BY billing_email ASC";

				$sql = "SELECT $sql_columns FROM $sql_joins  WHERE $sql_condition $it_from_date_condition
                $it_order_status_condition $it_hide_os_condition
				$sql_group_by";
//echo $sql;
				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				$old_customers = array();
				foreach($order_items as $order_key => $order_item){
					$old_customers[$order_item->billing_email] = $order_item->billing_email;
				}
				return $old_customers;
			}

			////ADDED IN VER4.0
			function it_product_last_sale_order_date($product_ids = ''){
				global $wpdb;

				$sql_columns = " it_woocommerce_order_itemmeta_product_id.meta_value 		AS product_id,
				it_woocommerce_order_itemmeta_variation_id.meta_value 	AS variation_id,
				MAX(it_shop_order.post_date) AS order_date";
				//$sql .= ", MAX(woocommerce_order_items.order_id) 				AS order_id";

				$sql_joins = "{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}posts as it_shop_order ON it_shop_order.id=it_woocommerce_order_items.order_id
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_id ON it_woocommerce_order_itemmeta_product_id.order_item_id=it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_variation_id ON it_woocommerce_order_itemmeta_variation_id.order_item_id=it_woocommerce_order_items.order_item_id";

				$sql_condition = "it_shop_order.post_type	= 'shop_order'
				AND it_woocommerce_order_itemmeta_product_id.meta_key 	= '_product_id'
				AND it_woocommerce_order_itemmeta_variation_id.meta_key 	= '_variation_id'";

				$product_ids_condition ='';
				if(!empty($product_ids)){
					$product_ids_condition = " AND it_woocommerce_order_itemmeta_product_id.meta_value IN ($product_ids)";
				}

				$sql_group_by = " GROUP BY product_id,variation_id";
//echo $sql;
				$sql = "SELECT $sql_columns FROM $sql_joins  WHERE $sql_condition $product_ids_condition
				$sql_group_by";


				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				if($wpdb->last_error){
					//echo $wpdb->last_error;
					return array();
				}



				$order_date =  array();
				foreach($order_items as $order_key => $order_item){
					$product_id 		= $order_item->product_id;
					$variation_id 		= $order_item->variation_id;
					if($variation_id > 0){
						$order_date['variation_id'][$variation_id] = $order_item->order_date;
					}

					$order_date['product_id'][$product_id] = $order_item->order_date;
				}

				//$this->print_array($order_date);

				return $order_date;

			}

			///GET VARIATION
			function it_get_variaiton_attributes($variation_by = 'variation_id', $variation_ids = '', $order_item_ids = ''){
				global $wpdb;

				$sql = "SELECT TRIM(LEADING 'attribute_' FROM meta_key)  AS attribute_key
				FROM {$wpdb->prefix}postmeta
				WHERE meta_key LIKE 'attribute%'";
				if($variation_ids){
					$sql .= " AND post_id IN ({$variation_ids})";
				}

				$sql .= " GROUP BY attribute_key ORDER BY attribute_key ASC";

				$attributes =  $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//$this->print_array($attributes);

				$new_attr 			= array();
				$attribute_keys 	= array();
				$attribute_labels 	= array();
				$return 			= array();
				$variations 		= array();

				$new_item_attr_variation_id		= array();
				$new_item_attr_order_item_id	= array();
				$order_item_variations			= array();

				//return $new_attr;
				if($attributes){
					foreach($attributes as $key => $value){
						$attribute_keys[]	= $value->attribute_key;
					}
				}

				//$this->print_array($attribute_keys);

				$attribute_keys = array_unique($attribute_keys);
				sort($attribute_keys);


				$attribute_meta_key = implode("', '",$attribute_keys);

				$sql = "SELECT TRIM(LEADING 'pa_' FROM it_woocommerce_order_itemmeta.meta_key) AS attribute_key, it_woocommerce_order_itemmeta.meta_value AS attribute_value, it_woocommerce_order_itemmeta.order_item_id, it_woocommerce_order_itemmeta.meta_key AS meta_key";
				if($variation_by == 'variation_id'){
					$sql .= ", it_woocommerce_order_itemmeta_variation_id.meta_value AS variation_id";
				}else{
					$sql .= ", 0 AS variation_id";
				}

				$sql .= " FROM {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta";
				if($variation_by == 'variation_id'){
					$sql .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_variation_id 			ON it_woocommerce_order_itemmeta_variation_id.order_item_id			=	it_woocommerce_order_itemmeta.order_item_id";
				}

				$sql .= " WHERE it_woocommerce_order_itemmeta.meta_key IN ('{$attribute_meta_key}')";

				if($variation_by == 'variation_id'){
					$sql .= " AND it_woocommerce_order_itemmeta_variation_id.meta_key 			= '_variation_id'";
					$sql .= " AND it_woocommerce_order_itemmeta_variation_id.meta_value > 0";
					//$sql .= " AND it_woocommerce_order_itemmeta_variation_id.meta_value = 4859";

					if($variation_ids){
						$sql .= " AND it_woocommerce_order_itemmeta_variation_id.meta_value IN ({$variation_ids})";
					}
				}

				if($order_item_ids){
					$sql .= " AND it_woocommerce_order_itemmeta.order_item_id IN ({$order_item_ids})";
				}



				$item_attributes =  $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				//$this->print_array($item_attributes);

				if($item_attributes){
					foreach($item_attributes as $key => $value){
						$attribute_key 		= $value->attribute_key;
						$attribute_key 		= ucwords(str_replace("-"," ",$attribute_key));

						$attribute_value	= $value->attribute_value;
						$attribute_value 	= ucwords(str_replace("-"," ",$attribute_value));

						$new_item_attr_variation_id[$value->variation_id][$attribute_key] = $attribute_value;
						$new_item_attr_order_item_id[$value->order_item_id][$attribute_key] = $attribute_value;

						$attribute_labels[] = $attribute_key;
					}
				}

				$attribute_labels = array_unique($attribute_labels);
				sort($attribute_labels);

				//$this->print_array($new_item_attr_order_item_id);

				//By Variation ID
				if($variation_by == 'variation_id'){
					foreach($new_item_attr_variation_id as $id => $attribute_values){
						foreach($attribute_labels as $key2 => $value2){
							//$this->print_array($attribute_values);
							if(isset($attribute_values[$value2]))
								$new_item_attr_variation_id[$id]['varations'][] = $attribute_values[$value2];
						}
					}

					foreach($new_item_attr_variation_id as $id => $attribute_values){
						$new_item_attr_variation_id[$id]['varation_string'] 	= implode(", ",$attribute_values['varations']);
						$variations[$id]['varation_string'] 					= implode(", ",$attribute_values['varations']);
					}
				}

				//$this->print_array($new_item_attr_variation_id);

				//By Order Item ID
				foreach($new_item_attr_order_item_id as $id => $attribute_values){
					foreach($attribute_labels as $key2 => $value2){
						//$this->print_array($attribute_values);
						if(isset($attribute_values[$value2]))
							$new_item_attr_order_item_id[$id]['varations'][] = $attribute_values[$value2];
					}
				}

				foreach($new_item_attr_order_item_id as $id => $attribute_values){
					$new_item_attr_order_item_id[$id]['varation_string'] 	= implode(", ",$attribute_values['varations']);
					$order_item_variations[$id]['varation_string'] 		= implode(", ",$attribute_values['varations']);
				}

				//$this->print_array($order_item_variations);

				$return['attribute_keys']		= $attribute_keys;
				$return['variation_labels']		= $attribute_labels;
				$return['varation_string']		= $variations;
				$return['item_varation_string']	= $order_item_variations;
				$return['varation']				= $new_item_attr_variation_id;

				//$this->print_array($return);

				return $return;
			}


			var $stored_variations	= array();
			function it_get_woo_variation($order_item_id = 0){
				global $wpdb;
					$v = "";
					$sql = "
					SELECT
					it_postmeta_variation.meta_value AS product_variation,
					woocommerce_order_itemmeta.meta_value as variation_id
					FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_variation ON it_postmeta_variation.post_id = woocommerce_order_itemmeta.meta_value
					WHERE it_woocommerce_order_items.order_item_id={$order_item_id}

					AND it_woocommerce_order_items.order_item_type = 'line_item'
					AND woocommerce_order_itemmeta.meta_key = '_variation_id'
					AND it_postmeta_variation.meta_key like 'attribute_%'";
					//echo $sql;
					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

					//print_r($order_items);

					if(!$order_items){
						$it_variation_id = isset($order_items[0]->variation_id) ? $order_items[0]->variation_id : 0;
						if(isset($this->stored_variations[$it_variation_id])){
							$v = $this->stored_variations[$it_variation_id];
						}else{
							$variation = array();
							if($order_items)
							foreach($order_items as $key=>$value){
								$variation[] = $value->product_variation;
							}
							$v = ucwords (implode(", ", $variation));

							$v = str_replace("-"," ",$v);
							$this->stored_variations[$it_variation_id] = $v;
							//$this->print_array($this->stored_variations);
						}

					}
					return $v;
			}

			function it_get_pt_product_id($id, $taxonomy = 'product_type', $termkey = 'name'){
			$term_name ="";
			if(!isset($this->terms_by[$taxonomy][$id])){
				$id			= (integer)$id;
				$terms		= get_the_terms($id, $taxonomy);
				$termlist	= array();
				if($terms && !empty($terms)){
					foreach ( $terms as $term ) {

						if( $term->$termkey == 'grouped' )
							$it_product_type= 'Grouped product';
						elseif ( $term->$termkey == 'external' )
							$it_product_type=  'External/Affiliate product';
						elseif ( $term->$termkey == 'simple' )
							$it_product_type=  'Simple product';
						elseif ( $term->$termkey == 'variable' )
							$it_product_type= 'Variable';
						else
							$it_product_type=  ucwords($term->name);

							$termlist[] = $it_product_type;
					}
					if(!empty($termlist)){
						$term_name =  implode( ', ', $termlist );
					}
				}
				$this->terms_by[$taxonomy][$id] = $term_name;
			}else{
				$term_name = $this->terms_by[$taxonomy][$id];
			}
			return $term_name;


		}

			//GET ALL POST META
			function it_get_full_post_meta($order_id,$is_product = false){
				$order_meta	= get_post_meta($order_id);

				$order_meta_new = array();
				if($is_product){
					foreach($order_meta as $omkey => $omvalue){
						$order_meta_new[$omkey] = $omvalue[0];
					}
				}else{
					foreach($order_meta as $omkey => $omvalue){
						$omkey = substr($omkey, 1);
						$order_meta_new[$omkey] = $omvalue[0];
					}
				}
				return $order_meta_new;
			}


			//GET ITEM COUNT
			function it_get_oi_count($order_id_string = array(),$order_item_type = 'line_item'){
				global $wpdb;
				$item_name = array();
				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				if(strlen($order_id_string) > 0){
					$sql = "SELECT it_woocommerce_order_items.order_id as order_id, COUNT(*) AS item_count FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items";
					$sql .= " WHERE order_item_type='{$order_item_type}'";
					$sql .= " AND order_id IN ({$order_id_string})";
					$sql .= " GROUP BY it_woocommerce_order_items.order_id";
					$sql .= " ORDER BY it_woocommerce_order_items.order_id DESC";

					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

					if(!empty($order_items)){
						foreach($order_items as $key => $value){
							$item_name[$value->order_id] = $value->item_count;
						}
					}
				}

				return $item_name;
				//return $order_items_counts;
			}

			function it_get_part_order_refund_amount($order_id_string = array()){
				global $wpdb;

				$item_name = array();
				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				if(strlen($order_id_string) > 0){



					$sql = "SELECT
							posts.post_parent 				as order_id
							,SUM(postmeta.meta_value) 		as total_amount
							,SUM(order_tax.meta_value) 		as order_tax";


					$sql .= "

						FROM {$wpdb->prefix}posts as posts

						LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	posts.ID

						LEFT JOIN  {$wpdb->prefix}postmeta as order_tax ON order_tax.post_id	=	posts.ID";

					$sql .= " LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.ID	=	posts.post_parent";

					$sql .= " WHERE posts.post_type = 'shop_order_refund' AND  postmeta.meta_key='_refund_amount'";

					$sql .= " AND  order_tax.meta_key='_order_tax'";

					if(strlen($order_id_string) > 0){
						$sql .= "AND posts.post_parent IN ({$order_id_string})";
					}

					$sql .= "AND shop_order.post_status NOT IN ('wc-refunded')";

					$sql .= " GROUP BY  posts.post_parent";

					$sql .= " ORDER BY posts.post_parent DESC";

					$order_items = $this->get_results($sql);





					if(!$order_items){
						foreach($order_items as $key => $value){
							if(isset($item_name[$value->order_id]['refund_amount'])){
								$item_name[$value->order_id]['refund_amount'] 	= $item_name[$value->order_id]['refund_amount'] + $value->total_amount;
								$item_name[$value->order_id]['order_tax'] 		= $item_name[$value->order_id]['order_tax'] + $value->order_tax;
							}else{
								$item_name[$value->order_id]['refund_amount'] 	= $value->total_amount;
								$item_name[$value->order_id]['order_tax'] 		= $value->order_tax;
							}
						}
					}
				}

				return $item_name;

			}


			//GET REFUND PART AMMOUNT
			function it_get_por_amount($order_id_string = array()){
				global $wpdb;

				$item_name = array();
				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				if(strlen($order_id_string) > 0){



					$sql = "SELECT
						it_posts.post_parent as order_id
						,SUM(postmeta.meta_value) 		as total_amount";

					$sql .= "

					FROM {$wpdb->prefix}posts as it_posts

					LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	it_posts.ID";

					$sql .= " LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.ID	=	it_posts.post_parent";

					$sql .= " WHERE it_posts.post_type = 'shop_order_refund' AND  postmeta.meta_key='_refund_amount'";

					if(strlen($order_id_string) > 0){
						$sql .= "AND it_posts.post_parent IN ({$order_id_string})";
					}

					$sql .= "AND shop_order.post_status NOT IN ('wc-refunded')";

					$sql .= " GROUP BY  it_posts.post_parent";

					$sql .= " ORDER BY it_posts.post_parent DESC";

					$order_items = $this->get_results($sql);

					//$this->print_array($order_items);

					//$this->print_sql($sql);

					if(!$order_items){
						foreach($order_items as $key => $value){
							if(isset($item_name[$value->order_id]))
								$item_name[$value->order_id] = $item_name[$value->order_id] + $value->total_amount;
							else
								$item_name[$value->order_id] = $value->total_amount;
						}
					}
				}

				return $item_name;

			}

			function it_get_por_amount_individual($order_id_string = array(),$order_item_id='',$based='order',$it_from_date='',$it_to_date=''){
				global $wpdb;

				$date_format = $this->it_date_format($it_from_date);

				$item_name = array();
				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				if(strlen($order_id_string) > 0){



					$sql = "SELECT
						it_posts.post_parent as order_id
						,SUM(postmeta.meta_value) 		as total_amount,
                        order_itemmeta1.meta_value as PR
						";

					$sql .= "

					FROM {$wpdb->prefix}posts as it_posts

					LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	it_posts.ID";

					$sql .= " LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.ID	=	it_posts.post_parent

					LEFT JOIN {$wpdb->prefix}woocommerce_order_items as order_items ON it_posts.ID	=	order_items.order_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_itemmeta ON order_items.order_item_id	=	order_itemmeta.order_item_id
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_itemmeta1 ON order_items.order_item_id	=	order_itemmeta1.order_item_id
					";
					if($based=='item') {

						$sql .= " LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_itemmeta2 ON order_items.order_item_id	=	order_itemmeta2.order_item_id ";
					}

					$sql .= " WHERE it_posts.post_type = 'shop_order_refund' AND  postmeta.meta_key='_refund_amount'";

					if(strlen($order_id_string) > 0 && $based=='order') {
						$sql .= " AND order_itemmeta.meta_key='_refunded_item_id' AND order_itemmeta.meta_value='$order_item_id' ";
					}



					$sql.=" AND order_itemmeta1.meta_key='_line_total' ";



					if($based=='item') {
					    $sql .= " AND order_itemmeta.meta_key='_refunded_item_id' AND order_itemmeta.meta_value<>'' ";
						$sql .= " AND (order_itemmeta2.meta_key='_product_id' OR order_itemmeta2.meta_key='_variation_id') AND  order_itemmeta2.meta_value='$order_item_id' ";
					    $sql .= " AND (DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";

					}

					if(strlen($order_id_string) > 0 && $based=='order'){
						$sql .= " AND it_posts.post_parent IN ({$order_id_string}) ";
						$order_item_id=$order_id_string;
					}

					$sql .= " AND shop_order.post_status NOT IN ('wc-refunded') ";

					$sql .= " GROUP BY  it_posts.post_parent ";

					$sql .= " ORDER BY it_posts.post_parent DESC";
                    //echo $sql;

					$order_items = $this->get_results($sql);

					//$this->print_array($order_items);

					//$this->print_sql($sql);

					if(!$order_items){
						foreach($order_items as $key => $value){
							if(isset($item_name[$order_item_id]))
								$item_name[$order_item_id] = $item_name[$order_item_id] + $value->PR;
							else
								$item_name[$order_item_id] = $value->PR;
						}
					}
				}
				//print_r($item_name);

				return $item_name;

			}

			function it_get_or_amount($order_id_string = array()){
				global $wpdb;

					$item_name = array();
					if(is_array($order_id_string)){
						$order_id_string = implode(",",$order_id_string);
					}

					if(strlen($order_id_string) > 0){



						$sql = "SELECT
							it_posts.post_parent as order_id
							,SUM(postmeta.meta_value) 		as total_amount";

						$sql .= "

						FROM {$wpdb->prefix}posts as it_posts

						LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	it_posts.ID";

						$sql .= " LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.ID	=	it_posts.post_parent";

						$sql .= " WHERE it_posts.post_type = 'shop_order_refund' AND  postmeta.meta_key='_refund_amount'";

						if(strlen($order_id_string) > 0){
							$sql .= "AND it_posts.post_parent IN ({$order_id_string})";
						}

						$sql .= " GROUP BY  it_posts.post_parent";

						$sql .= " ORDER BY it_posts.post_parent DESC";

						$order_items = $this->get_results($sql);

						//$this->print_array($order_items);

						if(!$order_items){
							foreach($order_items as $key => $value){
								if(isset($item_name[$value->order_id]))
									$item_name[$value->order_id] = $item_name[$value->order_id] + $value->total_amount;
								else
									$item_name[$value->order_id] = $value->total_amount;
							}
						}
					}

					return $item_name;

			}


			function get_results($sql_query = ""){
				global $wpdb;
				$wpdb->query("SET SQL_BIG_SELECTS=1");
				$results = $wpdb->get_results($sql_query); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared


				if($wpdb->last_error){
					//echo $wpdb->last_error;
					//$this->print_sql($sql_query);
				}

				$wpdb->flush();
				return $results;

			}

			//GET TAX GROSS
			function it_get_number_percentage($first_value = 0, $second_value = 0, $default = 0){
				$return = $default;
				$first_value = trim($first_value);
				$second_value = trim($second_value);

				if(is_numeric($first_value) && is_numeric($second_value) && $first_value > 0  && $second_value > 0){
					$return = (($first_value)/($second_value))*100;
				}

				return $return;
			}


			////ADDED IN VER4.5
			/// CUSTOM WORK - PRODUCT PER USERS
			function it_get_product_purchase_per_user($type = 'limit_row', $items_only = true, $id = '-1',$params=[]) {
				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';
				$it_customer_id=isset($params['users'][0]) ? $params['users'][0] :'';
				$it_customer_email=isset($params['users'][1]) ? $params['users'][1] :'';

				$sql_columns = "
                it_woocommerce_order_items.order_item_name			AS 'ItemName'
                ,it_woocommerce_order_items.order_item_id
                ,SUM(woocommerce_order_itemmeta.meta_value)		AS 'order_count'
                ,SUM(it_woocommerce_order_itemmeta2.meta_value)	AS 'total_amount'
                ,it_woocommerce_order_itemmeta3.meta_value			AS ProductID";


				$sql_joins = " {$wpdb->prefix}woocommerce_order_items  as it_woocommerce_order_items
                LEFT JOIN	{$wpdb->prefix}posts as it_posts ON it_posts.ID =	it_woocommerce_order_items.order_id
                LEFT JOIN   {$wpdb->prefix}postmeta as it_postmeta ON it_posts.ID =	it_postmeta.post_id

                LEFT JOIN	{$wpdb->prefix}woocommerce_order_itemmeta 	as woocommerce_order_itemmeta 	ON woocommerce_order_itemmeta.order_item_id =	it_woocommerce_order_items.order_item_id
                LEFT JOIN	{$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta2 	ON it_woocommerce_order_itemmeta2.order_item_id =	it_woocommerce_order_items.order_item_id
                LEFT JOIN	{$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta3 	ON it_woocommerce_order_itemmeta3.order_item_id =	it_woocommerce_order_items.order_item_id";

				$sql_condition = "
                it_posts.post_type 								=	'shop_order'
                AND woocommerce_order_itemmeta.meta_key			=	'_qty'
                AND it_woocommerce_order_itemmeta2.meta_key		=	'_line_total'
                AND it_woocommerce_order_itemmeta3.meta_key 		=	'_product_id'
                AND it_woocommerce_order_itemmeta3.meta_value='".$id."'

                AND it_postmeta.meta_key='_customer_user'
                AND it_postmeta.meta_value='".$it_customer_id."'
                ";

				if ($it_from_date != NULL &&  $it_to_date !=NULL){
					$it_from_date_condition= " AND DATE_FORMAT(it_posts.post_date, '%Y-%m-%d') BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$it_order_status_condition= " AND it_posts.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$it_hide_os_condition= " AND it_posts.post_status NOT IN (".$it_hide_os.")";


				$sql_group_by= "  group by ProductID ";
				$sql_order_by=" ORDER BY ProductID ";
				//$sql_limit = " LIMIT 20";

				$sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition
                $it_from_date_condition $it_order_status_condition
                $it_hide_os_condition  $sql_group_by $sql_order_by";

			//	return $sql;

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}


			////ADDED IN VER4.0
            /// ROLE/GROUP ADDON
			function it_get_woo_role_top_products($type = 'limit_row', $items_only = true, $id = '-1',$params=[]){
				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$sql_columns = "
                it_woocommerce_order_items.order_item_name			AS 'ItemName'
                ,it_woocommerce_order_items.order_item_id
                ,SUM(woocommerce_order_itemmeta.meta_value)		AS 'order_count'
                ,SUM(it_woocommerce_order_itemmeta2.meta_value)	AS 'total_amount'
                ,it_woocommerce_order_itemmeta3.meta_value			AS ProductID

                ,MONTH(it_posts.post_date) 					as month_number
                ,DATE_FORMAT(it_posts.post_date, '%Y-%m')		as month_key";

				$sql_joins = " {$wpdb->prefix}woocommerce_order_items  as it_woocommerce_order_items
                LEFT JOIN	{$wpdb->prefix}posts as it_posts ON it_posts.ID =	it_woocommerce_order_items.order_id
                LEFT JOIN	{$wpdb->prefix}woocommerce_order_itemmeta 	as woocommerce_order_itemmeta 	ON woocommerce_order_itemmeta.order_item_id =	it_woocommerce_order_items.order_item_id
                LEFT JOIN	{$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta2 	ON it_woocommerce_order_itemmeta2.order_item_id =	it_woocommerce_order_items.order_item_id
                LEFT JOIN	{$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta3 	ON it_woocommerce_order_itemmeta3.order_item_id =	it_woocommerce_order_items.order_item_id";

				$sql_condition = "
                it_posts.post_type 								=	'shop_order'
                AND woocommerce_order_itemmeta.meta_key			=	'_qty'
                AND it_woocommerce_order_itemmeta2.meta_key		=	'_line_total'
                AND it_woocommerce_order_itemmeta3.meta_key 		=	'_product_id'
                AND it_woocommerce_order_itemmeta3.meta_value='".$id."'";

				if ($it_from_date != NULL &&  $it_to_date !=NULL){
					$it_from_date_condition= " AND DATE_FORMAT(it_posts.post_date, '%Y-%m') BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$it_order_status_condition= " AND it_posts.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$it_hide_os_condition= " AND it_posts.post_status NOT IN (".$it_hide_os.")";


				$sql_group_by= "  group by month_number ";
				$sql_order_by=" ORDER BY month_number ";
				//$sql_limit = " LIMIT 20";

				$sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition
                $it_from_date_condition $it_order_status_condition
                $it_hide_os_condition  $sql_group_by $sql_order_by";

				//echo $sql;

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			function it_get_woo_role_amount($type = 'limit_row', $items_only = true, $id = '-1',$params=[]){
				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$sql_columns= "
                SUM(it_postmeta1.meta_value) AS 'total_amount'
                ,it_postmeta2.meta_value AS 'billing_email'
                ,it_postmeta3.meta_value AS 'billing_first_name'
                ,Count(it_postmeta2.meta_value) AS 'order_count'
                ,it_postmeta4.meta_value AS  customer_id
                ,it_postmeta5.meta_value AS  billing_last_name
                ,CONCAT(it_postmeta3.meta_value, ' ',it_postmeta5.meta_value) AS billing_name


                ,usermeta.meta_value as user_role
                ,MONTH(shop_order.post_date) 					as month_number
                ,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key
                ";

				$sql_joins = "{$wpdb->prefix}posts as it_posts
                LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID
                LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID
                LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_posts.ID

                ";

				$sql_joins .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta4 ON it_postmeta4.post_id=it_posts.ID";
				$sql_joins .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta5 ON it_postmeta5.post_id=it_posts.ID


                LEFT JOIN  {$wpdb->prefix}usermeta as usermeta ON it_postmeta4.meta_value=usermeta.user_id
                LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.id =	it_posts.ID


                ";


				$sql_condition = "
                it_posts.post_type='shop_order'
                AND it_postmeta1.meta_key='_order_total'
                AND it_postmeta2.meta_key='_billing_email'
                AND it_postmeta3.meta_key='_billing_first_name'
                AND it_postmeta4.meta_key='_customer_user'
                AND it_postmeta5.meta_key='_billing_last_name'


                AND usermeta.meta_key='{$wpdb->prefix}capabilities'
                AND it_postmeta4.meta_value='".$id."'";


				if ($it_from_date != NULL &&  $it_to_date !=NULL){
					$it_from_date_condition= " AND DATE_FORMAT(it_posts.post_date, '%Y-%m') BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}


				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$it_order_status_condition= " AND it_posts.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$it_hide_os_condition= " AND it_posts.post_status NOT IN (".$it_hide_os.")";

				$sql_group_by= "  group by month_number ";
				$sql_order_by=" ORDER BY month_number ";

				$sql = "SELECT $sql_columns FROM $sql_joins  WHERE $sql_condition
				$it_from_date_condition
				$it_order_status_condition $it_hide_os_condition
				$sql_group_by $sql_order_by
				";

				//return $sql;

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			//CROSS TAB
			var $products_list_in_category = NULL;
			public function it_get_woo_products_items($type = 'limit_row', $items_only = true, $id = '-1',$params=[]){

				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$sql = "
				SELECT
				it_woocommerce_order_itemmeta_product.meta_value 			as id
				,it_woocommerce_order_items.order_item_name 				as product_name
				,it_woocommerce_order_items.order_item_name 				as item_name
				,it_woocommerce_order_items.order_item_id 					as order_item_id
				,it_woocommerce_order_itemmeta_product.meta_value 			as product_id




				,SUM(it_woocommerce_order_itemmeta_product_total.meta_value) 	as total
				,SUM(it_woocommerce_order_itemmeta_product_qty.meta_value) 	as quantity

				,MONTH(shop_order.post_date) 					as month_number
				,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key

				FROM {$wpdb->prefix}woocommerce_order_items 			as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product 			ON it_woocommerce_order_itemmeta_product.order_item_id=it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}posts 						as shop_order 									ON shop_order.id								=	it_woocommerce_order_items.order_id

				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_total 	ON it_woocommerce_order_itemmeta_product_total.order_item_id=it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_qty		ON it_woocommerce_order_itemmeta_product_qty.order_item_id		=	it_woocommerce_order_items.order_item_id";



				$sql .= "
				WHERE
				it_woocommerce_order_itemmeta_product.meta_key		=	'_product_id'
				AND it_woocommerce_order_items.order_item_type		=	'line_item'
				AND shop_order.post_type						=	'shop_order'

				AND it_woocommerce_order_itemmeta_product_total.meta_key		='_line_total'
				AND it_woocommerce_order_itemmeta_product_qty.meta_key			=	'_qty'";

				if($id != NULL  && $id != '-1'){
					$sql .= " AND it_woocommerce_order_itemmeta_product.meta_value = {$id} ";
				}

				if ($it_from_date != NULL &&  $it_to_date !=NULL)
					$sql .= " AND DATE_FORMAT(shop_order.post_date, '%Y-%m') BETWEEN ('" . $it_from_date . "') and ('" . $it_to_date . "')";

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";


				$sql .= " group by month_number ORDER BY month_number";

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

			}//end cross


			//CROSS TAB VARIATION
			public function it_get_product_var_items($type = 'limit_row', $items_only = true, $id = '-1',$params=[]){
				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$category_id 				= "-1";


				$sql = "
					SELECT
					it_woocommerce_order_itemmeta_variation.meta_value			as id
					,it_woocommerce_order_itemmeta_product.meta_value 			as product_id
					,it_woocommerce_order_items.order_item_id 					as order_item_id
					,it_woocommerce_order_items.order_item_name 				as product_name
					,it_woocommerce_order_items.order_item_name 				as item_name


					,SUM(it_woocommerce_order_itemmeta_product_total.meta_value) 	as total
					,SUM(it_woocommerce_order_itemmeta_product_qty.meta_value) 	as quantity

					,MONTH(shop_order.post_date) 							as month_number
					,DATE_FORMAT(shop_order.post_date, '%Y-%m')				as month_key
					,it_woocommerce_order_itemmeta_variation.meta_value		as variation_id
					,it_woocommerce_order_items.order_id						as order_id
					,shop_order.post_status
					,it_woocommerce_order_items.order_item_id					as order_item_id

					FROM 	   {$wpdb->prefix}woocommerce_order_items		as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product 			ON it_woocommerce_order_itemmeta_product.order_item_id			=	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_total 	ON it_woocommerce_order_itemmeta_product_total.order_item_id	=	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_qty		ON it_woocommerce_order_itemmeta_product_qty.order_item_id		=	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_variation			ON it_woocommerce_order_itemmeta_variation.order_item_id 		= 	it_woocommerce_order_items.order_item_id

					LEFT JOIN  {$wpdb->prefix}posts 						as shop_order 									ON shop_order.id											=	it_woocommerce_order_items.order_id
				";


				$sql .= "
					WHERE
					it_woocommerce_order_itemmeta_product.meta_key		=	'_product_id'
					AND it_woocommerce_order_items.order_item_type		=	'line_item'
					AND shop_order.post_type						=	'shop_order'


					AND it_woocommerce_order_itemmeta_product_total.meta_key		='_line_total'
					AND it_woocommerce_order_itemmeta_product_qty.meta_key			=	'_qty'
					AND it_woocommerce_order_itemmeta_variation.meta_key 			= '_variation_id'
					AND (it_woocommerce_order_itemmeta_variation.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta_variation.meta_value > 0)
				";


				if($id != NULL  && $id != '-1'){
					$sql .= " AND it_woocommerce_order_itemmeta_variation.meta_value = {$id} ";
				}

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";


				$sql.=" AND DATE_FORMAT(shop_order.post_date, '%Y-%m') BETWEEN ('" . $it_from_date . "') and ('" . $it_to_date . "') ";


				$sql .= " group by month_number ORDER BY month_number";

				//echo $sql;

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

			}//end cross tab

			//CROSS TAB VARIATION
			function it_get_product_var_col_separated($order_item_id = 0){
				global $wpdb;
					$sql = "
					SELECT
					it_postmeta_variation.meta_value AS variation
					,it_postmeta_variation.meta_key AS attribute
					FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_variation ON it_postmeta_variation.post_id = woocommerce_order_itemmeta.meta_value

					WHERE it_woocommerce_order_items.order_item_id={$order_item_id}

					AND it_woocommerce_order_items.order_item_type = 'line_item'
					AND woocommerce_order_itemmeta.meta_key = '_variation_id'
					AND it_postmeta_variation.meta_key like 'attribute_%'";

					$items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

					//$this->print_array($items);

					$it_variations = array();
					foreach($items as $key => $value):

						$var = $value->attribute;
						$var = str_replace("attribute_pa_","",$var);
						$var = str_replace("attribute_","",$var);


						$var2 = $value->variation;
						if(strlen($var2)>0){
							$var2 = str_replace("-"," ",$var2);
						}else{
							$var2 = $var;
						}

						if(!isset($it_variations[$var]))
							$it_variations[$var] = ucfirst($var2);
					endforeach;

					return $it_variations;
			}


			//CROSS TAB COUNTRY
			public function it_get_woo_countries_items($type = 'limit_row', $items_only = true, $id = '-1',$params=[]){
				global $wpdb;


				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$sql = "
				SELECT
				it_postmeta1.meta_value 							as id
				,it_postmeta1.meta_value						 	as country_name
				,it_postmeta1.meta_value						 	as country_code
				,it_postmeta1.meta_value						 	as item_name
				,SUM(it_postmeta2.meta_value)						as total
				,COUNT(shop_order.ID) 							as quantity

				,MONTH(shop_order.post_date) 					as month_number
				,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key

				FROM {$wpdb->prefix}posts as shop_order
				LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta1 on it_postmeta1.post_id = shop_order.ID
				LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta2 on it_postmeta2.post_id = shop_order.ID

				";

				$sql .= "

				WHERE shop_order.post_type	= 'shop_order'
				AND it_postmeta1.meta_key 		= '_billing_country'
				AND	it_postmeta2.meta_key 		= '_order_total'";


				if($id != NULL  && $id != '-1'){
					$sql .= " AND it_postmeta1.meta_value IN ('{$id}') ";
				}

				if ($it_from_date != NULL &&  $it_to_date !=NULL)
					$sql .= " AND DATE_FORMAT(shop_order.post_date, '%Y-%m') BETWEEN ('" . $it_from_date . "') and ('" . $it_to_date . "')";

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

				$sql .= " group by month_number ORDER BY month_number";


				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			//GET COUNTRY CROSS TAB
			function it_get_woo_cp_items($type = 'limit_row', $items_only = true, $id = '-1',$params=[],$it_region_code=NULL){
				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$sql = "
					SELECT
					it_woocommerce_order_itemmeta_product.meta_value 			as id
					,it_woocommerce_order_items.order_item_name 				as product_name
					,it_woocommerce_order_itemmeta_product.meta_value 			as product_id
					,it_woocommerce_order_items.order_item_id 					as order_item_id
					,it_woocommerce_order_items.order_item_name 				as item_name


					,SUM(it_woocommerce_order_itemmeta_product_total.meta_value) 	as total
					,SUM(it_woocommerce_order_itemmeta_product_qty.meta_value) 	as quantity";

				if($it_region_code == "_billing_state"){
					$sql .= "
					,billing_state.meta_value as billing_state
					,CONCAT(billing_country.meta_value,'-',billing_state.meta_value) as month_key
					,CONCAT(billing_country.meta_value,'-',billing_state.meta_value) as state_code
					,billing_state.meta_value as month_number ";
				}else{
					$sql .= "
					,billing_country.meta_value as month_key
					,billing_country.meta_value as month_number ";
				}


				$sql .= "
					,billing_country.meta_value as billing_country";

				$sql .= "
					FROM {$wpdb->prefix}woocommerce_order_items 			as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product 			ON it_woocommerce_order_itemmeta_product.order_item_id=it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}posts 						as shop_order 									ON shop_order.id								=	it_woocommerce_order_items.order_id

					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_total 	ON it_woocommerce_order_itemmeta_product_total.order_item_id=it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_qty		ON it_woocommerce_order_itemmeta_product_qty.order_item_id		=	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}postmeta 						as billing_country 								ON billing_country.post_id									=	shop_order.ID";


				if($it_region_code == "_billing_state"){
					$sql .= "
					LEFT JOIN  {$wpdb->prefix}postmeta as billing_state ON billing_state.post_id=	shop_order.ID";
				}

				$sql .= "
				WHERE
				it_woocommerce_order_itemmeta_product.meta_key		=	'_product_id'
				AND it_woocommerce_order_items.order_item_type		=	'line_item'
				AND shop_order.post_type						=	'shop_order'

				AND billing_country.meta_key							=	'_billing_country'
				AND it_woocommerce_order_itemmeta_product_total.meta_key		='_line_total'
				AND it_woocommerce_order_itemmeta_product_qty.meta_key			=	'_qty'";

				if ($it_from_date != NULL &&  $it_to_date !=NULL)
					$sql .= " AND DATE_FORMAT(shop_order.post_date, '%Y-%m-%d') BETWEEN ('" . $it_from_date . "') and ('" . $it_to_date . "')";

				if($it_region_code == "_billing_state"){
					$sql .= " AND billing_state.meta_key							=	'_billing_state'";
				}

				if($id != NULL  && $id != '-1'){
					$sql .= " AND it_woocommerce_order_itemmeta_product.meta_value IN ({$id}) ";
				}

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
					$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";


				$sql .= " group by month_number ORDER BY month_number";
				//echo $sql;

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}


			//CROSS TAB PAYMENT
			public function it_get_woo_pg_items($type = 'limit_row', $items_only = true, $id = '-1',$params=[])
			{
				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$sql = "
				SELECT
				it_postmeta1.meta_value 							as id
				,it_postmeta3.meta_value						 	as payment_method
				,it_postmeta3.meta_value						 	as item_name
				,SUM(it_postmeta2.meta_value)						as total
				,COUNT(shop_order.ID) 							as quantity
				,MONTH(shop_order.post_date) 					as month_number
				,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key

				FROM {$wpdb->prefix}posts as shop_order
				LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta1 on it_postmeta1.post_id = shop_order.ID
				LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta2 on it_postmeta2.post_id = shop_order.ID
				LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta3 on it_postmeta3.post_id = shop_order.ID

				";



				$sql .= "

				WHERE shop_order.post_type	= 'shop_order'
				AND it_postmeta1.meta_key 		= '_payment_method'
				AND	it_postmeta2.meta_key 		= '_order_total'
				AND	it_postmeta3.meta_key 		= '_payment_method_title'";

				if($id != NULL  && $id != '-1'){
					$sql .= " AND it_postmeta1.meta_value IN ('{$id}') ";
				}

				if ($it_from_date != NULL &&  $it_to_date !=NULL)	$sql .= " AND DATE_FORMAT(shop_order.post_date, '%Y-%m') BETWEEN ('" . $it_from_date . "') and ('" . $it_to_date . "')";

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";


				$sql .= " group by month_number ORDER BY month_number";


				$wpdb->flush();
				$wpdb->query("SET SQL_BIG_SELECTS=1");

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}


			//CROSS TAB STATUS
			public function it_get_woo_items_status($type = 'limit_row', $items_only = true, $id = '-1',$params=[]){
				global $wpdb;

				$it_from_date=isset($params['it_from_date']) ? $params['it_from_date'] :'';
				$it_to_date=isset($params['it_to_date']) ? $params['it_to_date'] :'';
				$date_format = $this->it_date_format($it_from_date);
				$it_order_status=isset($params['order_status']) ? $params['order_status'] :'';
				$it_hide_os=isset($params['it_hide_os']) ? $params['it_hide_os'] :'';

				$sql = "SELECT ";
				$sql .= "
					shop_order.post_status 					as id
					,shop_order.post_status					as group_column
					,shop_order.post_status				 	as status_name
					,shop_order.post_status				 	as item_name
					,shop_order.post_status				 	as order_status";

				$sql .= "
					,SUM(it_postmeta2.meta_value)				as total
					,COUNT(shop_order.ID) 					as quantity
					,MONTH(shop_order.post_date) 			as month_number

					,MONTH(shop_order.post_date) 					as month_number
					,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key


					FROM {$wpdb->prefix}posts as shop_order
					LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta2 on it_postmeta2.post_id = shop_order.ID

					";

				$sql .= "
				WHERE shop_order.post_type	= 'shop_order'

				AND	it_postmeta2.meta_key 		= '_order_total'";


				if($id != NULL  && $id != '-1'){
					$sql .= "
					AND shop_order.post_status IN ('{$id}') ";
				}

				if ($it_from_date != NULL &&  $it_to_date !=NULL)	$sql .= " AND DATE_FORMAT(shop_order.post_date, '%Y-%m') BETWEEN ('" . $it_from_date . "') and ('" . $it_to_date . "')";

				if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

				$sql .= "  GROUP BY month_number ORDER BY month_number";
				//echo $sql;

				$wpdb->flush();
				$wpdb->query("SET SQL_BIG_SELECTS=1");

				return $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

			}

			//GET SUMMARY
			function it_get_woo_items_sale($type = 'limit_row', $items_only = true, $id = '-1'){
				global $wpdb;
				$order_items = array();

				if($items_only){
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

					$order_items = $this->convert_obj($array);
				}else{

					$request = $this->get_all_request();extract($request);
					$it_order_status		= $this->it_get_woo_sm_requests('it_orders_status',$it_order_status, "-1");
					$it_hide_os	= $this->it_get_woo_sm_requests('it_hide_os',$it_hide_os, "-1");

					if($id == "_by_product"){
						$sql = "
							SELECT
							it_woocommerce_order_itemmeta_product.meta_value 			as id
							,it_woocommerce_order_items.order_item_name 				as product_name
							,it_woocommerce_order_items.order_item_name 				as item_name
							,SUM(it_woocommerce_order_itemmeta_product_total.meta_value) 	as total
							,SUM(it_woocommerce_order_itemmeta_product_qty.meta_value) 	as quantity

							,MONTH(shop_order.post_date) 					as month_number
							,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key

							FROM {$wpdb->prefix}woocommerce_order_items 			as it_woocommerce_order_items
							LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product 			ON it_woocommerce_order_itemmeta_product.order_item_id			= it_woocommerce_order_items.order_item_id
							LEFT JOIN  {$wpdb->prefix}posts 						as shop_order 									ON shop_order.id											= it_woocommerce_order_items.order_id

							LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_total 	ON it_woocommerce_order_itemmeta_product_total.order_item_id	= it_woocommerce_order_items.order_item_id
							LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_qty		ON it_woocommerce_order_itemmeta_product_qty.order_item_id		= it_woocommerce_order_items.order_item_id";

							if($it_id_order_status != NULL  && $it_id_order_status != '-1'){
								$sql .= "
								LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships2 	ON it_term_relationships2.object_id	=	shop_order.ID
								LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
								LEFT JOIN  {$wpdb->prefix}terms 				as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
							}

							$sql .= "
							WHERE
							it_woocommerce_order_itemmeta_product.meta_key					= '_product_id'
							AND it_woocommerce_order_items.order_item_type					= 'line_item'
							AND shop_order.post_type									= 'shop_order'
							AND it_woocommerce_order_itemmeta_product_total.meta_key		= '_line_total'
							AND it_woocommerce_order_itemmeta_product_qty.meta_key			= '_qty'";
					}else{
						$sql = "
						SELECT

						SUM(it_postmeta2.meta_value)						as total
						,COUNT(shop_order.ID) 							as quantity

						,MONTH(shop_order.post_date) 					as month_number
						,DATE_FORMAT(shop_order.post_date, '%Y-%m')		as month_key

						FROM {$wpdb->prefix}posts as shop_order
						LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta2 on it_postmeta2.post_id = shop_order.ID";

						if($it_id_order_status != NULL  && $it_id_order_status != '-1'){
							$sql .= "
							LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships2 	ON it_term_relationships2.object_id	=	shop_order.ID
							LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
							LEFT JOIN  {$wpdb->prefix}terms 				as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
						}

						$sql .= "
						WHERE shop_order.post_type	= 'shop_order'";
						if($id != NULL  && $id != '-1'){
							$sql .= " AND	it_postmeta2.meta_key 	= '{$id}'";
						}

						$sql .= " AND it_postmeta2.meta_value > 0";
					}

					if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

					if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

					if ($it_from_date != NULL &&  $it_to_date !=NULL)	$sql .= " AND DATE_FORMAT(shop_order.post_date, '%Y-%m') BETWEEN '".$cross_tab_it_from_date."' AND '". $cross_tab_it_to_date ."'";

					if($it_id_order_status != NULL  && $it_id_order_status != '-1'){
						$sql .= "
						AND it_term_taxonomy2.taxonomy LIKE('shop_order_status')
						AND terms2.term_id IN (".$it_id_order_status .")";
					}


					//if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")$sql .= " AND shop_order.post_status IN (".$it_order_status.")";

					//if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")$sql .= " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

					$sql .= " group by month_number ORDER BY month_number";


					$wpdb->flush();
					$wpdb->query("SET SQL_BIG_SELECTS=1");
					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				}
				return $order_items;
			}



			//GET ALL CUSTOMERS
			function it_get_woo_customers_orders($post_type = 'shop_order',$post_status = 'no',$q=''){
				global $wpdb;

				//$post_status = $this->it_get_woo_requests_default('post_status',$post_status,true);
				if($post_status == "yes") $post_status == 'publish';


				$sql = "SELECT billing_email.meta_value AS id, concat(billing_first_name.meta_value, ' ',billing_last_name.meta_value) AS label, COUNT(billing_email.meta_value) AS counts FROM `{$wpdb->prefix}posts` as it_posts
					LEFT JOIN  {$wpdb->prefix}postmeta as customer_user ON customer_user.post_id=it_posts.ID
					LEFT JOIN  {$wpdb->prefix}postmeta as billing_first_name ON billing_first_name.post_id=it_posts.ID
					LEFT JOIN  {$wpdb->prefix}postmeta as billing_last_name ON billing_last_name.post_id=it_posts.ID
					LEFT JOIN  {$wpdb->prefix}postmeta as billing_email ON billing_email.post_id=it_posts.ID
				";
				$sql .= " WHERE
					post_type='{$post_type}'
				AND customer_user.meta_key = '_customer_user'
				AND billing_first_name.meta_key = '_billing_first_name'
				AND billing_last_name.meta_key = '_billing_last_name'
				AND billing_email.meta_key = '_billing_email'
				";

				if($q!=''){
					$sql.= " AND (billing_first_name.meta_value like '%$q%' OR billing_last_name.meta_value like '%$q%') ";
				}

				if($post_status == 'publish' || $post_status == 'trash')	$sql .= " AND it_posts.post_status = '".$post_status."'";

				$sql .= "
				GROUP BY billing_email.meta_value
				ORDER BY label  ASC";

				$products_category = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
//				if($encode)
//					return jsone_encode($products_category);
				return $products_category;
			}


			//GET ORDER STATUS
			function it_woo_so_status($it_shop_order_status = array()){
				global $wpdb;

				$sql = "SELECT it_terms.term_id AS id, it_terms.name AS label, it_terms.slug AS slug
				FROM {$wpdb->prefix}terms as terms
				LEFT JOIN {$wpdb->prefix}term_taxonomy AS term_taxonomy ON term_taxonomy.term_id = it_terms.term_id
				WHERE term_taxonomy.taxonomy = 'shop_order_status'";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os = implode("', '",$it_shop_order_status);
					$sql .= "	AND it_terms.slug IN ('{$it_in_shop_os}')";
				}

				$sql .= "
				GROUP BY it_terms.term_id
				ORDER BY it_terms.name ASC";

				$it_shop_order_status = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//echo $sql;
				return $it_shop_order_status;
			}//END shop_order_status


			function it_get_woo_orders_statuses(){
				if(!isset($this->constants['wc_order_statuses'])){
					if(function_exists('wc_get_order_statuses')){
						$it_order_statuses = wc_get_order_statuses();
					}else{
						$it_order_statuses = array();
					}

					$it_order_statuses['trash']	=	"Trash";

					$this->constants['wc_order_statuses'] = $it_order_statuses;
				}else{
					$it_order_statuses = $this->constants['wc_order_statuses'];
				}
				return $it_order_statuses;
			}

			//GET COUNTRY
			function it_get_paying_woo_state($state_key = 'billing_state',$country_key = false, $deliter = "-"){
				global $wpdb;
				if($country_key){
					//$sql = "SELECT CONCAT(billing_country.meta_value,'{$deliter}', billing_by.meta_value) as id, billing_by.meta_value as label, billing_country.meta_value as billing_country ";
					$sql = "SELECT billing_by.meta_value as id, billing_by.meta_value as label, billing_country.meta_value as billing_country ";
				}else
					$sql = "SELECT billing_by.meta_value as id, billing_by.meta_value as label ";

				$sql .= "
					FROM `{$wpdb->prefix}posts` as it_posts
					LEFT JOIN {$wpdb->prefix}postmeta as billing_by ON billing_by.post_id=it_posts.ID";
				if($country_key)
					$sql .= "
					LEFT JOIN {$wpdb->prefix}postmeta as billing_country ON billing_country.post_id=it_posts.ID";
				$sql .= "
					WHERE billing_by.meta_key='_{$state_key}' AND it_posts.post_type='shop_order'
				";

				if($country_key)
					$sql .= "
					AND billing_country.meta_key='_{$country_key}'";

				$sql .= "
				GROUP BY billing_by.meta_value
				ORDER BY billing_by.meta_value ASC";

				$results	= $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				$country    = $this->it_get_woo_countries();

				if($country_key){
					foreach($results as $key => $value):
							$v = $this->it_get_woo_states($value->billing_country, $value->label);
							$v = trim($v);
							if(strlen($v)>0)
								$results[$key]->label = $v ." (".$value->billing_country.")";
							else
								unset($results[$key]);
					endforeach;
				}else{

					foreach($results as $key => $value):
							$v = isset($country->countries[$value->label]) ? $country->countries[$value->label]: $value->label;
							$v = trim($v);
							if(strlen($v)>0)
								$results[$key]->label = $v;
							else
								unset($results[$key]);
					endforeach;
				}
//echo $sql;
				return $results;
			}

			//GET ALL STATE
			function it_get_woo_country_of_state(){
				global $wpdb;
				$sql = "SELECT
						billing_country.meta_value as parent_id,
						billing_state.meta_value as id,
						CONCAT(billing_country.meta_value,'-', billing_state.meta_value) billing_country_state

						FROM `{$wpdb->prefix}posts` as it_posts
						LEFT JOIN {$wpdb->prefix}postmeta as billing_state ON billing_state.post_id=it_posts.ID
						LEFT JOIN {$wpdb->prefix}postmeta as billing_country ON billing_country.post_id=it_posts.ID

						WHERE
						billing_state.meta_key='_billing_state'
						AND billing_country.meta_key='_billing_country'
						AND it_posts.post_type='shop_order'
						AND LENGTH(billing_state.meta_value) > 0
						GROUP BY billing_country_state
						ORDER BY billing_state.meta_value ASC
				";

				$results	= $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				foreach($results as $key => $value):
						$v = $this->it_get_woo_states($value->parent_id, $value->id);
						$v = trim($v);
						if(strlen($v)>0)
							$results[$key]->label = $v ." (".$value->parent_id.")";
						else
							unset($results[$key]);
				endforeach;
				//echo $sql;
				return $results;

				//$this->print_array($results);
			}

			function it_get_woo_states($cc = NULL,$st = NULL){
				global $woocommerce;
				$state_code = $st;

				if(!$cc) return $state_code;

				$states 			= $this->it_get_wc_woo_states($cc);


				if(is_array($states)){
					foreach($states as $key => $value){

						if($key == $state_code)
							return $value;
					}
				}else if(empty($states)){
					return $state_code;
				}
				return $state_code;
			}

			////ADDED IN VER4.0
            /// GET VARIATION AND SIMPLE PRODUCTS
			function it_get_simple_variation_product($type="VARIABLE",$product_id=NULL,$r="OBJECT"){
				global $wpdb;
				$sql_columns = "
                it_order_items.order_item_name as label,
                it_product_id.meta_value as id ";

				$sql_joins=" {$wpdb->prefix}woocommerce_order_items as it_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_product_id ON it_product_id.order_item_id=it_order_items.order_item_id ";
				if ($type=="SIMPLE" || $type=="VARIABLE"):
					$sql_joins .= "	LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_variation_id ON it_variation_id.order_item_id=it_order_items.order_item_id ";
				endif;
				$sql_condition = " 1=1 ";
				if ($product_id)
					$sql_condition .= " AND it_product_id.meta_value IN ({$product_id})";
				$sql_condition .= " AND it_order_items.order_item_type='line_item'
				AND it_product_id.meta_key='_product_id'";

				if ($type=="SIMPLE"):
					$sql_condition .= " AND it_variation_id.meta_value='0' AND it_variation_id.meta_key='_variation_id'";
				endif;
				if ($type=="VARIABLE"):
					$sql_condition .= " AND it_variation_id.meta_value>'0' AND it_variation_id.meta_key='_variation_id'";
				endif;
				$sql_group_by = " GROUP BY  it_product_id.meta_value ";
				$sql_order_by = " order By it_order_items.order_item_name ";

				$sql = "SELECT $sql_columns
				FROM $sql_joins
				WHERE $sql_condition $sql_group_by $sql_order_by";

				if ($r=="ARRAY_A"){
					$sql = $wpdb->get_results( $sql,ARRAY_A); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				}
				else{
					$sql = $wpdb->get_results( $sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				}
				return $sql;

			}

			//GET PRODUCTS FOR CHOSEN
			//GET PRODUCTS
			function it_get_product_woo_data_chosen($it_product_type = 'all',$encode=false,$q=''){

				global $wpdb;

				$category_id			= $this->it_get_woo_requests('it_category_id','-1');

				$taxonomy				= $this->it_get_woo_requests_default('taxonomy','product_cat');

				$purchased_product_id	= $this->it_get_woo_requests_default('purchased_product_id','-1');

				$it_hide_os 		= $this->it_get_woo_requests_default('it_hide_os','-1',true);

				$it_publish_order			= 'no';

				$sql_columns = " woocommerce_order_itemmeta.meta_value AS id, it_woocommerce_order_items.order_item_name AS label ";

				$sql_joins=" `{$wpdb->prefix}woocommerce_order_items` as it_woocommerce_order_items
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id";

				if($category_id != "-1" && $category_id >= 0){
					$sql_joins .= "
							LEFT JOIN {$wpdb->prefix}term_relationships		as it_term_relationships		ON it_term_relationships.object_id				= woocommerce_order_itemmeta.meta_value
							LEFT JOIN {$wpdb->prefix}term_taxonomy			AS term_taxonomy			ON term_taxonomy.term_taxonomy_id			= it_term_relationships.term_taxonomy_id
							LEFT JOIN {$wpdb->prefix}terms					AS terms					ON it_terms.term_id							= term_taxonomy.term_id";
				}
				if($it_product_type == 1)
					$sql_joins .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_variation_id_order_itemmeta ON it_variation_id_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id";

				if($it_product_type == 2 || ($it_product_type == 'grouped' || $it_product_type == 'external' || $it_product_type == 'simple' || $it_product_type == 'variable_')){
					$sql_joins .= "
							LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_product_type 	ON it_term_relationships_product_type.object_id		=	woocommerce_order_itemmeta.meta_value
							LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy_product_type 		ON it_term_taxonomy_product_type.term_taxonomy_id		=	it_term_relationships_product_type.term_taxonomy_id
							LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_product_type 				ON it_terms_product_type.term_id						=	it_term_taxonomy_product_type.term_id";
				}

				if(($it_publish_order == "yes") || ($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'"))	$sql .= " LEFT JOIN {$wpdb->prefix}posts as it_posts ON it_posts.ID = it_woocommerce_order_items.order_id";

				$sql_condition = " woocommerce_order_itemmeta.meta_key = '_product_id'";

				if($category_id != "-1" && $category_id >= 0){
					$sql_condition .= " AND term_taxonomy.taxonomy = 'product_cat'";
				}

				if($it_product_type == 1)
					$sql_condition .= " AND it_variation_id_order_itemmeta.meta_key = '_variation_id' AND (it_variation_id_order_itemmeta.meta_value IS NOT NULL AND it_variation_id_order_itemmeta.meta_value > 0)";

				if($category_id != "-1" && $category_id >= 0)
					$sql_condition .= " AND terms .term_id IN(".$category_id.")";

				if($it_publish_order == 'yes')	$sql_condition .= " AND it_posts.post_status = 'publish'";

				if($it_publish_order == 'publish' || $it_publish_order == 'trash')	$sql_condition .= " AND it_posts.post_status = '".$it_publish_order."'";

				if($it_product_type == 'grouped' || $it_product_type == 'external' || $it_product_type == 'simple' || $it_product_type == 'variable_'){
					$sql_condition .= " AND it_terms_product_type.name IN ('{$it_product_type}')";
				}

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$sql_condition.= " AND it_posts.post_status NOT IN ('".$it_hide_os."') ";

				if($q!=''){
					$sql_condition.= " AND it_woocommerce_order_items.order_item_name like '%$q%' ";
                }

				$sql_group_by = " GROUP BY woocommerce_order_itemmeta.meta_value ";
				$sql_order_by = " ORDER BY it_woocommerce_order_items.order_item_name ASC";

				$sql = "SELECT $sql_columns
				FROM $sql_joins
				WHERE $sql_condition  $sql_group_by $sql_order_by";

				//$this->print_sql($sql);

				$products = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//echo mysql_error();

				if($encode)
					return wp_json_encode($products);

				return $products;
			}


			//GET PRODUCTS
			function it_get_product_woo_data($it_product_type = 'all',$encode=false){

				global $wpdb;

				$category_id			= $this->it_get_woo_requests('it_category_id','-1');

				$taxonomy				= $this->it_get_woo_requests_default('taxonomy','product_cat');

				$purchased_product_id	= $this->it_get_woo_requests_default('purchased_product_id','-1');

				$it_hide_os 		= $this->it_get_woo_requests_default('it_hide_os','-1',true);

				$it_publish_order			= 'no';

				$sql_columns = " woocommerce_order_itemmeta.meta_value AS id, it_woocommerce_order_items.order_item_name AS label ";

				$sql_joins=" `{$wpdb->prefix}woocommerce_order_items` as it_woocommerce_order_items
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id";

				if($category_id != "-1" && $category_id >= 0){
					$sql_joins .= "
							LEFT JOIN {$wpdb->prefix}term_relationships		as it_term_relationships		ON it_term_relationships.object_id				= woocommerce_order_itemmeta.meta_value
							LEFT JOIN {$wpdb->prefix}term_taxonomy			AS term_taxonomy			ON term_taxonomy.term_taxonomy_id			= it_term_relationships.term_taxonomy_id
							LEFT JOIN {$wpdb->prefix}terms					AS terms					ON it_terms.term_id							= term_taxonomy.term_id";
				}
				if($it_product_type == 1)
					$sql_joins .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_variation_id_order_itemmeta ON it_variation_id_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id";

				if($it_product_type == 2 || ($it_product_type == 'grouped' || $it_product_type == 'external' || $it_product_type == 'simple' || $it_product_type == 'variable_')){
					$sql_joins .= "
							LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_product_type 	ON it_term_relationships_product_type.object_id		=	woocommerce_order_itemmeta.meta_value
							LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy_product_type 		ON it_term_taxonomy_product_type.term_taxonomy_id		=	it_term_relationships_product_type.term_taxonomy_id
							LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_product_type 				ON it_terms_product_type.term_id						=	it_term_taxonomy_product_type.term_id";
				}

				if(($it_publish_order == "yes") || ($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'"))	$sql .= " LEFT JOIN {$wpdb->prefix}posts as it_posts ON it_posts.ID = it_woocommerce_order_items.order_id";

				$sql_condition = " woocommerce_order_itemmeta.meta_key = '_product_id'";

				if($category_id != "-1" && $category_id >= 0){
					$sql_condition .= " AND term_taxonomy.taxonomy = 'product_cat'";
				}

				if($it_product_type == 1)
					$sql_condition .= " AND it_variation_id_order_itemmeta.meta_key = '_variation_id' AND (it_variation_id_order_itemmeta.meta_value IS NOT NULL AND it_variation_id_order_itemmeta.meta_value > 0)";

				if($category_id != "-1" && $category_id >= 0)
					$sql_condition .= " AND terms .term_id IN(".$category_id.")";

				if($it_publish_order == 'yes')	$sql_condition .= " AND it_posts.post_status = 'publish'";

				if($it_publish_order == 'publish' || $it_publish_order == 'trash')	$sql_condition .= " AND it_posts.post_status = '".$it_publish_order."'";

				if($it_product_type == 'grouped' || $it_product_type == 'external' || $it_product_type == 'simple' || $it_product_type == 'variable_'){
					$sql_condition .= " AND it_terms_product_type.name IN ('{$it_product_type}')";
				}

				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
					$sql_condition.= " AND it_posts.post_status NOT IN ('".$it_hide_os."') ";

				$sql_group_by = " GROUP BY woocommerce_order_itemmeta.meta_value ";
				$sql_order_by = " ORDER BY it_woocommerce_order_items.order_item_name ASC";

				$sql = "SELECT $sql_columns
				FROM $sql_joins
				WHERE $sql_condition $sql_group_by $sql_order_by";

				//return ($sql);

				$products = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//echo mysql_error();

                if($encode)
                    return wp_json_encode($products);

				return $products;
			}

			//GET CATEGORY_PRODUCT_ID

			function it_get_var_product_dropdown($post_type = 'product', $post_status = 'no'){
				global $wpdb;

				if($post_status == "yes") $post_status == 'publish';

				if($post_status == "publish") $post_status == 'publish';

				$publish_order	= $this->it_get_woo_requests_default('publish_order',$post_status,true);//if publish display publish order only, no or null display all order

				$sql = "SELECT posts.ID AS id, posts.post_title AS label FROM `{$wpdb->prefix}posts` AS posts";

				$sql .= "
						LEFT JOIN  {$wpdb->prefix}term_relationships 	as term_relationships_product_type 	ON term_relationships_product_type.object_id		=	posts.ID
						LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_product_type 		ON term_taxonomy_product_type.term_taxonomy_id		=	term_relationships_product_type.term_taxonomy_id
						LEFT JOIN  {$wpdb->prefix}terms 				as terms_product_type 				ON terms_product_type.term_id						=	term_taxonomy_product_type.term_id";


				$sql .= " WHERE posts.post_type = '{$post_type}'";

				$sql .= " AND terms_product_type.name IN ('variable')";

				if($publish_order == 'publish' || $publish_order == 'trash')	$sql .= " AND posts.post_status = '".$publish_order."'";

				$sql .= " GROUP BY posts.ID ORDER BY posts.post_title";

				$products = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $products;
			}

			function it_get_var_category_dropdown($taxonomy = 'product_cat',$post_status = 'no', $count = true){
				global $wpdb;

				$post_status = $this->it_get_woo_requests_default('post_status',$post_status,true);
				if($post_status == "yes") $post_status == 'publish';

				$sql = "SELECT
				terms.term_id AS id, terms.name AS label";

				if($count)
					$sql .= ", count(posts.ID) AS counts";

				$sql .= " FROM `{$wpdb->prefix}posts` AS posts
				LEFT JOIN {$wpdb->prefix}term_relationships AS term_relationships ON term_relationships.object_id = posts.post_parent
				LEFT JOIN {$wpdb->prefix}term_taxonomy AS term_taxonomy ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
				LEFT JOIN {$wpdb->prefix}terms AS terms ON terms.term_id = term_taxonomy.term_id";

				$sql .= " WHERE term_taxonomy.taxonomy = '{$taxonomy}'";
				if($post_status == 'publish' || $post_status == 'trash')	$sql .= " AND posts.post_status = '".$post_status."'";

				$sql .= "
				AND posts.post_parent > 0
				 AND posts.post_type = 'product_variation'
				GROUP BY terms.term_id
				ORDER BY terms.name ASC";

				$products_category = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				return $products_category;
			}

			function it_get_woo_pli_category($categories = array(), $products = array(), $return_default = '-1' , $return_formate = 'string'){
				global $wpdb;

				$it_cat_prod_id_string = $return_default;
				if(is_array($categories)){
					$categories = implode(",",$categories);
				}

				if(is_array($products)){
					$products = implode(",",$products);
				}

				if($categories  && $categories != "-1") {

					$sql  = " SELECT ";
					$sql .= " woocommerce_order_itemmeta.meta_value		AS product_id";

					$sql .= " FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items";
					$sql .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id=it_woocommerce_order_items.order_item_id";
					$sql .= " LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	woocommerce_order_itemmeta.meta_value ";
					$sql .= " LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
					$sql .= " WHERE 1*1 AND woocommerce_order_itemmeta.meta_key 	= '_product_id'";
					$sql .= " AND term_taxonomy.term_id IN (".$categories .")";

					if($products  && $products != "-1") $sql .= " AND woocommerce_order_itemmeta.meta_value IN (".$products .")";

					$sql .= " GROUP BY  woocommerce_order_itemmeta.meta_value";

					$sql .= " ORDER BY product_id ASC";

					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					$it_product_id_list = array();
					if(!$order_items){
						foreach($order_items as $key => $order_item) $it_product_id_list[] = $order_item->product_id;
						if($return_formate == 'string'){
							$it_cat_prod_id_string = implode(",", $it_product_id_list);
						}else{
							$it_cat_prod_id_string = $it_product_id_list;
						}
					}
				}

				return $it_cat_prod_id_string;

			}


			//GET TERMS
			function it_get_tag_products($taxonomy = 'product_tag'){
				global $wpdb;

				$sql = "SELECT terms.term_id AS id, terms.name as label
			FROM `{$wpdb->prefix}posts` AS it_posts
			LEFT JOIN  {$wpdb->prefix}term_relationships	as term_relationships 	ON term_relationships.object_id	=	it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy			as it_term_taxonomy 		ON it_term_taxonomy.term_taxonomy_id	=	term_relationships.term_taxonomy_id
			LEFT JOIN  {$wpdb->prefix}terms					as terms 				ON terms.term_id					=	it_term_taxonomy.term_id
			WHERE it_term_taxonomy.taxonomy = '{$taxonomy}'";
				$sql .= " GROUP BY terms.term_id";
				$sql .= " ORDER BY terms.term_id ASC, terms.term_id ASC";

				$product_tag = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $product_tag;
			}

			//SOLD PRODUCT PARENT
			function it_get_woo_sppc_data($taxonomy="product_cat"){

				global $wpdb;

				//$it_order_status	= $this->it_get_woo_sm_requests('it_orders_status',$it_order_status, "-1");
				$it_hide_os	= "-1";

				$sql ="";
				$sql .= " SELECT ";
				$sql .= " it_term_taxonomy_product_id.parent AS id";
				$sql .= " ,it_terms_parent_product_id.name AS label";

				$sql .= " FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items";


				$sql .= " LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.id=it_woocommerce_order_items.order_id";

				$sql .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_id ON it_woocommerce_order_itemmeta_product_id.order_item_id=it_woocommerce_order_items.order_item_id";

				$sql .= " 	LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_product_id 	ON it_term_relationships_product_id.object_id		=	it_woocommerce_order_itemmeta_product_id.meta_value
							LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy_product_id 		ON it_term_taxonomy_product_id.term_taxonomy_id	=	it_term_relationships_product_id.term_taxonomy_id
							LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_product_id 				ON it_terms_product_id.term_id						=	it_term_taxonomy_product_id.term_id";

				$sql .= " 	LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_parent_product_id 				ON it_terms_parent_product_id.term_id						=	it_term_taxonomy_product_id.parent";

				$sql .= " WHERE 1*1 ";
				$sql .= " AND it_woocommerce_order_items.order_item_type 	= 'line_item'";
				$sql .= " AND it_woocommerce_order_itemmeta_product_id.meta_key 	= '_product_id'";
				$sql .= " AND it_term_taxonomy_product_id.taxonomy 	= '$taxonomy'";
				$sql .= " AND it_term_taxonomy_product_id.parent > 0";


				$sql .= " AND it_posts.post_type 											= 'shop_order'";
				//if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")$sql .= " AND it_posts.post_status IN (".$it_order_status.")";
				if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")$sql .= " AND it_posts.post_status NOT IN ('".$it_hide_os."')";

				$sql .= " GROUP BY it_term_taxonomy_product_id.parent";

				$sql .= " ORDER BY it_terms_parent_product_id.name ASC";

				$category_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				if($wpdb->last_error){
					//echo $wpdb->last_error;
				}
				return $category_items;
			}

			//GET COUPON
			function it_get_woo_coupons_codes(){
				global $wpdb;
				$sql = " SELECT ";
				$sql .= "
				it_woocommerce_order_items.order_item_name				AS		'label',
				it_woocommerce_order_items.order_item_name				AS		'id'

				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN	{$wpdb->prefix}posts as it_posts 	ON it_posts.ID = it_woocommerce_order_items.order_id";
				$sql .= "
				WHERE
				it_posts.post_type 								=	'shop_order'
				AND it_woocommerce_order_items.order_item_type		=	'coupon'";

				$sql .= "
				Group BY it_woocommerce_order_items.order_item_name
				ORDER BY it_woocommerce_order_items.order_item_name ASC";

				$it_coupon_codes = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $it_coupon_codes;
			}

			//GET CATEGORY IN VARIOATION
			function it_get_woo_var_cat_data($taxonomy = 'product_cat', $post_status = 'no'){
				global $wpdb;

				$post_status = "yes";
				if($post_status == "yes") $post_status == 'publish';

				$post_where = ($taxonomy == "product_cat") ? "it_woocommerce_order_itemmeta_product.meta_value" : "it_woocommerce_order_items.order_id";

				$sql = "
					SELECT
					it_terms.term_id												as id
					,it_terms.name											as label
					FROM 	   {$wpdb->prefix}woocommerce_order_items		as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product 			ON it_woocommerce_order_itemmeta_product.order_item_id			=	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_variation			ON it_woocommerce_order_itemmeta_variation.order_item_id 		= 	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}posts 						as shop_order 									ON shop_order.id											=	it_woocommerce_order_items.order_id
				";

				$sql .= "
					LEFT JOIN  {$wpdb->prefix}term_relationships 			as it_term_relationships 							ON it_term_relationships.object_id		=	{$post_where}
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 				as term_taxonomy 								ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 						as it_terms 										ON it_terms.term_id					=	term_taxonomy.term_id";

				$sql .= "
					WHERE
					it_woocommerce_order_itemmeta_product.meta_key		=	'_product_id'
					AND it_woocommerce_order_items.order_item_type		=	'line_item'
					AND shop_order.post_type						=	'shop_order'
					AND it_woocommerce_order_itemmeta_variation.meta_key 	= '_variation_id'
					AND (it_woocommerce_order_itemmeta_variation.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta_variation.meta_value > 0)
				";

				if($post_status == 'publish' || $post_status == 'trash')	$sql .= " AND it_posts.post_status = '".$post_status."'";

				$sql .= " AND term_taxonomy.taxonomy LIKE('{$taxonomy}')";

				$sql .= " GROUP BY it_terms.name ORDER BY label ASC";

				$wpdb->flush();
				$wpdb->query("SET SQL_BIG_SELECTS=1");
				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $order_items;
			}

			//GET PRODUCT IN VARIATION
			function it_get_woo_var_data($post_type = 'shop_order', $post_status = 'no'){
				global $wpdb;

				$post_status = "yes";
				if($post_status == "yes") $post_status == 'publish';

				$sql = "
					SELECT
					it_woocommerce_order_itemmeta_product.meta_value			as id
					,it_woocommerce_order_items.order_item_name 				as label
					FROM 	   {$wpdb->prefix}woocommerce_order_items		as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product 			ON it_woocommerce_order_itemmeta_product.order_item_id			=	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_variation			ON it_woocommerce_order_itemmeta_variation.order_item_id 		= 	it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}posts 						as shop_order 									ON shop_order.id											=	it_woocommerce_order_items.order_id
				";

				$sql .= "
					WHERE
					it_woocommerce_order_itemmeta_product.meta_key		=	'_product_id'
					AND it_woocommerce_order_items.order_item_type		=	'line_item'
					AND shop_order.post_type						=	'{$post_type}'
					AND it_woocommerce_order_itemmeta_variation.meta_key 	= '_variation_id'
					AND (it_woocommerce_order_itemmeta_variation.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta_variation.meta_value > 0)
				";

				if($post_status == 'publish' || $post_status == 'trash')	$sql .= " AND it_posts.post_status = '".$post_status."'";

				$sql .= " GROUP BY it_woocommerce_order_itemmeta_product.meta_value ORDER BY label ASC";

				$wpdb->flush();
				$wpdb->query("SET SQL_BIG_SELECTS=1");
				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				return $order_items;
			}

			//GET VARIATION IN VARIATION
			function it_get_woo_atts($_variations = '-1'){
				global $wpdb;

				$sql = "	SELECT
							it_postmeta_variation.meta_key AS variation_key
							,it_postmeta_variation.meta_value AS variation_name";
				$sql .= "	FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
							LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta8 ON it_woocommerce_order_itemmeta8.order_item_id = it_woocommerce_order_items.order_item_id
							LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta_variation ON it_postmeta_variation.post_id = it_woocommerce_order_itemmeta8.meta_value";

				$sql .= "	WHERE it_postmeta_variation.meta_key like 'attribute_%'";
				$sql .= "	AND it_woocommerce_order_itemmeta8.meta_key = '_variation_id' AND it_woocommerce_order_itemmeta8.meta_value IS NOT NULL AND it_woocommerce_order_itemmeta8.meta_value > 0
						 	GROUP BY it_postmeta_variation.meta_key";
				$items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				$it_variations = array();
				if($_variations != '-1')
					$_variations			= $this->it_get_woo_requests('it_variations','-1',true);

				if($_variations == '-1'){
					foreach($items as $key => $value):
						$var = $value->variation_key;
						//$var = $this->attribute_label($value->variation_key, $value->variation_name);
						$var = str_replace("attribute_pa_","",$var);
						$var = str_replace("attribute_","",$var);
						$var2 = str_replace("-"," ",$var);
						$it_variations[$var] = ucfirst($var2);
					endforeach;
				}else{
					$_variations = explode(",",$_variations);

					//this->print_array($_variations);
					foreach($items as $key => $value):
						$var = $value->variation_key;
						//$var = $this->attribute_label($value->variation_key, $value->variation_name);
						$var = str_replace("attribute_pa_","",$var);
						$var = str_replace("attribute_","",$var);
						$var2 = str_replace("-"," ",$var);

						if(in_array($var, $_variations))
							$it_variations[$var] = ucfirst($var2);
					endforeach;
				}
				asort($it_variations);
				return $it_variations;
			}

			//VARIATION
			function it_get_woo_pv_atts($all_columns = "no"){
				global $wpdb;
				$sql = "SELECT it_postmeta_product_addons.meta_value product_attributes FROM {$wpdb->prefix}posts as it_posts";
				$sql .= " LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta_product_addons ON it_postmeta_product_addons.post_id = it_posts.ID";
				$sql .= " WHERE post_type in ('product')";
				$sql .= " AND it_postmeta_product_addons.meta_key IN ('_product_attributes') ";

				$product_addon_objects = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				//die(print_r($product_addon_objects));
				//$this->print_array($attributes);
				$product_addon_master = array();
				if(!$product_addon_objects && is_array($product_addon_objects)){
					foreach($product_addon_objects as $key => $value){
						$product_addon_lists = unserialize($value->product_attributes);
						if(is_array($product_addon_lists)) {


							foreach ( $product_addon_lists as $key2 => $value2 ) {
								$product_addon_master[] = $key2;
							}
						}
						//$this->print_array($product_addon_lists);
					}
				}

				 $product_addon_master_key = "";
				if(!$product_addon_master){
					$product_addon_master = array_unique($product_addon_master);
					sort($product_addon_master);

					$product_addon_master_key = implode("','", $product_addon_master);
				}

				$sql = "SELECT ";

				$sql .= " woocommerce_order_itemmeta.meta_key as attribute_key_lable ";
				$sql .= " ,woocommerce_order_itemmeta.meta_value as attribute_key_value ";
				$sql .= " , REPLACE(woocommerce_order_itemmeta.meta_key,'pa_','') as attribute_key ";
				$sql .= " ,woocommerce_order_itemmeta.order_item_id as order_item_id ";
				$sql .= " FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items";
				$sql .= " LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id";
				$sql .= " WHERE 1*1";

				if($product_addon_master_key){
					$sql .= " AND woocommerce_order_itemmeta.meta_key IN ('{$product_addon_master_key}') ";
				}

				if($all_columns == "no"){
					$it_variation_item_meta_key = $this->it_get_woo_requests('variation_itemmetakey','-1');
					if($it_variation_item_meta_key and $it_variation_item_meta_key != '-1'){
						$sql .= " AND woocommerce_order_itemmeta.meta_key IN ('{$it_variation_item_meta_key}') ";
					}
				}

				$sql .= " GROUP BY woocommerce_order_itemmeta.meta_key";
				$sql .= " ORDER BY attribute_key";

				$items_objects = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//$this->print_sql($sql);

				$product_attirbute_columns = array();
				if(!$items_objects){
					foreach($items_objects as $key => $value){
						//$product_addon_master[] = $key2;
						$product_attirbute_columns[strtolower("wcv_".$value->attribute_key)] = ucwords($value->attribute_key);
					}
				}

				//$this->print_array($product_attirbute_columns);

				return $product_attirbute_columns;


			}


			////////////////Variation Fields Start//////
			function it_get_woo_vd_items(){
				global $wpdb;
				$new_attr 			= array();
				$attribute_keys 	= array();
				$attribute_labels 	= array();
				$return 			= array();
				$it_variations 		= array();

				$new_item_attr_variation_id		= array();
				$new_item_attr_order_item_id	= array();
				$order_item_variations			= array();

				$sql = "SELECT it_postmeta_product_addons.meta_value product_attributes FROM {$wpdb->prefix}posts as it_posts";
				$sql .= " LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta_product_addons ON it_postmeta_product_addons.post_id = it_posts.ID";
				$sql .= " WHERE post_type in ('product')";
				$sql .= " AND it_postmeta_product_addons.meta_key IN ('_product_attributes') ";

				$product_addon_objects = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				//$this->print_array($attributes);
				$product_addon_master = array();
				if(!$product_addon_objects){
					foreach($product_addon_objects as $key => $value){
						$product_addon_lists = unserialize($value->product_attributes);
						if(is_array($product_addon_lists)) {
							foreach ( $product_addon_lists as $key2 => $value2 ) {
								$product_addon_master[] = $key2;
								//$attribute_keys2[]	= "wcv_".str_replace("pa_","",$key2);
							}
						}
						//$this->print_array($product_addon_lists);
					}
				}

				$product_addon_master_key = "";
				if(!$product_addon_master){
					$product_addon_master = array_unique($product_addon_master);
					sort($product_addon_master);

					$product_addon_master_key = implode("','", $product_addon_master);
				}

				$attribute_meta_key = $product_addon_master_key;

				$sql = "SELECT TRIM(LEADING 'pa_' FROM woocommerce_order_itemmeta.meta_key) AS attribute_key,
						woocommerce_order_itemmeta.meta_value AS attribute_value, woocommerce_order_itemmeta.order_item_id, woocommerce_order_itemmeta.meta_key AS meta_key";
				$sql .= " FROM {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta";

				$sql .= " WHERE woocommerce_order_itemmeta.meta_key IN ('{$attribute_meta_key}')";

				$sql .= " GROUP BY attribute_value ORDER BY attribute_key ASC";

				$it_item_attributes =  $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				if($it_item_attributes){
					foreach($it_item_attributes as $key => $value){
						$attribute_key 		= $value->attribute_key;
						$attribute_value	= $value->attribute_value;
						$attribute_value 	= ucwords(str_replace("-"," ",$attribute_value));
						$new_item_attr_order_item_id[$value->meta_key][$value->attribute_value] = $attribute_value;
					}
				}
				return $new_item_attr_order_item_id;
			}
			function it_woo_filter_chars($string) {
			   $string = str_replace(' ', '_', $string);
			   $string = str_replace('-', '_', $string);
			   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
			}

			function it_get_woo_var_dropdowns(){
				$new_item_attr_order_item_id = $this->it_get_woo_vd_items();
				$output = "";
				if(!$new_item_attr_order_item_id){
					$vi = 0;
					//$output .= '<div class="form-group dynamic_fields">';
					foreach($new_item_attr_order_item_id as $key => $values):
						$vl = str_replace("attribute_pa_","",$key);
						$vl = str_replace("pa_","",$vl);

						$vl = str_replace("-"," ",$vl);
						$label = ucwords($vl);

						$id = str_replace(" ","_",$vl);

						$attr = array();
						foreach($values as $k => $v){
							$attr[] = $k;
						}
						$detault =  $input = implode(",",$attr);
		//				$output .= '<div class="FormRow'.($vi%2 ? ' SecondRow' : ' FirstRow').' var_attr_'.$key.'">';
		//				$output .= '<div class="input-text">';
							$attribute_values = $values;
							$option='';
							$all_option=array();
							$output.='
							<div class="col-md-6">
								<div class="awr-form-title">
									'.$label.'
								</div>
									<select name="it_new_value_variations['.$key.'][]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search variation_elements">
										';

											foreach($attribute_values as $key => $value){
												$all_option[]=$key;

												$option.="<option value='".$key."' >".$value."</option>";
											}

									$output.='<option value="'.implode(',',$all_option).'">Select All</option>'.$option;
									$output.='
									</select>

								</div>';

					//	$output .= '</div>';
				//		$output .= '</div>';
						$vi++;
					endforeach;
			//		$output .= '</div>';
				}
				return $output;
			}
			////////////////Variation Fields End//////

			function it_get_woo_all_prod_sku($post_type = 'product'){
				global $wpdb;

				$sql = "SELECT postmeta_sku.meta_value AS id, postmeta_sku.meta_value AS label FROM `{$wpdb->prefix}posts` AS posts";

				$sql .= "
						LEFT JOIN  {$wpdb->prefix}term_relationships 	as term_relationships_product_type 	ON term_relationships_product_type.object_id		=	posts.ID
						LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_product_type 		ON term_taxonomy_product_type.term_taxonomy_id		=	term_relationships_product_type.term_taxonomy_id
						LEFT JOIN  {$wpdb->prefix}terms 				as terms_product_type 				ON terms_product_type.term_id						=	term_taxonomy_product_type.term_id";

				$sql .= " LEFT JOIN {$wpdb->prefix}postmeta AS postmeta_sku ON postmeta_sku.post_id = posts.ID";

				$sql .= " WHERE posts.post_type = '{$post_type}'";

				$sql .= " AND posts.post_status = 'publish'";

				$sql .= " AND terms_product_type.name IN ('variable','simple')";

				$sql .= " AND postmeta_sku.meta_key = '_sku' AND LENGTH(postmeta_sku.meta_value) > 0";

				$sql .= " GROUP BY postmeta_sku.meta_value ORDER BY postmeta_sku.meta_value";

				$products = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $products;
			}


			function it_get_woo_var_prod_sku($post_type = 'product'){
				global $wpdb;

				$sql = "SELECT postmeta_sku.meta_value AS id, postmeta_sku.meta_value AS label FROM `{$wpdb->prefix}posts` AS posts";

				$sql .= "
						LEFT JOIN  {$wpdb->prefix}term_relationships 	as term_relationships_product_type 	ON term_relationships_product_type.object_id		=	posts.ID
						LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_product_type 		ON term_taxonomy_product_type.term_taxonomy_id		=	term_relationships_product_type.term_taxonomy_id
						LEFT JOIN  {$wpdb->prefix}terms 				as terms_product_type 				ON terms_product_type.term_id						=	term_taxonomy_product_type.term_id";

				$sql .= " LEFT JOIN {$wpdb->prefix}postmeta AS postmeta_sku ON postmeta_sku.post_id = posts.ID";

				$sql .= " WHERE posts.post_type = '{$post_type}' AND posts.post_status = 'publish'";

				$sql .= " AND terms_product_type.name IN ('variable')";

				$sql .= " AND postmeta_sku.meta_key = '_sku' AND LENGTH(postmeta_sku.meta_value) > 0";

				$sql .= " GROUP BY postmeta_sku.meta_value ORDER BY postmeta_sku.meta_value";

				$products = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $products;
			}

			function it_get_woo_prod_sku($it_product_type = "simple"){
				global $wpdb;

				$sql = "SELECT it_postmeta_sku.meta_value AS id, it_postmeta_sku.meta_value AS label

				FROM `{$wpdb->prefix}woocommerce_order_items` as it_woocommerce_order_items

				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id = it_woocommerce_order_items.order_item_id

				LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta_sku ON it_postmeta_sku.post_id = woocommerce_order_itemmeta.meta_value

				";
				if($it_product_type == "variation")
					$sql .= " LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta2 ON it_woocommerce_order_itemmeta2.order_item_id = it_woocommerce_order_items.order_item_id";

				$sql .= " WHERE woocommerce_order_itemmeta.meta_key = '_product_id'";

				$sql .= " AND it_postmeta_sku.meta_key = '_sku' AND LENGTH(it_postmeta_sku.meta_value) > 0";

				if($it_product_type == "variation")
					$sql .= " AND it_woocommerce_order_itemmeta2.meta_key = '_variation_id' AND it_woocommerce_order_itemmeta2.meta_value > 0";

				$sql .= " GROUP BY it_postmeta_sku.meta_value ORDER BY it_postmeta_sku.meta_value ASC";
				$products = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//$this->print_array($products);

				//$this->print_sql($sql);

				return $products;
			}

			function it_get_woo_var_sku($it_product_type = "simple"){
				global $wpdb;

				$sql = "  SELECT it_postmeta_sku.meta_value AS id, it_postmeta_sku.meta_value AS label FROM `{$wpdb->prefix}woocommerce_order_itemmeta` AS woocommerce_order_itemmeta";
				$sql .= " LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta_sku ON it_postmeta_sku.post_id = woocommerce_order_itemmeta.meta_value";
				$sql .= " WHERE woocommerce_order_itemmeta.meta_key = '_variation_id' AND woocommerce_order_itemmeta.meta_value > 0";
				$sql .= " AND it_postmeta_sku.meta_key = '_sku' AND LENGTH(it_postmeta_sku.meta_value) > 0";
				$sql .= " GROUP BY it_postmeta_sku.meta_value ORDER BY it_postmeta_sku.meta_value ASC";
				$products = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				return $products;
			}


			//GET GATEWAY
			function it_get_woo_pay_gateways(){
				global $wpdb;

				$sql = "
					SELECT
					it_postmeta1.meta_value 							as id
					,it_postmeta3.meta_value						 	as label


					FROM {$wpdb->prefix}posts as shop_order
					LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta1 on it_postmeta1.post_id = shop_order.ID
					LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta3 on it_postmeta3.post_id = shop_order.ID";

					$sql .= "
					WHERE shop_order.post_type	= 'shop_order'
					AND it_postmeta1.meta_key 		= '_payment_method'
					AND	it_postmeta3.meta_key 		= '_payment_method_title'";

					$sql .= " group by it_postmeta1.meta_value ORDER BY it_postmeta3.meta_value ASC";

					$wpdb->flush();
					$wpdb->query("SET SQL_BIG_SELECTS=1");
					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

					return $order_items;
			}



			//GET REQUEST PARAMETERS
			public function it_get_woo_requests($name,$default = NULL,$set = false){

				if(isset($this->search_form_fields[$name])){
					$newRequest = $this->search_form_fields[$name];

					if(is_array($newRequest)){
						$newRequest = implode(",", $newRequest);
					}else{
						$newRequest = trim($newRequest);
					}

					if($set) $this->search_form_fields[$name] = $newRequest;

					return $newRequest;
				}else{
					if($set) 	$this->search_form_fields[$name] = $default;
					return $default;
				}
			}

			public function it_get_woo_requests_links($name,$default = NULL,$set = false){

				if(isset($_REQUEST[$name])){
					$newRequest = $_REQUEST[$name];

					if(is_array($newRequest)){
						$newRequest = implode(",", $newRequest);
					}else{
						$newRequest = trim($newRequest);
					}

					if($set) $_REQUEST[$name] = $newRequest;

					return $newRequest;
				}else{
					if($set) 	$_REQUEST[$name] = $default;
					return $default;
				}
			}

			var $request_string = array();
			function it_get_woo_sm_requests($id=1,$string='', $default = NULL){

				if(isset($this->request_string[$id])){
					$string = $this->request_string[$id];
				}else{
					if($string == "'-1'" || $string == "\'-1\'"  || $string == "-1" ||$string == "''" || strlen($string) <= 0)$string = $default;
					if(strlen($string) > 0 and $string != $default){ $string  		= "'".str_replace(",","','",$string)."'";}
					$this->request_string[$id] = $string;
				}

				return $string;
			}

			function it_get_woo_requests_default($name, $default='', $set = false){
				if(isset($_REQUEST[$name])){
					$newRequest = trim($_REQUEST[$name]);
					return $newRequest;
				}else{
					if($set) $_REQUEST[$name] = $default;
					return $default;
				}
			}


			//VARIATION STOCK
			function it_get_woo_vp_sku($order_id_string = array()){
				global $wpdb;

				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				$sql = "SELECT meta_value, post_id as product_id
						FROM  {$wpdb->prefix}postmeta as postmeta WHERE
						meta_key = '_sku' AND LENGTH(meta_value)>0";

				if(strlen($order_id_string) > 0){
					$sql .= " AND post_id IN ({$order_id_string})";
				}

				$sql .= " GROUP BY post_id";

				$order_items 		= $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				$product_variation  = array();
				if(!$order_items){
					foreach ( $order_items as $key => $order_item ) {
						$it_product_id		=	$order_item->product_id;
						$meta_value		=	$order_item->meta_value;
						$product_variation[$it_product_id] =  $meta_value;
					}
				}
				return $product_variation;

			}

			function it_get_woo_prod_var($order_id_string = array()){
				global $wpdb;

				if(is_array($order_id_string)){
					$order_id_string = implode(",",$order_id_string);
				}

				$sql = "SELECT meta_key, REPLACE(REPLACE(meta_key, 'attribute_', ''),'pa_','') AS attributes, meta_value, post_id as variation_id
						FROM  {$wpdb->prefix}postmeta as postmeta WHERE
						meta_key LIKE '%attribute_%'";

				if(strlen($order_id_string) > 0){
					$sql .= " AND post_id IN ({$order_id_string})";
					//$sql .= " AND post_id IN (23)";
				}

				$order_items 		= $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				$product_variation  = array();
				if(!$order_items){
					foreach ( $order_items as $key => $order_item ) {
						$variation_label	=	ucfirst($order_item->meta_value);
						$variation_key		=	$order_item->attributes;
						$it_variation_id		=	$order_item->variation_id;
						$product_variation[$it_variation_id][$variation_key] =  $variation_label;
					}
				}
				return $product_variation;
			}


			//PROJECT SALE
			function it_get_woo_old_orders_status($old = array('cancelled'),$new = array('cancelled')){
				$cancelled_id = $new;
				return $cancelled_id;
			}

			function it_get_woo_ts_months($it_shop_order_status, $it_hide_os=[], $it_from_date='', $it_to_date='', $meta_key = "_order_discount", $meta_key2 = NULL){
//return [];
                global $wpdb;
				$date_format = $this->it_date_format($it_from_date);
                $sql = " SELECT DATE_FORMAT(it_posts.post_date,'%M-%Y') AS 'Month'";
                if($meta_key2){
                    $sql .= " ,SUM(postmeta.meta_value + it_postmeta2.meta_value) AS 'TotalAmount'";
                }else{
                    $sql .= " ,SUM(postmeta.meta_value) AS 'TotalAmount'";
                }


                $sql .= " FROM {$wpdb->prefix}posts as it_posts

                LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON it_posts.ID=postmeta.post_id";

                if($meta_key2){
                    $sql .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta2 ON it_posts.ID=it_postmeta2.post_id";
                }



                $sql .= " WHERE  post_type='shop_order'";
                $sql .= " AND postmeta.meta_key='{$meta_key}'";

                if($meta_key2){
                    $sql .= " AND it_postmeta2.meta_key='{$meta_key2}'";
                }


               if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
                    $it_in_shop_os		= implode("', '",$it_shop_order_status);
                    $sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
                }

                if ($it_from_date != NULL && $it_to_date != NULL) {
					   
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
					
                }
				//return $it_hide_os;
                if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					
	                $in_it_hide_os =$it_hide_os;
	                if(is_array($it_hide_os))
                        $in_it_hide_os = implode("', '", $it_hide_os);
                    $sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
                }
				
                $sql .= "
                GROUP BY YEAR(it_posts.post_date), MONTH(it_posts.post_date)
                ORDER BY YEAR(it_posts.post_date), MONTH(it_posts.post_date);";

                //return $sql;

                //$this->print_sql($sql);

                $order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
                $dataArray	 = array();
                foreach($order_items as $key => $order_item) {
                    $Month				= $order_item->Month;
                    $Amount           	= $order_item->TotalAmount;
                    $dataArray[$Month]	= $Amount;
                }

                return $dataArray;
            }


			function it_get_woo_tss_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date, $month_count, $meta_key = "_order_discount"){
				global $wpdb;
				$date_format = $this->it_date_format($it_from_date);
				$sql = " SELECT
				DATE_FORMAT(it_posts.post_date,'%M-%Y') AS 'Month'
				,SUM(meta_value) AS 'TotalAmount'
				,it_posts.post_status AS 'order_status'
				FROM {$wpdb->prefix}posts as it_posts
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON it_posts.ID=postmeta.post_id";


				$sql .= "
				WHERE post_type='shop_order' AND meta_key='{$meta_key}'";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}


				if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os =$it_hide_os;
					if(is_array($it_hide_os))
						$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "
				GROUP BY YEAR(it_posts.post_date), MONTH(it_posts.post_date)
				ORDER BY YEAR(it_posts.post_date), MONTH(it_posts.post_date);";

				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//$this->print_sql($sql);
				//$this->print_array($wpdb);

				$dataArray	 = array();
				foreach($order_items as $key => $order_item) {
					$Month				= $order_item->Month;
					$Amount           	= $order_item->TotalAmount;
					$dataArray[$Month]	= $Amount;
				}

				return $dataArray;
			}


			function it_get_woo_pora_months($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date, $month_count){
				global $wpdb;
				$date_format = $this->it_date_format($it_from_date);
				$sql = " SELECT SUM(postmeta.meta_value) 	as total_amount,  DATE_FORMAT(it_posts.post_date,'%M-%Y') AS 'Month'

				FROM {$wpdb->prefix}posts as it_posts

				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	it_posts.ID";

				$sql .= " LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.ID	=	it_posts.post_parent";



				$sql .= " WHERE it_posts.post_type = 'shop_order_refund' AND  postmeta.meta_key='_refund_amount'";

				 if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				$sql .= " AND shop_order.post_type = 'shop_order'";



				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND shop_order.post_status IN ('{$it_in_shop_os}')";
				}

				$sql .= " AND shop_order.post_status NOT IN ('wc-refunded')";



				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os =$it_hide_os;
					if(is_array($it_hide_os))
						$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND shop_order.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "
				GROUP BY YEAR(it_posts.post_date), MONTH(it_posts.post_date)
				ORDER BY YEAR(it_posts.post_date), MONTH(it_posts.post_date);";

				$wpdb->query("SET SQL_BIG_SELECTS=1");

				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//$this->print_sql($sql);
				//$this->print_array($order_items);

				$dataArray	 = array();
				if(!$order_items){
					foreach($order_items as $key => $order_item) {
						$Month				= $order_item->Month;
						$Amount           	= $order_item->total_amount;
						$dataArray[$Month]	= $Amount;
					}
				}

				//$this->print_array($dataArray);

				return $dataArray;
			}

			function it_get_woo_total($items,$key_name){
				$total = 0;
				foreach ($items as $key => $value) {
					$total = $total + $value[$key_name];
				}

				$total	= trim($total);
				$total	= strlen($total) > 0 ? $total : 0;

				return $total;
			}



			//DASHBOARD
			function dashboard_it_get_por_amount($type = "today",$it_shop_order_status=[],$it_hide_os=[],$it_from_date='',$it_to_date=''){
				global $wpdb;

				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));
				$date_format = $this->it_date_format($it_from_date);

				$sql = " SELECT SUM(postmeta.meta_value) 		as total_amount

				FROM {$wpdb->prefix}posts as it_posts

				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	it_posts.ID";

				$sql .= " LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.ID	=	it_posts.post_parent";


				$sql .= " WHERE it_posts.post_type = 'shop_order_refund' AND  postmeta.meta_key='_refund_amount'";

				$sql .= " AND shop_order.post_type = 'shop_order'";


				$sql .= " AND shop_order.post_status NOT IN ('wc-refunded')";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  shop_order.post_status IN ('{$it_in_shop_os}')";
				}


				if ($it_from_date != NULL &&  $it_to_date != NULL && $type == "total"){
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if($type == "today") $sql .= " AND DATE(it_posts.post_date) = '{$today_date}'";

				if($type == "yesterday") 	$sql .= " AND DATE(it_posts.post_date) = '{$yesterday_date}'";

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os		= implode("', '",$it_hide_os);
					$sql .= " AND  shop_order.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= " LIMIT 1";

				//print_r($it_hide_os);
				//echo $sql;

				//$this->print_sql($sql);

				$wpdb->query("SET SQL_BIG_SELECTS=1");

				$order_items = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $order_items;

			}

			function it_get_dashboard_totals_coupons($type = "today",$it_shop_order_status=[],$it_hide_os=[],$it_from_date='',$it_to_date=''){
				global $wpdb,$options;
				$date_format = $this->it_date_format($it_from_date);
				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));
				$sql = "
				SELECT
				SUM(woocommerce_order_itemmeta.meta_value) As 'total_amount',
				Count(*) AS 'total_count'
				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id=it_woocommerce_order_items.order_item_id
				LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=it_woocommerce_order_items.order_id";

				$sql .= "
				WHERE
				it_woocommerce_order_items.order_item_type='coupon'
				AND woocommerce_order_itemmeta.meta_key='discount_amount'
				AND it_posts.post_type='shop_order'
				";

				if($type == "today") $sql .= " AND DATE(it_posts.post_date) = '{$today_date}'";
				if($type == "yesterday") 	$sql .= " AND DATE(it_posts.post_date) = '{$yesterday_date}'";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL &&  $it_to_date != NULL && $type != "today"){
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os		= implode("', '",$it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				//$this->print_sql($sql);
				return $order_items = $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				///$this->print_array($order_items);
			}

			function it_get_dashboard_value($data = NULL, $id='', $default = ''){
				if($data){
					if($data->$id)
						return $data->$id;
				}
				return $default;
			}

			function it_get_dashboard_avarages($first_value = 0, $second_value = 0, $default = 0){
				$return = $default;
				$first_value = trim($first_value);
				$second_value = trim($second_value);

				if($first_value > 0  and $second_value > 0){
					$return = ($first_value/$second_value);
				}

				return $return;
			}

			function it_get_dashborad_totals_orders($type = "today", $meta_key="_order_tax",$order_item_type="tax",$it_shop_order_status=[],$it_hide_os=[],$it_from_date='',$it_to_date=''){
				global $wpdb;
				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));

				$date_format = $this->it_date_format($it_from_date);

				$sql = "  SELECT";
				$sql .= " SUM(postmeta1.meta_value) 	AS 'total_amount'";
				$sql .= " ,count(posts.ID) 				AS 'total_count'";
				$sql .= " FROM {$wpdb->prefix}posts as posts";
				$sql .= " LEFT JOIN  {$wpdb->prefix}postmeta as postmeta1 ON postmeta1.post_id=posts.ID";

				$sql .= " WHERE postmeta1.meta_key = '{$meta_key}' AND posts.post_type = 'shop_order' AND postmeta1.meta_value > 0";


				$sql .= " AND posts.post_type='shop_order' ";

				if($type == "today") $sql .= " AND DATE(posts.post_date) = '{$today_date}'";
				if($type == "yesterday") 	$sql .= " AND DATE(posts.post_date) = '{$yesterday_date}'";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$in_shop_order_status		= implode("', '",$it_shop_order_status);
					$sql .= " AND  posts.post_status IN ('{$in_shop_order_status}')";
				}

				if ($it_from_date != NULL &&  $it_to_date != NULL && $type != "today"){
					$sql .= " AND DATE(posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_hide_order_status		= implode("', '",$it_hide_os);
					$sql .= " AND  posts.post_status NOT IN ('{$in_hide_order_status}')";
				}

				return $order_items = $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 


			}


			function get_total_of_order($type = "today", $meta_key="_order_tax",$order_item_type="tax",$shop_order_status=[],$hide_order_status=[],$start_date='',$end_date=''){
				global $wpdb;
				$today_date 			= $this->today;
				$yesterday_date 		= $this->yesterday;
				$date_format = $this->it_date_format($start_date);
				$sql = "  SELECT";
				$sql .= " SUM(postmeta1.meta_value) 	AS 'total_amount'";
				$sql .= " ,count(posts.ID) 				AS 'total_count'";
				$sql .= " FROM {$wpdb->prefix}posts as posts";
				$sql .= " LEFT JOIN  {$wpdb->prefix}postmeta as postmeta1 ON postmeta1.post_id=posts.ID";


				//$sql .= " WHERE postmeta1.meta_key = '{$meta_key}' AND woocommerce_order_items.order_item_type = '{$order_item_type}'";
				$sql .= " WHERE postmeta1.meta_key = '{$meta_key}' AND posts.post_type = 'shop_order' AND postmeta1.meta_value > 0";
				//$sql .= " AND woocommerce_order_items.order_item_type = '{$order_item_type}'";

				$sql .= " AND posts.post_type='shop_order' ";

				if($type == "today") $sql .= " AND DATE(posts.post_date) = '{$today_date}'";
				if($type == "yesterday") 	$sql .= " AND DATE(posts.post_date) = '{$yesterday_date}'";

				if(is_array($shop_order_status) && !empty(array_filter($shop_order_status))){
					$in_shop_order_status		= implode("', '",$shop_order_status);
					$sql .= " AND  posts.post_status IN ('{$in_shop_order_status}')";
				}

				if ($start_date != NULL &&  $end_date != NULL && $type != "today"){
					$sql .= " AND DATE(posts.post_date) BETWEEN STR_TO_DATE('" . $start_date . "', '$date_format') and STR_TO_DATE('" . $end_date . "', '$date_format')";
				}

				if(is_array($hide_order_status) && !empty(array_filter($hide_order_status))){
					$in_hide_order_status		= implode("', '",$hide_order_status);
					$sql .= " AND  posts.post_status NOT IN ('{$in_hide_order_status}')";
				}

				return $order_items = $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 


			}

			public $firstorderdate=NULL;
			function it_get_dashboard_first_orders_date($key = NULL){
				global $wpdb;
				if($this->firstorderdate){
					return $this->firstorderdate;
				}else{
					$sql = "SELECT DATE_FORMAT(it_posts.post_date, '%Y-%m-%d') AS 'OrderDate' FROM {$wpdb->prefix}posts  as it_posts	WHERE it_posts.post_type='shop_order' Order By it_posts.post_date ASC LIMIT 1";
					return $this->firstorderdate = $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				}
			}

			function it_get_dashboard_tsd($key = NULL){
				$now = time(); // or your date as well
				//$this->it_get_dashboard_first_orders_date();
				$first_date = strtotime(($this->it_get_dashboard_first_orders_date($key)));
				$datediff = $now - $first_date;
				$it_total_shop_day = floor($datediff/(60*60*24));
				return $it_total_shop_day;
			}

			function it_get_dashboard_tcc(){
				global $wpdb,$sql,$Limit;
				$sql = "SELECT COUNT(*) As 'category_count' FROM {$wpdb->prefix}term_taxonomy as term_taxonomy
						LEFT JOIN  {$wpdb->prefix}terms as it_terms ON it_terms.term_id=term_taxonomy.term_id
				WHERE taxonomy ='product_cat'";
				return $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				//print_array($order_items);
			}

			function it_get_dashboard_tpc(){
				global $wpdb,$sql,$Limit;
				$sql = "SELECT COUNT(*) AS 'product_count'  FROM {$wpdb->prefix}posts as it_posts WHERE  post_type='product' AND post_status = 'publish'";
				return $wpdb->get_var($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
			}

			////ADDED IN VER4.0
            /// COST OF GOOD
			function it_get_dashboard_totals_cogs($type = 'cog',$it_shop_order_status=[],$it_hide_os=[],$it_from_date='',$it_to_date=''){
				global $wpdb;

				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));
				$date_format = $this->it_date_format($it_from_date);
				$sql = "
				SELECT
				SUM(woo_itemmeta_cog.meta_value) as cog
				FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
				LEFT JOIN {$wpdb->prefix}posts as it_posts ON it_posts.ID=it_woocommerce_order_items.order_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id	=	it_woocommerce_order_items.order_item_id
				LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as woo_itemmeta_cog ON woocommerce_order_itemmeta.order_item_id	=	woo_itemmeta_cog.order_item_id";

				$sql .= " WHERE  it_posts.post_type = 'shop_order' AND woocommerce_order_itemmeta.meta_key = '_product_id'
				AND woo_itemmeta_cog.meta_key='".__IT_COG_TOTAL__."'
				";

				if($type == "today") 		$sql .= " AND DATE(it_posts.post_date) = '{$today_date}'";
				if($type == "yesterday") 	$sql .= " AND DATE(it_posts.post_date) = '{$yesterday_date}'";

				if($type == "today_yesterday"){
					$sql .= " AND (DATE(it_posts.post_date) = '{$today_date}'";
					$sql .= " OR DATE(it_posts.post_date) = '{$yesterday_date}')";
				}


				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}


				if ($it_from_date != NULL &&  $it_to_date != NULL && $type != "today"){
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os		= implode("', '",$it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				if($type == "today_yesterday"){
					$sql .= " GROUP BY group_date";
					$items =  $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				}else{
					$items =  $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				}
//echo $sql;
				//$this->print_sql($sql);
				return $items;
			}

			function it_get_dashboard_totals_orders($type = 'total',$it_shop_order_status=[],$it_hide_os=[],$it_from_date='',$it_to_date=''){
				global $wpdb;

				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));
				$date_format = $this->it_date_format($it_from_date);
				$sql = "
				SELECT
				count(*) AS 'total_count'
				,SUM(it_postmeta1.meta_value) AS 'total_amount'
				,DATE(it_posts.post_date) AS 'group_date'
				FROM {$wpdb->prefix}posts as it_posts ";
				$sql .= " LEFT JOIN  {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id = it_posts.ID";
				$sql .= " WHERE  post_type='shop_order'";



				$sql .= " AND it_postmeta1.meta_key='_order_total'";

				if($type == "today") 		$sql .= " AND DATE(it_posts.post_date) = '{$today_date}'";
				if($type == "yesterday") 	$sql .= " AND DATE(it_posts.post_date) = '{$yesterday_date}'";

				if($type == "today_yesterday"){
					$sql .= " AND (DATE(it_posts.post_date) = '{$today_date}'";
					$sql .= " OR DATE(it_posts.post_date) = '{$yesterday_date}')";
				}

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}


				if ($it_from_date != NULL &&  $it_to_date != NULL && $type != "today"){
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os		= implode("', '",$it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				if($type == "today_yesterday"){
					$sql .= " GROUP BY group_date";
					$items =  $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				}else{
					$items =  $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				}

				//$this->print_sql($sql);
				return $items;
			}

			function it_get_dashboard_toss($type = 'total',$it_shop_order_status=[],$it_hide_os=[],$it_from_date='',$it_to_date=''){
				global $wpdb;
				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));
				$date_format = $this->it_date_format($it_from_date);
				$id = "_order_shipping";
				$sql = "
				SELECT
				SUM(it_postmeta2.meta_value)						as total
				,COUNT(it_posts.ID) 							as quantity
				FROM {$wpdb->prefix}posts as it_posts
				LEFT JOIN	{$wpdb->prefix}postmeta as it_postmeta2 on it_postmeta2.post_id = it_posts.ID";

				$sql .= " WHERE it_posts.post_type	= 'shop_order'";
				$sql .= " AND it_postmeta2.meta_value > 0";
				$sql .= " AND it_postmeta2.meta_key 	= '{$id}'";


				if($type == "today") $sql .= " AND DATE(it_posts.post_date) = '{$today_date}'";
				if($type == "yesterday") 	$sql .= " AND DATE(it_posts.post_date) = '{$yesterday_date}'";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL &&  $it_to_date != NULL && $type == "total"){
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os		= implode("', '",$it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$items =  $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return isset($items->total) ? $items->total : 0;

			}


			function it_get_dashboard_tbs($type = 'today',$status = 'refunded',$it_hide_os=[],$it_from_date='',$it_to_date='')	{
				global $wpdb;
				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));

				$date_format = $this->it_date_format($it_from_date);

				$sql = "SELECT";

				$sql .= " SUM( postmeta.meta_value) As 'total_amount', count( postmeta.post_id) AS 'total_count'";
				$sql .= "  FROM {$wpdb->prefix}posts as it_posts";

				$status = "wc-".$status;
				$date_field = ($status == 'wc-refunded') ? "post_modified" : "post_date";

				$sql .= "
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id=it_posts.ID
				WHERE postmeta.meta_key = '_order_total' AND it_posts.post_type='shop_order'";



				if($type == "today" || $type == "today") $sql .= " AND DATE(it_posts.{$date_field}) = '".$today_date."'";
				if($type == "yesterday") 	$sql .=" AND DATE(it_posts.{$date_field}) = '".$yesterday_date."'";


				if ($it_from_date != NULL &&  $it_to_date != NULL && $type != "today"){
					$sql .= " AND DATE(it_posts.{$date_field}) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(strlen($status)>0){
					$sql .= " AND  it_posts.post_status IN ('{$status}')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os		= implode("', '",$it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= " Group BY it_posts.post_status ORDER BY total_amount DESC";
				//return $sql;
				return $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

			}

			function it_get_dashboard_lod($it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date){
				global $wpdb;
				$datetime= date_i18n("Y-m-d H:i:s");
				$date_format = $this->it_date_format($it_from_date);
				$sql = "SELECT ";
				$sql .= " it_posts.ID AS last_order_id, it_posts.post_date AS last_order_date, it_posts.post_status AS last_order_status, DATEDIFF('{$datetime}', it_posts.post_date) AS last_order_day, '{$datetime}' AS current_datetime" ;
				$sql .= " FROM {$wpdb->prefix}posts as it_posts";

				$sql .= " WHERE  it_posts.post_type='shop_order'";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL &&  $it_to_date != NULL){
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os		= implode("', '",$it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= " Order By it_posts.post_date DESC ";

				$sql .= " LIMIT 1";

				$wpdb->query("SET SQL_BIG_SELECTS=1");

				$order_items = $wpdb->get_row($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				//$this->print_array($order_items);

				return $order_items;
			}

			function it_get_new_customer_role($user_id){
				global $wpdb;

				$sql = "SELECT it_posts.id as order_id,it_posts.post_date as order_date,postmeta.meta_value as order_total
				FROM {$wpdb->prefix}posts as it_posts
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id = it_posts.ID
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta1 ON postmeta1.post_id = it_posts.ID
				";

				$sql .= " WHERE  it_posts.post_type = 'shop_order'";

				$sql .= " AND postmeta1.meta_key = '_customer_user'";

				$sql.=" AND postmeta1.meta_value='".$user_id."' ";

				$sql .= " AND postmeta.meta_key = '_order_total'";

				$sql .= " ORDER BY it_posts.post_date asc limit 0,1";

				$user =  $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 

				return $user;
			}

			function it_get_dashboard_ttoc($type = 'total', $guest_user = false){
				global $wpdb;

				$today_date 			= gmdate("Y-m-d");
				$yesterday_date 		= gmdate("Y-m-d",strtotime("-1 day",strtotime($today_date)));

				$sql = "SELECT ";
				if(!$guest_user){
					$sql .= " users.ID, ";
				}
				$sql .= " it_posts.post_date
				FROM {$wpdb->prefix}posts as it_posts
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id = it_posts.ID";

				if(!$guest_user){
					$sql .= " LEFT JOIN  {$wpdb->prefix}users as users ON users.ID = postmeta.meta_value";
				}

				$sql .= " WHERE  it_posts.post_type = 'shop_order'";

				$sql .= " AND postmeta.meta_key = '_customer_user'";

				if($guest_user){
					$sql .= " AND postmeta.meta_value = 0";
					if($type == "today")		$sql .= " AND DATE(it_posts.post_date) = '{$today_date}'";
					if($type == "yesterday")	$sql .= " AND DATE(it_posts.post_date) = '{$yesterday_date}'";
				}else{
					$sql .= " AND postmeta.meta_value > 0";
					if($type == "today")		$sql .= " AND DATE(users.user_registered) = '{$today_date}'";
					if($type == "yesterday")	$sql .= " AND DATE(users.user_registered) = '{$yesterday_date}'";
				}

				if(!$guest_user){
					$sql .= " GROUP BY  postmeta.meta_value";
				}else{
					$sql .= " GROUP BY  it_posts.ID";
				}



				$sql .= " ORDER BY it_posts.post_date desc";

				//echo $type;
				//$this->print_sql($sql);
				//
				$user =  $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				//$this->print_array($user);
				//echo "<br />";
				$count = count($user);
				//echo "<br />";
				//echo "<br />";
				return $count;
			}

			function it_get_dashboard_progress_of_details($total_today_sales = 0,$total_yesterday_sales = 0,$label = 'yesterday'){
				$progress_width 	= 0;
				$progress_label 	= '';
				$today_total_sales 	= "";
				$highlow			= "";

				if($total_today_sales > 0 and $total_yesterday_sales > 0){

					if($total_today_sales == $total_yesterday_sales){
						$progress_width 	= 100;
						$progress_label 	= sprintf("%.0f%%", $progress_width);;
						$today_total_sales 	= "{$progress_label} same as {$label}";
						$highlow			= "equal";
					}else if($total_today_sales > $total_yesterday_sales){
						if($total_yesterday_sales == 0){

						}else{
							//$progress_width 	= ($total_yesterday_sales/$total_today_sales)*100;
							$progress_width		= $this->it_get_number_percentage($total_yesterday_sales,$total_today_sales);
							$progress_label 	= sprintf("%.2f%%", $progress_width);;
							$today_total_sales 	= "{$progress_label} Increased in Compare with {$label}";
							$highlow			= "up";
						}

					}else if($total_today_sales < $total_yesterday_sales){
						if($total_today_sales == 0){

						}else{
							//$progress_width 	= ($total_today_sales/$total_yesterday_sales)*100;
							$progress_width		= $this->it_get_number_percentage($total_today_sales,$total_yesterday_sales);
							$progress_label 	= sprintf("%.2f%%", $progress_width);;
							$today_total_sales 	= "{$progress_label} Decreased in Compare with {$label}";
							$highlow			= "down";
						}

					}
				}else{

					if($total_today_sales == 0 and $total_yesterday_sales == 0){
						$progress_width 	= 0;
						$progress_label 	= sprintf("%.2f%%", $progress_width);;

						$highlow			= "equal";
					}else if($total_today_sales <= 0){
						$progress_width 	= 100;
						$progress_label 	= sprintf("%.2f%%", $progress_width);;
						$today_total_sales 	= "{$progress_label} Decreased in Compare with {$label}";
						$highlow			= "down";
					}else if($total_yesterday_sales <= 0){
						$progress_width 	= 100;
						$progress_label 	= sprintf("%.2f%%", $progress_width);;
						$today_total_sales 	= "{$progress_label} Decreased in Compare with {$label}";
						$highlow			= "down";
					}

				}
				return array('progress_width'=>$progress_width,'progress_label' => $today_total_sales,'progress_highlow'=>$highlow);
			}

			function it_get_dashboard_progress_contents($today = 0,$yesterday = 0, $label = 'yesterday'){

				$values 	= $this->it_get_dashboard_progress_of_details($today, $yesterday, $label = 'yesterday');
				$progress_width 	= $values['progress_width'];
				$progress_label 	= $values['progress_label'];
				$progress_highlow 	= $values['progress_highlow'];

				$output='<div class="awr-progress" >
							<span style="width:'.$progress_width.'%;"></span>
						</div>
						<span class="awr-progress-detail">'.$progress_label.'</span>';

				return $output;
			}

			function it_get_dashboard_time ($time, $current_time = NULL, $suffix = ''){
				if($time){
					if($current_time == NULL)
						$time = time() - $time; // to get the time since that moment
					else
						$time = $current_time - $time; // to get the time since that moment

					$tokens = array (
						31536000 => 'year',
						2592000 => 'month',
						604800 => 'week',
						86400 => 'day',
						3600 => 'hour',
						60 => 'minute',
						1 => 'second'
					);

					foreach ($tokens as $unit => $text) {
						if ($time < $unit) continue;
						$numberOfUnits = floor($time / $unit);
						return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'') .$suffix;
					}
				}else{
					return 0;
				}
			}


			function it_get_dashboard_numbers($value, $default = 0){
				global $options;
				$per_page = (isset($options[$value]) and strlen($options[$value]) > 0)? $options[$value] : $default;
				$per_page = is_numeric($per_page) ? $per_page : $default;
				return $per_page;
			}


			///////////////////
			//DASBOARD CHARTS
			function it_get_dashboard_sale_months_3d_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date)
			{
				global $wpdb;
				//WHERE it_posts.post_date BETWEEN DATE(NOW()) - INTERVAL (DAY(NOW()) - 1) DAY - INTERVAL 11 MONTH

				//FETCH MONTH STRING

				$date_format = $this->it_date_format($it_from_date);
				$sql = " SELECT
				YEAR(it_posts.post_date) as 'Year',
				MONTHNAME(it_posts.post_date) AS 'Month'
				,SUM(meta_value) AS 'TotalAmount'
				FROM {$wpdb->prefix}posts as it_posts
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON it_posts.ID=postmeta.post_id";


				$sql .= "
				WHERE post_type='shop_order' AND meta_key='_order_total'";


			   if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "
				GROUP BY YEAR(it_posts.post_date), MONTH(it_posts.post_date)
				ORDER BY YEAR(it_posts.post_date), MONTH(it_posts.post_date);";

				//return $sql;

				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				return $order_items;
			}

			function it_get_dashboard_sale_months_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date)
			{
				global $wpdb;
				//WHERE it_posts.post_date BETWEEN DATE(NOW()) - INTERVAL (DAY(NOW()) - 1) DAY - INTERVAL 11 MONTH

				//FETCH MONTH STRING

				$date_format = $this->it_date_format($it_from_date);
				$sql = " SELECT
				MONTHNAME(it_posts.post_date) AS 'Month'
				,SUM(meta_value) AS 'TotalAmount'
				FROM {$wpdb->prefix}posts as it_posts
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON it_posts.ID=postmeta.post_id";



				$sql .= "
				WHERE post_type='shop_order' AND meta_key='_order_total'";


			   if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "
				GROUP BY YEAR(it_posts.post_date), MONTH(it_posts.post_date)
				ORDER BY YEAR(it_posts.post_date), MONTH(it_posts.post_date);";


				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				return $order_items;
			}

			function it_get_dashboard_sale_months_multiple_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date)
			{
				global $wpdb;
				//WHERE it_posts.post_date BETWEEN DATE(NOW()) - INTERVAL (DAY(NOW()) - 1) DAY - INTERVAL 11 MONTH
				$date_format = $this->it_date_format($it_from_date);
				//FETCH MONTH STRING
				$sql = " SELECT
				(it_posts.post_date) AS 'Month'
				,SUM(meta_value) AS 'TotalAmount'
				FROM {$wpdb->prefix}posts as it_posts
				LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON it_posts.ID=postmeta.post_id";



				$sql .= "
				WHERE post_type='shop_order' AND meta_key='_order_total'";


			   if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "
				GROUP BY YEAR(it_posts.post_date), MONTH(it_posts.post_date)
				ORDER BY YEAR(it_posts.post_date), MONTH(it_posts.post_date);";

				//return $sql;

				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				return $order_items;
			}

			function it_get_dashboard_sale_days_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date)
			{
				global $wpdb;
				$date_format = $this->it_date_format($it_from_date);
				//print_array($weekarray);
				$sql       = " SELECT
					DATE(it_posts.post_date) AS 'Date' ,
					sum(meta_value) AS 'TotalAmount'

					FROM {$wpdb->prefix}posts as it_posts

					LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON it_posts.ID=postmeta.post_id";


				$sql .= " WHERE ";

				$sql .=" post_type='shop_order' AND meta_key='_order_total' ";


				//FETCH LAST 30 DAYS JUST
				//$sql .=" AND (it_posts.post_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY))";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "
					GROUP BY  DATE(it_posts.post_date)
					";

				 // echo $sql;
				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 


				return $order_items;

			}

			function it_get_dashboard_sale_weeks_chart($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date)
			{
				global $wpdb;
				$date_format = $this->it_date_format($it_from_date);
				$sql = " SELECT
					DATE(it_posts.post_date) AS 'Date' ,
					sum(meta_value) AS 'TotalAmount'

					FROM {$wpdb->prefix}posts as it_posts

					LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON it_posts.ID=postmeta.post_id";

				$sql .= "


					WHERE  post_type='shop_order' AND meta_key='_order_total' AND (it_posts.post_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY))";

				if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "
					GROUP BY  DATE(it_posts.post_date)
					";
				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				//echo $sql;
				return $order_items;
			}

			function it_get_dashboard_order_weeks_last($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date)
			{
				global $wpdb;

				$optionsid    = "sales_last_week";
				//$per_week     = $this->it_get_dashboard_numbers($optionsid,$this->per_page_default);
				$per_week     = 4;
				$date_string  = date_i18n("Y-m-d");
				$current_week = $this->current_week = $wpdb->get_var("SELECT WEEK('{$date_string}')");
				$current_year = $this->current_week = $wpdb->get_var("SELECT YEAR('{$date_string}')");
				$weeks        = array();

				//$per_week         = 3;

				if ($current_week > 0) {

					$last_week = $current_week - $per_week;

					$weeks = array();
					for ($i = 1; $i <= ($per_week); $i++) {
						$weeks[] = $last_week + $i;
					}


					$sql_array = array();
					foreach ($weeks as $item) {
						$sql = "
									SELECT
									IFNULL(SUM(postmeta.meta_value) , 0)  AS 'Value'
									,count( postmeta.post_id) as Count
									,DATE_ADD(MAKEDATE($current_year, 1), INTERVAL $item WEEK) AS Label2
									,'{$item}' AS 'Week'
									,IF(DATE_ADD(MAKEDATE($current_year, 1), INTERVAL $item WEEK) > CURDATE() , CURDATE(), DATE_ADD(MAKEDATE($current_year, 1), INTERVAL $item WEEK)) as Label
									FROM {$wpdb->prefix}postmeta as postmeta
									LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=postmeta.post_id";


						$sql .= "
									WHERE meta_key='_order_total'
									AND WEEK(DATE(it_posts.post_date)) = $item";

						if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
							$it_in_shop_os		= implode("', '",$it_shop_order_status);
							$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
						}

						if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
							$in_it_hide_os = implode("', '", $it_hide_os);
							$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
						}

						$sql_array[] = $sql;
						$sql         = "";
					}


					$sql         = implode(" UNION ", $sql_array);
					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					//$this->print_array($order_items);
				} else {
					$sql = "SELECT
						SUM(postmeta.meta_value)AS 'Value'
						,count( postmeta.post_id) as Count
						,DATE_ADD(MAKEDATE($current_year, 1), INTERVAL WEEK( DATE(it_posts.post_date)) WEEK) AS Label2
						,IF(DATE_ADD(MAKEDATE($current_year, 1), INTERVAL WEEK( DATE(it_posts.post_date)) WEEK) > CURDATE() , CURDATE(), DATE_ADD(MAKEDATE($current_year, 1), INTERVAL WEEK( DATE(it_posts.post_date)) WEEK)) as Label
						,WEEK( DATE(it_posts.post_date)) AS 'Week'

						FROM {$wpdb->prefix}postmeta as postmeta
						LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=postmeta.post_id";


					$sql .= "
						WHERE meta_key='_order_total'
						AND YEAR('{$date_string}') =  YEAR(DATE(it_posts.post_date))";

				   if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
						$it_in_shop_os		= implode("', '",$it_shop_order_status);
						$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
					}

					if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
						$in_it_hide_os = implode("', '", $it_hide_os);
						$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
					}
					$sql .= "
						GROUP BY Week
						order by it_posts.post_date ASC LIMIT 0, $per_week;";

					$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
					//$this->print_array($order_items);
				}

				//echo $sql;
				return $order_items;


			}

			function it_get_dashboard_top_products_chart_pie($it_shop_order_status, $it_hide_os, $it_from_date, $it_to_date)
			{
				global $wpdb, $sql, $Limit;

				$optionsid = "top_product_per_page";
				$per_page  = 10;
				$date_format = $this->it_date_format($it_from_date);
				$sql = "SELECT
					it_woocommerce_order_items.order_item_name AS 'Label'
					,it_woocommerce_order_items.order_item_id
					,SUM(woocommerce_order_itemmeta.meta_value) AS 'Qty'
					,SUM(it_woocommerce_order_itemmeta2.meta_value) AS 'Value'

					FROM {$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
					LEFT JOIN    {$wpdb->prefix}posts                        as it_posts                         ON it_posts.ID                                        =    it_woocommerce_order_items.order_id
					LEFT JOIN    {$wpdb->prefix}woocommerce_order_itemmeta     as woocommerce_order_itemmeta     ON woocommerce_order_itemmeta.order_item_id        =    it_woocommerce_order_items.order_item_id
					LEFT JOIN    {$wpdb->prefix}woocommerce_order_itemmeta     as it_woocommerce_order_itemmeta2     ON it_woocommerce_order_itemmeta2.order_item_id    =    it_woocommerce_order_items.order_item_id
					";


				$sql .= "
					WHERE
					it_posts.post_type                                 =    'shop_order'
					AND woocommerce_order_itemmeta.meta_key            =    '_qty'
					AND it_woocommerce_order_itemmeta2.meta_key        =    '_line_total'";

			    if(is_array($it_shop_order_status) && !empty(array_filter($it_shop_order_status))){
					$it_in_shop_os		= implode("', '",$it_shop_order_status);
					$sql .= " AND  it_posts.post_status IN ('{$it_in_shop_os}')";
				}

				if ($it_from_date != NULL && $it_to_date != NULL) {
					$sql .= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
				}

				if(is_array($it_hide_os) && !empty(array_filter($it_hide_os))){
					$in_it_hide_os = implode("', '", $it_hide_os);
					$sql .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
				}

				$sql .= "

					GROUP BY  it_woocommerce_order_items.order_item_name
					Order By Value DESC
					LIMIT {$per_page}";
				$order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
				//echo $sql;
				return $order_items;
			}


			//DASHBORAD DATA GRID
			function it_get_dashboard_value_order_sale($it_shop_order_status,$it_hide_os,$it_from_date,$it_to_date){}

			function it_get_dashboard_boxes_generator($type, $color, $icon, $title, $amount, $amount_type, $count, $count_type, $progress_amount=NULL){

				$html='';

				if($amount_type=='price'){
					$amount=$this->price($amount);
				}

				if($count_type=='number' && trim($count)!=''){
					$count='#'.$count;
				}else if($count_type=='precent' && trim($count)!=''){
					$count=$count.'%';
				}

				if($type=='simple'){
					$html='
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="awr-sum-item awr-sum-'.$color.' awr-'.$icon.'-ico">
								<div class="awr-txt">'.$title.'</div>
								<div class="awr-icon">
								    <i class="fa fa-1x '.$icon.'"></i>
								</div>
								<div class="awr-sum-content">
									<div class="awr-num-big">'.$amount.'</div>
									<span>'.$count.'</span>
								</div>
							</div><!--awr-sum-item -->
						</div>';
				}else{
					$html='
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="awr-sum-item awr-sum-item-progress awr-sum-'.$color.' awr-'.$icon.'-ico">
								<div class="awr-txt">'.$title.'</div>
								<div class="awr-icon">
								    <i class="fa fa-1x '.$icon.'"></i>
								</div>
								<div class="awr-sum-content">
									<div class="awr-num-big">'.$amount.'</div>
									<span>'.$count.'</span>
								</div>
								<div class="awr-bottom-num">
									'.$progress_amount.'
								</div>
							</div><!--awr-sum-item -->
						</div>';
				}
				return $html;
			}

			function it_translate_function($field,$default_vlaue=''){
				return get_option($field,$default_vlaue);
			}

			////ADDED IN VER4.0
			/// IMAGE VALIDATE
			function validImage($file) {
				$size = getimagesize($file);
				return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);
			}


			//CUSTOM WORK 15092
            function getShippingName($ship_code){
			    global $wpdb;
	            $sql = "SELECT pmeta.meta_value as shipping_address FROM {$wpdb->prefix}postmeta as pmeta WHERE pmeta.meta_key='_shipping_addresses' AND pmeta.meta_value LIKE '%{$ship_code}%' LIMIT 1
";
	            $order_items = $wpdb->get_results($sql); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared 
	            $shipping_address = $order_items;
	            foreach ($shipping_address as $item){
	                return $item->shipping_address;
                }

            }

		}//end class
	}//end if exist
?>
