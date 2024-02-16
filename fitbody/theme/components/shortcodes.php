<?php

/* ***************************************************************************** */
/* THEME SHORTCODES                                                              */
/* ***************************************************************************** */
/* Define all custom shortcodes here                                             */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


/*function theme_sameple_shortcode($atts) {
	$output = '';

	extract(shortcode_atts([
		'sample_attribute'  => 3,
	], $atts));

	$news = get_posts([
		'post_type' 	=> 'post',
		'numberposts' 	=> $atts['sample_attribute'],
		'orderby' 		=> 'date',
		'order' 		=> 'DESC'
	]);

	if(!empty($news)) {
		$output .= 'Test';
	}

	return $output;
}
add_shortcode('sample_shortcode', 'theme_sample_shortcode');*/

?>