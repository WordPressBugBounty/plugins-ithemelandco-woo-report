<?php

if ($file_used == "sql_table") {

    //GET POSTED PARAMETERS
    $request      = array();
    $start        = 0;
    $it_from_date = $this->it_get_woo_requests('it_from_date', null, true);
    $it_to_date   = $this->it_get_woo_requests('it_to_date', null, true);
    $date_format  = $this->it_date_format($it_from_date);

    $it_id_order_status = $this->it_get_woo_requests('it_id_order_status', null, true);
    $it_order_status    = $this->it_get_woo_requests('it_orders_status', '-1', true);
    $it_order_status    = "'" . str_replace(",", "','", $it_order_status) . "'";

    $group_by           = $this->it_get_woo_requests('it_groupby', 'refund_id', true);
    $refund_status_type = $this->it_get_woo_requests('it_refund_status_type', 'part_refunded', true);
    if ($refund_status_type == "part_refunded") {
        if ($group_by == "order_id") {
            //$_REQUEST['group_by'] = 'refund_id';
        }
    } else {
        if ($group_by == "refund_id") {
            $group_by = 'order_id';
        }
    }

    ///////////HIDDEN FIELDS////////////
    $it_hide_os       = $this->it_get_woo_requests('it_hide_os', '-1', true);
    $it_publish_order = 'no';

    $data_format = $this->it_get_woo_requests_links('date_format', get_option('date_format'), true);
    //////////////////////


    //ORDER SATTUS
    $it_id_order_status_join   = '';
    $it_order_status_condition = '';


    //ORDER STATUS
    $it_id_order_status_condition = '';

    //DATE
    $it_from_date_condition = '';

    //PUBLISH ORDER
    $it_publish_order_condition = '';

    //HIDE ORDER STATUS
    $it_hide_os_condition = '';

    $sql_columns   = '';
    $sql_joins     = '';
    $sql_condition = '';


    if ($refund_status_type == "part_refunded") {
        $sql_columns = "
			it_posts.ID 						as refund_id

			,it_posts.post_status 				as refund_status
			,it_posts.post_date 				as refund_date
			,it_posts.post_excerpt 			as refund_note
			,it_posts.post_author				as customer_user

			,postmeta.meta_value 			as refund_amount
			,SUM(postmeta.meta_value) 		as total_amount

			,shop_order.ID 					as order_id
			,shop_order.ID 					as order_id_number
			,shop_order.post_status 		as order_status
			,shop_order.post_date 			as order_date
			,COUNT(it_posts.ID) 				as refund_count";

        //echo $group_by;
        $group_sql = "";
        switch ($group_by) {
            case "refund_id":
                $group_sql .= ", it_posts.ID as group_column";
                $group_sql .= ", it_posts.ID as order_column";
                break;
            case "order_id":
                $group_sql .= ", shop_order.ID as group_column";
                $group_sql .= ", shop_order.post_author as order_column";
                break;
            case "refunded":
                $group_sql .= ", it_posts.post_author as group_column";
                $group_sql .= ", it_posts.post_author as order_column";
                break;
            case "daily":
                $group_sql .= ", DATE(it_posts.post_date) as group_column";
                $group_sql .= ", DATE(it_posts.post_date) as group_date";
                $group_sql .= ", DATE(it_posts.post_date) as order_column";
                break;
            case "monthly":
                $group_sql .= ", CONCAT(MONTHNAME(it_posts.post_date) , ' ',YEAR(it_posts.post_date)) as group_column";
                $group_sql .= ", DATE(it_posts.post_date) as order_column";
                break;
            case "yearly":
                $group_sql .= ", YEAR(it_posts.post_date)as group_column";
                $group_sql .= ", DATE(it_posts.post_date) as order_column";
                break;
            default:
                $group_sql .= ", it_posts.ID as group_column";
                $group_sql .= ", it_posts.ID as order_column";
                break;

        }
        //echo  $group_sql;;
        $sql_columns .= $group_sql;

        $sql_joins = "

			{$wpdb->prefix}posts as it_posts

			LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	it_posts.ID LEFT JOIN  {$wpdb->prefix}posts as shop_order ON shop_order.ID	=	it_posts.post_parent";

    } else {

        $sql_columns = "
			SUM(postmeta.meta_value) 		as total_amount
			,shop_order.post_author			as customer_user
			,shop_order.ID 					as order_id
			,shop_order.ID 					as order_id_number
			,shop_order.post_status 		as order_status
			,shop_order.post_modified		as order_date
			,COUNT(shop_order.ID) 			as refund_count
			";

        $group_sql = "";
        switch ($group_by) {
            case "order_id":
                $group_sql .= ", shop_order.ID as group_column";
                $group_sql .= ", shop_order.ID as order_column";
                break;
            case "refunded":
                $group_sql .= ", shop_order.post_author as group_column";
                $group_sql .= ", shop_order.post_author as order_column";
                break;
            case "daily":
                $group_sql .= ", DATE(shop_order.post_modified) as group_column";
                $group_sql .= ", DATE(shop_order.post_modified) as group_date";
                $group_sql .= ", DATE(shop_order.post_modified) as order_column";
                break;
            case "monthly":
                $group_sql .= ", CONCAT(MONTHNAME(shop_order.post_modified) , ' ',YEAR(shop_order.post_modified)) as group_column";
                $group_sql .= ", DATE(shop_order.post_modified) as order_column";
                break;
            case "yearly":
                $group_sql .= ", YEAR(shop_order.post_modified)as group_column";
                $group_sql .= ", DATE(shop_order.post_modified) as order_column";
                break;
            default:
                $group_sql .= ", shop_order.ID as group_column";
                $group_sql .= ", shop_order.ID as order_column";
                break;

        }
        //echo  $group_sql;;
        $sql_columns .= $group_sql;

        $sql_joins = "
			{$wpdb->prefix}posts as shop_order
			LEFT JOIN  {$wpdb->prefix}postmeta as postmeta ON postmeta.post_id	=	shop_order.ID ";
    }


    if ($it_id_order_status && $it_id_order_status != "-1") {
        $it_id_order_status_join = "
				LEFT JOIN  {$wpdb->prefix}term_relationships	as it_term_relationships2 	ON it_term_relationships2.object_id	=	shop_order.ID
				LEFT JOIN  {$wpdb->prefix}term_taxonomy			as it_term_taxonomy2 		ON it_term_taxonomy2.term_taxonomy_id	=	it_term_relationships2.term_taxonomy_id
				LEFT JOIN  {$wpdb->prefix}terms					as terms2 				ON terms2.term_id					=	it_term_taxonomy2.term_id";
    }

    $sql_joins .= $it_id_order_status_join;

    if ($refund_status_type == "part_refunded") {
        $sql_condition = "it_posts.post_type = 'shop_order_refund' AND  postmeta.meta_key='_refund_amount'";

        if ($it_from_date != null && $it_to_date != null) {
            $it_from_date_condition = "
						AND (DATE(it_posts.post_date) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
        }

        if ($it_id_order_status && $it_id_order_status != "-1") {
            $it_refunded_id               = $this->it_get_woo_old_orders_status(array('refunded'),
                array('wc-refunded'));
            $it_refunded_id               = implode("," . $it_refunded_id);
            $it_id_order_status_condition = " AND terms2.term_id NOT IN (" . $it_refunded_id . ")";

            if ($it_id_order_status && $it_id_order_status != "-1") {
                $it_id_order_status_condition .= " AND terms2.term_id IN (" . $it_id_order_status . ")";
            }
        } else {
            $it_id_order_status_condition = " AND shop_order.post_status NOT IN ('wc-refunded')";
            if ($it_order_status && $it_order_status != '-1' and $it_order_status != "'-1'") {
                $it_id_order_status_condition .= " AND shop_order.post_status IN (" . $it_order_status . ")";
            }
        }
    } else {
        $sql_condition = " shop_order.post_type = 'shop_order' AND  postmeta.meta_key='_order_total'";

        if ($it_from_date != null && $it_to_date != null) {
            $it_from_date_condition = "
						AND (DATE(shop_order.post_modified) BETWEEN STR_TO_DATE('" . $it_from_date . "', '$date_format') and STR_TO_DATE('" . $it_to_date . "', '$date_format'))";
        }

        if ($it_id_order_status && $it_id_order_status != "-1") {
            $it_refunded_id               = $this->it_get_woo_old_orders_status(array('refunded'),
                array('wc-refunded'));
            $it_refunded_id               = implode("," . $it_refunded_id);
            $it_id_order_status_condition = " AND terms2.term_id IN (" . $it_refunded_id . ")";
        } else {
            $it_id_order_status_condition .= " AND shop_order.post_status IN ('wc-refunded')";
        }
    }

    if ($it_hide_os && $it_hide_os != '-1' and $it_hide_os != "'-1'") {
        $it_hide_os_condition = " AND shop_order.post_status NOT IN ('" . $it_hide_os . "')";
    }//changed 20141013

    $sql_group_by = " GROUP BY  group_column";

    $sql_order_by = " ORDER BY order_column DESC";

    $sql = "SELECT $sql_columns
				FROM $sql_joins
				WHERE $sql_condition $it_from_date_condition $it_id_order_status_condition
				$it_hide_os_condition $sql_group_by $sql_order_by";

    //echo $sql;

    if ($refund_status_type == 'part_refunded') {
        switch ($group_by) {
            case "refund_id":
                $this->refund_status = "refunddetails_part_refunded_main";
                break;

            case "order_id":
                $this->refund_status = "refunddetails_part_refunded_order_id";
                break;

            case "refunded":
                $this->refund_status = "refunddetails_part_refunded";
                break;

            case "daily":
                $this->refund_status = "refunddetails_part_refunded_daily";
                break;

            case "monthly":
                $this->refund_status = "refunddetails_part_refunded_monthly";
                break;

            case "yearly":
                $this->refund_status = "refunddetails_part_refunded_yearly";
                break;
        }
    } else {
        switch ($group_by) {
            case $group_by == "refund_id" || $group_by == "order_id":
                $this->refund_status = "refunddetails_status_refunded_main";
                break;

            case "refunded":
                $this->refund_status = "refunddetails_status_refunded";
                break;

            case "daily":
                $this->refund_status = "refunddetails_status_daily";
                break;

            case "monthly":
                $this->refund_status = "refunddetails_status_monthly";
                break;

            case "yearly":
                $this->refund_status = "refunddetails_status_yearly";
                break;
        }
    }

    //echo $sql;

} elseif ($file_used == "data_table") {

    ////ADDE IN VER4.0
    /// TOTAL ROWS VARIABLES
    $result_count = $order_count = $total_amnt = 0;

    foreach ($this->results as $items) {
        $index_cols = 0;
        //for($i=1; $i<=20 ; $i++){
        $datatable_value .= ("<tr>");

        ////ADDE IN VER4.0
        /// TOTAL ROWS
        $result_count++;
        $order_count += $items->refund_count;
        $total_amnt  += $items->total_amount;


        $type_refund = $this->refund_status;


        switch ($type_refund) {
            case "refunddetails_status_refunded_main":
                {
                    //Order ID
                    $order_id = $items->order_id;

                    //CUSTOM WORK - 15862
                    if (is_array(__CUSTOMWORK_ID__) && in_array('15862', __CUSTOMWORK_ID__)) {
                        $order_id = get_post_meta($order_id, '_order_number_formatted', true);
                    }

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $order_id;
                    $datatable_value .= ("</td>");

                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->order_date));
                    $datatable_value .= ("</td>");

                    //Order Status
                    $it_table_value = isset($items->order_status) ? $items->order_status : '';

                    if ($it_table_value == 'wc-completed') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } elseif ($it_table_value == 'wc-refunded') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } else {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    }

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= str_replace("Wc-", "", $it_table_value);
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_status_refunded":
                {

                    $customer = new WP_User($items->customer_user);
                    //Refund By
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $customer->user_nicename;// $items->customer_user;
                    //$this->get_items_id_list($original_data,'customer_user','','string');
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_status_daily":
                {

                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->order_date));
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_status_monthly":
                {
                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->order_date));
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_status_yearly":
                {
                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->group_column;
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            ///////////////////////

            case "refunddetails_part_refunded_main":
                {
                    //Refund ID
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_id;
                    $datatable_value .= ("</td>");

                    //Refund Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->refund_date));
                    $datatable_value .= ("</td>");

                    //Refund Status
                    $it_table_value = isset($items->refund_status) ? $items->refund_status : '';

                    if ($it_table_value == 'wc-completed') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } elseif ($it_table_value == 'wc-refunded') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } else {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    }

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= str_replace("Wc-", "", $it_table_value);
                    $datatable_value .= ("</td>");


                    //Refund By
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->customer_user; //$this->get_items_id_list($original_data,'customer_user','','string');
                    $datatable_value .= ("</td>");

                    //Order id
                    $order_id = $items->order_id;

                    //CUSTOM WORK - 15862
                    if (is_array(__CUSTOMWORK_ID__) && in_array('15862', __CUSTOMWORK_ID__)) {
                        $order_id = get_post_meta($order_id, '_order_number_formatted', true);
                    }

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $order_id;
                    $datatable_value .= ("</td>");


                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->order_date));
                    $datatable_value .= ("</td>");

                    //Order Status
                    $it_table_value = isset($items->order_status) ? $items->order_status : '';

                    if ($it_table_value == 'wc-completed') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } elseif ($it_table_value == 'wc-refunded') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } else {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    }

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= str_replace("Wc-", "", $it_table_value);
                    $datatable_value .= ("</td>");


                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_note;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");

                }
                break;

            case "refunddetails_part_refunded_order_id":
                {
                    //Order ID
                    $order_id = $items->order_id;

                    //CUSTOM WORK - 15862
                    if (is_array(__CUSTOMWORK_ID__) && in_array('15862', __CUSTOMWORK_ID__)) {
                        $order_id = get_post_meta($order_id, '_order_number_formatted', true);
                    }

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $order_id;
                    $datatable_value .= ("</td>");

                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->order_date));
                    $datatable_value .= ("</td>");

                    //Order Status
                    $it_table_value = isset($items->order_status) ? $items->order_status : '';

                    if ($it_table_value == 'wc-completed') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } elseif ($it_table_value == 'wc-refunded') {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    } else {
                        $it_table_value = '<span class="awr-order-status awr-order-status-' . sanitize_title($it_table_value) . '" >' . ucwords(esc_html(sanitize_text_field($it_table_value),
                                'it_report_wcreport_textdomain')) . '</span>';
                    }

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= str_replace("Wc-", "", $it_table_value);
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_part_refunded":
                {

                    //Refund By
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->customer_user; //$this->get_items_id_list($original_data,'customer_user','','string');
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_part_refunded_daily":
                {

                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->refund_date));
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_part_refunded_monthly":
                {
                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= gmdate($date_format, strtotime($items->refund_date));
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;

            case "refunddetails_part_refunded_yearly":
                {
                    //Order Date
                    $date_format = get_option('date_format');

                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->group_column;
                    $datatable_value .= ("</td>");

                    //Order Counts
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->refund_count;
                    $datatable_value .= ("</td>");

                    //Refund Amount
                    $display_class = '';
                    if ($this->table_cols[$index_cols++]['status'] == 'hide') {
                        $display_class = 'display:none';
                    }
                    $datatable_value .= ("<td style='" . $display_class . "'>");
                    $datatable_value .= $items->total_amount == 0 ? $this->price(0) : $this->price($items->total_amount);
                    $datatable_value .= ("</td>");
                }
                break;
        }

        $datatable_value .= ("</tr>");
    }

    ////ADDE IN VER4.0
    /// TOTAL ROWS
    $table_name_total       = $table_name;
    $this->table_cols_total = $this->table_columns_total($table_name_total);
    $datatable_value_total  = '';

    $datatable_value_total .= ("<tr>");
    $datatable_value_total .= "<td>$result_count</td>";
    $datatable_value_total .= "<td>$order_count</td>";
    $datatable_value_total .= "<td>" . (($total_amnt) == 0 ? $this->price(0) : $this->price($total_amnt)) . "</td>";
    $datatable_value_total .= ("</tr>");

} elseif ($file_used == "search_form") {
    ?>
    <form class='alldetails search_form_report' action='' method='post'>
        <input type='hidden' name='action' value='submit-form'/>
        <div class="row">

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php esc_html_e('From Date', 'it_report_wcreport_textdomain'); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="it_from_date" id="pwr_from_date" type="text" readonly='true' class="datepick"/>
            </div>

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php esc_html_e('To Date', 'it_report_wcreport_textdomain'); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-calendar"></i></span>
                <input name="it_to_date" id="pwr_to_date" type="text" readonly='true' class="datepick"/>

                <input type="hidden" name="it_id_order_status[]" id="it_id_order_status" value="-1">
                <input type="hidden" name="it_orders_status[]" id="order_status"
                       value="<?php echo esc_attr($this->it_shop_status); ?>">
            </div>

            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php esc_html_e('Refund Type', 'it_report_wcreport_textdomain'); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-check"></i></span>
                <select name="it_refund_status_type" id="refund_type" class="refund_type">
                    <option value="part_refunded">Part Refund - Order status not refunded</option>
                    <option value="status_refunded" selected="selected">Order Status Refunded</option>
                </select>
            </div>


            <!--<div class="col-md-6">
                    <div class="awr-form-title">
                        <?php esc_html_e('Show Refund Note', 'it_report_wcreport_textdomain'); ?>
                    </div>

                    <div class="col-md-9 sor">
                        <input name="it_note_show" type="checkbox" class="show_note"/>
                    </div>
                </div>-->
            <div class="col-md-6">
                <div class="awr-form-title">
                    <?php esc_html_e('Group By', 'it_report_wcreport_textdomain'); ?>
                </div>
                <span class="awr-form-icon"><i class="fa fa-suitcase"></i></span>
                <select name="it_groupby" id="it_groupby">
                    <option value="refund_id" selected="selected">Refund ID</option>
                    <option value="order_id">Order ID</option>
                    <option value="refunded">Refunded</option>
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>

            </div>

        </div>


        <div class="col-md-12">
            <?php
            $it_hide_os       = $this->otder_status_hide;
            $it_publish_order = 'no';

            $data_format = $this->it_get_woo_requests_links('date_format', get_option('date_format'), true);
            ?>
            <input type="hidden" name="list_parent_category" value="">
            <input type="hidden" name="it_category_id" value="-1">
            <input type="hidden" name="group_by_parent_cat" value="0">

            <input type="hidden" name="it_hide_os" id="it_hide_os" value="<?php echo esc_html($it_hide_os); ?>"/>

            <input type="hidden" name="date_format" id="date_format" value="<?php echo esc_html($data_format); ?>"/>

            <input type="hidden" name="table_names" value="<?php echo esc_html($table_name); ?>"/>
            <div class="fetch_form_loading search-form-loading"></div>
            <button type="submit" value="Search" class="button-primary"><i class="fa fa-search"></i>
                <span><?php echo esc_html__('Search', 'it_report_wcreport_textdomain'); ?></span></button>
            <button type="button" value="Reset" class="button-secondary form_reset_btn"><i
                        class="fa fa-reply"></i><span><?php echo esc_html__('Reset Form',
                        'it_report_wcreport_textdomain'); ?></span></button>
        </div>

    </form>
    <?php
}

?>
