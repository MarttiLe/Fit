<?php

/* ***************************************************************************** */
/* THEME CORE FUNCTIONS                                                          */
/* ***************************************************************************** */
/* Do not edit this file                                                         */
/* ***************************************************************************** */


// ACF CUSTOMIZATION
// Define ACF paths
define( 'MY_ACF_PATH', get_stylesheet_directory() . '/theme/includes/acf/' );
define( 'MY_ACF_URL', get_stylesheet_directory_uri() . '/theme/includes/acf/' );

// Customize ACF asset urls
function theme_get_acf_settings_url( $url ) {
    return MY_ACF_URL;
}
add_filter('acf/settings/url', 'theme_get_acf_settings_url');

// Change ACF-JSON path
function theme_get_acf_json_save_point( $path ) {
    $path = get_stylesheet_directory() . '/theme/data/acf-json';
    return $path;
}
add_filter('acf/settings/save_json', 'theme_get_acf_json_save_point');

// Change ACF-JSON load path
function theme_get_acf_json_load_point( $paths ) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/theme/data/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'theme_get_acf_json_load_point');


// LOAD THEME SUPPORTS
function theme_custom_supports() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ]);
    if(IS_WOOCOMMERCE_PROJECT) {
        add_theme_support('woocommerce');
    }
}
add_action( 'after_setup_theme', 'theme_custom_supports' );


// REMOVE SPECIAL CHARS FROM UPLOADED MEDIA FILENAMES
function theme_sanitize_media_filenames($filename) {
    $sanitized_filename = remove_accents($filename); // Convert to ASCII

	// Standard replacements
	$invalid = array(
		' ' => '-',
		'%20' => '-',
		'_' => '-',
	);
	$sanitized_filename = str_replace(array_keys($invalid), array_values($invalid), $sanitized_filename);

	$sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
	$sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
	$sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
	$sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
	$sanitized_filename = strtolower($sanitized_filename); // Lowercase

	return $sanitized_filename;
}
if(ENABLE_SANITIZE_UPLOAD_FILENAMES) {
    add_filter('sanitize_file_name', 'theme_sanitize_media_filenames', 10);
}


// PROTECT EMAIL LINKS
// Automatically detects emails in editor content and filters them through antispambot(). Wrap email string inside [email]your@email.com[/email] tags to encode them manually.
function theme_encode_emails($emailAddress) {
    $emailRegEx = '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4})/i';
    return preg_replace_callback($emailRegEx, "theme_get_encoded_email", $emailAddress);
}
function theme_get_encoded_email($result) {
    return antispambot($result[1]);
}
function theme_encode_email_shortcode( $atts , $content = null ) {
    if ( is_admin() || !is_email($content)) {
        return $content;
    }

    return '<a href="mailto:' . antispambot( $content ) . '">' . esc_html( antispambot( $content ) ) . '</a>';
}
if(ENABLE_ANTISPAMBOT_EMAILS) {
    add_filter( 'the_content', 'theme_encode_emails', 20 );
    add_filter( 'widget_text', 'theme_encode_emails', 20 );
    add_shortcode( 'email', 'theme_encode_email_shortcode' );
}


// BEMIFY WP MENUS
// Based on https://github.com/samikeijonen/bemit/blob/master/inc/functions-filters.php and heavily customized
function theme_get_nav_menu_css_class( $classes, $item, $args, $depth ) {
	// Reset all default classes and start adding custom classes to array
    $_classes = [ 'menu__item' ];

    // Add custom classes that were entered through wp admin menu item CSS classes field
    if($custom_classes = array_filter(get_post_meta($item->ID, '_menu_item_classes', true))) {
        $_classes = array_merge($_classes, $custom_classes);
    }

	// Add a class if the menu item has children
	if ( in_array( 'menu-item-has-children', $classes, true ) ) {
		$_classes[] = 'menu__item--has-children';
	}
    // Add level parameter
    $_classes[] .= theme_get_menu_depth_class($depth, true);

	// Return custom classes.
	return $_classes;
}
add_filter( 'nav_menu_css_class', 'theme_get_nav_menu_css_class', 10, 4 );

function theme_get_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	// Start adding custom classes
    $atts['class'] = 'menu__anchor';

	// Add 'is-ancestor' class
	if ( in_array( 'current-page-ancestor', $item->classes, true ) || in_array( 'current-menu-ancestor', $item->classes, true ) ) {
		$atts['class'] .= ' is-ancestor';
	}
	// Add 'is-active' class
	if ( in_array( 'current-menu-item', $item->classes, true ) ) {
		$atts['class'] .= ' is-active';
    }
    // Add 'is-external' class
    if ( $atts['target'] === '_blank' ) {
        $atts['class'] .= ' is-external';
    }
    // Add level parameter
    $atts['class'] .= theme_get_menu_depth_class($depth, true);

	// Return custom classes
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'theme_get_nav_menu_link_attributes', 10, 4 );

function theme_get_nav_menu_submenu_css_class( $classes, $args, $depth ) {
	$classes = [ 'menu__sub-menu' ];
	return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'theme_get_nav_menu_submenu_css_class', 10, 3 );

function theme_get_menu_depth_class($depth, bool $space) {
    if($space) {
        $depth_class = ' ';
    } else {
        $depth_class = '';
    }
    if($depth === 0) {
        $depth_class .= 'is-top-level';
    } else if($depth === 1) {
        $depth_class .= 'is-first-level';
    } else if($depth === 2) {
        $depth_class .= 'is-second-level';
    } else {
        $depth_class = '';
    }
    return $depth_class;
}

function theme_clear_nav_menu_item_id($id, $item, $args) {
    return '';
}
add_filter('nav_menu_item_id', 'theme_clear_nav_menu_item_id', 10, 3);


?>