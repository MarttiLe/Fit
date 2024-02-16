<?php

/* ***************************************************************************** */
/* THEME CUSTOM POST TYPES                                                       */
/* ***************************************************************************** */
/* Define all custom post types, taxonomies & statuses here                      */
/* Customize and add/remove items as needed                                      */
/* ***************************************************************************** */


// CUSTOM POST TYPES
function theme_custom_post_types() {

    // Event post type (blog)
    register_post_type('event', [
        'labels' => [
            'name' => __('Events', 'fitbody-theme'),
            'singular_name' => __('Event', 'fitbody-theme'),
            'all_items' => __('All events', 'fitbody-theme'),
            'add_new' => __('Add new', 'fitbody-theme'),
            'add_new_item' => __('Add event', 'fitbody-theme'),
            'edit' => __('Edit', 'fitbody-theme'),
            'edit_item' => __('Edit event', 'fitbody-theme'),
            'new_item' => __('New event', 'fitbody-theme'),
            'view_item' => __('View event', 'fitbody-theme'),
            'search_items' => __('Search for events', 'fitbody-theme'),
            'not_found' => __('No events found', 'fitbody-theme'),
            'not_found_in_trash' => __('Trash is empty', 'fitbody-theme'),
            'parent_item_colon' => ''
        ],
        'description' => __('Events post type', 'fitbody-theme'),
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_ui' => true,
        'query_var' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-post',
        'has_archive' => 'event_cat',
        'rewrite' => [
            'slug' => 'events',
            'with_front' => false
        ],
        'capability_type' => 'post',
        'hierarchical' => false,
        'show_in_rest' => true,
        'supports' => [
            'title',
            'editor',
            'thumbnail'
        ],
        'taxonomies' => ['event_cat'],
    ]);

    // Advice post type (blog)
    register_post_type('advice', [
        'labels' => [
            'name' => __('Advice', 'fitbody-theme'),
            'singular_name' => __('Advice', 'fitbody-theme'),
            'all_items' => __('All advice', 'fitbody-theme'),
            'add_new' => __('Add new', 'fitbody-theme'),
            'add_new_item' => __('Add advice', 'fitbody-theme'),
            'edit' => __('Edit', 'fitbody-theme'),
            'edit_item' => __('Edit advice', 'fitbody-theme'),
            'new_item' => __('New advice', 'fitbody-theme'),
            'view_item' => __('View advice', 'fitbody-theme'),
            'search_items' => __('Search for advice', 'fitbody-theme'),
            'not_found' => __('No advice found', 'fitbody-theme'),
            'not_found_in_trash' => __('Trash is empty', 'fitbody-theme'),
            'parent_item_colon' => ''
        ],
        'description' => __('Advice post type', 'fitbody-theme'),
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_ui' => true,
        'query_var' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-admin-post',
        'has_archive' => 'advice_cat',
        'rewrite' => [
            'slug' => 'advice',
            'with_front' => false
        ],
        'capability_type' => 'post',
        'hierarchical' => false,
        'show_in_rest' => true,
        'supports' => [
            'title',
            'editor',
            'thumbnail'
        ],
        'taxonomies' => ['advice_cat'],
    ]);

    // Program post type
    register_post_type('program', [
        'labels' => [
            'name' => __('Programs', 'fitbody-theme'),
            'singular_name' => __('Program', 'fitbody-theme'),
            'all_items' => __('All programs', 'fitbody-theme'),
            'add_new' => __('Add new', 'fitbody-theme'),
            'add_new_item' => __('Add new program', 'fitbody-theme'),
            'edit' => __('Edit', 'fitbody-theme'),
            'edit_item' => __('Edit program', 'fitbody-theme'),
            'new_item' => __('New program', 'fitbody-theme'),
            'view_item' => __('View program', 'fitbody-theme'),
            'search_items' => __('Search for programs', 'fitbody-theme'),
            'not_found' => __('No programs found', 'fitbody-theme'),
            'not_found_in_trash' => __('Trash is empty', 'fitbody-theme'),
            'parent_item_colon' => __('Program parent', 'fitbody-theme')
        ],
        'description' => __('Program post type', 'fitbody-theme'),
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_ui' => true,
        'query_var' => true,
        'menu_position' => 6,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-playlist-video',
        'has_archive' => 'programs',
        'rewrite' => [
            'slug' => 'program',
            'with_front' => false
        ],
        'capability_type' => 'post',
        'hierarchical' => true,
        'supports' => [
            'title',
            'page-attributes',
            'thumbnail'
        ],
        'taxonomies' => ['program_cat', /*'program_tag'*/],
    ]);

    // Trainer post type
    register_post_type('trainer', [
        'labels' => [
            'name' => __('Trainers', 'fitbody-theme'),
            'singular_name' => __('Trainer', 'fitbody-theme'),
            'all_items' => __('All trainers', 'fitbody-theme'),
            'add_new' => __('Add new', 'fitbody-theme'),
            'add_new_item' => __('Add new trainer', 'fitbody-theme'),
            'edit' => __('Edit', 'fitbody-theme'),
            'edit_item' => __('Edit trainer', 'fitbody-theme'),
            'new_item' => __('New trainer', 'fitbody-theme'),
            'view_item' => __('View trainer', 'fitbody-theme'),
            'search_items' => __('Search for trainers', 'fitbody-theme'),
            'not_found' => __('No trainers found', 'fitbody-theme'),
            'not_found_in_trash' => __('Trash is empty', 'fitbody-theme'),
            'parent_item_colon' => ''
        ],
        'description' => __('Trainers post type', 'fitbody-theme'),
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-universal-access',
        'has_archive' => 'trainers',
        'rewrite' => [
            'slug' => 'trainer',
            'with_front' => false
        ],
        'capability_type' => 'post',
        'supports' => [
            'title'
        ],
    ]);

}
add_action('init', 'theme_custom_post_types');


