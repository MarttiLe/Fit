<?php

// GET EXECUTION TIME
function theme_debug_get_execution_time() {
	return microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
}


// E-MAIL NOTIFICATIONS FOR ADMIN
// Use this function when building error handling, in the case of serious errors
function theme_debug_send_report_email($title = '', $message = '', $additional_emails = false) {
	// Get data
	$emails = [];
	$admin_email = get_option('admin_email');
	array_push($emails, $admin_email);
	array_push($emails, $additional_emails);
	$user_id = get_current_user_id();
	$user_data = get_userdata($user_id);
	$user_email = $user_data->user_email;
	$site_name = get_option('blogname');
	if(empty($title)) {
		$title = __('Error notification - user encountered a serious error', 'fitbody-theme');
	}
	$content = '<strong>Affected user:</strong> ID '. $user_id .' ('. $user_email .')<br><br>' . $message;

	// Build report email
	$to = $emails;
    $subject = $site_name . ' | ' . $title;
	$message = $content;
	$headers = array('Content-Type: text/html; charset=UTF-8');

	// Send email
	$mail_sent = wp_mail($to, $subject, $message, $headers);

	if($mail_sent) {
		return true;
	} else {
		return false;
	}
}