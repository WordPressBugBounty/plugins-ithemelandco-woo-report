<?php

	if($file_used=="sql_table")
	{

		$it_product_id		= $this->it_get_woo_requests('it_product_id','-1',true);
		$category_id	= $this->it_get_woo_requests('it_category_id','-1',true);
		$ProductTypeID	= $this->it_get_woo_requests('ProductTypeID',NULL,true);
		$it_product_subtype= $this->it_get_woo_requests('it_sub_product_type','-1',true);
		$it_sku_number= $this->it_get_woo_requests('it_sku_no','-1',true);
		$it_product_sku= $this->it_get_woo_requests('it_sku_products','-1',true);
		$it_manage_stock= $this->it_get_woo_requests('it_stock_manage','-1',true);
		$it_stock_status= $this->it_get_woo_requests('it_status_of_stock','-1',true);
		$it_product_stock = $this->it_get_woo_requests('it_stock_product','-1',true);
		$it_txt_min_stock = $this->it_get_woo_requests('it_stock_min','-1',true);
		$it_txt_max_stock  = $this->it_get_woo_requests('it_stock_max','-1',true);

		if(!is_numeric($it_txt_min_stock)) $it_txt_min_stock=0;
		if(!is_numeric($it_txt_max_stock)) $it_txt_max_stock=0;

		$it_zero_stock = $this->it_get_woo_requests('it_stock_zero','no',true);
		$it_product_type= $this->it_get_woo_requests('it_products_type','-1',true);
		$it_zero_sold = $this->it_get_woo_requests('zero_sold','-1',true);
		$it_product_name = $this->it_get_woo_requests('it_name_of_product','-1',true);
		$it_basic_column = $this->it_get_woo_requests('it_general_cols','no',true);
		$it_zero_stock = $this->it_get_woo_requests('it_stock_zero','no',true);


		$it_product_sku		= $this->it_get_woo_sm_requests('it_sku_products',$it_product_sku, "-1");

		$it_manage_stock		= $this->it_get_woo_sm_requests('it_stock_manage',$it_manage_stock, "-1");

		$it_stock_status		= $this->it_get_woo_sm_requests('it_status_of_stock',$it_stock_status, "-1");

		//CUSTOM WORK - 15862
		$it_custom_sku		= $this->it_get_woo_requests('it_custom_sku','-1',true);

		$key=$this->it_get_woo_requests('table_names','',true);
		$visible_custom_taxonomy=array();
		$post_name='product';

		$all_tax_joins=$all_tax_conditions='';
		$custom_tax_cols=array();
		$all_tax=$this->fetch_product_taxonomies( $post_name );
		$current_value=array();
		if(is_array($all_tax) && count($all_tax)>0){
			//FETCH TAXONOMY
			$i=1;
			foreach ( $all_tax as $tax ) {

				$tax_status=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_default_search_'.$key.'_'.$tax);
				if($tax_status=='on'){


					$taxonomy=get_taxonomy($tax);
					$values=$tax;
					$label=$taxonomy->label;

					$show_column=get_option($key.'_'.$tax."_column");
					$translate=get_option($key.'_'.$tax."_translate");
					if($translate!='')
					{
						$label=$translate;
					}

					if($show_column=="on")
						$custom_tax_cols[]=array('lable'=>esc_htmlesc_html(sanitize_text_field($label),'it_report_wcreport_textdomain'),'status'=>'show');


					$visible_custom_taxonomy[]=$tax;

					${$tax} 		= $this->it_get_woo_requests('it_custom_taxonomy_in_'.$tax,'-1',true);

					/////////////////////////
					//APPLY PERMISSION TERMS
					$permission_value=$this->get_form_element_value_permission($tax,$key);
					$permission_enable=$this->get_form_element_permission($tax,$key);

					if($permission_enable && ${$tax}=='-1' && $permission_value!=1){
						${$tax}=implode(",",$permission_value);
					}
					/////////////////////////

					//echo(${$tax});

					if(is_array(${$tax})){ 		${$tax}		= implode(",", ${$tax});}

					$lbl_join=$tax."_join";
					$lbl_con=$tax."_condition";

					${$lbl_join} ='';
					${$lbl_con} = '';

					if(${$tax}  && ${$tax} != "-1") {
						${$lbl_join} = "
							LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships$i 			ON it_term_relationships$i.object_id=it_posts.ID
							LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy$i				ON term_taxonomy$i.term_taxonomy_id	=	it_term_relationships$i.term_taxonomy_id
							";
							//LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 						ON it_terms.term_id					=	term_taxonomy.term_id";




					}

					$all_tax_joins.=" ".${$lbl_join}." ";

					if(${$tax}  && ${$tax} != "-1")
						${$lbl_con} = " AND term_taxonomy$i.taxonomy LIKE('$tax') AND term_taxonomy$i.term_id IN (".${$tax} .")";

					$all_tax_conditions.=" ".${$lbl_con}." ";

					$i++;
				}
			}
		}



		//GET POSTED PARAMETERS


		$it_product_sku 		= $this->it_get_woo_requests('it_sku_products','-1',true);
		if($it_product_sku != NULL  && $it_product_sku != '-1'){
			$it_product_sku  		= "'".str_replace(",","','",$it_product_sku)."'";
		}


		$page				= $this->it_get_woo_requests('page',NULL);

		$report_name 		= apply_filters($page.'_default_report_name', 'product_page');
		$optionsid			= "per_row_variation_page";

		$report_name 		= $this->it_get_woo_requests('report_name',$report_name,true);
		$admin_page			= $this->it_get_woo_requests('admin_page',$page,true);
		$category_id		= $this->it_get_woo_requests('it_category_id','-1',true);
		$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
		$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
		$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_product_id			= $this->it_get_woo_requests('it_product_id','-1',true);

		//GET POSTED PARAMETERS
		$start				= 0;
		$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
		$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
		$it_product_id			= $this->it_get_woo_requests('it_product_id',"-1",true);
		$category_id 		= $this->it_get_woo_requests('it_category_id','-1',true);

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id 		= $this->it_get_woo_requests('it_brand_id','-1',true);

		///////////HIDDEN FIELDS////////////
		$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
		$it_publish_order='no';

		$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);

		/////////////////////////
		//APPLY PERMISSION TERMS
		$key=$this->it_get_woo_requests('table_names','',true);

		$category_id=$this->it_get_form_element_permission('it_category_id',$category_id,$key);

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id=$this->it_get_form_element_permission('it_brand_id',$brand_id,$key);

		$it_product_id=$this->it_get_form_element_permission('it_product_id',$it_product_id,$key);

		///////////////////////////


		//CUSTOM WORK - 15862
		$custom_sku = '';
		$custom_sku_join = '';
		$custom_sku_condition = '';

		//PRODUCT SUBTYPE
		$it_product_subtype_join='';
		$it_product_subtype_conditoin_1='';
		$it_product_subtype_conditoin_2='';


		//SKU NUMBER
		$it_sku_number_join='';
		$it_sku_number_conditoin='';

		//PRODUCT STOCK
		$it_product_stock_join ='';
		$it_product_stock_conditoin='';
		$product_other_conditoin='';

		//CATEGORY
		$category_id_join ='';
		$category_id_conditoin ='';

		////ADDED IN VER4.0
		//BRANDS ADDONS
		$brand_id_join ='';
		$brand_id_conditoin ='';

		//PRODUCT TYPE
		$it_product_type_join='';
		$it_product_type_conditoin='';

		//ZERO SOLD
		$it_zero_sold_join='';
		$it_zero_stock_conditoin='';
		$it_zero_sold_conditoin='';

		//STOCK STATUS
		$it_stock_status_join='';
		$it_stock_status_conditoin='';

		//MANAGE STOCK
		$it_manage_stock_join ='';
		$it_manage_stock_conditoin='';

		//PRODUCT NAME
		$it_product_name_conditoin='';

		//PRODUCT ID
		$it_product_id_conditoin='';
		$it_product_sku_conditoin='';


		//MAX & MIN
		$it_txt_min_stock_conditoin ='';
		$it_txt_max_stock_conditoin='';


		$sql_columns = "
		it_posts.post_title as product_name
		,it_posts.post_date as product_date
		,it_posts.post_modified as modified_date
		,it_posts.ID as product_id";

		$sql_joins = "{$wpdb->prefix}posts as it_posts ";

		if($it_product_subtype =="virtual")
			$it_product_subtype_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_virtual 			ON it_virtual.post_id			=it_posts.ID";

		if($it_product_subtype=="downloadable")
			$it_product_subtype_join = " LEFT JOIN  {$wpdb->prefix}postmeta as it_downloadable		ON it_downloadable.post_id		=it_posts.ID";

		if($it_sku_number || ($it_product_sku and $it_product_sku != '-1'))
			$it_sku_number_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_sku 				ON it_sku.post_id				=it_posts.ID";

		if($it_product_stock || $it_txt_min_stock || $it_txt_max_stock || $it_zero_stock)
			$it_product_stock_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_stock 				ON it_stock.post_id			=it_posts.ID";

		if($category_id and $category_id != "-1"){
			$category_id_join= " LEFT JOIN  {$wpdb->prefix}term_relationships as it_term_relationships ON it_term_relationships.object_id=it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy as term_taxonomy ON term_taxonomy.term_taxonomy_id=it_term_relationships.term_taxonomy_id
			LEFT JOIN  {$wpdb->prefix}terms as it_terms ON it_terms.term_id=term_taxonomy.term_id";
		}

		////ADDED IN VER4.0
		//BRANDS ADDONS
		if($brand_id and $brand_id != "-1"){
			$brand_id_join= " LEFT JOIN  {$wpdb->prefix}term_relationships as it_term_relationships_brand ON it_term_relationships_brand.object_id=it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy as term_taxonomy_brand ON term_taxonomy_brand.term_taxonomy_id=it_term_relationships_brand.term_taxonomy_id
			LEFT JOIN  {$wpdb->prefix}terms as it_terms_brand ON it_terms_brand.term_id=term_taxonomy_brand.term_id";
		}

		if($it_product_type and $it_product_type != "-1"){
			$it_product_type_join= "
					LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_product_type 	ON it_term_relationships_product_type.object_id		=	it_posts.ID
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as it_term_taxonomy_product_type 		ON it_term_taxonomy_product_type.term_taxonomy_id		=	it_term_relationships_product_type.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_product_type 				ON it_terms_product_type.term_id						=	it_term_taxonomy_product_type.term_id";
		}


		if($it_zero_sold=="yes")
			$it_zero_sold_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_total_sales 			ON it_total_sales.post_id			=it_posts.ID";

		if($it_stock_status and $it_stock_status != '-1')
			$it_stock_status_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_stock_status 			ON it_stock_status.post_id			=it_posts.ID";

		if($it_manage_stock and $it_manage_stock != '-1')
			$it_manage_stock_join= " LEFT JOIN  {$wpdb->prefix}postmeta as it_manage_stock 			ON it_manage_stock.post_id			=it_posts.ID";

		//CUSTOM WORK - 15862
		if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
			$custom_sku_join = " LEFT JOIN {$wpdb->prefix}postmeta as it_custom_sku ON it_custom_sku.post_id = it_posts.ID";
		}
		$sql_joins .= $custom_sku_join;

		$sql_condition= "  it_posts.post_type='product' AND it_posts.post_status = 'publish'";

		if($it_product_stock || $it_txt_min_stock || $it_txt_max_stock || $it_zero_stock)
			$product_other_conditoin= " AND it_stock.meta_key ='_stock'";

		if($it_sku_number || ($it_product_sku and $it_product_sku != '-1'))
			$it_sku_number_conditoin= " AND it_sku.meta_key ='_sku'";

		if($it_product_subtype=="downloadable")
			$it_product_subtype_conditoin_1= " AND it_downloadable.meta_key ='_downloadable'";

		if($it_product_subtype=="virtual")
			$it_product_subtype_conditoin_1= " AND it_virtual.meta_key ='_virtual'";



		if($it_product_name)
			$it_product_name_conditoin= " AND it_posts.post_title like '%{$it_product_name}%'";

		if($it_product_id and $it_product_id >0)
			$it_product_id_conditoin= " AND it_posts.ID IN ({$it_product_id})";

		if($it_product_stock)
			$it_product_stock_conditoin= " AND it_stock.meta_value IN ({$it_product_stock})";


		if($it_txt_min_stock)
			$it_txt_min_stock_conditoin= " AND it_stock.meta_value >= {$it_txt_min_stock}";

		if($it_txt_max_stock)
			$it_txt_max_stock_conditoin= " AND it_stock.meta_value <= {$it_txt_max_stock}";

		if($it_product_subtype=="downloadable")
			$it_product_subtype_conditoin_2= " AND it_downloadable.meta_value = 'yes'";

		if($it_product_subtype=="virtual")
			$it_product_subtype_conditoin_2= " AND it_virtual.meta_value = 'yes'";

		if($category_id and $category_id != "-1")
			$category_id_conditoin= " AND it_terms.term_id IN ({$category_id})";

		////ADDED IN VER4.0
		//BRANDS ADDONS
		if($brand_id  && $brand_id != "-1")
			$brand_id_conditoin= "
                AND term_taxonomy_brand.taxonomy LIKE('".__IT_BRAND_SLUG__."')
                AND it_terms_brand.term_id IN (".$brand_id .")";

		if($it_product_type and $it_product_type != "-1")
			$it_product_type_conditoin= " AND it_terms_product_type.name IN ('{$it_product_type}')";

		if($it_product_sku and $it_product_sku != '-1'){
			if(strlen($it_sku_number) > 0) {
				$it_product_sku_conditoin= " AND (it_sku.meta_value like '%{$it_sku_number}%' OR  it_sku.meta_value IN (".$it_product_sku.") )";
			}else{
				$it_product_sku_conditoin= " AND it_sku.meta_value IN (".$it_product_sku.")";
				//$sql .= " AND it_sku.meta_value = ".$it_product_sku;
			}
		}else{
			if(strlen($it_sku_number) > 0)
				$it_product_sku_conditoin= " AND it_sku.meta_value like '%{$it_sku_number}%'";
		}

		if($it_zero_stock == "no")
			$it_zero_stock_conditoin= " AND (it_stock.meta_value > 0 AND LENGTH(it_stock.meta_value) > 0)";

		if($it_zero_sold=="yes")
			$it_zero_sold_conditoin= " AND it_total_sales.meta_key ='total_sales' AND (it_total_sales.meta_value <= 0 OR LENGTH(it_total_sales.meta_value) <= 0)";

		if($it_stock_status and $it_stock_status != '-1')
		$it_stock_status_conditoin= " AND it_stock_status.meta_key ='_stock_status' AND it_stock_status.meta_value IN ({$it_stock_status})";

		if($it_manage_stock and $it_manage_stock != '-1')
		$it_manage_stock_conditoin= " AND it_manage_stock.meta_key ='_manage_stock' AND it_manage_stock.meta_value IN ({$it_manage_stock})";

		//CUSTOM WORK - 15862
		if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
			if($it_custom_sku  && $it_custom_sku != "-1") {
				$custom_sku_condition .= " AND it_custom_sku.meta_key = 'jk_sku' AND it_custom_sku.meta_value LIKE '%$it_custom_sku%'";
			}
		}
		$sql_condition .= $custom_sku_condition;

		$current_lng_cond='';
		$current_lng_join='';



		$sql="SELECT $sql_columns
			FROM $current_lng_join $sql_joins $it_product_subtype_join $it_sku_number_join $it_product_stock_join
			$category_id_join $brand_id_join $all_tax_joins $it_product_type_join $it_zero_sold_join $it_stock_status_join
			$it_manage_stock_join
			WHERE $sql_condition $product_other_conditoin $it_sku_number_conditoin
			$it_product_subtype_conditoin_1 $it_product_name_conditoin $it_product_id_conditoin
			$it_product_stock_conditoin $it_txt_min_stock_conditoin $it_txt_max_stock_conditoin
			$it_product_subtype_conditoin_2 $category_id_conditoin $brand_id_conditoin $all_tax_conditions
			$it_product_type_conditoin $it_product_sku_conditoin $it_zero_stock_conditoin
			$it_zero_sold_conditoin $it_stock_status_conditoin $it_manage_stock_conditoin $current_lng_cond GROUP BY product_id";

		//echo $sql;

		///////////////////
		//EXTRA COLUMNS
		$this->table_cols =$this->table_columns($table_name);

		$variation_cols_arr=array();
		if($it_basic_column=='yes'){


			$columns=array(
				array('lable'=>esc_html__('SKU','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Stock','it_report_wcreport_textdomain'),'status'=>'show'),
			);

			$array_index=3;

			///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
			$brands_cols=array();
            if(__IT_BRAND_SLUG__){
				$brands_cols[]=array('lable'=>__IT_BRAND_LABEL__,'status'=>'show');
				array_splice($columns,$array_index,0,$brands_cols);
				$array_index++;
			}

			if(is_array($custom_tax_cols))
				array_splice($columns,$array_index,0,$custom_tax_cols);

			//CUSTOM WORK - 15862
			if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
				$custom_sku_cols[]=array('lable'=>'Custom SKU','status'=>'show');
				array_splice($columns,1,0,$custom_sku_cols);
			}

		}else if($it_basic_column!='yes'){

			$columns=array(
				array('lable'=>esc_html__('SKU','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Product Name','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Product Type','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Category','it_report_wcreport_textdomain'),'status'=>'show'),

				array('lable'=>esc_html__('Created Date','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Modified Date','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Stock','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Regular Price','it_report_wcreport_textdomain'),'status'=>'currency'),
				array('lable'=>esc_html__('Sale Price','it_report_wcreport_textdomain'),'status'=>'currency'),

				array('lable'=>esc_html__('Downloadable','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Virtual','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Manage Stock','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Backorders','it_report_wcreport_textdomain'),'status'=>'show'),
				array('lable'=>esc_html__('Stock Status','it_report_wcreport_textdomain'),'status'=>'show')
			);



			$array_index=4;
			///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
			$brands_cols=array();
            if(__IT_BRAND_SLUG__){
				$brands_cols[]=array('lable'=>__IT_BRAND_LABEL__,'status'=>'show');
				array_splice($columns,$array_index,0,$brands_cols);
				$array_index++;
			}

			if(is_array($custom_tax_cols))
				array_splice($columns,$array_index,0,$custom_tax_cols);

			//CUSTOM WORK - 15862
			if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
				$custom_sku_cols[]=array('lable'=>'Custom SKU','status'=>'show');
				array_splice($columns,1,0,$custom_sku_cols);
			}

		}

		$this->table_cols = $columns;


	}elseif($file_used=="data_table"){


		/////////////CUSTOM TAXONOMY AND FIELDS////////////
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
					$visible_custom_taxonomy[]=$tax;
				}
			}
		}
		//////////////////////////////////////////////////////

		foreach($this->results as $items){
		    $index_cols=0;
		//for($i=1; $i<=20 ; $i++){
			$datatable_value.=("<tr>");

			$it_basic_column = $this->it_get_woo_requests('it_general_cols','-1',true);
			$it_product_type= $this->it_get_woo_requests('it_products_type','-1',true);

			$product_details=$this->it_get_full_post_meta($items->product_id);
			//print_r($product_details);
			if($it_basic_column=='yes'){
				//SKU
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= isset($product_details['sku']) && $product_details['sku']!='' ? round($product_details['sku']) : " ";
				$datatable_value.=("</td>");

				//CUSTOM WORK - 15862
				if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
					//Custom SKU
					$display_class = '';
					$custom_sku    = get_post_meta( $items->product_id, 'jk_sku', true );
					if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
						$display_class = 'display:none';
					}
					$datatable_value .= ( "<td style='" . $display_class . "' data-product-id='" . $items->product_id . "'>" );
					$datatable_value .= $custom_sku;
					$datatable_value .= ( "</td>" );
				}

				//Product Name
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= " <a href=\"".get_permalink($items->product_id)."\" target=\"_blank\">{$items->product_name}</a>";
				$datatable_value.=("</td>");

				//Category
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
						//TAXONOMY
						$display_class='';
						if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
						$datatable_value.=("<td style='".$display_class."'>");
							$datatable_value.= $this->it_get_cn_product_id($items->product_id,$tax);
						$datatable_value.=("</td>");
					}
				}

				//Stock
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= isset($product_details['stock']) && $product_details['stock']!='' ? round($product_details['stock'])  : "0";
				$datatable_value.=("</td>");




			}else if($it_basic_column!='yes'){

				//SKU
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.=  isset($product_details['sku']) ? $product_details['sku'] : "";
				$datatable_value.=("</td>");

				//CUSTOM WORK - 15862
				if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
					//Custom SKU
					$display_class = '';
					$custom_sku    = get_post_meta( $items->product_id, 'jk_sku', true );
					if ( $this->table_cols[ $index_cols ++ ]['status'] == 'hide' ) {
						$display_class = 'display:none';
					}
					$datatable_value .= ( "<td style='" . $display_class . "' data-product-id='" . $items->product_id . "'>" );
					$datatable_value .= $custom_sku;
					$datatable_value .= ( "</td>" );
				}

				//Product Name
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= " <a href=\"".get_permalink($items->product_id)."\" target=\"_blank\">{$items->product_name}</a>";
				$datatable_value.=("</td>");

				//Product Type
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->it_get_pt_product_id($items->product_id);;
				$datatable_value.=("</td>");

				//Category
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


				//Create Date
				$date_format	= get_option( 'date_format' );
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= gmdate($date_format,strtotime($items->product_date));
				$datatable_value.=("</td>");



				//Modified Date
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= gmdate($date_format,strtotime($items->modified_date));
				$datatable_value.=("</td>");

				//Stock
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $product_details['stock']!='' ? number_format($product_details['stock'],0,'','') : "0";
				$datatable_value.=("</td>");



				$regular_price='';
				$sale_price='';

				$regular_price=$product_details['regular_price'];
				$sale_price=$product_details['sale_price'];

				//Regualr Price
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($regular_price);
				$datatable_value.=("</td>");

				//Sale Price
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($sale_price);
				$datatable_value.=("</td>");

				//Downloadable
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= ucwords($product_details['downloadable']);
				$datatable_value.=("</td>");

				//Virtual
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= ucwords($product_details['virtual']);
				$datatable_value.=("</td>");

				//Manage Stock
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= ucwords($product_details['manage_stock']);
				$datatable_value.=("</td>");

				//Backorders
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $product_details['backorders']=='no' ? "Do not allow" : $product_details['backorders'];
				$datatable_value.=("</td>");

				//Stock Status
				$it_stock_status = array("instock" => esc_html__("In stock",'it_report_wcreport_textdomain'), "outofstock" => esc_html__("Out of stock",'it_report_wcreport_textdomain'));

				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $it_stock_status[$product_details['stock_status']];
				$datatable_value.=("</td>");



			}

			$datatable_value.=("</tr>");
		}
	}elseif($file_used=="search_form"){
	?>
		<form class='alldetails search_form_report' action='' method='post'>
            <input type='hidden' name='action' value='submit-form' />
            <div class="row">

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('SKU No','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-check"></i></span>
                    <input name="it_sku_no" type="text" class="sku_no"/>
                </div>

	            <?php
	            //CUSTOM WORK - 15862
	            if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
		            ?>
                    <div class="col-md-6">
                        <div class="awr-form-title">
				            <?php esc_html_e('Custom SKU','it_report_wcreport_textdomain');?>
                        </div>
                        <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                        <input name="it_custom_sku" type="text" />
                    </div>
		            <?php
	            }
	            ?>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Product Name','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-cog"></i></span>
                    <input name="it_name_of_product" type="text" class="it_name_of_product"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Min Stock','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-battery-0"></i></span>
                    <input name="it_stock_min" type="text" class="it_stock_min"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Max Stock','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-battery-4"></i></span>
                    <input name="it_stock_max" type="text" class="it_stock_max"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Product Stock','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-cog"></i></span>
                    <input name="it_stock_product" type="text" class="it_stock_product"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Show all sub-types','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-check"></i></span>
                    <select name="it_sub_product_type" id="it_sub_product_type">
                        <option value="">Show all sub-types</option>
                        <option value="downloadable">Downloadable</option>
                        <option value="virtual">Virtual</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Stock Status','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-check"></i></span>
                    <select name="it_status_of_stock" id="it_status_of_stock" class="it_status_of_stock">
                        <option value="-1">All</option>
                        <option value="instock">In stock</option>
                        <option value="outofstock">Out of stock</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Manage Stock','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-crop"></i></span>
                    <select name="it_stock_manage" id="it_stock_manage" class="it_stock_manage">
                        <option value="-1">All</option>
                        <option value="yes">Include items whose stock is mannaged</option>
                        <option value="no">Include items whose stock is not mannaged</option>
                    </select>
                </div>

                <?php
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
                        //$product_data = $this->it_get_product_woo_data('variable');
                        $products=$this->it_get_product_woo_data('0');
                        $option='';


                        foreach($products as $product){
							$selected='';
                            //CHECK IF IS IN PERMISSION
							if(is_array($permission_value) && !in_array($product->id,$permission_value))
								continue;

                            $option.="<option value='".$product -> id."' $selected>".$product -> label." </option>";
                        }


                    ?>
                    <select id="it_adr_product" name="it_product_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
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

					/////////////////
					//CUSTOM TAXONOMY
					$key=isset($_GET['smenu']) ? $_GET['smenu']:$_GET['parent'];
					$args_f=array("page"=>$key);
					//echo $this->make_custom_taxonomy($args_f);
				echo wp_kses(
					$this->make_custom_taxonomy($args_f),
					$this->allowedposttags()
				);

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
				?>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Product Type','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-check"></i></span>
                    <select name="it_products_type" id="it_products_type">
                        <option value="-1">Show all product types</option>
                        <option value="simple">Simple product</option>
                        <option value="variable">Variable</option>
                    </select>
                </div>

                <?php
                	$it_product_sku_data = $this->it_get_woo_all_prod_sku();
					//echo ($it_product_sku_data);
					if($it_product_sku_data){
				?>

                <div class="col-md-6">
                	<div class="awr-form-title">
						<?php esc_html_e('Product SKU','it_report_wcreport_textdomain');?>
                    </div>
					<span class="awr-form-icon"><i class="fa fa-cog"></i></span>
					<?php
                        $option='';
                        foreach($it_product_sku_data as $sku){
                            $option.="<option value='".$sku->id."' >".$sku->label."</option>";
                        }
                    ?>

                    <select name="it_sku_products[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
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
                <?php
					}
				?>


	            <?php
	            //CUSTOM WORK - 15862
	            if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
		            ?>
                    <div class="col-md-6">
                        <div class="awr-form-title">
				            <?php esc_html_e('Product Custom SKU','it_report_wcreport_textdomain');?>
                        </div>
                        <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                        <input name="it_custom_sku" type="text" />
                    </div>
		            <?php
	            }
	            ?>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Basic Column','it_report_wcreport_textdomain');?>
                    </div>

                    <input type="checkbox" name="it_general_cols" class="it_general_cols" value="yes">
                </div>


                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Zero Stock','it_report_wcreport_textdomain');?>
                    </div>

                    <input type="checkbox" name="it_stock_zero" class="it_stock_zero" value="yes" >
                    <label>Include items having 0 stock</label>
                </div>

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
                    <input type="hidden" name="it_id_order_status[]" id="it_id_order_status" value="-1">
                    <input type="hidden" name="it_orders_status[]" id="order_status" value="<?php echo esc_attr($this->it_shop_status); ?>">

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
