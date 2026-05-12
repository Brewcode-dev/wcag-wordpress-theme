<?php
/**
 * Frontend accessibility widget.
 *
 * Renders an icon-only FAB toggle + dialog containing user-facing accessibility tools.
 * Markup is structured as cards in a 2-column grid (single column on mobile) so it
 * scans like a settings panel instead of a checkbox dump. Icons + visible labels.
 *
 * @package WCAG_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wcag_wp_render_accessibility_widget() {
	if ( ! get_theme_mod( 'wcag_wp_a11y_widget_enabled', true ) ) {
		return;
	}

	$position = get_theme_mod( 'wcag_wp_a11y_widget_position', 'bottom-right' );

	$options = array(
		'high-contrast'     => array( 'label' => __( 'High contrast', 'wcag-wp' ),       'icon' => 'contrast' ),
		'negative-contrast' => array( 'label' => __( 'Negative contrast', 'wcag-wp' ),   'icon' => 'invert' ),
		'grayscale'         => array( 'label' => __( 'Grayscale', 'wcag-wp' ),           'icon' => 'grayscale' ),
		'underline-links'   => array( 'label' => __( 'Underline links', 'wcag-wp' ),     'icon' => 'link' ),
		'highlight-focus'   => array( 'label' => __( 'Stronger focus', 'wcag-wp' ),      'icon' => 'focus' ),
		'readable-font'     => array( 'label' => __( 'Readable font', 'wcag-wp' ),       'icon' => 'font' ),
		'pause-motion'      => array( 'label' => __( 'Pause animations', 'wcag-wp' ),    'icon' => 'pause' ),
		'bigger-cursor'     => array( 'label' => __( 'Larger cursor', 'wcag-wp' ),       'icon' => 'cursor' ),
	);
	?>
	<div class="wcag-a11y wcag-a11y--<?php echo esc_attr( $position ); ?>"
		data-wcag-a11y-widget
		role="region"
		aria-label="<?php esc_attr_e( 'Accessibility tools', 'wcag-wp' ); ?>">

		<button type="button"
			class="wcag-a11y__fab"
			aria-expanded="false"
			aria-controls="wcag-a11y-panel"
			aria-label="<?php esc_attr_e( 'Open accessibility settings', 'wcag-wp' ); ?>">
			<span class="wcag-a11y__fab-icon" aria-hidden="true">
				<?php echo wcag_wp_a11y_icon( 'accessibility' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</span>
			<span class="wcag-a11y__fab-label"><?php esc_html_e( 'Accessibility', 'wcag-wp' ); ?></span>
		</button>

		<div class="wcag-a11y__backdrop" data-wcag-backdrop hidden></div>

		<div class="wcag-a11y__panel"
			id="wcag-a11y-panel"
			role="dialog"
			aria-modal="true"
			aria-labelledby="wcag-a11y-title"
			tabindex="-1"
			hidden>

			<header class="wcag-a11y__header">
				<div class="wcag-a11y__header-text">
					<h2 id="wcag-a11y-title" class="wcag-a11y__title">
						<?php esc_html_e( 'Accessibility', 'wcag-wp' ); ?>
					</h2>
					<p class="wcag-a11y__subtitle"><?php esc_html_e( 'Adjust the page to your needs.', 'wcag-wp' ); ?></p>
				</div>
				<button type="button" class="wcag-a11y__close" aria-label="<?php esc_attr_e( 'Close accessibility settings', 'wcag-wp' ); ?>">
					<?php echo wcag_wp_a11y_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</button>
			</header>

			<section class="wcag-a11y__section" aria-labelledby="wcag-a11y-text-title">
				<h3 id="wcag-a11y-text-title" class="wcag-a11y__section-title">
					<?php echo wcag_wp_a11y_icon( 'text' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php esc_html_e( 'Text size', 'wcag-wp' ); ?>
				</h3>
				<div class="wcag-a11y__stepper" role="group" aria-labelledby="wcag-a11y-text-title">
					<button type="button" class="wcag-a11y__step" data-wcag-action="font-decrease"
						aria-label="<?php esc_attr_e( 'Decrease text size', 'wcag-wp' ); ?>">
						<span aria-hidden="true">−</span>
					</button>
					<div class="wcag-a11y__step-meter" data-wcag-font-level role="status" aria-live="polite">100%</div>
					<button type="button" class="wcag-a11y__step" data-wcag-action="font-increase"
						aria-label="<?php esc_attr_e( 'Increase text size', 'wcag-wp' ); ?>">
						<span aria-hidden="true">+</span>
					</button>
					<button type="button" class="wcag-a11y__step wcag-a11y__step--reset" data-wcag-action="font-reset"
						aria-label="<?php esc_attr_e( 'Reset text size', 'wcag-wp' ); ?>">
						<?php echo wcag_wp_a11y_icon( 'reset' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
				</div>
			</section>

			<section class="wcag-a11y__section" aria-labelledby="wcag-a11y-display-title">
				<h3 id="wcag-a11y-display-title" class="wcag-a11y__section-title">
					<?php echo wcag_wp_a11y_icon( 'eye' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php esc_html_e( 'Display & motion', 'wcag-wp' ); ?>
				</h3>
				<div class="wcag-a11y__grid">
					<?php foreach ( $options as $key => $opt ) : ?>
						<label class="wcag-a11y__card">
							<input type="checkbox" data-wcag-toggle="<?php echo esc_attr( $key ); ?>" class="wcag-a11y__card-input" />
							<span class="wcag-a11y__card-icon" aria-hidden="true">
								<?php echo wcag_wp_a11y_icon( $opt['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</span>
							<span class="wcag-a11y__card-label"><?php echo esc_html( $opt['label'] ); ?></span>
							<span class="wcag-a11y__card-state" aria-hidden="true">
								<span class="wcag-a11y__card-state-on"><?php esc_html_e( 'On', 'wcag-wp' ); ?></span>
								<span class="wcag-a11y__card-state-off"><?php esc_html_e( 'Off', 'wcag-wp' ); ?></span>
							</span>
						</label>
					<?php endforeach; ?>
				</div>
			</section>

			<footer class="wcag-a11y__footer">
				<button type="button" class="wcag-a11y__reset" data-wcag-action="reset-all">
					<?php echo wcag_wp_a11y_icon( 'reset' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php esc_html_e( 'Reset all settings', 'wcag-wp' ); ?>
				</button>
				<p class="wcag-a11y__legal">
					<?php esc_html_e( 'Saved only in your browser. Tested against WCAG 2.1 AA.', 'wcag-wp' ); ?>
				</p>
			</footer>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'wcag_wp_render_accessibility_widget', 5 );

/**
 * Returns an inline SVG for the widget icon set.
 */
