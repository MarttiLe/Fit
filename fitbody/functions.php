<?php

// LOAD THEME
require_once( 'theme/theme.php' );

// INITIALIZE THEME
if(function_exists( 'theme_init')) {
	add_action( 'after_setup_theme', 'theme_init' );
}

/* ***************************************************************************** */
/* THEME CUSTOM FUNCTIONS                                                        */
/* ***************************************************************************** */

// CUSTOM WPML LANGUAGE SWITCHER
function theme_language_switcher($wrapper_classes = '') {
	if(function_exists('icl_object_id')) {
		$args = [
			'skip_missing' => 0,
			'orderby' => 'custom',
			'order' => 'asc'
		];
		if($wrapper_classes) {
			$wrapper_classes = $wrapper_classes . ' ';
		}
		$languages = apply_filters( 'wpml_active_languages' , NULL, $args);
		if(!empty($languages) && count($languages) > 1) {
			echo '<nav class="'. $wrapper_classes .'language-switcher">';

			// Language list
			echo '<ul class="language-switcher__items">';
			foreach($languages as $l) {
				$active_class = '';
				if($l['active']) {
					$active_class = ' is-active';
				}
				echo '<li class="language-switcher__item' . $active_class . '">';
				if($l['country_flag_url']) {
					echo '<a href="'. $l['url'] .'" class="language-switcher__anchor">'. $l['language_code'] .'</a>';
				}
				echo '</li>';
			}
			echo '</ul></nav>';
		}
	}
}


// MODIFY ACF ICON PLUGIN PATH
function theme_acf_icon_path_suffix( $path_suffix ) {
    return 'assets/icons/acf-icon-picker/';
}
add_filter( 'acf_icon_path_suffix', 'theme_acf_icon_path_suffix' );


// BLOG LOADMORE
function theme_ajax_blog_loadmore() {
	if(!isset($_POST['args']) || empty($_POST['args'])) {
		die(json_encode([
			'success' 	=> false,
			'message' 	=> __('Error - invalid arguments provided', 'fitbody-theme')
		]));
	}

	$args = $_POST['args'];
	$blog_items = new WP_Query($args);

	ob_start();
	if($blog_items->have_posts()) {
		while($blog_items->have_posts()) {
			$blog_items->the_post();
			get_template_part('templates/components/card-blog');
		}
	}
	wp_reset_postdata();
	$output = ob_get_clean();

	$is_final_page = false;
	if($args['paged'] == $blog_items->max_num_pages) {
		$is_final_page = true;
	}

	if(empty($output)) {
		die(json_encode([
			'success' 	=> false,
			'message' 	=> __('Error - something went wrong when attempting to retrieve posts', 'fitbody-theme'),
			'debug'		=> $args
		]));
	} else {
		die(json_encode([
			'output'	=> $output,
			'final'		=> $is_final_page,
			'success' 	=> true,
			'message' 	=> __('Post fetch succeeded', 'fitbody-theme'),
			'debug'		=> $args
		]));
	}
}
add_action('wp_ajax_nopriv_theme_ajax_blog_loadmore', 'theme_ajax_blog_loadmore');
add_action('wp_ajax_theme_ajax_blog_loadmore', 'theme_ajax_blog_loadmore');


// REMOVE PRODUCT TAGS
add_action( 'admin_menu', 'theme_hide_product_tags_admin_menu', 9999 );
function theme_hide_product_tags_admin_menu() {
	remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product' );
}

add_action( 'admin_menu', 'theme_hide_product_tags_metabox' );
function theme_hide_product_tags_metabox() {
	remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
}

add_filter('manage_product_posts_columns', 'theme_hide_product_tags_column', 999 );
function theme_hide_product_tags_column( $product_columns ) {
	unset( $product_columns['product_tag'] );
	return $product_columns;
}

add_filter( 'quick_edit_show_taxonomy', 'theme_hide_product_tags_quick_edit', 10, 2 );
function theme_hide_product_tags_quick_edit( $show, $taxonomy_name ) {
    if ( 'product_tag' == $taxonomy_name )
        $show = false;

    return $show;
}


// REMOVE PRODUCT SHORT DESCRIPTION (EXCERPT)
function theme_remove_product_short_description() {
	remove_meta_box( 'postexcerpt', 'product', 'normal');
}
add_action('add_meta_boxes', 'theme_remove_product_short_description', 999);


