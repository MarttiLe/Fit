<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>


<?php do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="edit-profile">

	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<div class="account__block edit-profile__info">
			<p><strong><?php esc_html_e( 'Account info', 'fitbody-theme' ); ?></strong></p>
			<div class="text-field">
				<input type="text" class="text-field__input" name="account_first_name" id="account_first_name" placeholder="<?php esc_html_e( 'First name', 'woocommerce' ); ?>" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
				<label for="account_first_name" class="text-field__placeholder"><?php esc_html_e( 'First name', 'woocommerce' ); ?></label>
			</div>
			<div class="text-field">
				<input type="text" class="text-field__input" name="account_last_name" id="account_last_name" placeholder="<?php esc_html_e( 'Last name', 'woocommerce' ); ?>" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
				<label for="account_last_name" class="text-field__placeholder"><?php esc_html_e( 'Last name', 'woocommerce' ); ?></label>
			</div>
			<div class="text-field edit-profile__display-name">
				<input type="text" class="text-field__input" name="account_display_name" id="account_display_name" placeholder="<?php esc_html_e( 'Display name', 'woocommerce' ); ?>" value="<?php echo esc_attr( $user->display_name ); ?>" />
				<label for="account_display_name" class="text-field__placeholder"><?php esc_html_e( 'Display name', 'woocommerce' ); ?></label>
			</div>
			<div class="text-field">
				<input type="email" class="text-field__input" name="account_email" id="account_email" placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
				<label for="account_email" class="text-field__placeholder"><?php esc_html_e( 'Email address', 'woocommerce' ); ?></label>
			</div>
		</div>

		<div class="account__block edit-profile__password">
			<p><strong><?php esc_html_e( 'Password change', 'woocommerce' ); ?></strong></p>
			<div class="text-field">
				<input type="password" class="text-field__input" name="password_current" id="password_current" placeholder="<?php esc_html_e( 'Current password', 'fitbody-theme' ); ?>" autocomplete="off" />
				<label for="password_current" class="text-field__placeholder"><?php esc_html_e( 'Current password', 'fitbody-theme' ); ?></label>
			</div>
			<div class="text-field">
				<input type="password" class="text-field__input" name="password_1" id="password_1" placeholder="<?php esc_html_e( 'New password', 'fitbody-theme' ); ?>" autocomplete="off" />
				<label for="password_1" class="text-field__placeholder"><?php esc_html_e( 'New password', 'fitbody-theme' ); ?></label>
			</div>
			<div class="text-field">
				<input type="password" class="text-field__input" name="password_2" id="password_2" placeholder="<?php esc_html_e( 'Confirm new password', 'fitbody-theme' ); ?>" autocomplete="off" />
				<label for="password_2" class="text-field__placeholder"><?php esc_html_e( 'Confirm new password', 'fitbody-theme' ); ?></label>
			</div>
		</div>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<div class="loader-button">
			<button type="submit" class="loader-button__button button js-save-button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
			<div class="loader-button__loader loader js-save-loader"><span></span></div>
		</div>
		<input type="hidden" name="action" value="save_account_details" />

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>

</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>