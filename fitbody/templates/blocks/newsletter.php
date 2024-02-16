<?php
    /**
     * NEWSLETTER MAILING LIST SUBSCRIPTION BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_newsletter');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }
?>


<section class="newsletter<?php echo $block_classes; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>

    <div class="container">
        <div class="newsletter__inner">

            <div class="newsletter__heading section-heading">
                <h2 class="section-heading__title title h2"><?php echo $block_data['title']; ?></h2>
            </div>
            

            <?php if(!empty($block_data['text'])) : ?>
            <p class="newsletter__text"><?php echo $block_data['text']; ?></p>
            <?php endif; ?>

            <?php if(!empty($block_data['form_shortcode'])) : ?>
            <div class="newsletter__form">
                                
                <?php echo do_shortcode($block_data['form_shortcode']); ?>
                
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>