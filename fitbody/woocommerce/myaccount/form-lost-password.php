<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.2
 */

defined( 'ABSPATH' ) || exit;
?>


<?php do_action( 'woocommerce_before_lost_password_form' ); ?>

<div class="password-reset">
	<form method="post" class="woocommerce-ResetPassword lost_reset_password">

		<h2 class="h3"><?php _e( 'Password recovery', 'woocommerce' ); ?></h2>
		<p><strong><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></strong></p><?php // @codingStandardsIgnoreLine ?>

		<div class="password-reset__username text-field">
			<input class="text-field__input" type="text" name="user_login" id="user_login" placeholder="<?php _e( 'Username or email', 'woocommerce' ); ?>" autocomplete="username" />
			<label for="user_login" class="text-field__placeholder"><?php _e( 'Username or email', 'woocommerce' ); ?></label>
		</div>

		<div class="clear"></div>

		<?php do_action( 'woocommerce_lostpassword_form' ); ?>

		<input type="hidden" name="wc_reset_password" value="true" />
		<button type="submit" class="button" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php _e( 'Reset password', 'woocommerce' ); ?></button>

		<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

	</form>
</div>

<?php do_action( 'woocommerce_after_lost_password_form' ); ?>