// GET PRODUCT PRICE DATA
function theme_get_product_price_data($product_id, $append_currency = false) {
	$product_data = wc_get_product($product_id);
	$price = [
		'regular_price'	=> 0,
		'sale_price'	=> 0,
		'final_price'	=> 0,
		'currency'		=> '€',
		'sale_until'	=> false,
		'is_variable'	=> false
	];

	if(!empty($product_data) && !is_wp_error($product_data)) {
		if($product_data->is_type('variable')) {
			$variation_prices = [];
			$product_variations = $product_data->get_available_variations();
			foreach($product_variations as $variation) {
				array_push($variation_prices, $variation['display_price']);
			}
			$price = [
				'regular_price'	=> trimTrailingZeroes(min($variation_prices)),
				'sale_price'	=> '',
				'final_price'	=> __( 'fr.', 'fitbody-theme' ) . ' ' . trimTrailingZeroes(min($variation_prices)),
				'currency'		=> get_woocommerce_currency_symbol(),
				'sale_until'	=> false,
				'is_variable'	=> true
			];
		} else {
			$price = [
				'regular_price'	=> trimTrailingZeroes($product_data->get_regular_price()),
				'sale_price'	=> trimTrailingZeroes($product_data->get_sale_price()),
				'final_price'	=> trimTrailingZeroes($product_data->get_price()),
				'currency'		=> get_woocommerce_currency_symbol(),
				'sale_until'	=> false,
				'is_variable'	=> false
			];
		}
	}

	if(!empty($price['sale_price'])) {
		// Check if there's a sale period and whether it's still active
		$sale_until = get_post_meta($product_id, '_sale_price_dates_to', true );
		if(!empty($sale_until)) {
			if(time() < $sale_until) {
				$price['sale_until'] = ( $date = $sale_until ) ? date_i18n( 'd.m.Y', $date ) : '';
			} else {
				$price['sale_price'] = '';
			}
		}
	}

	if($append_currency) {
		if(!empty($price['regular_price'])) {
			$price['regular_price'] .= $price['currency'];
		}
		if(!empty($price['sale_price'])) {
			$price['sale_price'] .= $price['currency'];
		}
		if(!empty($price['final_price'])) {
			$price['final_price'] .= $price['currency'];
		} else {
			$price['final_price'] = __('Free!', 'fitbody-theme');
		}
	}
	if(empty($price['final_price']) || $price['final_price'] === 0) {
		$price['final_price'] = __('Free!', 'fitbody-theme');
	}

	return $price;
}


// REMOVE COUPONS FROM CHECKOUT
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );


// REMOVE PERMALINK METABOXES
function theme_remove_permalink_metabox() {
    remove_meta_box('slugdiv', 'page', 'normal');
	remove_meta_box('slugdiv', 'program', 'normal');
}
//add_action( 'admin_menu', 'theme_remove_permalink_metabox', 999 );


// VALIDATE PROGRAM AND PRODUCT CONNECTION
function theme_validate_program_product($program_id, $product_id) {
	$product = wc_get_product($product_id);
	$product_status = get_post_status($product_id);
	$product_stock_status = $product->get_stock_status();
	$product_is_program = get_field('product_is_program', $product_id);
	$linked_program = get_field('linked_program', $product_id);
	$linked_product = get_field('linked_product', $program_id);

	if($product_status === 'publish' && $product_stock_status === 'instock' && $product_is_program === true && $linked_product === $product_id && $linked_program === $program_id && $product->is_type('simple') && $product->is_virtual() && $product->is_sold_individually()) {
		return true;
	} else {
		return false;
	}
}


// GIVE PROGRAM TO USER
function theme_add_program_to_user_meta($program_id, $user_id = false) {
	if(!$user_id) {
		if(!is_user_logged_in()) {
			return false;
		}
		$user_id = get_current_user_id();
	}

	$user_programs = theme_get_user_programs($user_id);

	if(empty($user_programs)) {
		// User's first purchase, set up meta
		$user_programs = [];
		$program = [
			'purchase_timestamp'	=> time(),
			'modules'				=> []
		];

		$user_programs[$program_id] = $program;
	} else {
		if(!is_array($user_programs)) {
			// Program array is broken, re-initialize
			theme_debug_send_report_email(
				__('User encountered an error - corrupt program data array', 'fitbody-theme'),
				__("User's program metadata appears to have been corrupted and the array was re-initialized. They potentially lost access to their previously purchased programs. It may be wise to contact them to make sure they are not having issues.<br><br>Old data array:<br>", 'fitbody-theme') . $user_programs
			);
			$user_programs = [];
		} else {
			if(array_key_exists($program_id, $user_programs)) {
				// Program is already purchased and exists in the meta
				return true;
			} else {
				// User has previously purchased programs, add new one to meta array
				$program = [
					'purchase_timestamp'	=> time(),
					'modules'				=> []
				];

				$user_programs[$program_id] = $program;
			}
		}
	}

	// Validations passed, update user program meta
	$is_updated = update_user_meta($user_id, 'programs_owned', $user_programs);
	if($is_updated) {
		return true;
	} else {
		theme_debug_send_report_email(
			__('A user enountered an error', 'fitbody-theme'),
			__('User program meta update failed when attempting to acquire a program. It may be a good idea to contact this user and ask if they had issues with purchasing.', 'fitbody-theme')
		);
		return false;
	}
}


