<?php

/* ***************************************************************************** */
/* THEME CUSTOM WIDGETS                                                          */
/* ***************************************************************************** */
/* Define all widgets used by the theme here                                     */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// REGISTER WIDGET AREAS
function theme_custom_sidebars() {
	register_sidebar([
        'name' => __( 'Sample widget area', 'fitbody-theme' ),
        'id' => 'sample-widget-area',
    ]);
}
//add_action( 'widgets_init', 'theme_custom_sidebars' );


// REGISTER WIDGETS
function theme_custom_widgets_init() {
    //register_widget('sample_widget');
}

?>