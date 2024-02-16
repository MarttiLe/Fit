<?php
/**
* Template Name: Debug page
*/
?>

<?php 
// REDIRECT IF NOT ADMIN
if(!is_user_logged_in() || !current_user_can('administrator')) {
    $redirect_url = home_url();
    wp_safe_redirect($redirect_url, 301);
}
?>

<?php 
	$users = get_users();
?>

<?php get_header(); ?>

    <div class="page-heading page-heading--centered">
        <div class="container">
            <div class="page-heading__inner">
                <div class="page-heading__breadcrumb">
                    <?php theme_breadcrumbs_list(); ?>
                </div>

                <h1 class="page-heading__title h2"><?php the_title(); ?></h1>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container container--md">
            <div class="page-content__inner">
                <div class="editor-content">
                    <?php if(!empty($users)) : ?>
                        <?php foreach($users as $user) : ?>
                            <?php
                                $user_programs = get_user_meta($user->ID, 'programs_owned', );
                                $user_program_history = get_user_meta($user->ID, 'programs_history');
                            ?>
                            <div class="debug-user">
                                <button class="debug-user__accordion accordion-bar js-accordion-toggle" data-accordion-id="user-<?php echo $user->ID; ?>">ID: <?php echo $user->ID; ?> &mdash; <?php echo $user->user_nicename; ?> &mdash; <?php echo $user->user_email; ?><?php icon_svg('caron-down', 'accordion-bar__icon'); ?></button>
                                <div class="debug-user__content accordion-content js-accordion-content" data-accordion-id="user-<?php echo $user->ID; ?>">
                                    <div class="debug-user__content-item">
                                        <strong>User programs:</strong>
                                        <pre><?php var_dump($user_programs); ?></pre>
                                    </div>

                                    <div class="debug-user__content-item">
                                        <strong>User program history:</strong>
                                        <pre><?php var_dump($user_program_history); ?></pre>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>