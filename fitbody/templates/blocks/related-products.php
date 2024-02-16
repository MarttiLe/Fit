<?php
    /**
     * RELATED PRODUCTS SLIDER BLOCK
     * Requires glide.js slider component!
     * Initialization is handled in JS
     * @param block_classes string (optional) - pass additional classes for the section element
     * @param item_ids array (optional) - pass item ids for the loop manually
    **/


    // Get block data
    $block_data = get_field('block_related_products');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }

    $item_classes = '';
    if(!empty($args['item_classes'])) {
        $item_classes = ' ' . $args['item_classes'];
    }


    // Manage block details
    if(!empty($args['item_ids'])) {
        $block_data['items'] = $args['item_ids'];
    }

    $product_items = new WP_Query([
        'posts_per_page'    => 8,
        'post_type'         => 'product',
        'status'            => 'publish',
        'order'             => 'DESC',
        'post__in'          => $block_data['items'],
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

    $slider_items_total = 0;
    if(!empty($block_data['items'])) {
        $slider_items_total = count($block_data['items']);
    }

    if(empty($block_data['title'])) {
        $block_data['title'] = __( 'Related products', 'fitbody-theme' );
    }

    if(!empty($args['block_title'])) {
        $block_data['title'] = $args['block_title'];
    }
?>


<div class="related-products<?php echo $block_classes; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>
    
    <div class="related-products__heading section-heading section-heading--has-options">
        <h2 class="section-heading__title title h3"><?php echo $block_data['title']; ?></h2>

        <div class="section-heading__options">
            <?php if(!empty($block_data['items'])) : ?>
            <div class="related-products__nav slider-nav slider-nav--arrows" data-glide-el="controls">
                <button class="slider-nav__arrow slider-nav__arrow--prev js-product-slider-nav-prev" data-glide-dir="<" title="<?php _e( 'Previous', 'fitbody-theme' ); ?>"><?php icon_svg('arrow-left', 'slider-nav__icon'); ?><span class="screen-reader-text"><?php echo _e('Previous', 'fitbody-theme'); ?></span></button>
                <button class="slider-nav__arrow slider-nav__arrow--next js-product-slider-nav-next" data-glide-dir=">" title="<?php _e( 'Next', 'fitbody-theme' ); ?>"><?php icon_svg('arrow-right', 'slider-nav__icon'); ?><span class="screen-reader-text"><?php echo _e('Next', 'fitbody-theme'); ?></span></button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if(!empty($block_data['items'])) : ?>
    <div class="related-products__slider slider js-product-slider">
        <div class="slider__track" data-glide-el="track">
            <?php if($product_items->have_posts()) : ?>
            <ul class="related-products__items slider__items product-list product-list--slider">
                <?php 
                    while($product_items->have_posts()) {

                        $product_items->the_post();
                        get_template_part('templates/components/card-product', null, ['classes' => 'slider__item' . $item_classes]);

                    }
                    wp_reset_postdata();
                ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>