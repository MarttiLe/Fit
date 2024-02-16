<?php
    /**
     * PRODUCT CARD (WC)
     * @param post_id int (optional) - pass post ID to get a specific post, otherwise global wp $post object will be used
     * @param item_classes string (optional) - pass additional classes for the LI element
    **/

    if(!empty($args['post_id']) && is_int($args['post_id'])) {
        $post = get_post($args['post_id']);
        $id = $args['post_id'];
    } else {
        global $post;
        $id = $post->ID;
    }
    if(empty($post) || is_wp_error($post)) {
        return;
    }

    $item_classes = '';
    if(!empty($args['classes'])) {
        $item_classes = ' ' . $args['classes'];
    }

    $product = wc_get_product($id);

    $featured_img = get_post_thumbnail_id($id);
    if(!empty($featured_img)) {
        $featured_img = theme_get_wp_image($featured_img, 'product-thumb', 'product-card__img', __('Product photo', 'fitbody-theme'));
    } else {
        $featured_img = theme_get_wp_image(get_field('placeholder_img_product', 'options'), 'product-thumb', 'product-card__img', __('Product photo', 'fitbody-theme'));
    }

    $post_data = [
        'id'                => $id,
        'title'             => $post->post_title,
        'url'               => get_permalink($id),
        'image'             => $featured_img,
        'price'             => theme_get_product_price_data($product, true)
    ];
?>


<li class="product-list__item<?php echo $item_classes; ?>">
    <a href="<?php echo $post_data['url']; ?>" class="product-card">
        <div class="product-card__inner">
            <div class="product-card__image">
                <?php echo $post_data['image']; ?>
            </div>

            <h3 class="product-card__title"><?php echo $post_data['title']; ?></h3>

            <?php
                $block_attr = [
                    'classes' => 'product-card__price price--product-card',
                    'price_data'    => $post_data['price'],
                ];
                get_template_part('templates/components/price-tag', null, $block_attr);

                
            ?>

            <button class="product-card__button button button--bordered button--icon-right"><?php echo __( 'View details', 'fitbody-theme' ); ?><?php icon_svg('arrow-right', 'button__icon'); ?></button>
        </div>
    </a>
</li>