// REMOVE A PROGRAM FROM USER
// if $user_id is not supplied, clears current user's data
// if $program_id can be an id or string 'all'
function theme_remove_program_from_user_meta($program_id, $user_id = false, $generate_history_entry = true) {
	if(!$user_id) {
		if(is_user_logged_in()) {
			$user_id = get_current_user_id();
		} else {
			return false;
		}
	}

	$user_programs = theme_get_user_programs($user_id);
	if(!array_key_exists($program_id, $user_programs)) {
		return false;
	}

	if($program_id === 'all') {
		if($generate_history_entry) {
			foreach($user_programs as $key => $user_program) {
				theme_add_program_to_user_history($key, $user_id, $user_programs);
			}
		}

		$is_updated = update_user_meta($user_id, 'programs_owned', []);
	} else {
		if($generate_history_entry) {
			theme_add_program_to_user_history($program_id, $user_id, $user_programs);
		}

		unset($user_programs[$program_id]);
		$is_updated = update_user_meta($user_id, 'programs_owned', $user_programs);
	}

	if($is_updated) {
		return true;
	} else {
		return false;
	}
}

function theme_add_program_to_user_history($program_id, $user_id = false, $user_programs = false) {
	if(!$user_id) {
		if(is_user_logged_in()) {
			$user_id = get_current_user_id();
		} else {
			return false;
		}
	}

	if(!$user_programs) {
		$user_programs = get_user_meta($user_id, 'programs_owned', true);
	}

	if(!array_key_exists($program_id, $user_programs)) {
		return false;
	}

	$user_program_history = get_user_meta($user_id, 'programs_history', true);
	if(empty($user_program_history)) {
		$user_program_history = [];
	}

	$history_entry = [
		'id'					=> $program_id,
		'purchase_timestamp'	=> $user_programs[$program_id]['purchase_timestamp'],
		'expiry_timestamp'		=> time(),
		'progress'				=> '' // TODO: Add progress calculation
	];

	array_push($user_program_history, $history_entry);
	$is_updated = update_user_meta($user_id, 'programs_history', $user_program_history);
	if($is_updated) {
		return true;
	} else {
		return false;
	}
}


// GET USER PROGRAMS
function theme_get_user_programs($user_id = false, $include_history = false) {
	if(!$user_id) {
		if(!is_user_logged_in()) {
			return false;
		}
		$user_id = get_current_user_id();
	}

	if($include_history) {
		$programs = [
			'owned'		=> get_user_meta($user_id, 'programs_owned', true),
			'history'	=> get_user_meta($user_id, 'programs_history', true)
		];
	} else {
		$programs = get_user_meta($user_id, 'programs_owned', true);
	}


	return $programs;
}


// CHECK IF USER OWNS SPECIFIED PROGRAM
function theme_get_program_ownership_status($program_id, $user_id = false, $user_programs = false) {
	if(!$user_id) {
		if(!is_user_logged_in()) {
			return false;
		} else {
			$user_id = get_current_user_id();
		}
	}

	if(!$user_programs) {
		$user_programs = theme_get_user_programs($user_id);
	}

	if(!empty($user_programs) && is_array($user_programs)) {
		if(array_key_exists($program_id, $user_programs)) {
			return true;
		}
	}

	return false;
}


