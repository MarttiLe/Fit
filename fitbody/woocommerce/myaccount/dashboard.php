<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if(is_user_logged_in()) {
	$user_id = get_current_user_id();
	$user_programs = theme_get_user_programs($user_id);
	$user_program_ids = [];
	if(!empty($user_programs)) :
		foreach($user_programs as $key => $program) {
			array_push($user_program_ids, $key);
		}
	endif;
	if(empty($user_program_ids)) {
		$user_program_ids = [0];
	}
	$programs = new WP_Query([
        'posts_per_page'    => -1,
        'post_type'         => 'program',
        'status'            => 'publish',
		'post__in'			=> $user_program_ids,
        'post_parent'       => 0,
        'suppress_filters'  => false
    ]);

	if(!empty($programs)) {
		$programs = $programs->posts;
	}

	$programs_page_url = get_permalink(get_field('pages_programs', 'options'));
}

?>

<?php if(!empty($programs)) : ?>

	<div class="account__programs">
		<?php if(!empty($programs)) : ?>
		<ul class="programs-list programs-list--account">
			<?php foreach($programs as $key => $program) : ?>
				<li class="programs-list__item">
					<?php
						$card_attr = [
							'item_classes'		=> 'program-card--bordered',
							'post_id'  			=> $program->ID,
							'is_owned'			=> true,
							'ownership_dates' 	=> theme_get_program_ownership_dates($program->ID, false, $user_programs)
						];

						get_template_part('templates/components/card-program', null, $card_attr);
					?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>

<?php else : ?>

	<div class="account__no-programs">
		<p><?php echo __( 'You have not purchased any programs yet.', 'fitbody-theme' ); ?></p>
		<a href="<?php echo $programs_page_url; ?>" class="button"><?php echo __( 'Discover our programs', 'fitbody-theme' ); ?></a>
	</div>

<?php endif; ?>