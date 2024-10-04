<?php
echo '
<div class="awr-news-header">
	<div class="awr-news-header-big">'. esc_html__("Request Form",'it_report_wcreport_textdomain') .'</div>
	<div class="awr-news-header-mini">'. esc_html__("Send your request / issue for us",'it_report_wcreport_textdomain') .'</div>
</div>
<div class="awr-request-form">

   <form action="" class="it_request_form">
	    <div class="row">
	        <div class="col-md-12">
				<span class="awr-form-icon"><i class="fa fa-user"></i></span>
	            <input name="awr_fullname" id="awr_fullname" type="text" placeholder="'. esc_html__("Enter Full Name..",'it_report_wcreport_textdomain') .'" >
	        </div>

	        <div class="col-md-12">
				<span class="awr-form-icon"><i class="fa fa-envelope-o"></i></span>
	            <input name="awr_email" id="awr_email" type="text" placeholder="'. esc_html__("Enter Email.",'it_report_wcreport_textdomain') .'" value="'.esc_attr(get_option("admin_email")).'">
	        </div>

	        <div class="col-md-12">
				<span class="awr-form-icon"><i class="fa fa-check"></i></span>
	            <select name="awr_subject" class="">
	                <option value="">'. esc_html__("Select Subject",'it_report_wcreport_textdomain') .' </option>
	                <option value="request">'. esc_html__("Send a Request",'it_report_wcreport_textdomain') .'</option>
	                <option value="issue">'. esc_html__("Report an issue",'it_report_wcreport_textdomain') .'</option>
				</select>
	        </div>

	        <div class="col-md-12">
				<span class="awr-form-icon"><i class="fa fa-font"></i></span>
	            <input name="awr_title" id="awr_title" type="text" placeholder="'. esc_html__("Enter Title.",'it_report_wcreport_textdomain') .'" >
	        </div>

	        <div class="col-md-12">
	            <textarea name="awr_content" id="awr_content" placeholder="'. esc_html__("Enter Your request / issue...",'it_report_wcreport_textdomain') .'"></textarea>
	        </div>

	        <div class="col-md-12">
				<div class="fetch_form_loading fetch_form_loading_request search-form-loading"></div>
	            <button type="submit" value="Search" class="button-primary it_request_form_submit"><i class="fa fa-reply"></i> <span>'. esc_html__("Send",'it_report_wcreport_textdomain') .'</span></button>
	        </div>
	        <div class="col-md-12 it_request_form_message">

			</div>
	    </div>
   </form>

</div>';
?>
