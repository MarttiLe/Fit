<?php get_header(); ?>

    <?php
        global $post;
        $post_type = get_post_type();
        if(!empty($post_type)) {
            switch($post_type) {
                case 'advice':
                    $post_taxonomy = 'advice_cat';
                    break;
                case 'event':
                    $post_taxonomy = 'event_cat';
                    break;
                default:
                    $post_taxonomy = 'category';
            }
            $terms = get_the_terms($post, $post_taxonomy);
        }

        $featured_image = get_post_thumbnail_id();
        if(!empty($featured_image)) {
            $featured_image = theme_get_wp_image($featured_image, 'blog-banner', 'page-content__featured-img', __( 'Blog post banner', 'fitbody-theme' ));
        }

        $lead = get_field('lead');
    ?>

    <div class="page-heading page-heading--centered">
        <div class="container">
            <div class="page-heading__inner">
                <div class="page-heading__breadcrumb">
                    <?php theme_breadcrumbs_list(); ?>
                </div>

                <h1 class="page-heading__title h2"><?php the_title(); ?></h1>

                <?php if(!empty($terms)) : ?>
                <ul class="page-heading__tags label-list">
                    <?php foreach($terms as $term) : ?>
                    <li class="label-list__item"><div class="label"><span class="label__text"><?php echo $term->name; ?></span></div></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container container--md">
            <div class="page-content__inner">
                <?php if(!empty($featured_image) || !empty($lead)) : ?>
                <div class="page-content__heading">
                    <?php if(!empty($featured_image)) : ?>
                        <div class="page-content__banner">
                            <?php echo $featured_image; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($lead)) : ?>
                        <div class="page-content__lead"><?php echo $lead; ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="editor-content">
                    <?php the_content(); ?>
                </div>
            </div>

            <div class="page-content__socials social-share">
                <div class="social-share__title"><?php echo __( 'Share this post', 'fitbody-theme' ); ?>:</div>
                <ul class="social-share__items">
                    <li class="social-share__item"><a href="<?php echo theme_get_social_share_url('facebook'); ?>" target="_blank" rel="noopener" class="social-share__anchor" title="<?php echo __( 'Share this story on Facebook', 'fitbody-theme' ); ?>"><?php icon_svg('socials-fb', 'social-share__icon'); ?></a></li>
                    <li class="social-share__item"><a href="<?php echo theme_get_social_share_url('twitter'); ?>" target="_blank" rel="noopener" class="social-share__anchor" title="<?php echo __( 'Share this story on Twitter', 'fitbody-theme' ); ?>"><?php icon_svg('socials-tw', 'social-share__icon'); ?></a></li>
                    <!--<li class="social-share__item"><a href="<?php echo theme_get_social_share_url('linkedin'); ?>" target="_blank" rel="noopener" class="social-share__anchor" title="<?php echo __( 'Share this story on LinkedIn', 'fitbody-theme' ); ?>"><?php icon_svg('socials-in', 'social-share__icon'); ?></a></li>-->
                </ul>
            </div>
        </div>
    </div>

<?php get_footer(); ?>