// GET PROGRAM OWNERSHIP START AND END DATES
function theme_get_program_ownership_dates($program_id, $user_id = false, $user_programs = false) {
	if(!$user_id) {
		if(!is_user_logged_in()) {
			return false;
		} else {
			$user_id = get_current_user_id();
		}
	}

	if(!$user_programs) {
		$user_programs = theme_get_user_programs($user_id);
	}

	if(!empty($user_programs) && is_array($user_programs)) {
		if(array_key_exists($program_id, $user_programs)) {
			$program_type = get_field('type', $program_id);

			if($program_type === 'challenge') {
				$ownership_start = strtotime(get_field('start_date', $program_id));
			} else if($program_type === 'package') {
				$ownership_start = $user_programs[$program_id]['purchase_timestamp'];
			} else {
				$ownership_start = $user_programs[$program_id]['purchase_timestamp'];
			}

			$ownership_end = false;
			$ownership_end_date = false;
			$days_to_add = (int)get_field('expiry', $program_id);
			if(empty($days_to_add) || !is_int($days_to_add)) {
				$days_to_add = 0;
			}
			if($days_to_add !== 0) {
				$days_to_add = '+'. $days_to_add .' days';
				$ownership_end = strtotime($days_to_add, $ownership_start);
				$ownership_end_date = date('d.m.Y', $ownership_end);
			}

			return [
				'from' => [
					'timestamp' => $ownership_start,
					'date'		=> date('d.m.Y', $ownership_start)
				],
				'to' 	=> [
					'timestamp' => $ownership_end,
					'date'		=> $ownership_end_date
				]
			];
		}
	}

	return false;
}


// CHECK IF PROGRAM IS EXPIRED
function theme_get_program_expiry_status($program_id, $user_id = false, $user_programs = false) {
	if(!$user_id) {
		if(!is_user_logged_in()) {
			return false;
		} else {
			$user_id = get_current_user_id();
		}
	}

	if(!$user_programs) {
		$user_programs = theme_get_user_programs($user_id);
	}

	$ownership_dates = theme_get_program_ownership_dates($program_id, $user_id, $user_programs);
	$end_date = $ownership_dates['to']['timestamp'];
	$current_date = time();

	if($current_date > $end_date) {
		return true;
	} else {
		return false;
	}
}


// ADD PROGRAM TO USER META AFTER PURCHASE
function theme_acquire_programs_after_purchase($order_id) {
	$order = wc_get_order($order_id);
	$user_id = $order->get_user_id();

	foreach ($order->get_items() as $item ) {
		$product_id = $item->get_product_id();
		$is_program = get_field('product_is_program', $product_id);
		if($is_program) {
			$program_id = get_field('linked_program', $product_id);
			if(!empty($program_id)) {
				$is_updated = theme_add_program_to_user_meta($program_id, $user_id);
			}
		}
	}
}
add_action( 'woocommerce_order_status_completed',  'theme_acquire_programs_after_purchase',  10, 1 );


// GET MODULE PARENT PROGRAM
function theme_get_module_program($module_id = false) {
	if(!$module_id) {
		global $post;
		$ancestors = $post->ancestors;
	} else {
		$ancestors = get_post_ancestors($module_id);
	}

	$program_id = end($ancestors);

	return $program_id;
}


// GET MODULE COMPLETION STATUS
// Returns 'locked', 'active' or 'completed'
function theme_get_module_status($module_id, $program_id = false, $user_id = false, $user_programs = false) {
	$module_status = 'locked';

	if(!$user_id) {
		if(!is_user_logged_in()) {
			return false;
		} else {
			$user_id = get_current_user_id();
		}
	}

	if(!$user_programs) {
		$user_programs = theme_get_user_programs($user_id);
	}

	if(!$program_id) {
		$program_id = theme_get_module_program($module_id);
	}

	if(!empty($user_programs) && is_array($user_programs) && array_key_exists($program_id, $user_programs)) {
		$program_modules = $user_programs[$program_id]['modules'];
		if(!empty($program_modules) && array_key_exists($module_id, $program_modules) && !empty($program_modules[$module_id]['status'])) {
			$module_status = $program_modules[$module_id]['status'];
		} else {
			$program_type = get_field('type', $program_id);
			if($program_type === 'challenge') {
				$module_unlock_date = strtotime(get_field('unlock_date', $module_id));
				$current_date = time();
				if($current_date >= $module_unlock_date) {
					$module_status = 'active';
				}
			} else {
				$module_status = 'active';
			}
		}
	}

	return $module_status;
}


// UPDATE MODULE STATUS
function theme_update_module_status($module_id, $status = 'completed', $user_id = false) {
	if(!$user_id) {
		if(!is_user_logged_in()) {
			return false;
		} else {
			$user_id = get_current_user_id();
		}
	}

	if(!is_int($module_id) || !get_post_status($module_id)) {
		return false;
	}
	$program_id = theme_get_module_program($module_id);
	$user_programs = theme_get_user_programs($user_id);

	if(!empty($user_programs) && is_array($user_programs) && array_key_exists($program_id, $user_programs)) {
		$user_programs[$program_id]['modules'][$module_id]['status'] = $status;
		$is_updated = update_user_meta($user_id, 'programs_owned', $user_programs);
		if($is_updated) {
			return true;
		} else {
			return false;
		}
	}
}


