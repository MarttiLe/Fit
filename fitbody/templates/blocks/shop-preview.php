<?php
    /**
     * SHOP PREVIEW BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_shop_preview');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }


    // Manage block details
    $post_amount = 4;
    if(!empty($block_data['amount_of_posts'])) {
        $post_amount = $block_data['amount_of_posts'];
    }

    $product_items = new WP_Query([
        'posts_per_page'    => $post_amount,
        'post_type'         => 'product',
        'status'            => 'publish',
        'order'             => 'DESC',
        'suppress_filters'  => false,
        'tax_query' => [
            [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'exclude-from-catalog',
                'operator' => 'NOT IN',
            ],
        ],
        'meta_query' => [
            [
                'key'       => 'product_is_program',
                'value'     => true,
                'compare'   => '!='

            ]
        ]
    ]);

    $store_page_url = get_permalink(get_field('pages_store', 'options'));
?>


<section class="shop section">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>

    <div class="container">
        <div class="section-heading">
            <h2 class="section-heading__title title h2"><?php echo $block_data['title']; ?></h2>
        </div>

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

        <div class="shop__options">
            <a href="<?php echo $store_page_url; ?>" class="more-button"><span class="more-button__text"><?php echo __( 'View all products', 'fitbody-theme' ); ?><?php icon_svg('arrow-right'); ?></span></a>
        </div>
    </div>
</section>