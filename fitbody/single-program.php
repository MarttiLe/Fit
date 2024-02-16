<?php
    global $post;

    // Determine if this post is a program or module, get data for validity checks
    if($post->post_parent) {
        $is_module = true;
        $module_id = $post->ID;
        $program_id = theme_get_module_program($module_id);
        $is_owned = theme_get_program_ownership_status($program_id);
        $module_status = theme_get_module_status($module_id, $program_id);
        $module_type = get_field('type', $module_id);
        
    } else {
        $is_module = false;
        $program_id = $post->ID;
        $is_owned = theme_get_program_ownership_status($program_id);
        $is_unlocked = true;
    }

    // Run checks and redirect if anything fails
    $program_url = get_permalink($program_id);
    if($is_module && !$is_owned || $is_module && $module_type === 'group' || $is_module && $module_status === 'locked') {
        if(!current_user_can('manage_options')) {
            wp_safe_redirect($program_url, 301);
        }
    }
?>


<?php get_header(); ?>

<?php
    if(!$is_module) {
        // Main program page
        get_template_part('templates/single-program/single-program-content');
    } else {
        // Program module page
        get_template_part('templates/single-program/single-program-part-content');
    }
?>

<?php get_footer(); ?>