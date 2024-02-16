<?php
    /**
     * PROGRAM CARD
     * @param post_id int (optional) - pass post ID to get a specific post, otherwise global wp $post object will be used
     * @param item_classes string (optional) - pass additional classes for the element
    **/

    if(!empty($args['post_id']) && is_int($args['post_id'])) {
        $post = get_post($args['post_id']);
        $post_id = $args['post_id'];
    } else {
        global $post;
        $post_id = $post->ID;
    }
    if(empty($post) || is_wp_error($post)) {
        return;
    }

    $is_owned = false;
    if(!empty($args['is_owned'])) {
        $is_owned = true;

    }

    $ownership_dates = false;
    $ownership_start = false;
    $ownership_end = __('Permanent', 'fitbody-theme');
    if(!empty($args['ownership_dates'])) {
        $ownership_dates = $args['ownership_dates'];
        $ownership_start = $ownership_dates['from']['date'];
        if(!empty($ownership_dates['to']['date'])) {
            $ownership_end = $ownership_dates['to']['date'];
        }
    }

    $item_classes = '';
    if(!empty($args['item_classes'])) {
        $item_classes = ' ' . $args['item_classes'];
    }

    $type = get_field('type', $post_id);
    $product_id = get_field('linked_product', $post_id);
    $heading_data = get_field('heading', $post_id);
    $overview_data = get_field('overview', $post_id);

    $featured_img = get_the_post_thumbnail_url($post_id, 'program-thumb');
    if(empty($featured_img)) {
        $featured_img = wp_get_attachment_image_url($heading_data['heading_image'], 'program-thumb');
        if(empty($featured_img)) {
            $featured_img = wp_get_attachment_image_url(get_field('placeholder_img_program', 'options'), 'program-thumb');
        }
    }

    $button_text = __( 'View details', 'fitbody-theme' );
    if($is_owned) {
        $button_text = __( 'Continue program', 'fitbody-theme' );
    }

    $post_data = [
        'id'                => $post_id,
        'title'             => $post->post_title,
        'url'               => get_permalink($post_id),
        'image'             => $featured_img,
        'start_date'        => get_field('start_date', $post_id),
        'length'            => $overview_data['length'],
        'frequency'         => $overview_data['workout_frequency'],
        'price'             => theme_get_product_price_data($product_id, true),
        'is_owned'          => $is_owned,
        'ownership_start'   => $ownership_start,
        'ownership_end'     => $ownership_end,
        'button_text'       => $button_text
    ];
?>


<a href="<?php echo $post_data['url']; ?>" class="program-card<?php echo $item_classes; ?>">
    <div class="program-card__inner">
        <div class="program-card__image" style="background-image: url('<?php echo $post_data['image']; ?>');"></div>

        <div class="program-card__content">
            <h3 class="program-card__title title h3"><?php echo $post_data['title']; ?></h3>
            <ul class="program-card__details">
                <?php if($is_owned) : ?>
                    <?php if($type === 'challenge') : ?>
                    <li><?php echo __( 'Start date', 'fitbody-theme' ); ?>: <span class="program-card__detail"><?php echo $post_data['start_date']; ?></span></li>
                    <?php else : ?>
                    <li><?php echo __( 'Started on', 'fitbody-theme' ); ?>: <span class="program-card__detail"><?php echo $post_data['ownership_start']; ?></span></li>
                    <?php endif; ?>

                    <?php if(!empty($post_data['ownership_end'])) : ?>
                    <li><?php echo __( 'Available until', 'fitbody-theme' ); ?>: <span class="program-card__detail"><?php echo $post_data['ownership_end']; ?></span></li>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if($type === 'challenge') : ?>
                    <li><?php echo __( 'Start date', 'fitbody-theme' ); ?>: <span class="program-card__detail"><?php echo $post_data['start_date']; ?></span></li>
                    <?php else : ?>
                    <li><?php echo __( 'Start', 'fitbody-theme' ); ?>: <span class="program-card__detail"><?php echo __( 'Anytime', 'fitbody-theme' ); ?></span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(!empty($post_data['length'])) : ?>
                <li><?php echo __( 'Length', 'fitbody-theme' ); ?>: <span class="program-card__detail"><?php echo $post_data['length']; ?></span></li>
                <?php endif; ?>

                <?php if(!empty($post_data['frequency'])) : ?>
                <li><?php echo __( 'Workouts per week', 'fitbody-theme' ); ?>: <span class="program-card__detail"><?php echo $post_data['frequency']; ?></span></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="program-card__bottom">
            <div class="program-card__price">
            <?php
                if(!$is_owned) {
                    $block_attr = [
                        'classes'       => 'price--program-card',
                        'price_data'    => $post_data['price']
                    ];
                    get_template_part('templates/components/price-tag', null, $block_attr);
                }
            ?>
            </div>

            <div class="program-card__options"><button class="program-card__button button button--size-lg button--icon-right button--skewed"><span class="button__text"><?php echo $post_data['button_text']; ?></span><?php icon_svg('arrow-right', 'button__icon'); ?></button></div>
        </div>
    </div>
</a>