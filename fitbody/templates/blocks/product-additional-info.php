<?php
    /**
     * PRODUCT ADDITIONAL INFO BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/


    // Get block data
    $block_data = get_field('block_product_additional_info');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }
?>


<div class="product-extra<?php echo $block_classes; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>
    
    <?php if(!empty($block_data['items'])) : ?>
        <ul class="product-extra__nav nav-tabs">
            <?php foreach($block_data['items'] as $key => $info) : ?>
            <li class="nav-tabs__item"><button class="nav-tabs__anchor h3 js-nav-tab<?php if($key === 0) { echo ' is-active'; } ?>" data-tabs-id="product-extra" data-tabs-content-id="<?php echo $key; ?>"><?php echo $info['title']; ?></button></li>
            <?php endforeach; ?>
        </ul>

        <div class="nav-tabs-content">
            <?php foreach($block_data['items'] as $key => $info) : ?>
            <div class="product-extra__content editor-content tab-content js-nav-tab-content<?php if($key === 0) { echo ' is-active'; } ?>" data-tabs-id="product-extra" data-tabs-content-id="<?php echo $key; ?>">
                <div class="nav-tabs__mobile-title"><?php echo $info['title']; ?></div>
                <?php echo $info['content']; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

