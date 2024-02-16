<?php

/* ***************************************************************************** */
/* THEME CUSTOM WORDPRESS CLEANUP                                                */
/* ***************************************************************************** */
/* Remove unnecessary files and functionalities from WP                          */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// HIDE WP ADMIN BAR ON FRONTEND
function theme_hide_admin_bar() {
    return false;
}
if(ENABLE_ADMIN_BAR) {
    add_filter( 'show_admin_bar' , 'theme_hide_admin_bar');
    remove_action('wp_head', '_admin_bar_bump_cb');
}


// LIMIT POST/PAGE REVISIONS
function theme_limit_revisions( $num, $post ) {
    $num = SET_REVISION_LIMIT;
    return $num;
}
add_filter( 'wp_revisions_to_keep', 'theme_limit_revisions', 10, 2 );


// REMOVE DEFAULT IMAGE SIZES
function theme_remove_default_image_sizes($sizes) {
    unset($sizes['small']); // 150px
    unset($sizes['medium']); // 300px
    unset($sizes['large']); // 1024px
    unset($sizes['medium_large']); // 768px
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'theme_remove_default_image_sizes', 10, 1);


// DISABLE COMMENTS
function theme_disable_comments() {

    // Redirect any user trying to access comments page
    global $pagenow;

    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }

}
if(ENABLE_COMMENTS) {
    add_action('admin_init', 'theme_disable_comments');

    // Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);

    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);

    // Remove comments page in menu
    add_action('admin_menu', function () {
        remove_menu_page('edit-comments.php');
    });

    // Remove comments links from admin bar
    add_action('init', function () {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    });
    add_action('wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    });
}


// WORDPRESS HEAD CLEANUP
function theme_head_cleanup() {
    // Category feeds
    remove_action('wp_head', 'feed_links_extra', 3);

    // Post and content feeds
    remove_action('wp_head', 'feed_links', 2);

    // EditURI link
    remove_action('wp_head', 'rsd_link');

    // Windows Live Writer
    remove_action('wp_head', 'wlwmanifest_link');

    // Previous link
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);

    // Start link
    remove_action('wp_head', 'start_post_rel_link', 10, 0);

    // Links for adjacent posts
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

    // WP version (for security)
    remove_action('wp_head', 'wp_generator');

    // Emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action( 'init', 'theme_head_cleanup' );


// REMOVE UNNECESSARY CSS
function theme_deregister_styles() {
    // Gutenberg editor
    if(!ENABLE_GUTENBERG_STYLES) {
        wp_dequeue_style( 'wp-block-library' );
    }

    // Cookie notice
    if(!ENABLE_LOGIC_GDPR) {
        wp_dequeue_style( 'cookie-notice-front' );
    }
}
add_action( 'wp_print_styles', 'theme_deregister_styles', 100 );


// REMOVE SUPPORT FOR POST FORMATS
function theme_remove_post_formats_support() {
    remove_post_type_support( 'post', 'post-formats' );
}
add_action( 'init', 'theme_remove_post_formats_support', 10 );


// CLEAN UP PARAGRAPH TAGS FROM IMAGES UPLOADED THROUGH THE EDITOR
function theme_cleanup_ptags_on_images($content) {
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter( 'the_content', 'theme_cleanup_ptags_on_images' );


// CLEAN UP DOTS FROM "READ MORE" LINKS
function theme_cleanup_excerpt_more($more) {
	global $post;
	return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'fitbody-theme' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'fitbody-theme' ) .'</a>';
}
add_filter( 'excerpt_more', 'theme_cleanup_excerpt_more' );


// REMOVE WOOCOMMERCE STYLESHEETS
if(!ENABLE_WOOCOMMERCE_STYLES) {
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}


// REMOVE PREFIXES FROM ARCHIVE PAGES
add_filter( 'get_the_archive_title', function ($title) {    
	if ( is_category() ) {    
			$title = single_cat_title( '', false );    
		} elseif ( is_tag() ) {    
			$title = single_tag_title( '', false );    
		} elseif ( is_author() ) {    
			$title = '<span class="vcard">' . get_the_author() . '</span>' ;    
		} elseif ( is_tax() ) { //for custom post types
			$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
		} elseif (is_post_type_archive()) {
			$title = post_type_archive_title( '', false );
		}
	return $title;    
});


?>