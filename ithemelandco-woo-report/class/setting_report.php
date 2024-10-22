<?php

function get_operation_select($fields)
{
    $operators = array(
        "Numeric"     => array(
            "eq" => esc_html__('EQUALS', 'it_report_wcreport_textdomain'),
            "neq" => esc_html__('NOT EQUALS', 'it_report_wcreport_textdomain'),
            "lt" => esc_html__('LESS THEN', 'it_report_wcreport_textdomain'),
            "gt" => esc_html__('MORE THEN', 'it_report_wcreport_textdomain'),
            "meq" => esc_html__('EQUAL AND MORE', 'it_report_wcreport_textdomain'),
            "leq" => esc_html__('LESS AND EQUAL', 'it_report_wcreport_textdomain'),
        ),
        "String"    =>  array(
            "elike" => esc_html__('EXACTLY LIKE', 'it_report_wcreport_textdomain'),
            "like" => esc_html__('LIKE', 'it_report_wcreport_textdomain'),
        ),
    );
    $operators_options = '';
    foreach ($operators as $key => $value) {
        $operators_options .= '<optgroup label="' . $key . ' operators">';
        foreach ($value as $k => $v) {

            $selected = "";
            if ($fields == $k) {
                $selected = "SELECTED";
            }
            $operators_options .= '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
        }
        $operators_options .= '</optgroup>';
    }
    return $operators_options;
}

$it_report_options_part = array(

    array(
        'id' => 'it_report_metaboxname_fields_options_general_setting',
        'title' => esc_html__('General Settings', 'it_report_wcreport_textdomain'),
        'icon' => '',
        'variable' => 'it_report_metaboxname_fields_options_general_setting'
    ),
    array(
        'id' => 'it_report_metaboxname_fields_options_email_setting',
        'title' => esc_html__('Email', 'it_report_wcreport_textdomain'),
        'icon' => '',
        'variable' => 'it_report_metaboxname_fields_options_email_setting'
    ),
    array(
        'id' => 'it_report_metaboxname_fields_options_search_form',
        'title' => esc_html__('Add-ons', 'it_report_wcreport_textdomain'),
        'icon' => '',
        'variable' => 'it_report_metaboxname_fields_options_search_form'
    ),
    array(
        'id' => 'it_report_metaboxname_fields_options_projected',
        'title' => esc_html__('Target', 'it_report_wcreport_textdomain'),
        'icon' => '',
        'variable' => 'it_report_metaboxname_fields_options_projected'
    ),
    array(
        'id' => 'it_report_metaboxname_fields_options_translate',
        'title' => esc_html__('Translate', 'it_report_wcreport_textdomain'),
        'icon' => '',
        'variable' => 'it_report_metaboxname_fields_options_translate'
    )
);

//	if(!defined('__IT_TAX_FIELD_ADD_ON__') && !defined('__IT_PO_ADD_ON__') && !defined('__IT_BRANDS_ADD_ON__'))
//	{
//		unset($it_report_options_part[2]);
//	}



