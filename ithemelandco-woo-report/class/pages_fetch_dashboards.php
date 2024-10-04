<div class="container my_content awr-maincontainer">

    <div id="loader-wrapper">
        <div id="loader" class="loader-7">
		  <div class="line line1"></div>
          <div class="line line2"></div>
          <div class="line line3"></div>
		</div>

        <div class="loader-section section-left" id="loader-section-left"></div>
        <div class="loader-section section-right" id="loader-section-right"></div>

        <script>
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        var it_reporting_theme=getCookie("it_reporting_theme");
        if(it_reporting_theme=='dark'){
          var element = document.getElementById("loader-section-left");
          element.style.backgroundColor =     "#2d2c40";

          var element = document.getElementById("loader-section-right");
          element.style.backgroundColor =     "#2d2c40";
        }
        </script>

    </div>

    <div class="pw-topbar-wrapper pw-full-menu" style="visibility: hidden">
        <div class="pw-logo-wrapper">
            <div class="pw-logo-cnt">
                <div id="pw-nav-icon1">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        </div>

		<div class="pw-righbar-icon" title="Get Pro Version" style="width: 204px;/* border: 1px solid #ccc; */background-color: #0a7a11;margin-left: 640px;">
			<span class="get-pro">
				<a target="_blank" href="https://ithemelandco.com/plugins/woocommerce-report?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=user-lite-buy">
					<i class="fa fa-star"></i> Get Pro Version
				</a>
			</span>
		</div>

        <div class="pw-rightbar-wrapper">

			<div class="pw-righbar-icon pw-dark-icon" title="<?php esc_html_e("Change Theme",'it_report_wcreport_textdomain');?>">
                <i class="fa fa-moon-o"></i>
            </div>

            <div class="pw-righbar-icon pw-switch-wordpress">
                <a class="pw-switch-wordpress-a" href="<?php echo esc_attr(get_admin_url());?>" title="<?php esc_html_e("Switch to wordpress dashboard",'it_report_wcreport_textdomain');?>">
                    <i class="fa fa-wordpress"></i>
                </a>
            </div>

            <div class="pw-righbar-icon pw-expand-icon" title="<?php esc_html_e("Expand Window",'it_report_wcreport_textdomain');?>">
                <i class="fa fa-expand"></i>

            </div></div>
    </div>

	<?php

	$menu_html='';
	$our_menu=apply_filters( 'it_report_wcreport_page_fetch_menu', $visible_menu );
	update_option("it_report_menus",wp_json_encode($our_menu));

	//print_r($our_menu);

	$basic_menu='';
	$tax_field_reports='';
	$more_reports='';
	$cross_menu='';
	$other_menu='';



	?>

    <div class="awr-allmenu-cnt" style="visibility:hidden">
        <div class="awr-allmenu-close"><i class="fa fa-times"></i></div>
        <div class="row">

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="awr-allmenu-box">
                    <div class="awr-menu-title"><i class="fa fa-check"></i><?php echo esc_html__('Basics','it_report_wcreport_textdomain'); ?></a></div>
					<?php 
						echo wp_kses(
							$basic_menu,
							$this->allowedposttags(),array('javascript')
						);
						?>
                </div>
            </div>

			<?php
			if($tax_field_reports!='')
			{
				?>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="awr-allmenu-box">
                        <div class="awr-menu-title"><i class="fa fa-random"></i><?php echo esc_html__('All Order with Taxonomies & Fields','it_report_wcreport_textdomain'); ?></a></div>
						<?php 
						//echo $tax_field_reports; 

						echo wp_kses(
							$tax_field_reports,
							$this->allowedposttags(),array('javascript')
						);
						?>
                    </div>
                </div>
				<?php
			}
			?>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="awr-allmenu-box">
                    <div class="awr-menu-title"><i class="fa fa-files-o"></i><?php echo esc_html__('More Reports','it_report_wcreport_textdomain'); ?></a></div>
					<?php 
					//echo $more_reports; 
					echo wp_kses(
						$more_reports,
						$this->allowedposttags(),array('javascript')
					);
					
					?>
                </div>
            </div>

			<?php
			if($cross_menu!='')
			{
				?>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="awr-allmenu-box">
                        <div class="awr-menu-title"><i class="fa fa-random"></i><?php echo esc_html__('CrossTab','it_report_wcreport_textdomain'); ?></a></div>
						<?php //echo $cross_menu; 
						echo wp_kses(
							$cross_menu,
							$this->allowedposttags(),array('javascript')
						);
						?>
                    </div>
                </div>
				<?php
			}
			?>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="awr-allmenu-box">
                    <div class="awr-menu-title"><i class="fa fa-check"></i><?php echo esc_html__('Other','it_report_wcreport_textdomain'); ?></a></div>
					<?php //echo $other_menu; 
					echo wp_kses(
						$other_menu,
						$this->allowedposttags(),array('javascript')
					);
					?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 awr-allmenu-footer">
                <h3><?php echo esc_html__('WOOCommerce Advance Reporting System','it_report_wcreport_textdomain'); ?></h3>
                <span>Powered By <a href="https://ithemelandco.com">iThemelandCo</a></span>
            </div>
        </div><!--row -->
    </div>


	<?php
	$menu_html='';
	$included_menus='';
	if ($this->dashboard($this->it_plugin_status)){
		$included_menus='';
	}else{
		$included_menus=array("dashboard","active_plugin");

		$this->our_menu = array(
			"logo" => array(
				"label" => '',
				"id" => "logo",
				"link" => '#',
				"icon" => esc_html(__IT_REPORT_WCREPORT_URL__) . "/assets/images/logo.png",
				"mini_icon" => esc_html(__IT_REPORT_WCREPORT_URL__) . "/assets/images/mini_logo.png",
			),

			"dashboard" => array(
				"label" => esc_html__('Dashboard', 'it_report_wcreport_textdomain'),
				"id" => "dashboard",
				"link" => $it_plugin_main_url,
				"icon" => "fa-bookmark",
			),
			"active_plugin" => array(
				"label" => esc_html__('Activate Plugin','it_report_wcreport_textdomain'),
				"id" => "active_plugin",
				"link" => "admin.php?page=wcx_wcreport_plugin_active_report&parent=active_plugin",
				"icon" => "fa-check",
			)
		);

		//array_push($visible_menu[0]['childs'],$no_dashboard_menu);
	}

	//$our_menu=apply_filters( 'it_report_wcreport_page_fetch_menu', $visible_menu );

	$parent=(isset($_GET['parent']) ? $_GET['parent']: "");
	$child=(isset($_GET['smenu']) ? $_GET['smenu']:"");
	$selected_menu=array("parent" => $parent , "smenu" => $child);

	$menu_html.=$this->it_menu_generator($this->our_menu,'',$selected_menu);
	$menu_html_mini=$this->it_menu_generator($this->our_menu,"mini",$selected_menu);
	update_option("it_report_menus",wp_json_encode($this->our_menu));

	?>
    <div class="awr-mini-menu" style="visibility: hidden">
		<?php //echo $menu_html_mini;
		echo wp_kses(
			$menu_html_mini,
			$this->allowedposttags(),array('javascript')
		);
		
		?>
    </div>

    <nav id="ml-menu" class="awr-menu"  style="visibility:hidden">
        <div class="awr-item" data-id="logo">
                <img src="<?php echo esc_html(__IT_REPORT_WCREPORT_URL__) . "/assets/images/logo.png"; ?>" class="small image">
        </div>
        <div class="awr-mainmenu-cnt">
            <div class="awr-mainmenu-list-cnt">
                <ul class="awr-mainmenu-list-ul">

					<?php //echo $menu_html;
					
					echo wp_kses(
						$menu_html,
						$this->allowedposttags(),array('javascript')
					);
					?>

                </ul>
            </div><!--awr-mainmenu-list-cnt -->

        </div>


    </nav>

    <!-- Main container -->

    <div class="awr-content" style="visibility:hidden">

        <div class="pw-breadcrumb-wrapper">

			<?php
			$parent=(isset($_GET['parent']) ? $_GET['parent']: "");
			$child=(isset($_GET['smenu']) ? $_GET['smenu']:"");
			$page_title='';
			$breadcrumb='';
			$our_menu=$this->our_menu;
			//echo $parent.'-';
			//echo $child;
			//print_r($our_menu);
			if($parent==$child || $child=='')
			{
				$breadcrumb='
                                <span>/</span>
                                <div class="pw-section pw-active">'.(isset($our_menu[$parent]['label']) ? $our_menu[$parent]['label'] : "").'</div>';
				$page_title=(isset($our_menu[$parent]['label']) ? $our_menu[$parent]['label'] : "");
			}else{
				$breadcrumb='<span>/</span>
                                <div class="pw-section">'.(isset($our_menu[$parent]['label']) ? $our_menu[$parent]['label'] : "Active Plugin").'</div>
                                <span>/</span>
                                <div class="pw-section pw-active">'.(isset($our_menu[$parent]['childs'][$child]['label']) ? $our_menu[$parent]['childs'][$child]['label'] : "").'</div>';
				$page_title=isset($our_menu[$parent]['childs'][$child]['label']) ? $our_menu[$parent]['childs'][$child]['label'] : "";
			}

			/////////In EDIT Page////////
			if(isset($_GET['action']) || isset($_GET['id']))
				$page_title=str_replace("Add",esc_html__('Edit', 'ddd'),$page_title);

			?>
			<div class="pw-bread-title"><?php echo esc_html($page_title); ?></div>
            <div class="pw-breadcrumb-cnt">
                <a class="pw-section" href="<?php echo esc_attr(get_admin_url());?>admin.php?page=wcx_wcreport_plugin_dashboard&parent=dashboard">Home</a>
				<?php //echo $breadcrumb; 
				
				echo wp_kses(
					$breadcrumb,
					$this->allowedposttags(),array('javascript')
				);
				?>

                <!--FOR INTELLIGENCE PAGES-->
                <div class="it_intelligence_search_form"></div>
            </div>


        </div>

		<?php
		if(defined("__IT_PERMISSION_ADD_ON__"))
		{

			if(isset($_REQUEST['parent']) && isset($_REQUEST['smenu']))
			{
				$enable_parent_menu=$this->get_menu_capability($_REQUEST['parent']);
				$enable_sub_menu=$this->get_menu_capability($_REQUEST['smenu']);

				if($this->get_menu_capability($_REQUEST['parent']) && $this->get_menu_capability($_REQUEST['smenu']))
				{
					if ($this->dashboard($this->it_plugin_status) || $page=='plugin_active.php' || $page=="dashboard_report.php"){
						include($page);
					}else{
						$page='plugin_active.php';
						include($page);
					}
				}else{
					echo '
								<div class="wrap">
									<div class="row">
										<div class="col-xs-12">
											<div class="awr-box awr-acc-box">
												<div class="awr-acc-icon">
												    <i class="fa fa-meh-o"></i>
												</div>
												<h3 class="awr-acc-title">'. esc_html__("Access Denied !",'it_report_wcreport_textdomain').'</h3>
												<div class="awr-acc-desc">'. esc_html__("You have no permisson !! Please Contact site Administrator",'it_report_wcreport_textdomain').'</div>
											</div>
										</div><!--col-xs-12 -->
									</div><!--row -->
								</div><!--wrap -->';
				}

			}elseif(isset($_REQUEST['parent']) && !isset($_REQUEST['smenu'])){

				if($this->get_menu_capability($_REQUEST['parent']))
				{
					if ($this->dashboard($this->it_plugin_status) || $page=='plugin_active.php' || $page=="dashboard_report.php"){
						include($page);
					}else{
						$page='plugin_active.php';
						include($page);
					}
				}else{
					echo '
								<div class="wrap">
									<div class="row">
										<div class="col-xs-12">
											<div class="awr-box awr-acc-box">
												<div class="awr-acc-icon">
												    <i class="fa fa-meh-o"></i>
												</div>
												<h3 class="awr-acc-title">'. esc_html__("Access Denied !",'it_report_wcreport_textdomain').'</h3>
												<div class="awr-acc-desc">'. esc_html__("You have no permisson !! Please Contact site Administrator",'it_report_wcreport_textdomain').'</div>
											</div>
										</div><!--col-xs-12 -->
									</div><!--row -->
								</div><!--wrap -->';
				}
			}

		}
		else{
			if ($this->dashboard($this->it_plugin_status) || $page=='plugin_active.php' || $page=="dashboard_report.php"){
				include($page);
			}else{
				$page='plugin_active.php';
				include($page);
			}
		}


		//echo $parent;


		?>

        <!-- Ajax loaded content here -->
    </div>
</div>
