<?php

// Don't load it if you can't comment
if ( post_password_required() ) {
	return;
}

?>

<div class="comments">

	<?php if ( have_comments() ) : ?>

		<h3 class="comments__title"><?= __('Comments', 'fitbody-theme') ?> <span class="comments__amount">(<?= get_comments_number() ?>)</span></h3>

		<div class="comments__list">
			<?php
				wp_list_comments(array(
				'style'             => 'li',
				//'callback'          => '',
				'type'              => 'comment',
				'reply_text'        => __('Reply', 'fitbody-theme'),
				'page'              => '',
				'per_page'          => '',
				'reverse_top_level' => true,
				'reverse_children'  => false
			) );
			?>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="comments__pagination comments-pagination">
			<div class="comments-pagination__prev"><?php previous_comments_link( __( '&larr; Previous Comments', 'fitbody-theme' ) ); ?></div>
			<div class="comments-pagination__next"><?php next_comments_link( __( 'More Comments &rarr;', 'fitbody-theme' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() ) : ?>
			<p class="comments__closed"><?= __( 'Comments are closed.' , 'fitbody-theme' ) ?></p>
		<?php endif; ?>

	<?php endif; ?>

	<?php
		comment_form(array(
			'logged_in_as'	=> '',
			'title_reply'	=> __( 'Ask a question' , 'fitbody-theme' ),
			'label_submit'	=> __( 'Submit' , 'fitbody-theme' ),
			'class_submit'	=> 'button'
		));
	?>

</div>