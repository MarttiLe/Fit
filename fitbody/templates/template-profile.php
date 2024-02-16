<?php
/**
* Template Name: Profile page (Login, register, dashboard)
*/
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


<div class="page-content account">

    <?php if(is_wc_endpoint_url('lost-password')) : ?>
    <div class="container container--md">
        <div class="page-content__inner">
    <?php endif; ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; endif; ?>

    <?php if(is_wc_endpoint_url('lost-password')) : ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if(is_user_logged_in()) : ?>
    <div class="account__logout">
        <div class="container">
            <a href="<?php echo wp_logout_url(home_url()); ?>"><?php echo __( 'Log out', 'fitbody-theme' ); ?></a>
        </div>
    </div>
    <?php endif; ?>

</div>


<?php get_footer(); ?>