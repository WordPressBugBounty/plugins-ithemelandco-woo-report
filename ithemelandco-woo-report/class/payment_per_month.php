<?php
	global $it_rpt_main_class;

    if (!$it_rpt_main_class->dashboard($it_rpt_main_class->it_plugin_status)){
        header("location:".admin_url()."admin.php?page=wcx_wcreport_plugin_active_report&parent=active_plugin");
    }else {
	    $smenu=$_REQUEST['smenu'];
	    $fav_icon=' fa-star-o ';
	    if($it_rpt_main_class->fetch_our_menu_fav($smenu)){
		    $fav_icon=' fa-star ';
	    }
	    
        $params_page = [
            "smenu" => $smenu,
            "fav_icon" => $fav_icon,
            "table_name" => 'payment_per_month'
        ];
         echo wp_kses(
    $it_rpt_main_class->it_generate_htmls( $params_page ),
    $it_rpt_main_class->allowedposttags()
);
    }
        ?>