// UPDATE MODULE STATUS VIA AJAX
function theme_ajax_update_module_status() {
	$module_id = (int)$_POST['module_id'];
	$errors = false;

	if(empty($module_id)) {
		$errors = __('Error! Could not update module progress - Invalid module ID.', 'fitbody-theme');
	}

	$is_updated = theme_update_module_status($module_id);
	if(!$is_updated) {
		$errors = __('Error! Something went wrong, please try again later.', 'fitbody-theme');
	}

	if(!$errors) {
		die(json_encode([
			'success' => true,
			'message' => __('Success! Module status updated.', 'fitbody-theme')
		]));
	} else {
		die(json_encode([
			'success' => false,
			'message' => $errors
		]));
	}
}
add_action('wp_ajax_nopriv_theme_ajax_update_module_status', 'theme_ajax_update_module_status');
add_action('wp_ajax_theme_ajax_update_module_status', 'theme_ajax_update_module_status');


// REMOVE WOOCOMMERCE ORDER AGAIN BUTTON
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );


// SHOW PRIVATE PROGRAMS IN ADMIN GUTENBERG PARENT SELECTION
add_filter( 'rest_program_query', 'theme_show_private_in_rest_program_query', 10, 2);
function theme_show_private_in_rest_program_query( $args, $request ) {
    $args['post_status'] = array( 'publish', 'private' );
    return $args;
}

//Remove add to cart message
add_filter( 'wc_add_to_cart_message_html', '__return_false' );


//Hide client address field
function awoohc_override_checkout_fields( $fields ) {
        unset(
            $fields['billing']['billing_address_1'],
            $fields['billing']['billing_address_2'],
            $fields['billing']['billing_city'],
            $fields['billing']['billing_postcode'],
        );
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'awoohc_override_checkout_fields' );

//Fix WooCommerce variation price
add_filter('woocommerce_show_variation_price',      function() { return TRUE;});

//Keep users logged in for longer
function wcs_users_logged_in_longer( $expirein ) {
    // 7 days in seconds : 604800
    return 604800;
}
add_filter( 'auth_cookie_expiration', 'wcs_users_logged_in_longer' );


function   tb_custom_woocommerce_change_order_status( $order_id ) {
	if ( ! $order_id ) {return;}
	$order = wc_get_order( $order_id );
	if( 'processing'== $order->get_status() ) {
		$order->update_status( 'completed' );
	}
}
add_action('woocommerce_order_status_changed','tb_custom_woocommerce_change_order_status');



// Function to filter products based on multiple categories
function filter_products() {

  // Get the selected categories from the AJAX request
  $selected_categories = $_POST['categories'];

  // Build the SQL query to filter products based on the selected categories
  $query = "SELECT * FROM products WHERE ";

  // Loop through the selected categories and add them to the SQL query
  foreach ($selected_categories as $category) {
    $query .= "category = '$category' OR ";
  }

  // Remove the trailing "OR" from the query
  $query = rtrim($query, "OR ");

  // Execute the SQL query
  $result = mysqli_query($conn, $query);

  // Check if the query was successful
  if ($result) {

    // Create an array to store the filtered products
    $filtered_products = array();

    // Loop through the query results and add each product to the filtered_products array
    while ($row = mysqli_fetch_assoc($result)) {
      $filtered_products[] = $row;
    }

    // Return the filtered products as a JSON-encoded string
    echo json_encode($filtered_products);

  } else {
    // Return an error message if the query fails
    echo "Error: " . mysqli_error($conn);
  }
}


add_action('wp_ajax_filter_programs', 'filter_programs');
add_action('wp_ajax_nopriv_filter_programs', 'filter_programs');

function filter_programs() {
    $selected_categories = $_POST['categories'];
    $args = array(
        'post_type'         => 'program',
        'posts_per_page'    => -1,
        'post_status'       => 'publish',
        'post_parent'       => 0,
        'suppress_filters'  => false,
    );

    if (!empty($selected_categories)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'program_cat',
                'field' => 'slug',
                'terms' => $selected_categories,
								'operator' => 'AND',
            )
        );
    }

    $programs_query = new WP_Query($args);

    $programs_html = '';
    if ($programs_query->have_posts()) {
        while ($programs_query->have_posts()) {
            $programs_query->the_post();
            $card_attr = [
                'post_id' => get_the_ID(),
            ];
            $programs_html .= get_template_part('templates/components/card-program', null, $card_attr);
        }
    } else {
        $programs_html .= get_template_part('templates/components/training-error', null, $card_attr);


    }

    wp_reset_postdata();

    echo json_encode($programs_html);
    wp_die();
}



?>
