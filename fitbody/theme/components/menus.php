<?php

/* ***************************************************************************** */
/* THEME CUSTOM MENUS                                                            */
/* ***************************************************************************** */
/* Define all menus used by the theme here                                       */
/* All items should end with the suffix "-nav"                                   */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


function theme_custom_menus() {
	register_nav_menus([
        'header-nav' => __( 'Header navigation', 'fitbody-theme' ),
        'footer-nav' => __( 'Footer navigation', 'fitbody-theme' ),
    ]);
}
add_action( 'init', 'theme_custom_menus' );

?>