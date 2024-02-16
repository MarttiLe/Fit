<?php
    // GENERAL
    $program_id = get_the_ID();
    $is_logged_in = is_user_logged_in();
    $is_owned = theme_get_program_ownership_status($program_id);
    $user_programs = theme_get_user_programs();
    $current_date = time();

    $program_data = [];
    $program_data['type'] = get_field('type');
    $program_data['start_date'] = '';
    if($program_data['type'] === 'challenge') {
        $program_data['start_date'] = get_field('start_date');
    }

    $linked_product = get_field('linked_product');
    $is_purchasable = false;
    if(!empty($linked_product)) {
        $product_id = $linked_product;
        $product_is_valid = theme_validate_program_product($program_id, $product_id);
        if($product_is_valid) {
            // TODO: Run additional check about product availiability?
            $is_purchasable = true;
            $price = theme_get_product_price_data($product_id, true);
        }
    }

    // HEADING
    $heading_data = get_field('heading');
    $heading_style = '';
    $heading_image = wp_get_attachment_image_url($heading_data['heading_image'], 'page-banner');
    $heading_style = ' style="background-image: url('. $heading_image .')"';
    $heading_video = $heading_data['heading_video'];

    // OVERVIEW SIDEBAR
    $overview_data = get_field('overview');
    switch($overview_data['difficulty']) {
        case 'beginner':
            $overview_data['difficulty'] = __( 'Beginner', 'fitbody-theme' );
            break;
        case 'intermediate':
            $overview_data['difficulty'] = __( 'Intermediate', 'fitbody-theme' );
            break;
        case 'advanced':
            $overview_data['difficulty'] = __( 'Advanced', 'fitbody-theme' );
            break;
        default:
            $overview_data['difficulty'] = __( 'Anyone', 'fitbody-theme' );
            break;
    }

    // CONTENT
    $content_data = get_field('content');
    if(empty($content_data['introduction_content']['title'])) {
        $content_data['introduction_content']['title'] = __( 'Program introduction', 'fitbody-theme' );
    }
    if(empty($content_data['main_content']['title'])) {
        $content_data['main_content']['title'] = __( 'Program details', 'fitbody-theme' );
    }
    if(empty($content_data['introduction_content']['title_owned'])) {
        $content_data['introduction_content']['title_owned'] = __( 'Welcome!', 'fitbody-theme' );
    }
    if(empty($content_data['main_content']['title_owned'])) {
        $content_data['main_content']['title_owned'] = __( 'Program details', 'fitbody-theme' );
    }
    if(empty($content_data['gear_content']['title'])) {
        $content_data['gear_content']['title'] = __( "What you'll need", 'fitbody-theme' );
    }
    if(empty($content_data['additional_materials']['title'])) {
        $content_data['additional_materials']['title'] = __( "Bonus materials", 'fitbody-theme' );
    }


    // TRAINER
    $trainer_data = get_field('trainers');
    $trainers = [];
    if(!empty($trainer_data)) {
        foreach($trainer_data as $trainer) {
            $trainer_data = [];
            $trainer_data['name'] = get_the_title($trainer);
            $trainer_data['photo'] = theme_get_wp_image(get_field('photo', $trainer), 'profile-thumb', 'trainer-card__img circular-image circular-image--lg', __( 'Profile picture for', 'fitbody-theme' ) . ' ' . $trainer_data['name']);
            if(empty($trainer_data['photo']) || is_wp_error($trainer_data['photo'])) {
                $trainer_data['photo'] = theme_get_wp_image(get_field('placeholder_img_trainer', 'options'), 'profile-thumb', 'trainer-card__img circular-image', __('Profile picture for', 'fitbody-theme') . ' ' . $trainer_data['name']);
            }
            $trainer_data['description'] = get_field('short_description', $trainer);
            array_push($trainers, $trainer_data);
        }
    }
?>


