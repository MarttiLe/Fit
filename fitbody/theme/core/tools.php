<?php

/* ***************************************************************************** */
/* VARIOUS TOOLS BUNDLED WITH THE THEME                                          */
/* ***************************************************************************** */
/* Do not customize this file                                                    */
/* If customization is needed, move the specific function to functions.php       */
/* ***************************************************************************** */


// GET MEDIA METADATA
function theme_get_attachment_meta( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	return [
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	];
}


// FETCH DESIRED ATTACHMENT META
function theme_get_attachment_meta_by_attr($attachment_id, $attr, $placeholder = '') {
	$attachment = get_post($attachment_id);

	$metadata = '';
	if($attr == 'alt'){
		$data = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
	} elseif($attr == 'href') {
		$data = get_permalink( $attachment->ID );
	} elseif($attr == 'title') {
		$data = $attachment->post_title;
	}

	if(!empty($placeholder) && !empty($data)) {
		$metadata = $data;
	} elseif(!empty($placeholder) && empty($data)) {
		$metadata = $placeholder;
	}

	return $metadata;
}


// FORMAT PHONE NUMBERS FOR TEL OR CALLTO ANCHORS (REMOVES SPACES AND ALL SYMBOLS EXCEPT +)
function theme_get_formatted_tel($string) {
  $string = preg_replace('/[^\p{L}\p{N}\s+]/u', '', $string);
  $string = preg_replace('/\s+/', '', $string);
  return $string;
}


// TRIM STRING LENGTH
function theme_get_trimmed_text($text, $max_length, $dots = true) {
	$character_count = strlen($text);
	if ($character_count > $max_length) {
		$cropped_text = wordwrap(preg_replace( "/\r|\n|\r\n/", " ", $text), $max_length);
		$text = substr($cropped_text, 0, strpos($cropped_text, "\n"));
		if($dots) {
		  $read_more_string = '...';
		  $text = $text . $read_more_string;
		}
	}
	return $text;
}


// CUSTOM PAGINATION
function theme_pagination_nav($query = false, $classes = '') {
	if(!$query) {
		global $wp_query;
		$query = $wp_query;
	}

	$bignum = 999999999;
	if ($query->max_num_pages <= 1) {
		return;
	}

	if($classes) {
		$classes = $classes . ' ';
	}

	$output = paginate_links([
		'base' => str_replace($bignum, '%#%', esc_url(get_pagenum_link($bignum))),
		'format' => '',
		'current' => max(1, get_query_var('paged')),
		'total' => $query->max_num_pages,
		'prev_text' => icon_svg('arrow-left', 'pagination__icon', false),
		'next_text' => icon_svg('arrow-right', 'pagination__icon', false),
		'type' => 'list',
		'end_size' => 3,
		'mid_size' => 3
	]);
	$output = '<nav class="'. $classes .'pagination">' . str_replace(["<ul class='page-numbers'>", '<li>', 'page-numbers', 'prev', 'next', 'current'], ['<ul class="pagination__items">', '<li class="pagination__item">', 'pagination__anchor', 'pagination__anchor--previous', 'pagination__anchor--next', 'is-active'], $output) . '</nav>';

	echo $output;
}


