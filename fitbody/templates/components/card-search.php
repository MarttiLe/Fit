<?php

    // Default output data
    $data = [
        'result'                  => '',
        'result_type'             => '',
        'title'                 => '',
        'excerpt'               => '',
        'url'                   => ''
    ];


    // Process block data
    if (empty($attributes['result'])) {
        global $result;
        $data['title'] = get_the_title($result->ID);
        $data['excerpt'] = theme_get_excerpt($result->ID, 150);
        $data['url'] = get_permalink($result->ID);
        $data['post_type'] = get_post_type($result->ID);

    } else {
        $data['post'] = $attributes['result'];
        $data['title'] = get_the_title($data['result']->ID);
        $data['excerpt'] = theme_get_excerpt($data['result']->ID, 150);
        $data['url'] = get_permalink($data['result']->ID);
        $data['post_type'] = get_post_type($data['result']->ID);
    }
    switch($data['post_type']) {
    case 'post' :
        $data['post_type'] = __('Blog post', 'fitbody-theme');
        break;
    case 'page' :
        $data['post_type'] = __('Page', 'fitbody-theme');
        break;
    case 'event' :
        $data['post_type'] = __('Event', 'fitbody-theme');
        break;
    case 'product' :
        $data['post_type'] = __('Product', 'fitbody-theme');
        break;
}


?>

<div class="search-card">
    <div class="search-card__inner">
        <p class="search-card__type"><?php echo $data['post_type']; ?></p>

        <h2 class="search-card__title"><a href="<?php echo $data['url']; ?>" class="search-card__anchor"><?php echo $data['title']; ?></a></h2>

        <?php if(!empty($data['excerpt'])) : ?>
        <p class="search-card__excerpt"><?php echo $data['excerpt']; ?></p>
        <?php endif; ?>

    </div>
</div>
