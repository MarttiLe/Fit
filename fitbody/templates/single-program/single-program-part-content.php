<?php 
    // GENERAL
    $module_id = get_the_ID();
    $program_id = theme_get_module_program($module_id);
    $program_type = get_field('type', $program_id);
    $program_url = get_permalink($program_id);
    $user_programs = theme_get_user_programs();
    $module_status = theme_get_module_status($module_id);

    // HEADING
    $heading_data = get_field('heading', $program_id);
    $heading_style = '';
    $heading_image = wp_get_attachment_image_url($heading_data['heading_image'], 'page-banner');
    $heading_style = ' style="background-image: url('. $heading_image .')"';

    // CONTENT
    $content_home = get_field('content_home');
    $content_gym = get_field('content_gym');

    $has_home_content = false;
    $has_gym_content = false;
    if(!empty($content_home['title']) || !empty($content_home['content']) || !empty($content_home['blocks'])) {
        $has_home_content = true;
    }
    if(!empty($content_gym['title']) || !empty($content_gym['content']) || !empty($content_gym['blocks'])) {
        $has_gym_content = true;
    }

    //var_dump($user_programs);
    //theme_cron_remove_expired_programs();
?>


<div class="program">

    <div class="page-heading page-heading--program"<?php echo $heading_style; ?>>
        <div class="container">
            <div class="page-heading__inner">

                <div class="page-heading__main">
                    <div class="page-heading__breadcrumb">
                        <?php theme_breadcrumbs_list(); ?>
                    </div>

                    <h1 class="page-heading__title h2"><?php the_title(); ?></h1>
                </div>

            </div>
        </div>
    </div>

    <div class="page-content page-content--with-sidebar">
        <div class="container">
            <div class="page-content__inner">

                <div class="page-content__main">
                    <div class="page-content__block">

                        <?php // Render tabs if gym has any content, if not only show home ?>
                        <?php if($has_home_content && $has_gym_content) : ?>
                        <div class="program__content-tabs">
                            <ul class="nav-tabs">
                                <li class="nav-tabs__item"><button class="nav-tabs__anchor js-nav-tab is-active" data-tabs-id="module-content" data-tabs-content-id="home"><?php echo __( 'At home', 'fitbody-theme' ); ?></button></li>
                                <li class="nav-tabs__item"><button class="nav-tabs__anchor js-nav-tab" data-tabs-id="module-content" data-tabs-content-id="gym"><?php echo __( 'At the gym', 'fitbody-theme' ); ?></button></li>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <div class="nav-tabs-content">
                            <?php if($has_home_content) : ?>
                                <?php 
                                    $is_active = '';
                                    if($has_home_content && $has_gym_content || $has_home_content && !$has_gym_content) {
                                        $is_active = ' is-active';
                                    }
                                ?>
                                <div class="tab-content js-nav-tab-content<?php echo $is_active; ?>" data-tabs-id="module-content" data-tabs-content-id="home">
                                    <?php if(!empty($content_home['title']) || !empty($content_home['content'])) : ?>
                                    <div class="program__section editor-content">
                                        <?php if(!empty($content_home['title'])) : ?>
                                        <h2 class="title h2"><?php echo $content_home['title']; ?></h2>
                                        <?php endif; ?>

                                        <?php echo $content_home['content']; ?>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(!empty($content_home['blocks'])) : ?>
                                        <?php foreach($content_home['blocks'] as $video_block) : ?>
                                            <div class="program__section editor-content">
                                                <?php if(!empty($video_block['subtitle'])) : ?>
                                                    <h3 class="h4"><?php echo $video_block['subtitle']; ?></h3>
                                                <?php endif; ?>

                                                <?php if(!empty($video_block['content'])) : ?>
                                                    <?php echo $video_block['content']; ?>
                                                <?php endif; ?>

                                                <?php if($video_block['video_type'] === 'embed') : ?>
                                                    <?php if(!empty($video_block['video_embed'])) : ?>
                                                        <div class="wp-block-embed is-type-video">
                                                            <div class="wp-block-embed__wrapper">
                                                                <?php echo $video_block['video_embed']; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php elseif($video_block['video_type'] === 'upload') : ?>
                                                    <?php if(!empty($video_block['video_upload'])) : ?>
                                                        <video controls controlslist="nodownload" class="video-player">
                                                            <source src="<?php echo $video_block['video_upload']['url']; ?>#t=0.001" type="<?php echo $video_block['video_upload']['mime_type']; ?>" title="<?php echo $video_block['video_upload']['title']; ?>">
                                                        </video>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            
                                                <?php if(!empty($video_block['secondary_content'])) : ?>
                                                    <?php echo $video_block['secondary_content']; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php if(!empty($content_home['secondary_content'])) : ?>
                                    <div class="program__section editor-content">
                                        <?php echo $content_home['secondary_content']; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if($has_gym_content) : ?>
                                <?php 
                                    $is_active = '';
                                    if(!$has_home_content && $has_gym_content) {
                                        $is_active = ' is-active';
                                    }
                                ?>
                                <div class="tab-content js-nav-tab-content<?php echo $is_active; ?>" data-tabs-id="module-content" data-tabs-content-id="gym">
                                    <?php if(!empty($content_gym['title']) || !empty($content_gym['content'])) : ?>
                                    <div class="program__section editor-content">
                                        <?php if(!empty($content_gym['title'])) : ?>
                                        <h2 class="title h2"><?php echo $content_gym['title']; ?></h2>
                                        <?php endif; ?>
                                        
                                        <?php echo $content_gym['content']; ?>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(!empty($content_gym['blocks'])) : ?>
                                        <?php foreach($content_gym['blocks'] as $video_block) : ?>
                                            <div class="program__section editor-content">
                                                <?php if(!empty($video_block['subtitle'])) : ?>
                                                    <h3 class="h4"><?php echo $video_block['subtitle']; ?></h3>
                                                <?php endif; ?>

                                                <?php if(!empty($video_block['content'])) : ?>
                                                    <?php echo $video_block['content']; ?>
                                                <?php endif; ?>

                                                <?php if($video_block['video_type'] === 'embed') : ?>
                                                    <?php if(!empty($video_block['video_embed'])) : ?>
                                                        <div class="wp-block-embed is-type-video">
                                                            <div class="wp-block-embed__wrapper">
                                                                <?php echo $video_block['video_embed']; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php elseif($video_block['video_type'] === 'upload') : ?>
                                                    <?php if(!empty($video_block['video_upload'])) : ?>
                                                        <video controls controlslist="nodownload" class="video-player">
                                                            <source src="<?php echo $video_block['video_upload']['url']; ?>#t=0.001" type="<?php echo $video_block['video_upload']['mime_type']; ?>" title="<?php echo $video_block['video_upload']['title']; ?>">
                                                        </video>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            
                                                <?php if(!empty($video_block['secondary_content'])) : ?>
                                                    <?php echo $video_block['secondary_content']; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php if(!empty($content_gym['secondary_content'])) : ?>
                                    <div class="program__section editor-content">
                                        <?php echo $content_gym['secondary_content']; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if($module_status !== 'completed') : ?>
                        <div class="program__options button-list button-list--center">
                            <div class="button-list__item">
                                <button class="button button--size-lg button--icon-left button--positive button--complete js-complete-module-button" data-module-id="<?php echo $module_id; ?>"><?php echo __( 'Complete module', 'fitbody-theme' ); ?><?php icon_svg('completed', 'button__icon'); ?><div class="button__loader"></div></button>
                                <p class="program__completion-message alert-message alert-message--positive alert-message--animated text-with-icon js-module-completed-message"><?php icon_svg('completed', 'text-with-icon__icon'); ?><?php echo __( 'Awesome work! This module is now complete!', 'fitbody-theme' ); ?></p>
                            </div>
                        </div>
                        <?php else : ?>
                        <div class="program__options">
                            <p class="program__completion-message alert-message alert-message--positive text-with-icon is-active"><?php icon_svg('completed', 'text-with-icon__icon'); ?><?php echo __( 'Good job, you have already completed this module!', 'fitbody-theme' ); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!--<div class="page-content__block page-content__block--md">
                        <div class="program__options button-list button-list--space-between">
                            <div class="button-list__item">
                                <a href="#" class="button button--size-lg button--icon-left button--bordered">Previous module<?php icon_svg('arrow-left', 'button__icon'); ?></a>
                            </div>
                            <div class="button-list__item">
                                <a href="#" class="button button--size-lg button--icon-right button--bordered">Next module<?php icon_svg('arrow-right', 'button__icon'); ?></a>
                            </div>
                        </div>
                    </div>-->
                </div>

                <div class="page-content__aside">
                    <?php
                        $block_attr = [];
                        get_template_part('templates/components/sidebar-modules', null, $block_attr);
                    ?>
                </div>

            </div>
        </div>
    </div>

</div>