<?php
global $it_rpt_main_class;


global $wpdb;

$order_date="SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN ('trash') GROUP BY it_posts.ID Order By it_posts.post_date ASC LIMIT 5";
$results= $wpdb->get_results($wpdb->prepare("SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN (%s) GROUP BY it_posts.ID Order By it_posts.post_date ASC LIMIT 5",'trash'));

$first_date='';
$i=0;
while($i<5){

	if(count($results)>0 && $results[$i]->order_date!=0)
	{
		if(isset($results[$i]))
			$first_date=$results[$i]->order_date;
		break;
	}
	$i++;
}

$order_date="SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN ('trash') GROUP BY it_posts.ID Order By it_posts.post_date DESC LIMIT 1";
$results= $wpdb->get_results($wpdb->prepare("SELECT it_posts.ID AS order_id, it_posts.post_date AS order_date, it_posts.post_status AS order_status FROM {$wpdb->prefix}posts as it_posts WHERE it_posts.post_type='shop_order' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND it_posts.post_status NOT IN (%s) GROUP BY it_posts.ID Order By it_posts.post_date DESC LIMIT 1",'trash'));

$it_to_date='';
if(isset($results[0]))
	$it_to_date=$results[0]->order_date;

if($first_date==''){
	$first_date= gmdate("Y-m-d");

	if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$first_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'from_date',$first_date);
	}

	$this->it_from_date_dashboard=$first_date;
	$first_date=substr($first_date,0,4);
}else{

	if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$first_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'from_date',$first_date);
	}

	$it_from_date_dashboard=explode(" ",$first_date);
	$this->it_from_date_dashboard=$it_from_date_dashboard[0];

	$first_date=substr($first_date,0,4);
}

if($it_to_date==''){
	$it_to_date= gmdate("Y-m-d");
	if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$it_to_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'to_date',$it_to_date);
	}
	$this->it_to_date_dashboard=$it_to_date;
	$it_to_date=substr($it_to_date,0,4);
}else{
	if(get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'dashboard_status')=='true' && get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'customize_date')=='true'){
		$it_to_date=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'to_date',$it_to_date);
	}
	$it_to_date_dashboard=explode(" ",$it_to_date);
	$this->it_to_date_dashboard=$it_to_date_dashboard[0];

	$it_to_date=substr($it_to_date,0,4);
}


?>

