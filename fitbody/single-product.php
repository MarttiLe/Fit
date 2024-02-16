<?php
    // REDIRECT TO PROGRAM PAGE IF THIS PRODUCT IS A PROGRAM
    $is_program = get_field('product_is_program');
    if($is_program) {
        $linked_program = get_field('linked_program');
        $program_page = get_permalink($linked_program);
        wp_safe_redirect( $program_page, 301 );
    }
?>

<?php get_header(); ?>

<?php 
    global $product;

    // Banner image
    $store_page = get_field('pages_store', 'options');
    $banner_image = get_the_post_thumbnail_url($store_page, 'page-banner');
    $heading_style = '';
    if(!empty($banner_image)) {
        $heading_style = ' style="background-image: url('. $banner_image .');"';
    }

    // Gallery
    $featured_img = get_post_thumbnail_id($id);
    if(!empty($featured_img)) {
        $featured_img_full = wp_get_attachment_image_url($featured_img, 'full');
        $featured_img = theme_get_wp_image($featured_img, 'product-full', 'product-gallery__img', __('Product photo', 'fitbody-theme'));
    } else {
        $featured_img_full = wp_get_attachment_image_url(get_field('placeholder_img_product', 'options'), 'full');
        $featured_img = theme_get_wp_image(get_field('placeholder_img_product', 'options'), 'product-full', 'product-gallery__img', __('Product photo', 'fitbody-theme'));
    }
    $gallery_featured = [
        'thumb'     => $featured_img,
        'full'      => $featured_img_full
    ];
    $gallery_images = $product->get_gallery_image_ids();
    $gallery_items = [];
    if(!empty($gallery_images)) {
        foreach($gallery_images as $key => $image) {
            $image = [
                'thumb'     => theme_get_wp_image($image, 'product-thumb', 'product-gallery__img'),
                'full'      => wp_get_attachment_image_url($image, 'full')
            ];
            $gallery_items[$key] = $image;
        }
    }
    $gallery_count = count($gallery_items);
    $more_images_text = '+' . ($gallery_count - 2) . ' ' . __( 'more', 'fitbody-theme' );

    // Product data
    $is_variable = $product->is_type('variable');
    $related_products = $product->get_upsell_ids();

    // Compile final data
    $post_data = [
        'content'           => get_the_content(),
        'price'             => $product->get_regular_price(),
        'discount_price'    => $product->get_sale_price(),
        'currency'          => get_woocommerce_currency_symbol(),
        'is_variable'       => $is_variable,
        'gallery'           => [
            'featured'          => $gallery_featured,
            'items'             => $gallery_items,
            'count'             => $gallery_count
        ]
    ];
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
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

    <div class="page-content">
        <div class="container">
            <div class="page-content__inner">

                <?php 
                    // WC errors/notices
                    do_action( 'woocommerce_before_single_product' ); 
                ?>

                <div class="product__main">
                    <div class="product__gallery product-gallery">
                        <ul class="product-gallery__items">
                            <li class="product-gallery__item">
                                <a href="<?php echo $post_data['gallery']['featured']['full']; ?>" data-fancybox="product-gallery" class="product-gallery__anchor" title="<?php echo __( 'View product gallery', 'fitbody-theme' ); ?>">
                                    <?php echo $post_data['gallery']['featured']['thumb']; ?>
                                </a>
                            </li>

                            <?php if(!empty($post_data['gallery']['items'])) : ?>
                                <?php foreach($post_data['gallery']['items'] as $key => $gallery_item) : ?>
                                    <?php if($key < 2 || ($key === 2 && $post_data['gallery']['count'] <= 3)) : ?>
                                    <li class="product-gallery__item">
                                        <a href="<?php echo $gallery_item['full']; ?>" data-fancybox="product-gallery" class="product-gallery__anchor" title="<?php echo __( 'View product gallery', 'fitbody-theme' ); ?>">
                                            <?php echo $gallery_item['thumb']; ?>
                                        </a>
                                    </li>
                                    <?php elseif($key === 2 && $post_data['gallery']['count'] > 3) : ?>
                                    <li class="product-gallery__item">
                                        <a href="<?php echo $gallery_item['full']; ?>" data-fancybox="product-gallery" class="product-gallery__anchor product-gallery__more" title="<?php echo __( 'View product gallery', 'fitbody-theme' ); ?>">
                                            <?php icon_svg('gallery', 'product-gallery__icon'); ?>
                                            <span><?php echo $more_images_text; ?></span>
                                        </a>
                                    </li>
                                    <?php else : ?>
                                        <li class="product-gallery__item">
                                            <a href="<?php echo $gallery_item['full']; ?>" data-fancybox="product-gallery" class="product-gallery__anchor">&nbsp;</a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="product__content">
                        <?php if(!empty($post_data['content'])) : ?>
                        <div class="product__description editor-content">
                            <?php the_content(); ?>
                        </div>
                        <?php endif; ?>

                        <?php if(!$post_data['is_variable']) : ?>
                            <?php if(empty($post_data['discount_price'])) : ?>
                            <div class="product__price price price--product"><span class="price__standard"><?php echo $post_data['price']; ?><?php echo $post_data['currency']; ?></span></div>
                            <?php else : ?>
                            <div class="product__price price price--product">
                                <div class="price__main">
                                    <div class="price__title"><?php echo __( 'Price', 'fitbody-theme' ); ?>:</div>
                                    <del class="price__old"><?php echo $post_data['price']; ?><?php echo $post_data['currency']; ?></del>
                                    <span class="price__new"><?php echo $post_data['discount_price']; ?><?php echo $post_data['currency']; ?></span>
                                </div>
                            </div>
                            
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="product__options">
                            <?php woocommerce_template_single_add_to_cart(); ?>
                        </div>
                    </div>
                </div>

                <?php if(get_field('display_block_product_additional_info')) : ?>
                    <div class="product__extra">
                        <?php 
                            $block_attr = [];
                            get_template_part('templates/blocks/product-additional-info', null, $block_attr);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if(!empty($related_products)) : ?>
                <div class="product__related">
                    <?php
                        $block_attr = [
                            'item_ids'  => $related_products
                        ];
                        get_template_part('templates/blocks/related-products', null, $block_attr);
                    ?>
                </div>
                <?php endif; ?>

                <?php 
                    do_action( 'woocommerce_after_single_product' ); 
                ?>

            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>