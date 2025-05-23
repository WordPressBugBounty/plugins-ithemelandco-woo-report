<?php

if($file_used=="sql_table")
{

	//GET POSTED PARAMETERS
	$request 			= array();
	$start				= 0;
	$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
	$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
	$date_format = $this->it_date_format($it_from_date);

	//CUSTOM WORK - 11899
	$it_from_date_delivery		  = $this->it_get_woo_requests('it_from_date_delivery',NULL,true);
	$it_to_date_delivery			= $this->it_get_woo_requests('it_to_date_delivery',NULL,true);

	$it_product_id			= $this->it_get_woo_requests('it_product_id',"-1",true);
	$category_id 		= $this->it_get_woo_requests('it_category_id','-1',true);
	$tag_id 		= $this->it_get_woo_requests('it_tags_id','-1',true);
	$it_id_order_status 	= $this->it_get_woo_requests('it_id_order_status',NULL,true);
	$it_order_status		= $this->it_get_woo_requests('it_orders_status','-1',true);
	//$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";
	$it_cat_prod_id_string = $this->it_get_woo_pli_category($category_id,$it_product_id);
	$it_show_cog		= $this->it_get_woo_requests('it_show_cog','no',true);

	//BRAND ADDONS
	$brand_id 		= $this->it_get_woo_requests('it_brand_id','-1',true);
	$it_brand_prod_id_string = $this->it_get_woo_pli_category($brand_id,$it_product_id);

	///////////HIDDEN FIELDS////////////
	$it_hide_os		= $this->it_get_woo_requests('it_hide_os','-1',true);
	$it_publish_order='no';

	$data_format=$this->it_get_woo_requests_links('date_format',get_option('date_format'),true);
	//////////////////////
	//$category_id 				= "-1";
	if(is_array($category_id)){ 		$category_id		= implode(",", $category_id);}
	if(is_array($tag_id)){ 		$tag_id		= implode(",", $tag_id);}

	//BRANDS ADDON
	if(is_array($brand_id)){ 		$brand_id		= implode(",", $brand_id);}

	//CUSTOM WORK - 15862
	$it_custom_sku		= $this->it_get_woo_requests('it_custom_sku','-1',true);

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
					$custom_tax_cols[]=array('lable'=>esc_html(sanitize_text_field($label),'ithemelandco-woo-report'),'status'=>'show');


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

				$lbl_col=$tax."_cols";
				$lbl_join=$tax."_join";
				$lbl_con=$tax."_condition";


				${$lbl_col} ='';
				${$lbl_join} ='';
				${$lbl_con} = '';

				if(${$tax}  && ${$tax} != "-1") {
					${$lbl_col} = "

							,term_taxonomy$i.parent						AS term_parent";
				}

				$all_tax_cols=" ".${$lbl_col}." ";

				if(${$tax}  && ${$tax} != "-1") {
					${$lbl_join} = "
							LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships$i 	ON it_term_relationships$i.object_id		=	it_woocommerce_order_itemmeta7.meta_value
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


	//CUSTOM WORK - 15862
	$custom_sku = '';
	$custom_sku_join = '';
	$custom_sku_condition = '';

	//CATEGORY
	$category_id_cols='';
	$category_id_join='';
	$category_id_condition='';

	//TAG
	$tag_id_cols='';
	$tag_id_join='';
	$tag_id_condition='';

	//CATEGORY
	$brand_id_cols='';
	$brand_id_join='';
	$brand_id_condition='';
	$it_brand_prod_id_string_condition='';

	//PRODUCT
	$it_product_id_condition='';

	//DATE
	$it_from_date_condition='';

	//CUSTOM WORK - 11899
	//DELIVERY DATA CONDITION
	$date_delivery_condition= '';
	$delivery_date_join='';

	//PRODUCT STRING
	$it_cat_prod_id_string_condition='';

	//ORDER STATUS
	$it_order_status_condition='';
	$it_id_order_status_join='';
	$it_id_order_status_condition='';

	//PUBLISH STATUS
	$it_publish_order_condition='';

	//HIDE ORDER STATUS
	$it_hide_os_condition='';


	/////////////////////////
	//APPLY PERMISSION TERMS
	$key=$this->it_get_woo_requests('table_names','',true);

	$category_id=$this->it_get_form_element_permission('it_category_id',$category_id,$key);
	$tag_id=$this->it_get_form_element_permission('it_tag_id',$tag_id,$key);

	////ADDED IN VER4.0
	//BRANDS ADDONS
	$brand_id=$this->it_get_form_element_permission('it_brand_id',$brand_id,$key);

	$it_product_id=$this->it_get_form_element_permission('it_product_id',$it_product_id,$key);

	$it_order_status=$this->it_get_form_element_permission('it_orders_status',$it_order_status,$key);

	if($it_order_status != NULL  && $it_order_status != '-1')
		$it_order_status  		= "'".str_replace(",","','",$it_order_status)."'";
	///////////////////////////


	$sql = " SELECT ";

	$sql_columns = "shop_order.ID as order_id,
					it_woocommerce_order_items.order_item_name		AS 'product_name'
					,it_woocommerce_order_items.order_item_id		AS order_item_id
					,it_woocommerce_order_itemmeta7.meta_value		AS product_id
					,DATE(shop_order.post_date)					AS post_date
					";

	//CUSTOM WORK - 15862
	if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){

		//$sql_columns .= ",it_custom_sku.meta_value					AS custom_sku";
	}

	if($category_id  && $category_id != "-1") {

		$category_id_cols = "
					,it_terms.term_id								AS term_id
					,it_terms.name									AS term_name
					,term_taxonomy.parent						AS term_parent
				";
	}

	/////ADDED IN VER4.0
	/// PRODUCT TAG
	if($tag_id  && $tag_id != "-1") {

		$tag_id_cols = "
					,it_terms_tag.term_id								AS term_id_tag
					,it_terms_tag.name									AS term_name_tag
					,term_taxonomy_tag.parent						AS term_parent_tag
				";
	}

	//BRANDS ADDON
	if($brand_id  && $brand_id != "-1") {

		$brand_id_cols = "
					,it_terms_brand.term_id								AS term_id
					,it_terms_brand.name									AS term_name
					,term_taxonomy_brand.parent						AS term_parent
				";
	}

	//$sql .= " ,woocommerce_order_itemmeta.meta_value AS 'quantity' ,it_woocommerce_order_itemmeta6.meta_value AS 'total_amount'";
	$sql_columns .= "$category_id_cols $tag_id_cols $brand_id_cols ,SUM(woocommerce_order_itemmeta.meta_value) AS 'quantity' ,SUM(it_woocommerce_order_itemmeta6.meta_value) AS 'total_amount' ";


	//COST OF GOOD
	if($it_show_cog=='yes'){
		$sql_columns .= " ,SUM(woocommerce_order_itemmeta.meta_value * it_woocommerce_order_itemmeta22.meta_value) AS 'total_cost'";
	}


	$sql_joins = "
					{$wpdb->prefix}woocommerce_order_items as it_woocommerce_order_items
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as woocommerce_order_itemmeta ON woocommerce_order_itemmeta.order_item_id=it_woocommerce_order_items.order_item_id
					LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta6 ON it_woocommerce_order_itemmeta6.order_item_id=it_woocommerce_order_items.order_item_id ";

	//COST OF GOOD
	if($it_show_cog=='yes'){
		$sql_joins .=	"
            LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta22 ON it_woocommerce_order_itemmeta22.order_item_id=it_woocommerce_order_items.order_item_id ";
	}

	$sql_joins .=	"
        LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta7 ON it_woocommerce_order_itemmeta7.order_item_id=it_woocommerce_order_items.order_item_id		LEFT JOIN  {$wpdb->prefix}woocommerce_order_itemmeta as it_woocommerce_order_itemmeta11 ON it_woocommerce_order_itemmeta11.order_item_id=it_woocommerce_order_items.order_item_id
                ";

	if($category_id  && $category_id != "-1") {
		$category_id_join = "
					LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_woocommerce_order_itemmeta7.meta_value
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 				as it_terms 				ON it_terms.term_id					=	term_taxonomy.term_id";
	}

	/////ADDED IN VER4.0
	/// PRODUCT TAG
	if($tag_id  && $tag_id != "-1") {
		$tag_id_join = "
					LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_tag 	ON it_term_relationships_tag.object_id		=	it_woocommerce_order_itemmeta7.meta_value
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_tag 		ON term_taxonomy_tag.term_taxonomy_id	=	it_term_relationships_tag.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_tag 				ON it_terms_tag.term_id					=	term_taxonomy_tag.term_id";
	}


	//BRANDS ADDON
	if($brand_id  && $brand_id != "-1") {
		$brand_id_join = "
					LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships_brand 	ON it_term_relationships_brand.object_id		=	it_woocommerce_order_itemmeta7.meta_value
					LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy_brand 		ON term_taxonomy_brand.term_taxonomy_id	=	it_term_relationships_brand.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms 				as it_terms_brand 				ON it_terms_brand.term_id					=	term_taxonomy_brand.term_id";
	}

	if($it_id_order_status  && $it_id_order_status != "-1") {
		$it_id_order_status_join= "
					LEFT JOIN  {$wpdb->prefix}term_relationships	as it_term_relationships2 	ON it_term_relationships2.object_id	=	it_woocommerce_order_items.order_id
					LEFT JOIN  {$wpdb->prefix}term_taxonomy			as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
					LEFT JOIN  {$wpdb->prefix}terms					as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
	}


    //CUSTOM WORK - 15862
	if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
		if($it_custom_sku  && $it_custom_sku != "-1") {
			$custom_sku_join = " LEFT JOIN {$wpdb->prefix}postmeta as it_custom_sku ON it_custom_sku.post_id = it_woocommerce_order_itemmeta7.meta_value ";
		}
	}

	$sql_joins .= " $custom_sku_join $category_id_join $tag_id_join $brand_id_join $it_id_order_status_join
					LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.id=it_woocommerce_order_items.order_id";

	$sql_condition = "
					1*1
					AND woocommerce_order_itemmeta.meta_key	= '_qty'
					AND it_woocommerce_order_itemmeta6.meta_key	= '_line_total'";

	//CUSTOM WORK - 15862
	if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)){
		if($it_custom_sku  && $it_custom_sku != "-1") {
			$custom_sku_condition .= " AND it_custom_sku.meta_key = 'jk_sku' AND it_custom_sku.meta_value LIKE '%$it_custom_sku%'";
		}
	}

	//COST OF GOOD
	if($it_show_cog=='yes'){
		$sql_condition .="
            AND it_woocommerce_order_itemmeta22.meta_key	= '".__IT_COG_TOTAL__."' ";
	}

	$sql_condition .="
        AND it_woocommerce_order_itemmeta7.meta_key 	= '_product_id'
        AND it_woocommerce_order_itemmeta11.meta_key 	= '_variation_id'
        AND it_woocommerce_order_itemmeta11.meta_value 	= '0'
        AND (shop_order.post_type					= 'shop_order'
        OR shop_order.post_type					= 'shop_order_refund')
        ";


	//CUSTOM WORK - 11899
	if ($it_from_date_delivery != NULL &&  $it_to_date_delivery !=NULL) {
		$delivery_date_join      = " LEFT JOIN {$wpdb->prefix}postmeta as it_delivery ON it_delivery.post_id=shop_order.ID ";
		$date_delivery_condition .= " AND it_delivery.meta_key='_delivery_date' ";
	}


	//CUSTOM WORK - 11899
	if ($it_from_date_delivery != NULL &&  $it_to_date_delivery !=NULL){
		$date_delivery_condition .= " AND it_delivery.meta_value BETWEEN '".$it_from_date_delivery."' AND '". $it_to_date_delivery ."'";
	}



	if ($it_from_date != NULL &&  $it_to_date !=NULL){
		$it_from_date_condition= "
					AND (DATE(shop_order.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
	}

	if($it_product_id  && $it_product_id != "-1")
		$it_product_id_condition = "
					AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_product_id .")";

	if($category_id  && $category_id != "-1")
		$category_id_condition = "
					AND it_terms.term_id IN (".$category_id .")";

	////ADDED IN VER4.0
	/// PRODUCT TAG
	if($tag_id  && $tag_id != "-1")
		$tag_id_condition = "
					AND it_terms_tag.term_id IN (".$tag_id .")";

	//BRANDS ADDON
	if($brand_id  && $brand_id != "-1")
		$brand_id_condition = "
					AND it_terms_brand.term_id IN (".$brand_id .")";

	if($it_cat_prod_id_string  && $it_cat_prod_id_string != "-1")
		$it_cat_prod_id_string_condition= " AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_cat_prod_id_string .")";

	////ADDED IN VER4.0
	//BRANDS ADDONS
	if($it_brand_prod_id_string  && $it_brand_prod_id_string != "-1")
		$it_brand_prod_id_string_condition= " AND it_woocommerce_order_itemmeta7.meta_value IN (".$it_brand_prod_id_string .")";

	if($it_id_order_status  && $it_id_order_status != "-1")
		$it_id_order_status_condition = "
					AND terms2.term_id IN (".$it_id_order_status .")";


	if(strlen($it_publish_order)>0 && $it_publish_order != "-1" && $it_publish_order != "no" && $it_publish_order != "all"){
		$in_post_status		= str_replace(",","','",$it_publish_order);
		$it_publish_order_condition= " AND  shop_order.post_status IN ('{$in_post_status}')";
	}
	//echo $it_order_status;
	if($it_order_status  && $it_order_status != '-1' and $it_order_status != "'-1'")
		$it_order_status_condition= " AND shop_order.post_status IN (".$it_order_status.")";

	if($it_hide_os  && $it_hide_os != '-1' and $it_hide_os != "'-1'")
		$it_hide_os_condition = " AND shop_order.post_status NOT IN ('".$it_hide_os."')";

	$sql_group_by = " GROUP BY  it_woocommerce_order_itemmeta7.meta_value";

	$sql_order_by = " ORDER BY total_amount DESC";

	$sql = "SELECT $sql_columns $all_tax_cols FROM $sql_joins $all_tax_joins $delivery_date_join WHERE
							$sql_condition $it_from_date_condition $it_product_id_condition
							$category_id_condition $tag_id_condition $brand_id_condition $all_tax_conditions  $it_cat_prod_id_string_condition
							$it_brand_prod_id_string_condition $it_id_order_status_condition $it_publish_order_condition
							$date_delivery_condition $custom_sku_condition
							$it_order_status_condition $it_hide_os_condition $sql_group_by $sql_order_by";


	//echo $sql;

	$this->table_cols =$this->table_columns($table_name);
	$array_index=4;
	if(is_array($custom_tax_cols))
		array_splice($this->table_cols,$array_index,0,$custom_tax_cols);

	///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
	$brands_cols=array();
	if(__IT_BRAND_SLUG__){
		$brands_cols[]=array('lable'=>__IT_BRAND_LABEL__,'status'=>'show');
		array_splice($this->table_cols,$array_index,0,$brands_cols);
		$array_index++;
	}


	//CUSTOM WORK - 15862
	if(is_array(__CUSTOMWORK_ID__) && in_array('15862',__CUSTOMWORK_ID__)) {
		$custom_sku_cols[]=array('lable'=>'Custom SKU','status'=>'show');
		array_splice($this->table_cols,1,0,$custom_sku_cols);
	}


	////ADDE IN VER4.0
	/// COST OF GOOD
	//CHECK IF COST OF GOOD IS ENABLE
	if($it_show_cog!='yes'){
		unset($this->table_cols[count($this->table_cols)-1]);
		unset($this->table_cols[count($this->table_cols)-1]);
	}

	//$this->table_cols = $columns;

}elseif($file_used=="data_table"){
	//print_r($this->results);

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


	////ADDE IN VER4.0
	/// TOTAL ROWS VARIABLES
	$product_count=$sales_qty=$total_amnt=$cog_amnt=$profit_amnt=0;


	foreach($this->results as $items){
	    $index_cols=0;
		//for($i=1; $i<=20 ; $i++){

		////ADDE IN VER4.0
		/// TOTAL ROWS
		$product_count++;

		$datatable_value.=("<tr>");

		//Product SKU
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->it_get_prod_sku($items->order_item_id, $items->product_id);
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
		$datatable_value.= " <a href=\"".get_permalink($items->product_id)."\" target=\"_blank\">".get_the_title($items->product_id)."</a>";
		$datatable_value.=("</td>");

		//Categories
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->it_get_cn_product_id($items->product_id,"product_cat");
		$datatable_value.=("</td>");


		//Tags
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $this->it_get_cn_product_id($items->product_id,"product_tag");
		$datatable_value.=("</td>");

		///////////////CUSTOM TAXONOMY/////////////////
		if(defined("__IT_TAX_FIELD_ADD_ON__") &&  is_array($visible_custom_taxonomy)){
			foreach((array)$visible_custom_taxonomy as $tax){
				//Category
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
				$datatable_value.=$this->it_get_cn_product_id($items->product_id,$tax);
				$datatable_value.=("</td>");
			}
		}
		////////////////////////////

		///////////CHECK IF BRANDS ADD ON IS ENABLE///////////
		if(__IT_BRAND_SLUG__){
			$display_class='';
			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
			$datatable_value.= $this->it_get_cn_product_id($items->product_id,__IT_BRAND_SLUG__);
			$datatable_value.=("</td>");
		}


		//Sale Qty.
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."' >");
		$datatable_value.= $items->quantity;

		////ADDED IN VER4.0
		/// TOTAL ROWS
		$sales_qty+=$items->quantity;
		$datatable_value.=("</td>");

		//Current Stock
		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		//$datatable_value.= number_format($this->it_get_prod_stock_($items->order_item_id, $items->product_id),0);
		$datatable_value.= get_post_meta( $items->product_id, '_stock', true );
		$datatable_value.=("</td>");

		//Amount
		//global $WOOCS;
		//$res=$WOOCS->woocs_exchange_value('£568.11');

		$display_class='';
		if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
		$datatable_value.=("<td style='".$display_class."'>");
		$datatable_value.= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);

		////ADDED IN VER4.0
		/// TOTAL ROWS
		$total_amnt+=$items->total_amount;
		$datatable_value.=("</td>");


		//COST OF GOOD
		$it_show_cog= $this->it_get_woo_requests('it_show_cog',"no",true);
		if($it_show_cog=='yes'){
			$display_class='';

			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
			//$datatable_value.= $cog == 0 ? $this->price(0) : $this->price($cog);
			$datatable_value.= $items->total_cost == 0 ? $this->price(0) : $this->price($items->total_cost);

			////ADDED IN VER4.0
			/// TOTAL ROWS
			$cog_amnt+=$items->total_cost;

			$datatable_value.=("</td>");

			if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
			$datatable_value.=("<td style='".$display_class."'>");
			//$datatable_value.= $cog == 0 ? $this->price(0) : $this->price($cog);
			$datatable_value.= ($items->total_amount-$items->total_cost) == 0 ? $this->price(0) : $this->price($items->total_amount-$items->total_cost);

			////ADDED IN VER4.0
			/// TOTAL ROWS
			$profit_amnt+=($items->total_amount-$items->total_cost);

			$datatable_value.=("</td>");
		}


		$datatable_value.=("</tr>");
	}

	////ADDED IN VER4.0
	/// TOTAL ROW
	$table_name_total= $table_name;
	$this->table_cols_total = $this->table_columns_total( $table_name_total );
	$it_show_cog		= $this->it_get_woo_requests('it_show_cog','no',true);
	$datatable_value_total='';
	if($it_show_cog!='yes'){
		////ADDE IN VER4.0
		/// COST OF GOOD
		unset($this->table_cols_total[count($this->table_cols_total)-1]);
		unset($this->table_cols_total[count($this->table_cols_total)-1]);
	}

	$datatable_value_total.=("<tr>");
	$datatable_value_total.="<td>$product_count</td>";
	$datatable_value_total.="<td>$sales_qty</td>";
	$datatable_value_total.="<td>".(($total_amnt) == 0 ? $this->price(0) : $this->price($total_amnt))."</td>";
	if($it_show_cog=='yes'){
		$datatable_value_total.="<td>".(($cog_amnt) == 0 ? $this->price(0) : $this->price($cog_amnt))."</td>";
		$datatable_value_total.="<td>".(($profit_amnt) == 0 ? $this->price(0) : $this->price($profit_amnt))."</td>";
	}
	$datatable_value_total.=("</tr>");

}elseif($file_used=="search_form"){
	?>
    <form class='alldetails search_form_report' action='' method='post' id="product_form">
        <input type='hidden' name='action' value='submit-form' />
        <div class="row">

            <div class="col-md-6">
                <div class="awr-form-title">
					<?php esc_html_e('Date From','ithemelandco-woo-report');?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="it_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick"/>
            </div>

            <div class="col-md-6">
                <div class="awr-form-title">
					<?php esc_html_e('Date To','ithemelandco-woo-report');?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="it_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"/>
            </div>


            <?php
            //CUSTOM WORK - 11899
            if(is_array(__CUSTOMWORK_ID__) && in_array('11899',__CUSTOMWORK_ID__)) {

                ?>
                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Delivery Date From', 'ithemelandco-woo-report'); ?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_from_date_delivery" id="pwr_from_date_delivery" type="text" readonly='true'
                           class="datepick"/>
                </div>

                <div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Delivery Date To', 'ithemelandco-woo-report'); ?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_to_date_delivery" id="pwr_to_date_delivery" type="text" readonly='true' class="datepick"/>
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
				        <?php esc_html_e('Custom SKU','ithemelandco-woo-report');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                    <input name="it_custom_sku" type="text" />
                </div>
		        <?php
	        }
	        ?>

			<?php
			/////////////////
			//CUSTOM TAXONOMY
			$key=isset($_GET['smenu']) ? $_GET['smenu']:$_GET['parent'];
			$args_f=array("page"=>$key);
			//echo $this->make_custom_taxonomy($args_f);
			echo wp_kses(
				$this->make_custom_taxonomy($args_f),
				$this->allowedposttags()
			);
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
						<?php esc_html_e('Category','ithemelandco-woo-report');?>
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
                            <option value="-1"><?php esc_html_e('Select All','ithemelandco-woo-report');?></option>
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

			<?php
			$col_style='';
			$permission_value=$this->get_form_element_value_permission('it_tags_id');
			if($this->get_form_element_permission('it_tags_id') ||  $permission_value!='') {
				if ( ! $this->get_form_element_permission( 'it_tags_id' ) && $permission_value != '' ) {
					$col_style = 'display:none';
				}
				?>

                <div class="col-md-6" style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
						<?php esc_html_e( 'Tags', 'ithemelandco-woo-report' ); ?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-tags"></i></span>
					<?php
					$p_categories     = $this->it_get_tag_products( 'product_tag' );
					$option           = '';
					$current_category = $this->it_get_woo_requests_links( 'it_tags_id', '', true );
					//echo $current_product;

					foreach ( $p_categories as $category ) {
						$selected = '';

						$option .= "<option $selected value='" . $category->id . "' >" . $category->label . " </option>";
					}

					?>
                    <select name="it_tags_id[]" multiple="multiple" size="5" data-size="5"
                            class="chosen-select-search">
						<?php
						if ( $this->get_form_element_permission( 'it_tags_id' ) && ( ( ! is_array( $permission_value ) ) || ( is_array( $permission_value ) && in_array( 'all', $permission_value ) ) ) ) {
							?>
                            <option value="-1"><?php esc_html_e( 'Select All', 'ithemelandco-woo-report' ); ?></option>
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
                            <option value="-1"><?php esc_html_e('Select All','ithemelandco-woo-report');?></option>
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
			$permission_value=$this->get_form_element_value_permission('it_product_id');
			if($this->get_form_element_permission('it_product_id') ||  $permission_value!=''){
				if(!$this->get_form_element_permission('it_product_id') &&  $permission_value!='')
					$col_style='display:none';
				?>

                <div class="col-md-6"  style=" <?php echo esc_attr($col_style); ?>">
                    <div class="awr-form-title">
						<?php esc_html_e('Product','ithemelandco-woo-report');?>
                    </div>
                    <span class="awr-form-icon"><i class="fa fa-cog"></i></span>
					<?php
					$products=$this->it_get_product_woo_data('simple');

// print_r($products);

					$option='';
					$current_product=$this->it_get_woo_requests_links('it_product_id','',true);
					//echo $current_product;

					foreach($products as $product){
						$selected='';
						if(is_array($permission_value) && !in_array($product->id,$permission_value))
							continue;
						if(!$this->get_form_element_permission('it_product_id') &&  $permission_value!='')
							$selected="selected";


						$option.="<option $selected value='".$product -> id."' >".$product -> label." </option>";
					}


					?>
                    <select name="it_product_id[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
						<?php
						if($this->get_form_element_permission('it_product_id') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
						{
							?>
                            <option value="-1"><?php esc_html_e('Select All','ithemelandco-woo-report');?></option>
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
						<?php esc_html_e('Status','ithemelandco-woo-report');?>
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

						////ADDED IN VER4.0
						/// APPLY DEFAULT STATUS AT FIRST
						if(is_array($shop_status_selected) && in_array($key,$shop_status_selected))
							$selected="selected";

						$option.="<option value='".$key."' $selected >".$value."</option>";
					}
					?>

                    <select name="it_orders_status[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">
						<?php
						if($this->get_form_element_permission('it_orders_status') && ((!is_array($permission_value)) || (is_array($permission_value) && in_array('all',$permission_value))))
						{
							?>
                            <option value="-1"><?php esc_html_e('Select All','ithemelandco-woo-report');?></option>
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
			?>

			<?php
			if(__IT_COG__!=''){
				?>

                <div class="col-md-6">
                    <div class="awr-form-title">
						<?php esc_html_e('SHOW JUST INCLUDE C.O.G & PROFIT','ithemelandco-woo-report');?>
                        <br />
                        <span class="description"><?php esc_html_e('Include just products with current Profit(Cost of good) plugin(Selected in Setting -> Add-on Settings -> Cost of Good).','ithemelandco-woo-report');?></span>
                    </div>

                    <input name="it_show_cog" type="checkbox" value="yes"/>

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

            <input type="hidden" name="it_hide_os" id="it_hide_os" value="<?php echo esc_html($it_hide_os);?>" />

            <input type="hidden" name="date_format" id="date_format" value="<?php echo esc_html($data_format);?>" />
            <input type="hidden" name="table_names" value="<?php echo esc_html($table_name);?>"/>

            <div class="fetch_form_loading search-form-loading"></div>
            <button type="submit" value="Search" class="button-primary"><i class="fa fa-search"></i> <span><?php echo esc_html__('Search','ithemelandco-woo-report'); ?></span></button>
            <button type="button" value="Reset" class="button-secondary form_reset_btn"><i class="fa fa-reply"></i><span><?php echo esc_html__('Reset Form','ithemelandco-woo-report'); ?></span></button>
        </div>

    </form>
	<?php
}

?>
