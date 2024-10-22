<?php
/*
Plugin Name: PW Advanced Woo Reporting Customer Role ADD-ON
Plugin URI: https://ithemelandco.com/plugins/woocommerce-report/
Description: Add Order/Country report.
Version: 1.0
Author: iThemelandCo
Author URI: https://ithemelandco.com
Text Domain: it_report_wcreport_textdomain
Domain Path: /languages/
*/

define("__IT_CUSTOMER_ROLE_ADD_ON__",'');
define( '__IT_REPORT_WCREPORT_ROOT_DIR_CUSTOMER_ROLE_ADD_ON__', dirname(__FILE__));
define ('__IT_REPORT_WCREPORT_URL_CUSTOMER_ROLE_ADD_ON__',plugins_url('', __FILE__));

if(!class_exists('it_report_wcreport_customer_role_addon_class')){

	class it_report_wcreport_customer_role_addon_class{

		function __construct(){
			add_action( 'it_report_wcreport_admin_menu', array($this,'add_customer_role' ));
			add_filter( 'it_report_wcreport_page_fetch_menu', array($this,'add_fetch_page_customer_role'));
			add_filter( 'it_report_wcreport_page_titles', array($this,'add_page_titles'));
		}

		function add_page_titles($page_titles){
			$page_titles['customer_role_total_sale']=__( "Total Sales",'it_report_wcreport_textdomain');
			$page_titles['customer_role_registered']=__( "New User Sign-Up",'it_report_wcreport_textdomain');
			$page_titles['customer_role_top_products']=__( "Top 20 Products",'it_report_wcreport_textdomain');
			$page_titles['customer_role_bottom_products']=__( "Lowly 20 Products",'it_report_wcreport_textdomain');
			return $page_titles;
		}

		function add_customer_role() {

			global $it_rpt_main_class;
			$role_capability=$it_rpt_main_class->get_capability();
			//CUSTOM TAX & FIELDS
			add_submenu_page('itwrl_submenu', __('Total Sales','it_report_wcreport_textdomain'), __('Total Sales','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_customer_role_total_sale',   array($this,'wcx_plugin_menu_customer_role_total_sale' ) );

			add_submenu_page('itwrl_submenu', __('New User Sign-Up','it_report_wcreport_textdomain'), __('New User Sign-Up','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_customer_role_registered',   array($this,'wcx_plugin_menu_customer_role_registered' ) );

			add_submenu_page('itwrl_submenu', __('Top 20 Products','it_report_wcreport_textdomain'), __('Top 20 Products','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_customer_role_top_products',   array($this,'wcx_plugin_menu_customer_role_top_products' ) );

			add_submenu_page('itwrl_submenu', __('Bottom 20 Products','it_report_wcreport_textdomain'), __('Top 20 Products','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_customer_role_bottom_products',   array($this,'wcx_plugin_menu_customer_role_bottom_products' ) );

		}

		//////////////////////CUSTOM TAX & FIELDS//////////////////////
		//1-TOTAL SALES
		function wcx_plugin_menu_customer_role_total_sale(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_CUSTOMER_ROLE_ADD_ON__."/class/customer_role_total_sale.php");
		}

		//2-REGISTERED CUSTOMERS
		function wcx_plugin_menu_customer_role_registered(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_CUSTOMER_ROLE_ADD_ON__."/class/customer_role_registered.php");
		}

		//3-TOP 20 PRODUCTS
		function wcx_plugin_menu_customer_role_top_products(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_CUSTOMER_ROLE_ADD_ON__."/class/customer_role_top_products.php");
		}

		//4-BOTTOM 20 PRODUCTS
		function wcx_plugin_menu_customer_role_bottom_products(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_CUSTOMER_ROLE_ADD_ON__."/class/customer_role_bottom_products.php");
		}

		//CHANGE FETCH MENU VISIBLE MENUS
		function add_fetch_page_customer_role( $visible_menu ) {
			$group_role_menu=array(
				"label" => __('Customer Role/Group','it_report_wcreport_textdomain'),
				"id" => "customer_role_group",
				"link" => "#",
				"icon" => "fa-user-circle",
				"childs" => array(
					"customer_role_total_sale" => array(
						"label" => __("Total Sales" ,'it_report_wcreport_textdomain'),
						"id" => "customer_role_total_sale",
						"link" => "admin.php?page=wcx_wcreport_plugin_customer_role_total_sale&parent=customer_role_group&smenu=customer_role_total_sale",
						"icon" => "fa-usd",
					),
					"customer_role_registered" => array(
						"label" => '<span style="color:#d97c7c">'.__("New User Sign-Up(Pro)" ,'it_report_wcreport_textdomain').'</span>',
						"id" => "customer_role_registered",
						"link" => "#",
						"icon" => "fa-user-plus",
					),
					"customer_role_top_products" => array(
						"label" => __("Top 20 Products" ,'it_report_wcreport_textdomain'),
						"id" => "customer_role_top_products",
						"link" => "admin.php?page=wcx_wcreport_plugin_customer_role_top_products&parent=customer_role_group&smenu=customer_role_top_products",
						"icon" => "fa-level-up",
					),
					"customer_role_bottom_products" => array(
						"label" => '<span style="color:#d97c7c">'.__("Lowly 20 Products(Pro)" ,'it_report_wcreport_textdomain').'</span>',
						"id" => "customer_role_bottom_products",
						"link" => "#",
						"icon" => "fa-level-down",
					),
				)
			);


			global $it_rpt_main_class;
			$it_rpt_main_class->our_menu=$it_rpt_main_class->array_insert_after("customer_reports",$it_rpt_main_class->our_menu,"customer_role_group",$group_role_menu);
			$visible_menu=$it_rpt_main_class->our_menu;
			return $visible_menu;
		}

	}
	new it_report_wcreport_customer_role_addon_class;
}
?>
