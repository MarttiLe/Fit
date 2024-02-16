<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>


<?php
	$dashboard_url = wc_get_page_permalink('myaccount');
	$edit_account_url = wc_get_account_endpoint_url('edit-account');
	$orders_url = wc_get_account_endpoint_url('orders');

	$option_1 = $option_2 = $option_3 = false;
	if(is_wc_endpoint_url('edit-account')) {
		$option_2 = true;
	} else if(is_wc_endpoint_url('orders')) {
		$option_3 = true;
	} else {
		$option_1 = true;
	}
?>


<div class="account__nav">
	<ul class="nav-tabs nav-tabs--margin-bottom-lg">
		<li class="nav-tabs__item"><a href="<?php echo $dashboard_url; ?>" class="nav-tabs__anchor<?php if($option_1) { echo ' is-active'; } ?>"><?php echo __( 'My programs', 'fitbody-theme' ); ?></a></li>
		<li class="nav-tabs__item"><a href="<?php echo $edit_account_url; ?>" class="nav-tabs__anchor<?php if($option_2) { echo ' is-active'; } ?>"><?php echo __( 'Edit details', 'fitbody-theme' ); ?></a></li>
		<li class="nav-tabs__item"><a href="<?php echo $orders_url; ?>" class="nav-tabs__anchor<?php if($option_3) { echo ' is-active'; } ?>"><?php echo __( 'Order history', 'fitbody-theme' ); ?></a></li>
	</ul>
</div>


<div class="account__mobile-nav mobile-account-nav">
	<?php do_action( 'woocommerce_before_account_navigation' ); ?>

	<div class="select">
		<select class="select__input mobile-account-nav__select mobile-select-nav js-mobile-select-nav">
			<option value="<?php echo $dashboard_url; ?>"<?php if($option_1) { echo ' selected'; } ?>><?php _e( 'My programs', 'fitbody-theme' ); ?></option>
			<option value="<?php echo $edit_account_url; ?>"<?php if($option_2) { echo ' selected'; } ?>><?php _e( 'Edit details', 'fitbody-theme' ); ?></option>
			<option value="<?php echo $orders_url; ?>"<?php if($option_3) { echo ' selected'; } ?>><?php _e( 'Order history', 'fitbody-theme' ); ?></option>
		</select>
	</div>

	<?php do_action( 'woocommerce_after_account_navigation' ); ?>
</div>