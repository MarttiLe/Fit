<?php
    /**
     * TESTIMONIALS SLIDER BLOCK
     * Requires glide.js slider component!
     * Initialization is handled in JS
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_testimonials');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }


    // Manage block details
    $slider_items_total = 0;
    if(!empty($block_data['items'])) {
        $slider_items_total = count($block_data['items']);
    }
?>


<section class="testimonials section<?php echo $block_classes; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>
    
    <div class="container">
        <div class="testimonials__heading section-heading">
            <h2 class="section-heading__title title h2"><?php echo $block_data['title']; ?></h2>
        </div>

        <?php if(!empty($block_data['items'])) : ?>
        <div class="testimonials__slider slider js-testimonials-slider">
            <div class="slider__track" data-glide-el="track">
                <ul class="slider__items">
                    <?php foreach($block_data['items'] as $key => $testimonial) : ?>
                    <li class="testimonial-card slider__item<?php if($key === 0) { echo ' slider__item--active'; } ?>">
                        <div class="testimonial-card__inner">
                            <blockquote class="testimonial-card__quote">
                                <?php echo $testimonial['text']; ?>
                            </blockquote>

                            <div class="testimonial-card__profile">
                                <div class="testimonial-card__photo">
                                    <?php echo theme_get_wp_image($testimonial['photo'], 'profile-thumb', 'testimonial-card__img circular-image', __( 'Profile picture for', 'fitbody-theme' ) . ' ' . $testimonial['name']); ?>
                                </div>
                                <div class="testimonial-card__info">
                                    <div class="testimonial-card__name"><?php echo $testimonial['name']; ?></div>
                                    <?php if(!empty($testimonial['position'])) : ?>
                                    <div class="testimonial-card__position"><?php echo $testimonial['position']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if($slider_items_total > 1) : ?>
            <div class="testimonials__dots slider__dots slider-nav" data-glide-el="controls[nav]">
                <?php $page_counter = 0; foreach($block_data['items'] as $testimonial) : ?>
                    <button class="slider__dot slider-nav__item" data-glide-dir="=<?php echo $page_counter; ?>"></button>
                    <?php $page_counter++; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>