<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>


<div class="container container--md">
	<div class="page-content__inner">

		<div class="account__login" data-sal="fade">

			<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

			<div class="grid grid--gutters-lg" id="customer_login">
				<div class="grid__col grid__col--2 grid__col--md">

			<?php endif; ?>

				<div class="login">
					<h2 class="login__title title h3"><?php _e( 'Log in', 'fitbody-theme' ); ?></h2>

					<form class="woocommerce-form woocommerce-form-login" method="post">

						<?php do_action( 'woocommerce_login_form_start' ); ?>

						<div class="login__username text-field">
							<input type="text" class="text-field__input" name="username" id="username" autocomplete="username" placeholder="<?php _e( 'E-mail or username', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
							<label for="username" class="text-field__placeholder"><?php _e( 'E-mail or username', 'woocommerce' ); ?></label>
						</div>

						<div class="login__password text-field">
							<input class="text-field__input" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php _e( 'Password', 'woocommerce' ); ?>" />
							<label for="password" class="text-field__placeholder"><?php _e( 'Password', 'woocommerce' ); ?></label>
						</div>

						<?php do_action( 'woocommerce_login_form' ); ?>

						<div class="login__options">
							<div class="login__remember-me input-group input-group--checkbox">
								<input class="input-group__input" name="rememberme" type="checkbox" id="rememberme" value="forever" />
								<label for="rememberme" class="input-group__label"><?php _e( 'Remember me', 'woocommerce' ); ?></label>
								<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
							</div>

							<div class="login__lost-pw">
								<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
							</div>
						</div>

						<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php _e( 'Log in', 'woocommerce' ); ?></button>

						<?php do_action( 'woocommerce_login_form_end' ); ?>

					</form>
				</div>

			<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

				</div>

				<div class="grid__col grid__col--2 grid__col--md">

					<div class="register">
						<h2 class="register__title title h3"><?php _e( 'Register', 'fitbody-theme' ); ?></h2>

						<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

							<?php do_action( 'woocommerce_register_form_start' ); ?>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

								<div class="register__username text-field">
									<input type="text" class="text-field__input" name="username" id="reg_username" autocomplete="username" placeholder="<?php _e( 'Username', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
									<label for="reg_username" class="text-field__placeholder"><?php _e( 'Username', 'woocommerce' ); ?></label>
								</div>

							<?php endif; ?>

							<div class="register__email text-field">
								<input type="email" class="text-field__input" name="email" id="reg_email" autocomplete="email" placeholder="<?php _e( 'E-mail address', 'woocommerce' ); ?>" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
								<label for="reg_email" class="text-field__placeholder"><?php _e( 'E-mail address', 'woocommerce' ); ?></label>
							</div>

							<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

								<div class="register__password text-field">
									<input type="password" class="text-field__input" name="password" id="reg_password" placeholder="<?php _e( 'Password', 'woocommerce' ); ?>" autocomplete="new-password" />
									<label for="reg_password" class="text-field__placeholder"><?php _e( 'Password', 'woocommerce' ); ?></label>
								</div>

							<?php else : ?>

								<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

							<?php endif; ?>

							<?php do_action( 'woocommerce_register_form' ); ?>

							<p class="woocommerce-form-row form-row">
								<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
								<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
							</p>

							<?php do_action( 'woocommerce_register_form_end' ); ?>

						</form>
					</div>

				</div>

			</div>
			<?php endif; ?>

			<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

		</div>

	</div>
</div>