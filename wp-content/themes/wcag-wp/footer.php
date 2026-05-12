<?php
/**
 * Site footer.
 *
 * @package WCAG_WP
 */
?>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
				<div class="site-footer__widgets">
					<?php dynamic_sidebar( 'sidebar-footer' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer', 'wcag-wp' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'depth'          => 1,
							'container'      => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Social media', 'wcag-wp' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'social',
							'menu_id'        => 'social-menu',
							'depth'          => 1,
							'container'      => false,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<p class="site-info">
				<?php
				printf(
					/* translators: 1: year, 2: site name. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'wcag-wp' ),
					esc_html( date_i18n( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
				<span class="site-info__sep" aria-hidden="true">·</span>
				<a href="#page"><?php esc_html_e( 'Back to top', 'wcag-wp' ); ?></a>
			</p>
		</footer>
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