// CUSTOM BREADCRUMBS
function theme_breadcrumbs_list($wrapper_classes = false, $echo = true) {
	if($wrapper_classes) {
		$wrapper_classes = $wrapper_classes . ' ';
	}

	$output = '<ul class="'. $wrapper_classes .'breadcrumbs">';
	$output .= '<li class="breadcrumbs__item"><a href="'. home_url() .'" class="breadcrumbs__anchor">'. __('Home', 'fitbody-theme') .'</a></li>';

	if(is_page()) {

		global $post;
		$ancestors = get_post_ancestors($post->ID);
		if(!empty($ancestors)) {
			foreach($ancestors as $ancestor) {
				$post_title = get_the_title($ancestor);
				$post_url = get_permalink($ancestor);
				$output .= '<li class="breadcrumbs__item"><a href="'. $post_url .'" class="breadcrumbs__anchor">'. $post_title .'</a></li>';
			}
		}

		$current_post_title = theme_get_trimmed_text(get_the_title(get_the_ID()), 25);
		$current_post_url = get_permalink(get_the_ID());
		$output .= '<li class="breadcrumbs__item"><a href="'. $current_post_url .'" class="breadcrumbs__anchor">'. $current_post_title .'</a></li>';

	} else if(is_singular('program')) {

		global $post;
		$programs_page_id = get_field('pages_programs', 'options');
		$programs_page_url = get_permalink($programs_page_id);
		$programs_page_title = get_the_title($programs_page_id);
		if(!empty($programs_page_url) && !is_wp_error($programs_page_url)) {
			$output .= '<li class="breadcrumbs__item"><a href="'. $programs_page_url .'" class="breadcrumbs__anchor">'. $programs_page_title .'</a></li>';
		}

		$is_module = false;
		if($post->post_parent) {
			$is_module = true;
		}

		if($is_module) {
			$program_main_page_id = theme_get_module_program($post->ID);
			$program_main_page_url = get_permalink($program_main_page_id);
			$program_main_page_title = theme_get_trimmed_text(get_the_title($program_main_page_id), 25);
			if(!empty($program_main_page_url)) {
				$output .= '<li class="breadcrumbs__item"><a href="'. $program_main_page_url .'" class="breadcrumbs__anchor">'. $program_main_page_title .'</a></li>';
			}
		}

		$current_post_title = theme_get_trimmed_text(get_the_title(get_the_ID()), 25);
		$current_post_url = get_permalink(get_the_ID());
		$output .= '<li class="breadcrumbs__item"><a href="'. $current_post_url .'" class="breadcrumbs__anchor">'. $current_post_title .'</a></li>';
		
	} else if(is_singular('event')) {

		global $post;
		$blog_page_id = get_field('pages_events', 'options');
		$blog_page_url = get_permalink($blog_page_id);
		$blog_page_title = get_the_title($blog_page_id);
		if(!empty($blog_page_url) && !is_wp_error($blog_page_url)) {
			$output .= '<li class="breadcrumbs__item"><a href="'. $blog_page_url .'" class="breadcrumbs__anchor">'. $blog_page_title .'</a></li>';
		}

		$current_post_title = theme_get_trimmed_text(get_the_title(get_the_ID()), 25);
		$current_post_url = get_permalink(get_the_ID());
		$output .= '<li class="breadcrumbs__item"><a href="'. $current_post_url .'" class="breadcrumbs__anchor">'. $current_post_title .'</a></li>';

	} else if(is_singular('advice')) {

		global $post;
		$blog_page_id = get_field('pages_advice', 'options');
		$blog_page_url = get_permalink($blog_page_id);
		$blog_page_title = get_the_title($blog_page_id);
		if(!empty($blog_page_url) && !is_wp_error($blog_page_url)) {
			$output .= '<li class="breadcrumbs__item"><a href="'. $blog_page_url .'" class="breadcrumbs__anchor">'. $blog_page_title .'</a></li>';
		}

		$current_post_title = theme_get_trimmed_text(get_the_title(get_the_ID()), 25);
		$current_post_url = get_permalink(get_the_ID());
		$output .= '<li class="breadcrumbs__item"><a href="'. $current_post_url .'" class="breadcrumbs__anchor">'. $current_post_title .'</a></li>';

	} else if(is_singular('product')) {

		global $post;
		$shop_page_id = get_field('pages_store', 'options');
		$shop_page_url = get_permalink($shop_page_id);
		$shop_page_title = get_the_title($shop_page_id);
		if(!empty($shop_page_url) && !is_wp_error($shop_page_url)) {
			$output .= '<li class="breadcrumbs__item"><a href="'. $shop_page_url .'" class="breadcrumbs__anchor">'. $shop_page_title .'</a></li>';
		}

		$current_post_title = theme_get_trimmed_text(get_the_title(get_the_ID()), 25);
		$current_post_url = get_permalink(get_the_ID());
		$output .= '<li class="breadcrumbs__item"><a href="'. $current_post_url .'" class="breadcrumbs__anchor">'. $current_post_title .'</a></li>';

	} else if(is_shop()) {

		global $post;
		$shop_page_id = get_field('pages_store', 'options');
		$shop_page_url = get_permalink($shop_page_id);
		$shop_page_title = get_the_title($shop_page_id);
		if(!empty($shop_page_url) && !is_wp_error($shop_page_url)) {
			$output .= '<li class="breadcrumbs__item"><a href="'. $shop_page_url .'" class="breadcrumbs__anchor">'. $shop_page_title .'</a></li>';
		}

	} else if(is_archive('product')) {
		
		global $post;
		$shop_page_id = get_field('pages_store', 'options');
		$shop_page_url = get_permalink($shop_page_id);
		$shop_page_title = get_the_title($shop_page_id);
		if(!empty($shop_page_url) && !is_wp_error($shop_page_url)) {
			$output .= '<li class="breadcrumbs__item"><a href="'. $shop_page_url .'" class="breadcrumbs__anchor">'. $shop_page_title .'</a></li>';
		}

		$term = get_queried_object();
		$current_term = '';
		if(!empty($term)) {
			$current_term = $term->term_id;
		}
		$term_url = get_term_link($current_term, 'product_cat');
		$term_title = get_the_archive_title($current_term);
		$output .= '<li class="breadcrumbs__item"><a href="'. $term_url .'" class="breadcrumbs__anchor">'. $term_title .'</a></li>';

	}

	$output .= '</ul>';

	if($echo) {
		echo $output;
	} else {
		return $output;
	}
}


