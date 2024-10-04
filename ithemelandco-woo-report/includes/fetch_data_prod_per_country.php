<?php

	if($file_used=="sql_table")
	{

		//GET POSTED PARAMETERS
		$start				= 0;
		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$date_format = $this->it_date_format($it_from_date);


//		$it_from_date=substr($it_from_date,0,strlen($it_from_date)-3);
//		$it_to_date=substr($it_to_date,0,strlen($it_to_date)-3);

		$it_product_id			= $this->it_get_woo_requests('it_product_id',"-1",true);
		$category_id 		= $this->it_get_woo_requests('it_category_id','-1',true);
		$it_cat_prod_id_string = $this->it_get_woo_pli_category($category_id,$it_product_id);

		////ADDED IN VER4.0
        //BRANDS ADDONS
		$brand_id 		= $this->it_get_woo_requests('it_brand_id','-1',true);
		$it_brand_prod_id_string = $this->it_get_woo_pli_category($brand_id,$it_product_id);

		$it_sort_by 			= $this->it_get_woo_requests('sort_by','item_name',true);
		$it_order_by 			= $this->it_get_woo_requests('order_by','ASC',true);

		$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		//$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		$it_country_code		= $this->it_get_woo_requests('it_countries_code','-1',true);

		/*if($it_country_code != NULL  && $it_country_code != '-1')
		{
			$it_country_code = str_replace(",", "','",$it_country_code);
		}*/
		$state_code="-1";


		///////////HIDDEN FIELDS////////////
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_publish_order='no';

		/////////////////////////
		//APPLY PERMISSION TERMS
		$key=$this->it_get_woo_requests('table_names','',true);

		$category_id=$this->it_get_form_element_permission('it_category_id',$category_id,$key);

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id=$this->it_get_form_element_permission('it_brand_id',$brand_id,$key);

		$it_product_id=$this->it_get_form_element_permission('it_product_id',$it_product_id,$key);

		$it_country_code=$this->it_get_form_element_permission('it_countries_code',$it_country_code,$key);

		if($it_country_code != NULL  && $it_country_code != '-1')
			$it_country_code  		= "'".str_replace(",","','",$it_country_code)."'";

		$it_order_status=$this->it_get_form_element_permission('it_orders_status',$it_order_status,$key);

		if($it_order_status != NULL  && $it_order_status != '-1')
			$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";

		///////////////////////////

		$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
		//////////////////////

		/////////CUSTOM TAXONOMY//////////
		$key=$this->it_get_woo_requests('table_names','',true);
		$visible_custom_taxonomy=array();
		$post_name='product';

		$all_tax_cols=$all_tax_joins=$all_tax_conditions='';
		$custom_tax_cols=array();
		$all_tax=$this->fetch_product_taxonomies( $post_name );
		$current_value=array();
		if(defined("__IT_TAX_FIELD_ADD_ON__") && is_array($all_tax) && count($all_tax)>0){
			//FETCH TAXONOMY
			$i=10;
			foreach ( $all_tax as $tax ) {
				$tax_status=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_default_search_'.$key.'_'.$tax);
				if($tax_status=='on'){

					$taxonomy=get_taxonomy($tax);
					$values=$tax;
					$label=$taxonomy->label;

					$translate=get_option($key.'_'.$tax."_translate");
					$show_column=get_option($key.'_'.$tax."_column");
					if($translate!='')
					{
						$label=$translate;
					}

					if($show_column=="on")
						$custom_tax_cols[]=array('lable'=>esc_html(sanitize_text_field($label),'it_report_wcreport_textdomain'),'status'=>'show');


					$visible_custom_taxonomy[]=$tax;

					${$tax} 		= $this->it_get_woo_requests('it_custom_taxonomy_in_'.$tax,'-1',true);

					//echo(${$tax});

					/////////////////////////
					//APPLY PERMISSION TERMS
					$permission_value=$this->get_form_element_value_permission($tax,$key);
					$permission_enable=$this->get_form_element_permission($tax,$key);

					if($permission_enable && ${$tax}=='-1' && $permission_value!=1){
						${$tax}=implode(",",$permission_value);
					}
					/////////////////////////

					if(is_array(${$tax})){ 		${$tax}		= implode(",", ${$tax});}

					$lbl_join=$tax."_join";
					$lbl_con=$tax."_condition";

					${$lbl_join} ='';
					${$lbl_con} = '';

					if(${$tax}  && ${$tax} != "-1") {
						${$lbl_join} = "
							LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships$i 	ON it_term_relationships$i.object_id		=	it_woocommerce_order_itemmeta_product.meta_value
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy$i 		ON term_taxonomy$i.term_taxonomy_id	=	it_term_relationships$i.term_taxonomy_id
					";
					}

					$all_tax_joins.=" ".${$lbl_join}." ";

					if(${$tax}  && ${$tax} != "-1")
						${$lbl_con} = "AND term_taxonomy$i.taxonomy LIKE('$tax') AND term_taxonomy$i.term_id IN (".${$tax} .")";

					$all_tax_conditions.=" ".${$lbl_con}." ";

					$i++;
				}
			}
		}
		//////////////////

		//REGION CODE
		$it_region_code_join='';
		$it_region_code_condition='';

		//CATEGORY ID
		$category_id_join='';
		$category_id_condition='';

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id_join='';
		$brand_id_condition='';
		$it_brand_prod_id_string_condition="";

		//Category Product ID
		$it_cat_prod_id_string_condition="";

		//STATUS ID
		$it_id_order_status_join='';

		//DATE
		$it_from_date_condition='';

		//ORDER STATUS ID
		$it_id_order_status_condition='';

		//PRODUCT ID
		$it_product_id_condition='';

		//COUNTRY
		$it_country_code_condition='';

		//STATE
		$state_code_condition='';

		//ORDER
		$it_order_status_condition='';

		//HIDE ORDER
		$it_hide_os_condition='';

		$sql_columns = "
			it_woocommerce_order_itemmeta_product.meta_value 			as ids
			,it_woocommerce_order_items.order_item_name 				as product_name
			,it_woocommerce_order_itemmeta_product.meta_value 			as product_id
			,it_woocommerce_order_items.order_item_id 					as order_item_id
			,it_woocommerce_order_items.order_item_name 				as item_name


			,SUM(it_woocommerce_order_itemmeta_product_total.meta_value) 	as total
			,SUM(it_woocommerce_order_itemmeta_product_qty.meta_value) 	as quantity";


		$sql_columns .= "
		,billing_country.meta_value as month_key
		,billing_country.meta_value as month_number ";

		$sql_columns .= "
		,billing_country.meta_value as billing_country";

		$sql_joins  = " {$wpdb->prefix}woocommerce_order_items 			as it_woocommerce_order_items
			LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product 			ON it_woocommerce_order_itemmeta_product.order_item_id=it_woocommerce_order_items.order_item_id
			LEFT JOIN  {$wpdb->prefix}posts 						as shop_order 									ON shop_order.id								=	it_woocommerce_order_items.order_id

			LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_total 	ON it_woocommerce_order_itemmeta_product_total.order_item_id=it_woocommerce_order_items.order_item_id
			LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta 	as it_woocommerce_order_itemmeta_product_qty		ON it_woocommerce_order_itemmeta_product_qty.order_item_id		=	it_woocommerce_order_items.order_item_id
			LEFT JOIN  {$wpdb->prefix}postmeta 						as billing_country 								ON billing_country.post_id									=	shop_order.ID";


		if($category_id != NULL  && $category_id != "-1"){
			$category_id_join = "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_woocommerce_order_itemmeta_product.meta_value
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id
			LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 				ON it_terms.term_id					=	term_taxonomy.term_id";
		}

		////ADDED IN VER4.0
		//BRANDS ADDONS
		if($brand_id != NULL  && $brand_id != "-1"){
			$brand_id_join = "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_brand 	ON it_term_relationships_brand.object_id		=	it_woocommerce_order_itemmeta_product.meta_value
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_brand 		ON term_taxonomy_brand.term_taxonomy_id	=	it_term_relationships_brand.term_taxonomy_id
			LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_brand 				ON it_terms_brand.term_id					=	term_taxonomy_brand.term_id";
		}


		if($it_id_order_status != NULL  && $it_id_order_status != '-1'){
			$it_id_order_status_join = "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships2 	ON it_term_relationships2.object_id	=	it_woocommerce_order_items.order_id
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
			LEFT JOIN  {$wpdb->prefix}terms 				as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
		}



			$sql_condition  = "
			it_woocommerce_order_itemmeta_product.meta_key		=	'_product_id'
			AND it_woocommerce_order_items.order_item_type		=	'line_item'
			AND shop_order.post_type						=	'shop_order'

			AND billing_country.meta_key							=	'_billing_country'
			AND it_woocommerce_order_itemmeta_product_total.meta_key		='_line_total'
			AND it_woocommerce_order_itemmeta_product_qty.meta_key			=	'_qty'";

		if ($it_from_date != NULL &&  $it_to_date !=NULL)
    		$it_from_date_condition = " AND DATE_FORMAT(shop_order.post_date, '%Y-%m-%d') BETWEEN ('" . $it_from_date . "') and ('" . $it_to_date . "')";



		if($category_id  != NULL && $category_id != "-1"){

			$category_id_condition = "
			AND term_taxonomy.taxonomy LIKE('product_cat')
			AND it_terms.term_id IN (".$category_id .")";
		}

		////ADDED IN VER4.0
        //BRANDS ADDONS
		if($brand_id  != NULL && $brand_id != "-1"){

			$brand_id_condition = "
			AND term_taxonomy_brand.taxonomy LIKE('".__IT_BRAND_SLUG__."')
			AND it_terms_brand.term_id IN (".$brand_id .")";
		}

		if($it_cat_prod_id_string  && $it_cat_prod_id_string != "-1")
			$it_cat_prod_id_string_condition = " AND it_woocommerce_order_itemmeta_product.meta_value IN (".$it_cat_prod_id_string .")";

		////ADDED IN VER4.0
		//BRANDS ADDONS
		if($it_brand_prod_id_string  && $it_brand_prod_id_string != "-1")
			$it_brand_prod_id_string_condition = " AND it_woocommerce_order_itemmeta_product.meta_value IN (".$it_brand_prod_id_string .")";

		if($it_id_order_status != NULL  && $it_id_order_status != '-1'){
			$it_id_order_status_condition .= "
			AND it_term_taxonomy2.taxonomy LIKE('shop_order_status')
			AND terms2.term_id IN (".$it_id_order_status .")";
		}

		if($it_product_id != NULL  && $it_product_id != '-1'){
			$it_product_id_condition  = "
			AND it_woocommerce_order_itemmeta_product.meta_value IN ($it_product_id)";
		}

		if($it_country_code != NULL  && $it_country_code != '-1')
			$it_country_code_condition = "
				AND	billing_country.meta_value	IN ({$it_country_code})";

		if($state_code != NULL  && $state_code != '-1')
			$state_code_condition = "
				AND	billing_state.meta_value	IN ({$state_code})";

		if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
			$it_order_status_condition = " AND shop_order.post_status IN (".$it_order_status.")";
		if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
			$it_hide_os_condition = " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

		$sql_group_by = " group by it_woocommerce_order_itemmeta_product.meta_value";


		$sql_order_by = " ORDER BY {$it_sort_by} {$it_order_by}";

		$sql = "SELECT $sql_columns
				FROM $sql_joins $it_region_code_join $category_id_join $brand_id_join $all_tax_joins
				$it_id_order_status_join
				WHERE $sql_condition $it_region_code_condition $it_from_date_condition
				$category_id_condition $brand_id_condition $all_tax_conditions $it_cat_prod_id_string_condition $it_brand_prod_id_string_condition
				$it_id_order_status_condition $it_product_id_condition $it_country_code_condition
				$state_code_condition $it_order_status_condition $it_hide_os_condition
				$sql_group_by $sql_order_by";
		//echo $sql;

		$array_index=3;
		$this->table_cols =$this->table_columns($table_name);

		///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
		$brands_cols=array();
		if(__IT_BRAND_SLUG__){
			$brands_cols[]=array('lable'=>__IT_BRAND_LABEL__,'status'=>'show');
			array_splice($this->table_cols,$array_index,0,$brands_cols);
			$array_index++;
		}

		if(is_array($custom_tax_cols))
		{
			array_splice($this->table_cols,$array_index,0,$custom_tax_cols);
			$array_index+=count($custom_tax_cols);
		}

		$data_country=array();
		$country_data = $this->it_get_paying_woo_country();


		$country_sel_arr	= '';
		if($it_country_code != NULL  && $it_country_code != '-1')
		{
			$it_country_code = str_replace("','", ",",$it_country_code);
			$country_sel_arr = explode(",",$it_country_code);
		}

		foreach($country_data as $country){
			if($it_country_code=='-1' || is_array($country_sel_arr) && in_array($country -> id,$country_sel_arr))
			{
				$data_country[]=$country -> id;
				$value=array(array('lable'=>$country -> label,'status'=>'currency'));
				array_splice($this->table_cols, $array_index++, 0, $value );
			}
		}

		$value=array(array('lable'=>esc_html__('Total','it_report_wcreport_textdomain'),'status'=>'currency'));
		array_splice($this->table_cols, $array_index, 0, $value );
		$this->data_country=$data_country;

		//echo $sql;

	}elseif($file_used=="data_table"){

		/////////CUSTOM TAXONOMY////////
		$key=$this->it_get_woo_requests('table_names','',true);
		$visible_custom_taxonomy=array();
		$post_name='product';
		$all_tax=$this->fetch_product_taxonomies( $post_name );
		$current_value=array();
		if(is_array($all_tax) && count($all_tax)>0){
			//FETCH TAXONOMY
			foreach ( $all_tax as $tax ) {
				$tax_status=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_default_search_'.$key.'_'.$tax);
				$show_column=get_option($key.'_'.$tax.'_column');
				if($show_column=='on'){
				//if($tax_status=='on'){
					$visible_custom_taxonomy[]=$tax;
				}
			}
		}
		///////////////////////////

		foreach($this->results as $items){		    $index_cols=0;
		//for($i=1; $i<=20 ; $i++){

			$datatable_value.=("<tr>");

				//Product SKU
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->it_get_prod_sku($items->order_item_id, $items->product_id);
				$datatable_value.=("</td>");

				//Product NAME
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->product_name;
				$datatable_value.=("</td>");

				//Categories
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->it_get_cn_product_id($items->product_id,"product_cat");
				$datatable_value.=("</td>");

                ///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
                if(__IT_BRAND_SLUG__){
                    $display_class='';
                   	if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
                    $datatable_value.=("<td style='".$display_class."'>");
                    $datatable_value.= $this->it_get_cn_product_id($items->product_id,__IT_BRAND_SLUG__);
                    $datatable_value.=("</td>");
                }

				if(is_array($visible_custom_taxonomy)){
					foreach((array)$visible_custom_taxonomy as $tax){
						//Category
						$display_class='';
						if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
						$datatable_value.=("<td style='".$display_class."'>");
							$datatable_value.= $this->it_get_cn_product_id($items->product_id,$tax);
						$datatable_value.=("</td>");
					}
				}

				$type = 'total_row';$items_only = true; $id = $items->ids;

				$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
				$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
				$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
				$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";
				//$it_from_date=substr($it_from_date,0,strlen($it_from_date)-3);
				//$it_to_date=substr($it_to_date,0,strlen($it_to_date)-3);


				$params=array(
					"it_from_date"=>$it_from_date,
					"it_to_date"=>$it_to_date,
					"order_status"=>$it_order_status,
					"it_hide_os"=>'"trash"'
				);
				//print_r($arr);
				$items_product=$this->it_get_woo_cp_items($type , $items_only, $id,$params);

				$country_arr=array();
				foreach($items_product as $item_product){
					$country_arr[$item_product->billing_country]['total']=$item_product->total;
					$country_arr[$item_product->billing_country]['qty']=$item_product->quantity;
				}

				$j=2;
				$total=0;
				$qty=0;
				foreach($this->data_country as $country_name){
					$it_table_value=$this->price(0);
					if(isset($country_arr[$country_name]['total'])){
						$it_table_value=$this->price($country_arr[$country_name]['total']) .' #'.$country_arr[$country_name]['qty'];
						$total+=$country_arr[$country_name]['total'];
						$qty+=$country_arr[$country_name]['qty'];
					}


					$display_class='';
					if($this->table_cols[$j++]['status']=='hide') $display_class='display:none';
					$datatable_value.=("<td style='".$display_class."'>");
						$datatable_value.= $it_table_value;
					$datatable_value.=("</td>");
				}


				//Total
				$display_class='';
				if($this->table_cols[$j]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($total) .' #'.$qty;
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

                <?php
					/////////////////
					//CUSTOM TAXONOMY
					$key=isset($_GET['smenu']) ? $_GET['smenu']:$_GET['parent'];
					$args_f=array("page"=>$key);
					echo wp_kses(
						$this->make_custom_taxonomy($args_f),
						$this->allowedposttags()
					);
						
					// echo $this->make_custom_taxonomy($args_f);
				?>

                <?php
					$col_style='';
					$permission_value=$this->get_form_element_value_permission('it_category_id');
					if($this->get_form_element_permission('it_category_id') ||  $permission_value!=''){
						if(!$this->get_form_element_permission('it_category_id') &&  $permission_value!='')
							$col_style='display:none';
				?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('Category','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-tags"></i></span>
					<?php
                        $args = array(
                            'taxonomy' => 'product_cat',
							'orderby'                  => 'name',
                            'order'                    => 'ASC',
                            'hide_empty'               => 1,
                            'hierarchical'             => 0,
                            'exclude'                  => '',
                            'include'                  => '',
                            'child_of'          		 => 0,
                            'number'                   => '',
                            'pad_counts'               => false

                        );

                        //$categories = get_categories($args);
                        $current_category=$this->it_get_woo_requests_links('it_category_id','',true);

                        $categories = get_terms($args);
                        $option='';
                        foreach ($categories as $category) {
							$selected='';
							//CHECK IF IS IN PERMISSION
							if(is_array($permission_value) && !in_array($category->term_id,$permission_value))
								continue;
							/*if(!$this->get_form_element_permission('it_category_id') &&  $permission_value!='')
								$selected="selected";

                            if($current_category==$category->term_id)
                                $selected="selected";*/

                            $option .= '<option value="'.$category->term_id.'" '.$selected.'>';
                            $option .= $category->name;
                            $option .= ' ('.$category->count.')';
                            $option .= '</option>';
                        }
                    ?>
                    <select name="it_category_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        	if($this->get_form_element_permission('it_category_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
							{
						?>
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
                        <?php
							}
						?>
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

                <?php
					}

                    ////ADDED IN VER4.0
                    //BRANDS ADDONS
                    $col_style='';
                    $permission_value=$this->get_form_element_value_permission('it_brand_id');
                    if(__IT_BRAND_SLUG__ && ($this->get_form_element_permission('it_brand_id') ||  $permission_value!='')){
                        if(!$this->get_form_element_permission('it_brand_id') &&  $permission_value!='')
                            $col_style='display:none';
                        ?>

                        <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                            <div class="awr-form-title">
                                <?php Brand?>
                            </div>
                            <span class="awr-form-icon"><i class="fa fa-tags"></i></span>
                            <?php
                            $args = array(
                            	'taxonomy' => __IT_BRAND_SLUG__,    
								'orderby'                  => 'name',
                                'order'                    => 'ASC',
                                'hide_empty'               => 1,
                                'hierarchical'             => 0,
                                'exclude'                  => '',
                                'include'                  => '',
                                'child_of'          		 => 0,
                                'number'                   => '',
                                'pad_counts'               => false

                            );

                            //$categories = get_categories($args);
                            $current_category=$this->it_get_woo_requests_links('it_brand_id','',true);

                            $categories = get_terms($args);
                            $option='';
                            foreach ($categories as $category) {
                                $selected='';
                                //CHECK IF IS IN PERMISSION
                                if(is_array($permission_value) && !in_array($category->term_id,$permission_value))
                                    continue;
                                /*if(!$this->get_form_element_permission('it_category_id') &&  $permission_value!='')
                                    $selected="selected";

                                if($current_category==$category->term_id)
                                    $selected="selected";*/

                                $option .= '<option value="'.$category->term_id.'" '.$selected.'>';
                                $option .= $category->name;
                                $option .= ' ('.$category->count.')';
                                $option .= '</option>';
                            }
                            ?>
                            <select name="it_brand_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                                <?php
                                if($this->get_form_element_permission('it_brand_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
                                {
                                    ?>
                                    <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
                                    <?php
                                }
                                ?>
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
                <?php
                    }

					$col_style='';
					$permission_value=$this->get_form_element_value_permission('it_orders_status');
					if($this->get_form_element_permission('it_orders_status') ||  $permission_value!=''){

						if(!$this->get_form_element_permission('it_orders_status') &&  $permission_value!='')
							$col_style='display:none';
				?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
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

							$selected="";
							//CHECK IF IS IN PERMISSION
							if(is_array($permission_value) && !in_array($key,$permission_value))
								continue;

							/*if(!$this->get_form_element_permission('it_parent_brand_id') &&  $permission_value!='')
								$selected="selected";*/

	                        ////ADDED IN VER4.0
	                        /// APPLY DEFAULT STATUS AT FIRST
	                        if(is_array($shop_status_selected) && in_array($key,$shop_status_selected))
		                        $selected="selected";

	                        $option.="<option value='".$key."' $selected >".$value."</option>";
                        }
                    ?>

                    <select name="it_orders_status[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        	if($this->get_form_element_permission('it_parent_brand_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
							{
						?>
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
                        <?php
							}
						?>
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

                <?php
					}
					$col_style='';
					$permission_value=$this->get_form_element_value_permission('it_product_id');
					if($this->get_form_element_permission('it_product_id') ||  $permission_value!=''){

						if(!$this->get_form_element_permission('it_product_id') &&  $permission_value!='')
							$col_style='display:none';
				?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
                        <?php esc_html_e('Product','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-cog"></i></span>
					<?php
                        $products=$this->it_get_product_woo_data('all');
                        $option='';
                        $current_product=$this->it_get_woo_requests_links('it_product_id','',true);
                        //echo $current_product;

                        foreach($products as $product){
							$selected='';
							//CHECK IF IS IN PERMISSION
							if(is_array($permission_value) && !in_array($product -> id,$permission_value))
								continue;
							/*if(!$this->get_form_element_permission('it_product_id') &&  $permission_value!='')
								$selected="selected";

                            if($current_product==$product->id)
                                $selected="selected";*/
                            $option.="<option $selected value='".$product -> id."' >".$product -> label." </option>";
                        }


                    ?>
                    <select name="it_product_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                        <?php
                        	if($this->get_form_element_permission('it_product_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
							{
						?>
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
                        <?php
							}
						?>
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

                <?php
					}
					$col_style='';
					$permission_value=$this->get_form_element_value_permission('it_countries_code');
					if($this->get_form_element_permission('it_countries_code') ||  $permission_value!=''){
						if(!$this->get_form_element_permission('it_countries_code') &&  $permission_value!='')
							$col_style='display:none';
				?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                	<div class="awr-form-title">
						<?php esc_html_e('Country','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-globe"></i></span>
					<?php
                        $country_data = $this->it_get_paying_woo_country();

                        $option='';
                        //$current_product=$this->it_get_woo_requests_links('it_product_id','',true);
                        //echo $current_product;

                        foreach($country_data as $country){
							 $selected='';
							//CHECK IF IS IN PERMISSION
							if(is_array($permission_value) && !in_array($country -> id,$permission_value))
								continue;
							/*if(!$this->get_form_element_permission('it_countries_code') &&  $permission_value!='')
								$selected="selected";*/

                            /*if($current_product==$country->id)
                                $selected="selected";*/
                            $option.="<option $selected value='".$country -> id."' >".$country -> label." </option>";
                        }
                    ?>

                    <select name="it_countries_code[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
                       <?php
                        	if($this->get_form_element_permission('it_countries_code') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
							{
						?>
                        <option value="-1"><?php esc_html_e('Select All','it_report_wcreport_textdomain');?></option>
                        <?php
							}
						?>
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
                <?php
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