<div class="program">
    <?php if(!empty($program_data['start_date']) && $current_date < strtotime($program_data['start_date'])) : ?>
    <div class="notification-bar">
        <div class="container">
            <p class="notification-bar__text"><strong><?php echo __( 'This challenge will begin on', 'fitbody-theme' ) . ' ' . $program_data['start_date']; ?>.</strong> <?php echo __( 'You will not be able to access any modules until then.', 'fitbody-theme' ); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <div class="page-heading page-heading--program"<?php echo $heading_style; ?>>
        <?php if(!empty($heading_video)) : ?>
        <video autoplay muted loop class="page-heading__video">
            <source src="<?php echo $heading_video; ?>" type="video/mp4">
        </video>
        <?php endif; ?>

        <div class="container">
            <div class="page-heading__inner">

                <div class="page-heading__main">
                    <div class="page-heading__breadcrumb">
                        <?php theme_breadcrumbs_list(); ?>
                    </div>

                    <h1 class="page-heading__title h2"><?php the_title(); ?></h1>
                </div>

                <div class="page-heading__aside">
                    <?php if(!empty($heading_data['intro_video'])) : ?>
                    <button class="play-button js-modal-toggle" data-modal-id="program-intro-video" title="<?php echo __( 'Play video', 'fitbody-theme' ); ?>">
                        <div class="play-button__tooltip">
                            <div class="tooltip"><?php echo __( 'Check video introduction', 'fitbody-theme' ); ?></div>
                        </div>
                        <?php icon_svg('play', 'play-button__icon'); ?>
                    </button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <?php if(!empty($heading_data['intro_video'])) : ?>
    <div id="program-intro-video" class="modal js-modal">
        <div class="modal__inner video-modal">
            <div class="video-modal__content video-player">
                <iframe class="video-player__iframe" width="100%" height="100%" src="<?php echo $heading_data['intro_video']; ?>" title="<?php echo __( 'Introductory video for the program', 'fitbody-theme' ); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>

        <button class="modal__close js-modal-close" title="<?php echo __( 'Close modal', 'fitbody-theme' ); ?>"><?php icon_svg('close', 'modal__icon'); ?></button>
    </div>
    <?php endif; ?>

    <div class="page-content page-content--with-sidebar">
        <div class="container">
            <div class="page-content__inner">

                <div class="page-content__main">
                    <div class="page-content__block">
                        <?php if($is_owned) : ?>
                            <?php if(!empty($content_data['introduction_content']['text_owned'])) :  ?>
                            <div class="program__section editor-content">
                                <h2 class="title h2"><?php echo $content_data['introduction_content']['title_owned']; ?></h2>
                                <?php echo $content_data['introduction_content']['text_owned']; ?>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty($content_data['main_content']['text_owned'])) : ?>
                            <div class="program__section editor-content">
                                <h2 class="title h2"><?php echo $content_data['main_content']['title_owned']; ?></h2>
                                <?php echo $content_data['main_content']['text_owned']; ?>
                            </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php if(!empty($content_data['introduction_content']['text'])) : ?>
                            <div class="program__section editor-content">
                                <h2 class="title h2"><?php echo $content_data['introduction_content']['title']; ?></h2>
                                <?php echo $content_data['introduction_content']['text']; ?>
                            </div>
                            <?php endif; ?>

                            <?php if(!empty($content_data['main_content']['text'])) : ?>
                            <div class="program__section editor-content">
                                <h2 class="title h2"><?php echo $content_data['main_content']['title']; ?></h2>
                                <?php echo $content_data['main_content']['text']; ?>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if(!empty($content_data['gear_content']['text'])) : ?>
                            <div class="program__section editor-content">
                                <h2 class="title h2"><?php echo $content_data['gear_content']['title']; ?></h2>
                                <?php echo $content_data['gear_content']['text']; ?>
                            </div>

                            <?php if(!empty($content_data['gear_content']['products'])) : ?>
                            <?php
                                $block_attr = [
                                    'item_ids'      => $content_data['gear_content']['products'],
                                    'block_title'   => __( 'We recommend', 'fitbody-theme' ),
                                    'block_classes' => 'product-list--mini program__section',
                                    'item_classes'  => 'product-card--sm',
                                ];
                                get_template_part('templates/blocks/related-products', null, $block_attr);
                            ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($is_owned && !empty($content_data['main_content']['text'])) : ?>
                        <div class="program__section editor-content">
                            <div class="content-readmore js-content-readmore">
                                <h2 class="title h2"><?php echo $content_data['main_content']['title']; ?></h2>
                                <?php echo $content_data['main_content']['text']; ?>

                                <div class="content-readmore__options">
                                    <button class="button js-content-readmore-toggle"><?php echo __( 'Read more', 'fitbody-theme' ); ?></button>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if(!$is_owned && $is_purchasable) : ?>
                    <div class="page-content__block page-content__block--sm">
                        <div class="program__cta">
                            <?php
                                $block_attr = [
                                    'classes' => 'program__price price--program',
                                    'price_data'    => $price,
                                    'prefix'         => __( 'Price:', 'fitbody-theme' )
                                ];
                                get_template_part('templates/components/price-tag', null, $block_attr);
                            ?>
 
                            <div class="program__join">
                                <a href="<?php echo wc_get_cart_url() . '?add-to-cart=' . $linked_product; ?>" class="sidebar-price__button button button--skewed button--size-lg button--thick-text"><span class="button__text"><?php echo __( 'Join program', 'fitbody-theme' ); ?></span></a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($trainers)) : ?>
                        <?php foreach($trainers as $trainer) : ?>
                            <div class="page-content__block">
                                <div class="trainer-card">
                                    <div class="trainer-card__header">
                                        <div class="trainer-card__photo">
                                            <?php echo $trainer['photo']; ?>
                                        </div>

                                        <div class="trainer-card__info">
                                            <div class="trainer-card__position"><?php echo __( 'Trainer of this program', 'fitbody-theme' ); ?></div>
                                            <h4 class="trainer-card__name"><?php echo $trainer['name']; ?></h4>
                                        </div>
                                    </div>

                                    <div class="trainer-card__content">
                                        <?php echo $trainer['description']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="page-content__aside">

                    <?php if(!$is_owned && $is_purchasable) : ?>
                    <div class="sidebar-price">
                        <div class="sidebar-price__inner">
                            <?php
                                $block_attr = [
                                    'classes'           => 'sidebar-price__price price--program',
                                    'price_data'        => $price,
                                    'hide_old_price'    => true,
                                    'title'             => __( 'Price', 'fitbody-theme' )
                                ];
                                get_template_part('templates/components/price-tag', null, $block_attr);
                            ?>

                            <div class="sidebar-price__join">
                                <a href="<?php echo wc_get_cart_url() . '?add-to-cart=' . $linked_product; ?>" class="sidebar-price__button button button--skewed button--white button--size-lg button--thick-text"><span class="button__text"><?php echo __( 'Join program', 'fitbody-theme' ); ?></span></a>
                            </div>
                        </div>
                    </div>
                    <?php elseif($is_owned && !$is_purchasable) : ?>
                    <div class="sidebar-price">
                        <div class="sidebar-price__inner">
                            <div class="sidebar-price__error">
                                <p class="sidebar-price__error-main"><?php echo __( 'Joining is closed', 'fitbody' ); ?></p>
                                <p class="sidebar-price__error-sub"><?php echo __( 'This program is currently not available', 'fitbody' ); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($is_owned) : ?>
                    <?php
                        $block_attr = [];
                        get_template_part('templates/components/sidebar-modules', null, $block_attr);
                    ?>
                    <?php endif; ?>

                    <div class="sidebar-overview">
                        <h3 class="sidebar-overview__title"><?php echo __( 'Overview', 'fitbody-theme' ); ?></h3>
                        <ul class="sidebar-overview__list attribute-list">
                            <?php if(!empty($program_data['start_date'])) : ?>
                            <li class="attribute-list__item">
                                <div class="attribute-list__item-inner">
                                    <span class="attribute-list__item-title"><?php echo __( 'Start date', 'fitbody-theme' ); ?>:</span>
                                    <span class="attribute-list__item-value"><?php echo $program_data['start_date']; ?></span>
                                </div>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($overview_data['difficulty'])) : ?>
                            <li class="attribute-list__item">
                                <div class="attribute-list__item-inner">
                                    <span class="attribute-list__item-title"><?php echo __( 'Difficulty level', 'fitbody-theme' ); ?>:</span>
                                    <span class="attribute-list__item-value"><?php echo $overview_data['difficulty']; ?></span>
                                </div>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($overview_data['length'])) : ?>
                            <li class="attribute-list__item">
                                <div class="attribute-list__item-inner">
                                    <span class="attribute-list__item-title"><?php echo __( 'Duration', 'fitbody-theme' ); ?>:</span>
                                    <span class="attribute-list__item-value"><?php echo $overview_data['length']; ?></span>
                                </div>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($overview_data['workout_frequency'])) : ?>
                            <li class="attribute-list__item">
                                <div class="attribute-list__item-inner">
                                    <span class="attribute-list__item-title"><?php echo __( 'Workouts per week', 'fitbody-theme' ); ?>:</span>
                                    <span class="attribute-list__item-value"><?php echo $overview_data['workout_frequency']; ?></span>
                                </div>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($overview_data['required_gear']) && $overview_data['required_gear'] !== []) : ?>
                            <li class="attribute-list__item attribute-list__item--gear">
                                <div class="attribute-list__item-inner">
                                    <span class="attribute-list__item-title"><?php echo __( 'Gear needed', 'fitbody-theme' ); ?>:</span>
                                    <div class="attribute-list__item-value">
                                        <ul class="inline-list">
                                            <?php foreach($overview_data['required_gear'] as $gear) : ?>
                                                <li class="inline-list__item" title="<?php echo $gear['label']; ?>"><?php icon_svg($gear['value']); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($overview_data['language'])) : ?>
                            <li class="attribute-list__item">
                                <div class="attribute-list__item-inner">
                                    <span class="attribute-list__item-title"><?php echo __( 'Language', 'fitbody-theme' ); ?>:</span>
                                    <span class="attribute-list__item-value"><?php echo $overview_data['language']; ?></span>
                                </div>
                            </li>
                            <?php endif; ?>

                            <?php if(!empty($overview_data['group'])) : ?>
                            <li class="attribute-list__item">
                                <div class="attribute-list__item-inner">
                                    <span class="attribute-list__item-title"><?php echo __( 'Community', 'fitbody-theme' ); ?>:</span>
                                    <span class="attribute-list__item-value">
                                        <?php 
                                            if($is_owned) {
                                                echo '<a href="'. $overview_data['group'] .'" target="_blank">'. __( 'Visit', 'fitbody-theme' ) .' &raquo;</a>';
                                            } else {
                                                echo __( 'Yes', 'fitbody-theme' );
                                            }
                                        ?>
                                    </span>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if(!empty($content_data['additional_materials']['items']) && $is_owned) : ?>
                    <div class="sidebar-materials">
                        <h3 class="sidebar-materials__title"><?php echo $content_data['additional_materials']['title']; ?></h3>
                        <div class="sidebar-materials__text editor-content">
                            <?php echo $content_data['additional_materials']['text']; ?>
                        </div>

                        <ul class="sidebar-materials__items attribute-list">
                            <?php foreach($content_data['additional_materials']['items'] as $material) : ?>
                                <li class="attribute-list__item">
                                    <div class="attribute-list__item-inner">
                                        <span class="attribute-list__item-title"><?php echo $material['name']; ?></span>
                                        <span class="attribute-list__item-value">
                                            <a href="<?php echo $material['file']['url']; ?>" target="_blank"><?php echo $material['file']['title']; ?> &raquo;</a>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        
                    </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
</div>
