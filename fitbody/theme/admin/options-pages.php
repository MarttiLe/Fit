<?php

/* ***************************************************************************** */
/* OPTIONS PAGES                                                                 */
/* ***************************************************************************** */
/* Define custom admin menu pages here                                           */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */

// Create theme options page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}


?>