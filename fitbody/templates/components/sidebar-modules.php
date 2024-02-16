<?php
    /**
     * PROGRESS/PROGRAM NAV SIDEBAR
     * @param block_classes string (optional) - pass additional classes for the block
    **/

    $block_classes = '';
    if(!empty($args['classes'])) {
        $block_classes = ' ' . $args['classes'];
    }


    // PROGRAM DATA
    global $post;
    if($post->post_parent) {
        $module_id = get_the_ID();
        $program_id = theme_get_module_program($module_id);
    } else {
        $module_id = false;
        $program_id = get_the_ID();
    }
    $program_type = get_field('type', $program_id);


    // MODULE DATA
    $program_modules = get_pages([
        'post_type'     => 'program',
        'post_status'   => 'publish',
        'number'        => 0,
        'child_of'      => $program_id,
        'sort_column'   => 'menu_order',
        'sort_order'    => 'ASC'
    ]);
    $program_module_groups = [];
    $program_modules_grouped = [];
    if(!empty($program_modules)) {
        foreach($program_modules as $key => $module) {
            $type = get_field('type', $module->ID);
            if($type === 'group') {
                $group_item = [
                    'id'        => $module->ID,
                    'title'      => $module->post_title,
                    'type'      => 'group',
                    'children'  => []
                ];
                array_push($program_module_groups, $group_item);
                unset($program_modules[$key]);
            }
        }

        foreach($program_modules as $key => $module) {
            $module_item = [
                'id'            => $module->ID,
                'title'          => $module->post_title,
                'type'          => 'module',
                'status'        => theme_get_module_status($module->ID),
                'url'           => get_permalink($module->ID),
                'group_id'      => $module->post_parent
            ];
            $program_modules[$key] = $module_item;
        }

        foreach($program_module_groups as $module_group) {
            foreach($program_modules as $module) {
                if($module_group['id'] === $module['group_id']) {
                    array_push($module_group['children'], $module);
                }
            }
            array_push($program_modules_grouped, $module_group);
        }

        if(empty($program_modules_grouped)) {
            // Non-grouped program
            $program_modules_grouped = $program_modules;
        }
    }


    // USER DATA
    $user_programs = theme_get_user_programs();
    if(is_array($user_programs) && array_key_exists($program_id, $user_programs)) {
        $user_program_module_data = $user_programs[$program_id]['modules'];
    }
    

    // PROGRESSION %
    // Count the number of completed modules from user's meta and compare it with the total amount of modules
    $modules_completed = 0;
    if(!empty($user_program_module_data)) {
        $modules_completed = count($user_program_module_data);
    }
    $progress_percent = 0;
    if(!empty($user_program_module_data)) {
        $modules_total = count($program_modules);

        $modules_completed = count($user_program_module_data);
        if($modules_total !== 0 && $modules_completed !== 0) {
            $progress_percent = round((int)$modules_completed / (int)$modules_total * 100);
        }
    }
    $progress_bar_number_classes = '';
    if($progress_percent > 80) {
        $progress_bar_number_classes = ' progress-bar__number--light';
    }

    global $post;
    $is_module = false;
    if($post->post_parent) {
        $is_module = true;
        $program_id = theme_get_module_program($post->ID);
        $program_url = get_permalink($program_id);
    }
?>

