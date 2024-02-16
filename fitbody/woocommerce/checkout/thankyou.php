<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$store_page_url = get_permalink(get_field('pages_store', 'options'));
$profile_page_url = get_permalink(get_field('pages_profile', 'options'));
?>

<div class="woocommerce-order order-thanks">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="alert-bar alert-bar--negative is-active">
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
			</div>

		<?php else : ?>

			<div class="order-thanks__content">
				<h2 class="order-thanks__title woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you! We have successfully received your order!', 'fitbody-theme' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<p>
					<?php echo __( "The details regarding your order have been sent to your e-mail", 'fitbody-theme' ); ?>.<br>
					<?php echo __( 'Your order number is ', 'fitbody-theme' ); ?> <strong>#<?php echo $order->get_order_number(); ?></strong>.
				</p>
			</div>

		<?php endif; ?>

	<?php else : ?>

		<div class="order-thanks__content">
			<h2 class="order-thanks__title woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you! We have successfully received your order!', 'fitbody-theme' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<p><?php echo __( "The details regarding your order have been sent to your e-mail", 'fitbody-theme' ); ?>.</p>
			<p><?php echo __( 'Your order number is ', 'fitbody-theme' ); ?> <strong>#<?php echo $order->get_order_number(); ?></strong>.</p>
		</div>

	<?php endif; ?>

	<div class="checkout-options">
		<div class="checkout-options__item"><a href="<?php echo $profile_page_url; ?>" class="button"><?php echo __( 'View profile', 'fitbody-theme' ); ?></a></div>
		<div class="checkout-options__item"><a href="<?php echo $store_page_url; ?>" class="button"><?php echo __( 'Continue shopping', 'fitbody-theme' ); ?></a></div>
	</div>

</div>
