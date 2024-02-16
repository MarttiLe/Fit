<?php get_header(); ?>

	<div class="page-content">
        <div class="page-content__container container">

			<?php the_archive_title( '<h1 class="page-content__title">', '</h1>' ); ?>

            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <div class="editor-content">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>

        </div>
    </div>

<?php get_footer(); ?>