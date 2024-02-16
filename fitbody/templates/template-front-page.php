<?php
/**
* Template Name: Front page
*/
?>

<?php get_header(); ?>


    <?php
        if(get_field('display_block_hero')) {
            $block_attr = [];
            get_template_part('templates/blocks/hero', null, $block_attr);
        }
    ?>


    <?php
        if(get_field('display_block_about')) {
            $block_attr = [];
            get_template_part('templates/blocks/about', null, $block_attr);
        }
    ?>
    

    <?php
        if(get_field('display_block_programs_preview')) {
            $block_attr = [];
            get_template_part('templates/blocks/programs-preview', null, $block_attr);
        }
    ?>


    <?php
        if(get_field('display_block_newsletter')) {
            $block_attr = [];
            get_template_part('templates/blocks/newsletter', null, $block_attr);
        }
    ?>


    <?php
        if(get_field('display_block_testimonials')) {
            $block_attr = [];
            get_template_part('templates/blocks/testimonials', null, $block_attr);
        }
    ?>


    <?php
        if(get_field('display_block_blog_preview')) {
            $block_attr = [];
            get_template_part('templates/blocks/blog-preview', null, $block_attr);
        }
    ?>


    <?php
        if(get_field('display_block_shop_preview')) {
            $block_attr = [];
            get_template_part('templates/blocks/shop-preview', null, $block_attr);
        }
    ?>


    <?php
        if(get_field('display_block_trainer')) {
            $block_attr = [];
            get_template_part('templates/blocks/trainer', null, $block_attr);
        }
    ?>


    <?php
        if(get_field('display_block_instagram')) {
            $block_attr = [];
            get_template_part('templates/blocks/instagram', null, $block_attr);
        }
    ?>

<?php get_footer(); ?>