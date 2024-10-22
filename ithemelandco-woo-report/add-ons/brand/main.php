<?php
/*
Plugin Name: iThemelandCo WooCommerce Report for "Brand Plugins" ADD-ON
Plugin URI: http://ithemelandco.com/plugins/woocommerce-report
Description: With this add-on you can add Brands taxonomy to mos of reports.
Version: 1.1
Author: iThemelandCo
Author URI: https://ithemelandco.com
Text Domain: it_report_wcreport_textdomain
Domain Path: /languages/
*/

/*
    v1.1
		Fixed : Some of Accounting value
		Fixed : Fixed some functionality issues

    Ver 1.0 :
		- Add All orders report per brand
		- Add Brands Report
	    	- Add Brands Taxonomy to some of reports : All Orders, Product, Profit, Prod/month, Variation/month, Product/country,
		Product/State, Variation, Stock list, Variation Stock
 */

define("__IT_BRANDS_ADD_ON__",'');
define( '__IT_REPORT_WCREPORT_ROOT_DIR_BRANDS_ADD_ON__', dirname(__FILE__));
define ('__IT_REPORT_WCREPORT_URL_BRANDS_ADD_ON__',plugins_url('', __FILE__));

if(!class_exists('it_report_wcreport_brands_addon_class')){

	class it_report_wcreport_brands_addon_class{

		function __construct(){
			add_action( 'it_report_wcreport_admin_menu', array($this,'add_brands' ));
			add_filter( 'it_report_wcreport_page_fetch_menu', array($this,'add_fetch_page_brands'));
			add_filter( 'it_report_wcreport_page_titles', array($this,'add_page_titles'));
		}

		function add_page_titles($page_titles){
			$page_titles['details_brands']=__( "All Orders per Brands",'it_report_wcreport_textdomain');
			$page_titles['brand']=__( "Brand Report",'it_report_wcreport_textdomain');
			$page_titles['custom_taxonomy']=__( "Custom Taxonomy",'it_report_wcreport_textdomain');

			return $page_titles;
		}

		function add_brands() {

			global $it_rpt_main_class;
			$role_capability=$it_rpt_main_class->get_capability();
			//CUSTOM TAX & FIELDS
			add_submenu_page('itwrl_submenu', __('All Orders (Custom Taxonomy, Field)','it_report_wcreport_textdomain'), __('All Orders (Custom Taxonomy, Field)','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_all_order_brands',   array($this,'wcx_plugin_menu_all_order_brands' ) );

			add_submenu_page('itwrl_submenu', __('Brands Report','it_report_wcreport_textdomain'), __('Brands Reoprt','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_brand_brands',   array($this,'wcx_plugin_menu_brand_brands' ) );
		}

		//////////////////////CUSTOM TAX & FIELDS//////////////////////
		//1-ALL DETAILS
		function wcx_plugin_menu_all_order_brands(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_BRANDS_ADD_ON__."/class/details_brands.php");
		}

		//2-BRAND REPORT
		function wcx_plugin_menu_brand_brands(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_BRANDS_ADD_ON__."/class/brand.php");
		}

		//CHANGE FETCH MENU VISIBLE MENUS
		function add_fetch_page_brands( $visible_menu ) {
			$brands_menu=array(
						"label" => '<span style="color:#d97c7c">'.__("Brands Reports(Pro)",'it_report_wcreport_textdomain').'</span>',
						"id" => "brands_reports",
						"link" => "#",
						"icon" => "fa-tag",
						"childs" => array(
							"details_brands" => array(
								"label" => '<span style="color:#d97c7c">'.__("All Orders Per Brands(Pro)" ,'it_report_wcreport_textdomain').'</span>',
								"id" => "details_brands",
								"link" => "#",
								"icon" => "fa-cog",
							),
							"brand_brands" => array(
								"label" => '<span style="color:#d97c7c">'.__("Brands Report(Pro)" ,'it_report_wcreport_textdomain').'</span>',
								"id" => "brand_brands",
								"link" => "admin.php?page=wcx_wcreport_plugin_brand_brands&parent=brands_reports&smenu=brand_brands",
								"icon" => "fa-tags",
							)
						)
					);

			global $it_rpt_main_class;
			$it_rpt_main_class->our_menu=$it_rpt_main_class->array_insert_after("product_reports",$it_rpt_main_class->our_menu,"brands_reports",$brands_menu);
			$visible_menu=$it_rpt_main_class->our_menu;
			return $visible_menu;
		}

	}
	new it_report_wcreport_brands_addon_class;
}
?>