//GENERAL SETTING
$it_report_metaboxname_fields_options_general_setting = array(
    array(
        'label'    => esc_html__('System Settings', 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_sys_search',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_sys_search',
        'type'    => 'notype',
    ),
    array(
        'label'    => esc_html__('Shop Order Status', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set default shop order status, Selected status will be used for calculating salse amount. Default statuses : completed, on-hold and processing', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'order_status',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'order_status',
        'type'    => 'order_status',
    ),

    array(
        'label'    => esc_html__('Hide Trash Order', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'otder_status_hide',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'otder_status_hide',
        'type'    => 'checkbox',
    ),
    array(
        'label'    => esc_html__('Invoice Logo', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Upload an image as Invoice Pdf logo', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'invoice_logo',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'invoice_logo',
        'type'    => 'upload',
    ),
    array(
        'label'    => esc_html__('Invoice Footer Text', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set footer text for invoice pdf', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'invoice_footer_text',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'invoice_footer_text',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__('Close Search Automatically', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please. Yes, Please. If checked, the search form will be closed automatically after submission', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'close_search',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'close_search',
        'type'    => 'checkbox',
    ),
    array(
        'label'    => esc_html__('Dashboard Setting', 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
        'type'    => 'notype',
    ),
    array(
        'label'    => esc_html__('Dashboard Status', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('You can enable/disable dashboard and set another report as default report', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status',
        'type'    => 'select',
        'options'    => array(
            'one' => array(
                'label' => esc_html__('Enable, please', 'it_report_wcreport_textdomain'),
                'value' => 'true',
            ),
            'two' => array(
                'label' => esc_html__('Disable, please', 'it_report_wcreport_textdomain'),
                'value' => 'false',
            ),
        ),
    ),
    // array(
    // 	'label'	=> esc_html__('Alternative Report','it_report_wcreport_textdomain'),
    // 	'desc'	=> esc_html__('Choose which one of reports that you want to display insted dashboard','it_report_wcreport_textdomain'),
    // 	'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_alt',
    // 	'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_alt',
    // 	'type'	=>'reports',
    // 	'dependency' => array(
    // 		'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status'),
    // 		__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status' => array('select','false')
    // 	),
    // ),
    array(
        'label'    => esc_html__('Dashboard Date', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Do you want to set customize date for dashboard search ?', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customize_date',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customize_date',
        'type'    => 'select',
        'options'    => array(
            'one' => array(
                'label' => esc_html__('No, I want use default date', 'it_report_wcreport_textdomain'),
                'value' => 'false',
            ),
            'two' => array(
                'label' => esc_html__('Yes, please', 'it_report_wcreport_textdomain'),
                'value' => 'true',
            ),
        ),
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('From Date', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set from date for dashboard search', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'from_date',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'from_date',
        'ids'    => 'pwr_from_date',
        'type'    => 'datepicker',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status', __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customize_date'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customize_date' => array('select', 'true'),
        ),
    ),
    array(
        'label'    => esc_html__('To Date', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set to date for dashboard search', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'to_date',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'to_date',
        'ids'    => 'pwr_to_date',
        'type'    => 'datepicker',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status', __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customize_date'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customize_date' => array('select', 'true'),
        ),
    ),
    // array(
    // 	'label'	=> esc_html__('Disable Map in Dashboard ?','it_report_wcreport_textdomain'),
    // 	'desc'	=> esc_html__('Yes, Please','it_report_wcreport_textdomain'),
    // 	'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_map',
    // 	'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_map',
    // 	'type'	=>'checkbox',
    // 	'dependency' => array(
    // 		'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status'),
    // 		__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status' => array('select','true')
    // 	),
    // ),
    array(
        'label'    => esc_html__('Disable Charts in Dashboard ?', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'disable_chart',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'disable_chart',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Dashboard Box Count', 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
        'type'    => 'notype',
    ),
    array(
        'label'    => esc_html__('Recent Order', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Recent Order table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'recent_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'recent_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Top Product', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Top Product table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_product_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_product_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Top Category', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Top Category table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_category_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_category_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Top Customer', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Top Customer table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_customer_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_customer_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Top Billing Country', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Top Billing Country table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_country_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_country_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Top State Country', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Top State Country table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_state_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_state_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Top Payment Gateway', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Top Payment Gateway table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_gateway_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_gateway_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Top Coupon', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Minimum page number for Top Coupon table', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_coupon_post_per_page',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'top_coupon_post_per_page',
        'type'    => 'numeric',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dashboard_status' => array('select', 'true')
        ),
    ),

);

//CUSTOM WORK - 12679
if (is_array(__CUSTOMWORK_ID__) && in_array('12679', __CUSTOMWORK_ID__)) {
    $extra_cols = array();
    $extra_cols[] = array(
        'label'    => esc_html__('Clinic Type', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set default shop order status, Selected status will be used for calculating salse amount. Default statuses : completed, on-hold and processing', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'clinic_type',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'clinic_type',
        'type'    => 'text',
    );
    array_splice($it_report_metaboxname_fields_options_general_setting, 3, 0, $extra_cols);
}


//EMAIL SETTING
$it_report_metaboxname_fields_options_email_setting = array(

    array(
        'label'    => '<span style="color:#d97c7c">' . esc_html__('Automatic Email (Available in PRO Version)', 'it_report_wcreport_textdomain') . '</span>',
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'automatic_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'automatic_email',
        'type'    => 'notype',
    ),
    array(
        'label'    => esc_html__('Active Email Reporting', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email',
        'type'    => 'checkbox',
    ),

    array(
        'label'    => esc_html__('Email Today Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'today_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'today_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Yesterday Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'yesterday_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'yesterday_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Current Week Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_week_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_week_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Last Week Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_week_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_week_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Current Month Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_month_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_month_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Last Month Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_month_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_month_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Current Year Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_year_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cur_year_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Last Year Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_year_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'last_year_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Till Today Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'till_today_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'till_today_email',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Email Total/Other/Today Summary Report', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'total_summary',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'total_summary',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),

    //CUSOM WORK - 4061
    array(
        'label'    => esc_html__('Email Purchased Product by Customer', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Yes, Please', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'product_by_customer',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'product_by_customer',
        'type'    => 'checkbox',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),

    array(
        'label'    => esc_html__('Email Send To', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Receiver Email', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sendto_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sendto_email',
        'type'    => 'text',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('From Name', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Sender Name', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'from_name',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'from_name',
        'type'    => 'text',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('From Email', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Sender Email', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sendfrom_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sendfrom_email',
        'type'    => 'text',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),
    array(
        'label'    => esc_html__('Subject', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set Email Subject', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'subject_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'subject_email',
        'type'    => 'text',
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),

    array(
        'label'    => esc_html__('Email Schedule', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Set the email schedule', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'email_schedule',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'email_schedule',
        'type'    => 'select',
        'options'    => array(
            '00' => array(
                'label' => esc_html__('Select One', 'it_report_wcreport_textdomain'),
                'value' => '0',
            ),
            '0' => array(
                'label' => esc_html__('Once Hourly', 'it_report_wcreport_textdomain'),
                'value' => 'it_schd_hourly',
            ),
            '1' => array(
                'label' => esc_html__('Once Daily', 'it_report_wcreport_textdomain'),
                'value' => 'it_schd_daily',
            ),
            '2' => array(
                'label' => esc_html__('Once Weekly', 'it_report_wcreport_textdomain'),
                'value' => 'it_schd_weekly',
            ),
            '3' => array(
                'label' => esc_html__('Once Monthly', 'it_report_wcreport_textdomain'),
                'value' => 'it_schd_monthly',
            ),
            '4' => array(
                'label' => esc_html__('Twice Hourly', 'it_report_wcreport_textdomain'),
                'value' => 'it_schd_twicehourly',
            ),
            '5' => array(
                'label' => esc_html__('Twice Daily', 'it_report_wcreport_textdomain'),
                'value' => 'it_schd_twicedaily',
            ),
            '6' => array(
                'label' => esc_html__('Twice Weekly', 'it_report_wcreport_textdomain'),
                'value' => 'it_schd_twiceweekly',
            )

        ),
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email' => array('checkbox', 'true')
        ),
    ),

    array(
        'label'    => esc_html__('Optimize Email Format', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Check this fields, if your email is unformatted.', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'optimize_email',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'optimize_email',
        'type'    => 'checkbox',
    ),


);



//ADD-ONS SETTING
$it_brands_fields = array();
if (defined("__IT_BRANDS_ADD_ON__")) {
    $it_brands_fields = array(
        array(
            'label'    => '<span style="color:#d97c7c">' . esc_html__("Brands Add-ons (Available in PRO Version)", 'it_report_wcreport_textdomain') . '</span>',
            'desc'    => esc_html__("Set the Brands add-ons settings.", 'it_report_wcreport_textdomain'),
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_brands_addons',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_brands_addons',
            'type'    => 'notype',
        ),
        array(
            'label'    => esc_html__('Enable Brand Taxonomy', 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__('Do you want to enable brand taxonomy ?', 'it_report_wcreport_textdomain'),
            'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand',
            'type'    => 'select',
            'options'    => array(
                '1' => array(
                    'label' => esc_html__('No, at all', 'it_report_wcreport_textdomain'),
                    'value' => 'no',
                ),
                '2' => array(
                    'label' => esc_html__('Yes, I want use iThemelandCo Plugin', 'it_report_wcreport_textdomain'),
                    'value' => 'yes_this',
                ),
                '3' => array(
                    'label' => esc_html__('Yes, I have Brands plugin', 'it_report_wcreport_textdomain'),
                    'value' => 'yes_another',
                ),

            )
        ),
        array(
            'label'    => esc_html__('Plugin', 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__('Choose your "Brands" plugin name.', 'it_report_wcreport_textdomain'),
            'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brands_plugin',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brands_plugin',
            'type'    => 'select',
            'options'    => array(
                '1' => array(
                    'label' => esc_html__('WooCommerce Brands By Woothemes', 'it_report_wcreport_textdomain'),
                    'value' => 'product_brand',
                ),
                '2' => array(
                    'label' => esc_html__('Ultimate WooCommerce Brands Plugin', 'it_report_wcreport_textdomain'),
                    'value' => 'product_brand',
                ),
                '3' => array(
                    'label' => esc_html__('YITH WOOCOMMERCE BRANDS ADD-ON', 'it_report_wcreport_textdomain'),
                    'value' => 'yith_product_brand',
                ),
                '4' => array(
                    'label' => esc_html__('Proword WooCommerce Brand', 'it_report_wcreport_textdomain'),
                    'value' => 'product_brand',
                ),
                '5' => array(
                    'label' => esc_html__('Other Plugin', 'it_report_wcreport_textdomain'),
                    'value' => 'other',
                ),
            ),
            'dependency' => array(
                'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand'),
                __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand' => array('select', 'yes_another'),
            ),
        ),
        array(
            'label'    => esc_html__('Taxonomy Slug', 'it_report_wcreport_textdomain'),
            'desc'    => __('Set the taxonomy slug plugin. <br /><strong>E.g: </strong>WooCommerce Brands by Woothemes : product_brand ', 'it_report_wcreport_textdomain'),
            'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_slug',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_slug',
            'type'    => 'text',

            'dependency' => array(
                'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand', __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brands_plugin'),
                __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand' => array('select', 'yes_another'),
                __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brands_plugin' => array('select', 'other')
            ),
        ),
        array(
            'label'    => esc_html__('Brand Label', 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__('Set brand label.', 'it_report_wcreport_textdomain'),
            'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_label',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_label',
            'type'    => 'text',

            'dependency' => array(
                'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand'),
                __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand' => array('select', 'yes_another', 'yes_this'),
            ),
        ),
        array(
            'label'    => esc_html__('Brand Thumnail', 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__('Show brands as thumbnail in grid column.', 'it_report_wcreport_textdomain'),
            'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_thumb',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'brand_thumb',
            'type'    => 'checkbox',

            'dependency' => array(
                'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand'),
                __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_brand' => array('select', 'yes_this'),
            ),
        )
    );
}

////ADDED IN VER4.0
/// PRODUCT OPTIONS CUSTOM FIELDS
$product_options_fields = array();
if (defined("__IT_PO_ADD_ON__") && defined("THEMECOMPLETE_EPO_VERSION")) {
    $product_options_fields = array(
        ////ADDED IN VER4.0
        /// PRODUCT OPTIONS CUSTOM FIELDS
        array(
            'label'    => esc_html__("Product Options Plugin", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("You can add fields generated by Product Options Plugin to Some Reports", 'it_report_wcreport_textdomain'),
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_sectio',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_sectio',
            'type'    => 'notype',
        ),
        array(
            'label' => '',
            'desc'  => esc_html__('Select your custom fields for serach query, This fields just will be displayed in All Orders Reort (Based on Taxonomies)', 'it_report_wcreport_textdomain'),
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_custom_fields',
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_custom_fields',
            'type'  => 'tab_multi_side',
        ),
        array(
            'label'    => esc_html__("Checkout Options Plugin", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("You can add fields generated by Checkout Options Plugin to Some Reports", 'it_report_wcreport_textdomain'),
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_checkout_sectio',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_checkout_sectio',
            'type'    => 'notype',
        ),
        array(
            'label' => '',
            'desc'  => esc_html__('Select your custom fields for serach query, This fields just will be displayed in All Orders Reort (Based on Taxonomies)', 'it_report_wcreport_textdomain'),
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_checkout_custom_fields',
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_checkout_custom_fields',
            'type'  => 'tab_multi_side_checkout',
        ),
    );
}


////ADDED IN VER4.0
/// TAX CUSTOM FIELDS
$custom_tax_fields = array();
if (defined("__IT_TAX_FIELD_ADD_ON__")) {
    $custom_tax_fields = array(
        array(
            'label'    => esc_html__("Custom Taxonomy & Fields", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("In this section you can choose which WooCommerce Taxonomy and which Custom fields appear in Report.", 'it_report_wcreport_textdomain'),
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
            'type'    => 'notype',
        ),
        array(
            'label'    => esc_html__('Custom Taxonmy', 'it_report_wcreport_textdomain'),
            'desc'    => "",
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
            'type'    => 'custom_search_items',
        ),
        array(
            'label' => esc_html__('Custom Fields', 'it_report_wcreport_textdomain'),
            'desc'  => esc_html__('Select your custom fields for serach query, This fields just will be displayed in All Orders Reort (Based on Taxonomies)', 'it_report_wcreport_textdomain') . '<br />' . esc_html__('<strong>Note : </strong> Individual fields are used for specific products , so you can set to display them in data table only, Not as filter.', 'it_report_wcreport_textdomain'),
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_fields',
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_fields',
            'type'  => 'multi_side',
        ),
    );
}


////ADDED IN VER4.1
/// PRODUCT OPTIONS CUSTOM FIELDS
$custom_int_fields = array();
if (defined("__IT_INT_REPORTS_ADD_ON__")) {
    $custom_int_fields = array(
        ////ADDED IN VER4.0
        /// PRODUCT OPTIONS CUSTOM FIELDS
        array(
            'label'    => esc_html__("INTELLIGENCE REPORTS - RFM - AUTOMATION", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("Customers who have purchased more recently, more frequently, and have spent more money, are likelier to buy again. But those who haven't, are less valuable for the company and therefore, likely to churn. By giving points, for various hierarchies you can easily find out who are your best customers.", 'it_report_wcreport_textdomain'),
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_sectio',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_sectio',
            'type'    => 'notype',
        ),

        array(
            'label'    => esc_html__("Recency Points", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("How recently did the customer purchase?", 'it_report_wcreport_textdomain'),
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_recency_point',
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_recency_point',
            'type'  => 'recency_numeric_int',
        ),
        array(
            'label'    => esc_html__("Frequency Points", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("How often do they purchase?", 'it_report_wcreport_textdomain'),
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_frequency_point',
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_frequency_point',
            'type'  => 'frequency_numeric_int',
        ),
        array(
            'label'    => esc_html__("Monetary Value", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("How much do they spend?", 'it_report_wcreport_textdomain'),
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_monetary_point',
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_monetary_point',
            'type'  => 'monetary_numeric_int',
        ),
    );
}



//CUSTOM WORK - 12300
/// TICKERA CUSOTM FIELDS
$tickera_fields = array();
if (defined("__IT_TICKERA_ADD_ON__")) {
    $tickera_fields = array(
        array(
            'label'    => esc_html__("Tickera Plugin", 'it_report_wcreport_textdomain'),
            'desc'    => esc_html__("You can add fields generated by Tickera Plugin to Some Reports", 'it_report_wcreport_textdomain'),
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'tickera_sectio',
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'tickera_sectio',
            'type'    => 'notype',
        ),
        array(
            'label' => '',
            'desc'  => esc_html__('Select your custom fields for serach query, This fields just will be displayed in All Orders Reort.', 'it_report_wcreport_textdomain'),
            'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'tickera_custom_fields',
            'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'tickera_custom_fields',
            'type'  => 'tab_multi_side_tickera',
        ),
    );
}




$it_report_metaboxname_fields_options_search_form = array(

    //		array(
    //			'label'	=> esc_html__("Currency Switcher - Multiple Currency",'it_report_wcreport_textdomain'),
    //			'desc'	=> '',
    //			'name'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_default_search',
    //			'id'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_default_search',
    //			'type'	=> 'notype',
    //		),
    //		array(
    //			'label'	=> esc_html__('Enable Currency Swither','it_report_wcreport_textdomain'),
    //			'desc'	=> esc_html__('If you have "WooCommerce Currency Switcher" plugin, You price will be convert to your admin currency','it_report_wcreport_textdomain'),
    //			'name'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'currency_switcher',
    //			'id'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'currency_switcher',
    //			'type'	=> 'checkbox',
    //		),




    //COST OF GOOD
    // array(
    // 	'label'	=> esc_html__('Cost of Good','it_report_wcreport_textdomain'),
    // 	'desc'	=> "",
    // 	'name'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_enable_cog',
    // 	'id'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'set_enable_cog',
    // 	'type'	=> 'notype',
    // ),
    array(
        'label'    => esc_html__('Enable Field', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Do you want to enable custom field of cost of god ?', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog',
        'type'    => 'select',
        'options'    => array(
            'one' => array(
                'label' => esc_html__('No, at all', 'it_report_wcreport_textdomain'),
                'value' => 'no',
            ),
            'two' => array(
                'label' => esc_html__('Yes, I have Cost of Good plugin', 'it_report_wcreport_textdomain'),
                'value' => 'yes_another',
            ),
            //				'three' => array(
            //					'label' => esc_html__('Yes, I want use this Cost of Good','it_report_wcreport_textdomain'),
            //					'value' => 'yes_this',
            //				),
        )
    ),


    array(
        'label'    => esc_html__('Plugin', 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__('Choose your "Cost of Goods/Profit" plugin name.', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_plugin',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_plugin',
        'type'    => 'select',
        'options'    => array(
            'one' => array(
                'label' => esc_html__('WooCommerce Cost of Goods by Woothemes', 'it_report_wcreport_textdomain'),
                'value' => 'woo_profit',
            ),
            'two' => array(
                'label' => esc_html__('WooCommerce Profit of Sales Report by IndoWebKreasi', 'it_report_wcreport_textdomain'),
                'value' => 'indo_profit',
            ),
            //				'three' => array(
            //					'label' => esc_html__('Other Plugin','it_report_wcreport_textdomain'),
            //					'value' => 'other',
            //				),
        ),
        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog' => array('select', 'yes_another'),
        ),
    ),

    array(
        'label'    => esc_html__('Custom field 1', 'it_report_wcreport_textdomain'),
        'desc'    => __('Set the custom field of plugin. <br /><strong>e.g: </strong>WooCommerce Cost of Goods by Woothemes : _wc_cog_cost <br />WooCommerce Profit of Sales Report by IndoWebKreasi : _posr_cost_of_good', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_field',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_field',
        'type'    => 'text',

        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog', __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_plugin'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog' => array('select', 'yes_another'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_plugin' => array('select', 'other'),
        ),
    ),
    array(
        'label'    => esc_html__('Custom field 2', 'it_report_wcreport_textdomain'),
        'desc'    => __('Set the total custom field of plugin. <br /><strong>e.g: </strong>WooCommerce Cost of Goods by Woothemes : _wc_cog_item_total_cost  <br />WooCommerce Profit of Sales Report by IndoWebKreasi : _posr_line_cog_total', 'it_report_wcreport_textdomain'),
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_field_total',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_field_total',
        'type'    => 'text',

        'dependency' => array(
            'parent_id' => array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog', __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_plugin'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'enable_cog' => array('select', 'yes_another'),
            __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'cog_plugin' => array('select', 'other'),
        ),
    ),
);

if (defined("__IT_BRANDS_ADD_ON__")) {
    global $it_rpt_main_class;
    $it_rpt_main_class->array_insert($it_report_metaboxname_fields_options_search_form, $it_brands_fields, 0);
    //$menu_fields=$it_rpt_main_class->array_insert_after("4",$it_report_metaboxname_fields_options_search_form,"5",$it_brands_fields);
}

if (defined("__IT_PO_ADD_ON__") && defined("THEMECOMPLETE_EPO_VERSION")) {
    global $it_rpt_main_class;
    $it_rpt_main_class->array_insert($it_report_metaboxname_fields_options_search_form, $product_options_fields, 0);
    //$menu_fields=$it_rpt_main_class->array_insert_after("2",$it_report_metaboxname_fields_options_search_form,"3",$product_options_fields);
}

if (defined("__IT_TAX_FIELD_ADD_ON__")) {
    global $it_rpt_main_class;
    $it_rpt_main_class->array_insert($it_report_metaboxname_fields_options_search_form, $custom_tax_fields, 0);
    //$menu_fields=$it_rpt_main_class->array_insert_after("2",$it_report_metaboxname_fields_options_search_form,"3",$custom_tax_fields);
}


if (defined("__IT_INT_REPORTS_ADD_ON__")) {
    global $it_rpt_main_class;
    $it_rpt_main_class->array_insert($it_report_metaboxname_fields_options_search_form, $custom_int_fields, 0);
}

//CUSTOM WORK - 12300
if (defined("__IT_TICKERA_ADD_ON__")) {
    global $it_rpt_main_class;
    $it_rpt_main_class->array_insert($it_report_metaboxname_fields_options_search_form, $tickera_fields, 0);
    //$menu_fields=$it_rpt_main_class->array_insert_after("2",$it_report_metaboxname_fields_options_search_form,"3",$tickera_fields);
}

//FETCH YEARS
global $wpdb;

$order_date = '';
$results = $wpdb->get_results($wpdb->prepare("SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN (%s) GROUP BY it_posts.ID Order By it_posts.post_date ASC LIMIT 1", 'trash'));

$first_date = '';
if (isset($results[0]))
    $first_date = $results[0]->order_date;

if ($first_date == '') {
    $first_date = gmdate("Y-m-d");
    $first_date = substr($first_date, 0, 4);
} else {
    $first_date = substr($first_date, 0, 4);
}

$order_date = '';
$results = $wpdb->get_results($wpdb->prepare("SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN (%s) GROUP BY it_posts.ID Order By it_posts.post_date DESC LIMIT 1", 'trash'));

$it_to_date = '';
if (isset($results[0]))
    $it_to_date = $results[0]->order_date;

if ($it_to_date == '') {
    $it_to_date = gmdate("Y-m-d");
    $it_to_date = substr($it_to_date, 0, 4);
} else {
    $it_to_date = substr($it_to_date, 0, 4);
}




$cur_year = gmdate("Y-m-d");
$cur_year = substr($cur_year, 0, 4);

$option = "";
for ($year = ($first_date - 5); $year < ($it_to_date + 10); $year++) {
    $year_arr[$year] = array(
        'label'    => $year,
        'value'    => $year
    );
}


//SEARCH OPTION
$it_report_metaboxname_fields_options_projected = array(
    array(
        'label'    => '<span style="color:#d97c7c">' . esc_html__('Project Sale (Available in PRO Version)', 'it_report_wcreport_textdomain') . '</span>',
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_search',
        'type'    => 'notype',
    ),
    array(
        'label' => esc_html__('Projected Sales Year', 'it_report_wcreport_textdomain'),
        'desc'  => esc_html__('Choose Year', 'it_report_wcreport_textdomain'),
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'projected_year',
        'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'projected_year',
        'type'  => 'select_year',
        'options'    => $year_arr,
    ),
    array(
        'label'    => esc_html__("Set Sales of monthes", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_year_sale',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_year_sale',
        'type'    => 'notype',
    ),
    array(
        'label'    => "",
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'monthes',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'monthes',
        'type'    => 'monthes',
    ),

);

//TRANSLATE
$it_report_metaboxname_fields_options_translate = array(
    array(
        'label'    => esc_html__("Set Your Translate(s)", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_translate',
        'type'    => 'notype',
    ),
    array(
        'label'    => esc_html__("Set Translate for January", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'jan_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'jan_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for February", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'feb_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'feb_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for March", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'mar_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'mar_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for April", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'apr_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'apr_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for May", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'may_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'may_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for June", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'jun_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'jun_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for July", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'jul_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'jul_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for August", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'aug_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'aug_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for September", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sep_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'sep_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for October", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'oct_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'oct_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for November", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'nov_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'nov_translate',
        'type'    => 'text',
    ),
    array(
        'label'    => esc_html__("Set Translate for December", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dec_translate',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'dec_translate',
        'type'    => 'text',
    ),

);

//LICENSE INFO
$it_report_metaboxname_fields_options_license = array(
    array(
        'label'    => esc_html__("Customization", 'it_report_wcreport_textdomain'),
        'desc'    => "",
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'plugin_info',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'plugin_info',
        'type'    => 'notype',
    ),
    array(
        'label'    => esc_html__("Set Your Custom Work ID", 'it_report_wcreport_textdomain'),
        'desc'    => esc_html__("Set Your Custom Work ID if You Ordered the Custom Work.", 'it_report_wcreport_textdomain'),
        'name'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customwork_id',
        'id'    => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'customwork_id',
        'type'    => 'text',
    ),
    // array(
    // 	'label'	=> "",
    // 	'desc'	=> "",
    // 	'name'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'license',
    // 	'id'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'license',
    // 	'type'	=> 'text_info',
    // ),
);



if (isset($_POST["update_settings"])) {

    //	print_r($_POST);

    // Do the saving
    if (!in_array(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_fields', $_POST)) {
        delete_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_fields');
    }


    foreach ($_POST as $key => $value) {

        if (!isset($_POST[$key])) {
            delete_option($key);
            continue;
        }

        $old = get_option($key);
        $new = $value;

        if (!is_array($new)) {

            $original_args                     = array();
            $timestamp                         = time();

            //SAVE SCHEDULE IN SETTING
            if (isset($_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'email_schedule'])) {

                $schedule_activate_old            = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email', 0);
                $schedule_recurrence_old        = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'email_schedule', 0);

                $schedule_activate                = isset($_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email']) ? $_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'active_email'] : 0;
                $schedule_recurrence            = isset($_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'email_schedule']) ? $_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'email_schedule'] : 0;
                $schedule_hook_name                = __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . '_schedule_mailing_sales_status_event';

                if ($schedule_activate_old != '' && ($schedule_activate_old != $schedule_activate) or ($schedule_recurrence_old != $schedule_recurrence)) {
                    //echo "action";
                    wp_unschedule_event($timestamp, $schedule_hook_name, $original_args);
                    wp_clear_scheduled_hook($schedule_hook_name, $original_args);
                }

                if ($schedule_activate == 'on') {
                    if (strlen($schedule_recurrence) > 2) {
                        if (!wp_next_scheduled($schedule_hook_name)) {
                            wp_schedule_event($timestamp, $schedule_recurrence, $schedule_hook_name);
                        }
                    }
                }
            }

            if ($new && $new != $old) {
                update_option($key, $new);
            } elseif ('' == $new && $old) {
                delete_option($key);
            }
        } elseif (
            $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'set_default_fields' || $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'order_status' || $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_custom_fields' || $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'po_checkout_custom_fields' || $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_recency_point' || $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_frequency_point' || $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'int_monetary_point'
            || $key == __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'tickera_custom_fields'
        ) {
            //print_r($_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'tickera_custom_fields']);
            if ($new && $new != $old) {
                update_option($key, $new);
            } elseif ('' == $new) {
                delete_option($key);
            }
        } else {

            $get_year = array_keys($value);
            $get_year = $get_year[0];

            foreach ($value[$get_year] as $keys => $vals) {

                $old = get_option($key . "_" . $get_year . "_" . $keys);
                $new = $vals;

                if ($new && $new != $old) {
                    update_option($key . "_" . $get_year . "_" . $keys, $new);
                } elseif ('' == $new && $old) {
                    delete_option($key . "_" . $get_year . "_" . $keys);
                }
            }
        }
    }


?>
    <div id="setting-error-settings_updated" class="updated settings-error">
        <p><strong><?php echo esc_html__('Settings saved', 'it_report_wcreport_textdomain'); ?>.</strong></p>
    </div>
<?php
}


$html = '<div class="wrap">
            <div class="row">
			    <div class="col-xs-12">
			    <div class="awr-box">
			         <div class="awr-title">
                        <h3>
                            <i class="fa fa-cog"></i>
                        ' . esc_html__('Woo Report Settings', 'it_report_wcreport_textdomain') . '
                        </h3>


                    </div>
			         <div class="awr-box-content">


                <form method="POST" action="" class=" awr-setting-form">
                    <input type="hidden" name="update_settings" value="Y" />
                    <input type="hidden" name="update_setting" value="NN" />
                    <div class="tabs tabsA tabs-style-underline">
                        <nav>
                            <ul>';
foreach ($it_report_options_part as $option_part) {
    $html .= '<li><a href="#' . $option_part['id'] . '" class="">' . $option_part['icon'] . ' <span>' . $option_part['title'] . '</span></a></li>';
}
$html .= '
                            </ul>
                        </nav>
                        <div class="content-wrap">';


foreach ($it_report_options_part as $option_part) {
    //TAB TITLE


    $html .= '<section id="' . $option_part['id'] . '">';
    $html .= '<table class="form-table">';
    $this_part_variable = ${$option_part['variable']};
    foreach ($this_part_variable as $field) {
        if (isset($field['dependency'])) {
            $html .= it_report_dependency($field['id'], $field['dependency']);
        }
        // get value of this field if it exists for this post
        $meta = get_option($field['id']);
        //echo $field['id'];
        //if($field['id'])
        // begin a table row with
        $extra_class = '';
        if ($field['type'] == 'notype')
            $extra_class = 'awr-setting-title';
        $html .= '<tr class="' . $field['id'] . '_field ' . $extra_class . '" > ';

        $cols = '';
        if ($field['type'] == 'custom_search_items' || $field['type'] == 'tab_multi_site') {
            $cols = 'colspan="2"';
        } else {
            //$html.= '<th><label for="'.$field['id'].'">'.$field['label'].'</label></th> ';
        }
        $html .= '
					<td ' . $cols . '>
					<div class="awr-form-title"><label for="' . $field['id'] . '">' . $field['label'] . '</label></div>';
        switch ($field['type']) {

            case 'notype':
                $html .= '<span class="description">' . $field['desc'] . '</span>';
                break;

            case 'text_info':

                if ($this->dashboard($this->it_plugin_status)) {
                    $html .= '<h3>Plugin is Licensed !</h3>';

                    $result = $this->dashboard($this->it_plugin_status);

                    $html .= '<div style="border-left:5px solid #eee;padding:5px;line-height:20px;letter-spacing: 1px;"><strong>Plugin Name : </strong>' . (isset($result['verify-purchase']['item_name']) ?? "") . '';
                    $html .= '<br /><strong>Buyer Id : </strong>' . (isset($result['verify-purchase']['buyer']) ?? "") . '';
                    $html .= '<br /><strong>Purchase Date : </strong>' . (isset($result['verify-purchase']['created_at']) ?? "") . '';
                    $html .= '<br /><strong>License Type : </strong>' . (isset($result['verify-purchase']['licence']) ?? "") . '';
                    $html .= '<br /><strong>Supported Until : </strong>' . (isset($result['verify-purchase']['supported_until']) ?? "") . '</div>';
                }
                break;

            case 'text':
                $html .= '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" class="' . $field['id'] . '" value="' . $meta . '" />
							<br /><span class="description">' . $field['desc'] . '</span>	';
                break;

            case 'link':
                $html .= '<a class="button-primary" href="' . $field['href'] . '" id="' . $field['id'] . '" ><i class="fa fa-envelope-o"></i>' . esc_html__('Click Here', 'it_report_wcreport_textdomain') . '</a>
<div class="email_target"></div>
                        <span class="description">' . $field['desc'] . '</span>';
                break;

            case 'radio':
                foreach ($field['options'] as $option) {
                    $html .= '<input type="radio" name="' . $field['id'] . '" class="' . $field['id'] . '" value="' . $option['value'] . '" ' . checked($meta, $option['value'], 0) . ' ' . $option['checked'] . ' />
										<label for="' . $option['value'] . '">' . $option['label'] . '</label><br><br>';
                }
                break;

            case 'checkbox':
                $html .= '
                                <div class="awr-slidecheck">
                                    <input type="hidden" name="' . $field['id'] . '" value="off"/> <input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ' . checked($meta, "on", 0) . ' />
                                    <label for="' . $field['id'] . '"></label>
                                </div>
								<span class="description">' . $field['desc'] . '</span>';
                break;

            case 'order_status':
                $it_order_status = $this->it_get_woo_orders_statuses();
                $option = '';
                foreach ($it_order_status as $key => $value) {
                    $selected = '';

                    if (is_array($meta) && in_array($key, $meta))
                        $selected = 'SELECTED';
                    $option .= "<option $selected value='" . $key . "' >" . $value . "</option>";
                }

                $html .= '
								<select name="' . $field['id'] . '[]" multiple="multiple" size="5"  data-size="5" class="chosen-select-search">';
                $html .= '<option value="-1">' . esc_html__('Select All', 'it_report_wcreport_textdomain') . '</option>';
                $html .= $option;
                $html .= '
							</select>
								<br /><span class="description">' . $field['desc'] . '</span>';
                break;

            case 'select':
                $html .= '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="' . $field['id'] . '" style="width: 170px;">';
                foreach ($field['options'] as $option) {
                    $html .= '<option ' . selected($meta, $option['value'], 0) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
                }
                $html .= '</select><br /><span class="description">' . $field['desc'] . '</span>';
                break;

            case 'datepicker': {
                    $html .= '<input name="' . $field['id'] . '" id="' . $field['ids'] . '" type="text"  class="datepick"  value="' . $meta . '" /><br /><span class="description">' . $field['desc'] . '</span>';
                }
                break;

            case 'reports': {
                    global $it_rpt_main_class;

                    $all_menu = '';
                    foreach ($it_rpt_main_class->our_menu as $roots) {

                        if ($roots['id'] == 'logo') continue;

                        if (!isset($roots['childs']))
                            $all_menu .= '<option value="' . $roots['link'] . '" ' . $selected . '>' . $roots['label'] . '</option>';
                        else {
                            $all_menu .= '<optgroup label="' . $roots['label'] . '">';
                            foreach ($roots['childs'] as $childs) {

                                $selected = '';
                                if ($meta == $childs['link']) {
                                    $selected = 'SELECTED';
                                }
                                $all_menu .= '<option value="' . $childs['link'] . '" ' . $selected . '>' . $childs['label'] . '</option>';
                            }
                            $all_menu .= '</optgroup>';
                        }
                    }

                    $html .= '
							<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="' . $field['id'] . '" style="width: 170px;">
								' . $all_menu . '
							</select>

							<br /><span class="description">' . $field['desc'] . '</span>	';
                }
                break;

            case "custom_search_items": {
                    $custom_tax_pages = array(
                        array("details_tax_field" => esc_html__("Taxonomies All Order", 'it_report_wcreport_textdomain')),
                        array("product" => esc_html__("Product", 'it_report_wcreport_textdomain')),
                        array("variation_stock" => esc_html__("Variation Stock", 'it_report_wcreport_textdomain')),


                    );


                    $html .= '
								<div class="col-md-12 bhoechie-tab-container">
									<div class=" col-md-3 bhoechie-tab-menu menu_tax">
									  <div class="list-group">';
                    $i = 0;
                    foreach ($custom_tax_pages as $tab) {
                        foreach ($tab as $key => $value) {
                            $active = '';
                            if ($i == 0)
                                $active = "active";
                            $i++;
                            $html .= '
												<a href="javascript:void(0);" class="list-group-item ' . $active . ' text-center">
												  <div class="awr-label-' . $key . '">' . $value . '</div>
												</a>';
                        }
                    }
                    $html .= '
									  </div>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab menu_tax_content">';

                    $i = 0;
                    foreach ($custom_tax_pages as $tab) {

                        foreach ($tab as $key => $value) {

                            if (in_array($key, array("prod_per_month", "prod_per_state", "prod_per_country")) && !defined("__IT_CROSSTABB_ADD_ON__")) {
                                $html .= '<div class="bhoechie-tab-content ' . $active . '"><center>';
                                $html .= '<h4><center><i class="fa fa-4x fa-user-times"></i><br />' . __("'CrossTab Add-on' is needed! Please Purchase/Active it. <br />click ", 'it_report_wcreport_textdomain') . "<a target='_blank' href='" . admin_url() . "admin.php?page=wcx_wcreport_plugin_addons_report&parent=addons'>" . esc_html__("Here", 'it_report_wcreport_textdomain') . "</a>" . esc_html__(" For more info !", 'it_report_wcreport_textdomain') . '</center></h4>';
                                $html .= '</div>';
                                continue;
                            }

                            $active = '';
                            if ($i == 0)
                                $active = "active";
                            $i++;
                            $html .= '<div class="bhoechie-tab-content ' . $active . '"><center>';

                            $original_query = 'product';

                            $post_name = 'product';
                            $option_data = '';
                            $param_line = $value;

                            $all_tax = $this->fetch_product_taxonomies($post_name);
                            $current_value = array();
                            if (is_array($all_tax) && count($all_tax) > 0) {

                                $post_type_label = get_post_type_object($post_name);
                                $label = $post_type_label->label;

                                //FETCH TAXONOMY
                                foreach ($all_tax as $tax) {

                                    if (strpos($tax, "pa_") !== false)
                                        continue;

                                    $taxonomy = get_taxonomy($tax);
                                    $values = $tax;
                                    $label = $taxonomy->label;
                                    $attribute_taxonomies = wc_get_attribute_taxonomies();

                                    $it_display_type = '';

                                    $meta = get_option($field['id'] . '_' . $key . '_' . $tax);
                                    $meta_column = get_option($key . '_' . $tax . '_column');
                                    $translate = get_option($key . '_' . $tax . '_translate');

                                    //echo $meta. "# ".$field['id'].'_'.$tax;
                                    $checked = '';
                                    if ($meta == "on")
                                        $checked = ' checked="checked"';

                                    $checked_col = '';
                                    if ($meta_column == "on")
                                        $checked_col = ' checked="checked"';


                                    $html .= '
														<div class="full-lbl-cnt more-padding">
															<label class="full-label">
																<input type="hidden" data-input="post_type" id="it_checkbox_' . $key . '_' . $tax . '" name="' . $field['id'] . '_' . $key . '_' . $tax . '" class="it_taxomomy_checkbox" value="off">
																<input type="checkbox" data-input="post_type" id="it_checkbox_' . $key . '_' . $tax . '" name="' . $field['id'] . '_' . $key . '_' . $tax . '" class="it_taxomomy_checkbox" ' . $checked . '>
																Enable "' . $label . '"
															</label>
															<br />

															<label class="full-label">
																<input type="hidden" data-input="post_type" id="it_column_' . $key . '_' . $tax . '" name="' . $key . '_' . $tax . '_column" class="it_taxomomy_checkbox" value="off">
																<input type="checkbox" data-input="post_type" id="it_column_' . $key . '_' . $tax . '" name="' . $key . '_' . $tax . '_column" class="it_taxomomy_checkbox" ' . $checked_col . '>
																Show "' . $label . '" in Grid
															</label>
															<br />


															<input type="text" name="' . $key . '_' . $tax . '_translate" value="' . $translate . '"/><span class="description">' . esc_html__('Set Label, Leave blank to use from default label', 'it_report_wcreport_textdomain') . '</span>
														</div>
														<br />
														';
                                }
                            }

                            //$html.=$param_line.'<hr />';
                            $html .= '</center></div>';
                        }
                    }
                    $html .= '
									</div>
								</div>
						<script>
							jQuery(document).ready(function($) {
								$("div.menu_tax>div.list-group>a").on("click",function(e) {
									e.preventDefault();
									$(this).siblings("a.active").removeClass("active");
									$(this).addClass("active");
									var index = $(this).index();
									$("div.menu_tax_content>div.bhoechie-tab-content").removeClass("active");
									$("div.menu_tax_content>div.bhoechie-tab-content").eq(index).addClass("active");
								});
							});
						</script>';
                }
                break;


            case 'multi_side': {
                }
                break;


                ////ADDE IN VER4.0
                /// PRODUCT OPTIONS PLUGIN
            case "tab_multi_side": {
                }
                break;

                ////ADDE IN VER4.0
                /// PRODUCT OPTIONS PLUGIN
            case "tab_multi_side_checkout": {
                }
                break;


                //CUSTOM WORK - 12300
                /// TICKERA PLUGIN
            case "tab_multi_side_tickera": {
                    global $wpdb;
                    $options_all = '';
                    $selected_options = '';
                    $fields = $wpdb->get_results("SELECT posts.ID as Form_Id,fposts.ID as Field_Id, fposts.post_title as Field_Name,fmeta.meta_value as Field_Type,fmeta1.meta_value as Field_Html_Name from {$wpdb->prefix}posts as posts LEFT JOIN {$wpdb->prefix}posts as fposts ON posts.ID=fposts.post_parent LEFT JOIN {$wpdb->prefix}postmeta as fmeta ON fposts.ID=fmeta.post_id LEFT JOIN {$wpdb->prefix}postmeta as fmeta1 ON fposts.ID=fmeta1.post_id WHERE posts.post_type='tc_forms' and fposts.post_type='tc_form_fields' AND fmeta.meta_key='field_type' AND fmeta1.meta_key='name'", ARRAY_A);
                    //print_r($fields);
                    $fields_array = array();
                    if ($fields != null) {
                        foreach ($fields as $v => $tfield) {
                            //$data=unserialize($v['meta_value']);
                            //foreach($v as $field) {
                            $fields_array[$tfield['Field_Id']] = $tfield['Field_Name'];
                            $type = $tfield['Field_Type'];
                            $type = explode("_", $type);
                            $type = " (" . $type[1] . ")";

                            $options_all .= '<option value="' . $tfield['Field_Id'] . '_' . $tfield['Field_Html_Name'] . '">' . $tfield['Field_Name'] . $type . '</option>';
                            if (is_array($meta) && in_array($tfield['Field_Id'] . '_' . $tfield['Field_Html_Name'], $meta))
                                $selected_options .= '<option value="' . $tfield['Field_Id'] . '_' . $tfield['Field_Html_Name'] . '" SELECTED>' . $tfield['Field_Name'] . $type . '</option>';

                            //}
                        }
                    }

                    $html .= '


								<div class="description">' . $field['desc'] . '</div><br />
							<div class="row pw-twoside-list">
								<div class="col-xs-4">
									<select name="from" id="undo_redo_tickera" class="form-control" size="11" multiple="multiple">
										' . $options_all . '
									</select>
								</div>

								<div class="col-xs-4 pw-twoside-btns">
									<button type="button" id="undo_redo_tickera_undo" class="btn btn-primary btn-block">undo</button>
									<button type="button" id="undo_redo_tickera_rightAll" class="btn btn-default btn-block"><i class="fa fa-forward"></i></button>
									<button type="button" id="undo_redo_tickera_rightSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-right"></i></button>
									<button type="button" id="undo_redo_tickera_leftSelected" class="btn btn-default btn-block"><i class="fa fa-chevron-left"></i></button>
									<button type="button" id="undo_redo_tickera_leftAll" class="btn btn-default btn-block"><i class="fa fa-backward"></i></button>
									<button type="button" id="undo_redo_tickera_redo" class="btn btn-warning btn-block">redo</button>

								</div>

								<div class="col-xs-4">
									<select name="' . $field['id'] . '[]"  id="undo_redo_tickera_to" class="form-control" size="11" multiple="multiple" style="height: 162px;
">' . $selected_options . '</select>
									<button type="button" id="translate_fields_tickera" class="btn btn-warning btn-block" style="background-color:#0DBF44;border-color: #06A036; border-radius: 4px; margin-top: 5px;">' . esc_html__('Field`s Settings', 'it_report_wcreport_textdomain') . '</button>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12" style="margin-top: 20px;">
									<div class="awr-form-title" style="padding: 7px 5px 10px;text-align: center;background: #e0e0e0;color: #666;margin-bottom: 15px;">
										' . esc_html__('Field`s Settings', 'it_report_wcreport_textdomain') . '
									</div>


									<div class="col-xs-12 it_awr_fields_translate_tickera">';

                    //$operators=array("eq"=>esc_html__('EQUALS','it_report_wcreport_textdomain'),);



                    if (is_array($meta)) {

                        //print_r($meta);

                        foreach ($meta as $opt) {
                            $label = explode("_", $opt, 2);

                            $id_field = $label[1];
                            $filter = get_option($label[1] . "_filter");
                            $col = get_option($label[1] . "_column");
                            $translate = get_option($label[1] . "_translate");
                            $label = $fields_array[$label[0]];


                            $html .= '

                                        <div class="col-xs-12 pw-translate_tickera">
                                            <input type="hidden" name="' . $id_field . '_column" placeholder="Label for ' . $opt . '" value="off">
                                            <input type="checkbox" name="' . $id_field . '_column" placeholder="Label for ' . $opt . '" "' . checked("on", $col, 0) . '">' . esc_html__("Display in Grid", 'it_report_wcreport_textdomain');
                            $html .= '
                                            <input type="hidden" name="' . $id_field . '_filter" placeholder="Label for ' . $opt . '" value="off">
                                            <input type="checkbox" name="' . $id_field . '_filter" placeholder="Label for ' . $opt . '" "' . checked("on", $filter, 0) . '">' . esc_html__("Display in Filter", 'it_report_wcreport_textdomain');
                            $html .= '
                                            <br />
                                            <input type="text" name="' . $id_field . '_translate" placeholder="Label for ' . $label . '" value="' . ($translate) . '">

                                        </div>


                                        <br />
                                    ';
                        }
                    }
                    $html .= '
									</div>
								</div>
							</div>

							<script type="text/javascript">
								"use strict";
								jQuery(document).ready(function($) {
									$("#undo_redo_tickera").multiselect();
									$("#translate_fields_tickera").on("click",function(){
										$("#undo_redo_tickera_to option").prop("selected", true);
										var data="";
										data=$(".custom_report_tickera_custom_fields_field").find("input[name],select[name],textarea[name]").serialize()+"&field=' . $field['id'] . '";
										confirm($(".custom_report_tickera_custom_fields_field").find("input[name],select[name],textarea[name]").serialize());

										var pdata = {
														action: "it_rpt_fetch_custom_fields_tickera",
														postdata: data,
													}

										$.ajax ({
											type: "POST",
											url : ajaxurl,
											data:  pdata,
											success : function(resp){
											    confirm(resp);
												$(".it_awr_fields_translate_tickera").html(resp);
											}
										});
									});
								});
							</script>
							';
                }
                break;

            case 'select_year': {
                    $html .= '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="' . $field['id'] . '" style="width: 170px;">';
                    foreach ($field['options'] as $option) {
                        $html .= '<option ' . selected($meta, $option['value'], 0) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
                    }
                    $html .= '</select><br /><span class="description">' . $field['desc'] . '</span>';

                    $all_monthes = array();
                    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

                    //	$html.=$first_date;$year<$it_to_date;

                    for ($year = 2010; $year < 2025; $year++) {

                        foreach ($months as $month) {
                            $all_monthes[$year][$month] = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'monthes_' . $year . '_' . $month);
                        }
                    }
                    //print_r($all_monthes);
                    $html .= '
								<script>

									var all_month=' . wp_json_encode($all_monthes) . ';

									var mS = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];


									jQuery(document).ready(function($){
										var cur_year="";
										cur_year=$("#custom_report_projected_year").val();

										$("#custom_report_projected_year").change(function(){

											chg_year=$(this).val();
											var i=0
											$(".pwr_year_months").each(function(){
												input_name=$(this).attr("name");
												input_name=input_name.replace(cur_year,chg_year);
												$(this).attr("name",input_name);

												your_val="0";
												your_month=mS[i];
												if(all_month[chg_year][your_month])
													your_val=all_month[chg_year][your_month];

												$(this).val(your_val);
												i=i+1;
											});
										});
									});
								</script>
							';
                }
                break;

            case 'monthes':

                $first_date = get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'projected_year', $first_date);

                foreach ($months as $month) {
                    $value = get_option($field['id'] . '_' . $first_date . '_' . $month, 0);

                    $html .= '
							<span><label><strong>' . $month . '</strong></label></span><input type="text" name="' . $field['id'] . '[' . $first_date . '][' . $month . ']" id="' . $field['id'] . '" class="' . $field['id'] . ' pwr_year_months" value="' . $value . '"/><br />';
                }

                $html .= '
							<br /><span class="description">' . $field['desc'] . '</span>	';
                break;

            case 'numeric':
                $html .= '
							<input type="number" name="' . $field['id'] . '"  id="' . $field['id'] . '" value="' . ($meta == '' ? "" : $meta) . '" size="30" class="width_170 ' . $field['id'] . '" min="0" pattern="[-+]?[0-9]*[.,]?[0-9]+" title="Only Digits!" class="input-text qty text" />
        ';
                $html .= '
								<br /><span class="description">' . $field['desc'] . '</span>';
                break;

            case 'recency_numeric_int':

                $int_html = '';
                for ($i = 5; $i > 0; $i--) {

                    $title = ($i) . ' ' . esc_html__("Points - Less Than", 'it_report_wcreport_textdomain') . ' ';
                    if ($i == 1)
                        $title = ($i) . ' ' . esc_html__("Point &nbsp - Less Than", 'it_report_wcreport_textdomain') . ' ';

                    $metas = isset($meta[$i]) ? $meta[$i] : "";

                    $int_html .= '
						        <label for="' . $field['id'] . '[' . $i . ']" class="full-label">' . $title . '</label>
							    <input type="number" name="' . $field['id'] . '[' . $i . ']"  id="' . $field['id'] . '[' . $i . ']" value="' . ($metas == '' ? "" : $metas) . '" size="30" class="width_170 ' . $field['id'] . '[' . $i . ']" min="0" pattern="[-+]?[0-9]*[.,]?[0-9]+" title="Only Digits!" class="input-text qty text" />&nbsp&nbsp ' . esc_html__("Days", 'it_report_wcreport_textdomain') . '<br />
						        ';
                }

                $html .= $int_html;
                break;

            case 'frequency_numeric_int':
                $int_html = '';
                for ($i = 5; $i > 0; $i--) {

                    $title = ($i) . ' ' . esc_html__("Points - More Than", 'it_report_wcreport_textdomain') . ' ';
                    if ($i == 1)
                        $title = ($i) . ' ' . esc_html__("Point &nbsp - More Than", 'it_report_wcreport_textdomain');
                    $metas = isset($meta[$i]) ? $meta[$i] : "";

                    $int_html .= '
						        <label for="' . $field['id'] . '[' . $i . ']" class="full-label">' . $title . '</label>
							    <input type="number" name="' . $field['id'] . '[' . $i . ']"  id="' . $field['id'] . '[' . $i . ']" value="' . ($metas == '' ? "" : $metas) . '" size="30" class="width_170 ' . $field['id'] . '[' . $i . ']" min="0" pattern="[-+]?[0-9]*[.,]?[0-9]+" title="Only Digits!" class="input-text qty text" />&nbsp&nbsp ' . esc_html__("Times", 'it_report_wcreport_textdomain') . '<br />
						        ';
                }

                $html .= $int_html;
                break;

            case 'monetary_numeric_int':
                $current_currency = get_woocommerce_currency_symbol();
                $int_html = '';
                for ($i = 5; $i > 0; $i--) {

                    $title = ($i) . ' ' . esc_html__("Points - More Than", 'it_report_wcreport_textdomain') . ' ';
                    if ($i == 1)
                        $title = ($i) . ' ' . esc_html__("Point &nbsp - More Than", 'it_report_wcreport_textdomain');
                    $metas = isset($meta[$i]) ? $meta[$i] : "";

                    $int_html .= '
						        <label for="' . $field['id'] . '[' . $i . ']" class="full-label">' . $title . '</label>
							    <input type="number" name="' . $field['id'] . '[' . $i . ']"  id="' . $field['id'] . '[' . $i . ']" value="' . ($metas == '' ? "" : $metas) . '" size="30" class="width_170 ' . $field['id'] . '[' . $i . ']" min="0" pattern="[-+]?[0-9]*[.,]?[0-9]+" title="Only Digits!" class="input-text qty text" />&nbsp&nbsp ' . $current_currency . '<br />
						        ';
                }

                $html .= $int_html;
                break;


            case 'html_editor': {
                    ob_start();

                    $html .= '
								<p><span class="description">' . $field['desc'] . '</span></p>
								<p class="form-field product_field_type" >';
                    $editor_id = $field['id'];
                    wp_editor(stripslashes($meta), $editor_id);
                    $html .= ob_get_clean();
                    $html .= '</p>';
                }
                break;

            case "it_pages": {
                    $args = array(
                        'depth'                 => 0,
                        'child_of'              => 0,
                        'selected'              => $meta,
                        'echo'                  => 0,
                        'name'                  => $field['id'],
                        'id'                    => null, // string
                        'show_option_none'      => esc_html__('Choose a Page', 'it_report_wcreport_textdomain'), // string
                        'show_option_no_change' => null, // string
                        'option_none_value'     => null, // string
                    );
                    $html .= wp_dropdown_pages(esc_attr($args));
                    $html .= '<br /><span class="description">' . $field['desc'] . '</span>';
                }
                break;

            case 'posttype_seletc': {
                    $output = 'objects';
                    $args = array(
                        'public' => true
                    );
                    $post_types = get_post_types($args, $output);

                    $html .= '<select name="' . $field['id'] . '[]" id="' . $field['id'] . '" class="chosen-select-build-posttype" multiple="multiple"> ';
                    $html .= '<option value="" >' . esc_html__('Choose Post Type', 'it_report_wcreport_textdomain') . '</option>';
                    foreach ($post_types  as $post_type) {

                        if ($post_type->name != 'attachment') {
                            $post_value = $post_type->name;
                            $post_lbl = $post_type->labels->name;

                            $selected = '';
                            if (is_array($meta) && in_array($post_value, $meta))
                                $selected = 'SELECTED';

                            $html .= '<option value="' . $post_value . '" ' . $selected . '>' . $post_lbl . ' (' . $post_value . ')</option>';
                        }
                    }

                    $html .= '<br /><span class="description">' . $field['desc'] . '</span>';
                    $html .= '</select>
							<script type="text/javascript">
								jQuery(document).ready(function(){
									var visible = true;
									setInterval(
									function()
									{
										if(visible)
											if(jQuery(".chosen-select-build-posttype").is(":visible"))
											{
												jQuery(".chosen-select-build-posttype").chosen();
											}
									}, 100);
								});
							</script>';
                }
                break;

            case 'all_search': {
                    $html .= '
							<select name="' . $field['name'] . '" >
								<option value="">' . esc_html__('Choose Live Search', 'it_report_wcreport_textdomain') . '</option>';
                    global $it_woo_ad_main_class, $wpdb;

                    $args = array(
                        'post_type' => 'it_report',
                        'post_status' => 'publish',
                    );

                    $my_query_archive = new WP_Query($args);

                    if ($my_query_archive->have_posts()):
                        while ($my_query_archive->have_posts()) : $my_query_archive->the_post();
                            $id = get_the_ID();
                            $html .= '<option value="' . $id . '" ' . selected($id, $meta, 0) . '>' . get_the_title() . '</option>';
                        endwhile;
                        wp_reset_query();
                    endif;
                    $html .= '</select>';
                    $html .= '<br /><span class="description">' . $field['desc'] . '</span>';
                }
                break;


            case "colorpicker":

                $html .= '<div class="medium-lbl-cnt">
											<label for="' . $field['id'] . '" class="full-label">' . $field['label'] . '</label>
											<input name="' . $field['id'] . '" id="' . $field['id'] . '" type="text" class="wp_ad_picker_color" value="' . $meta . '" data-default-color="#' . $meta . '">
										  </div>
									';
                $html .= '

							<br />';
                $html .= '<br /><span class="description">' . $field['desc'] . '</span>';
                break;

            case 'icon_type':
                $html .= $meta;
                $html .= '<input type="hidden" id="' . $field['id'] . 'font_icon" name="' . $field['id'] . '" value="' . $meta . '"/>';
                $html .= '<div class="' . $field['id'] . ' it_iconpicker_grid" id="benefit_image_icon">';
                $html .= include(__IT_LIVESEARCH_ROOT_DIR__ . '/includes/font-awesome.php');
                $html .= '</div>';
                $html .= '<br /><span class="description">' . $field['desc'] . '</span><br />';
                $output = '
							<script type="text/javascript">
								jQuery(document).ready(function(jQuery){';
                if ($meta == '') $meta = "fa-none";
                $output .= 'jQuery( ".' . $field['id'] . ' .' . $meta . '" ).siblings( ".active" ).removeClass( "active" );
									jQuery( ".' . $field['id'] . ' .' . $meta . '" ).addClass("active");';
                $output .= '
									jQuery(".' . $field['id'] . ' i").on("click",function(){
										var val=(jQuery(this).attr("class").split(" ")[0]!="fa-none" ? jQuery(this).attr("class").split(" ")[0]:"");
										jQuery("#' . $field['id'] . 'font_icon").val(val);
										jQuery(this).siblings( ".active" ).removeClass( "active" );
										jQuery(this).addClass("active");
									});
								});
							</script>';
                $html .= $output;
                break;

            case 'upload':
                //wp_enqueue_media();
                $image = esc_html(__IT_REPORT_WCREPORT_URL__) . '/assets/images/pw-transparent.gif';
                //$image='';
                if ($meta) {
                    $image = wp_get_attachment_image_src($meta, 'medium');
                    $image = $image[0];
                }

                $html .= '<input name="' . $field['id'] . '" id="' . $field['id'] . '" type="hidden" class="custom_upload_image ' . $field['id'] . '" value="' . (isset($meta) ? $meta : '') . '" />
							<img src="' . (isset($image) ? $image : '') . '" class="custom_preview_image" alt="" width="150" height="150" />
							<input name="btn" class="awr_upload_image_button button" type="button" value="' . esc_html__('Choose Image', 'it_report_wcreport_textdomain') . '" />
							<button type="button" class="awr_search_remove_image_button button">Remove image</button>';
                break;

            case "default_archive_grid": {
                    global $it_woo_ad_main_class, $wpdb;

                    $query_meta_query = array('relation' => 'AND');
                    $query_meta_query[] = array(
                        'key' => __IT_REPORT_WCREPORT_FIELDS_PERFIX__ . 'shortcode_type',
                        'value' => "search_archive_page",
                        'compare' => '=',
                    );

                    $args = array(
                        'post_type' => 'ad_woo_search_grid',
                        'post_status' => 'publish',
                        'meta_query' => $query_meta_query,
                    );

                    $html .= '<select name="' . $field['id'] . '" id="' . $field['id'] . '" class="' . $field['id'] . '" style="width: 170px;">
									<option>' . esc_html__('Choose Shorcode', 'it_report_wcreport_textdomain') . '</option>';

                    $my_query_archive = new WP_Query($args);
                    if ($my_query_archive->have_posts()):
                        while ($my_query_archive->have_posts()) : $my_query_archive->the_post();
                            $html .= '<option value="' . get_the_ID() . '" ' . selected($meta, get_the_ID(), 0) . '>' . get_the_title() . '</option>';
                        endwhile;
                    endif;

                    $html .= '</select>';
                }
                break;

            case "it_sendto_form_fields": {
                    $html .= '
							<label class="it_showhide" for="displayProduct-price"><input name="' . $field['id'] . '[name_from]" type="checkbox" ' . (is_array($meta) && in_array("name_from", $meta) ? "CHECKED" : "") . ' value="name_from" class="displayProduct-eneble">' . esc_html__('Name (From) Field', 'it_report_wcreport_textdomain') . ' </label>

							<label class="it_showhide" for="displayProduct-price"><input name="' . $field['id'] . '[name_to]" type="checkbox" ' . (is_array($meta) && in_array("name_to", $meta) ? "CHECKED" : "") . ' value="name_to" class="displayProduct-eneble">' . esc_html__('Name (To) Field', 'it_report_wcreport_textdomain') . ' </label>

							<label class="it_showhide" for="displayProduct-star"><input name="' . $field['id'] . '[email]" type="checkbox" ' . (is_array($meta) && in_array("email", $meta) ? "CHECKED" : "") . ' value="email" class="displayProduct-eneble">' . esc_html__('Email (To) Field', 'it_report_wcreport_textdomain') . ' </label>

							<label class="it_showhide" for="displayProduct-metatag"><input name="' . $field['id'] . '[description]" type="checkbox" ' . (is_array($meta) && in_array("description", $meta) ? "CHECKED" : "") . ' value="description">' . esc_html__('Description Field', 'it_report_wcreport_textdomain') . ' </label>
							';
                }
                break;

            case 'multi_select': {

                    $html .= '<select name="' . $field['id'] . '[]" id="' . $field['id'] . '" style="width: 170px;" class="chosen-select-build" multiple="multiple">';
                    foreach ($field['options'] as $option) {
                        $selected = '';
                        if (is_array($meta) && in_array($option['value'], $meta))
                            $selected = 'SELECTED';
                        $html .= '<option ' . $selected . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
                    }
                    $html .= '</select><br /><span class="description">' . $field['desc'] . '</span>';

                    $html .= '
							<script type="text/javascript">
								jQuery(document).ready(function(){
									var visible = true;
									setInterval(
										function()
										{
											if(visible)
												if(jQuery(".chosen-select-build").is(":visible"))
												{
													visible = false;
													jQuery(".chosen-select-build").chosen();
												}
									}, 100);

								});
							</script>
							';
                }
                break;
        }
    }
    $html .= '</table>';
    $html .= '</section>';
}

$html .= '</div><!--END TAB-->';

$html .= ' <div class="awr-setting-submit">
				<button type="submit" value="Save Settings" class="button-primary"><i class="fa fa-floppy-o"></i> <span>' . esc_html__("Save settings", 'it_report_wcreport_textdomain') . '</span></button>
			</div>
		</form>
		</div>
		</div><!--awr-box -->
	</div><!--col-xs-12 -->
	</div>	<!--row -->
	</div>

	<script type="text/javascript">
		function strpos(haystack, needle, offset) {
			var i = (haystack + "").indexOf(needle, (offset || 0));
			return i === -1 ? false : i;
		}

		jQuery(document).ready(function(){
			[].slice.call( document.querySelectorAll( ".tabsA" ) ).forEach( function( el ) {
				new CBPFWTabs( el );
			});


			////////////SHOW/HIDE CUSTOM FIELD SELECTION/////////////


			////////////END SHOW/HIDE CUSTOM FIELD SELECTION/////////////

		});
	</script>
	';


echo wp_kses(
    $html,
    $this->allowedposttags()
);

// echo $html;

////ADDED IN VER4.0
/// PRODUCT OPTIONS CUSTOM FIELDS
/*
 * Change value of options to slug : example : "Flag Color => Flag_Color" and save 3 fields for each one : slug_tranlate, slug_column, slug_filter
 * Note : slug_filter use just for global fields
*/

function it_generate_multi_side($args) {}


?>