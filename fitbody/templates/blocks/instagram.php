<?php
    /**
     * INSTAGRAM BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_instagram');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }

    if(empty($block_data['text'])) {
        $block_data['text'] = __( 'Follow us', 'fitbody-theme' );
    }
    
    if(!empty($block_data['photos'])) {
        if(!empty($block_data['photo_display_style'])) {
            shuffle($block_data['photos']);
        }
        array_slice($block_data['photos'], 0, 9);
        foreach($block_data['photos'] as $key => $photo) {
            $block_data['photos'][$key] = theme_get_wp_image($photo['photo'], 'profile-thumb', 'instagram__img', __('Instagram photo', 'fitbody-theme'));
        }
    }
?>


<section class="instagram<?php echo $block_classes; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>

    <div class="instagram__inner">
        <?php if(!empty($block_data['photos'])) : ?>
        <ul class="instagram__items">
            <?php foreach($block_data['photos'] as $key => $photo) : ?>
            <li class="instagram__item">
                <?php echo $photo; ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <div class="instagram__label-wrap">
            <a href="https://instagram.com/<?php echo $block_data['handle']; ?>" target="_blank" class="instagram__label" title="<?php echo __( 'Visit our instagram page', 'fitbody-theme' ); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/icon-instagram-colored.svg" alt="<?php echo __( 'Instagram icon', 'fitbody-theme' ); ?>" class="instagram__icon">
                <h4 class="instagram__title"><?php echo $block_data['text']; ?></h4>
                <p class="instagram__subtitle">@<?php echo $block_data['handle']; ?></p>
            </a>
        </div>
    </div>
</section>