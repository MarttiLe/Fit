<?php

/* ***************************************************************************** */
/* BLOCK EDITOR (GUTENBERG) CUSTOMIZATIONS                                       */
/* ***************************************************************************** */
/* Change block editor defaults                                                  */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// CUSTOMIZE BLOCKS AVAILABLE IN THE THEME
// Full block list can be found here: https://wordpress.org/support/article/blocks/
function theme_supported_gutenberg_blocks( $allowed_blocks, $post ) {
	$allowed_blocks = [
		// Common blocks
		'core/image',
		'core/video',
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/quote',
		// Formatting blocks
		'core/table',
		'core/code',
		'core/html',
		// Layout blocks
		'core/columns',
		'core/media-text',
		'core/buttons',
		'core/separator',
		'core/spacer',
		// Widget blocks
		'core/shortcode',
		// Embeds blocks
		'core/embed',
	];

	return $allowed_blocks;
}
add_filter( 'allowed_block_types_all', 'theme_supported_gutenberg_blocks', 10, 2 );


// ADD CUSTOM COLOR SCHEMES
add_theme_support( 'editor-color-palette', [
	[
		'name'  => __( 'Brand gold', 'fitbody-theme' ),
		'slug'  => 'brand_gold',
		'color'	=> '#D2B37F',
	],
	[
		'name'  => __( 'Brand gold light', 'fitbody-theme' ),
		'slug'  => 'brand_gold_light',
		'color'	=> '#E8D8BD',
	],
	[
		'name'	=> __( 'Brand gold dark', 'fitbody-theme' ),
		'slug'	=> 'brand_gold_dark',
		'color'	=> '#bda171',
	],
	[
		'name'	=> __( 'Brand gray', 'fitbody-theme' ),
		'slug'	=> 'brand_gray',
		'color'	=> '#BEC3C6',
	],
	[
		'name'	=> __( 'Brand gray light', 'fitbody-theme' ),
		'slug'	=> 'brand_gray_light',
		'color'	=> '#efefef',
	],
	[
		'name'	=> __( 'Brand gray dark', 'fitbody-theme' ),
		'slug'	=> 'brand_gray_dark',
		'color'	=> '#666666',
	],
	[
		'name'	=> __( 'Brand positive', 'fitbody-theme' ),
		'slug'	=> 'brand_gray_positive',
		'color'	=> '#31bd69',
	],
	[
		'name'	=> __( 'Brand negative', 'fitbody-theme' ),
		'slug'	=> 'brand_gray_negative',
		'color'	=> '#C5014C',
	]
]);


// CUSTOM THEME BLOCK CATEGORIES
function theme_block_categories( $categories, $post ) {
    if ( $post->post_type !== 'post' ) {
        return $categories;
    }
    return array_merge(
        $categories,
        [
            [
                'slug' => 'theme-blocks',
                'title' => __( 'Theme custom blocks', 'fitbody-theme' ),
                'icon'  => 'star-filled',
            ],
		]
    );
}
//add_filter( 'block_categories', 'theme_block_categories', 10, 2 );