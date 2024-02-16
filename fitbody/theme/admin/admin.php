<?php

/* ***************************************************************************** */
/* THEME CUSTOM ADMIN CUSTOMIZATION                                              */
/* ***************************************************************************** */
/* Customization for the backend admin panel                                     */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// THEME HIDE ADMIN MENU ITEMS
function theme_hide_admin_menu_items() {
	if(is_admin()) {
		global $user_ID;

		if(!ENABLE_POSTS) {
			// Remove posts
			remove_menu_page('edit.php');
		}

		if(!ENABLE_COMMENTS) {
			// Remove comments
			remove_menu_page('edit-comments.php');
		}

		// WPClever
		remove_menu_page('wpclever');
	}
}
add_action( 'admin_menu', 'theme_hide_admin_menu_items' );


// HIDE ACF IN ADMIN MENU
function theme_show_acf_in_admin( $show ) {
	if(!current_user_can('manage_options')) {
		return false;
	} else {
		return true;
	}
}
add_filter('acf/settings/show_admin', 'theme_show_acf_in_admin');


// ADD CUSTOM EDITOR CLASS TO TINYMCE
function theme_append_custom_tinymce_body_class( $mce ) {
	if(!empty($mce['body_class'])) {
		if ( $post = get_post() ) {
			$mce['body_class'] .= ' ' . sanitize_html_class( $post->ID );
		}
		$mce['body_class'] .= ' editor-content';
	}
    return $mce;
}
if(ENABLE_FRONTEND_STYLES_IN_TINYMCE) {
	add_filter( 'tiny_mce_before_init', 'theme_append_custom_tinymce_body_class' );
}


// DON'T BREAK CATEGORY HIERARCHY IN ADMIN MENU
function theme_admin_term_checklist($args){
    $args['checked_ontop'] = false;
    return $args;
}
add_filter('wp_terms_checklist_args', 'theme_admin_term_checklist', 10, 2);


// DISABLE DEFAULT DASHBOARD WIDGETS
function theme_disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
}
add_action('wp_dashboard_setup', 'theme_disable_default_dashboard_widgets');


// WP ADMIN CUSTOMIZER CLEANUP
function theme_customizer_cleanup($wp_customize) {
    $wp_customize->remove_section('colors');
    $wp_customize->remove_section('background_image');
    //$wp_customize->remove_panel('nav_menus');
    $wp_customize->remove_section('nav');
    $wp_customize->remove_control('site_icon');
}
add_action( 'customize_register', 'theme_customizer_cleanup', 50 );


// CUSTOM LOGIN PAGE STYLES
function theme_login_css() {
	$theme_ver = wp_get_theme()->get('Version');

	wp_enqueue_style('theme-login-styles', get_template_directory_uri() . '/build/css/login.min.css', array(), $theme_ver, 'all');
}
add_action('login_enqueue_scripts', 'theme_login_css', 10);


// CHANGE LOGO LINK FROM WORDPRESS.ORG TO SITE URL
function theme_login_url() {
	return home_url();
}
add_filter('login_headerurl', 'theme_login_url');


// CUSTOM ADMIN FOOTER
function theme_custom_admin_footer() {
	echo __('<span id="footer-thankyou">Developed by <a href="https://www.logic.ee/" target="_blank">Logic Technologies</a></span>', 'fitbody-theme');
}
add_filter('admin_footer_text', 'theme_custom_admin_footer');


// FIX WPML FLAG SIZE IF SVG
function theme_custom_admin_css() {
	echo '<style>
		#icl_string_translations .wpml-st-col-string IMG {
			max-width: 24px;
			vertical-align: middle;
		}
	</style>';
}
add_action('admin_head', 'theme_custom_admin_css');

?>
