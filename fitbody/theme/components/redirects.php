<?php

/* ***************************************************************************** */
/* THEME REDIRECTS                                                               */
/* ***************************************************************************** */
/* Define all manual redirects here                                              */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */

// REDIRECT/DISABLE CERTAIN TEMPLATES
function theme_redirect_templates() {
    global $wp_query;

    if ( is_author() || is_404() || is_singular('trainer') ) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }

}
add_action('template_redirect', 'theme_redirect_templates');

?>
