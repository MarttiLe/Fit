<?php
    /**
     * BLOG PREVIEW BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_blog_preview');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }


    // Manage block details
    if($block_data['select_posts_manually']) {
        $advice_items = new WP_Query([
            'post__in'          => $block_data['posts_advice'],
            'post_type'         => 'advice',
            'status'            => 'publish',
            'suppress_filters'  => false
        ]);
        $events_items = new WP_Query([
            'post__in'          => $block_data['posts_events'],
            'post_type'         => 'event',
            'status'            => 'publish',
            'suppress_filters'  => false
        ]);
    } else {
        $post_amount = 3;
        if(!empty($block_data['amount_of_posts'])) {
            $post_amount = $block_data['amount_of_posts'];
        }

        $advice_items = new WP_Query([
            'posts_per_page'    => $post_amount,
            'post_type'         => 'advice',
            'status'            => 'publish',
            'order'             => 'DESC',
            'suppress_filters'  => false
        ]);
        $events_items = new WP_Query([
            'posts_per_page'    => $post_amount,
            'post_type'         => 'event',
            'status'            => 'publish',
            'order'             => 'DESC',
            'suppress_filters'  => false
        ]);
    }

    $events_page_url = get_permalink(get_field('pages_events', 'options'));
    $advice_page_url = get_permalink(get_field('pages_advice', 'options'));
?>


<section class="blog-preview section section--padding-sm<?php echo $block_classes; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>

    <div class="blog-preview__bg dual-bg dual-bg--reverse">&nbsp;</div>

    <div class="container">
        <div class="section-heading section-heading--has-options">
            <h2 class="blog-preview__title section-heading__title title h2"><?php echo $block_data['title']; ?></h2>
            
            <div class="section-heading__options">
                <ul class="blog-preview__tabs nav-tabs">
                    <li class="nav-tabs__item"><button class="nav-tabs__anchor js-nav-tab is-active" data-tabs-id="blog" data-tabs-content-id="0"><?php echo __( 'Events', 'fitbody-theme' ); ?></button></li>
                    <li class="nav-tabs__item"><button class="nav-tabs__anchor js-nav-tab" data-tabs-id="blog" data-tabs-content-id="1"><?php echo __( 'Health advice', 'fitbody-theme' ); ?></button></li>
                </ul>
            </div>
        </div>

        <div class="nav-tabs-content">
            <div class="blog-preview__content tab-content js-nav-tab-content is-active" data-tabs-id="blog" data-tabs-content-id="0">
                <?php if($events_items->have_posts()) : ?>
                <ul class="blog-preview__items blog-list">
                    <?php 
                        while($events_items->have_posts()) {

                            $events_items->the_post();
                            get_template_part('templates/components/card-blog');

                        }
                        wp_reset_postdata();
                    ?>
                </ul>
                <?php else : ?>
                <div class="alert-bar alert-bar--white is-active"><p class="alert-bar__text"><?php echo __( 'There are currently no posts available', 'fitbody-theme' ); ?></p></div>
                <?php endif; ?>

                <div class="blog-preview__options">
                    <a href="<?php echo $events_page_url; ?>" class="more-button"><span class="more-button__text"><?php echo __( 'View all events', 'fitbody-theme' ); ?><?php icon_svg('arrow-right'); ?></span></a>
                </div>
            </div>

            <div class="blog-preview__content tab-content js-nav-tab-content" data-tabs-id="blog" data-tabs-content-id="1">
                <?php if($advice_items->have_posts()) : ?>
                <ul class="blog-preview__items blog-list">
                    <?php 
                        while($advice_items->have_posts()) {

                            $advice_items->the_post();
                            get_template_part('templates/components/card-blog');

                        }
                        wp_reset_postdata();
                    ?>
                </ul>
                <?php else : ?>
                <div class="alert-bar alert-bar--white is-active"><p class="alert-bar__text"><?php echo __( 'There are currently no posts available', 'fitbody-theme' ); ?></p></div>
                <?php endif; ?>

                <div class="blog-preview__options">
                    <a href="<?php echo $advice_page_url; ?>" class="more-button"><span class="more-button__text"><?php echo __( 'Get more advice', 'fitbody-theme' ); ?><?php icon_svg('arrow-right'); ?></span></a>
                </div>
            </div>
        </div>

    </div>
</section>