// SVG ICONS
function icon_svg($name, $classes = '', $echo = true, $autoprefix = true) {
    if(!$name) {
        return;
    }
    if($classes) {
        $classes = ' ' . $classes;
    }

	// Handle prefix
	$prefix = 'icon-';
	if(!$autoprefix || theme_string_starts_with($name, 'icon-')) {
		$prefix = '';
	}

	// Version
	$build_ver = wp_get_theme()->get('Version');

	// Output icon
	$output = '<svg class="icon'. $classes .'"><use href="'. get_template_directory_uri() .'/assets/icons/_icon-spritesheet-'. $build_ver .'.svg#'. $prefix . $name .'"></use></svg>';
	if($echo) {
		echo $output;
	} else {
		return $output;
	}
}


// DETECT IF STRING STARTS WITH SPECIFIED CHARS
function theme_string_starts_with($haystack, $needle) {
    return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}


// LOADER
// Generates a loader bar. Add class "loader--disabled" for disabled state.
function theme_loader($id = false, $classes = false, $echo = true) {
	if(!empty($id)) {
		$id = 'id="'. $id .'"';
	}
	if(!empty($classes)) {
		$classes = ' '. $classes;
	}
	if($echo) {
		$output = '<div '. $id .' class="loader'. $classes .'"><div class="loader__inner"><div class="loader__dot">&nbsp;</div><div class="loader__dot">&nbsp;</div><div class="loader__dot">&nbsp;</div></div></div>';
	} else {
		return $output;
	}
}


// EXTRACT FILE INFO FROM URL
function theme_get_file_info_from_url($url, $get_size = false) {
	$output = [];

	$file = file_get_contents($url);
	$output['name'] = basename($url);
	$output['ext'] = pathinfo($url, PATHINFO_EXTENSION);
	$output['name_without_ext'] = pathinfo($url, PATHINFO_FILENAME);

	if($get_size) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		$data = curl_exec($ch);
		$output['size'] = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($ch);
	}

	return $output;
}