<div class="wrap">
    <div class="row">

        <div class="col-xs-12 col-md-12">
            <div class="awr-box">
			    <?php
			    $table_name='dashboard_report';
			    $it_rpt_main_class->search_form_html($table_name);
			    ?>
            </div>
        </div>

        <div class="col-xs-12 col-md-12">
            <div class="awr-box">


                <div id="target">
                    <?php
                    $table_name='dashboard_report';
                   	$it_rpt_main_class->table_html($table_name);
                    ?>
                </div>
            </div>
        </div>


		<?php
		if ($this->dashboard($this->it_plugin_status)){
			?>


			<?php
			$disbale_chart=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_chart',"off");
			if($this->get_dashboard_capability('charts') && $disbale_chart=='off'){
				?>
                <!--CHARTS/TABS-->
                <div class="col-xs-12 col-md-12">
                    <div class="awr-box ">
                    <div class="tabs tabsA tabs-style-underline">
                        <nav>
                            <ul class="tab-uls">

								<?php
								if($this->get_dashboard_capability('sale_by_months_chart')){
									?>

                                    <li><a href="#section-bar-1" class="" data-target="pwr_chartdiv_month"> <span><?php echo esc_html__('Sales By Months','it_report_wcreport_textdomain') ?></span></a></li>


									<?php
								}
								if($this->get_dashboard_capability('sale_by_days_chart')){
									?>
                                    <li><a href="#section-bar-2" class="" data-target="pwr_chartdiv_day"> <span><?php echo esc_html__('Sales By Days','it_report_wcreport_textdomain') ?></span></a></li>

									<?php
								}
								if($this->get_dashboard_capability('3d_month_chart_chart')){
									?>
                                    <li><a href="#section-bar-3" class="" data-target="pwr_chartdiv_multiple"> <span><?php echo esc_html__('3D Month Chart','it_report_wcreport_textdomain') ?></span></a></li>

									<?php
								}
								if($this->get_dashboard_capability('top_products_chart')){
									?>
                                    <li><a href="#section-bar-4" class="" data-target="pwr_chartdiv_pie"> <span><?php echo esc_html__('Top Products','it_report_wcreport_textdomain') ?></span></a></li>
									<?php
								}
								?>
                            </ul>
                        </nav>

                        <div class="awr-theme-chart">
                            <ul>
                                <li  class="awr-theme-chart-title">
                                    <span class=""><?php echo  esc_html__('Click to change theme','it_report_wcreport_textdomain'); ?>:&nbsp;&nbsp;</span>
                                </li>

                                <li class="it_switch_chart_theme it_switch_chart_theme_light" data-theme="light">
                                    <img width="36" height="35" src="<?php echo esc_html(__IT_REPORT_WCREPORT_URL__)?>/assets/images/theme_light2.png" alt="theme_light">
                                </li>

                                <li class="it_switch_chart_theme it_switch_chart_theme_dark" data-theme="dark">
                                    <img width="36" height="35" src="<?php echo esc_html(__IT_REPORT_WCREPORT_URL__)?>/assets/images/theme_dark2.png" alt="theme_dark">
                                </li>

                                <li class="it_switch_chart_theme " data-theme="patterns">
                                    <img width="36" height="35" src="<?php echo esc_html(__IT_REPORT_WCREPORT_URL__)?>/assets/images/theme_pattern2.png" alt="theme_patterns">
                                </li>

                                <li class="it_switch_chart_theme " data-theme="none">
                                    <img width="36" height="35" src="<?php echo esc_html(__IT_REPORT_WCREPORT_URL__)?>/assets/images/theme_none.png" alt="theme_none">
                                </li>

                            </ul>
                        </div>

                        <div class="content-wrap">

							<?php
							if($this->get_dashboard_capability('sale_by_months_chart')){
								?>
                                <section id="section-bar-1">
                                    <div id="pwr_chartdiv_month" style="width: 100%; height: 450px;"></div>
                                </section>

								<?php
							}
							if($this->get_dashboard_capability('sale_by_days_chart')){
								?>
                                <section id="section-bar-2">
                                    <div id="pwr_chartdiv_day" style="width: 100%; height: 450px;"></div>
                                </section>

								<?php
							}
							if($this->get_dashboard_capability('3d_month_chart_chart')){
								?>
                                <section id="section-bar-3">
                                    <div id="pwr_chartdiv_multiple" style="width: 100%; height: 550px;"></div>
                                </section>

								<?php
							}
							if($this->get_dashboard_capability('top_products_chart')){
								?>
                                <section id="section-bar-4">
                                    <div id="pwr_chartdiv_pie" style="width: 100%; height: 450px;"></div>
                                </section>
								<?php
							}
							?>

                        </div>
                    </div>

                </div>
                </div>
			<?php } ?>


			<?php
			$disbale_map=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_map',"off");
			$disbale_map='on';
			if($this->get_dashboard_capability('map') && $disbale_map=='off'){
				?>
                <!--MAP--><!---->
                <div class="col-xs-12 col-md-12">
                    <div class="awr-box">
                        <div class="awr-title">
                            <h3><i class="fa fa-desktop"></i><?php esc_html_e('Map','it_report_wcreport_textdomain');?></h3>
                            <div class="awr-title-icons">
                                <div class="awr-title-icon awr-toggle-icon"><i class="fa fa-arrow-up"></i></div>
                                <div class="awr-title-icon awr-setting-icon"><i class="fa fa-cog"></i></div>
                                <div class="awr-title-icon awr-close-icon"><i class="fa fa-times"></i></div>
                            </div>
                        </div>

                        <div class="awr-box-content container5 pw-map-content">
                            <div class="map">
                                <span>Alternative content for the map</span>
                            </div>


                            <div class="rightPanel">
                                <h2><?php echo  esc_html__('Select a year','it_report_wcreport_textdomain'); ?></h2>
                                <div class="knobContainer">
                                    <input class="knob" data-width="80" data-height="80" data-min="<?php echo esc_attr($first_date);?>" data-max="<?php echo esc_html($it_to_date); ?>" data-cursor=true data-fgColor="#454545" data-thickness=.45 value="<?php echo esc_attr($first_date);?>" data-bgColor="#c7e8ff" />
                                </div>
                                <div class="areaLegend">
                                    <span>Alternative content for the legend</span>
                                </div>
                                <div class="plotLegend"></div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
			?>



			<?php
			if($this->get_dashboard_capability('datagrids')){
				?>
                <!--DATA GRID-->


				<?php
				if($this->get_dashboard_capability('monthly_summary')){
					?>
                    <div class="col-xs-12 col-md-12">
						<?php
						$table_name='monthly_summary';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>

					<?php
				}
				if($this->get_dashboard_capability('order_summary')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='order_summary';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>

					<?php
				}
				if($this->get_dashboard_capability('sale_order_status')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='sale_order_status';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>
                    <div class="awr-clearboth"></div>

					<?php
				}

				if($this->get_dashboard_capability('top_products')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='top_5_products';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>

					<?php
				}
				if($this->get_dashboard_capability('top_category')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='top_5_category';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>
                    <div class="awr-clearboth"></div>
					<?php
				}
				if($this->get_dashboard_capability('top_billing_country')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='top_5_country';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>

					<?php
				}
				if($this->get_dashboard_capability('top_biling_state')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='top_5_state';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>
                    <div class="awr-clearboth"></div>
					<?php
				}
				if($this->get_dashboard_capability('recent_orders')){
					?>
                    <div class="col-xs-12 col-md-12">
						<?php
						$table_name='recent_5_order';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>

					<?php
				}
				if($this->get_dashboard_capability('top_customers')){
					?>
                    <div class="col-xs-12 col-md-12">
						<?php
						$table_name='top_5_customer';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>

					<?php
				}
				if($this->get_dashboard_capability('top_coupon')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='top_5_coupon';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>

					<?php
				}
				if($this->get_dashboard_capability('top_payment_gateway')){
					?>
                    <div class="col-xs-12 col-md-6">
						<?php
						$table_name='top_5_gateway';
						$it_rpt_main_class->table_html($table_name);
						?>
                    </div>
					<?php
				}
			}//END PERMISSION
			?>


			<?php
		}//END DASHBOARD FUNCITON CHECK
		?>

    </div><!--row -->
</div>

<?php


$country_values=array();
$areas=array();

$all_country=array();
for($year=$first_date;$year<=$it_to_date;$year++){
	$Country_sql="SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'BillingCountry' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID WHERE it_posts.post_type	=	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '".$year."-01-01' AND '".$year."-12-30' AND it_posts.post_status NOT IN ('trash') GROUP BY it_postmeta2.meta_value Order By Total DESC";

	//echo($Country_sql);

	$results= $wpdb->get_results($wpdb->prepare("SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'BillingCountry' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID WHERE it_posts.post_type	=	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '%s-01-01' AND '%s-12-30' AND it_posts.post_status NOT IN (%s) GROUP BY it_postmeta2.meta_value Order By Total DESC",array($year,$year,'trash')));

	foreach($results as $items){

		if($items->BillingCountry=='')
			continue;

		$all_country[]=$items->BillingCountry;
	}
}

for($year=$first_date;$year<=$it_to_date;$year++){
	$Country_sql="SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'BillingCountry' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID WHERE it_posts.post_type	=	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '".$year."-01-01' AND '".$year."-12-30' AND it_posts.post_status NOT IN ('trash') GROUP BY it_postmeta2.meta_value Order By Total DESC";

	$results= $wpdb->get_results($wpdb->prepare("SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'BillingCountry' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID WHERE it_posts.post_type	=	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '%s-01-01' AND '%s-12-30' AND it_posts.post_status NOT IN (%s) GROUP BY it_postmeta2.meta_value Order By Total DESC",array($year,$year,'trash')));

	$this_year_country=array();

	foreach($results as $items){

		if($items->BillingCountry=='')
			continue;

		$country      	= $this->it_get_woo_countries();//Added 20150225
		$it_table_value = isset($country->countries[$items->BillingCountry]) ? $country->countries[$items->BillingCountry]: $items->BillingCountry;


		$country_values[]=round($items->Total,0);
		$areas[$year][$items->BillingCountry]= array(
			"value" => $items->Total,
			"href" => "http://en.wikipedia.org/w/index.php?search=".$it_table_value,
			"tooltip" => array(
				"content" => "<span style=\"font-weight:bold;\">".$it_table_value."</span><br /><span style=\"font-weight:bold;\">".  $this->price($items->Total) . " # " .$items->OrderCount."</span><br />Total : ".$items->Total
			));
		$this_year_country[]=$items->BillingCountry;
	}



	if(is_array($this_year_country) && is_array($all_country) && count($all_country)>0 && count($this_year_country)>0)
	{
		$diff_array=array_diff($all_country,$this_year_country);

		foreach($diff_array as $diff_country){
			$country      	= $this->it_get_woo_countries();
			$it_table_value = isset($country->countries[$diff_country]) ? $country->countries[$diff_country]: $diff_country;



			//$country_values[]=0;
			$areas[$year][$diff_country]= array(
				"value" => "0",
				"href" => "http://en.wikipedia.org/w/index.php?search=".$it_table_value,
				"tooltip" => array(
					"content" => "<span style=\"font-weight:bold;\">".$it_table_value."</span><br /><span style=\"font-weight:bold;\">".  $this->price(0) . " #0</span><br />Total : 0"
				));
		}
	}
}

$plots=array();
$state_values=array();
$state=array();

$all_states=array();

//GET ALL STATES
for($year=$first_date;$year<=$it_to_date;$year++){
	$State_sql="SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'billing_state' ,it_postmeta3.meta_value AS 'billing_country' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_posts.ID WHERE it_posts.post_type =	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_state' AND it_postmeta3.meta_key	= '_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '".$year."-01-01' AND '".$year."-12-01' AND it_posts.post_status NOT IN ('trash') GROUP BY it_postmeta2.meta_value Order By Total DESC";

	$results= $wpdb->get_results($wpdb->prepare("SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'billing_state' ,it_postmeta3.meta_value AS 'billing_country' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_posts.ID WHERE it_posts.post_type =	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_state' AND it_postmeta3.meta_key	= '_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '%s-01-01' AND '%s-12-30' AND it_posts.post_status NOT IN (%s) GROUP BY it_postmeta2.meta_value Order By Total DESC",array($year,$year,'trash')));

	foreach($results as $items){

		if($items->billing_state=='' || $items->billing_country=='')
			continue;

		$it_table_value=$this->it_get_woo_bsn($items->billing_country,$items->billing_state);

		//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
		$this_state=explode("(",$it_table_value);
		$this_state=str_replace("-","_",$this_state[0]);
		$it_table_value=$this_state;

		$all_states[]=$it_table_value;
	}
}

//print_r($all_states);

for($year=$first_date;$year<=$it_to_date;$year++){
	$State_sql="SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'billing_state' ,it_postmeta3.meta_value AS 'billing_country' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_posts.ID WHERE it_posts.post_type =	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_state' AND it_postmeta3.meta_key	= '_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '".$year."-01-01' AND '".$year."-12-01' AND it_posts.post_status NOT IN ('trash') GROUP BY it_postmeta2.meta_value Order By Total DESC";

	$results= $wpdb->get_results($wpdb->prepare("SELECT SUM(it_postmeta1.meta_value) AS 'Total' ,it_postmeta2.meta_value AS 'billing_state' ,it_postmeta3.meta_value AS 'billing_country' ,Count(*) AS 'OrderCount' FROM {$wpdb->prefix}posts as it_posts LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta1 ON it_postmeta1.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta2 ON it_postmeta2.post_id=it_posts.ID LEFT JOIN {$wpdb->prefix}postmeta as it_postmeta3 ON it_postmeta3.post_id=it_posts.ID WHERE it_posts.post_type =	'shop_order' AND it_postmeta1.meta_key =	'_order_total' AND it_postmeta2.meta_key	=	'_billing_state' AND it_postmeta3.meta_key	= '_billing_country' AND it_posts.post_status IN ('wc-completed', 'wc-on-hold', 'wc-processing') AND DATE(it_posts.post_date) BETWEEN '%s-01-01' AND '%s-12-30' AND it_posts.post_status NOT IN (%s) GROUP BY it_postmeta2.meta_value Order By Total DESC",array($year,$year,'trash')));

	$this_year_states=array();

	foreach($results as $items){

		if($items->billing_state=='' || $items->billing_country=='')
			continue;



		$it_table_value=$this->it_get_woo_bsn($items->billing_country,$items->billing_state);
		$it_table_values=strtolower(str_replace(" ","",$it_table_value));

		//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
		$this_state=explode("(",$it_table_values);
		$this_state=str_replace("-","_",$this_state[0]);
		$it_table_values=$this_state;

		$state[]=$it_table_values;
		$state_values[]=round($items->Total,0);
		$plots[$year][$it_table_values]= array(
			"value" => $items->Total,
			"tooltip" => array(
				"content" => "<span style=\"font-weight:bold;\">".$it_table_value."</span><br /><span style=\"font-weight:bold;\">".  $this->price($items->Total) . " # " .$items->OrderCount."</span><br />Total : ".$items->Total
			));

		//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
		$this_state=explode("(",$it_table_value);
		$this_state=str_replace("-","_",$this_state[0]);
		$it_table_value=$this_state;

		$this_year_states[]=$it_table_value;
	}

	if(is_array($this_year_states) && is_array($all_states)  && count($all_states)>0 && count($this_year_states)>0)
	{
		$diff_array=array_diff($all_states,$this_year_states);
		foreach($diff_array as $diff_state){

			//$state_values[]=0;

			$it_table_values=strtolower(str_replace(" ","",$diff_state));


			//REMOVE ( FROM STATE NAME : EXMP : Spain(Madrid) => Spain
			$this_state=explode("(",$it_table_values);
			$this_state=str_replace("-","_",$this_state[0]);
			$it_table_values=$this_state;

			$state[]=$it_table_values;
			$plots[$year][$it_table_values]= array(
				"value" => "0",
				"tooltip" => array(
					"content" => "<span style=\"font-weight:bold;\">".$diff_state."</span><br /><span style=\"font-weight:bold;\">".  $this->price(0) . " # 0</span><br />Total : 0"
				));
		}
	}
}



//print_r($plots);
$map_date=array();

if($first_date!=$it_to_date){
	for($year=$first_date;$year<=$it_to_date;$year++){

		$a_years=isset($areas[$year]) ? $areas[$year]: "";
		$p_years=isset($plots[$year]) ? $plots[$year]: "";

		$map_date[$year]=array("areas" =>$a_years,"plots" =>$p_years);
	}
}else{
	$year=$first_date;
	$a_years=isset($areas[$year]) ? $areas[$year]: "";
	$p_years=isset($plots[$year]) ? $plots[$year]: "";

	$map_date[$year]=array("areas" =>$a_years,"plots" =>$p_years);
}

//print_r($map_date);


/////////SESARCH RANGES//////////
$first_limit_country=$two_limit_country=$first_limit_state=$two_limit_state=false;
if(is_array($country_values) && count($country_values)>0)
{
	sort($country_values);
	$max_counrty= max($country_values);
	$math=round($max_counrty/3,0);
	$first_limit_country=$math;
	$two_limit_country=$math+$math;
}

if(is_array($state_values) && count($state_values)>0)
{
	sort($state_values);
	$max_state= max($state_values);
	$math=round($max_state/3,0);
	$first_limit_state=$math;
	$two_limit_state=$math+$math;
}

/*

//--------------

*/


//////////////////


//print_r($map_date);

$arr=$map_date;
?>

<?php
$disbale_map=get_option(__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'disable_map',"off");
if($this->get_dashboard_capability('map') && $disbale_map=='off'){
	?>
    <script type="text/javascript">

        var data = <?php echo (wp_json_encode($arr)=='' ? "''":wp_json_encode($arr)) ; ?>;

        jQuery( document ).ready(function( $ ) {

            var myarray =[];
            var myJSON = new Object();
			<?php
			//die(print_r($state));
			foreach((array)$state as $state_name){
			if($state_name=='' || is_numeric($state_name))	continue;
			?>

            var geocoder = new google.maps.Geocoder();
            var address = "<?php echo esc_html($state_name);?>";

            geocoder.geocode( { 'address': address}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();


                    var item = {
                        "latitude": latitude,
                        "longitude": longitude,
                        "text": {
                            "position": "left",
                            "content": "",
                        }
                    };

                    myJSON.<?php echo esc_attr(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $state_name))));?>=item;
                }

            });

			<?php
			}
			?>

            setTimeout(function(){

                // Default plots params
                var plots = myJSON;


                $(".knob").knob({
                    release : function (value) {
                        $(".container5").trigger('update', [data[value], {}, {}, {animDuration : 300}]);
                    }
                });

                // Mapael initialisation
                $world = $(".container5");
                $world.mapael({
                    map : {
                        name : "world_countries",
                        defaultArea: {
                            attrs : {
                                fill: "#eee",
                                stroke : "#aaa",
                                "stroke-width" : 0.3
                            }
                        },
                        defaultPlot: {
                            text : {
                                attrs: {
                                    fill:"#333"
                                },
                                attrsHover: {
                                    fill:"#fff",
                                    "font-weight":"bold"
                                }
                            }
                        }
                        , zoom : {
                            enabled : true
                            //,mousewheel :false
                            , step : 0.25
                            , maxLevel : 20
                        }
                    },
                    legend : {
						<?php
						global  $woocommerce;

						if($first_limit_country){
						?>
                        area : {
                            display : true,
                            title :"<?php esc_html_e('Country Orders Amount','it_report_wcreport_textdomain');?>",
                            marginBottom : 7,
                            slices : [
                                {
                                    max :<?php echo esc_html($first_limit_country); ?>,
                                    attrs : {
                                        fill : "#6ECBD4"
                                    },
                                    label :'<?php esc_html_e('Less than','it_report_wcreport_textdomain');?> <?php echo  esc_html($first_limit_country).' '.esc_attr(get_woocommerce_currency()); ?>'
                                },
                                {
                                    min :<?php echo esc_html($first_limit_country); ?>,
                                    max :<?php echo esc_html($two_limit_country); ?>,
                                    attrs : {
                                        fill : "#3EC7D4"
                                    },
                                    label :'> <?php echo esc_html($first_limit_country).' '.esc_attr(get_woocommerce_currency()); ?> <?php esc_html_e('and','it_report_wcreport_textdomain');?> < <?php echo esc_html($two_limit_country).' '.esc_attr(get_woocommerce_currency()); ?>'
                                },
                                {
                                    min :<?php echo esc_html($two_limit_country); ?>,
                                    attrs : {
                                        fill : "#01565E"
                                    },
                                    label :'<?php esc_html_e('More than','it_report_wcreport_textdomain');?> <?php echo esc_html($two_limit_country).' '.esc_attr(get_woocommerce_currency()); ?>'
                                }
                            ]
                        },
						<?php
						}
						if($first_limit_state){
						?>
                        plot :{
                            display : true,
                            title: "<?php esc_html_e('State Orders Amount','it_report_wcreport_textdomain');?>",
                            marginBottom : 6,
                            slices : [
                                {
                                    type :"circle",
                                    max :<?php echo esc_html($first_limit_state); ?>,
                                    attrs : {
                                        fill : "#FD4851",
                                        "stroke-width" : 1
                                    },
                                    attrsHover :{
                                        transform : "s1.5",
                                        "stroke-width" : 1
                                    },
                                    label :"<?php esc_html_e('Less than','it_report_wcreport_textdomain');?> <?php echo esc_html($first_limit_state).' '.esc_attr(get_woocommerce_currency());?>",
                                    size : 10
                                },
                                {
                                    type :"circle",
                                    min :<?php echo esc_html($first_limit_state); ?>,
                                    max :<?php echo esc_html($two_limit_state); ?>,
                                    attrs : {
                                        fill : "#FD4851",
                                        "stroke-width" : 1
                                    },
                                    attrsHover :{
                                        transform : "s1.5",
                                        "stroke-width" : 1
                                    },
                                    label :"> <?php echo esc_html($first_limit_state).' '.esc_attr(get_woocommerce_currency()).' '; ?> <?php esc_html_e('and','it_report_wcreport_textdomain');?> < <?php echo esc_html($two_limit_state).' '.esc_attr(get_woocommerce_currency()); ?>",
                                    size : 20
                                },
                                {
                                    type :"circle",
                                    min :<?php echo esc_html($two_limit_state); ?>,
                                    attrs : {
                                        fill : "#FD4851",
                                        "stroke-width" : 1
                                    },
                                    attrsHover :{
                                        transform : "s1.5",
                                        "stroke-width" : 1
                                    },
                                    label :"<?php esc_html_e('More than','it_report_wcreport_textdomain');?> <?php echo ' '.esc_html($two_limit_state).' '.esc_attr(get_woocommerce_currency()); ?>",
                                    size : 30
                                }
                            ]
                        }
						<?php
						}
						?>
                    },
                    plots : $.extend(true, {}, data[<?php echo esc_html($first_date);?>]['plots'], plots),
                    areas: data[<?php echo esc_html($first_date);?>]['areas']
                });

            },2000);

        });
    </script>
	<?php
}
?>

<script>
    jQuery( document ).ready(function( $ ) {



        var toggle=true;
        $(".awr-news-read-oldest").on("click",function(){
            if(toggle){
                $(".awr-news-read-oldest").html("<?php echo esc_html__('Hide Oldest News !','it_report_wcreport_textdomain')?>");
            }else
            {
                $(".awr-news-read-oldest").html("<?php echo esc_html__('Show Oldest News !','it_report_wcreport_textdomain')?>");
            }

            $(".awr-news-oldest").toggle("slideUp");

            toggle=!toggle;
        });


        [].slice.call( document.querySelectorAll( ".tabsA" ) ).forEach( function( el ) {
            new CBPFWTabs( el );
        });

        [].slice.call( document.querySelectorAll( ".tabsB" ) ).forEach( function( el ) {
            new CBPFWTabs( el );
        });


    });
</script>
