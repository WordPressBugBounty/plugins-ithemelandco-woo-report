<?php
/*
Plugin Name: PW Advanced Woo Reporting Variation ADD-ON
Plugin URI: https://ithemelandco.com/plugins/woocommerce-report/
Description: This add-on adds extra reports(Variation & Stock Variation) to the main plugins(Advanced Woocommerce Reporting).
Version: 1.3
Author: iThemelandCo
Author URI: https://ithemelandco.com
Text Domain: it_report_wcreport_textdomain
Domain Path: /languages/
*/


/*
V1.3 :
	Update : Menu order and Breadcrumb issue
V1.2 : Update : Compatible with PW Advanced Woo Reporting ver 4.0
 */

define("__IT_VARIATION_ADD_ON__",'');
define( '__IT_REPORT_WCREPORT_ROOT_DIR_VARIATION_ADD_ON__', dirname(__FILE__));
define ('__IT_REPORT_WCREPORT_URL_VARIATION_ADD_ON__',plugins_url('', __FILE__));

if(!class_exists('it_report_wcreport_variation_addon_class')){
	
	class it_report_wcreport_variation_addon_class{
				
		function __construct(){
			add_action( 'it_report_wcreport_admin_menu', array($this,'add_variation_menu' ));
			add_filter( 'it_report_wcreport_page_fetch_menu', array($this,'add_fetch_page_variation_menu'));
			add_filter( 'it_report_wcreport_page_titles', array($this,'add_page_titles'));
		}
		
		function add_page_titles($page_titles){
			$page_titles['variation'] = __( "Purchased Variation",'it_report_wcreport_textdomain');
			$page_titles['variation_stock']=__( "Variation Stock",'it_report_wcreport_textdomain');

			return $page_titles;
		}
		
		function add_variation_menu() {
			
			global $it_rpt_main_class;
			$role_capability=$it_rpt_main_class->get_capability();
			
			add_submenu_page('itwrl_submenu', __('Variation','it_report_wcreport_textdomain'), __('Variation','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_variation',  array($this, 'wcx_plugin_menu_variation' ) );
			
			add_submenu_page('itwrl_submenu', __('Variation Stock','it_report_wcreport_textdomain'), __('Variation Stock','it_report_wcreport_textdomain'), $role_capability, 'wcx_wcreport_plugin_variation_stock', array($this, 'wcx_plugin_menu_variation_stock' ));
					
		}
		
		function wcx_plugin_menu_variation(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_VARIATION_ADD_ON__."/class/variation.php");
		}
		
		function wcx_plugin_menu_variation_stock(){
			global $it_rpt_main_class;
			$it_rpt_main_class->pages_fetch(__IT_REPORT_WCREPORT_ROOT_DIR_VARIATION_ADD_ON__."/class/variation_stock.php");
		}
		
		//CHANGE FETCH MENU VISIBLE MENUS
		function add_fetch_page_variation_menu( $visible_menu ) {
			
			$var_menu=array(
							"label" => '<span style="color:#d97c7c">'.__("Purchased Variation(Pro)",'it_report_wcreport_textdomain').'</span>',
							"id" => "variation",
							"link" => "#",
							"icon" => "fa-server",
						);
						
			$var_stock_menu=array(
							"label" => '<span style="color:#d97c7c">'.__("Variation Stock(Pro)",'it_report_wcreport_textdomain').'</span>',
							"id" => "variation_stock",
							"link" => "#",
							"icon" => "fa-rocket",
						);			


			global $it_rpt_main_class;
			//print_r($it_rpt_main_class->our_menu['product_reports']['childs']);
			$it_rpt_main_class->our_menu['product_reports']['childs']=$it_rpt_main_class->array_insert_after("product",$it_rpt_main_class->our_menu['product_reports']['childs'],"variation",$var_menu);

			$it_rpt_main_class->our_menu['product_reports']['childs']=$it_rpt_main_class->array_insert_after("stock_list",$it_rpt_main_class->our_menu['product_reports']['childs'],"variation_stock",$var_stock_menu);

			$visible_menu=$it_rpt_main_class->our_menu;
			return $visible_menu;
		}
		
	}
	new it_report_wcreport_variation_addon_class;
}
?>