// CUSTOM TAXONOMIES
function theme_custom_taxonomies() {

    // Event categories taxonomy
	register_taxonomy( 'event_cat', 'event', [
		'hierarchical'          => true,
		'labels'                => [
            'name'              => _x( 'Categories', 'taxonomy general name', 'fitbody-theme' ),
            'singular_name'     => _x( 'Category', 'taxonomy singular name', 'fitbody-theme' ),
            'search_items'      => __( 'Search categories', 'fitbody-theme' ),
            'all_items'         => __( 'All categories', 'fitbody-theme' ),
            'parent_item'       => __( 'Parent category', 'fitbody-theme' ),
            'parent_item_colon' => __( 'Parent category:', 'fitbody-theme' ),
            'edit_item'         => __( 'Edit category', 'fitbody-theme' ),
            'update_item'       => __( 'Update category', 'fitbody-theme' ),
            'add_new_item'      => __( 'Add new category', 'fitbody-theme' ),
            'new_item_name'     => __( 'New category name', 'fitbody-theme' ),
            'menu_name'         => __( 'Categories', 'fitbody-theme' ),
        ],
        'show_in_rest'          => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => [
            'slug' => 'events'
        ]
    ]);

    // Advice categories taxonomy
	register_taxonomy( 'advice_cat', 'advice', [
		'hierarchical'          => true,
		'labels'                => [
            'name'              => _x( 'Categories', 'taxonomy general name', 'fitbody-theme' ),
            'singular_name'     => _x( 'Category', 'taxonomy singular name', 'fitbody-theme' ),
            'search_items'      => __( 'Search categories', 'fitbody-theme' ),
            'all_items'         => __( 'All categories', 'fitbody-theme' ),
            'parent_item'       => __( 'Parent category', 'fitbody-theme' ),
            'parent_item_colon' => __( 'Parent category:', 'fitbody-theme' ),
            'edit_item'         => __( 'Edit category', 'fitbody-theme' ),
            'update_item'       => __( 'Update category', 'fitbody-theme' ),
            'add_new_item'      => __( 'Add new category', 'fitbody-theme' ),
            'new_item_name'     => __( 'New category name', 'fitbody-theme' ),
            'menu_name'         => __( 'Categories', 'fitbody-theme' ),
        ],
        'show_in_rest'          => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => [
            'slug' => 'advice'
        ]
    ]);

    // Program categories taxonomy
	register_taxonomy( 'program_cat', 'program', [
		'hierarchical'          => true,
		'labels'                => [
            'name'              => _x( 'Categories', 'taxonomy general name', 'fitbody-theme' ),
            'singular_name'     => _x( 'Category', 'taxonomy singular name', 'fitbody-theme' ),
            'search_items'      => __( 'Categories', 'fitbody-theme' ),
            'all_items'         => __( 'Categories', 'fitbody-theme' ),
            'parent_item'       => __( 'Category', 'fitbody-theme' ),
            'parent_item_colon' => __( 'Category:', 'fitbody-theme' ),
            'edit_item'         => __( 'Edit category', 'fitbody-theme' ),
            'update_item'       => __( 'Update category', 'fitbody-theme' ),
            'add_new_item'      => __( 'Add new category', 'fitbody-theme' ),
            'new_item_name'     => __( 'New category name', 'fitbody-theme' ),
            'menu_name'         => __( 'Categories', 'fitbody-theme' ),
        ],
        'show_in_rest'          => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => [
            'slug' => 'programs'
        ]
    ]);

    // Program tags taxonomy
	/*register_taxonomy( 'program_tag', 'program', [
		'hierarchical'          => false,
		'labels'                => [
            'name'              => _x( 'Tags', 'taxonomy general name', 'fitbody-theme' ),
            'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'fitbody-theme' ),
            'search_items'      => __( 'Tags', 'fitbody-theme' ),
            'all_items'         => __( 'Tag', 'fitbody-theme' ),
            'parent_item'       => __( 'Tag', 'fitbody-theme' ),
            'parent_item_colon' => __( 'Tag:', 'fitbody-theme' ),
            'edit_item'         => __( 'Edit tag', 'fitbody-theme' ),
            'update_item'       => __( 'Update tag', 'fitbody-theme' ),
            'add_new_item'      => __( 'Add new tag', 'fitbody-theme' ),
            'new_item_name'     => __( 'New tag name', 'fitbody-theme' ),
            'menu_name'         => __( 'Tags', 'fitbody-theme' ),
        ],
        'show_in_rest'          => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => [
            'slug' => 'tags'
        ]
    ]);*/

}
add_action('init', 'theme_custom_taxonomies');


// CUSTOM POST STATUSES
function theme_custom_post_statuses() {

    register_post_status('inactive', [
        'label' => __('Inactive', 'fitbody-theme'),
        'post_type' => ['item'],
        'public' => true,
        'exclude_from_search' => true,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Inactive <span class="count">(%s)</span>', 'Inactive <span class="count">(%s)</span>', 'fitbody-theme'),
    ]);

}
//add_action('init', 'theme_custom_post_statuses');


?>