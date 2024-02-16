<?php

/* ***************************************************************************** */
/* THEME CUSTOM FONTS                                                            */
/* ***************************************************************************** */
/* Define all fonts used by the theme here                                       */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// Enqueue fonts from Google Fonts
// PS: When importing multiple fonts, keep them in a single request by separating families via '&'
// Example: fonts.googleapis.com/css2?family=Exo:wght@400;600;700&family=Open+Sans:wght@400;700&display=swap
function theme_custom_fonts() {
    wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,300;1,400;1,500;1,600;1,700;1,900&display=swap');
}
add_action('wp_enqueue_scripts', 'theme_custom_fonts');

?>