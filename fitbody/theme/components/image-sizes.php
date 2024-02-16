<?php

/* ***************************************************************************** */
/* THEME CUSTOM IMAGE SIZES                                                      */
/* ***************************************************************************** */
/* Define all image sizes used by the theme here                                 */
/* Avoid creating unnecessary image sizes to save server space                   */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// DEFINE CUSTOM IMAGE SIZES
function theme_custom_image_sizes() {
    add_image_size( 'profile-thumb', 360, 360, true );
    add_image_size( 'blog-thumb', 1000, 800, true );
    add_image_size( 'blog-banner', 970, 420, true );
    add_image_size( 'product-thumb', 320, 300, true );
    add_image_size( 'product-full', 800, 600, true );
    add_image_size( 'program-thumb', 710, 320, true );
    add_image_size( 'full-hd', 1920, 1080, true );
    add_image_size( '720p', 1280, 720, true );
    add_image_size( 'page-banner', 1920, 560, true );
}
add_action( 'after_setup_theme', 'theme_custom_image_sizes' );


// ADD SPECIFIC CUSTOM IMAGE SIZES TO WP MEDIA EDITOR
function theme_custom_media_image_sizes( $sizes ) {
    $add_sizes = [
        //'sample-size' => __('Sample size', 'fitbody-theme'),
    ];

    $newsizes = array_merge( $sizes, $add_sizes );
    return $newsizes;
}
//add_filter( 'image_size_names_choose', 'theme_custom_media_image_sizes' );

?>