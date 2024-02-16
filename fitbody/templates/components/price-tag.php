<?php
    /**
     * PRICE TAG
     * @param block_classes string (optional) - pass additional classes for the block
     * @param title string (optional) - pass to show a title 
     * @param prefix string (optional) - pass to show a prefix in front of the price number
     * @param hide_old_price bool (optional) - pass to hide the old price in the event that the product is discounted
     * 
    **/

    $block_classes = '';
    if(!empty($args['classes'])) {
        $block_classes = ' ' . $args['classes'];
    }

    $price_data = '';
    if(!empty($args['price_data'])) {
        $price_data = $args['price_data'];
    } else {
        if(!empty($args['product_id'])) {
            $price_data = theme_get_product_price_data($args['product_id'], true);
        } else {
            global $post;
            $price_data = theme_get_product_price_data($post->ID, true);
        }
    }

    $title = false;
    if(!empty($args['title'])) {
        $title = $args['title'];
    }

    $prefix = false;
    if(!empty($args['prefix'])) {
        $prefix = $args['prefix'];
    }

    $hide_old_price = false;
    if(!empty($args['hide_old_price'])) {
        $hide_old_price = true;
    }

    $hide_until_message = false;
    if(!empty($args['hide_until_message'])) {
        $hide_until_message = true;
    }
?>

<div class="price<?php echo $block_classes; ?>">
    <?php if(!empty($title)) : ?>
        <div class="price__title"><?php echo $title; ?>:</div>
    <?php endif; ?>

    <div class="price__main">
        <?php if(!empty($prefix)) : ?>
            <span class="price__prefix"></span>
        <?php endif; ?>
        <?php if(!empty($price_data['sale_price'])) : ?>
            <?php if(!$hide_old_price) : ?>
                <del class="price__old"><?php echo $price_data['regular_price']; ?></del>
            <?php endif; ?>
            <span class="price__new"><?php if(!empty($prefix)) { echo '<span class="price__prefix">'. $prefix .'</span> '; } ?><?php echo $price_data['final_price']; ?></span>
        <?php else : ?>
            <span class="price__standard"><?php if(!empty($prefix)) { echo '<span class="price__prefix">'. $prefix .'</span> '; } ?><?php echo $price_data['final_price']; ?></span>
        <?php endif; ?>
    </div>

    <?php if(!$hide_until_message && !empty($price_data['sale_until'])) : ?>
    <div class="price__date"><?php echo __( 'Join before', 'fitbody-theme' ); ?> <date><?php echo $price_data['sale_until']; ?></date></div>
    <?php endif; ?>
</div>