function wcag_wp_a11y_icon( $name ) {
	$icons = array(
		'accessibility' => '<svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" focusable="false" aria-hidden="true"><path d="M12 2a2 2 0 1 1 0 4 2 2 0 0 1 0-4Zm9 5v2h-6v2.6l2.6 9-1.9.7L13 13l-2.7 8.3-1.9-.7L11 11.6V9H5V7h16Z"/></svg>',
		'close'         => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" focusable="false" aria-hidden="true"><path d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4l-6.3 6.3-1.4-1.4L9.2 12 2.9 5.7l1.4-1.4L10.6 10.6l6.3-6.3z"/></svg>',
		'reset'         => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" focusable="false" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>',
		'text'          => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" focusable="false" aria-hidden="true"><path d="M5 4h14v3h-1V6h-5v13h2v1H9v-1h2V6H6v1H5z"/></svg>',
		'eye'           => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" focusable="false" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>',
		'contrast'      => '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" focusable="false" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2Zm0 18V4a8 8 0 0 1 0 16Z"/></svg>',
		'invert'        => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" focusable="false" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 3v18" stroke-linecap="round"/></svg>',
		'grayscale'     => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" focusable="false" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>',
		'link'          => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false" aria-hidden="true"><path d="M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/><path d="M14 10a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/></svg>',
		'focus'         => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" focusable="false" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="3" stroke-dasharray="4 3"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="none"/></svg>',
		'font'          => '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" focusable="false" aria-hidden="true"><path d="M9.3 5h5.4l4.6 14h-2.5l-1.2-3.6H8.4L7.2 19H4.7L9.3 5Zm-.2 9h5.8l-2.9-8.7L9.1 14Z"/></svg>',
		'pause'         => '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" focusable="false" aria-hidden="true"><rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/></svg>',
		'cursor'        => '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" focusable="false" aria-hidden="true"><path d="M5 3l14 8-6 2-2 6L5 3Z"/></svg>',
	);
	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}
