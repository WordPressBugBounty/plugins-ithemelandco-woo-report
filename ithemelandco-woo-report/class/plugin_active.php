<?php

	$it_active_plugin = array(

		array(
			'label'	=> esc_html__('Purchase Code','ithemelandco-woo-report'),
			'desc'	=> esc_html__('Enter Your Purchase Code','ithemelandco-woo-report'),
			'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_purchase_code',
			'id'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_purchase_code',
			'type'	=> 'text',

		),

		array(
			'label'	=> esc_html__('Email','ithemelandco-woo-report'),
			'desc'	=> esc_html__('Enter Your Valid Email.','ithemelandco-woo-report'),
			'name'  => __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_email',
			'id'	=> __IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_email',
			'type'	=> 'text',
		
		),


	);
    $text_return='';
	if (isset($_POST["update_settings"])) {
		// Do the saving

		// Check nonce before processing any data
		if (!isset($_POST['it_report_nonce_field']) || !wp_verify_nonce($_POST['it_report_nonce_field'], 'it_report_nonce_action')) {
			// Exit if nonce is invalid or missing
			wp_die(__('Nonce verification failed', 'ithemelandco-woo-report'));
		}

        $email=isset($_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_email']) ? $_POST[__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_email']:"";

		foreach($_POST as $key=>$value){
			if(!isset($_POST[$key])){
				delete_option($key);
				continue;
			}

			$old = get_option($key);
			$new = $value;
			if(!is_array($new))
			{



				if ($new && $new != $old) {
					update_option($key, $new);
				} elseif ('' == $new && $old) {
					delete_option($key);
				}
			}else{


				$get_year=array_keys($value);
				$get_year=$get_year[0];

				foreach($value[$get_year] as $keys=>$vals){

					$old = get_option($key."_".$get_year."_".$keys);
					$new = $vals;

					if ($new && $new != $old) {
						update_option($key."_".$get_year."_".$keys, $new);
					} elseif ('' == $new && $old) {
						delete_option($key."_".$get_year."_".$keys);
					}

				}
			}
		}

		global $it_rpt_main_class;
		$field=__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_purchase_code';
		$it_rpt_main_class->it_plugin_status=get_option($field);


		$field=__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_email';
		$it_rpt_main_class->email=get_option($field);


		$text='';


		$check_db = $it_rpt_main_class->dashboard($it_rpt_main_class->it_plugin_status);

		if ($it_rpt_main_class->dashboard($it_rpt_main_class->it_plugin_status) && isset($check_db["verify-purchase"]["status"]) && $check_db["verify-purchase"]["status"]=='valid' && filter_var($it_rpt_main_class->email, FILTER_VALIDATE_EMAIL)){
			$text=esc_html__('Congratulation, The Plugin has been Activated Successfully !','ithemelandco-woo-report');
			?>
                <script>
                    jQuery(document).ready(function ($) {
                        setTimeout(function() {
                            $(".it_active_ok").attr("style", "display:block !important");
                            $(".it_active_email").attr("style", "display:none !important");
                        },500);
                    });
                </script>

            <?php
		}else if ((isset($check_db["verify-purchase"]["status"]) && $check_db["verify-purchase"]["status"]!='valid') || !filter_var($it_rpt_main_class->email, FILTER_VALIDATE_EMAIL)){
			$text=esc_html__('Unfortunately, The Purchase code is Wrong, Please try Again !','ithemelandco-woo-report');
			$text_return=$check_db["verify-purchase"]["status"];
			?>
                <script>
                    jQuery(document).ready(function ($) {
                        setTimeout(function(){
                            $(".it_active_error").attr("style","display:block !important");
                        },500);
                    });
                </script>

            <?php
		}
	}

    global $it_rpt_main_class;
	$field_1=$it_active_plugin[0];
	$field_2=$it_active_plugin[1];

	$meta_1 = get_option($field_1['id']);
	$meta_2 = get_option($field_2['id']);

    $text_ok=esc_html__('Congratulation, The Plugin has been Activated Successfully ! Move to ','ithemelandco-woo-report').'<a href="admin.php?page='.$it_rpt_main_class->it_plugin_main_url.'">'.esc_html__("Dashboard",'ithemelandco-woo-report').'</a>';
    $text_error=esc_html__('Unfortunately, The Purchase code is Wrong or Email is not Valid, Please try Again !','ithemelandco-woo-report');

	$html= '
    <div class="wrap">
        <div class="row">
                <div class="col-xs-12">
                    <div class="awr-box">
                            <div class="awr-title">
                                <h3><i class="fa fa-shield"></i>'.esc_html__('Plugin Activate','ithemelandco-woo-report').'  </h3>
                            </div><!--awr-title -->
                            <div class="awr-box-content" >
                                <div class="col-md-12">
                                    <div id="setting-error-settings_updated" class="updated settings-error it_active_ok">
                                        <p><strong>'.$text_ok.'</strong></p>
                                    </div>

                                    <div id="setting-error-settings_updated" class="error it_active_error">
                                        <p><strong>'.$text_error.'</strong></p>
                                        <p style="color: #f1c40f"><strong>'.$text_return.'</strong></p>
                                    </div>';
                                    global $it_rpt_main_class;
                                    $field=__IT_REPORT_WCREPORT_FIELDS_PERFIX__.'activate_email';
                                    $it_rpt_main_class->email=get_option($field);
                                    if(!filter_var($it_rpt_main_class->email, FILTER_VALIDATE_EMAIL)) {
	                                    $html .= '
                                        <div id="setting-error-settings_updated" class="updated email-notice it_active_email">
                                            <p><strong>' . esc_html__( 'Please set email for complete activation in Ver4.0', 'ithemelandco-woo-report' ) . '</strong></p>
                                        </div>';
                                    }
                                    $html.= '
                                </div>

                                <form method="POST" action="" class="awr-setting-form">
										'.wp_nonce_field('it_report_nonce_action', 'it_report_nonce_field')
.'
                                        <input type="hidden" name="update_settings" value="Y" />
                                        <div class="col-md-6">
                                            <div class="awr-form-title"><label>'.$field_1['label'].'</label></div>

                                            <input type="text" name="'.$field_1['id'].'" id="'.$field_1['id'].'" class="'.$field_1['id'].'" value="'.$meta_1.'" >
                                            <br /><div class="description">'.$field_1['desc'].'</div>

                                        </div>

                                        <div class="col-md-6">
                                            <div class="awr-form-title"><label>'.$field_2['label'].'</label></div>

                                            <input type="email" name="'.$field_2['id'].'" id="'.$field_2['id'].'" class="'.$field_2['id'].'" value="'.$meta_2.'" >
                                            <br /><div class="description">'.$field_1['desc'].'</div>

                                        </div>

                                        <div class="col-md-12">
                                            <div class="awr-setting-submit" style="margin-top:20px">
                                                <button type="submit" value="Save settings" class="button-primary"><i class="fa fa-floppy-o"></i> <span>'.esc_html__('Save Settings','ithemelandco-woo-report').'</span></button>

                                            </div>
                                        </div>
                                </form>
                            </div>

                    </div>
                </div>
        </div>
	</div>

	';

	//echo $html;

	echo wp_kses(
		$html,
		$it_rpt_main_class->allowedposttags()
	);
?>
