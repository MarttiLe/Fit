		</main>

		<?php
			$company_legal_name = get_field('company_legal_name', 'option');
		?>

		<!-- Site footer -->
		<footer class="footer">
			<div class="footer__container container">

				<div class="footer__inner has-white-links">
					<div class="footer__copyright">
						<?php echo __( 'All rights reserved', 'fitbody-theme' ); ?> &copy; <?php echo date('Y'); ?> <?php echo $company_legal_name; ?>
					</div>

					<nav class="footer__nav footer-nav">
						<?php
							wp_nav_menu([
								'menu' => __( 'Footer navigation', 'fitbody-theme' ),
								'container' => false,
								'menu_class' => 'footer-nav__items menu',
								'theme_location' => 'footer-nav',
							]);
						?>
					</nav>
				</div>
				
			</div>
		</footer>

		<!-- WP footer -->
		<?php wp_footer(); ?>

	</body>

</html>