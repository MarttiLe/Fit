<?php
    /**
     * PROGRAMS PREVIEW BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_programs_preview');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }


    // Manage block details
    $user_programs = theme_get_user_programs();
    $is_logged_in = is_user_logged_in();
    if($is_logged_in) {
        $user_id = get_current_user_id();
    }
    
    $programs_page_url = get_permalink(get_field('pages_programs', 'options'));

    $all_programs = new WP_Query([
        'posts_per_page'    => -1,
        'post_type'         => 'program',
        'status'            => 'publish',
        'post_parent'       => 0,
        'suppress_filters'  => false
    ]);

    $all_programs = $all_programs->posts;
    $gym = [];
    $home = [];

    foreach($all_programs as $key => $program) {
        $product_id = get_field('linked_product', $program->ID);
        $is_valid = theme_validate_program_product($program->ID, $product_id);

        if($is_valid) {
            $program_type = get_field('category', $program->ID);
            if(!empty($program_type)) {
                switch($program_type) {
                    case 'gym':
                        array_push($gym, $program);
                        break;
                    case 'home':
                        array_push($home, $program);
                        break;
                    default:
                        unset($all_programs[$key]);
                }
            }
        } else {
            unset($all_programs[$key]);
        }
    }

    $all_programs = array_slice($all_programs, 0, $block_data['amount']);
    $gym = array_slice($gym, 0, $block_data['amount']);
    $home = array_slice($home, 0, $block_data['amount']);
?>


<section class="programs section">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>

    <div class="programs__bg dual-bg">&nbsp;</div>

    <div class="container">
        <div class="section-heading section-heading--has-options">
            <h2 class="section-heading__title title h2"><?php echo $block_data['title']; ?></h2>

            <div class="section-heading__options">
                <ul class="programs__tabs nav-tabs">
                    <li class="nav-tabs__item"><button class="nav-tabs__anchor js-nav-tab is-active" data-tabs-id="programs" data-tabs-content-id="all"><?php echo __( 'All', 'fitbody-theme' ); ?></button></li>
                    <li class="nav-tabs__item"><button class="nav-tabs__anchor js-nav-tab" data-tabs-id="programs" data-tabs-content-id="gym"><?php echo __( 'At the gym', 'fitbody-theme' ); ?></button></li>
                    <li class="nav-tabs__item"><button class="nav-tabs__anchor js-nav-tab" data-tabs-id="programs" data-tabs-content-id="home"><?php echo __( 'At home', 'fitbody-theme' ); ?></button></li>
                </ul>
            </div>
        </div>

        <div class="nav-tabs-content">
            <div class="programs__content tab-content js-nav-tab-content is-active" data-tabs-id="programs" data-tabs-content-id="all">
                <?php if(!empty($all_programs)) : ?>
                <ul class="programs__items programs-list">
                    <?php foreach($all_programs as $program) : ?>
                        <li class="programs-list__item">
                            <?php
                                $card_attr = [
                                    'post_id'   => $program->ID,
                                ];

                                if($is_logged_in) {
                                    $program_is_owned = theme_get_program_ownership_status($program->ID, false, $user_programs);
                                    $card_attr['is_owned'] = $program_is_owned;
                                    if($program_is_owned) {
                                        $card_attr['ownership_dates'] = theme_get_program_ownership_dates($program->ID, false, $user_programs);
                                    }
                                }

                                get_template_part('templates/components/card-program', null, $card_attr);
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else : ?>
                <div class="programs__alert alert-bar alert-bar--white alert-bar--lg is-active"><p class="alert-bar__text"><?php echo __( 'There appear to be no active programs right now, sorry!', 'fitbody-theme' ); ?></p></div>
                <?php endif; ?>
            </div>

            <div class="programs__content tab-content js-nav-tab-content" data-tabs-id="programs" data-tabs-content-id="gym">
                <?php if(!empty($gym)) : ?>
                <ul class="programs__items programs-list">
                    <?php foreach($gym as $program) : ?>
                        <li class="programs-list__item">
                            <?php
                                $card_attr = [
                                    'post_id'   => $program->ID,
                                ];

                                if($is_logged_in) {
                                    $program_is_owned = theme_get_program_ownership_status($program->ID, false, $user_programs);
                                    $card_attr['is_owned'] = $program_is_owned;
                                    if($program_is_owned) {
                                        $card_attr['ownership_dates'] = theme_get_program_ownership_dates($program->ID, false, $user_programs);
                                    }
                                }

                                get_template_part('templates/components/card-program', null, $card_attr);
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else : ?>
                <div class="programs__alert alert-bar alert-bar--white alert-bar--lg is-active"><p class="alert-bar__text"><?php echo __( 'There appear to be no active challenges right now, sorry!', 'fitbody-theme' ); ?></p></div>
                <?php endif; ?>
            </div>

            <div class="programs__content tab-content js-nav-tab-content" data-tabs-id="programs" data-tabs-content-id="home">
                <?php if(!empty($home)) : ?>
                <ul class="programs__items programs-list">
                    <?php foreach($home as $program) : ?>
                        <li class="programs-list__item">
                            <?php
                                $card_attr = [
                                    'post_id'   => $program->ID,
                                ];

                                if($is_logged_in) {
                                    $program_is_owned = theme_get_program_ownership_status($program->ID, false, $user_programs);
                                    $card_attr['is_owned'] = $program_is_owned;
                                    if($program_is_owned) {
                                        $card_attr['ownership_dates'] = theme_get_program_ownership_dates($program->ID, false, $user_programs);
                                    }
                                }
                                
                                get_template_part('templates/components/card-program', null, $card_attr);
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php else : ?>
                <div class="programs__alert alert-bar alert-bar--white alert-bar--lg is-active"><p class="alert-bar__text"><?php echo __( 'There appear to be no active packages right now, sorry!', 'fitbody-theme' ); ?></p></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="programs__options">
            <a href="<?php echo $programs_page_url; ?>" class="more-button"><span class="more-button__text"><?php echo __( 'View all programs', 'fitbody-theme' ); ?><?php icon_svg('arrow-right'); ?></span></a>
        </div>

        <?php wp_reset_postdata(); ?>
    </div>
</section>