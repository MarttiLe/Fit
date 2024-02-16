<?php

/* ***************************************************************************** */
/* ENQUEUE THEME CUSTOM SCRIPTS & STYLESHEETS                                    */
/* ***************************************************************************** */
/* All file enqueueing should be done via this function                          */
/* All files should preferably be loaded in the footer                           */
/* Version control is provided automatically using $build_ver                    */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// LOAD THEME SCRIPTS AND STYLES
function theme_load_custom_scripts_styles() {
    if (!is_admin()) {

        // GET THEME VERSION
        // Version is defined in the styles.css file of theme root. It is presented in unix timestamp format and automatically incremented via gulp build.
        $build_ver = wp_get_theme()->get('Version');
        $jquery_dep = [];

        wp_deregister_script('jquery');
        if(ENABLE_JQUERY) {
            // Register jQuery (through CDN) before everything else
            wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.12.4', false);
            $jquery_dep = ['jquery'];
        }

        // Register main CSS
        wp_register_style('theme-styles', get_stylesheet_directory_uri() . '/build/css/style.min.css', [], $build_ver, 'all');

        // Register main JS
        wp_register_script('theme-scripts', get_stylesheet_directory_uri() . '/build/js/scripts.min.js', $jquery_dep, $build_ver, true);

        // Register vendor JS
        // It is advised to copy vendor scripts into source/js/vendor/ and build them into one single file
        // Only enable this in projects that use it!
        wp_register_script('vendor-scripts', get_stylesheet_directory_uri() . '/build/js/vendors.min.js', $jquery_dep, $build_ver, true);

        // Register Fancybox gallery
        wp_register_script('fancybox-js', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js',  array('jquery'));
        wp_register_style('fancybox-css', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css');


        // Enqueue everything
        if(ENABLE_JQUERY) {
            wp_enqueue_script('jquery');
        }
        wp_enqueue_style('theme-styles');
        wp_enqueue_script('theme-scripts');
        wp_enqueue_script('vendor-scripts');
        if(is_singular('product')) {
            wp_enqueue_style('fancybox-css');
            wp_enqueue_script('fancybox-js');
        }


        // Script global variables
        wp_localize_script( 'theme-scripts', 'ajaxSettings', [
            'adminUrl'        => admin_url( 'admin-ajax.php' ),
            'postsPerPage'    => get_option( 'posts_per_page' )
        ]);

    }
}
add_action( 'wp_enqueue_scripts', 'theme_load_custom_scripts_styles', 999 );


// LOAD EDITOR STYLES IN WP ADMIN
function theme_load_admin_editor_styles() {
    add_editor_style( get_stylesheet_directory_uri() . '/build/css/editor-style.min.css' );
}
add_action( 'admin_init', 'theme_load_admin_editor_styles' );


?>