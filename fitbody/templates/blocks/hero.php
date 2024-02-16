<?php
    /**
     * HERO SLIDER BLOCK
     * Requires hero-slider.js component
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_hero');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }


    // Manage block details
    $slider_items_total = 0;
    if(!empty($block_data['items'])) {
        $slider_items_total = count($block_data['items']);
    }

    if($slider_items_total <= 1) {
        $block_classes .= ' hero--no-slides';
    }
?>


<section class="hero js-hero-carousel<?php echo $block_classes; ?>" data-hero-carousel-slides-total="<?php echo $slider_items_total; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>

    <div class="hero__container container">

        <?php if(!empty($block_data['items'])) : ?>

            <ul class="hero__backgrounds">
                <?php foreach($block_data['items'] as $key => $item) : ?>
                    <?php 
                        $is_active = '';
                        if($key === 0) {
                            $is_active = ' is-active';
                        }
                    ?>
                    <li class="hero__background js-hero-carousel-background<?php echo $is_active; ?>" data-hero-carousel-slide="<?php echo $key; ?>" style="background-image: url('<?php echo wp_get_attachment_image_url($item['image'], 'full-hd'); ?>');">&nbsp;</li>
                <?php endforeach; ?>
            </ul>

            <div class="hero__inner">
                <ul class="hero__contents">
                    <?php foreach($block_data['items'] as $key => $item) : ?>
                        <?php 
                            $is_active = '';
                            if($key === 0) {
                                $is_active = ' is-active';
                            }
                        ?>
                        <li class="hero__content js-hero-carousel-content<?php echo $is_active; ?>" data-hero-carousel-slide="<?php echo $key; ?>">
                            <h1 class="hero__title h1"><?php echo $item['title']; ?></h1>

                            <?php if(!empty($item['text'])) : ?>
                                <p class="hero__subtitle"><?php echo $item['text']; ?></p>
                            <?php endif; ?>

                            <?php if(!empty($item['button']['url'])) : ?>
                                <?php 
                                    if(empty($item['button']['title'])) {
                                        $item['button']['title'] = __( 'Read more', 'fitbody-theme' );
                                    }
                                ?>
                                <div class="hero__options">
                                    <a href="<?php echo $item['button']['url']; ?>" target="<?php echo $item['button']['target']; ?>" class="more-button"><span class="more-button__text"><?php echo $item['button']['title']; ?><?php icon_svg('arrow-right'); ?></span></a>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if($slider_items_total > 1) : ?>
                    <div class="hero__dots">
                        <div class="slider-nav">
                            <?php foreach($block_data['items'] as $key => $item) : ?>
                                <?php 
                                    $is_active = '';
                                    if($key === 0) {
                                        $is_active = ' is-active';
                                    }
                                ?>
                                <button class="slider-nav__item js-hero-carousel-dot<?php echo $is_active; ?>" data-hero-carousel-slide="<?php echo $key; ?>"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>
</section>