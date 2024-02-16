<?php
/**
* Template Name: Blog page
*/
?>

<?php
    $post_type = get_field('post_type');
    if(empty($post_type) || $post_type !== 'advice' && $post_type !== 'event') {
        $post_type = 'post';
    }

    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'page-banner');
    $heading_style = '';
    if(!empty($featured_image)) {
        $heading_style = ' style="background-image: url('. $featured_image .');"';
    }

    $posts_per_page = get_option('posts_per_page');
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $blog_items = new WP_Query([
        'post_type'         => $post_type,
        'status'            => 'publish',
        'posts_per_page'    => $posts_per_page,
        'paged'             => $paged,
        'order'             => 'DESC',
        'suppress_filters'  => false
    ]);
    $is_final_page = false;
	if($paged >= $blog_items->max_num_pages) {
		$is_final_page = true;
	}
?>

<?php get_header(); ?>

<div class="page-heading"<?php echo $heading_style; ?>>
    <div class="container">
        <div class="page-heading__inner">
            <div class="page-heading__breadcrumb">
                <?php theme_breadcrumbs_list(); ?>
            </div>

            <h1 class="page-heading__title h2"><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<section class="blog page-content page-content--transparent">
    <div class="container">
        <div class="page-content__inner">

            <?php if($blog_items->have_posts()) : ?>
                <ul class="blog__items blog-list js-loadmore-<?php echo $post_type; ?>-container">
                    <?php 
                        while($blog_items->have_posts()) {

                            $blog_items->the_post();
                            get_template_part('templates/components/card-blog');

                        }
                        wp_reset_postdata();
                    ?>
                </ul>

                <?php if(!$is_final_page) : ?>
                <div class="blog__options">
                    <button class="blog__loadmore loadmore-button js-loadmore-<?php echo $post_type; ?>"><?php echo __( 'Load more posts', 'fitbody-theme' ); ?></button>
                </div>
                <?php endif; ?>
            <?php else : ?>
            <div class="alert-bar alert-bar--white is-active"><p class="alert-bar__text"><?php echo __( 'There are currently no posts available', 'fitbody-theme' ); ?></p></div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>