<?php get_header(); ?>

    <div class="page-heading page-heading--centered">
        <div class="container">
            <div class="page-heading__inner">
                <div class="page-heading__breadcrumb">
                    <?php theme_breadcrumbs_list(); ?>
                </div>

                <h1 class="page-heading__title h2"><?php the_title(); ?></h1>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container container--md">
            <div class="page-content__inner">
                <div class="editor-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>