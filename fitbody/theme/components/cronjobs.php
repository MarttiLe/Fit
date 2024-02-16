<?php

/* ***************************************************************************** */
/* WP CRONS                                                                      */
/* ***************************************************************************** */
/* Define all Wordpress cronjobs used by the theme here                          */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


add_action( 'theme_cron_remove_expired_programs', 'theme_cron_remove_expired_programs' );
 
function theme_cron_remove_expired_programs() {

	$users = get_users();

	foreach($users as $user) {
		$user_programs = theme_get_user_programs($user->ID);
		if(!empty($user_programs)) {
			foreach($user_programs as $key => $program) {
				$is_expired = theme_get_program_expiry_status($key, $user->ID, $user_programs);
				if($is_expired) {
					theme_remove_program_from_user_meta($key, $user->ID);
					// TODO: Send expiry email to the user
				}
			}
		}
	}

}

?>