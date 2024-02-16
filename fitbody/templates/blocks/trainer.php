<?php
    /**
     * TRAINER BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/

    // Get block data
    $block_data = get_field('block_trainer');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }
?>


<section class="trainer section">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>
    
    <div class="trainer__bg dual-bg dual-bg--reverse">&nbsp;</div>

    <div class="container">
        <h2 class="trainer__title title h2"><?php echo $block_data['title']; ?></h2>

        <div class="trainer__avatar"><img src="<?php echo get_template_directory_uri() . '/assets/images/egle-trainer-avatar.jpg'; ?>" alt="<?php echo __( 'Photo of the founder - Egle Nabi', 'fitbody-theme' ); ?>"></div>
        
        <div class="trainer__content editor-content">
            <?php echo $block_data['description']; ?>
        </div>

        <?php if(!empty($block_data['button']['url'])) : ?>
            <?php 
                if(empty($block_data['button']['title'])) {
                    $block_data['button']['title'] = __( 'Read more', 'fitbody-theme' );
                }
            ?>
            <div class="trainer__options">
                <a href="<?php echo $block_data['button']['url']; ?>" target="<?php echo $block_data['button']['target']; ?>" class="more-button"><span class="more-button__text"><?php echo $block_data['button']['title']; ?><?php icon_svg('arrow-right'); ?></span></a>
            </div>
        <?php endif; ?>
    </div>
</section>