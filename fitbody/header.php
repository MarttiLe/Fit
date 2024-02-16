<!doctype html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<!-- Site meta -->
		<?php // Title gets automatically loaded by wp_head(); ?>
		<meta name="Keywords" content="Fitbody, Egle, Nabi, Treeningud, EgleFit OÜ">

		<!-- Mobile meta -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<!-- Favicons and themes -->
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri() . '/assets/favicons/apple-touch-icon.png'; ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri() . '/assets/favicons/favicon-32x32.png'; ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri() . '/assets/favicons/favicon-16x16.png'; ?>">
		<link rel="manifest" href="<?php echo get_template_directory_uri() . '/assets/favicons/site.webmanifest'; ?>">
		<link rel="mask-icon" href="<?php echo get_template_directory_uri() . '/assets/favicons/safari-pinned-tab.svg'; ?>" color="#ffffff">
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri() . '/assets/favicons/favicon.ico'; ?>">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-config" content="<?php echo get_template_directory_uri() . '/assets/favicons/browserconfig.xml'; ?>">
		<meta name="theme-color" content="#ffffff">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php /*
		<!-- Facebook meta -->
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo get_bloginfo('name') . ' | ' . get_bloginfo('description'); ?>" />
		<meta property="og:image" content="<?php echo get_template_directory_uri() . '/assets/images/og-image.png'; ?>" />

		<!-- Twitter meta -->
		<meta name="twitter:title" content="<?php echo get_bloginfo('name') . ' | ' . get_bloginfo('description'); ?>" />
		<meta name="twitter:image:src" content="<?php echo get_template_directory_uri() . '/assets/images/og-image.png'; ?>" />
		*/ ?>

		<?php if ( function_exists('cn_cookies_accepted') && cn_cookies_accepted() ) : ?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-FYLYCNRJ5T"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-FYLYCNRJ5T');
		</script>
		<?php endif; ?>

		<!-- WordPress head -->
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<?php
			$socials_fb = get_field('socials_fb', 'options');
			$socials_ig = get_field('socials_ig', 'options');
			$login_url = get_permalink(get_field('pages_login', 'options'));
			$profile_url = get_permalink(get_field('pages_profile', 'options'));
			$cart_url = wc_get_cart_url();
			global $woocommerce;
			$cart_product_amount = $woocommerce->cart->cart_contents_count;
		?>
		<!-- Site header -->
		<header class="header">
			<div class="header__container">
				<div class="header__inner">

					<h1 class="header__logo header-logo">
						<a href="<?php echo home_url(); ?>" class="header-logo__anchor"><img src="<?php echo get_template_directory_uri() . '/assets/images/logo.svg'; ?>" title="<?php echo __( 'Return to Fitbody front page', 'fitbody-theme' ); ?>" alt="EgleFit OÜ" class="header-logo__img" /></a>
					</h1>

					<div class="header__main">
						<div class="header__languages"><?php echo theme_language_switcher(); ?></div>

						<div class="header__content">
							<nav class="header__nav header-nav">
								<?php
									wp_nav_menu([
										'menu' => __( 'Main navigation', 'fitbody-theme' ),
										'container' => false,
										'menu_class' => 'header-nav__items menu',
										'theme_location' => 'header-nav',
									]);
								?>
							</nav>

							
							<div class="search-icon" onclick="toggleSearchBar()">
								
								</div>

								<div class="search-bar-container" id="searchBarContainer">
								<form action="<?php echo home_url(); ?>" method="get">
									<input type="text" name="s" placeholder="Sisesta märksõna...">
									<input type="submit" value="Otsi" class="submit-btn">
								</form>
								</div>

							<?php if(!empty($socials_fb) || !empty($socials_ig)) : ?>
							<div class="header__socials">
								<ul class="header__socials socials-list socials-list--nowrap">
									<?php if(!empty($socials_fb)) : ?>
									<li class="socials-list__item"><a href="<?php echo $socials_fb; ?>" class="socials-list__anchor" title="<?php _e( 'Facebook', 'fitbody-theme' ); ?>" target="_blank"><?php icon_svg('socials-fb'); ?><span class="screen-reader-text"><?php _e( 'Facebook', 'fitbody-theme' ); ?></span></a></li>
									<?php endif; ?>
									<?php if(!empty($socials_ig)) : ?>
									<li class="socials-list__item"><a href="<?php echo $socials_ig; ?>" class="socials-list__anchor" title="<?php _e( 'Instagram', 'fitbody-theme' ); ?>" target="_blank"><?php icon_svg('socials-ig'); ?><span class="screen-reader-text"><?php _e( 'Instagram', 'fitbody-theme' ); ?></span></a></li>
									<?php endif; ?>
								</ul>
							</div>
							<?php endif; ?>


							<div class="header__account">
								<?php if(!is_user_logged_in()) : ?>
								<a href="<?php echo $login_url; ?>" class="header__account-button button button--skewed button--nowrap"><span class="button__text"><?php _e( 'Enter/Register', 'fitbody-theme' ); ?></span></a>
								<?php else : ?>
								<a href="<?php echo $profile_url; ?>" class="header__account-button button button--skewed button--nowrap"><span class="button__text"><?php _e( 'My profile', 'fitbody-theme' ); ?></span></a>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<?php if($cart_product_amount > 0) : ?>
					<div class="header__cart" title="<?php echo __( 'View your cart', 'fitbody' ); ?>">
						<a href="<?php echo $cart_url; ?>" class="sticky-cart">
							<div class="sticky-cart__icon-wrap"><?php icon_svg('basket', 'sticky-cart__icon'); ?></div>
							<div class="sticky-cart__inner">
								<p class="sticky-cart__message"><span class="sticky-cart__amount"><?php echo $cart_product_amount; ?></span> <span class="sticky-cart__text"><?php echo __( 'product(s) in cart', 'fitbody' ); ?></span></p>
							</div>
						</a>
					</div>
					<?php endif; ?>

					<div class="header__toggle">
						<button class="mobile-menu-toggle js-mobile-menu-toggle">
							<span class="mobile-menu-toggle__icon">&nbsp;</span>
						</button>
					</div>
				</div>
			</div>
		</header>

		<!-- Site content -->
		<main class="main">
			<div class="header-offset">&nbsp;</div>