<div class="sidebar-modules<?php echo $block_classes; ?>">
    <button class="sidebar-modules__toggle drawer-toggle drawer-toggle--bottom js-modules-drawer-toggle"><?php echo __( 'Program modules', 'fitbody-theme' ); ?><?php icon_svg('caron-down', 'drawer-toggle__icon'); ?></button>

    <div class="sidebar-modules__inner">
        <h3 class="sidebar-modules__title"><?php echo __( 'Progress', 'fitbody-theme' ); ?></h3>
        <div class="sidebar-modules__bar progress-bar">
            <div class="progress-bar__inner" style="width: <?php echo $progress_percent; ?>%;">&nbsp;</div>
            <div class="progress-bar__number<?php echo $progress_bar_number_classes; ?>"><?php echo $progress_percent; ?>%</div>
        </div>

        <h3 class="sidebar-modules__title"><?php echo __( 'Program modules', 'fitbody-theme' ); ?></h3>
        <?php if(!empty($program_modules_grouped)) : ?>
        <div class="sidebar-modules__nav">
            <ul class="module-nav">
                <?php foreach($program_modules_grouped as $key => $module) : ?>

                    <?php if($module['type'] === 'group') : ?>
                        <?php
                            $module_classes = '';
                            if(!empty($module['children']) && in_array_r($module_id, $module['children'])) {
                                $module_classes = ' is-active';
                            }
                        ?>
                        <li class="module-nav__item">
                            <div class="module-nav-item module-nav-item--group">
                                <div class="module-nav-item__inner js-accordion-toggle<?php echo $module_classes; ?>" data-accordion-id="module-nav-group-<?php echo $key; ?>">
                                    <?php icon_svg('caron-down', 'module-nav-item__icon'); ?>
                                    <h4 class="module-nav-item__title"><?php echo $module['title']; ?></h4>
                                </div>

                                <?php if(!empty($module['children'])) : ?>
                                    <ul class="module-nav-item__submenu module-nav accordion-content js-accordion-content<?php echo $module_classes; ?>" data-accordion-id="module-nav-group-<?php echo $key; ?>">
                                        <?php foreach($module['children'] as $child_module) : ?>
                                            <?php
                                                $module_classes = '';
                                                $module_icon = 'in-progress';
                                                $module_alt = __( 'View this module', 'fitbody-theme' );
                                                if($child_module['status'] === 'completed') {
                                                    $module_classes = ' module-nav-item--completed';
                                                    $module_icon = 'completed';
                                                } else if($child_module['status'] === 'locked') {
                                                    $module_classes = ' module-nav-item--locked';
                                                    $module_icon = 'locked';
                                                    $module_alt = __( 'This module is currently locked', 'fitbody-theme' );
                                                }
                                                if($module_id === $child_module['id']) {
                                                    $module_classes .= ' module-nav-item--current';
                                                    $module_alt = __( 'Current module', 'fitbody-theme' );
                                                }
                                            ?>
                                            <li class="module-nav__item module-nav-item<?php echo $module_classes; ?>">
                                                <a href="<?php if($child_module['status'] !== 'locked') { echo $child_module['url']; } ?>" class="module-nav-item__inner" title="<?php echo __( 'View this module', 'fitbody-theme' ); ?>">
                                                    <?php icon_svg($module_icon, 'module-nav-item__icon'); ?>
                                                    <h4 class="module-nav-item__title"><?php echo $child_module['title']; ?></h4>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php else : ?>
                        <?php
                            $module_classes = '';
                            $module_icon = 'in-progress';
                            $module_alt = __( 'View this module', 'fitbody-theme' );
                            if($child_module['status'] === 'completed') {
                                $module_classes = ' module-nav-item--completed';
                                $module_icon = 'completed';
                            } else if($child_module['status'] === 'locked') {
                                $module_classes = ' module-nav-item--locked';
                                $module_icon = 'locked';
                                $module_alt = __( 'This module is currently locked', 'fitbody-theme' );
                            }
                            if($module_id === $child_module['id']) {
                                $module_classes = ' module-nav-item--current';
                                $module_alt = __( 'Current module', 'fitbody-theme' );
                            }
                        ?>
                        <li class="module-nav__item module-nav-item<?php echo $module_classes; ?>">
                            <a href="<?php if($child_module['status'] !== 'locked') { echo $child_module['url']; } ?>" class="module-nav-item__inner" title="<?php echo __( 'View this module', 'fitbody-theme' ); ?>">
                                <?php icon_svg('completed', 'module-nav-item__icon'); ?>
                                <h4 class="module-nav-item__title"><?php echo $child_module['title']; ?></h4>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php else : ?>
        <div class="sidebar-modules__error alert-message alert-message--negative is-active"><?php echo __( 'No modules have been added yet', 'fitbody-theme' ); ?></div>
        <?php endif; ?>

        <?php if($is_module) : ?>
        <div class="sidebar-modules__options">
            <a href="<?php echo $program_url; ?>" class="sidebar-modules__back"><?php icon_svg('back', 'sidebar-modules__back-icon'); ?><?php echo __( 'Return to program page', 'fitbody-theme' ); ?></a>
        </div>
        <?php endif; ?>
    </div>
</div>