// FORMAT BYTES TO OTHER SIZES
function theme_get_formatted_bytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);
    //$bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}


// CUSTOM EXCERPT
function theme_get_excerpt($post_id = false, $max_chars = 200) {
	if(empty($post_id)) {
		global $post;
		$post_data = [
			'id'	=> $post->ID,
			'excerpt'	=> $post->excerpt,
			'content'	=> $post->post_content
		];
	} else {
		$post_data = get_post($post_id);
		$post_data = [
			'id'	=> $post_data->ID,
			'excerpt'	=> $post_data->excerpt,
			'content'	=> $post_data->post_content
		];
	}

	if(!empty($post_data['excerpt'])) {
		$excerpt = theme_get_trimmed_text($post_data['excerpt'], $max_chars);
	} else {
		$excerpt = strip_tags(theme_get_trimmed_text($post_data['content'], $max_chars));
	}

	return $excerpt;
}


// CUSTOM IMAGE GET, RETURNS ARRAY OF IMAGE DATA
function theme_get_image($attachment_id, $image_size = false, $alt_fallback = false) {
	if(!is_int($attachment_id)) {
		return;
	}
	if(!$image_size) {
		$image_size = 'full';
	}

	$url = wp_get_attachment_image_url($attachment_id, $image_size);
	$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	if(empty($alt)) {
		if(!empty($alt_fallback)) {
			$alt = $alt_fallback;
		} else {
			$alt = __( 'Image', 'fitbody-theme' );
		}
	}

	$output = [
		'url'	=> $url,
		'alt'	=> $alt,
	];

	return $output;
}


// CUSTOM IMAGE GET, RETURNS WP-BUILT IMG TAG
function theme_get_wp_image($attachment_id, $image_size = false, $classes = [], $alt_fallback = false, $alt_override = false, $data_attributes = []) {
	if(empty($attachment_id) || !is_int($attachment_id)) {
		return;
	}
	if(!$image_size) {
		$image_size = 'full';
	}

	// Handle attributes
	$attributes = [
		'class' => $classes
	];

	// Handle alt override
	if(!empty($alt_override)) {
		$attributes['alt'] = $alt_override;
	}

	// Handle getting image
	$image = wp_get_attachment_image($attachment_id, $image_size, false, $attributes);

	// Handle alt fallback
	if(strpos($image, 'alt=""')) {
		$alt_fallback = 'alt="' . $alt_fallback .'"';
		$image = str_replace('alt=""', $alt_fallback, $image);
	}

	// Handle data attribute insertion
	if(!empty($data_attributes) && is_array($data_attributes)) {
		foreach($data_attributes as $key => $data_attribute) {
			$current_attribute = 'data-' . $key . '="' . $data_attribute . '"';
			$current_attribute = ' '. $current_attribute;
			$image = substr_replace($image, $current_attribute, -2, 0);
		}
	}

	return $image;
}


// GET SOCIAL MEDIA SHARE LINK
function theme_get_social_share_url($site, $url = false) {
	if(empty($site)) {
		return;
	}

	if(!$url) {
		global $wp;
		$url = home_url($wp->request);
	}
		
	$url_encoded = urlencode($url);

	$output = '';
	switch($site) {
		case 'facebook':
			$output = 'https://www.facebook.com/sharer/sharer.php?u=' . $url_encoded;
			break;
		case 'twitter':
			$output = 'https://twitter.com/intent/tweet?text=' . $url_encoded;
			break;
		case 'linkedin':
			$output = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url_encoded;
			break;
		case 'messenger':
			$output = 'fb-messenger://share/?link=' . $url_encoded;
			break;
	}

	return $output;
}


// REMOVE USELESS .00 DECIMALS
function TrimTrailingZeroes($number) {
    return str_replace('.00', '', number_format((float)$number, 2, '.', ''));
}


// MULTIDIMENSIONAL IN_ARRAY
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

?>