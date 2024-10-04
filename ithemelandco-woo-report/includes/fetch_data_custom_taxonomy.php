<?php

	if($file_used=="sql_table")
	{

		//GET POSTED PARAMETERS
		$request 			= array();
		$start				= 0;
		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$date_format = $this->it_date_format($it_from_date);

		$it_parent_cat_id		= $this->it_get_woo_requests('it_parent_category_id','-1',true);
		if($it_parent_cat_id!='-1')
			$it_parent_cat_id  		= "'".str_replace(",","','",$it_parent_cat_id)."'";


		$it_customy_taxonomies	= $this->it_get_woo_requests('it_customy_taxonomies','-1',true);

		$it_child_cat_id	= $this->it_get_woo_requests('child_category_id','-1',true);
		$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		$it_list_parent_cat			= $this->it_get_woo_requests('list_parent_category',NULL,false);
		$category_id			= $this->it_get_woo_requests('it_category_id','-1',true);
		$it_group_by_parent_cat			= $this->it_get_woo_requests('group_by_parent_cat','-1',true);
		///////////HIDDEN FIELDS////////////
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_publish_order='no';
		$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);



		///////////////////////////


		//////////////////////
		//it_customy_taxonomies

		//DATE
		$it_from_date_condition='';

		//ORDER STATUS
		$it_order_status_condition='';
		$it_id_order_status_join='';
		$it_id_order_status_condition='';

		//CATEGORY
		$category_id_condition='';

		//ORDER STATUS
		$it_order_status_condition='';

		//PARENT CATEGORY
		$it_parent_cat_id_condition='';

		//CHILD CATEGORY
		$it_child_cat_id_condition='';

		//LIST PARENT CATEGORY
		$it_list_parent_cat_condition='';

		//PUBLISH STATUS
		$it_publish_order_condition='';

		//HIDE ORDER STATUS
		$it_hide_os_condition='';

		$sql_columns = "
		SUM(it_woocommerce_order_itemmeta_product_qty.meta_value) AS quantity
		,SUM(it_woocommerce_order_itemmeta_product_line_total.meta_value) AS total_amount
		,it_terms_product_id.term_id AS category_id
		,it_terms_product_id.name AS category_name
		,it_term_taxonomy_product_id.parent AS parent_category_id
		,it_terms_parent_product_id.name AS parent_category_name";

		$sql_joins= "{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items

		 LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_id ON it_woocommerce_order_itemmeta_product_id.order_item_id=it_woocommerce_order_items.order_item_id
		 LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_qty ON it_woocommerce_order_itemmeta_product_qty.order_item_id=it_woocommerce_order_items.order_item_id
		 LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta_product_line_total ON it_woocommerce_order_itemmeta_product_line_total.order_item_id=it_woocommerce_order_items.order_item_id";


		$sql_joins .= " 	LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_product_id 	ON it_term_relationships_product_id.object_id		=	it_woocommerce_order_itemmeta_product_id.meta_value
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy_product_id 		ON it_term_taxonomy_product_id.term_taxonomy_id	=	it_term_relationships_product_id.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_product_id 				ON it_terms_product_id.term_id						=	it_term_taxonomy_product_id.term_id

		 LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_parent_product_id 				ON it_terms_parent_product_id.term_id						=	it_term_taxonomy_product_id.parent

		 LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.id=it_woocommerce_order_items.order_id";

		if(strlen($it_id_order_status)>0 && $it_id_order_status != "-1" && $it_id_order_status != "no" && $it_id_order_status != "all"){
				$it_id_order_status_join= "
				LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
				LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
		}

		$sql_condition = " 1*1
		AND it_woocommerce_order_items.order_item_type 					= 'line_item'
		AND it_woocommerce_order_itemmeta_product_id.meta_key 			= '_product_id'
		AND it_woocommerce_order_itemmeta_product_qty.meta_key 			= '_qty'
		AND it_woocommerce_order_itemmeta_product_line_total.meta_key 	= '_line_total'
		AND it_term_taxonomy_product_id.taxonomy 						= '$it_customy_taxonomies'
		AND it_posts.post_type 											= 'shop_order'";

		if(strlen($it_id_order_status)>0 && $it_id_order_status != "-1" && $it_id_order_status != "no" && $it_id_order_status != "all"){
			$it_id_order_status_condition= " AND  term_taxonomy.term_id IN ({$it_id_order_status})";
		}

		if($it_parent_cat_id != NULL and $it_parent_cat_id != "-1"){
			$it_parent_cat_id_condition= " AND it_term_taxonomy_product_id.parent IN ($it_parent_cat_id)";
		}

		if($it_child_cat_id != NULL and $it_child_cat_id != "-1"){
			$it_child_cat_id_condition= " AND it_terms_product_id.term_id IN ($it_child_cat_id)";
		}

		if($it_list_parent_cat != NULL and $it_list_parent_cat > 0){
			$it_list_parent_cat_condition= " AND it_term_taxonomy_product_id.parent > 0";
		}
		if ($it_from_date != NULL &&  $it_to_date !=NULL){
			$it_from_date_condition= " AND DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format')";
		}

		if(strlen($it_publish_order)>0 && $it_publish_order != "-1" && $it_publish_order != "no" && $it_publish_order != "all"){
			$in_post_status		= str_replace(",","','",$it_publish_order);
			$it_publish_order_condition= " AND  it_posts.post_status IN ('{$in_post_status}')";
		}

		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition= " AND it_posts.post_status IN (".$it_order_status.")";

		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition= " AND it_posts.post_status NOT IN ('".$it_hide_os."')";


		if($category_id  && $category_id != "-1") {
			$category_id_condition= " AND it_terms_product_id.term_id IN ($category_id)";
		}


		$sql_group_by='';

		if($it_group_by_parent_cat == 1){
			$sql_group_by= " GROUP BY parent_category_id";
		}else{
			$sql_group_by= " GROUP BY category_id";
		};

		$sql_order_by= "  Order By total_amount DESC";

		$sql = "SELECT $sql_columns FROM $sql_joins $it_id_order_status_join WHERE $sql_condition
				$it_id_order_status_condition $it_parent_cat_id_condition $it_child_cat_id_condition
				$it_list_parent_cat_condition $it_from_date_condition $it_publish_order_condition
				$it_order_status_condition $it_hide_os_condition $category_id_condition
				$sql_group_by $sql_order_by
				";

		//echo $sql;

	}elseif($file_used=="data_table"){

		////ADDE IN VER4.0
		/// TOTAL ROWS VARIABLES
		$sales_qty=$category_count=$total_amnt=0;

		foreach($this->results as $items){
		    $index_cols=0;
		//for($i=1; $i<=20 ; $i++){

			////ADDE IN VER4.0
			/// TOTAL ROWS
			$category_count++;

			$datatable_value.=("<tr>");

				//Category Name
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->category_name;
				$datatable_value.=("</td>");

				//Quantity
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->quantity;

                    ////ADDE IN VER4.0
                    /// TOTAL ROWS
                    $sales_qty+=$items->quantity;
				$datatable_value.=("</td>");

				//Amount
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);

                    ////ADDED IN VER4.0
                    /// TOTAL ROWS
                    $total_amnt+=$items->total_amount;
				$datatable_value.=("</td>");

			$datatable_value.=("</tr>");
		}

		////ADDED IN VER4.0
		/// TOTAL ROW
		$table_name_total= $table_name;
		$this->table_cols_total = $this->table_columns_total( $table_name_total );
		$datatable_value_total='';

		$datatable_value_total.=("<tr>");
		$datatable_value_total.="<td>$category_count</td>";
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

                <?php
					$permission_value=$this->get_form_element_value_permission('it_customy_taxonomies');

					$tax_items=array();
					$json_taxs='';
					$post_name='product';
					$options='';
					$custom_tax_cols='';
					//$all_tax=get_object_taxonomies( $post_name );
					$all_tax=$this->fetch_product_taxonomies( $post_name );
					$current_value=array();
					if(is_array($all_tax) && count($all_tax)>0){
						//FETCH TAXONOMY
						$i=1;
						foreach ( $all_tax as $tax ) {

							$selected='';
							//CHECK IF IS IN PERMISSION
							if(is_array($permission_value) && !in_array($tax,$permission_value))
								continue;

							$tax_status=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_default_search_'.$tax);
							if($tax_status=='on' ||  $permission_value!=''){


								if(!$this->get_form_element_permission('it_customy_taxonomies') &&  $permission_value!='')
									$selected="selected";

								$taxonomy=get_taxonomy($tax);
								$values=$tax;
								$label=$taxonomy->label;

								$options.="<option $selected value='$values'>$label</option>";

								$args = array(
                                    'taxonomy' => $tax,    
									'orderby'                  => 'name',
                                    'order'                    => 'ASC',
                                    'hide_empty'               => 0,
                                    'hierarchical'             => 1,
                                    'exclude'                  => '',
                                    'include'                  => '',
                                    'child_of'          		 => 0,
                                    'number'                   => '',
                                    'pad_counts'               => false

                                );

								$items=array();
                                $categories = get_terms($args);
								$i=0;
                                foreach ($categories as $category) {
                                    $items[$i]['id']=$category->term_id;
									$items[$i]['label']=$category->name;
									$i++;
                                }
                                $tax_items[$tax]=$items;
							}
						}

						$json_taxs = wp_json_encode($tax_items);
					}

					//echo ($json_taxs);

				$col_style='';
				$permission_value=$this->get_form_element_value_permission('it_customy_taxonomies');
				if($this->get_form_element_permission('it_customy_taxonomies') ||  $permission_value!=''){

					if(!$this->get_form_element_permission('it_customy_taxonomies') &&  $permission_value!='')
						$col_style='display:none';

					if($options!=''){
				?>

                <div class="col-md-6" style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('Custom Taxonomy','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-calendar"></i></span>

                        <select name="it_customy_taxonomies" id="it_customy_taxonomies">
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
                      	<script type="text/javascript">
							"use strict";
							jQuery( document ).ready(function( $ ) {

								var taxs='';
								taxs=<?php echo esc_js($json_taxs)?>;

								$("#it_customy_taxonomies").change(function(){

									var datas='';
									var option_data = Array();
									datas=taxs[$(this).val()];
									var options = '<option value="-1">Select All</option>';
									var i = 1;
									$.each(datas, function(key,val){

										options += '<option value="' + val.id + '">' + val.label + '</option>';
										option_data[val.id] = val.label;
										i++;
									});

									//$("#it_adr_product").html(options);
									$('#it_adr_customy_taxonomy_id').empty(); //remove all child nodes
									$("#it_adr_customy_taxonomy_id").html(options);
									$('#it_adr_customy_taxonomy_id').trigger("chosen:updated");
								});

							});
                        </script>
                </div>

                <!--<div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Items','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-tags"></i></span>

                    <select name="it_customy_taxonomy_id[]" id="it_adr_customy_taxonomy_id" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">

                    </select>
            	</div>-->

				<?php }
                }
                ?>
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
