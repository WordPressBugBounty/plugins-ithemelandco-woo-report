<?php

	if($file_used=="sql_table")
	{
		$it_create_date =  gmdate("Y-m-d");
		$it_from_date=$this->it_from_date_dashboard;
		$it_to_date=$this->it_to_date_dashboard;

		$it_hide_os=$this->otder_status_hide;
		$it_shop_order_status=$this->it_shop_status;

		if(isset($_POST['it_from_date']))
		{
			//parse_str($_REQUEST, $my_array_of_vars);
			$this->search_form_fields=$_POST;

			$it_from_date		  = $this->it_get_woo_requests('it_from_date',NULL,true);
			$it_to_date			= $this->it_get_woo_requests('it_to_date',NULL,true);
			$it_hide_os	= $this->it_get_woo_requests('it_hide_os',$it_hide_os,true);
			$it_shop_order_status	= $this->it_get_woo_requests('shop_order_status',$it_shop_order_status,true);
			$it_create_date =  $it_to_date;
		}


		$it_url_shop_order_status	= "";
		$it_in_shop_os	= "";
		$it_in_post_os	= "";


		//$it_hide_os=$this->otder_status_hide;
		//$it_hide_os	= $this->it_get_woo_requests('it_hide_os',$it_hide_os,true);
		$it_hide_os=explode(',',$it_hide_os);
		//$it_shop_order_status="wc-completed,wc-on-hold,wc-processing";
		//$it_shop_order_status	= $this->it_get_woo_requests('shop_order_status',$it_shop_order_status,true);
		if(strlen($it_shop_order_status)>0 and $it_shop_order_status != "-1")
			$it_shop_order_status = explode(",",$it_shop_order_status);
		else $it_shop_order_status = array();

		if(count($it_shop_order_status)>0){
			$it_in_post_os	= implode("', '",$it_shop_order_status);
		}

		$in_it_hide_os = "";
		if(count($it_hide_os)>0){
			$in_it_hide_os		= implode("', '",$it_hide_os);
		}

		/*Today*/
		$sql_columns = " SUM(postmeta.meta_value)AS 'OrderTotal'
		,COUNT(*) AS 'OrderCount'
		,'Today' AS 'SalesOrder'";

		$sql_joins = " {$wpdb->prefix}postmeta as postmeta
		LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=postmeta.post_id";

		if(strlen($it_in_shop_os)>0){
			$it_in_shop_os_join = "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
		}

		$sql_condition = " meta_key='_order_total'
		AND DATE(it_posts.post_date) = '".$it_create_date."' AND it_posts.post_type IN ('shop_order')";

		if(strlen($it_in_shop_os)>0){
			$sql_condition .= " AND  term_taxonomy.term_id IN ({$it_in_shop_os})";
		}

		if(strlen($it_in_post_os)>0){
			$sql_condition .= " AND  it_posts.post_status IN ('{$it_in_post_os}')";
		}

		if(strlen($in_it_hide_os)>0){
			$sql_condition .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
		}

		$it_today_sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition";


		/*Yesterday*/
		$sql_columns = " SUM(postmeta.meta_value)AS 'OrderTotal'
		,COUNT(*) AS 'OrderCount'
		,'Yesterday' AS 'Sales Order'";

		$sql_joins=" {$wpdb->prefix}postmeta as postmeta LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=postmeta.post_id";

		if(strlen($it_in_shop_os)>0){
			$sql_joins .= "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
		}

		$sql_condition = " meta_key='_order_total' AND  DATE(it_posts.post_date)= DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))
		 AND it_posts.post_type IN ('shop_order')";

		if(strlen($it_in_shop_os)>0){
			$sql_condition .= " AND  term_taxonomy.term_id IN ({$it_in_shop_os})";
		}

		if(strlen($it_in_post_os)>0){
			$sql_condition .= " AND  it_posts.post_status IN ('{$it_in_post_os}')";
		}

		if(strlen($in_it_hide_os)>0){
			$sql_condition .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
		}

		$it_yesterday_sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition";

		/*Week*/
		$sql_columns = " SUM(postmeta.meta_value)AS 'OrderTotal'
		,COUNT(*) AS 'OrderCount'
		,'Week' AS 'Sales Order'";

		$sql_joins = " {$wpdb->prefix}postmeta as postmeta
		LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=postmeta.post_id";

		if(strlen($it_in_shop_os)>0){
			$sql_joins .= "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
		}

		$sql_condition = " meta_key='_order_total' AND WEEK(CURDATE()) = WEEK(DATE(it_posts.post_date)) AND YEAR(CURDATE()) = YEAR(it_posts.post_date) AND it_posts.post_type IN ('shop_order')";

		if(strlen($it_in_shop_os)>0){
			$sql_condition .= " AND  term_taxonomy.term_id IN ({$it_in_shop_os})";
		}

		if(strlen($it_in_post_os)>0){
			$sql_condition .= " AND  it_posts.post_status IN ('{$it_in_post_os}')";
		}


		if(strlen($in_it_hide_os)>0){
			$sql_condition .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
		}

		$it_week_sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition";

		/*Month*/
		$sql_columns = " SUM(postmeta.meta_value)AS 'OrderTotal' ,COUNT(*) AS 'OrderCount','Month' AS 'Sales Order'";

		$sql_joins = " {$wpdb->prefix}postmeta as postmeta LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=postmeta.post_id";

		if(strlen($it_in_shop_os)>0){
			$sql_joins .= "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
		}

		$sql_condition = " meta_key='_order_total'
		AND MONTH(DATE(CURDATE())) = MONTH( DATE(it_posts.post_date))
		AND YEAR(DATE(CURDATE())) = YEAR( DATE(it_posts.post_date))
		AND it_posts.post_type IN ('shop_order')";

		if(strlen($it_in_shop_os)>0){
			$sql_condition .= " AND  term_taxonomy.term_id IN ({$it_in_shop_os})";
		}

		if(strlen($it_in_post_os)>0){
			$sql_condition .= " AND  it_posts.post_status IN ('{$it_in_post_os}')";
		}

		if(strlen($in_it_hide_os)>0){
			$sql_condition .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
		}

		$it_month_sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition";

		/*Year*/
		$sql_columns = " SUM(postmeta.meta_value)AS 'OrderTotal' ,COUNT(*) AS 'OrderCount','Year' AS 'Sales Order'";

		$sql_joins = " {$wpdb->prefix}postmeta as postmeta LEFT JOIN  {$wpdb->prefix}posts as it_posts ON it_posts.ID=postmeta.post_id";

		if(strlen($it_in_shop_os)>0){
			$sql_joins .= "
			LEFT JOIN  {$wpdb->prefix}term_relationships 	as it_term_relationships 	ON it_term_relationships.object_id		=	it_posts.ID
			LEFT JOIN  {$wpdb->prefix}term_taxonomy 		as term_taxonomy 		ON term_taxonomy.term_taxonomy_id	=	it_term_relationships.term_taxonomy_id";
		}

		$sql_condition = " meta_key='_order_total' AND YEAR(DATE(CURDATE())) = YEAR( DATE(it_posts.post_date)) AND it_posts.post_type IN ('shop_order')";

		if(strlen($it_in_shop_os)>0){
			$sql_condition .= " AND  term_taxonomy.term_id IN ({$it_in_shop_os})";
		}

		if(strlen($it_in_post_os)>0){
			$sql_condition .= " AND  it_posts.post_status IN ('{$it_in_post_os}')";
		}

		if(strlen($in_it_hide_os)>0){
			$sql_condition .= " AND  it_posts.post_status NOT IN ('{$in_it_hide_os}')";
		}

		$it_year_sql = "SELECT  $sql_columns FROM $sql_joins WHERE $sql_condition";

		$sql = '';
		$sql .= $it_today_sql;
		$sql .= " UNION ";
		$sql .= $it_yesterday_sql;
		$sql .= " UNION ";
		$sql .= $it_week_sql;
		$sql .= " UNION ";
		$sql .= $it_month_sql;
		$sql .= " UNION ";
		$sql .= $it_year_sql;

		//echo $sql;

	}elseif($file_used=="data_table"){

		foreach($this->results as $items){
			$index_cols=0;
		//for($i=1; $i<=20 ; $i++){

			$datatable_value.=("<tr>");

				//Month
				$display_class='';
				$month=esc_html(sanitize_text_field($items->SalesOrder),'it_report_wcreport_textdomain');
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $month;
				$datatable_value.=("</td>");

				//Target Sales
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $items->OrderCount;
				$datatable_value.=("</td>");

				//Actual Sales
				$display_class='';
				if($this->table_cols[$index_cols++]['status']=='hide') $display_class='display:none';
				$datatable_value.=("<td style='".$display_class."'>");
					$datatable_value.= $this->price($items->OrderTotal);
				$datatable_value.=("</td>");

			$datatable_value.=("</tr>");
		}
	}elseif($file_used=="search_form"){}

?>
