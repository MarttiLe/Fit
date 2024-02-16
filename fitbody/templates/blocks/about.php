<?php
    /**
     * ABOUT BLOCK
     * @param block_classes string (optional) - pass additional classes for the section element
    **/

    $block_data = get_field('block_about');

    $block_classes = '';
    if(!empty($args['block_classes'])) {
        $block_classes = ' ' . $args['block_classes'];
    }
?>

<section class="about section<?php echo $block_classes; ?>">
    <?php if(!empty($block_data['nav_anchor'])) : ?>
        <div id="<?php echo $block_data['nav_anchor']; ?>" class="scroll-anchor">&nbsp;</div>
    <?php endif; ?>

    <div class="container">
        <?php if(!empty($block_data['content_rows'])) : ?>
            <?php foreach($block_data['content_rows'] as $row) : ?>
            <div class="about__cols">
                <div class="about__col">
                    <h2 class="about__title title h2"><?php echo $row['title']; ?></h2>
                </div>
                <div class="about__col">
                    <?php if(!empty($row['text'])) : ?>
                    <p class="about__description"><?php echo $row['text']; ?></p>
                    <?php endif; ?>

                    <?php if(!empty($row['icon_list'])) : ?>
                    <ul class="icon-list">
                        <?php foreach($row['icon_list'] as $item) : ?>
                        <li class="icon-list__item">
                            <div class="icon-list__icon-wrap"><?php icon_svg($item['icon'], 'icon-list__icon'); ?></div>
                            <span class="icon-list__text"><?php echo $item['text']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>