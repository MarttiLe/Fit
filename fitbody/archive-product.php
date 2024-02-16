<?php get_header(); ?>

<?php
    $product_categories = get_terms([
        'taxonomy'   => "product_cat",
        'hide_empty' => true,
        'exclude'    => get_field('pages_programs_category', 'options')
    ]);
    $posts_per_page = get_option('posts_per_page');
    $product_items = new WP_Query([
        'post_type'         => 'product',
        'posts_per_page'    => $posts_per_page,
        'paged'             => $paged,
        'status'            => 'publish',
        'order'             => 'DESC',
        'suppress_filters'  => false,
        'meta_query' => [
            [
                'key'       => 'product_is_program',
                'value'     => true,
                'compare'   => '!='

            ]
        ]
    ]);
    $product_count = count($product_items->posts);

    $store_page = get_field('pages_store', 'options');
    $store_page_url =  get_permalink($store_page);
    $store_page_title = get_the_title($store_page);
    $banner_image = get_the_post_thumbnail_url($store_page, 'page-banner');
    $heading_style = '';
    if(!empty($banner_image)) {
        $heading_style = ' style="background-image: url('. $banner_image .');"';
    }
?>

<div class="page-heading"<?php echo $heading_style; ?>>
    <div class="container">
        <div class="page-heading__inner">
            <div class="page-heading__breadcrumb">
                <?php theme_breadcrumbs_list(); ?>
            </div>

            <h1 class="page-heading__title h2"><?php echo $store_page_title; ?></h1>
        </div>
    </div>
</div>

<section class="shop page-content">
    <div class="container">
        <div class="page-content__inner">

            <?php if(!empty($product_categories)) : ?>
            <ul class="page-content__filters nav-tabs nav-tabs--margin-bottom-lg">
                <li class="nav-tabs__item">
                    <a href="<?php echo $store_page_url; ?>" class="nav-tabs__anchor is-active"><?php echo __( 'All items', 'fitbody-theme' ); ?></a>
                </li>
                <?php foreach($product_categories as $product_cat) : ?>
                <li class="nav-tabs__item">
                    <a href="<?php echo get_term_link($product_cat->term_id); ?>" class="nav-tabs__anchor"><?php echo $product_cat->name; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <div class="shop__content">
                <?php if($product_items->have_posts()) : ?>
                <ul class="shop__items product-list">
                    <?php 
                        while($product_items->have_posts()) {

                            $product_items->the_post();
                            get_template_part('templates/components/card-product');

                        }
                        wp_reset_postdata();
                    ?>
                </ul>
                <?php else : ?>
                <p class="shop__error"><?php echo __( 'It appears there are currently no products available', 'fitbody-theme' ); ?></p>
                <?php endif; ?>
            </div>

            <div class="shop__pagination">
                <?php theme_pagination_nav($product_items); ?>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>